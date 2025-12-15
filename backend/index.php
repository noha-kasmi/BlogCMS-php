<?php
$host = "localhost";
$dbname = "blogcms";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur DB: " . $e->getMessage());
}

$stmt = $pdo->query("SELECT * FROM article WHERE statut_article = 'publié' ORDER BY date_cre_article DESC");
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>BlogCMS - Articles</title>
</head>
<body>
<h1>Liste des articles publiés</h1>
<?php foreach($articles as $article): ?>
    <h2><?= htmlspecialchars($article['titre']) ?></h2>
    <p><?= nl2br(htmlspecialchars($article['contenu_article'])) ?></p>
    <small>Publié le <?= $article['date_cre_article'] ?></small>
    <hr>
<?php endforeach; ?>
</body>
</html>
