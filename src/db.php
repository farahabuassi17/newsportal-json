<?php
$host = "db";      // اسم service
$user = "news_user";
$pass = "news_pass";
$db = "news_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>