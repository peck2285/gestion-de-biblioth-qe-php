<?php
include_once 'connexion.php';

// Initialisez la variable $livre
$livre = array( 'titre' => '', 'auteur' => '', 'isbn' => '', 'genre' => '', 'disponible' => 0 );

// Initialisez les messages
$success_message = '';
$error_message = '';

// Vérifiez s'il y a un ID de livre dans l'URL
if ( $_SERVER[ 'REQUEST_METHOD' ] == 'GET' && isset( $_GET[ 'id_livre' ] ) ) {
    $id_livre = $_GET[ 'id_livre' ];

    // Récupérez les données du livre depuis la base de données
    $result = $mysqli->query( "SELECT * FROM livres WHERE id_livre = $id_livre" );

    if ( $result->num_rows == 1 ) {
        $livre = $result->fetch_assoc();
    }
}

// Si le formulaire est soumis
if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
    $id_livre = $_POST[ 'id_livre' ];
    $titre = $mysqli->real_escape_string( $_POST[ 'titre' ] );
    $auteur = $mysqli->real_escape_string( $_POST[ 'auteur' ] );
    $isbn = $mysqli->real_escape_string( $_POST[ 'isbn' ] );
    $genre = $mysqli->real_escape_string( $_POST[ 'genre' ] );
    $disponible = isset( $_POST[ 'disponible' ] ) ? 1 : 0;

    // Mettez à jour les données du livre dans la base de données
    $updateQuery = "UPDATE livres SET titre='$titre', auteur='$auteur', isbn='$isbn', genre='$genre', disponible=$disponible WHERE id_livre=$id_livre";

    if ( $mysqli->query( $updateQuery ) ) {
        // Mise à jour réussie
        $success_message = 'Les données ont été mises à jour avec succès.';
    } else {
        // Affichez un message d'erreur
        $error_message = "Erreur de mise à jour : " . $mysqli->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éditer un Livre</title>
</head>
<body>

    <h1>Éditer un Livre</h1>

    <?php
    // Affichez le message de réussite s'il y en a un
        if ( $success_message !== '' ) {
            echo "<script>alert('$success_message'); window.location.href = 'admin.php';</script>";
        }

        // Affichez le message d'erreur s'il y en a un
        if ( $error_message !== '' ) {
            echo "<p style='color: red;'>$error_message</p>";
        }
        ?>

        <form action = '' method = 'post'>
        <!-- Ajoutez un champ caché pour l'id_livre -->
        <input type="hidden" name="id_livre" value="<?php echo $livre['id_livre']; ?>">

        <label for="titre">Titre:</label>
        <input type="text" name="titre" value="<?php echo htmlspecialchars($livre['titre']); ?>" required><br>

        <label for="auteur">Auteur:</label>
        <input type="text" name="auteur" value="<?php echo htmlspecialchars($livre['auteur']); ?>" required><br>

        <label for="isbn">ISBN:</label>
        <input type="text" name="isbn" value="<?php echo htmlspecialchars($livre['isbn']); ?>" required><br>

        <label for="genre">Genre:</label>
        <input type="text" name="genre" value="<?php echo htmlspecialchars($livre['genre']); ?>" required><br>

        <label for="disponible">Disponible:</label>
        <input type="checkbox" name="disponible" <?php echo $livre['disponible'] ? 'checked' : '';
        ?>><br>

        <input type = 'submit' value = 'Enregistrer'>
        </form>

        </body>
        </html>
