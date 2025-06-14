<?php
session_start();
require_once 'includes/db.php'; // Connexion PDO

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $mot_de_passe = $_POST["mot_de_passe"];

    // Préparer la requête pour récupérer l'utilisateur de type étudiant
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ? AND type = 'etudiant'");
    $stmt->execute([$email]);
    $etudiant = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si l'utilisateur existe et que le mot de passe correspond
    if ($etudiant && password_verify($mot_de_passe, $etudiant['mot_de_passe'])) {
        // Stocker les infos dans la session
        $_SESSION["etudiant_id"] = $etudiant["id"];
        $_SESSION["nom"] = $etudiant["nom"];
        $_SESSION["prenom"] = $etudiant["prenom"];

        // Rediriger vers l'espace étudiant
        header("Location: espace_etudiant.php");
        exit();
    } else {
        echo "<script>alert('Email ou mot de passe incorrect.'); window.history.back();</script>";
    }
}
?>
