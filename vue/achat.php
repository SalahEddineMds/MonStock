<?php 
    include("entete.php");

    if (!empty($_GET["id_achat"])) {
        $article = getAchat($_GET["id_achat"]);
    }
?>

<div class="home-content">
    <div class="overview-boxes">

        <button onclick="createNewAchat()" class="valider">Nouveau</button>

        <?php if (!empty($_GET["id_achat"])): ?>
        <div class="box">
            <form action="../model/ajoutFournisseurAchat.php" method="post" id="achat-form">
                <input type="hidden" name="id_achat" value="<?= $_GET['id_achat'] ?>">
                <label for="id_fournisseur">Fournisseur</label>
                    <select name="id_fournisseur" id="id_fournisseur">
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
            <div class="box">
                <form action="../model/ajoutArticleAchat.php" method="post" id="article-form">
                    <input type="hidden" name="id_achat" value="<?= $_GET['id_achat'] ?>">
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
                    <th>Fournisseur</th>
                    <th>Montant</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                <?php
                $achats = getAchat();
                if (!empty($achats) && is_array( $achats )) {
                    foreach ($achats as $key => $value) {
                        if ($value["total"] > 0) {
                    ?>
                    <tr>
                        <td><?=$value["nom"]. " ".$value["prenom"] ?></td>
                        <td><?=number_format($value["total"])?></td>
                        <td><?=date("d/m/Y H:i:s", strtotime($value["date_achat"]))?></td>
                        <td>
                            <a href="recuAchat.php?id=<?= $value["id"]?>" style="color: blue !important;"><i class='bx bx-receipt'></i></a>
                            <a onclick="annuleAchat(<?= $value['id']?>) "style="color: red; cursor: pointer;"><i class='bx bx-x-circle'></i></a>
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
        var article = document.querySelector("#id_article");
        var quantite = document.querySelector("#quantite");
        var prix = document.querySelector("#prix");

        var prixUnitaire = article.options[article.selectedIndex].getAttribute("data-prix");

        prix.value = Number(quantite.value) * Number(prixUnitaire);
    }
</script>