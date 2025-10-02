<?php
session_start();

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['user_type']);
}

// Check if user is a lawyer
function isLawyer() {
    return isLoggedIn() && $_SESSION['user_type'] === 'lawyer';
}

// Check if user is a client
function isClient() {
    return isLoggedIn() && $_SESSION['user_type'] === 'client';
}

// Check if user is an admin
function isAdmin() {
    return isLoggedIn() && $_SESSION['user_type'] === 'admin';
}

// Redirect to login if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

// Redirect to login if not a lawyer
function requireLawyer() {
    if (!isLawyer()) {
        header('Location: login.php');
        exit();
    }
}

// Redirect to login if not a client
function requireClient() {
    if (!isClient()) {
        header('Location: login.php');
        exit();
    }
}

// Redirect to login if not an admin
function requireAdmin() {
    if (!isAdmin()) {
        header('Location: login.php');
        exit();
    }
}

// Logout function
function logout() {
    session_destroy();
    header('Location: login.php');
    exit();
}
?>
