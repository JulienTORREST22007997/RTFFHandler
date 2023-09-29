<?php
require_once './Database.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT t.*, a.display_name AS username FROM TICKET t LEFT JOIN ACCOUNT a ON t.author = a.account_id";
$stmt = $db->prepare($query);

try {
    $stmt->execute();
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
}
?>

<?php
require_once './navigation.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Votre Page</title>
</head>
<body>
<div style="margin-left:220px; padding:10px;">
    <h1>Liste des Posts</h1>
    <?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<div style='border: 1px solid #ccc; margin-bottom: 10px; padding: 10px;'>";
        echo "<h2>" . htmlspecialchars(isset($row['title']) ? $row['title'] : 'Titre inconnu') . "</h2>";
        echo "<p>" . htmlspecialchars(isset($row['message']) ? $row['message'] : 'Message inconnu') . "</p>";

        if (!empty($row['image_path'])) {
            echo "<img src='" . htmlspecialchars($row['image_path']) . "' alt='Image associée' style='width: 200px; height: auto;'/>";
        }

        echo "<p><strong>Auteur :</strong> " . htmlspecialchars(isset($row['username']) ? $row['username'] : 'Auteur inconnu') . "</p>";
        echo "<p><strong>Date :</strong> " . htmlspecialchars(isset($row['date']) ? $row['date'] : 'Date inconnue') . "</p>";
        echo "<a href='viewTicket.php?ticket_id=" . htmlspecialchars($row['ticket_id']) . "' style='padding: 10px; background-color: blue; color: white; text-decoration: none; border-radius: 5px;'>Répondre</a>";
        echo "</div>";
    }
    ?>
</div>
</body>
</html>
