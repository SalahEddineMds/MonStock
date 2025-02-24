<?php
    include("connexionbd.php");

    if (!empty($_GET["idFournisseur"]))
     {
        $idFournisseur = $_GET["idFournisseur"];
        $sql = "UPDATE fournisseur SET etat=? WHERE id=?";
        $req = $connexion->prepare($sql);
        $req->execute([0,$idFournisseur]);
    }


header("Location: ../vue/fournisseur.php");