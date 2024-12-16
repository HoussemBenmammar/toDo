<?php
// database.php
$host = 'localhost';
$dbname = 'toDo';
$username = 'root'; // Remplacez par votre nom d'utilisateur MySQL
$password = '';     // Remplacez par votre mot de passe MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>