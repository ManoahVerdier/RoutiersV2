<<<<<<< HEAD
<?php
/**
 * Vue
 * Formulaire de changement de mdp
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<h1>Récupération de compte - Étape 2</h1>

<?php
$showform = 1;

if (isset($validation_errors)) {
?>
		<div class="alert alert-warning" role="alert">
                    <h3>Mot de passe incorrect</h3>
                        <?=$validation_errors?>
			<p>
                            <b>Le mot de passe n'a pas été modifié.</b>
			</p>
		</div>
	<?php
} else {
    $display_instructions = 1;
}

if (isset($validation_passed)) {
?>
		<div class="alert alert-success" role="alert">
                        <h3> Succès !</h3>
                        <p>
				Votre mot de passe a été mis à jour.
				Vous pouvez à présent vous <a href="<?=site_url('connexion')?>">connecter</a>
			</p>
		</div>
	<?php

    $showform = 0;
}
if (isset($recovery_error)) {
?>
		<div class="alert alert-danger" role="alert">
                    <h3>Erreur</h3>
			<p>
				Données de récupération introuvables : lien expiré ou incorrect.
			</p>
			<p>
				Le lien de récupération expire au bout de
				<?=( (int) config_item('recovery_code_expiration') / ( 60 * 60 ) ) ?>
				heures.<br />Merci de bien vouloir utiliser le lien suivant :  
				<a href="<?=site_url('recuperation')?>">Récupération de compte</a> 
				afin de recevoir un nouveau lien.
			</p>
		</div>
	<?php

    $showform = 0;
}
if (isset($disabled)) {
    ?>
		<div class="alert alert-danger" role="alert">
                    <h3>Compte bloqué</h3>
			<p>
				La récupération de compte est desactivée.
			</p>
			<p>
				Vous avez atteint le maximum de tentatives de connexion ou bien
				le maximum de tentatives de modification du mot de passe.
				Le compte sera débloqué dans <?= ( (int) config_item('seconds_on_hold') / 60 ) ?> 
				minutes.
			</p>
		</div>
	<?php

    $showform = 0;
}
if ($showform == 1) {
    if (isset($recovery_code, $user_id)) {
        ?>
        <div id="form" class="text-center form-mdp">
        <?php echo form_open(); ?>
            <fieldset>
                <legend>Choisissez votre nouveau mot de passe</legend>
                <div>

        <?php
        // Champ Mdp et Label associé ********************************

        $input_data = [
            'name' => 'passwd',
            'id' => 'passwd',
            'class' => 'form_input password form-control',
            'placeholder' => 'Mot de passe',
            'max_length' => config_item('max_chars_for_password')
        ];
        echo form_password($input_data);
        ?>

                </div>
                <div>

                    <?php
                    // CONFIRM PASSWORD LABEL AND INPUT ******************************

                    $input_data = [
                        'name' => 'passwd_confirm',
                        'id' => 'passwd_confirm',
                        'placeholder' => 'Confirmation',
                        'class' => 'form_input password form-control',
                        'max_length' => config_item('max_chars_for_password')
                    ];
                    echo form_password($input_data);
                    ?>

                </div>
            </fieldset>
            <div>
                <div>

                    <?php
                    // RECOVERY CODE *****************************************************************
                    echo form_hidden('recovery_code', $recovery_code);

                    // USER ID *****************************************************************
                    echo form_hidden('user_identification', $user_id);

                    // SUBMIT BUTTON **************************************************************
                    $input_data = [
                        'name' => 'form_submit',
                        'id' => 'submit_button',
                        'class' => 'btn btn-lg btn-primary',
                        'value' => 'Changer le mot de passe'
                    ];
                    echo form_submit($input_data);
                    ?>

                </div>
            </div>
        </form>
        </div>
                    <?php
                }
            }
=======
<?php
/**
 * Vue
 * Formulaire de changement de mdp
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<h1>Récupération de compte - Étape 2</h1>

<?php
$showform = 1;

if (isset($validation_errors)) {
?>
		<div class="alert alert-warning" role="alert">
                    <h3>Mot de passe incorrect</h3>
                        <?=$validation_errors?>
			<p>
                            <b>Le mot de passe n'a pas été modifié.</b>
			</p>
		</div>
	<?php
} else {
    $display_instructions = 1;
}

if (isset($validation_passed)) {
?>
		<div class="alert alert-success" role="alert">
                        <h3> Succès !</h3>
                        <p>
				Votre mot de passe a été mis à jour.
				Vous pouvez à présent vous <a href="<?=site_url('connexion')?>">connecter</a>
			</p>
		</div>
	<?php

    $showform = 0;
}
if (isset($recovery_error)) {
?>
		<div class="alert alert-danger" role="alert">
                    <h3>Erreur</h3>
			<p>
				Données de récupération introuvables : lien expiré ou incorrect.
			</p>
			<p>
				Le lien de récupération expire au bout de
				<?=( (int) config_item('recovery_code_expiration') / ( 60 * 60 ) ) ?>
				heures.<br />Merci de bien vouloir utiliser le lien suivant :  
				<a href="<?=site_url('recuperation')?>">Récupération de compte</a> 
				afin de recevoir un nouveau lien.
			</p>
		</div>
	<?php

    $showform = 0;
}
if (isset($disabled)) {
    ?>
		<div class="alert alert-danger" role="alert">
                    <h3>Compte bloqué</h3>
			<p>
				La récupération de compte est desactivée.
			</p>
			<p>
				Vous avez atteint le maximum de tentatives de connexion ou bien
				le maximum de tentatives de modification du mot de passe.
				Le compte sera débloqué dans <?= ( (int) config_item('seconds_on_hold') / 60 ) ?> 
				minutes.
			</p>
		</div>
	<?php

    $showform = 0;
}
if ($showform == 1) {
    if (isset($recovery_code, $user_id)) {
        ?>
        <div id="form" class="text-center form-mdp">
        <?php echo form_open(); ?>
            <fieldset>
                <legend>Choisissez votre nouveau mot de passe</legend>
                <div>

        <?php
        // Champ Mdp et Label associé ********************************

        $input_data = [
            'name' => 'passwd',
            'id' => 'passwd',
            'class' => 'form_input password form-control',
            'placeholder' => 'Mot de passe',
            'max_length' => config_item('max_chars_for_password')
        ];
        echo form_password($input_data);
        ?>

                </div>
                <div>

                    <?php
                    // CONFIRM PASSWORD LABEL AND INPUT ******************************

                    $input_data = [
                        'name' => 'passwd_confirm',
                        'id' => 'passwd_confirm',
                        'placeholder' => 'Confirmation',
                        'class' => 'form_input password form-control',
                        'max_length' => config_item('max_chars_for_password')
                    ];
                    echo form_password($input_data);
                    ?>

                </div>
            </fieldset>
            <div>
                <div>

                    <?php
                    // RECOVERY CODE *****************************************************************
                    echo form_hidden('recovery_code', $recovery_code);

                    // USER ID *****************************************************************
                    echo form_hidden('user_identification', $user_id);

                    // SUBMIT BUTTON **************************************************************
                    $input_data = [
                        'name' => 'form_submit',
                        'id' => 'submit_button',
                        'class' => 'btn btn-lg btn-primary',
                        'value' => 'Changer le mot de passe'
                    ];
                    echo form_submit($input_data);
                    ?>

                </div>
            </div>
        </form>
        </div>
                    <?php
                }
            }
>>>>>>> 7ff721c (initial commit)
/* End of file choose_password_form.php */