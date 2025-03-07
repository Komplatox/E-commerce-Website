<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "db_name";

$connect = mysqli_connect($host, $username, $password, $dbname);
if (!$connect) {
    die("mysqli connection failed: " . mysqli_connect_error());
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "PDO connection failed: " . $e->getMessage();
    exit;
}
?>
