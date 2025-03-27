<?php
    include("connexionbd.php");

    if (!empty($_GET["idAchat"]) && 
        !empty($_GET["idArticle"]) && 
        !empty($_GET["quantite"])
       )
     {
        $sql = "UPDATE achat SET etat=? WHERE id=?";
        $req = $connexion->prepare($sql);
        $req->execute(array(0,$_GET["idAchat"]));
            if ($req->rowCount()!=0) {
                $sql = "UPDATE article SET quantite=quantite-? WHERE id=?";
                $req = $connexion->prepare($sql);
                $req->execute(array($_GET["quantite"],$_GET["idArticle"]));
            }
    }


header("Location: ../vue/achat.php");