<?php
$host = "localhost";
$dbname = "blogcms"; // Nom de ta base
$user = "root";
$pass = "";

try {
    // Création de l'objet PDO
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $user,
        $pass
    );
    // Mode d'erreur : Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
