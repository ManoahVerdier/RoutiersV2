<?php
/**
 * Vue
 * Liste des rapports faits ou a faire
 */
//Régalge des paramètres de date : France
setlocale(LC_ALL, 'fr_FR');
?>
<div class='mes_rapports'>
    <h3>Rapports de l'assemblée</h3>

    <?php
    //S'il manque des rapports, message
    if ($relance) {
        ?>
        <div class="alert alert-success" role="alert">Relance envoyée aux participants</div>    

        <?php
    }
    else if ($misses) {//S'il manque des rapports, message
        ?>
        <div class="alert alert-danger" role="alert">Attention, certaines participations n'ont pas encore fait l'objet d'un rapport</div>    

        <?php
    }
    ?>
    <table class='table table-hover table-bordered'>
        <?php
        if(empty($parts) || sizeof($parts)==0):
        ?>
        <p>L'assemblée n'as pas de participations récentes. Lorsque ce sera le cas, cette page te permettra de saisir ou consulter un rapport.</p>
        <?php
        endif;
        /* Parcours des participations */
        foreach ($parts as $part) {
            ?>

            <?php
            /* Si le rapport est manquant */
            if ($part->missing === '1' || $part->missing === 1) {
                ?>
                <tr class="table-danger">
                    <td>Participation du <?= utf8_encode(strftime('%A %e %B %Y', strtotime($part->date))) ?></td>
                    <td>
                        <form action='' method='post'>
                            <input type='hidden' id='idCren' name='idCren' value='<?=$part->idCren?>'/>
                            <input type='submit' id='submit' name='submit' value='Relancer' class='missing btn btn-danger'/>
                        </form>
                    </td>
    <?php } else { ?>
                <tr>
                    <td>Participation du <?= utf8_encode(strftime('%A %e %B %Y', strtotime($part->date))) ?></td>
                    <td>
                        <a class='dispo btn btn-primary' href="<?= site_url('rapport/consulterRP/' . $part->idRap) ?>">Voir le rapport</a>
                    </td>
                    <?php
                }
                ?>
            </tr>
<?php } ?>
    </table>
</div>