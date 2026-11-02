<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "blood_bank";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("");
?>