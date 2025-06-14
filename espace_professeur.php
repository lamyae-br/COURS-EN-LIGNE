<?php
session_start();

// Vérification de session
if (!isset($_SESSION["professeur_id"])) {
    header("Location: login.php");
    exit();
}

$nom = $_SESSION["nom"];
$prenom = $_SESSION["prenom"];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Espace Professeur</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
        }

        header {
            background-color: #34495e;
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
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        h1 {
            color: #2c3e50;
        }

        ul.menu {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
        }

        ul.menu li {
            background-color: #2980b9;
            padding: 15px 25px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        ul.menu li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        ul.menu li:hover {
            background-color: #1f618d;
        }

        .logout {
            margin-top: 30px;
            text-align: center;
        }

        .logout a {
            color: #c0392b;
            font-weight: bold;
            text-decoration: none;
        }

        .logout a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<header>
    
    <h1>Bienvenue, <?php echo htmlspecialchars($prenom . ' ' . $nom); ?></h1>
</header>

<div class="container">
    <p>Ceci est votre espace personnel professeur.</p>

    <ul class="menu">
        <li><a href="mes_cours.php">📚 Mes Cours</a></li>
        
        <li><a href="ajouter_cours.php">➕ Ajouter un cours</a></li>
        <li><a href="gerer_etudiants.php">👨‍🎓 Gérer les étudiants</a></li> <!-- Nouveau lien -->
    </ul>

    <div class="logout">
        <p><a href="deconnexion.php">Se déconnecter</a></p>
    </div>
</div>

</body>
</html>
