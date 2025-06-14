<?php
require_once 'includes/db.php'; // adapte le chemin si besoin

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $identifiant = $_POST['id_professeur'];
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $departement = trim($_POST['departement']);
    $email = trim($_POST['email_inscription']);
    $mot_de_passe = $_POST['mot_de_passe_inscription'];
    $confirmation = $_POST['confirmation_mot_de_passe'];

    // Validation des données
    $erreurs = [];

    // Vérification : mot de passe == confirmation
    if ($mot_de_passe !== $confirmation) {
        $erreurs[] = "Les mots de passe ne correspondent pas.";
    }

    // Vérification force du mot de passe
    if (strlen($mot_de_passe) < 8) {
        $erreurs[] = "Le mot de passe doit contenir au moins 8 caractères.";
    }

    // Vérifier si l'email est valide
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "L'adresse email n'est pas valide.";
    }

    // Vérifier si l'email ou l'identifiant existe déjà
    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ? OR identifiant = ?");
    $stmt->execute([$email, $identifiant]);
    if ($stmt->rowCount() > 0) {
        $erreurs[] = "Cet email ou identifiant est déjà utilisé.";
    }

    // S'il y a des erreurs, on les affiche
    if (!empty($erreurs)) {
        echo "<script>alert('" . implode("\\n", $erreurs) . "'); window.history.back();</script>";
        exit();
    }

    // Hachage du mot de passe
    $mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    // Insertion dans la base de données
    try {
        $stmt = $pdo->prepare("INSERT INTO utilisateurs 
                              (type, identifiant, nom, prenom, email, mot_de_passe, departement_filiere) 
                              VALUES ('professeur', ?, ?, ?, ?, ?, ?)");
        
        $result = $stmt->execute([
            $identifiant,
            $nom,
            $prenom,
            $email,
            $mot_de_passe_hache,
            $departement
        ]);

        if ($result) {
            // Redirection avec message de succès
            echo "<script>
                alert('Inscription réussie. Vous pouvez maintenant vous connecter.');
                window.location.href = 'Espace_prof.html';
            </script>";
            exit();
        }
    } catch (PDOException $e) {
        // En cas d'erreur SQL
        error_log("Erreur d'inscription: " . $e->getMessage());
        echo "<script>
            alert('Une erreur technique est survenue. Veuillez réessayer plus tard.');
            window.history.back();
        </script>";
        exit();
    }
} else {
    // Si quelqu'un essaie d'accéder directement au script
    header("Location: professeur.html");
    exit();
}
?>