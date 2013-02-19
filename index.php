<?php
/**
 * Code minimalisme de d'envoi d'alerte mail pour Leboncoin.fr
 * @version 1.0
 */
ini_set("display_errors", true);

$dirname = dirname(__FILE__);
require $dirname."/config.php";
require $dirname."/lib/lbc.php";
require $dirname."/ConfigManager.php";

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
$fp = @fopen("http://www.leboncoin.fr", "r");
if (!$fp || false === fgetc($fp)) {
    $error = "Cet hébergement ne semble pas permettre la récupération d'information. distantes. L'application ne fonctionnera pas.";
} else {
    fclose($fp);
}

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
        <?php if (isset($error)) : ?>
        <p style="color: #EF0000; font-weight: bold;"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php echo $content; ?>
        <footer>
            <a href="https://github.com/Blount/LBCMail/issues">Rapporter un bug</a>
            | Support : <a href="http://alerte.ilatumi.org/forum">Forum</a>
        </footer>
    </body>
</html>
