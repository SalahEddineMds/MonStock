<?php 
    include("entete.php");

    if (!empty($_GET["id_vente"])) {
        $article = getVente($_GET["id_vente"]);
    }
?>

<div class="home-content">
    <?php if (empty($_GET["id_vente"])): ?>
        <button onclick="createNewVente()" class="valider" style="margin-left: 25px; margin-bottom: 5px;">Nouveau Vente</button>
    <?php endif; ?>    
    <div class="overview-boxes">
        <div class="va-container">

            <?php if (!empty($_GET["id_vente"])): ?>
            <div class="vabox">
                <form action="../model/ajoutClientVente.php" method="post" id="vente-form">
                    <input type="hidden" name="id_vente" value="<?= $_GET['id_vente'] ?>">
                    <label for="id_client">Client</label>
                    <select name="id_client" id="id_client" class="tom-select">
                        <?php 
                            $clients = getClient();
                            if (!empty($clients) && is_array($clients)) {
                                foreach ($clients as $key => $value) {
                                    $selected = (!empty($vente) && $vente["id_client"] == $value["id"]) ? "selected" : "";
                                    echo "<option value='{$value["id"]}' {$selected}>{$value["nom"]} {$value["prenom"]}</option>";
                                }
                            }
                        ?>  
                    </select>
                    <button class="valider" type="submit">Valider Vente</button>
                </form>

                <div class="vabox">
                    <form action="../model/ajoutArticleVente.php" method="post" id="article-form">
                        <input type="hidden" name="id_vente" value="<?= $_GET['id_vente'] ?>">
                        <label for="id_article">Article</label>
                        <select onchange="remplirPrix()" name="id_article" id="id_article" class="tom-select">
                            <option value="" disabled selected>Choisir un article</option>
                            <?php 
                                $articles = getArticle();
                                if (!empty($articles) && is_array($articles)) {
                                    foreach ($articles as $key => $value) {
                                        echo "<option data-prix='{$value["prix_vente_unitaire"]}' value='{$value["id"]}'>{$value["nom_article"]} - {$value["quantite"]} disponible</option>";                                   
                                    }
                                }
                            ?>  
                        </select>

                        <label for="quantite">Quantité</label>
                        <input onkeyup="setPrix()" type="number" name="quantite" id="quantite" placeholder="Veuillez saisir la quantité" min="1">

                        <label for="prix_u">Prix unitaire</label>
                        <input onkeyup="setPrix()" type="number" name="prix_u" id="prix_u" placeholder="Prix unitaire" min="0" step="any">
                        
                        <label for="prix">Prix total</label>
                        <input type="number" name="prix" id="prix" placeholder="Veuillez saisir le prix" min="0" step="any">
                        

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
                    <h3>Détails de la vente</h3>
                    <table class="mtable">
                        <tr>
                            <th>Article</th>
                            <th>Quantité</th>
                            <th>Prix unitaire</th>
                            <th>Prix total</th>
                            <th>Action</th>
                        </tr>
                        <?php
                        // Récupérer les lignes de la vente actuelle
                        $lignes_vente = getVenteLignes($_GET["id_vente"]);
                        $total_vente = 0;
                        
                        if (!empty($lignes_vente) && is_array($lignes_vente)) {
                            foreach ($lignes_vente as $ligne) {
                                $total_vente += $ligne["prix"];
                                $prix_uni = $ligne["prix"] / $ligne["quantite"]
                        ?>
                        <tr>
                            <td><?= $ligne["nom_article"] ?></td>
                            <td><?= $ligne["quantite"] ?></td>
                            <td><?= $prix_uni ?></td>
                            <td><?= $ligne["prix"] ?></td>
                            <td>
                                <a onclick="modifierLigne(<?= $ligne['id'] ?>)" title="Modifier" style="color: blue !important; cursor: pointer;"><i class='bx bx-edit-alt'></i></a>
                                <a onclick="supprimerLigne(<?= $ligne['id'] ?>, <?= $_GET['id_vente'] ?>)" title="Supprimer" style="color: red !important; cursor: pointer;"><i class='bx bx-x-circle'></i></a>
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
                        <strong>Total de la vente: <?= number_format($total_vente, 2) ?></strong>
                    </div>
                </div>
            </div>
            <?php else: ?>


            <div style="display: block;" class="box">
                <form action="" method="get">
                    <table class="mtable">
                        <tr>
                            <th>Client</th>
                            <th>Montant</th>
                            <th>Date</th>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="nom_p_client" id="nom_p_client" placeholder="Veuillez saisir le nom ou prenom">
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
                        <th>Client</th>
                        <th>Montant</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    if (!empty($_GET)) {
                        $ventes = getVente(null, $_GET);
                    } else {
                        $ventes = getVente();
                    }
                    if (!empty($ventes) && is_array( $ventes )) {
                        foreach ($ventes as $key => $value) {
                            if ($value["total"] > 0) {
                        ?>
                        <tr>                      
                            <td><?=$value["nom"]. " ".$value["prenom"] ?></td>
                            <td><?= $value["total"] ?></td>
                            <td><?=date("d/m/Y H:i:s", strtotime($value["date_vente"]))?></td>
                            <td>
                                <a href="recuVente.php?id=<?= $value["id"]?>" title="Afficher le Reçu" style="color: blue !important;"><i class='bx bx-receipt'></i></a>
                                <a href="vente.php?id_vente=<?=$value['id']?>" title="Modifier" style="color: blue !important;"><i class='bx bx-edit'></i></a>
                                <a onclick="annuleVente(<?= $value['id']?>)" title="Annuler" style="color: red; cursor: pointer;"><i class='bx bx-x-circle'></i></a>
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

    function createNewVente() {
        document.querySelector(".valider").disabled = true;
        window.location.href = "../model/ajoutVente.php";
    }
        
    function annuleVente(idVente, idArticle, quantite) {
        if (confirm("Voulez-vous vraiment annuler cette vente?")) {
            window.location.href = "../model/annuleVente.php?idVente="+idVente
        }
    }

    function setPrix() {
        var quantite = document.querySelector("#quantite");
        var prix_u = document.querySelector("#prix_u");
        var prix = document.querySelector("#prix");

        
        prix.value = Number(quantite.value) * Number(prix_u.value);
    }

    function remplirPrix(){
        var article = document.querySelector("#id_article")
        var prix_u = article.options[article.selectedIndex].getAttribute("data-prix");
        document.querySelector("#prix_u").value = prix_u;
        setPrix();
    }

    function supprimerLigne(idLigne, idVente) {
        if (confirm("Voulez-vous vraiment supprimer cette ligne?")) {
            window.location.href = "../model/supprimerLigneVente.php?idLigne=" + idLigne + "&idVente=" + idVente;
        }
    }
</script>