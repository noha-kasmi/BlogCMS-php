<?php

$host = 'localhost';
$dbname = 'blogcms';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$titre = $contenu = $date_creation = $date_modification = $id_utilisateur = $id_categorie = '';
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $contenu = trim($_POST['contenu_article'] ?? '');
    $id_utilisateur = trim($_POST['id_utilisateur'] ?? '');
    $id_categorie = trim($_POST['id_categorie'] ?? '');
    
    if (empty($titre)) {
        $errors['titre'] = "Le titre est obligatoire";
    }
    
    if (empty($contenu)) {
        $errors['contenu_article'] = "Le contenu est obligatoire";
    }
    
    if (empty($id_utilisateur) || !is_numeric($id_utilisateur)) {
        $errors['id_utilisateur'] = "L'ID utilisateur est obligatoire et doit être numérique";
    }
    
    if (empty($id_categorie) || !is_numeric($id_categorie)) {
        $errors['id_categorie'] = "L'ID catégorie est obligatoire et doit être numérique";
    }
    
    if (empty($errors)) {
        try {
            $date_creation = date('Y-m-d H:i:s');
            $date_modification = $date_creation; 
            
            $sql = "INSERT INTO article (titre, contenu, date_cre_article, date_modification, id_utilisateur, id_categorie) 
                    VALUES (:titre, :contenu, :date_creation, :date_modification, :id_utilisateur, :id_categorie)";
            
            $stmt = $pdo->prepare($sql);
            
$stmt->bindParam(':titre', $titre);
$stmt->bindParam(':contenu', $contenu);
$stmt->bindParam(':date_creation', $date_creation);
$stmt->bindParam(':date_modification', $date_modification);
$stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
$stmt->bindParam(':id_categorie', $id_categorie, PDO::PARAM_INT);

            
            if ($stmt->execute()) {
                $success = true;
                $last_id = $pdo->lastInsertId();
                
                $titre = $contenu = $id_utilisateur = $id_categorie = '';
            }
            
        } catch(PDOException $e) {
            $errors['blogcms'] = "Erreur lors de l'insertion : " . $e->getMessage();
        }
    }
}

try {
    $stmt_users = $pdo->query("SELECT id_utilisateur, user_name, role FROM utilisateur ORDER BY user_name");
    $utilisateurs = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
    
    $stmt_categories = $pdo->query("SELECT id_categorie, nom_categorie , description_categorie FROM categorie ORDER BY nom_categorie");
    $categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $utilisateurs = [];
    $categories = [];
    $errors['fetch'] = "Erreur lors du chargement des données : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un article</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 30px;
        }
        
        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: bold;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        input[type="text"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        textarea {
            min-height: 200px;
            resize: vertical;
        }
        
        .error {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .btn {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #2980b9;
        }
        
        .btn-reset {
            background-color: #95a5a6;
            margin-left: 10px;
        }
        
        .btn-reset:hover {
            background-color: #7f8c8d;
        }
        
        .form-actions {
            margin-top: 30px;
            text-align: center;
        }
        
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #3498db;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 0 5px 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ajouter un nouvel article</h1>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                 Article ajouté avec succès ! ID : <?php echo htmlspecialchars($last_id); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errors['database'])): ?>
            <div class="alert alert-error">
                 <?php echo htmlspecialchars($errors['database']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errors['fetch'])): ?>
            <div class="alert alert-error">
                 <?php echo htmlspecialchars($errors['fetch']); ?>
            </div>
        <?php endif; ?>
        
        <div class="info-box">
            <strong> Information :</strong>tous les champs sont obligatoires. 
            Les dates de création et modification sont automatiquement générées.
        </div>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="titre">Titre de l'article </label>
                <input type="text" id="titre" name="titre" value="<?php echo htmlspecialchars($titre); ?>" required>
                <?php if (!empty($errors['titre'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['titre']); ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="contenu">Contenu de l'article </label>
                <textarea id="contenu" name="contenu" required><?php echo htmlspecialchars($contenu); ?></textarea>
                <?php if (!empty($errors['contenu'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['contenu']); ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="id_utilisateur">Auteur (Utilisateur) </label>
                <select id="id_utilisateur" name="id_utilisateur" required>
                    <option value="">Sélectionnez un utilisateur</option>
                    <?php foreach ($utilisateurs as $utilisateur): ?>
                        <option value="<?php echo htmlspecialchars($utilisateur['id_utilisateur']); ?>"
                            <?php echo ($id_utilisateur == $utilisateur['id_utilisateur']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($utilisateur['user_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($errors['id_utilisateur'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['id_utilisateur']); ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="id_categorie">Catégorie *</label>
                <select id="id_categorie" name="id_categorie" required>
                    <option value="">Sélectionnez une catégorie</option>
                    <?php foreach ($categories as $categorie): ?>
                        <option value="<?php echo htmlspecialchars($categorie['id_categorie']); ?>"
                            <?php echo ($id_categorie == $categorie['id_categorie']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($categorie['nom_categorie']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($errors['id_categorie'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['id_categorie']); ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn">Ajouter l'article</button>
            </div>
        </form>
    </div>
    <footer class="s-footer footer">
            <div class="row">
                <div class="column large-full footer__content">
                    <div class="footer__copyright">
                        <span>© Copyright Typerite 2019</span> 
                        <span>Design by <a href="https://www.styleshout.com/">StyleShout</a></span>
                    </div>
                </div>
            </div>

            <div class="go-top">
                <a class="smoothscroll" title="Back to Top" href="#top"></a>
            </div>
        </footer>
</body>
</html>