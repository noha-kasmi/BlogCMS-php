<?php
// ================= CONNEXION DB =================
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blogcms";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

$msg = "";

// ================= TRAITEMENT FORM =================
if (isset($_POST['submit'])) {

    $email    = trim($_POST['email']);
    $username = trim($_POST['user_name']);
    $password = trim($_POST['password']);
    $role     = trim($_POST['role']);

    if (!empty($email) && !empty($username) && !empty($password) && !empty($role)) {

        $email    = $conn->real_escape_string($email);
        $username = $conn->real_escape_string($username);
        $role     = $conn->real_escape_string($role);

        // Hash password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Date création
        $date_cre_compte = date("Y-m-d H:i:s");

        $sql = "INSERT INTO utilisateur 
                (email, user_name, password, date_cre_compte, role)
                VALUES 
                ('$email', '$username', '$password_hash', '$date_cre_compte', '$role')";

        if ($conn->query($sql)) {
            $msg = "<p style='color:green;font-weight:bold;'>Compte créé avec succès.</p>";
        } else {
            $msg = "<p style='color:red;'>Erreur : email ou username déjà existant.</p>";
        }

    } else {
        $msg = "<p style='color:red;'>Tous les champs sont obligatoires.</p>";
    }
}
?>

<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <title>Création Utilisateur</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS -->
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/vendor.css">
    <link rel="stylesheet" href="css/main.css">
</head>

<body class="ss-bg-white">

<div class="s-wrap site-wrapper">

    <!-- HEADER -->
    <header class="s-header header">
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
    <div class="s-content content">
        <main class="row content__page">

            <section class="column large-full">

                <h1 class="display-1">Créer un Compte</h1>

                <!-- MESSAGE PHP -->
                <?php if ($msg) echo $msg; ?>

                <form method="post" autocomplete="off">
                    <fieldset>

                        <div class="form-field">
                            <input type="email" name="email" class="full-width" placeholder="Email" required>
                        </div>

                        <div class="form-field">
                            <input type="text" name="user_name" class="full-width" placeholder="Username" required>
                        </div>

                        <div class="form-field">
                            <input type="password" name="password" class="full-width" placeholder="Password" required>
                        </div>

                        <div class="form-field">
                            <input type="text" class="full-width" value="<?php echo date('Y-m-d H:i:s'); ?>" disabled>
                            <small>Date de création automatique</small>
                        </div>

                        <div class="form-field">
                            <select name="role" class="full-width" required>
                                <option value="">-- Choisir le rôle --</option>
                                <option value="admin">Admin</option>
                                <option value="author">Author</option>
                                <option value="editor">Editor</option>
                            </select>
                        </div>

                        <button type="submit" name="submit" class="btn btn--primary btn-wide full-width"> Créer le compte </button>
                    </fieldset>
                </form>

            </section>

        </main>
    </div>

    <!-- FOOTER -->
    <footer class="s-footer footer">
        <div class="row">
            <div class="column large-full footer__content">
                © Typerite 2019
            </div>
        </div>
    </footer>

</div>

</body>
</html>
