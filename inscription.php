<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once 'connexion.php';

    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $email = $_POST["email"];
    $mot_de_passe = $_POST["mot_de_passe"];
    $role = $_POST["role"];

    $mot_de_passe_hash = md5($mot_de_passe);

    $nom = $mysqli->real_escape_string($nom);
    $prenom = $mysqli->real_escape_string($prenom);
    $email = $mysqli->real_escape_string($email);

    $sql = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES ('$nom', '$prenom', '$email', '$mot_de_passe_hash', '$role')";

    if ($mysqli->query($sql) === TRUE) {
        $_SESSION["inscription_success"] = true;
        header("Location: login.php"); // Rediriger vers la page de connexion
        exit();
    } else {
        $erreur_message = "Erreur lors de l'inscription : " . $mysqli->error;
    }

    $mysqli->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="form-container">
        <h1>Inscription</h1>

        <?php
        if (isset($erreur_message)) {
            echo "<p class='erreur'>$erreur_message</p>";
        }

        if (isset($_SESSION["inscription_success"]) && $_SESSION["inscription_success"] == true) {
            echo "<p class='succes'>Inscription réussie. Connectez-vous maintenant.</p>";
            unset($_SESSION["inscription_success"]);
        }
        ?>

        <form action="inscription.php" method="post">
            <label for="nom">Nom :</label>
            <input type="text" name="nom" required>

            <label for="prenom">Prénom :</label>
            <input type="text" name="prenom" required>

            <label for="email">Email :</label>
            <input type="email" name="email" required>

            <label for="mot_de_passe">Mot de Passe :</label>
            <input type="password" name="mot_de_passe" required>

            <label for="role">Rôle :</label>
            <select name="role" required>
                <option value="utilisateur">Utilisateur</option>
                <option value="administrateur">Administrateur</option>
            </select>

            <input type="submit" value="S'inscrire">
        </form>
<a href = 'login.php'>Se connecter</a>

    </div>

</body>
</html>
