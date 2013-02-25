<?php

// configuration générale.

/**
 * Définir à true si vous souhaitez activer l'utilisation
 * multi utilisateur.
 */
define("MULTI_USER", false); // valeur true ou false


define("USER_AGENT", "Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.2.6) Gecko/20100628 Ubuntu/10.04 (lucid) Firefox/3.6.6");


#
# Plage de fonctionnement.
# Défaut : de 7h à 24h.
###########################
define("CHECK_START", 7);
define("CHECK_END", 24);


/**
 * PROXY
 */
// adresse du proxy
define("PROXY_IP", "");
// port du proxy
define("PROXY_PORT", 0);


// on affiche les erreurs PHP
ini_set("display_errors", true);


// définir un fuseau horaire
if (function_exists("date_default_timezone_set")) {
    date_default_timezone_set("Europe/Paris");
}
