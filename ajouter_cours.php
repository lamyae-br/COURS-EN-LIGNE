<?php
session_start();
require_once 'includes/db.php'; // Connexion via $pdo

// Vérifie que le professeur est connecté
if (!isset($_SESSION["professeur_id"])) {
    header("Location: connexion.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST["titre"];
    $description = $_POST["description"];
    $professeur_id = $_SESSION["professeur_id"]; // Variable bien nommée

    // Insertion du cours avec l'id du professeur connecté
    $stmt = $pdo->prepare("INSERT INTO cours (nom, description, id_professeur) VALUES (?, ?, ?)");
    $stmt->execute([$titre, $description, $professeur_id]);

    header("Location: mes_cours.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un cours</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            padding: 50px;
        }
        .container {
            width: 500px;
            background-color: white;
            padding: 30px;
            margin: auto;
            box-shadow: 0 0 10px #ccc;
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #0056b3;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Ajouter un nouveau cours</h2>
    <form method="POST" action="">
        <label for="titre">Titre du cours :</label>
        <input type="text" id="titre" name="titre" required>

        <label for="description">Description :</label>
        <textarea id="description" name="description" rows="5" required></textarea>

        <button type="submit">Ajouter</button>
    </form>
    <a href="mes_cours.php">← Retour à mes cours</a>
</div>
</body>
</html>
