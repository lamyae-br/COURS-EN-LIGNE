<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION["professeur_id"])) {
    header("Location: connexion.php");
    exit();
}

$professeur_id = $_SESSION["professeur_id"];

$stmt = $pdo->prepare("SELECT * FROM cours WHERE id_professeur = ?");
$stmt->execute([$professeur_id]);
$cours = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Cours</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background-color: #f2f2f2;
        }

        header {
            background-color: #34495e;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .cours {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 8px;
            background-color: #fdfdfd;
        }

        .cours h3 {
            margin: 0;
            color: #2980b9;
        }

        .cours p {
            margin-top: 8px;
            color: #555;
        }

        .retour {
            margin-top: 20px;
            text-align: center;
        }

        .retour a {
            text-decoration: none;
            color: #2980b9;
            font-weight: bold;
        }

        .retour a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<header>
    <h1>Mes Cours</h1>
</header>

<div class="container">
    <h2>Liste des cours que vous avez créés</h2>

    <?php if (count($cours) > 0): ?>
        <?php foreach ($cours as $cours_item): ?>
            <div class="cours">
                <h3><?= htmlspecialchars($cours_item['nom']) ?></h3>
                <p><?= nl2br(htmlspecialchars($cours_item['description'])) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Vous n’avez encore créé aucun cours.</p>
    <?php endif; ?>

    <div class="retour">
        <a href="espace_professeur.php">← Retour à l’espace professeur</a>
    </div>
</div>

</body>
</html>
