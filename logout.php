<?php
session_start();

// Save the locale session variable
$locale = isset($_SESSION['locale']) ? $_SESSION['locale'] : null;

// Destroy all session variables
session_unset();
session_destroy();

// Start a new session and restore the locale session variable
session_start();
if ($locale) {
    $_SESSION['locale'] = $locale;
}

// Redirect to the login page
header("Location: index.php");
exit;
?>
