<?php

ini_set("display_errors", true);

// définir un fuseau horaire
if (function_exists("date_default_timezone_set")) {
    date_default_timezone_set("Europe/Paris");
}


$dirname = dirname(__FILE__);
$configFile = $dirname."/config.php";
if (is_file($configFile)) {
    require $configFile;
}
require $dirname."/lib/lbc.php";
require $dirname."/lib/Http/Client/Curl.php";
require $dirname."/ConfigManager.php";

// initialisation des constantes.
if (!defined("MULTI_USER")) {
    define("MULTI_USER", false);
}
if (!defined("PROXY_IP")) {
    define("PROXY_IP", "");
}
if (!defined("PROXY_PORT")) {
    define("PROXY_PORT", "");
}
if (!defined("CHECK_START")) {
    define("CHECK_START", 7);
}
if (!defined("CHECK_END")) {
    define("CHECK_END", 24);
}

// initialise le client HTTP.
$client = new HttpClientCurl();
if (defined("USER_AGENT")) {
    $client->setUserAgent(USER_AGENT);
}
if (PROXY_IP) {
    $client->setProxyIp(PROXY_IP);
    if (PROXY_PORT) {
        $client->setProxyPort(PROXY_PORT);
    }
}

### Fonctions ###

/**
 * Test la connexion HTTP.
 * @param HttpClientAbstract $client
 * @return string|boolean
 */
function testHTTPConnection(HttpClientAbstract $client) {
    // teste la connexion
    $client->setDownloadBody(false);
    $client->request("http://www.google.fr");
    if (200 == $client->getRespondCode()) {
        // le proxy semble fonctionner.
        $client->request("http://www.leboncoin.fr");
        if (200 != $client->getRespondCode()) {
            return false;
        }
    }
    return true;
}