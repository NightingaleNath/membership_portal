<?php
function loadLanguage($locale) {
    $file = __DIR__ . "/locales/$locale.php";
    if (file_exists($file)) {
        return include($file);
    }
    return include(__DIR__ . "/locales/en.php");
}

// Load the user's locale
$locale = isset($_SESSION['locale']) ? $_SESSION['locale'] : 'en';
$translations = loadLanguage($locale);
