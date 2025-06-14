<?php
session_start();
require_once 'includes/db.php'; // Inclure la connexion PDO

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $mot_de_passe = $_POST["mot_de_passe"];

    // Préparer la requête pour récupérer l'utilisateur de type professeur
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ? AND type = 'professeur'");
    $stmt->execute([$email]);
    $prof = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si l'utilisateur existe et que le mot de passe correspond
    if ($prof && password_verify($mot_de_passe, $prof['mot_de_passe'])) {
        // Stocker les infos dans la session
        $_SESSION["professeur_id"] = $prof["id"];
        $_SESSION["nom"] = $prof["nom"];
        $_SESSION["prenom"] = $prof["prenom"];

        // Rediriger vers l'espace professeur
        header("Location: espace_professeur.php");
        exit();
    } else {
        echo "<script>alert('Email ou mot de passe incorrect.'); window.history.back();</script>";
    }
}
?>
