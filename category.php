<?php
// Connexion à la base
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blogcms";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Récupérer toutes les catégories
$sql = "SELECT * FROM categorie ORDER BY nom_categorie ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Categories - Typerite</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/vendor.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>

<div id="top" class="s-wrap site-wrapper">

    <!-- HEADER -->
    <header class="s-header">
        <div class="header__top">
            <div class="header__logo">
                <a class="site-logo" href="index.php">
                    <img src="images/logo.svg" alt="Homepage">
                </a>
            </div>
        </div>
        <nav class="header__nav-wrap">
            <ul class="header__nav">
                <li><a href="index.php">Home</a></li>
                <li><a href="category.php">Categories</a></li>
                <li><a href="page-about.html">About</a></li>
                <li><a href="page-contact.php">Contact</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
            </ul>
        </nav>
    </header>

    <!-- CONTENT -->
    <div class="s-content">
        <header class="listing-header">
            <h1 class="h2">Toutes les catégories</h1>
        </header>

        <div class="masonry-wrap">
            <div class="masonry">
                <div class="grid-sizer"></div>

                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Image par défaut si aucune image n'est définie
                        $image = !empty($row['image']) 
                            ? 'uploads/' . $row['image'] 
                            : 'images/wheel-2000.jpg';
                        ?>
                        <article class="masonry__brick entry format-standard animate-this">
                            <div class="entry__thumb">
                                <a href="category.php?id=<?= $row['id_categorie'] ?>" class="entry__thumb-link">
                                    <img src="<?= $image ?>" alt="<?= htmlspecialchars($row['nom_categorie']) ?>">
                                </a>
                            </div>
                            <div class="entry__text">
                                <div class="entry__header">
                                    <h2 class="entry__title">
                                        <a href="category.php?id=<?= $row['id_categorie'] ?>">
                                            <?= htmlspecialchars($row['nom_categorie']) ?>
                                        </a>
                                    </h2>
                                </div>
                                
                                
                            </div>
                        </article>
                    <?php
                    }
                } else {
                    echo "<p>Aucune catégorie trouvée.</p>";
                }
                $conn->close();
                ?>

            </div> <!-- end masonry -->
        </div> <!-- end masonry-wrap -->
    </div> <!-- end s-content -->

    <!-- FOOTER -->
    <footer class="s-footer">
        <div class="footer__copyright">
            <span>© Copyright Typerite 2019</span>
            <span>Design by <a href="https://www.styleshout.com/">StyleShout</a></span>
        </div>
    </footer>

</div> <!-- end s-wrap -->

<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/plugins.js"></script>
<script src="js/main.js"></script>

</body>
</html>
