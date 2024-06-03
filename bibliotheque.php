<?php
session_start();

// Inclure la connexion à la base de données
include_once 'connexion.php';

// Liste des livres par genre
$livres_par_genre = array(
    "Poétiques" => array(
        array("titre" => "LE DÉVERSOIR", "auteur" => "Arthur Teboul", "isbn" => "978-1-234567-89-7", "disponible" => true),
        array("titre" => "LE POÈTE ET SON OMBRE", "auteur" => "Paul Eluard", "isbn" => "978-1-234567-89-8", "disponible" => true),
        array("titre" => "UN JOUR, UN POÈME", "auteur" => "Jean Orizet", "isbn" => "978-1-234567-89-9", "disponible" => true)
    ),
    "Théâtre" => array(
        array("titre" => "L'Illusion comique", "auteur" => "Pierre Corneille", "isbn" => "978-1-234567-90-0", "disponible" => true),
        array("titre" => "Le Cid", "auteur" => "Pierre Corneille", "isbn" => "978-1-234567-90-1", "disponible" => true),
        array("titre" => "Horace", "auteur" => "Pierre Corneille", "isbn" => "978-1-234567-90-2", "disponible" => true)
    ),
    "Romans épistolaires" => array(
        array("titre" => "Lettre au père", "auteur" => "Franz Kafka", "isbn" => "978-1-234567-90-3", "disponible" => true),
        array("titre" => "Lettre à la jeunesse - Lettre à la France", "auteur" => "Émile Zola", "isbn" => "978-1-234567-90-4", "disponible" => true),
        array("titre" => "Un été au Kansai", "auteur" => "Romain Slocombe", "isbn" => "978-1-234567-90-5", "disponible" => true),
        array("titre" => "Lettres Persanes", "auteur" => "Montesquieu", "isbn" => "978-1-234567-90-6", "disponible" => true)
    )
);

// Enregistrer les livres dans la base de données
foreach ($livres_par_genre as $genre => $livres) {
    foreach ($livres as $livre) {
        $titre = $mysqli->real_escape_string($livre['titre']);
        $auteur = $mysqli->real_escape_string($livre['auteur']);
        $isbn = $mysqli->real_escape_string($livre['isbn']);
        $disponible = $livre['disponible'] ? 1 : 0;

        // Vérifier si le livre existe déjà
        $result = $mysqli->query("SELECT id_livre FROM livres WHERE titre = '$titre' AND auteur = '$auteur'");
        if ($result->num_rows == 0) {
            // Ajouter le livre à la base de données
            $mysqli->query("INSERT INTO livres (titre, auteur, genre, isbn, disponible) VALUES ('$titre', '$auteur', '$genre', '$isbn', $disponible)");
        }
    }
}

// Récupérer la liste complète des livres
$result = $mysqli->query("SELECT id_livre, titre, auteur, genre, isbn, disponible FROM livres");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliothèque</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="bibliotheque-container">
        <h1>Bibliothèque</h1>

        <!-- Formulaire de recherche -->
        <form action="bibliotheque.php" method="get" class="recherche-form">
            <label for="recherche">Recherche par Titre, Auteur ou Genre :</label>
            <input type="text" name="recherche" placeholder="Entrez le titre, l'auteur ou le genre...">
            <input type="submit" value="Rechercher">
        </form>

        <!-- Options de filtre -->
        <div class="livres-filtres">
            <label for="filtre">Filtrer par :</label>
            <select name="filtre" id="filtre">
                <option value="tous">Tous les livres</option>
                <option value="disponibles">Livres disponibles</option>
                <option value="empruntes">Livres empruntés</option>
            </select>
        </div>

        <?php
        // Afficher les livres par genre
        foreach ($livres_par_genre as $genre => $livres) {
            echo "<div class='livres-section $genre'>";
            echo "<h2>$genre</h2>";

            foreach ($livres as $livre) {
                echo "<div class='livre'>";
                echo "<h3>{$livre['titre']}</h3>";
                echo "<p>Auteur: {$livre['auteur']}</p>";
                echo "<p>ISBN: {$livre['isbn']}</p>";
                echo "<p>Disponible: " . ($livre['disponible'] ? 'Oui' : 'Non') . "</p>";
                echo "</div>";
            }

            echo "</div>";
        }
        ?>
    </div>

</body>
</html>
