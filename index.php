<?php
include_once 'connexion.php';

// Fonction pour obtenir la liste des livres depuis la base de données

function getLivres( $filterGenre = null, $filterAuteur = null, $search = null )
 {
    global $mysqli;
    $livres = array();

    $query = 'SELECT * FROM livres WHERE 1';

    if ( $filterGenre ) {
        $query .= " AND genre = '$filterGenre'";
    }

    if ( $filterAuteur ) {
        $query .= " AND auteur = '$filterAuteur'";
    }

    if ( $search ) {
        $query .= " AND titre LIKE '%$search%'";
    }

    $result = $mysqli->query( $query );

    while ( $row = $result->fetch_assoc() ) {
        $livres[] = $row;
    }

    return $livres;
}

// Fonction pour obtenir la liste des genres depuis la base de données

function getGenres()
 {
    global $mysqli;
    $genres = array();

    $result = $mysqli->query( 'SELECT DISTINCT genre FROM livres' );

    while ( $row = $result->fetch_assoc() ) {
        $genres[] = $row[ 'genre' ];
    }

    return $genres;
}

// Fonction pour obtenir la liste des auteurs depuis la base de données

function getAuteurs()
 {
    global $mysqli;
    $auteurs = array();

    $result = $mysqli->query( 'SELECT DISTINCT auteur FROM livres' );

    while ( $row = $result->fetch_assoc() ) {
        $auteurs[] = $row[ 'auteur' ];
    }

    return $auteurs;
}

// Variables pour les filtres et la recherche
$filterGenre = isset( $_GET[ 'genre' ] ) ? $_GET[ 'genre' ] : null;
$filterAuteur = isset( $_GET[ 'auteur' ] ) ? $_GET[ 'auteur' ] : null;
$search = isset( $_GET[ 'search' ] ) ? $_GET[ 'search' ] : null;

// Obtenir la liste des livres, genres et auteurs en fonction des filtres et de la recherche
$livres = getLivres( $filterGenre, $filterAuteur, $search );
$genres = getGenres();
$auteurs = getAuteurs();
?>

<!DOCTYPE html>
<html lang = 'en'>
<head>
<meta charset = 'UTF-8'>
<meta name = 'viewport' content = 'width=device-width, initial-scale=1.0'>
<title>Ma Bibliothèque</title>
<link rel = 'stylesheet' href = 'index.css'>
<!-- Ajoutez d'autres liens CSS si nécessaire -->
</head>
<body>

<div class="navbar">
    <div class="navbar-left">
        <a href="#" class="logo">Ma Bibliothèque</a>
    </div>
    <div class="navbar-right">
        <a href="login.php">Connexion</a>
        <a href="#">Inscription</a>
    </div>
</div>

<div class="container">
    <div class="search-bar">
        <form method="GET">
            <input type="text" name="search" placeholder="Rechercher par nom du livre" value="<?php echo $search; ?>">
            <select name="genre">
                <option value="">Filtrer par genre</option>
                <?php foreach ($genres as $genre) : ?>
                    <option value="<?php echo $genre; ?>" <?php echo ($filterGenre === $genre) ? 'selected' : ''; ?>><?php echo $genre; ?></option>
                <?php endforeach; ?>
            </select>
            <select name="auteur">
                <option value="">Filtrer par auteur</option>
                <?php foreach ($auteurs as $auteur) : ?>
                    <option value="<?php echo $auteur; ?>" <?php echo ($filterAuteur === $auteur) ? 'selected' : ''; ?>><?php echo $auteur; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Rechercher</button>
        </form>
    </div>

    <div class="livres-list">
        <?php if (empty($livres)) : ?>
            <p>Aucun résultat trouvé.</p>
        <?php else : ?>
            <?php foreach ($livres as $livre) : ?>
                <div class="livre-card">
                    <img src="images/image.jpeg" alt="Image du livre">
                    <h3><?php echo $livre['titre']; ?></h3>
                    <p>Auteur: <?php echo $livre['auteur']; ?></p>
                    <p>ISBN: <?php echo $livre['isbn']; ?></p>
                    <p>Genre: <?php echo $livre['genre']; ?></p>
                    <p>Disponible: <?php echo ($livre['disponible'] ? 'Oui' : 'Non' );
?></p>
<a href = 'login.php' class = 'emprunter-btn'>Emprunter</a>
</div>
<?php endforeach;
?>
<?php endif;
?>
</div>
</div>

</body>
</html>
                