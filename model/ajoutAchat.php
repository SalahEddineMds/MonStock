<?php
    include("connexionbd.php");
    include_once("function.php");

    if (!empty($_POST["id_article"])
        && !empty($_POST["id_fournisseur"])
        && !empty($_POST["quantite"])
        && !empty($_POST["prix"])) {


                
                $sql = "INSERT INTO achat(id_article, id_fournisseur, quantite, prix)
                VALUES(?, ?, ?, ?)";
                $req = $connexion->prepare($sql);

                $req->execute(array(
                $_POST["id_article"],
                $_POST["id_fournisseur"],
                $_POST["quantite"],
                $_POST["prix"]));

                if ($req->rowCount()!=0) {

                    $sql = "UPDATE article SET quantite = quantite+? WHERE id=?";
                    $req = $connexion->prepare($sql);

                    $req->execute(array(
                        $_POST["quantite"],
                        $_POST["id_article"]));

                        if ($req->rowCount()!=0) {
                            $_SESSION["message"]["text"] = "achat effectuéé avec succès";
                            $_SESSION["message"]["type"] = "success";
                        } else {
                            $_SESSION["message"]["text"] = "Impossible de faire cette achat";
                            $_SESSION["message"]["type"] = "danger";
                        }
                                        
                } else {
                    $_SESSION["message"]["text"] = "Une erreur s'est produite lors de la achat";
                    $_SESSION["message"]["type"] = "danger";
                }

                
    } else {
        $_SESSION["message"]["text"] = "Une information obligatoire non renseignée";
        $_SESSION["message"]["type"] = "danger";
    }

    header("Location: ../vue/achat.php");
?>