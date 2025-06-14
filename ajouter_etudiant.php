<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION["professeur_id"])) {
    header("Location: login.php");
    exit();
}

$professeur_id = $_SESSION["professeur_id"];

$sqlEtudiants = "SELECT * FROM utilisateurs WHERE type = 'etudiant'";
$etudiants = $pdo->query($sqlEtudiants)->fetchAll(PDO::FETCH_ASSOC);

$sqlCours = "SELECT * FROM cours WHERE id_professeur = ?";
$stmtCours = $pdo->prepare($sqlCours);
$stmtCours->execute([$professeur_id]);
$cours = $stmtCours->fetchAll(PDO::FETCH_ASSOC);

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_etudiant = $_POST['id_etudiant'];
    $id_cours = $_POST['id_cours'];

    $check = $pdo->prepare("SELECT * FROM inscriptions WHERE id_etudiant = ? AND id_cours = ?");
    $check->execute([$id_etudiant, $id_cours]);

    if ($check->rowCount() === 0) {
        $stmt = $pdo->prepare("INSERT INTO inscriptions (id_etudiant, id_cours) VALUES (?, ?)");
        $stmt->execute([$id_etudiant, $id_cours]);
        $message = "<div class='success'>✅ Étudiant ajouté avec succès.</div>";
    } else {
        $message = "<div class='warning'>⚠️ Étudiant déjà inscrit à ce cours.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un étudiant</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #2c3e50;
            color: #fff;
            padding: 20px 40px;
            text-align: center;
        }

        .container {
            max-width: 700px;
            margin: 40px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #34495e;
        }

        select {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            margin-top: 20px;
            background-color: #2980b9;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        button:hover {
            background-color: #21618c;
        }

        .back-link {
            display: inline-block;
            margin-top: 25px;
            text-decoration: none;
            color: #2980b9;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        .warning {
            background-color: #fff3cd;
            color: #856404;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #ffeeba;
        }
    </style>
</head>
<body>

<header>
    <h1>Ajouter un étudiant à un cours</h1>
</header>

<div class="container">

    <?= $message ?>

    <form method="post">
        <label for="id_etudiant">Étudiant :</label>
        <select name="id_etudiant" id="id_etudiant" required>
            <?php foreach ($etudiants as $etudiant): ?>
                <option value="<?= $etudiant['id'] ?>">
                    <?= htmlspecialchars($etudiant['nom'] . ' ' . $etudiant['prenom']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="id_cours">Cours :</label>
        <select name="id_cours" id="id_cours" required>
            <?php foreach ($cours as $coursItem): ?>
                <option value="<?= $coursItem['id'] ?>">
                    <?= htmlspecialchars($coursItem['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">➕ Ajouter l'étudiant</button>
    </form>

    <a class="back-link" href="liste_etudiants.php">⬅ Retour à la liste des étudiants</a>

</div>

</body>
</html>
