<?php
require_once '../config/Database.php';

// uploadProfileImage.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnection();
    if (isset($_FILES['profileImage'])) {
        $image = $_FILES['profileImage'];
        if ($image['error'] == 0) {

            $fileName = uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);

            $destination = 'uploads/' . $fileName;
            if (move_uploaded_file($image['tmp_name'], $destination)) {
                $userId = 'someUserId'; 
                $updateQuery = "UPDATE ACCOUNT SET image = :image WHERE user_id = :userId";

                $stmt = $db->prepare($updateQuery);
                $stmt->bindParam(':image', $destination);
                $stmt->bindParam(':userId', $userId);

                if ($stmt->execute()) {
                    echo "L'image de profil a été mise à jour avec succès !";
                } else {
                    echo "Une erreur est survenue lors de la mise à jour de l'image de profil.";
                }
            } else {
                echo "Une erreur est survenue lors du déplacement du fichier.";
            }
        } else {
            echo "Une erreur est survenue lors du téléchargement du fichier.";
        }
    } else {
        echo "Aucun fichier n'a été téléchargé.";
    }
}
?>

<?php ?>
    <!-- uploadProfileImage.php -->
    <form action="uploadProfileImage.php" method="post" enctype="multipart/form-data">
        Sélectionnez une image de profil :
        <input type="file" name="profileImage" accept="image/*" required>
        <input type="submit" value="Télécharger l'image" name="submit">
    </form>
<?php?>
