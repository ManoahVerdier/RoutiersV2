<?php
/* Vue
 * Gestion des indisponibilités
 */

/* Réglage du temps sur la France */
setlocale(LC_ALL, 'fr_FR');
?> 

<div id="wrapIndispos">
    <h3>Mes disponibilités</h3>

    <h5>Prochains créneaux</h5>



    <?php
    /* Parcours des créneaux */
    foreach ($creneaux as $creneau) {
        /* Par défaut, disponible, donc classe de bouton vert, pas de préfixe et state à 1 */
        $class = "success";
        $pre = "";
        $state = 1;
        /* Parcours des indispos */
        foreach ($indispos as $indispo) {
            /* S'il y a une indispo pour le créneau en cours */
            if ($indispo->idCren == $creneau->id) {
                /* Classe bouton rouge */
                $class = "danger";
                /* Préfixe */
                $pre = 'in';
                /* Etat a 0 (pour le formulaire */
                $state = 0;
            }
        }
        ?>
        <div class="row">
            <div class="col-sm-8 col-xs-3  col-md-6 col-lg-4 my-auto">
                Le <?= utf8_encode(strftime('%A %e %B %Y', strtotime($creneau->date))) ?> à <?= $creneau->heure ?> heures
            </div>
            <div class="col-sm-2 col-xs-3">
                <form method='post' class='text-center'>
                    <!--ID du créneau-->
                    <input type="hidden" name="id" value="<?= $creneau->id ?>"/>
                    <!--Dispo ou indispo-->
                    <input type="hidden" name="state" value="<?= $state ?>"/>
                    <!--Bouton submit visible-->
                    <input type="submit" value="<?= $pre ?>disponible" class="btn btn-<?= $class ?>">
                </form>
            </div>

        </div>
    <?php } ?>
</div>
