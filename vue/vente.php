<?php 
    include("entete.php");

    if (!empty($_GET["id_vente"])) {
        $article = getVente($_GET["id_vente"]);
    }
?>

<div class="home-content">
    <div class="overview-boxes">
        
        <button onclick="createNewVente()" class="valider">Nouveau</button>

        <?php if (!empty($_GET["id_vente"])): ?>
        <div class="box">
            <form action="../model/ajoutClientVente.php" method="post" id="vente-form">
                <input type="hidden" name="id_vente" value="<?= $_GET['id_vente'] ?>">
                <label for="id_client">Client</label>
                <select name="id_client" id="id_client">
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

            <div class="box">
                <form action="../model/ajoutArticleVente.php" method="post" id="article-form">
                    <input type="hidden" name="id_vente" value="<?= $_GET['id_vente'] ?>">
                    <label for="id_article">Article</label>
                    <select onchange="setPrix()" name="id_article" id="id_article">
                        <?php 
                            $articles = getArticle();
                            if (!empty($articles) && is_array($articles)) {
                                foreach ($articles as $key => $value) {
                                    echo "<option data-prix='{$value["prix_unitaire"]}' value='{$value["id"]}'>{$value["nom_article"]} - {$value["quantite"]} disponible</option>";                                   
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
        <?php endif; ?>

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
                        if ($value["total"] > 0) {
                    ?>
                    <tr>                      
                        <td><?=$value["nom"]. " ".$value["prenom"] ?></td>
                        <td><?= number_format($value["total"])?></td>
                        <td><?=date("d/m/Y H:i:s", strtotime($value["date_vente"]))?></td>
                        <td>
                            <a href="recuVente.php?id=<?= $value["id"]?>" style="color: blue !important;"><i class='bx bx-receipt'></i></a>
                            <a onclick="annuleVente(<?= $value['id']?>)" style="color: red; cursor: pointer;"><i class='bx bx-x-circle'></i></a>
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

</section>

<?php 
    include("pied.php");
?>

<script>

    function createNewVente() {
        document.querySelector(".valider").style.display = "none";
        window.location.href = "../model/ajoutVente.php";
    }
        
    function annuleVente(idVente, idArticle, quantite) {
        if (confirm("Voulez-vous vraiment annuler cette vente?")) {
            window.location.href = "../model/annuleVente.php?idVente="+idVente
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