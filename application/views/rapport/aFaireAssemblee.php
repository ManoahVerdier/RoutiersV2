<?php
/**
 * Vue
 * Liste des rapports faits ou a faire
 */
//Régalge des paramètres de date : France
setlocale(LC_ALL, 'fr_FR');
$fmt = datefmt_create(
    'fr_FR',
    IntlDateFormatter::FULL,
    IntlDateFormatter::FULL
);
$fmt->setPattern('eeee d MMMM yyyy');
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
                <tr class="<?= strtotime($part->date)> strtotime("-90 days")?"table-danger":""?>">
                    <td>Participation du <?= datefmt_format($fmt,  strtotime($part->date)) ?></td>
                    <td>
                        
                        <?php if(strtotime($part->date)> strtotime("-90 days")):?>
                            <form action='' method='post' class="d-inline-block">
                                <input type='hidden' id='idCren' name='idCren' value='<?=$part->idCren?>'/>
                                <input type='hidden' id='type' name='type' value='relancer'/>
                                <input type='submit' id='submit' name='submit' value='Relancer' class='missing btn btn-danger'/>
                            </form>
                            <form action='' method='post'  class="d-inline-block">
                                <input type='hidden' id='idCren' name='idCren' value='<?=$part->idCren?>'/>
                                <input type='hidden' id='type' name='type' value='annuler'/>
                                <input type='submit' id='submit' name='submit' value="N'a pas eu lieu" class='missing btn btn-danger'/>
                            </form>
                        <?php else: ?>
                            <input type='submit' id='submit' name='submit' value='Pas de rapport' class='missing btn btn-danger disabled' disabled/>
                        <?php endif; ?>
                    </td>
    <?php } else { ?>
                <tr>
                    <td>Participation du <?= datefmt_format($fmt,  strtotime($part->date)) ?></td>
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