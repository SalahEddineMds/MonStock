<?php 
    include("entete.php");

    if (!empty($_GET["id_achat"])) {
        $article = getAchat($_GET["id_achat"]);
    }
?>

<div class="home-content">
    <?php if (empty($_GET["id_achat"])): ?>
        <button onclick="createNewAchat()" class="valider" style="margin-left: 25px; margin-bottom: 5px;">Nouveau Achat</button>
    <?php endif; ?>  
    <div class="overview-boxes">
        <div class="va-container">

            <?php if (!empty($_GET["id_achat"])): ?>
            <div class="vabox">
                <form action="../model/ajoutFournisseurAchat.php" method="post" id="achat-form">
                    <input type="hidden" name="id_achat" value="<?= $_GET['id_achat'] ?>">
                    <label for="id_fournisseur">Fournisseur</label>
                        <select name="id_fournisseur" id="id_fournisseur" class="tom-select">
                        <?php 
                            $fournisseurs = getFournisseur();
                            if (!empty($fournisseurs) && is_array($fournisseurs)) {
                                foreach ($fournisseurs as $key => $value) {
                                    $selected = (!empty($achat) && $achat["id_fournisseur"] == $value["id"]) ? "selected" : "";
                                    echo "<option value='{$value["id"]}' {$selected}>{$value["nom"]} {$value["prenom"]}</option>";
                                }
                            }
                        ?>  
                        </select>
                        <button class="valider" type="submit">Valider Achat</button>
                </form>
                <div class="vabox">
                    <form action="../model/ajoutArticleAchat.php" method="post" id="article-form">
                        <input type="hidden" name="id_achat" value="<?= $_GET['id_achat'] ?>">
                        <label for="id_article">Article</label>
                        <select onchange="setPrix()" name="id_article" id="id_article" class="tom-select">
                            <option value="" disabled selected>Choisir un article</option>
                            <?php 
                                $articles = getArticle();
                                if (!empty($articles) && is_array($articles)) {
                                    foreach ($articles as $key => $value) {
                                        echo "<option data-prix='{$value["prix_achat_unitaire"]}' value='{$value["id"]}'>{$value["nom_article"]} - {$value["quantite"]} disponible</option>";                                   
                                    }
                                }
                            ?> 
                        </select>

                        <label for="quantite">Quantité</label>
                        <input onkeyup="setPrix()" type="number" name="quantite" id="quantite" placeholder="Veuillez saisir la quantité" min="1">

                        <label for="prix_u">Prix unitaire</label>
                        <input onkeyup="setPrix()" type="number" name="prix_u" id="prix_u" placeholder="Veuillez saisir le prix unitaire" min="0" step="any">

                        <label for="prix">Prix total</label>
                        <input type="number" name="prix" id="prix" placeholder="Prix total" min="0" step="any" readonly>

                        <button type="submit">Ajouter</button>

                        <?php
                        if (!empty($_SESSION["message"]["text"])) {
                        ?>
                            <div class="alert <?=$_SESSION["message"]["type"]?>">
                                <?=$_SESSION["message"]["text"]?>
                            </div>
                        <?php 
                        unset($_SESSION["message"]); // Efface le message après affichage
                        }
                        ?>
                    </form>    
                </div>                  
            </div>
            <div style="display: block;" class="box">
                    <h3>Détails de l'achat</h3>
                    <table class="mtable">
                        <tr>
                            <th>Article</th>
                            <th>Quantité</th>
                            <th>Prix unitaire</th>
                            <th>Prix total</th>
                            <th>Action</th>
                        </tr>
                        <?php
                        // Récupérer les lignes de l'achat actuelle
                        $lignes_achat = getAchatLignes($_GET["id_achat"]);
                        $total_achat = 0;
                        
                        if (!empty($lignes_achat) && is_array($lignes_achat)) {
                            foreach ($lignes_achat as $ligne) {
                                $total_achat += $ligne["prix"];
                                $prix_uni = $ligne["prix"] / $ligne["quantite"]
                        ?>
                        <tr>
                            <td><?= $ligne["nom_article"] ?></td>
                            <td><?= $ligne["quantite"] ?></td>
                            <td><?= $prix_uni ?></td>
                            <td><?= $ligne["prix"] ?></td>
                            <td>
                                <a onclick="modifierLigne(<?= $ligne['id'] ?>)" title="Modifier" style="color: blue !important; cursor: pointer;"><i class='bx bx-edit-alt'></i></a>
                                <a onclick="supprimerLigne(<?= $ligne['id'] ?>, <?= $_GET['id_achat'] ?>)" title="Supprimer" style="color: red !important; cursor: pointer;"><i class='bx bx-x-circle'></i></a>
                            </td>
                        </tr>
                        <?php
                            }
                        } else {
                        ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">Aucun article dans cette vente</td>
                        </tr>
                        <?php
                        }
                        ?>
                    </table>
                    <div style="margin-top: 20px; text-align: right; padding-right: 20px;">
                        <strong>Total de la vente: <?= number_format($total_achat, 2) ?></strong>
                    </div>
                </div>
            </div>
            <?php else: ?>

            <div style="display: block;" class="box">
                <form action="" method="get">
                    <table class="mtable">
                        <tr>
                            <th>Fournisseur</th>
                            <th>Montant</th>
                            <th>Date</th>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="nom_p_fournisseur" id="nom_p_fournisseur" placeholder="Veuillez saisir le nom ou prenom">
                            </td>
                            <td>
                                <input type="number" name="montant" id="montant" placeholder="Veuillez saisir le montant" min="0" step="any">
                            </td>
                            <td>
                                <input type="date" name="date" id="date">
                            </td>
                        </tr>
                    </table>
                    <br>
                    <button type="submit">Recherche</button>
                </form>
                <br>

                <table class="mtable">
                    <tr>
                        <th>Fournisseur</th>
                        <th>Montant</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    if (!empty($_GET)) {
                        $achats = getAchat(null, $_GET);
                    } else {
                        $achats = getAchat();
                    }
                    if (!empty($achats) && is_array( $achats )) {
                        foreach ($achats as $key => $value) {
                            if ($value["total"] > 0) {
                        ?>
                        <tr>
                            <td><?=$value["nom"]. " ".$value["prenom"] ?></td>
                            <td><?=number_format($value["total"],2,".","")?></td>
                            <td><?=date("d/m/Y H:i:s", strtotime($value["date_achat"]))?></td>
                            <td>
                                <a href="recuAchat.php?id=<?= $value["id"]?>" title="Afficher le Reçu" style="color: blue !important;"><i class='bx bx-receipt'></i></a>
                                <a href="achat.php?id_achat=<?=$value['id']?>" title="Modifier" style="color: blue !important;"><i class='bx bx-edit'></i></a>
                                <a onclick="annuleAchat(<?= $value['id']?>)" title="Annuler" style="color: red; cursor: pointer;"><i class='bx bx-x-circle'></i></a>
                            </td>

                        </tr>    
                    <?php
                            }
                        }
                    }
                    ?>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</section>

<?php 
    include("pied.php");
?>

<script>
    function createNewAchat() {
        document.querySelector(".valider").style.display = "none";
        window.location.href = "../model/ajoutAchat.php";
    }

    function annuleAchat(idAchat, idArticle, quantite) {
        if (confirm("Voulez-vous vraiment annuler cette achat?")) {
            window.location.href = "../model/annuleAchat.php?idAchat="+idAchat
        }
    }


    function setPrix() {
        var quantite = document.querySelector("#quantite");
        var prix_u = document.querySelector("#prix_u");
        var prix = document.querySelector("#prix");

        prix.value = Number(quantite.value) * Number(prix_u.value);
    }

    function supprimerLigne(idLigne, idAchat) {
        if (confirm("Voulez-vous vraiment supprimer cette ligne?")) {
            window.location.href = "../model/supprimerLigneAchat.php?idLigne=" + idLigne + "&idAchat=" + idAchat;
        }
    }
</script>