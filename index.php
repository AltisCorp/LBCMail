<?php
/**
 * Code minimalisme de d'envoi d'alerte mail pour Leboncoin.fr
 * @version 1.0
 */

require dirname(__FILE__)."/bootstrap.php";

if (!empty($_SERVER["PHP_AUTH_USER"])) {
    ConfigManager::setConfigName($_SERVER["PHP_AUTH_USER"]);
} elseif (MULTI_USER) {
    echo "Accès non autorisé.";
    exit;
}

$view = "list-alerts";
if (isset($_GET["a"])) {
    $view = $_GET["a"];
}
$view .= ".phtml";

ob_start();
require $dirname."/views/".$view;
$content = ob_get_clean();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Alerte mail pour Leboncoin.fr</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="styles.css" />
    </head>
    <body>
        <?php if (!testHTTPConnection($client)) : ?>
        <div style="color: #EF0000; font-weight: bold;">
            <p>Erreur lors du test de connexion :</p>
            <p>Message retourné : <?php echo ($error = $client->getError())?$error:"aucun"; ?>.</p>
            <ul>
                <li>Vérifiez que votre hébergement permet des connexions distantes.</li>
                <?php if (PROXY_IP && PROXY_PORT) : ?>
                <li>Vérifiez si votre proxy fonctionne correctement.</li>
                <?php endif; ?>
            </ul>
        </div>
        <?php endif; ?>
        <?php echo $content; ?>
        <footer>
            Version <?php echo require $dirname."/version.php"; ?>
            | <a href="https://github.com/Blount/LBCMail/issues">Rapporter un bug</a>
            | Support : <a href="http://alerte.ilatumi.org/forum">Forum</a>
        </footer>
    </body>
</html>
