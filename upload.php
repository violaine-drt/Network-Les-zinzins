<?php
include 'importBdd.php';
$mysqli = importBdd();

$connectedId = intval($_SESSION['connected_id']);
$userId = $connectedId;

// Dossier où les images téléchargées seront stockées
$dossierCible = "uploads/"; 
$fichierCible = $dossierCible . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($fichierCible, PATHINFO_EXTENSION));

// Vérifie si le fichier est bien une image
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        echo "Le fichier est une image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo 'Le fichier n\'est pas une image';
        $uploadOk = 0;
    }
}

// Vérifie la taille du fichier
if ($_FILES["fileToUpload"]["size"] > 1500000) {
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
    if (!file_exists($dossierCible)) {
        mkdir($dossierCible, 0777, true);
    }

    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $fichierCible)) {
        // Utiliser une requête préparée pour améliorer la sécurité
        $updateQuery = $mysqli->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
        $updateQuery->bind_param("si", $fichierCible, $connectedId);
        $updateQuery->execute();
        $updateQuery->close();

        echo "Le fichier " . basename($_FILES["fileToUpload"]["name"]) . " a été téléchargé avec succès.";
    } else {
        echo "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
    }
}

?>
<p><a href="settings.php?user_id=<?php echo $userId; ?>">Pour être redirigé.e, cliquez ici</a>.</p>