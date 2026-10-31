<?php
// Force session startup before anything else
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enable strict error reporting to find hidden issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$username = "root";
$password = "";
$dbname = "food_ordering_db";

// Handle custom port connection fallback automatically
$conn = @new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    // If standard port fails, check fallback port 3307
    $conn = new mysqli($host, $username, $password, $dbname, 3307);
    if ($conn->connect_error) {
        die("Database Connection Failed completely: " . $conn->connect_error);
    }
}
?>