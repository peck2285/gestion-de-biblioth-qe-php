<?php
session_start();

if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
    include_once 'connexion.php';

    $email = $_POST[ 'email' ];
    $mot_de_passe = $_POST[ 'mot_de_passe' ];

    $email = $mysqli->real_escape_string( $email );

    $sql = "SELECT id_utilisateur, nom, prenom, role, mot_de_passe FROM utilisateurs WHERE email = '$email'";
    $result = $mysqli->query( $sql );

    if ( $result->num_rows == 1 ) {
        $row = $result->fetch_assoc();

        if ( md5( $mot_de_passe ) === $row[ 'mot_de_passe' ] ) {
            $erreur_message = 'Sucess';
            $_SESSION[ 'id_utilisateur' ] = $row[ 'id_utilisateur' ];
            $_SESSION[ 'nom' ] = $row[ 'nom' ];
            $_SESSION[ 'email' ] = $row[ 'email' ];
            $_SESSION[ 'prenom' ] = $row[ 'prenom' ];
            $_SESSION[ 'role' ] = $row[ 'role' ];

            if ( $row[ 'role' ] === 'administrateur' ) {
                echo 'Redirecting to admin.php';
                header( 'Location: admin.php' );
                exit();
            } else {
                echo 'Redirecting to client.php';
                header( 'Location: client.php' );
                exit();
            }

        } else {
            $erreur_message = 'Identifiants invalides. Veuillez réessayer.';
        }
    } else {
        $erreur_message = 'Identifiants invalides. Veuillez réessayer.';
    }

    $mysqli->close();
}
?>
<!DOCTYPE html>
<html lang = 'en'>
<head>
<meta charset = 'UTF-8'>
<meta name = 'viewport' content = 'width=device-width, initial-scale=1.0'>
<title>Connexion</title>
<link rel = 'stylesheet' href = 'styles.css'>
</head>
<body>

<div class = 'form-container'>
<h1>Connexion</h1>

<?php
if ( isset( $erreur_message ) ) {
    echo "<p class='erreur'>$erreur_message</p>";
}
?>

<form action = 'login.php' method = 'post'>
<label for = 'email'>Email :</label>
<input type = 'email' name = 'email' required>

<label for = 'mot_de_passe'>Mot de Passe :</label>
<input type = 'password' name = 'mot_de_passe' required>

<input type = 'submit' value = 'Se connecter'>
</form>
<a href = 'inscription.php'>S'inscrire</a>
</div>

</body>
</html>
