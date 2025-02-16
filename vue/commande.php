<?php 
    include("entete.php");

    if (!empty($_GET["id"])) {
        $article = getCommande($_GET["id"]);
    }
?>

<div class="home-content">
    <div class="overview-boxes">
        <div class="box">
            <form action=" <?= !empty($_GET["id"]) ? "../model/modifCommande.php" : "../model/ajoutCommande.php" ?>" method="post">
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
        <div class="box">
            <table class="mtable">
                <tr>
                    <th>Article</th>
                    <th>Fournisseur</th>
                    <th>Quantité</th>
                    <th>Prix</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                <?php
                $commandes = getCommande();
                if (!empty($commandes) && is_array( $commandes )) {
                    foreach ($commandes as $key => $value) {
                    ?>
                    <tr>
                        <td><?=$value["nom_article"]?></td>
                        <td><?=$value["nom"]. " ".$value["prenom"] ?></td>
                        <td><?=$value["quantite"]?></td>
                        <td><?=$value["prix"]?></td>
                        <td><?=date("d/m/Y H:i:s", strtotime($value["date_commande"]))?></td>
                        <td>
                            <a href="recuCommande.php?id=<?= $value["id"]?>"><i class='bx bx-receipt'></i></a>
                            <a onclick="annuleCommande(<?= $value["id"]?>,<?= $value["idArticle"]?>,<?= $value["quantite"]?>)" style="color: red"><i class='bx bx-x-circle'></i></a>
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
    function annuleCommande(idCommande, idArticle, quantite) {
    // Get quantité en stock
    var articleElement = document.querySelector("#id_article option[value='" + idArticle + "']");
    if (!articleElement) {
        alert("Erreur: Article introuvable.");
        return;
    }
    
    var stockDisponible = parseInt(articleElement.textContent.match(/- (\d+) disponible/)[1], 10);

    // Check si quantité de la commade superieur au quantité en stock
    if (quantite > stockDisponible) {
        alert("Erreur: La quantité disponible est insuffisante pour annuler cette commande.");
        return;
    }

    if (confirm("Voulez-vous vraiment annuler cette commande?")) {
        window.location.href = `../model/annuleCommande.php?idCommande=${idCommande}&idArticle=${idArticle}&quantite=${quantite}`;
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