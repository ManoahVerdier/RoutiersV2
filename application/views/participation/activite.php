<?php /**
 * Vue
 * Tableaux de résultats et statistiques
 */ ?>

<div class='activite'>
    <h2>Mon activité</h2>
    <div class='row'>
        <div class='col-md-4 col-sm-12 text-center'>
            <h5>Mes sorties</h5>
            <table class='table table-hover table-bordered'>
                <?php
                foreach ($dates AS $date) {
                    ?>
                    <tr><td><?= $date ?></td></tr>
                <?php } ?>
                <tr><td class="table-dark">Total : <?= $totDates->Nb ?></td></tr>
            </table>
        </div>
        <div class='col-md-4 col-sm-12 text-center'>
            <h5>Langues rencontrées</h5>
            <table class='table table-hover table-bordered'>
                <?php
                foreach ($langues AS $langue) {
                    ?>
                    <tr><td><?= $langue->intitule ?></td><td><?= $langue->Nb ?></td></tr>
                <?php } ?>
                <tr><td class="table-dark">Total</td><td class="table-dark"><?= $totLangs->Nb ?></td></tr>
            </table>
        </div>
        <div class='col-md-4 col-sm-12 text-center'>
            <h5>Publications</h5>
            <table class='table table-hover table-bordered'>
                <?php
                foreach ($pubs AS $pub) {
                    ?>
                    <tr><td><?= $pub->titre ?></td><td><?= $pub->Nb ?></td></tr>
                <?php } ?>
                <tr><td class="table-dark"  >Total</td><td class="table-dark"><?= $totPubs->Nb ?></td></tr>
            </table>
        </div>
    </div>
</div>
