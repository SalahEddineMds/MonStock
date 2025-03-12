<?php 
    include("entete.php");

    if (!empty($_GET["id"])) {
        $article = getVente($_GET["id"]);
    }
?>

<div class="home-content">
    <div class="overview-boxes">
        <div class="box">
            <form action="<?= !empty($_GET["id"]) ? "../model/modifVente.php" : "../model/ajoutVente.php" ?>" method="post" id="vente-form">
                <label for="id_client">Client</label>
                <select name="id_client" id="id_client">
                    <?php 
                        $clients = getClient();
                        if (!empty($clients) && is_array($clients)) {
                            foreach ($clients as $key => $value) {
                                ?>
                                <option value="<?= $value["id"] ?>"><?= $value["nom"]. " ". $value["prenom"]?></option>
                                <?php
                            }
                        }
                    ?>  
                </select>
                <button class="valider" type="submit">Valider Vente</button>
            </form>
            <div class="box">
                <form action="../model/ajoutArticleVente.php" method="post" id="article-form">
                    <input type="hidden" name="id_vente" value="<?= isset($_GET['id_vente']) ? $_GET['id_vente'] : '' ?>">
                    <label for="id_article">Article</label>
                    <select onchange="setPrix()" name="id_article" id="id_article">
                        <?php 
                            $articles = getArticle();
                            if (!empty($articles) && is_array($articles)) {
                                foreach ($articles as $key => $value) {
                                    ?>
                                    <option data-prix="<?= $value["prix_unitaire"] ?>" value="<?= $value["id"] ?>"><?= $value["nom_article"]. " - ". $value["quantite"]. " disponible" ?></option>
                                    <?php
                                }
                            }
                        ?>  
                    </select>

                    <label for="quantite">Quantité</label>
                    <input onkeyup="setPrix()" type="number" name="quantite" id="quantite" placeholder="Veuillez saisir la quantité" min="1">

                    <label for="prix">Prix</label>
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
        <div class="box">
            <table class="mtable">
                <tr>
                    <th>Client</th>
                    <th>Montant</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                <?php
                $ventes = getVente();
                if (!empty($ventes) && is_array( $ventes )) {
                    foreach ($ventes as $key => $value) {
                    ?>
                    <tr>                      
                        <td><?=$value["nom"]. " ".$value["prenom"] ?></td>
                        <td><?= number_format($value["prix"])?></td>
                        <td><?=date("d/m/Y H:i:s", strtotime($value["date_vente"]))?></td>
                        <td>
                            <a href="recuVente.php?id=<?= $value["id"]?>" style="color: blue !important;"><i class='bx bx-receipt'></i></a>
                            <a onclick="annuleVente(<?= $value['id']?>,<?= $value['idArticle']?>,<?= $value['quantite']?>)" style="color: red; cursor: pointer;"><i class='bx bx-x-circle'></i></a>
                        </td>

                    </tr>    
                <?php
                    }
                }
                ?>
            </table>
        </div>
    </div>
</div>

</section>

<?php 
    include("pied.php");
?>

<script>
    function annuleVente(idVente, idArticle, quantite) {
        if (confirm("Voulez-vous vraiment annuler cette vente?")) {
            window.location.href = "../model/annuleVente.php?idVente="+idVente+"&idArticle="+idArticle+"&quantite="+quantite
        }
    }

    function setPrix() {
        var article = document.querySelector("#id_article");
        var quantite = document.querySelector("#quantite");
        var prix = document.querySelector("#prix");

        var prixUnitaire = article.options[article.selectedIndex].getAttribute("data-prix");

        prix.value = Number(quantite.value) * Number(prixUnitaire);
    }
</script>