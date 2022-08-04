<?php
setlocale(LC_ALL, 'fr_FR');
$nbPart = sizeof($parts);
$cpt = 1;
?>
<div id='wrapCren'>
    <?php if($creneauDate->annule){?>
    <div class='alert alert-danger'>Ce créneau a été <b>Annulé</b></div>
    <?php }?>
    <h3 class="mb-5 mt-5">Créneau du <?= utf8_encode(strftime('%A %e %B %Y', strtotime($creneauDate->date))) ?></h3>
    <div class="row">
        <div class="col-sm-4">
            <div class='row px-4 full-height rowCol'>
                <div class='col-sm-12 wrapCol border rounded shadow mob-mb-10 mob-pb-10'>
                    <div class="row">
                        <div class="col-sm-12"><h5 class="text-center  pt-4 pb-4">Détails du créneau</h5></div>
                        <div class="col-sm-12 text-center"><b>Horaire </b></div>
                         <div class="col-sm-12 text-center"><?= $creneau->heure ?>h</div>
                         <div class="col-sm-12 text-center"><b>Lieu </b></div>
                        <div class="col-sm-12 text-center"><?= $lieu ?></div>
                        <div class="col-sm-12 text-center"><b>Adresse </b></div>
                        <div class="col-sm-12 text-center"><?= $lieu_det->adresse ?></div>
                        <div class="col-sm-12 text-center"><b class='alert-danger'><?= $creneauDate->annule?'Créneau annulé':'' ?></b></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4 participants">
            <div class='row px-4 full-height rowCol'>
                <div class='col-sm-12 wrapCol border rounded shadow mob-mb-10 mob-mt-10'>
                    <h5 class="text-center pt-4 pb-4">Participants</h5>
                    <?php foreach ($parts as $part) { ?>
                        <div class="row participant pb-3 pt-3 border-top <?= $cpt == $nbPart ? 'border-bottom' : '' ?>">
                            <div class="col-sm-12 text-center"><h6>Participant n°<?= $cpt ?></h6></div>
                            <div class="col-sm-12 text-center"><?= $part->prenom ?> <?= $part->nom ?></div>
                            <div class="col-sm-12 text-center"><?= $part->telephone ?></div>
                        </div>
                        <?php
                        $cpt++;
                    }
                    while ($cpt <= 2) {
                        ?>
                        <div class="row participant pb-3 pt-3 border-top <?= $cpt == $nbPart ? 'border-bottom' : '' ?>">
                            <div class="col-sm-12 text-center"><h6>Participant n°<?= $cpt ?></h6></div>
                            <div class="col-sm-12 text-center">Indéfini</div>
                            <div class="col-sm-12 text-center">&nbsp;</div>
                        </div>
                        <?php
                        $cpt++;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class='row mt-3'>
        <div class='col-sm-4 mob-mb-10 mob-mt-10'>
            <div class='row px-4 full-height rowCol'>
                <div class='col-sm-12 wrapCol px-0'>
                    <a href='<?= site_url('planning') ?>' class='btn btn-secondary btn-block'>
                        Retour à la liste
                    </a>
                </div>
            </div>
        </div>
        <div class='col-sm-4 mob-mb-10'>
            <div class='row px-4 full-height rowCol'>
                <div class='col-sm-12 wrapCol px-0'>
                    <a href='<?= site_url('annulerProcl') . '/' . $idCren . '/' . $auth_user_id ?>' class='btn btn<?= $creneauDate->annule?'-outline':''?>-danger btn-block <?= $creneauDate->annule || strtotime($creneauDate->date)<strtotime(date('Y-m-d'))?'disabled':''?>' <?= $creneauDate->annule?'disabled':'data-toggle="confirmation" data-title="Êtes-vous sûr ?" data-popout="true" data-btn-Ok-Label="Oui" data-btn-Cancel-Label="Non"'?>>
                        <?= $creneauDate->annule?'Participation annulée':'Annuler ma participation'?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('[data-toggle=confirmation]').confirmation({
            rootSelector: '[data-toggle=confirmation]',
            // other options
        });
        $('#manual').hide();
        $('#manualBtnClose').hide();
    });
    
    function openManual(){
        $('#manual').show();
        $('#manualBtn').hide();
        $('#manualBtnClose').show();
    }
    
    function hideManual(){
        $('#manual').hide();
        $('#manualBtn').show();
        $('#manualBtnClose').hide();
    }
</script>