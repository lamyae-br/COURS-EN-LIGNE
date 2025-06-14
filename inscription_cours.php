<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['etudiant_id'])) {
    header("Location: connexion_etudiant.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $etudiant_id = $_SESSION['etudiant_id'];
    $id_cours = intval($_POST['id_cours']);

    // Vérifier si l'inscription existe déjà
    $stmt = $pdo->prepare("SELECT * FROM inscriptions WHERE id_etudiant = ? AND id_cours = ?");
    $stmt->execute([$etudiant_id, $id_cours]);
    if ($stmt->rowCount() > 0) {
        echo "<script>alert('Vous êtes déjà inscrit à ce cours.'); window.location.href = 'espace_etudiant.php';</script>";
        exit();
    }

    // Insérer l'inscription
    $stmt = $pdo->prepare("INSERT INTO inscriptions (id_etudiant, id_cours) VALUES (?, ?)");
    if ($stmt->execute([$etudiant_id, $id_cours])) {
        echo "<script>alert('Inscription réussie !'); window.location.href = 'espace_etudiant.php';</script>";
    } else {
        echo "<script>alert('Erreur lors de l\'inscription.'); window.location.href = 'espace_etudiant.php';</script>";
    }
} else {
    header("Location: espace_etudiant.php");
    exit();
}
