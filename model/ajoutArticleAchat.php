<?php
session_start();
include("connexionbd.php");

if (!empty($_POST["id_achat"]) && !empty($_POST["id_article"]) && !empty($_POST["quantite"])) {
    $id_achat = $_POST["id_achat"];
    $id_article = $_POST["id_article"];
    $quantite = $_POST["quantite"];

    // prix article
    $sql = "SELECT prix_unitaire, quantite FROM article WHERE id = ?";
    $req = $connexion->prepare($sql);
    $req->execute([$id_article]);
    $article = $req->fetch();

    if (!$article) {
        $_SESSION["message"]["text"] = "Article non trouvé.";
        $_SESSION["message"]["type"] = "danger";
        header("Location: ../vue/achat.php?id_achat=" . $id_achat);
        exit;
    }

    $prix_unitaire = $article["prix_unitaire"];
    $total_price = $prix_unitaire * $quantite;

    // insert into achat_ligne
    $sql = "INSERT INTO achat_ligne (id_achat, id_article, quantite, prix) VALUES (?, ?, ?, ?)";
    $req = $connexion->prepare($sql);
    $req->execute([$id_achat, $id_article, $quantite, $total_price]);

    // misajour total
    $sql = "UPDATE achat SET total = total + ? WHERE id = ?";
    $req = $connexion->prepare($sql);
    $req->execute([$total_price, $id_achat]);

    //update quantite
    $sql = "UPDATE article SET quantite = quantite + ? WHERE id = ?";
    $req = $connexion->prepare($sql);
    $req->execute([$quantite, $id_article]);

    $_SESSION["message"]["text"] = "Article ajouté avec succès.";
    $_SESSION["message"]["type"] = "success";
} else {
    $_SESSION["message"]["text"] = "Veuillez remplir tous les champs.";
    $_SESSION["message"]["type"] = "danger";
}

header("Location: ../vue/achat.php?id_achat=" . $id_achat);
exit;
?>
