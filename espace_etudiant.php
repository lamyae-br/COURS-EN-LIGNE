<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['etudiant_id'])) {
    header("Location: connexion_etudiant.php");
    exit();
}

$etudiant_id = $_SESSION['etudiant_id'];
$nom = $_SESSION['nom'] ?? 'Étudiant';
$prenom = $_SESSION['prenom'] ?? '';

// Récupérer les cours inscrits de l'étudiant
$sql = "SELECT c.id, c.nom AS nom_cours, c.description, u.nom AS prof_nom, u.prenom AS prof_prenom 
        FROM inscriptions i
        JOIN cours c ON i.id_cours = c.id
        LEFT JOIN utilisateurs u ON c.id_professeur = u.id
        WHERE i.id_etudiant = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$etudiant_id]);
$cours_inscrits = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer tous les cours disponibles
$sql_all = "SELECT c.id, c.nom AS nom_cours, c.description, u.nom AS prof_nom, u.prenom AS prof_prenom 
            FROM cours c
            LEFT JOIN utilisateurs u ON c.id_professeur = u.id";
$stmt_all = $pdo->query($sql_all);
$cours_disponibles = $stmt_all->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les ids des cours déjà inscrits pour éviter doublons dans l'affichage
$ids_inscrits = array_column($cours_inscrits, 'id');

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Étudiant</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f0f8ff; }
        .container { max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px #ccc; text-align: center; }
        h1 { color: #004080; }
        .btn-logout, .btn-inscrire {
            margin-top: 20px;
            background-color: #d9534f;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-logout:hover { background-color: #c9302c; }
        .btn-inscrire { background-color: #5cb85c; margin-left: 10px; }
        .btn-inscrire:hover { background-color: #4cae4c; }
        ul.cours-list { list-style-type: none; padding: 0; text-align: left; }
        ul.cours-list li { background: #e6f0ff; margin: 10px 0; padding: 15px; border-radius: 6px; box-shadow: 0 0 5px #b3c6ff; display: flex; justify-content: space-between; align-items: center; }
        ul.cours-list li strong { color: #003366; }
        .cours-info { max-width: 80%; }
    </style>
</head>
<body>

<div class="container">
    <h1>Bienvenue, <?= htmlspecialchars($prenom . ' ' . $nom) ?> !</h1>
    <p>Vous êtes connecté à votre espace étudiant.</p>

    <h2>Mes cours inscrits</h2>
    <?php if (count($cours_inscrits) > 0): ?>
        <ul class="cours-list">
            <?php foreach ($cours_inscrits as $c): ?>
                <li>
                    <div class="cours-info">
                        <strong><?= htmlspecialchars($c['nom_cours']) ?></strong><br>
                        Description : <?= nl2br(htmlspecialchars($c['description'])) ?><br>
                        Professeur : <?= htmlspecialchars($c['prof_nom'] . ' ' . $c['prof_prenom']) ?>
                    </div>
                    <span>✅</span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Vous n'êtes inscrit à aucun cours pour le moment.</p>
    <?php endif; ?>

    <h2>Cours disponibles</h2>
    <?php if (count($cours_disponibles) > 0): ?>
        <ul class="cours-list">
            <?php foreach ($cours_disponibles as $c): ?>
                <li>
                    <div class="cours-info">
                        <strong><?= htmlspecialchars($c['nom_cours']) ?></strong><br>
                        Description : <?= nl2br(htmlspecialchars($c['description'])) ?><br>
                        Professeur : <?= htmlspecialchars($c['prof_nom'] . ' ' . $c['prof_prenom']) ?>
                    </div>
                    <?php if (!in_array($c['id'], $ids_inscrits)): ?>
                        <form method="post" action="inscription_cours.php" style="margin:0;">
                            <input type="hidden" name="id_cours" value="<?= $c['id'] ?>">
                            <button type="submit" class="btn-inscrire">S'inscrire</button>
                        </form>
                    <?php else: ?>
                        <span>Déjà inscrit</span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucun cours disponible actuellement.</p>
    <?php endif; ?>

    <form method="post" action="deconnexionn.php">
        <button type="submit" class="btn-logout">Se déconnecter</button>
    </form>
</div>

</body>
</html>
