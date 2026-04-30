<?php
/**
 * SumoMedia.in - Admin Entry Point
 * Redirects to the appropriate admin page based on login status.
 */

session_start();

if (!empty($_SESSION['admin_logged_in'])) {
    // If logged in, take them to the Dashboard
    header('Location: dashboard.php');
} else {
    // If not logged in, take them to the login page
    header('Location: login.php');
}
exit;
