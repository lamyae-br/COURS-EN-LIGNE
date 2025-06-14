<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION["professeur_id"])) {
    header("Location: login.php");
    exit();
}

$professeur_id = $_SESSION["professeur_id"];

$sql = "SELECT i.id AS inscription_id, u.nom AS nom_etudiant, u.prenom, c.nom AS nom_cours, c.id AS id_cours, u.id AS id_etudiant
        FROM inscriptions i
        JOIN utilisateurs u ON i.id_etudiant = u.id
        JOIN cours c ON i.id_cours = c.id
        WHERE c.id_professeur = ?
        ORDER BY c.nom, u.nom";

$stmt = $pdo->prepare($sql);
$stmt->execute([$professeur_id]);
$inscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes étudiants</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            max-width: 1000px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #2980b9;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        form {
            display: inline;
        }

        button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #c0392b;
        }

        .add-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #27ae60;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .add-button:hover {
            background-color: #1e8449;
        }

        h2 {
            color: #2c3e50;
        }
    </style>
</head>
<body>

<header>
<p><a href="espace_professeur.php" style="color: #ecf0f1; text-decoration: underline;">⬅ Retour à l’espace professeur</a></p>
    <h1>Liste des étudiants par cours</h1>
</header>

<div class="container">

    <?php if (count($inscriptions) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Nom du cours</th>
                    <th>Nom étudiant</th>
                    <th>Prénom</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inscriptions as $inscription): ?>
                    <tr>
                        <td><?= htmlspecialchars($inscription['nom_cours']) ?></td>
                        <td><?= htmlspecialchars($inscription['nom_etudiant']) ?></td>
                        <td><?= htmlspecialchars($inscription['prenom']) ?></td>
                        <td>
                            <form method="post" action="supprimer_etudiant.php" onsubmit="return confirm('Supprimer cet étudiant ?');">
                                <input type="hidden" name="id_etudiant" value="<?= $inscription['id_etudiant'] ?>">
                                <input type="hidden" name="id_cours" value="<?= $inscription['id_cours'] ?>">
                                <button type="submit">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun étudiant inscrit dans vos cours pour le moment.</p>
    <?php endif; ?>

    <h2>Ajouter un étudiant à un cours</h2>
    <a class="add-button" href="ajouter_etudiant.php">➕ Ajouter</a>

</div>

</body>
</html>
