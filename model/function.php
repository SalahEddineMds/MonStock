<?php

include("connexionbd.php");
function getArticle($id=null, $DONNErecherche = array()) {
    if (!empty($id)) {
        $sql = "SELECT nom_article, libelle_categorie, quantite, prix_unitaire, date_fabrication,
        date_expiration, id_categorie, a.id FROM article AS a, categorie_article AS c
        WHERE a.id_categorie = c.id AND a.id=?";

        $req = $GLOBALS["connexion"]->prepare($sql);

        $req->execute(array($id));

        return $req->fetch();

    } elseif (!empty($DONNErecherche)) {
        $recherche = "";
        extract($DONNErecherche);
        if (!empty($nom_article)) $recherche .= "AND a.nom_article LIKE '%$nom_article%' ";
        if (!empty($id_categorie)) $recherche .= " AND a.id_categorie = $id_categorie ";
        if (!empty($quantite)) $recherche .= " AND a.quantite = $quantite ";
        if (!empty($prix_unitaire)) $recherche .= " AND a.prix_unitaire = $prix_unitaire ";
        if (!empty($date_fabrication)) $recherche .= " AND DATE(a.date_fabrication) = '$date_fabrication' ";
        if (!empty($date_expiration)) $recherche .= " AND DATE(a.date_expiration) = '$date_expiration' ";

        $sql = "SELECT nom_article, libelle_categorie, quantite, prix_unitaire, date_fabrication,
        date_expiration, id_categorie, a.id FROM article AS a, categorie_article AS c
        WHERE a.id_categorie = c.id $recherche";

        $req = $GLOBALS["connexion"]->prepare($sql);

        $req->execute();

        return $req->fetchAll();
    } else {
        $sql = "SELECT nom_article, libelle_categorie, quantite, prix_unitaire, date_fabrication,
        date_expiration, id_categorie, a.id FROM article AS a, categorie_article AS c
        WHERE a.id_categorie = c.id";

        $req = $GLOBALS["connexion"]->prepare($sql);

        $req->execute();

        return $req->fetchAll();
    }
    
}

function getClient($id=null, $DONNErecherche = array()) {
    if (!empty($id)) {
        $sql = "SELECT * FROM client WHERE id=? AND etat=?";

        $req = $GLOBALS["connexion"]->prepare($sql);

        $req->execute(array($id,1));

        return $req->fetch();

    } elseif(!empty($DONNErecherche)) {
        $recherche= "";
        extract($DONNErecherche);
        if (!empty($nom)) $recherche .= "AND nom LIKE '%$nom%' ";
        if (!empty($prenom)) $recherche .= "AND prenom LIKE '%$prenom%' ";
        if (!empty($telephone)) $recherche .= "AND telephone LIKE '%$telephone%' ";
        if (!empty($adresse)) $recherche .= "AND adresse LIKE '%$adresse%' ";

        $sql = "SELECT * FROM client WHERE 1=1 $recherche";

        $req = $GLOBALS["connexion"]->prepare($sql);

        $req->execute();

        return $req->fetchAll();
    } else {
        $sql = "SELECT * FROM client WHERE etat=?";

        $req = $GLOBALS["connexion"]->prepare($sql);

        $req->execute(array(1));

        return $req->fetchAll();
    }
    
}

function getVente($id=null) {
    if (!empty($id)) {
        $sql = "SELECT v.id, nom, prenom, total, date_vente, adresse, telephone
         FROM client AS c, vente AS v WHERE v.id_client=c.id AND v.id=? AND v.etat=?";

        $req = $GLOBALS["connexion"]->prepare($sql);

        $req->execute(array($id,1));

        return $req->fetch();

    } else {
        $sql = "SELECT v.id, nom, prenom, total, date_vente
                FROM client AS c, vente AS v WHERE v.id_client=c.id AND v.etat=?";

        $req = $GLOBALS["connexion"]->prepare($sql);

        $req->execute(array(1));

        return $req->fetchAll();
    }
}

