<?php
/**
 * Vue
 * Forumlaire de modification des infos utilisateur
 */
/* Affichage des éventuelles erreurs */
if (isset($validation_errors)) {
    ?>
    <div class="alert alert-warning" role="alert">
        <h3>Erreur</h3>
        <?= $validation_errors ?>
        <p>
            <b>Le RP n'a pas été modifié.</b>
        </p>
    </div>
    <?php
}

/* Affichage d'un éventuel message de succès */
if (isset($validation_passed)) {
    ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <h3> Succès !</h3>
        <p>
            Le RP a été mis à jour.
        </p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php
}

/* Formulaire */
?>
<div class='wrapOptions mt-3'>
    <h3>Changement de responsable prédication : <?=$nom ?></h3>

    <?php echo form_open(); ?>

    
    <div class='row form-group'>
        <div class='col-sm'></div>
        <div class='col-sm-6'>
            <select class='form-control' name='nouvRP' id='nouvRP' type='text'>
                <?php foreach($procls as $procl){
                    if($procl->id!=$old){?>
                    <option value="<?=$procl->id ?>"><?=$procl->prenom ?> <?=$procl->nom ?></option>
                    <?php }} ?>
            </select>
        </div>
        <div class='col-sm'></div>
    </div>
    <div class='row'>
        <div class='col-sm'></div>
        <div class='col-sm-6'><input class='form-control btn btn-primary' id='submit' type='submit'/></div>
        <div class='col-sm'></div>
    </div> 
</form> <!--Seul car ouvert par fonction-->
<div class='row mt-3'>
    <div class='col-sm'></div>
    <div class='col-sm-6'><a class='btn btn-secondary btn-block' href='<?=site_url('rp')?>'>Retourner à la liste</a></div>
    <div class='col-sm'></div>
</div>
</div>