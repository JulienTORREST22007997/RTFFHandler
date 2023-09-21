<?php
require_once '../config/Database.php';

// Si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnection();

    $user_id = $_POST['user_id'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $display_name = $_POST['display_name'];

    $query = "INSERT INTO ACCOUNT (account_id, password, display_name,creation_date) VALUES (:user_id, :password, :display_name, NOW())";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':display_name', $display_name);
    // Liez également les autres paramètres

    if ($stmt->execute()) {
        echo "Utilisateur créé avec succès !";
    } else {
        echo "Une erreur est survenue lors de la création de l'utilisateur.";
    }
}
?>

<!-- Formulaire pour créer un utilisateur -->
<form method="post" action="createUser.php">
    Email: <input type="email" name="user_id" required><br>
    Mot de Passe: <input type="password" name="password" required><br>
    Nom d'Affichage: <input type="text" name="display_name" required><br>
    Image: <input type="text" name="image"><br>
    <input type="submit" value="Créer Utilisateur">
</form>
