<?php
// Db credentials
$host = 'localhost';
$db = 'newdatabase';
$user = 'root';
$pass = 'Abd2812';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);

// Get the worker ID and date from the request
$workerId = $_POST['workerId'];
$date = $_POST['date'];

// Perform the SQL query to delete the worker from the shift
$stmt = $pdo->prepare('DELETE FROM shift WHERE WorkerID = ? AND Date = ?');
$stmt->execute([$workerId, $date]);

// Check if the deletion was successful
if ($stmt->rowCount() > 0) {
    echo 'success';
} else {
    echo 'error';
}
?>
