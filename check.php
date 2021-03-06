<?php
$key = "";

$dirname = dirname(__FILE__);
require $dirname."/bootstrap.php";

if ($key && (!isset($_GET["key"]) || $_GET["key"] != $key)) {
    return;
}

$checkStart = CHECK_START > 23?0:CHECK_START;
$checkEnd = !CHECK_END?24:CHECK_END;
$hour = (int)date("G");
if ($hour < $checkStart || $hour >= $checkEnd) {
    return;
}

if (!testHTTPConnection($client)) {
    // impossible d'effectuer une connexion.
    return;
}

// Le fichier lock permet d'empêcher le fichier check.php d'être
// lancé plus d'une fois en même temps.
$lock_filename = $dirname."/configs/.lock";
if (is_file($lock_filename)) {
    $currentTime = (int) file_get_contents($lock_filename);
    if ((time() - $currentTime) < (10 * 60)) {
        return;
    }
    // si le fichier lock existe depuis plus de 10mins
    // il y a peut-être eu une erreur lors de la dernière exécution.
    // on ignore alors son existance.
}
file_put_contents($lock_filename, time());

function mail_utf8($to, $subject = '(No subject)', $message = '')
{
    $subject = "=?UTF-8?B?".base64_encode($subject)."?=";

    $headers = "MIME-Version: 1.0" . "\r\n" .
            "Content-type: text/html; charset=UTF-8" . "\r\n";

    return mail($to, $subject, $message, $headers);
}

$client->setDownloadBody(true);

$files = scandir(dirname(__FILE__)."/configs");
foreach ($files AS $file) {
    if (false === strpos($file, ".csv")) {
        continue;
    }
    ConfigManager::setConfigName(str_replace(".csv", "", $file));
    $alerts = ConfigManager::getAlerts();
    if (count($alerts) == 0) {
        continue;
    }
    foreach ($alerts AS $i => $alert) {
        $currentTime = time();
        if (!isset($alert->time_updated)) {
            $alert->time_updated = 0;
        }
        if (((int)$alert->time_updated + (int)$alert->interval*60) > $currentTime
            || $alert->suspend) {
            continue;
        }
        $alert->time_updated = $currentTime;
        if (!$content = $client->request($alert->url)) {
            error_log("Curl Error : ".$client->getError());
            continue;
        }
        $ads = Lbc_Parser::process($content, array(
            "price_min" => $alert->price_min,
            "price_max" => $alert->price_max,
            "cities" => $alert->cities,
            "price_strict" => (bool)$alert->price_strict
        ));
        if (count($ads) == 0) {
            ConfigManager::saveAlert($alert);
            continue;
        }
        $newAds = array();
        $time_last_ad = (int)$alert->time_last_ad;
        foreach ($ads AS $ad) {
            if ($time_last_ad < $ad->getDate()) {
                $newAds[] = require $dirname."/views/mail-ad.phtml";
                if ($alert->time_last_ad < $ad->getDate()) {
                    $alert->time_last_ad = $ad->getDate();
                }
            }
        }
        if ($newAds) {
            $subject = "Alert LeBonCoin : ".$alert->title;
            $message = '<h2>Alerte générée le '.date("d/m/Y H:i", $currentTime).'</h2>
                <p>Liste des nouvelles annonces :</p><hr /><br />'.
                implode("<br /><hr /><br />", $newAds).'<hr /><br />';
            mail_utf8($alert->email, $subject, $message);
        }
        ConfigManager::saveAlert($alert);
    }
}

if (is_file($lock_filename)) {
    unlink($lock_filename);
}
