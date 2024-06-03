<?php
include_once 'connexion.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["id_utilisateur"])) {
    header("Location: login.php");
    exit();
}

// Vérifiez s'il y a un ID de livre dans l'URL
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id_livre'])) {
    $id_livre = $_GET['id_livre'];

    // Utilisez des requêtes préparées pour éviter les injections SQL
    $stmt = $mysqli->prepare("SELECT * FROM livres WHERE id_livre = ?");
    $stmt->bind_param("i", $id_livre);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $livre = $result->fetch_assoc();
    } else {
        // Affichez l'erreur directement sur la page
        $erreur_message = "Livre non trouvé";
    }
    $stmt->close();
}

// Traiter le formulaire d'emprunt
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date_retour = $_POST['date_retour'];
    $id_utilisateur = $_SESSION['id_utilisateur'];
    $date_emprunt = date('Y-m-d');

    // Utilisez des requêtes préparées pour éviter les injections SQL
    $stmt = $mysqli->prepare('INSERT INTO emprunts (id_livre, id_utilisateur, date_emprunt, date_retour) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('iiss', $_GET['id_livre'], $id_utilisateur, $date_emprunt, $date_retour);

    if ($stmt->execute()) {
        $message = 'Emprunt enregistré avec succès.';
    } else {
        // Affichez l'erreur directement sur la page
        $erreur_message = "Erreur lors de l'enregistrement de l'emprunt. Veuillez réessayer.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang='fr'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Emprunter Livre</title>
    <link rel='stylesheet' href='emprunt.css'>
    <!-- Ajoutez d'autres liens CSS si nécessaire -->
</head>

<body>

    <div class='navbar'>
        <div class='navbar-left'>
            <a href='client.php' class='logo'>Ma Bibliothèque</a>
        </div>
        <div class='navbar-right'>
            <?php if (isset($_SESSION['nom'])) : ?>
                <span><?php echo $_SESSION['nom']; ?></span>
                <button class='logout-btn' onclick="window.location.href='logout.php'">Déconnexion</button>
            <?php else : ?>
                <a href='login.php'>Connexion</a>
                <a href=''>Inscription</a>
            <?php endif; ?>
        </div>
    </div>

    <div class='container'>
        <div class='livre-details'>
            <?php if (isset($livre)) : ?>
                <h2><?php echo $livre['titre']; ?></h2>
                <p>Auteur: <?php echo $livre['auteur']; ?></p>
                <!-- Ajoutez d'autres détails du livre ici -->
            <?php else : ?>
                <p>Les détails du livre ne sont pas disponibles.</p>
            <?php endif; ?>
        </div>

        <div class='emprunt-form'>
            <?php
            if (isset($message)) {
                echo "<p class='success-message'>$message</p>";
            }
            if (isset($erreur_message)) {
                echo "<p class='error-message'>$erreur_message</p>";
            }
            ?>
            <form method='post'>
                <label for='date_retour'>Date de retour :</label>
                <input type='date' name='date_retour' required>
                <button type='submit'>Confirmer l'emprunt</button>
            </form>
        </div>

        <div class='emprunts-table'>
            <h2>Emprunts en cours</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID Emprunt</th>
                        <th>Titre Livre</th>
                        <th>Auteur</th>
                        <th>Date Retour</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Sélectionnez les emprunts depuis la base de données
                    $result_emprunts = $mysqli->query("SELECT id_emprunt, id_livre, id_utilisateur, date_retour FROM emprunts WHERE id_utilisateur = " . $_SESSION['id_utilisateur']);

                    // Affichez les résultats dans le tableau
                    while ($emprunt = $result_emprunts->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$emprunt['id_emprunt']}</td>";

                        // Récupérer les informations du livre
                        $stmt_livre = $mysqli->prepare("SELECT titre, auteur FROM livres WHERE id_livre = ?");
                        $stmt_livre->bind_param("i", $emprunt['id_livre']);
                        $stmt_livre->execute();
                        $result_livre = $stmt_livre->get_result();
                        $livre_info = $result_livre->fetch_assoc();
                        $stmt_livre->close();

                        echo "<td>{$livre_info['titre']}</td>";
                        echo "<td>{$livre_info['auteur']}</td>";
                        echo "<td>{$emprunt['date_retour']}</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

</body>

</html>
