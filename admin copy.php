<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant qu'admin
if ( !isset( $_SESSION[ 'id_utilisateur' ] ) || $_SESSION[ 'role' ] !== 'administrateur' ) {
    header( 'Location: login.php' );
    exit();
}

// Récupérer le nom d'utilisateur de la session
$nom_utilisateur = $_SESSION["nom"];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Admin</title>
 
</head>
<body>

    <div class="navbar">
        <a href="#" style="float: left;">Tableau de Bord</a>
        <a href="#">Livres</a>
        <a href="#">Mes Livres</a>
        <a href="#">Profil</a>
        <div class="user-info">
            <span><?php echo $nom_utilisateur; ?></span>
            <button class="logout-btn" onclick="window.location.href='logout.php'">Déconnexion</button>
        </div>
    </div>

    <div class="admin-dashboard">
        <h1 class="welcome-message">Bienvenue, <?php echo $nom_utilisateur; ?>!</h1>

        <!-- Ajoutez ici le contenu spécifique au tableau de bord de l'administrateur -->

</div>

</body>
</html>

