<?php
include 'importBdd.php';
$mysqli = importBdd();

$connectedId = intval($_SESSION['connected_id']);
$userId = $connectedId;

$targetDir = "uploads/"; // Dossier où les images téléchargées seront stockées
$targetFile = $targetDir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// Vérifie si le fichier est une image réelle ou une fausse image
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        echo "Le fichier est une image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo 'le fichier n\'est pas une image';
        $uploadOk = 0;
    }
}

// Vérifie si le fichier existe déjà
if (file_exists($targetFile)) {
    echo "Désolé, le fichier existe déjà.";
    $uploadOk = 0;
}

// Vérifie la taille du fichier
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Désolé, votre fichier est trop volumineux.";
    $uploadOk = 0;
}

// Autorise certains formats de fichier
$allowedFileTypes = array("jpg", "jpeg", "png", "gif");
if (!in_array($imageFileType, $allowedFileTypes)) {
    echo "Désolé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
    $uploadOk = 0;
}

// Vérifie si $uploadOk est défini à 0 à la suite d'une erreur
if ($uploadOk == 0) {
    echo "Désolé, votre fichier n'a pas été téléchargé.";
} else {
    // Si tout est ok, tente de télécharger le fichier
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
        // Enregistre le chemin du fichier dans la base de données pour l'utilisateur connecté
        $updateQuery = "UPDATE users SET profile_image = '$targetFile' WHERE id = $connectedId";
        $mysqli->query($updateQuery);
        echo "Le fichier " . basename($_FILES["fileToUpload"]["name"]) . " a été téléchargé avec succès.";
    } else {
        echo "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
    }
}
?>