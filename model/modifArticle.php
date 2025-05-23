<?php
    include("connexionbd.php");
    if (!empty($_POST["nom_article"])
        && !empty($_POST["id_categorie"])
        && !empty($_POST["prix_vente_unitaire"])
        && !empty($_POST["prix_achat_unitaire"])
        && !empty($_POST["id"])) {

        $sql = "UPDATE article SET nom_article=?, id_categorie=?, quantite=?, prix_vente_unitaire=?, prix_achat_unitaire=?,
                date_fabrication=?, date_expiration=? WHERE id=?";
            $req = $connexion->prepare($sql);

            $req->execute(array(
                $_POST["nom_article"],
                $_POST["id_categorie"],
                $_POST["quantite"],
                $_POST["prix_vente_unitaire"],
                $_POST["prix_achat_unitaire"],
                $_POST["date_fabrication"],
                $_POST["date_expiration"],
                $_POST["id"]
                ));

                if ($req->rowCount()!=0) {
                    $_SESSION["message"]["text"] = "Article modifié avec succès";
                    $_SESSION["message"]["type"] = "success";
                } else {
                    $_SESSION["message"]["text"] = "Rien a été modifié";
                    $_SESSION["message"]["type"] = "warning";
                }
                
    } else {
        $_SESSION["message"]["text"] = "Une information obligatoire non renseignée";
        $_SESSION["message"]["type"] = "danger";
    }

    header("Location: ../vue/article.php");
?>