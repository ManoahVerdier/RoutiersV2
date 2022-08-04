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
                        <div class="col-sm-12 text-center">Horaire : <?= $creneau->heure ?>h</div>
                        <div class="col-sm-12 text-center">Lieu : <?= $lieu ?></div>
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
        <div class="col-sm-4">
            <div class='row px-4 full-height rowCol'>
                <div class='col-sm-12 wrapCol border rounded shadow mob-mt-10'>
                    <h5 class="text-center  pt-4 pb-4">Invitations envoyées</h5>
                    <?php
                    foreach ($invits as $invit) {
                        $class = '';
                        $title = '';
                        if ($invit->ok === NULL) {
                            $class = 'warning';
                            $title = 'En attente de réponse';
                        } else if ($invit->ok == 1) {
                            $class = 'success';
                            $title = 'Acceptée';
                        } else if ($invit->ok == 0 || $invit->ok == -1) {
                            $class = 'danger';
                            $title = 'Refusée';
                        }
                        ?>
                        <div class="row pb-3 pt-3 alert alert-<?= $class ?> rounded-0">
                            <div class="col-sm-12 text-center"><b><?= $title ?></b></div>
                            <div class="col-sm-12 text-center"><?= $invit->prenom ?> <?= $invit->nom ?></div>
                            <div class="col-sm-12 text-center">Envoyée le <?= utf8_encode(strftime('%d/%m/%Y', strtotime($invit->date))) ?></div>
                        </div>
                        <?php
                        $cpt++;
                    }
                    ?>
                </div></div>
        </div>
    </div>
    <div class='row mt-3'>
        <div class='col-sm-4 mob-mb-10 mob-mt-10'>
            <div class='row px-4 full-height rowCol'>
                <div class='col-sm-12 wrapCol px-0'>
                    <a href='<?= site_url('creneaux') ?>' class='btn btn-secondary btn-block'>
                        Retour à la liste
                    </a>
                </div>
            </div>
        </div>
        <div class='col-sm-4 mob-mb-10'>
            <div class='row px-4 full-height rowCol'>
                <div class='col-sm-12 wrapCol px-0'>
                    <a href='<?= site_url('annuler') . '/' . $idCren ?>' class='btn btn<?= $creneauDate->annule?'-outline':''?>-danger btn-block <?= $creneauDate->annule?'disabled':''?>' <?= $creneauDate->annule?'disabled':'data-toggle="confirmation" data-title="Êtes-vous sûr ?" data-popout="true" data-btn-Ok-Label="Oui" data-btn-Cancel-Label="Non"'?>>
                        <?= $creneauDate->annule?'Créneau annulé':'Annuler le créneau'?>
                    </a>
                </div>
            </div>
        </div>

        <div class='col-sm-4 mob-mb-10'>
            <div class='row px-4 full-height rowCol'>
                <div class='col-sm-12 wrapCol px-0' id='manual'>
                    <form action='' method='post' class='row px-3 mb-2'>
                        <select id='procl' name='procl' class='select-control col-sm-8 mb-2 py-2'>
                            <option value='0' selected>Proclamateurs</option>
                            <?php foreach($procls as $procl){?>
                            <option value='<?=$procl->id?>'><?=$procl->prenom?> <?=$procl->nom?></option>
                            <?php } ?>
                            <option value='-1' selected>Tout le monde</option>
                        </select>    
                        <div class='col-sm-4 pl-2 pr-0'><input type='submit' id='submit' name='submit' value='Envoyer' class='btn btn-primary btn-block'/></div>
                    </form>
                </div>
                <div class='col-sm-12 wrapCol px-0'>
                    <input type='button' class='btn btn-<?= $creneauDate->annule?'outline-':''?><?=$nbPart>=$creneau->nb?'success':'primary'?> btn-block' value='<?=$creneauDate->annule?'Créneau annulé':$nbPart>=$creneau->nb?'Créneau complet':'Inviter manuellement'?>' onclick='openManual()' id='manualBtn' <?=$creneauDate->annule || $nbPart>=$creneau->nb?'disabled':''?>/>
                    <input type='button' class='btn btn-secondary btn-block' value='Annuler' onclick='hideManual()' id='manualBtnClose'/>
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