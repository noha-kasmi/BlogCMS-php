<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blogcms";

// Connexion à la base
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Récupérer les articles
$sql = "SELECT * FROM article ORDER BY date_cre_article DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <title>Typerite</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/vendor.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/modernizr.js"></script>
</head>
<body>

<div id="top" class="s-wrap site-wrapper">
    <header class="s-header">
        <header class="s-header header">

            <div class="header__top">
                <div class="header__logo">
                    <a class="site-logo" href="index.html">
                        <img src="images/logo.svg" alt="Homepage">
                    </a>
                </div>

                <div class="header__search">
    
                    <form role="search" method="get" class="header__search-form" action="#">
                        <label>
                            <span class="hide-content">Search for:</span>
                            <input type="search" class="header__search-field" placeholder="Type Keywords" value="" name="s" title="Search for:" autocomplete="off">
                        </label>
                        <input type="submit" class="header__search-submit" value="Search">
                    </form>
        
                    <a href="#0" title="Close Search" class="header__search-close">Close</a>
        
                </div>  <!-- end header__search -->

                <!-- toggles -->
                <a href="#0" class="header__search-trigger"></a>
                <a href="#0" class="header__menu-toggle"><span>Menu</span></a>

            </div>

            <nav class="header__nav-wrap">

                <ul class="header__nav">
                <li><a href="index.php">Home</a></li>
                <li><a href="category.php">Categories</a></li>
                <li><a href="page-about.html">About</a></li>
                <li><a href="page-contact.php">Contact</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
            </ul>
                </ul> <!-- end header__nav -->

                <ul class="header__social">
                    <li class="ss-facebook">
                        <a href="https://facebook.com/">
                            <span class="screen-reader-text">Facebook</span>
                        </a>
                    </li>
                    <li class="ss-twitter">
                        <a href="#0">
                            <span class="screen-reader-text">Twitter</span>
                        </a>
                    </li>
                    <li class="ss-dribbble">
                        <a href="#0">
                            <span class="screen-reader-text">Instagram</span>
                        </a>
                    </li>
                    <li class="ss-behance">
                        <a href="#0">
                            <span class="screen-reader-text">Behance</span>
                        </a>
                    </li>
                </ul>

            </nav> <!-- end header__nav-wrap -->

        </header> <!-- end s-header -->
    </header>

    <div class="s-content">
        <div class="masonry-wrap">
            <div class="masonry">
                <div class="grid-sizer"></div>

                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        ?>
                        <article class="masonry__brick entry format-standard animate-this">
                            <?php
                        $image = !empty($article['image']) 
                            ? 'uploads/' . $article['image'] 
                            : 'images/sample-image.jpg';
                            ?>

<div class="entry__thumb">
    <a href="single.php?id=<?= $article['id'] ?>" class="entry__thumb-link">
        <img src="<?= $image ?>" alt="<?= htmlspecialchars($article['titre']) ?>">
    </a>
</div>


                            <div class="entry__text">
                                <div class="entry__header">
                                    <h2 class="entry__title">
                                        <a href="single-standard.php?id=<?php echo $row['id_article']; ?>">
                                            <?php echo htmlspecialchars($row['titre']); ?>
                                        </a>
                                    </h2>
                                    <div class="entry__meta">
                                        <span class="entry__meta-date">
                                            <a href="single-standard.php?id=<?php echo $row['id_article']; ?>">
                                                <?php echo $row['date_cre_article']; ?>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                <div class="entry__excerpt">
                                    <p>
                                        <?php echo nl2br(htmlspecialchars(substr($row['contenu_article'],0,200))); ?>...
                                    </p>
                                </div>
                            </div>
                        </article>
                        <?php
                    }
                } else {
                    echo "<p>Aucun article trouvé.</p>";
                }
                $conn->close();
                ?>

            </div> <!-- end masonry -->
        </div> <!-- end masonry-wrap -->

    </div> <!-- end s-content -->

    <footer class="s-footer">
        <!-- Footer ici, reste inchangé -->
    </footer>
</div> <!-- end s-wrap -->

<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/plugins.js"></script>
<script src="js/main.js"></script>

</body>
</html>
