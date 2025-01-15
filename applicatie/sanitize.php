<?php

require_once 'db_connectie.php';

function sanitize($unclean)
{
    // Verwijder alle HTML- en PHP-tags
    $cleaner = strip_tags((string)$unclean);

    // Converteer HTML-entiteiten naar hun corresponderende karakters
    $cleaner = html_entity_decode($cleaner, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    // Vervang specifieke HTML-entiteiten zoals &nbsp; door een reguliere spatie
    $cleaner = str_replace('&nbsp;', '.', $cleaner);

    // Verwijder overtollige witruimte aan het begin en einde
    $cleaner = trim($cleaner);

    // Verwijder dubbele witruimtes
    $cleaner = preg_replace('/\s+/', ' ', $cleaner);
    $cleaner = preg_replace('/\s+/', ' ', $cleaner);


    return $cleaner;
}