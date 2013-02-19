<?php

// configuration générale.

/**
 * Définir à true si vous souhaitez activer l'utilisation
 * multi utilisateur.
 */
define("MULTI_USER", false); // valeur true ou false


// on affiche les erreurs PHP
ini_set("display_errors", true);


// définir un fuseau horaire
if (function_exists("date_default_timezone_set")) {
    date_default_timezone_set("Europe/Paris");
}
