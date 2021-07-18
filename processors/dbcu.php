<?php

use Dotenv\Dotenv;

require 'C:\XAMPP\htdocs\Finalsproject\vendor\autoload.php';
// DBCU - Database Controller Unit
// Constants
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$serverName = $_ENV['SERVER_NAME'];
$dbUsername = $_ENV['DB_USERNAME'];
$dbPassword = $_ENV['DB_PASSWORD'];
$dbName = $_ENV['DB_NAME'];

$connection = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);

// Connection check
if (!$connection) {
    echo $serverName, $dbUsername, $dbPassword, $dbName;
    die("Connection failed: " . mysqli_connect_error());
}