<?php
    include("connexionbd.php");
    if (!empty($_POST["nom_article"])
        && !empty($_POST["id_categorie"])
        && !empty($_POST["prix_unitaire"])) {

        $sql = "INSERT INTO $nom_base_de_donnes.article(nom_article, id_categorie, quantite, prix_unitaire, date_fabrication, date_expiration)
                VALUES (?, ?, ?, ?, ?, ?)";
            $req = $connexion->prepare($sql);

            $req->execute(array(
                $_POST["nom_article"],
                $_POST["id_categorie"],
                $_POST["quantite"],
                $_POST["prix_unitaire"],
                $_POST["date_fabrication"],
                $_POST["date_expiration"]));

                if ($req->rowCount()!=0) {
                    $_SESSION["message"]["text"] = "Article ajouté avec succès";
                    $_SESSION["message"]["type"] = "success";
                } else {
                    $_SESSION["message"]["text"] = "Une erreur s'est produite lors de l'ajout de l'article";
                    $_SESSION["message"]["type"] = "danger";
                }
                
    } else {
        $_SESSION["message"]["text"] = "Une information obligatoire non renseignée";
        $_SESSION["message"]["type"] = "danger";
    }

    header("Location: ../vue/article.php");
?>