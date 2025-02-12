<?php

include("connexionbd.php");
function getArticle($id=null) {
    if (!empty($id)) {
        $sql = "SELECT * FROM article WHERE id=?";

        $req = $GLOBALS["connexion"]->prepare($sql);

        $req->execute(array($id));

        return $req->fetch();

    } else {
        $sql = "SELECT * FROM article";

        $req = $GLOBALS["connexion"]->prepare($sql);

        $req->execute();

        return $req->fetchAll();
    }
    
}

function getClient($id=null) {
    if (!empty($id)) {
        $sql = "SELECT * FROM client WHERE id=?";

        $req = $GLOBALS["connexion"]->prepare($sql);

        $req->execute(array($id));

        return $req->fetch();

    } else {
        $sql = "SELECT * FROM client";

        $req = $GLOBALS["connexion"]->prepare($sql);

        $req->execute();

        return $req->fetchAll();
    }
    
}

function getVente($id=null) {
    if (!empty($id)) {
        $sql = "SELECT v.id, nom_article, nom, prenom, v.quantite, prix, date_vente, prix_unitaire, adresse, telephone
         FROM client AS c, vente AS v, article AS a WHERE v.id_article=a.id AND v.id_client=c.id AND v.id=?";

        $req = $GLOBALS["connexion"]->prepare($sql);

        $req->execute(array($id));

        return $req->fetch();

    } else {
        $sql = "SELECT v.id, nom_article, nom, prenom, v.quantite, prix, date_vente
                FROM client AS c, vente AS v, article AS a WHERE v.id_article=a.id AND v.id_client=c.id";

        $req = $GLOBALS["connexion"]->prepare($sql);

        $req->execute();

        return $req->fetchAll();
    }
}

function getFournisseur($id=null) {
    if (!empty($id)) {
        $sql = "SELECT * FROM fournisseur WHERE id=?";

        $req = $GLOBALS["connexion"]->prepare($sql);

        $req->execute(array($id));

        return $req->fetch();

    } else {
        $sql = "SELECT * FROM fournisseur";

        $req = $GLOBALS["connexion"]->prepare($sql);

        $req->execute();

        return $req->fetchAll();
    }
    
}

?>