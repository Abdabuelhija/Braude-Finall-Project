<?php
// Db credentials
$host = 'localhost';
$db = 'newdatabase';
$user = 'root';
$pass = 'Abd2812';
$charset = 'utf8mb4';

// Set up PDO connection
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);

// Get the date from the request
$date = $_GET['date'];

// Perform the SQL query
$stmt = $pdo->prepare('SELECT shift.Date, shift.WorkerID, shift.ShiftHours, workers.firstname, workers.lastname FROM shift JOIN workers ON shift.WorkerID = workers.ID WHERE Date = ?');
$stmt->execute([$date]);

// Fetch the results and send them as a JSON response
$results = $stmt->fetchAll();
echo json_encode($results);
?>