function getFournisseur($id=null, $DONNErecherche = array()) {
    if (!empty($id)) {
        $sql = "SELECT * FROM fournisseur WHERE id=? AND etat=?";

        $req = $GLOBALS["connexion"]->prepare($sql);

        $req->execute(array($id,1));

        return $req->fetch();

    } elseif(!empty($DONNErecherche)) {
        $recherche= "";
        extract($DONNErecherche);
        if (!empty($nom)) $recherche .= "AND nom LIKE '%$nom%' ";
        if (!empty($prenom)) $recherche .= "AND prenom LIKE '%$prenom%' ";
        if (!empty($telephone)) $recherche .= "AND telephone LIKE '%$telephone%' ";
        if (!empty($adresse)) $recherche .= "AND adresse LIKE '%$adresse%' ";

        $sql = "SELECT * FROM fournisseur WHERE 1=1 $recherche";

        $req = $GLOBALS["connexion"]->prepare($sql);

        $req->execute();

        return $req->fetchAll(); 

    } else {
        $sql = "SELECT * FROM fournisseur WHERE etat=?";

        $req = $GLOBALS["connexion"]->prepare($sql);

        $req->execute(array(1));

        return $req->fetchAll();
    }
    
}

function getCommande($id=null) {
    if (!empty($id)) {
        $sql = "SELECT co.id, nom_article, nom, prenom, co.quantite, prix, date_commande, prix_unitaire, adresse, telephone
         FROM fournisseur AS f, commande AS co, article AS a WHERE co.id_article=a.id AND co.id_fournisseur=f.id AND co.id=? AND co.etat=?";

        $req = $GLOBALS["connexion"]->prepare($sql);

        $req->execute(array($id, 1));

        return $req->fetch();

    } else {
        $sql = "SELECT co.id, nom_article, nom, prenom, co.quantite, prix, date_commande, a.id AS idArticle
                FROM fournisseur AS f, commande AS co, article AS a WHERE co.id_article=a.id AND co.id_fournisseur=f.id AND co.etat=?";

        $req = $GLOBALS["connexion"]->prepare($sql);

        $req->execute(array(1));

        return $req->fetchAll();
    }
}

function getAllCommande() {
    $sql = "SELECT COUNT(*) AS nbre FROM commande WHERE etat=?";
    $req = $GLOBALS["connexion"]->prepare($sql);

        $req->execute(array(1));

        return $req->fetch();
}

function getAllVente() {
    $sql = "SELECT COUNT(*) AS nbre FROM vente WHERE etat=?";
    $req = $GLOBALS["connexion"]->prepare($sql);

        $req->execute(array(1));

        return $req->fetch();
}

function getAllArticle() {
    $sql = "SELECT COUNT(*) AS nbre FROM article";
    $req = $GLOBALS["connexion"]->prepare($sql);

        $req->execute();

        return $req->fetch();
}

function getCA() {
    $sql = "SELECT SUM(total) AS total FROM vente";
    $req = $GLOBALS["connexion"]->prepare($sql);

        $req->execute();

        return $req->fetch();
}

function getLastVente($id = null) {
    $sql = "SELECT v.id, c.nom, c.prenom, v.total, v.date_vente
            FROM vente v
            JOIN client c ON v.id_client = c.id
            WHERE v.etat = ?
            ORDER BY v.date_vente DESC
            LIMIT 10"; 

    $req = $GLOBALS["connexion"]->prepare($sql);
    $req->execute([1]);

    return $req->fetchAll();
}



function getMostVente($id=null) {
    $sql = "SELECT a.nom_article, SUM(vl.prix) AS prix
            FROM vente v
            JOIN vente_ligne vl ON v.id = vl.id_vente
            JOIN article a ON vl.id_article = a.id
            WHERE v.etat = ?
            GROUP BY a.id, a.nom_article
            ORDER BY prix DESC
            LIMIT 10";

    $req = $GLOBALS["connexion"]->prepare($sql);
    $req->execute([1]);

    return $req->fetchAll();
}


function getCategorie($id=null) {
    if (!empty($id)) {
        $sql = "SELECT * FROM categorie_article WHERE id=? AND etat=?";

        $req = $GLOBALS["connexion"]->prepare($sql);

        $req->execute(array($id, 1));

        return $req->fetch();

    } else {
        $sql = "SELECT * FROM categorie_article WHERE etat=?";

        $req = $GLOBALS["connexion"]->prepare($sql);

        $req->execute(array(1));

        return $req->fetchAll();
    }
    
}

?>