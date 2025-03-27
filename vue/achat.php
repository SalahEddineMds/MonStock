<?php 
    include("entete.php");

    if (!empty($_GET["id"])) {
        $article = getAchat($_GET["id"]);
    }
?>

<div class="home-content">
    <div class="overview-boxes">
        <div class="box">
            <form action=" <?= !empty($_GET["id"]) ? "../model/modifAchat.php" : "../model/ajoutAchat.php" ?>" method="post">
                <label for="id_fournisseur">Fournisseur</label>
                    <select name="id_fournisseur" id="id_fournisseur">
                        <?php 
                             $fournisseurs = getFournisseur();
                            if (!empty($fournisseurs) && is_array($fournisseurs)) {
                                foreach ($fournisseurs as $key => $value) {
                                    ?>
                                    <option value="<?= $value["id"] ?>"><?= $value["nom"]. " ". $value["prenom"]?></option>
                                    <?php

                                }
                            }
                        ?>  
                    </select>
                    <button class="valider" type="submit">Valider Achat</button>
            </form>
        <div class="box">
            <form action="../model/ajoutArticleAchat.php" method="post" id="article-form">
                <input value="<?= !empty($_GET["id"]) ? $article["id"] : "" ?>" type="hidden" name="id" id="id">
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
                <input onkeyup="setPrix()" value="<?= !empty($_GET["id"]) ? $article["quantite"] : "" ?>" type="number" name="quantite" id="quantite" placeholder="Veuillez saisir la quantité" min="0">

                <label for="prix">Prix</label>
                <input value="<?= !empty($_GET["id"]) ? $article["prix"] : "" ?>" type="number" name="prix" id="prix" placeholder="Veuillez saisir le prix" min="0" step="any">

                <button type="submit">Valider</button>

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
                    <th>Fournisseur</th>
                    <th>Montant</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                <?php
                $achats = getAchat();
                if (!empty($achats) && is_array( $achats )) {
                    foreach ($achats as $key => $value) {
                    ?>
                    <tr>
                        <td><?=$value["nom"]. " ".$value["prenom"] ?></td>
                        <td><?=number_format($value["prix"])?></td>
                        <td><?=date("d/m/Y H:i:s", strtotime($value["date_achat"]))?></td>
                        <td>
                            <a href="recuAchat.php?id=<?= $value["id"]?>" style="color: blue !important;"><i class='bx bx-receipt'></i></a>
                            <a onclick="annuleAchat(<?= $value['id']?>,<?= $value['idArticle']?>,<?= $value['quantite']?>) "style="color: red; cursor: pointer;"><i class='bx bx-x-circle'></i></a>
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
    function annuleAchat(idAchat, idArticle, quantite) {
    // Get quantité en stock
    var articleElement = document.querySelector("#id_article option[value='" + idArticle + "']");
    if (!articleElement) {
        alert("Erreur: Article introuvable.");
        return;
    }
    
    var stockDisponible = parseInt(articleElement.textContent.match(/- (\d+) disponible/)[1], 10);

    // Check si quantité de la commade superieur au quantité en stock
    if (quantite > stockDisponible) {
        alert("Erreur: La quantité disponible est insuffisante pour annuler cette achat.");
        return;
    }

    if (confirm("Voulez-vous vraiment annuler cette achat?")) {
        window.location.href = `../model/annuleAchat.php?idAchat=${idAchat}&idArticle=${idArticle}&quantite=${quantite}`;
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