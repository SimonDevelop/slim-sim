<?php
// Init session for tests
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Autoload
require dirname(__DIR__) ."/vendor/autoload.php";
