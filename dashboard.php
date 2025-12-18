<?php
require_once __DIR__ . '/config/db.php';

$page = $_GET['page'] ?? 'articles';

/* ===== ARTICLES ===== */
if ($page === 'articles') {
    $stmt = $pdo->query("SELECT * FROM article ORDER BY date_cre_article DESC");
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/* ===== COMMENTS ===== */
if ($page === 'commentaires') {
    $stmt = $pdo->query("
        SELECT commentaire.*, article.titre 
        FROM commentaire
        JOIN article ON commentaire.id_article = article.id_article
        ORDER BY date_cre_comment DESC
    ");
    $commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html class="no-js" lang="fr">
<head>
    <meta charset="utf-8">
    <title>Dashboard Admin</title>

    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/vendor.css">
    <link rel="stylesheet" href="css/main.css">

    <style>
        .dash-nav{
            display:flex;
            gap:20px;
            margin-bottom:30px;
            border-bottom:2px solid #eee;
        }
        .dash-nav a{
            padding:10px 20px;
            font-weight:600;
            text-decoration:none;
            color:#333;
        }
        .dash-nav a.active{
            border-bottom:3px solid #000;
        }

        table{
            width:80%;
            border-collapse:collapse;
            background:#fff;
        }
        th,td{
            padding:12px;
            border-bottom:1px solid #ddd;
        }
        th{
            background:#f7f7f7;
        }

        .btn{
            padding:5px 10px;
            font-size:13px;
            border-radius:4px;
            text-decoration:none;
            color:#fff;
        }
        .edit{background:#28a745;}
        .delete{background:#dc3545;}
        .add{background:#007bff; margin-bottom:20px; display:inline-block;}
    </style>
</head>

<body>

<div id="top" class="s-wrap site-wrapper">

<!-- ===== NAVBAR PRINCIPAL (KIF HOWA) ===== -->
<header class="s-header header">
    <div class="header__top">
        <div class="header__logo">
            <a class="site-logo" href="index.php">
                <img src="images/logo.svg" alt="">
            </a>
        </div>
        <a href="#0" class="header__menu-toggle"><span>Menu</span></a>
    </div>

    <nav class="header__nav-wrap">
        <ul class="header__nav">
                <li><a href="index.php">Home</a></li>
                <li><a href="category.php">Categories</a></li>
                <li><a href="page-about.html">About</a></li>
                <li><a href="page-contact.php">Contact</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
    </nav>
</header>

<!-- ===== CONTENT ===== -->
<div class="s-content content">
<main class="row content__page">

<section class="column large-full">

<h1 class="display-1">Dashboard Admin</h1>

<!-- ===== DASHBOARD NAVBAR ===== -->
<div class="dash-nav">
    <a href="dashboard.php?page=articles" class="<?= $page==='articles'?'active':'' ?>"> Articles</a>
    <a href="dashboard.php?page=commentaires" class="<?= $page==='commentaires'?'active':'' ?>"> Commentaires</a>
    <a href="add_article.php"> Ajouter Article</a>
</div>

<!-- ===== ARTICLES LIST ===== -->
<?php if($page === 'articles'): ?>

<!-- <a href="add_article.php" class="btn add"> Ajouter un article</a> -->

<table>
<tr>
    <th>ID</th>
    <th>Titre</th>
    <th>Date</th>
    <th>Actions</th>
</tr>

<?php foreach($articles as $a): ?>
<tr>
    <td><?= $a['id_article'] ?></td>
    <td><?= htmlspecialchars($a['titre']) ?></td>
    <td><?= date('d/m/Y', strtotime($a['date_cre_article'])) ?></td>
    <td>
        <a href="edit_article.php?id=<?= $a['id_article'] ?>" class="btn edit">Edit</a>
        <a href="delete_article.php?id=<?= $a['id_article'] ?>"
           class="btn delete"
           onclick="return confirm('Supprimer cet article ?')">Supprimer</a>
    </td>
</tr>
<?php endforeach; ?>
</table>

<?php endif; ?>

<!-- ===== COMMENTS LIST ===== -->
<?php if($page === 'commentaires'): ?>

<table>
<tr>
    <th>ID</th>
    <th>Commentaire</th>
    <th>Article</th>
    <th>Date</th>
    <th>Action</th>
</tr>

<?php foreach($commentaires as $c): ?>
<tr>
    <td><?= $c['id_commentaire'] ?></td>
    <td><?= htmlspecialchars($c['contenu_comment']) ?></td>
    <td><?= htmlspecialchars($c['titre']) ?></td>
    <td><?= date('d/m/Y', strtotime($c['date_cre_comment'])) ?></td>
    <td>
        <a href="delete_comment.php?id=<?= $c['id_commentaire'] ?>"
           class="btn delete"
           onclick="return confirm('Supprimer ce commentaire ?')">
           Supprimer
        </a>
    </td>
</tr>
<?php endforeach; ?>
</table>

<?php endif; ?>

</section>
</main>
</div>

</div>

<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
