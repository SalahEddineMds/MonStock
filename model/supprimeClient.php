<?php
    include("connexionbd.php");

    if (!empty($_GET["idClient"]))
     {
        $idClient = $_GET["idClient"];
        $sql = "UPDATE client SET etat=? WHERE id=?";
        $req = $connexion->prepare($sql);
        $req->execute([0,$idClient]);
    }


header("Location: ../vue/client.php");