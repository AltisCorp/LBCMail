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


// initialise le client HTTP.
$client = new HttpClientCurl();
if (defined("USER_AGENT")) {
    $client->setUserAgent(USER_AGENT);
}
if (defined("PROXY_IP") && PROXY_IP) {
    $client->setProxyIp(PROXY_IP);
    if (defined("PROXY_PORT") && PROXY_PORT) {
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