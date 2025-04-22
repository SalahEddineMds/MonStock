<?php 
    include("entete.php");

    if (!empty($_GET["id"])) {
        $article = getArticle($_GET["id"]);
    }
?>

<div class="home-content">
    <div class="overview-boxes">
        <div class="vabox">
            <form action=" <?= !empty($_GET["id"]) ? "../model/modifArticle.php" : "../model/ajoutArticle.php" ?>" method="post">
                <h3><?= !empty($_GET["id"]) ? "Modifier Article" : "Ajouter un Article" ?></h3>
                <label for="nom_article">Nom de l'article</label>
                <input value="<?= !empty($_GET["id"]) ? $article["nom_article"] : "" ?>" type="text" name="nom_article" id="nom_article" placeholder="Veuillez saisir le nom">
                <input value="<?= !empty($_GET["id"]) ? $article["id"] : "" ?>" type="hidden" name="id" id="id">

                <label for="id_categorie">Catégorie</label>
                <select name="id_categorie" id="id_categorie" class="tom-select">
                    <?php 
                    $categories = getCategorie();
                    if (!empty($categories) && is_array($categories)) {
                        foreach($categories as $key => $value){

                    ?>
                        <option <?= !empty($_GET["id"]) && $article["id_categorie"]== $value['id'] ? "selected" : "" ?> value="<?= $value["id"] ?>"><?= $value['libelle_categorie'] ?></option>                
                    
                    <?php 

                        }
                    }
                    ?>
                </select>

                <label for="quantite">Quantité</label>
                <input value="<?= !empty($_GET["id"]) ? $article["quantite"] : "" ?>" type="number" name="quantite" id="quantite" placeholder="Veuillez saisir la quantité" min="0">

                <label for="prix_vente_unitaire">Prix de vente unitaire</label>
                <input value="<?= !empty($_GET["id"]) ? $article["prix_vente_unitaire"] : "" ?>" type="number" name="prix_vente_unitaire" id="prix_vente_unitaire" placeholder="Veuillez saisir le prix" min="0" step="any">

                <label for="prix_achat_unitaire">Prix d'achat unitaire</label>
                <input value="<?= !empty($_GET["id"]) ? $article["prix_achat_unitaire"] : "" ?>" type="number" name="prix_achat_unitaire" id="prix_achat_unitaire" placeholder="Veuillez saisir le prix" min="0" step="any">

                <label for="date_fabrication">Date de fabrication</label>
                <input value="<?= !empty($_GET["id"]) ? $article["date_fabrication"] : "" ?>" type="datetime-local" name="date_fabrication" id="date_fabrication">

                <label for="date_expiration">Date d'expiration</label>
                <input value="<?= !empty($_GET["id"]) ? $article["date_expiration"] : "" ?>" type="datetime-local" name="date_expiration" id="date_expiration">

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
        <div style="display: block;" class="box">
            <form action="" method="get">
                <table class="mtable">
                    <tr>
                        <th>Nom article</th>
                        <th>Catégorie</th>
                        <th>Quantité</th>
                        <th>Prix de vente unitaire</th>
                        <th>Prix d'achat unitaire</th>
                        <th>Date fabrication</th>
                        <th>Date expiration</th>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="nom_article" id="nom_article" placeholder="Veuillez saisir le nom">
                        </td>
                        <td>
                            <select name="id_categorie" id="id_categorie" class="tom-select">
                                <option value="">---Choisir---</option>
                                <?php 
                                $categories = getCategorie();
                                if (!empty($categories) && is_array($categories)) {
                                    foreach($categories as $key => $value){

                                ?>
                                    <option <?= !empty($_GET["id"]) && $article["id_categorie"]== $value['id'] ? "selected" : "" ?> value="<?= $value["id"] ?>"><?= $value['libelle_categorie'] ?></option>                
                                
                                <?php 

                                    }
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <input type="number" name="quantite" id="quantite" placeholder="Veuillez saisir la quantité" min="0">
                        </td>
                        <td>
                            <input type="number" name="prix_vente_unitaire" id="prix_vente_unitaire" placeholder="Veuillez saisir le prix" min="0" step="any">
                        </td>
                        <td>
                            <input type="number" name="prix_achat_unitaire" id="prix_achat_unitaire" placeholder="Veuillez saisir le prix" min="0" step="any">
                        </td>
                        <td>
                            <input type="date" name="date_fabrication" id="date_fabrication">
                        </td>
                        <td>
                            <input type="date" name="date_expiration" id="date_expiration">
                        </td>
                    </tr>
                </table>
                <br>
                <button type="submit">Recherche</button>
            </form>
            <br>
            <table class="mtable">
                <tr>
                    <th>Nom article</th>
                    <th>Catégorie</th>
                    <th>Quantité</th>
                    <th>Prix de vente unitaire</th>
                    <th>Prix d'achat unitaire</th>
                    <th>Date fabrication</th>
                    <th>Date expiration</th>
                    <th>Action</th>
                </tr>
                <?php
                if (!empty($_GET)) {
                    $articles = getArticle(null, $_GET);
                } else {
                    $articles = getArticle();
                }
                

                
                if (!empty($articles) && is_array( $articles )) {
                    foreach ($articles as $key => $value) {    
                        // Check si quantite < 5
                        $lowStockClass = ($value["quantite"] < 5) ? 'low-stock-row' : '';
                ?>
                    <tr class="<?= $lowStockClass ?>">
                        <td><?=$value["nom_article"]?></td>
                        <td><?=$value["libelle_categorie"]?></td>
                        <td><?=$value["quantite"]?></td>
                        <td><?=number_format($value["prix_vente_unitaire"],2,".","")?></td>
                        <td><?=number_format($value["prix_achat_unitaire"],2,".","")?></td>
                        <td><?=date("d/m/Y H:i:s",strtotime($value["date_fabrication"]))?></td>
                        <td><?=date("d/m/Y H:i:s",strtotime($value["date_expiration"]))?></td>
                        <td><a href="?id=<?=$value["id"]?>" style="color: blue !important;"><i class='bx bx-edit-alt'></i></a></td>
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