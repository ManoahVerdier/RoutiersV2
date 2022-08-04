<?php
/**
 * Vue
 * Formulaire pour le rapport
 */
?>
<div class='rapport'>
    <h4 class='text-center border-top border-bottom'>Langue n°<?= $nbLng ?></h4>

    <?php /* Eventuels messages d'erreur ou de succès */ ?>
    <?php if (isset($message) && $message != '') { ?>
        <div class='alert alert-success'> <?= $message ?></div>
    <?php } ?>

    <?php if (validation_errors() != '') { ?>
        <div class='alert alert-danger errors'><?php echo validation_errors(); ?></div>
    <?php } ?>

    <?php echo form_open(); ?>

    <!--Choix de la langue-->
    <select name='langue' class='form-control <?= (form_error('langue') !== "") ? 'error' : '' ?>'>
        <option selected disabled  value='-1'>Choisir une langue</option>
        <?php foreach ($langs as $lang) { ?>
            <option value='<?= $lang->id ?>'  <?= (set_value('langue') == $lang->id && $populate) ? 'selected' : '' ?>><?= $lang->intitule ?></option>
        <?php } ?>
    </select>

    <!--Nb personnes vues-->
    <input min="0" type="number" name="nbRenc" value="<?php echo $populate ? set_value('nbRenc') : ''; ?>" size="50" class='form-control <?= (form_error('nbRenc') !== "") ? 'error' : '' ?>' placeholder="Nombre de contacts"/>

           <?php
           /* Affichage du nombre de lignes publications requis (0 par défaut, le nombre de ligne déjà affichée si rechargement) */
            $tmpType="";
           for ($cpt = 1; $cpt <= 10; $cpt++) {
               ?>
                <div id="publication<?= $cpt ?>" class="row position-relative">
                    <div class='col-sm-8'>
                        <select name='pub<?= $cpt ?>' id='pub<?= $cpt ?>'  class='form-control <?= (form_error('pub' . $cpt) !== "") ? 'error' : '' ?>'>
                            <option selected value='-1'>Choisir une publication</option>
                            <?php foreach ($pubs as $pub) { ?>
                                <?php if($pub->type!=$tmpType): if($tmpType!=""):?>
                                </optgroup>
                                <?php endif;?>
                                <optgroup label="<?=$pub->type?>">
                                <?php $tmpType=$pub->type; endif;?>
                                <option value='<?= $pub->id ?>' <?= (set_value('pub' . $cpt) == $pub->id) && $populate ? 'selected' : '' ?>><?= $pub->titre ?></option>
                            <?php } ?>
                                </optgroup>
                        </select>
                    </div>
                    <div class='col-sm-4'>
                        <input min="1" type="number" name="qte<?= $cpt ?>" value="<?php echo set_value('qte' . $cpt) && $populate ? set_value('qte' . $cpt) : 1; ?>" size="50"  class='form-control <?= (form_error('qte' . $cpt) !== "") ? 'error' : '' ?>' placeholder='Quantité'/>
                    </div>
                    <div class="delPub out btn btn-danger rounded-circle btn-sm py-0 px-1 mt-1" onclick="delPub();"><i class="fa fas fa-minus"></i></div>
                </div>

            <?php } ?>
    
    <?php
           /* Affichage du nombre de lignes faits requis (0 par défaut, le nombre de ligne déjà affichée si rechargement) */
           for ($cpt = 1; $cpt <= 10; $cpt++) {
               ?>
                <div id="fait<?= $cpt ?>" class="row">
                    <div class='col-sm-12'>
                        <textarea name='fait<?= $cpt ?>' id='fait<?= $cpt ?>'  class='mb-4 form-control <?= (form_error('fait' . $cpt) !== "") ? 'error' : '' ?>'><?php echo set_value('fait' . $cpt) && $populate ? set_value('fait' . $cpt) : "Note ici les détails du fait..."; ?></textarea>
                    </div>
                </div>

            <?php } ?>

    <!-- Charger une ligne de publications en plus-->
    <div class='addPub'>
        <button type='button' id='addPub' class='btn btn-lg btn-block btn-outline-secondary'/>Ajouter une publication</button>
    </div>
    <!-- Charger une ligne de fait en plus-->
    <div class='addFait mb-5'>
        <button type='button' id='addFait' class='btn btn-lg btn-block btn-outline-secondary'/>Ajouter un fait</button>
    </div>

    <div class='row'>
        <!--Fin définitive, appelé par les autres boutons fin-->
        <input type="hidden" name="fin" value="" id="submit"/>
        <!--Nombre de ligne de publications (pour pouvoir en réafficher autant si rechargement)-->
        <input type="hidden" name="nbPub" value="<?php echo set_value('nbPub'); ?>" id="nbPub"/>
        <!--Nombre de ligne de faits (pour pouvoir en réafficher autant si rechargement)-->
        <input type="hidden" name="nbFait" value="<?php echo set_value('nbFait'); ?>" id="nbFait"/>
        <!--Id Rapport sidéjà créé (donc au moins 2 langues)-->
        <input type="hidden" name="idRap" value="<?php echo "" !== $idRap ? $idRap : -1; ?>" id="nbPub"/>
        <!-- Nombre de langues en cours-->
        <input type="hidden" name="nbLng" value="<?php echo "" !== $nbLng ? $nbLng : 1; ?>" id="nbLng"/>
        <div class='col-sm-6'><button class='btn btn-lg btn-block btn-success' type="submit" id="fin"/>Envoyer</button></div>
        <div class='col-sm-6 hidden-phone'><button class='btn btn-lg btn-block btn-primary' type="submit" id="suite"/>Continuer avec une autre langue</button></div>
        <div class='col-sm-6 hidden-desktop hidden-tablet mobileLangAdd'><button class='btn btn-lg btn-block btn-primary' type="submit" id="suite"/>Autre langue</button></div>
    </div>

