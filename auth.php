<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session only if it hasn't been started
}

// Check if the user is logged in and set the username
if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['username']; // Adjust if you store the username differently
} else {
    $username = null; // Set to null if not logged in
}
