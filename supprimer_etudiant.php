<?php
session_start();
require_once 'includes/db.php'; // adapte le chemin si besoin

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_etudiant = $_POST['id_etudiant'];
    $id_cours = $_POST['id_cours'];

    $sql = "DELETE FROM inscriptions WHERE id_etudiant = ? AND id_cours = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_etudiant, $id_cours]);

    header("Location: liste_etudiants.php");
    exit();
}
?>
