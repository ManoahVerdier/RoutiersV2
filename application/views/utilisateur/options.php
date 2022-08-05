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
            <b>Le profil n'a pas été modifié.</b>
        </p>
    </div>
    <?php
}

/* Affichage d'un éventuel message de succès */
if (isset($validation_passed)) {
    ?>
    <div class="alert alert-success" role="alert">
        <h3> Succès !</h3>
        <p>
            Votre profil a été mis à jour.
        </p>
    </div>
    <?php
}

/* Formulaire */
?>
<div class='wrapOptions'>
    <h3>Options</h3>

    <?php echo form_open(); ?>

    <div class='row form-group'>
        <div class='col-sm'></div>
        <div class='col-sm-5 col-md-3'>
            <input class='form-control' name='prenom' id='prenom' type='text' placeholder='Prénom' value="<?= $user->prenom ?>"/>
        </div>
        <div class='col-sm-5 col-md-3'>
            <input class='form-control' name='nom' id='nom' type='text' placeholder='Nom' value="<?= $user->nom ?>"/>
        </div>
        <div class='col-sm'></div>
    </div>
    <div class='row form-group'>
        <div class='col-sm'></div>
        <div class='col-md-6 col-sm-10'>
            <input class='form-control' name='mail' id='mail' type='text' placeholder='E-mail' value="<?= $user->mail ?>"/>
        </div>
        <div class='col-sm'></div>
    </div>
    <div class='row form-group'>
        <div class='col-sm'></div>
        <div class='col-md-6 col-sm-10'>
            <input class='form-control' name='phone' id='phone' type='text' placeholder='Téléphone' value="<?= $user->telephone ?>"/>
        </div>
        <div class='col-sm'></div>
    </div>
    <div class='row form-group'>
        <div class='col-sm'></div>
        <div class='col-md-6 col-sm-10'>
            <input class='form-control form_input password' name='pass1' id='pass1' type='password' placeholder='Nouveau mot de passe (optionnel)'/>
        </div>
        <div class='col-sm'></div>
    </div>
    <div class='row form-group'>
        <div class='col-sm'></div>
        <div class='col-md-6 col-sm-10'>
            <input class='form-control form_input password' name='pass2' id='pass2' type='password' placeholder='Confirmation'/>
        </div>
        <div class='col-sm'></div>
    </div>   
    <!-- Champ caché : id utilisateur-->
    <div class='row'>
        <div class='col-sm'></div>
        <div class='col-md-6 col-sm-10'>
            <input name='user_identification' id="user_identification" type='hidden' value = "<?= $id ?>"/>
        </div>
        <div class='col-sm'></div>
    </div> 
    <div class='row'>
        <div class='col-sm'></div>
        <div class='col-md-6 col-sm-10'><input class='form-control btn btn-primary' id='submit' type='submit'/></div>
        <div class='col-sm'></div>
    </div> 
</form> <!--Seul car ouvert par fonction-->
</div>