<?php
require_once './Database.php'; // Chemin à adapter en fonction de votre structure de projet.
session_start(); // Démarrer la session

if(!isset($_SESSION['account_id'])) {
    header('Location: connectUser.php'); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    exit;
}
// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $message = $_POST['message'];

    $database = new Database();
    $db = $database->getConnection();
    $author = $_SESSION['account_id'];

    $image_path = null;

    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["image"]["name"];
        $filetype = $_FILES["image"]["type"];
        $filesize = $_FILES["image"]["size"];

        // Vérifie l'extension du fichier
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) die("Erreur : Veuillez sélectionner un format de fichier valide.");

        // Vérifie la taille du fichier - 5MB maximum
        $maxsize = 5 * 1024 * 1024;
        if($filesize > $maxsize) die("Erreur : La taille du fichier est supérieure à la limite autorisée.");

        // Vérifie le type MIME du fichier
        if(in_array($filetype, $allowed)){
            $upload_dir = 'uploads/';
            $uploaded_file = $upload_dir . uniqid() . '.' . $ext;

            if(move_uploaded_file($_FILES["image"]["tmp_name"], $uploaded_file)){
                $image_path = $uploaded_file;
            } else{
                echo "Erreur: Il y a eu un problème lors de l'upload de votre fichier. Veuillez réessayer.";
            }
        } else{
            echo "Erreur: Il y a eu un problème lors de l'upload de votre fichier. Veuillez réessayer.";
        }
    }

    $query = "INSERT INTO TICKET (title, message, image_path, date, author) VALUES (:title, :message, :image_path, NOW(), :author)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':message', $message);
    $stmt->bindParam(':image_path', $image_path);
    $stmt->bindParam(':author', $author);

    if($stmt->execute()) {
        echo "Post créé avec succès !";
    } else {
        echo "Une erreur est survenue lors de la création du post.";
    }
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
    <!-- Le contenu de votre page -->
</div>
</body>
</html>

<form method="post" action="createPost.php" enctype="multipart/form-data">
    Titre: <input type="text" name="title" required><br>
    Message: <textarea name="message" required></textarea><br>
    Image: <input type="file" name="image"><br>
    <input type="submit" value="Créer Post">
</form>
