<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION["professeur_id"])) {
    header("Location: login.php");
    exit();
}
$idProf = $_SESSION["professeur_id"];

$cours = $pdo->prepare("SELECT * FROM cours WHERE id_professeur = ?");
$cours->execute([$idProf]);
$cours_list = $cours->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les étudiants</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ecf0f1;
        }

        header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
            background-color: #f9f9f9;
        }

        a.manage-button {
            display: inline-block;
            padding: 8px 15px;
            background-color: #27ae60;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        a.manage-button:hover {
            background-color: #1e8449;
        }
    </style>
</head>
<body>

<header>
<p><a href="espace_professeur.php" style="color: #ecf0f1; text-decoration: underline;">⬅ Retour à l’espace professeur</a></p>
    <h1>Gérer les étudiants par cours</h1>
</header>

<div class="container">
    <?php if (count($cours_list) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Nom du cours</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cours_list as $cours): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cours['nom']); ?></td>
                        <td><?php echo htmlspecialchars($cours['description']); ?></td>
                        <td>
                            <a class="manage-button" href="liste_etudiants.php?cours_id=<?php echo $cours['id']; ?>">
                                Voir / Gérer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun cours trouvé pour ce professeur.</p>
    <?php endif; ?>
</div>

</body>
</html>