</form> <!-- Seul car ouvert par la fonction-->
</div>

<script>
    
    /*Récpération d nombre de lignes de publications*/
    var nbPub = 0<?= isset($nbPub) ? $nbPub : 0 ?>;
    /*Masquage des lignes de publications pour celles qui sont au dela de ce nombre*/
    for (var i = 1 + nbPub; i <= 10; i++) {
        $('#publication' + i).hide();
        $("select[name='pub" + i + "']").prop('disabled', true);
        $("input[name='qte" + i + "']").prop('disabled', true);
    }
    
    /*Evenement clic sur le bouton ajout de publication*/
    $('#addPub').bind('click', addPub);

    function addPub() {
        $('#publication' + nbPub+ ' .delPub').hide();
        nbPub++;                                                        //On incrémente le nb
        $('#publication' + nbPub+ ' .delPub').show();
        $('#nbPub').val(nbPub);                                         //On enregistre la nelle valeur dans le champ caché
        $('#publication' + nbPub).show();                               //On affiche la section
        $("select[name='pub" + nbPub + "']").prop('disabled', false);   //On réactive la liste
        $("input[name='qte" + nbPub + "']").prop('disabled', false);    //On réactive le nombre
    }
    
    function delPub() {
        $('#publication' + nbPub).hide();                                    //On cache la section
        $("select[name='pub" + nbPub + "']").prop('disabled', 'disabled');   //On desactive la liste
        $("input[name='qte" + nbPub + "']").prop('disabled', 'disabled');    //On desactive le nombre
        $('#publication' + nbPub+ ' .delPub').hide();
        nbPub--;                                                             //On décrémente le nb
        $('#nbPub').val(nbPub);                                              //On enregistre la nelle valeur dans le champ caché
        $('#publication' + nbPub+ ' .delPub').show();
    }
    
    /*Récpération d nombre de lignes de faits*/
    var nbFait = 0<?= isset($nbFait) ? $nbFait : 0 ?>;
    /*Masquage des lignes de publications pour celles qui sont au dela de ce nombre*/
    for (var i = 1 + nbFait; i <= 10; i++) {
        $('#fait' + i).hide();
        $("textarea[name='fait" + i + "']").prop('disabled', true);
    }
    
    /*Evenement clic sur le bouton ajout de publication*/
    $('#addFait').bind('click', addFait);

    function addFait() {
        nbFait++;                                                           //On incrémente le nb
        $('#nbFait').val(nbFait);                                           //On enregistre la nelle valeur dans le champ caché
        $('#fait' + nbFait).show();                                         //On affiche la section
        $("textarea[name='fait" + nbFait + "']").prop('disabled', false);   //On réactive la zone
    }
    
    /*Clic sur fin*/
    $('#fin').bind('click', function () {
        $('#submit').val('fin');
    });

    /*Clic sur ajouter une langue*/
    $('#suite').bind('click', function () {
        $('#submit').val('suite');
    });

/*Pour chaque ligne de pub*/
<?php for ($cpt = 1; $cpt <= 10; $cpt++) { ?>
        /*Quand on clic sur une liste de pub, le premier element deviens non selectionnable*/
        $('#pub1').bind('click', function () {
            $('#pub<?= $cpt ?> option:first').prop('disabled', true);
        });
        /*Et quand on en sors, il redeviens selectionnable*/
        $('#pub1').bind('focusout', function () {
            $('#pub<?= $cpt ?> option:first').prop('disabled', false);
        });
<?php } ?>
/*Ainsi, si aucune pub n'est choisie, -1 et retourné et on sait que ce n'est pas normal, par contre on ne peut pas choisir -1 manuellement.
 * Donc on est sur que -1=rien de choisi, et donc erreur*/

$(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});
</script>