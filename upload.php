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
        echo 'le fichier nest pas une image';
    }
}
?>