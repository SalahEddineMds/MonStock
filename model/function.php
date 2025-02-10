<?php

include("connexionbd.php");
function getArticle() {
    $sql = "SELECT * FROM article";

    $req = $GLOBALS["connexion"]->prepare($sql);

    $req->execute();

    return $req->fetchAll();
}

?>