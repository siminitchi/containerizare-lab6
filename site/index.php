<?php
$host = 'database_lab6';
$user = 'root';
$pass = 'root';
$db = 'testdb';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
die("Eroare la conectare: " . $conn->connect_error);
} else {
echo "<h1>Bine ați venit în Docker Compose!</h1>";
echo "<p>Conectarea la baza de date a fost realizată cu succes.</p>";
}
?>