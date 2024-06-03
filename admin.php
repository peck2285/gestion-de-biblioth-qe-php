<?php
include_once 'connexion.php';

session_start();

// Vérifier si l'utilisateur est connecté en tant qu'admin
if ( !isset( $_SESSION[ 'id_utilisateur' ] ) || $_SESSION[ 'role' ] !== 'administrateur' ) {
    header( 'Location: login.php' );
    exit();
}

// Récupérer le nom d'utilisateur de la session
$nom_utilisateur = $_SESSION["nom"];


// Fonction pour obtenir la liste des livres depuis la base de données

function getLivres()
 {
    global $mysqli;
    $livres = array();

    $result = $mysqli->query( 'SELECT * FROM livres' );

    while ( $row = $result->fetch_assoc() ) {
        $livres[] = $row;
    }

    return $livres;
}

// Ajouter un livre à la base de données
if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' && isset( $_POST[ 'ajouter' ] ) ) {
    $titre = $_POST[ 'titre' ];
    $auteur = $_POST[ 'auteur' ];
    $isbn = $_POST[ 'isbn' ];
    $genre = $_POST[ 'genre' ];
    $disponible = isset( $_POST[ 'disponible' ] ) ? 1 : 0;

    $mysqli->query( "INSERT INTO livres (titre, auteur, isbn, genre, disponible) VALUES ('$titre', '$auteur', '$isbn', '$genre', $disponible)" );
}

// Supprimer un livre de la base de données
if ( isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] == 'supprimer' && isset( $_GET[ 'id_livre' ] ) ) {
    $id_livre = $_GET[ 'id_livre' ];
    $mysqli->query( "DELETE FROM livres WHERE id_livre = $id_livre" );
}

// Obtenir la liste des livres
$livres = getLivres();
?>

<!DOCTYPE html>
<html lang = 'en'>
<head>
<meta charset = 'UTF-8'>
<meta name = 'viewport' content = 'width = device-width, initial-scale = 1.0'>
<title>Tableau de Bord - Admin</title>
<link rel = 'stylesheet' href = 'admin.css'>
</head>
<body>

<div class = 'navbar'>
<a href = '#' style = 'float: left;
'>Tableau de Bord</a>
<div class = 'user-info'>
<span><?php echo $nom_utilisateur;
?></span>
<button class = 'logout-btn' onclick = "window.location.href='logout.php'">Déconnexion</button>
</div>
</div>

<h1>Gestion des Livres</h1>
<button onclick = "window.location.href='ajouter_livre.php'">Ajouter</button>
<table border = '1' style = 'width:100%'>
<tr>
<th>ID Livre</th>
<th>Titre</th>
<th>Auteur</th>
<th>ISBN</th>
<th>Genre</th>
<th>Disponible</th>
<th>Action</th>
</tr>
<?php foreach ( $livres as $livre ) : ?>
<tr>
<td><?php echo $livre[ 'id_livre' ];
?></td>
<td><?php echo $livre[ 'titre' ];
?></td>
<td><?php echo $livre[ 'auteur' ];
?></td>
<td><?php echo $livre[ 'isbn' ];
?></td>
<td><?php echo $livre[ 'genre' ];
?></td>
<td><?php echo ( $livre[ 'disponible' ] ? 'Disponible' : 'Non Disponible' );
?></td>
<td>
<a href = "editer_livre.php?id_livre=<?php echo $livre['id_livre']; ?>">Éditer</a>
<a href = "?action=supprimer&id_livre=<?php echo $livre['id_livre']; ?>" onclick = "return confirm('Êtes-vous sûr de vouloir supprimer ce livre?' )">Supprimer</a>
</td>
</tr>
<?php endforeach;
?>
</table>
</div>

</body>
</html>
