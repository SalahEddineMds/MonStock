<?php 
    include("entete.php");

    if (!empty($_GET["id_vente"])) {
        $article = getVente($_GET["id_vente"]);
    }
?>

<div class="home-content">
    <?php if (empty($_GET["id_vente"])): ?>
        <button onclick="createNewVente()" class="valider" style="margin-left: 25px; margin-bottom: 5px;">Nouveau Vente</button>
    <?php endif; ?>    <div class="overview-boxes">
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
            <?php endif; ?>


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
                                <a href="vente.php?id_vente=<?=$value['id']?>" title="Ajouter à Vente" style="color: blue !important;"><i class='bx bx-plus'></i></a>
                                <a onclick="annuleVente(<?= $value['id']?>)" title="Supprimer cette Vente" style="color: red; cursor: pointer;"><i class='bx bx-x-circle'></i></a>
                            </td>

                        </tr>    
                    <?php
                            }
                        }
                    }
                    ?>
                </table>
            </div>
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
</script>