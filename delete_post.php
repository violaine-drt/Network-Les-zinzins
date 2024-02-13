<?php
include 'importBdd.php';
$mysqli = importBdd();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['connected_id'])) {
    // Sinon il est envoyé sur la page de connexion 
    // (pas nécessaire puisque le mur n'est de toute façon pas accessible mais on sait jamais)
    header("Location: login.php");
    exit(); // Ici on sort après redirection (sécurité)
}

// On vérifie l'id du post
if (isset($_GET['post_id'])) {
    $postId = intval($_GET['post_id']);

    // On supprime les likes également de la base de donnés -> conflit
    $deleteLikesQuery = "DELETE FROM likes WHERE post_id = $postId";
    $resultLikes = $mysqli->query($deleteLikesQuery);

    if (!$resultLikes) {
        echo "Erreur lors de la suppression des likes du post : " . $mysqli->error;
        exit(); // Annuler si la suppression des likes échoues (sécurité)
    }

    // Idem pour les tags
    $deleteTagsQuery = "DELETE FROM posts_tags WHERE post_id = $postId";
    $resultTags = $mysqli->query($deleteTagsQuery);

    if (!$resultTags) {
        echo "Erreur lors de la suppression des tags du post : " . $mysqli->error;
        exit(); // Annuler si la suppression des tags échoues (sécurité)
    }

    // Ici on supprime le post en lui-même
    $deleteQuery = "DELETE FROM posts WHERE id = $postId";
    $result = $mysqli->query($deleteQuery);

    if ($result) {
        // On retourne sur Wall automatiquement
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        echo "Erreur lors de la suppression du post : " . $mysqli->error;
    }
} else {
    echo "ID de post non spécifié.";
}
?>