<?php
/**
 * Vue
 * Forumlaire de modification des infos utilisateur
 */
?>
<div class="home-header border-bottom box-shadow bg-white mb-5">
    <h1 class="text-center display-4">Témoignage aux routiers</h1>
    <hr/>   
    <h3 class="text-center text-muted  "><?= $cong->nom ?></h3>
</div>
<div class="container">
<?php
if (isset($expire)) {
    ?>
    <div class="alert alert-danger" role="alert">
        <h3>Erreur</h3>
        <p>
            <b>Ce lien a expiré. Les liens d'inscription ne sont valables qu'une semaine, merci de contacter ton responsable prédication afin qu'il t'envoie une nouvelle invitation.</b>
        </p>
    </div>
    <?php
}
else if (isset($invalide)) {
    ?>
    <div class="alert alert-danger" role="alert">
        <h3>Erreur</h3>
        <p>
            <b>Ce lien est invalide. Merci de contacter ton responsable prédication afin qu'il t'envoie une nouvelle invitation.</b>
        </p>
    </div>
    <?php
}else {
/* Affichage des éventuelles erreurs */
if (isset($validation_errors)) {
    ?>
    <div class="alert alert-warning" role="alert">
        <h3>Erreur</h3>
        <?= $validation_errors ?>
        <p>
            <b>Merci de corriger les erreurs</b>
        </p>
    </div>
    <?php
}

if (isset($mauvaisCode)) {
    ?>
    <div class="alert alert-warning" role="alert">
        <h3>Erreur</h3>
        <p>
            <b>Le code entré est erroné</b>
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
            Vous êtes bien inscrits !
        </p>
        <b> Vous pouvez dès à présent <a href='<?=site_url('connexion')?>'>Vous connecter</a>.</b>
    </div>
    <?php
}else {

/* Formulaire */
?>
<div class='wrapOptions'>
    <h3 class="text-center">Inscription</h3>

    <?php echo form_open(); ?>

    <div class='row form-group'>
        <div class='col-sm'></div>
        <div class='col-sm-3'>
            <input class='form-control' name='prenom' id='prenom' type='text' placeholder='Prénom' value="<?= set_value('prenom') ?>"/>
        </div>
        <div class='col-sm-3'>
            <input class='form-control' name='nom' id='nom' type='text' placeholder='Nom' value="<?= set_value('nom') ?>"/>
        </div>
        <div class='col-sm'></div>
    </div>
    <div class='row form-group'>
        <div class='col-sm'></div>
        <div class='col-sm-6'>
            <input class='form-control' name='mail' id='mail' type='text' placeholder='E-mail' value="<?= set_value('mail')!==''?set_value('mail'):$invit->mail ?>"/>
        </div>
        <div class='col-sm'></div>
    </div>
    <div class='row form-group'>
        <div class='col-sm'></div>
        <div class='col-sm-6'>
            <input class='form-control' name='phone' id='phone' type='text' placeholder='Téléphone' value="<?= set_value('phone') ?>"/>
        </div>
        <div class='col-sm'></div>
    </div>
    <div class='row form-group'>
        <div class='col-sm'></div>
        <div class='col-sm-6'>
            <input class='form-control form_input password' name='pass1' id='pass1' type='password' placeholder='Mot de passe'/>
        </div>
        <div class='col-sm'></div>
    </div>
    <div class='row form-group'>
        <div class='col-sm'></div>
        <div class='col-sm-6'>
            <input class='form-control form_input password' name='pass2' id='pass2' type='password' placeholder='Confirmation'/>
        </div>
        <div class='col-sm'></div>
    </div>   
    <div class='row form-group'>
        <div class='col-sm'></div>
        <div class='col-sm-6'>
            <input class='form-control form_input password' name='code' id='code' type='text' placeholder='Code invitation' value="<?= set_value('code') ?>"/>
        </div>
        <div class='col-sm'></div>
    </div>   
    <div class='row'>
        <div class='col-sm'></div>
        <div class='col-sm-6'><input class='form-control btn btn-primary' id='submit' type='submit'/></div>
        <div class='col-sm'></div>
    </div> 
</form> <!--Seul car ouvert par fonction-->
</div>
</div>

<?php }

}?>