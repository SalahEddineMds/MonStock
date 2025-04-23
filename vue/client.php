<?php 
    include("entete.php");

    if (!empty($_GET["id"])) {
        $client = getClient($_GET["id"]);
    }
?>

<div class="home-content">
    <div class="overview-boxes">
        <div class="vabox">
            <form action=" <?= !empty($_GET["id"]) ? "../model/modifClient.php" : "../model/ajoutClient.php" ?>" method="post">
                <h3><?= !empty($_GET["id"]) ? "Modifier Client" : "Ajouter un Client" ?></h3>
                <label for="nom">Nom</label>
                <input value="<?= !empty($_GET["id"]) ? $client["nom"] : "" ?>" type="text" name="nom" id="nom" placeholder="Veuillez saisir le nom">
                <input value="<?= !empty($_GET["id"]) ? $client["id"] : "" ?>" type="hidden" name="id" id="id">

                <label for="prenom">Prénom</label>
                <input value="<?= !empty($_GET["id"]) ? $client["prenom"] : "" ?>" type="text" name="prenom" id="prenom" placeholder="Veuillez saisir le prénom">

                <label for="telephone">N° de Téléphone</label>
                <input value="<?= !empty($_GET["id"]) ? $client["telephone"] : "" ?>" type="text" name="telephone" id="telephone" placeholder="Veuillez saisir le N° de téléphone">

                <label for="adresse">Adresse</label>
                <input value="<?= !empty($_GET["id"]) ? $client["adresse"] : "" ?>" type="text" name="adresse" id="adresse" placeholder="Veuillez saisir l'adresse">

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
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>N° Téléphone</th>
                        <th>Adresse</th>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="nom" id="nom" placeholder="Veuillez saisir le nom">
                        </td>
                        <td>
                            <input type="text" name="prenom" id="prenom" placeholder="Veuillez saisir le prénom">
                        </td>
                        <td>
                            <input type="text" name="telephone" id="telephone" placeholder="Veuillez saisir le N° Téléphone">
                        </td>
                        <td>
                            <input type="text" name="adresse" id="adresse" placeholder="Veuillez saisir l'Adresse">
                        </td>
                    </tr>
                </table>
                <br>
                <button type="submit">Recherche</button>
            </form>
            <br>
            <table class="mtable">
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>N° de Téléphone</th>
                    <th>Adresse</th>
                    <th>Action</th>
                </tr>
                <?php
                    if (!empty($_GET)) {
                        $clients = getClient(null, $_GET);
                    } else {
                        $clients = getClient();
                    }

                    if (!empty($clients) && is_array( $clients )) {
                        foreach ($clients as $key => $value) {
                    ?>
                    <tr>
                        <td><?=$value["nom"]?></td>
                        <td><?=$value["prenom"]?></td>
                        <td><?=$value["telephone"]?></td>
                        <td><?=$value["adresse"]?></td>
                        <td>
                            <a href="?id=<?=$value['id']?>" title="Modifier" style="color: blue !important;"><i class='bx bx-edit-alt'></i></a>
                            <a onclick="supprimeClient(<?= $value['id']?>)" title="Supprimer" style="color: red; cursor: pointer;"><i class='bx bx-x-circle'></i></a>
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
    function supprimeClient(idClient) {

    if (confirm("Voulez-vous vraiment supprimer ce client?")) {
        window.location.href = `../model/supprimeClient.php?idClient=${idClient}`;
    }
}
</script>