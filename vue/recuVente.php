<?php 
    include("entete.php");

    if (!empty($_GET["id"])) {
        $ventes = getVente($_GET["id"]);
        $vente_lignes = getVenteLignes($_GET["id"]);
    }
?>

<div class="home-content">

        <button class="hidden-print" id="btnPrint" style="position:relative; left:42%;"><i class='bx bx-printer' ></i> Imprimer</button>

    <div class="page">
        <div class="cote-a-cote">
            <h2>MonStock stock</h2>
            <div>
                <p>Reçu N° #: <?= $ventes["id"]?> </p>
                <p>Date: <?= date("d/m/Y H:i:s", strtotime($ventes["date_vente"]))?> </p>
            </div>
        </div>
        <div class="cote-a-cote" style="width: 50%;">
            <p>Nom: </p>
            <p><?=$ventes["nom"]. " ".$ventes["prenom"] ?></p>
        </div>
        <div class="cote-a-cote" style="width: 50%;">
            <p>Téléphone: </p>
            <p><?=$ventes["telephone"]?></p>
        </div>
        <div class="cote-a-cote" style="width: 50%;">
            <p>Adresse: </p>
            <p><?=$ventes["adresse"]?></p>
        </div>

        <br>

        <table class="mtable">
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Montant</th>
                </tr>
                <?php foreach ($vente_lignes as $ligne) : ?>
                <tr>
                    <td><?= $ligne["nom_article"] ?></td>
                    <td><?= $ligne["quantite"] ?></td>
                    <td>
                        <?php 
                            if ($ligne["quantite"] != 0) {
                                $prix_unitaire = $ligne["prix"] / $ligne["quantite"];
                            } else {
                                $prix_unitaire = 0;
                            }
                        ?>
                        <?= $prix_unitaire ?>
                    </td>
                    <td><?= $ligne["prix"] ?></td>
                </tr>
                <?php endforeach; ?>    
                <tr>
                    <td colspan="3" style="text-align:right; font-weight:bold;">Total:</td>
                    <td><strong><?=$ventes["total"]?> DZD</strong></td>
                </tr>
        </table>
    </div>
    
</div>

</section>

<?php 
    include("pied.php");
?>

<script>
    var btnPrint =document.querySelector("#btnPrint");
    btnPrint.addEventListener("click", () => {
       window.print(); 
    });

    
</script>