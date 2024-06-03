<?php
include_once 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = $_POST['titre'];
    $auteur = $_POST['auteur'];
    $isbn = $_POST['isbn'];
    $genre = $_POST['genre'];
    $disponible = isset($_POST['disponible']) ? 1 : 0;

    $mysqli->query("INSERT INTO livres (titre, auteur, isbn, genre, disponible) VALUES ('$titre', '$auteur', '$isbn', '$genre', $disponible)");

    header('Location: admin.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Livre</title>
</head>
<body>

    <h1>Ajouter un Livre</h1>

    <form action="" method="post">
        <label for="titre">Titre:</label>
        <input type="text" name="titre" required><br>

        <label for="auteur">Auteur:</label>
        <input type="text" name="auteur" required><br>

        <label for="isbn">ISBN:</label>
        <input type="text" name="isbn" required><br>

        <label for="genre">Genre:</label>
        <input type="text" name="genre" required><br>

        <label for="disponible">Disponible:</label>
        <input type="checkbox" name="disponible"><br>

        <input type="submit" value="Ajouter">
    </form>

</body>
</html>
