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
    </div>
    <?php
}

/* Affichage d'un éventuel message de succès */
if (isset($validation_passed)) {
    ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <h3> Succès !</h3>
        <p>
            <?=(set_value('prenom')!=null || set_value('nom'))?set_value('prenom'):"L'utilisateur";?> <?=set_value('nom');?> a été invité à s'inscrire.
        </p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php
}

/* Formulaire */
?>
<div class='wrapOptions'>
    <h3>Inviter un <?=$dest=='RP'?'responsable prédication':'proclamateur'?> à s'inscrire</h3>

    <?php echo form_open(); ?>

    <div class='row form-group'>
        <div class='col-sm'></div>
        <div class='col-sm-3'>
            <input class='form-control' name='prenom' id='prenom' type='text' placeholder='Prénom' value='<?=set_value('prenom');?>'/>
        </div>
        <div class='col-sm-3'>
            <input class='form-control' name='nom' id='nom' type='text' placeholder='Nom'  value='<?=set_value('nom');?>'/>
        </div>
        <div class='col-sm'></div>
    </div>
    <div class='row form-group'>
        <div class='col-sm'></div>
        <div class='col-sm-6'>
            <input class='form-control' name='mail' id='mail' type='text' placeholder='E-mail'  value='<?=set_value('mail');?>'/>
        </div>
        <div class='col-sm'></div>
    </div>
    <?php if($dest=='RP'){?>
    <div class='row form-group'>
        <div class='col-sm'></div>
        <div class='col-sm-6'>
            <select class='form-control' name='idCong' id='idCong' type='text' placeholder='Assemblée'>
                <?php foreach($congs as $cong){?>
                    <option <?=set_value('cong')==$cong->id?'selected':''?> value="<?=$cong->id ?>"><?=$cong->nom ?></option>
                <?php } ?>
            </select>
        </div>
        <div class='col-sm'></div>
    </div>
    <?php }?>
    <div class='row'>
        <div class='col-sm'></div>
        <div class='col-sm-6'><input class='form-control btn btn-primary' id='submit' type='submit'/></div>
        <div class='col-sm'></div>
    </div> 
</form> <!--Seul car ouvert par fonction-->
<div class='row mt-3'>
    <div class='col-sm'></div>
    <div class='col-sm-6'><a class='btn btn-secondary btn-block' href='<?=$dest=='RP'?site_url('rp'):site_url('proclamateurs')?>'>Retourner à la liste</a></div>
    <div class='col-sm'></div>
</div>
</div>