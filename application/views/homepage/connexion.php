<<<<<<< HEAD
<?php
/**
 * Vue 
 * Connexion
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class='text-center form-connexion'>
    <h1>Connexion</h1>
    <?php
    //On s'assure que l'utilisateur n'est pas bloqué, sans quoi on affiche juste le message concerné
    if (!isset($on_hold_message)) {
        //S'il y a une erreur de connexion, il faut l'afficher
        if (isset($login_error_mesg)) {
            $nbRest = config_item('max_allowed_attempts') - $this->authentication->login_errors_count;
            ?>
            <div class="alert alert-warning" role="alert">
                Mail ou mot de passe incorrect </br> <?= $nbRest ?> essai<?= ($nbRest > 1) ? 's' : '' ?> restant<?= ($nbRest > 1) ? 's' : '' ?>
                <?php if ($nbRest < 3) { ?>
                    <p>
                        Attention : pensez aux majuscules/minuscules
                    </p>
                <?php } ?>
            </div>
            <?php
        }

        //Si cette page est appelée pour déconnexion, on affiche le bon message
        if ($this->input->get('logout')) {
            ?>

            <div class="alert alert-success" role="alert">
                Vous êtes déconnectés
            </div>
            <?php
        }

        //Initialisation du formulaire Community auth
        echo form_open($login_url, ['class' => 'std-form']);
        ?>

        <div>

            <input placeholder='E-mail' type="text" name="login_string" value="<?php echo set_value('login_string'); ?>" class='form-control'/>

            <input placeholder='Mot de passe' type="password" name="login_pass" value="" class='form-control'/>

            <button class='btn btn-lg btn-block btn-primary' type="submit" />Envoyer</button>

        </div>
    </form>
    <p><a href='<?php echo site_url('recuperation'); ?>' class='btn btn-lg btn-block btn-secondary'>Mot de passe oublié ?</a></p>
    <?php
} else {
    // Message affiché en cas de trop nombreuses tentatives de connexion
    ?>

    <div class="alert alert-danger" role="alert">
        <h3>Attention</h3>
        <p>
            Nombre maximal de tentatives atteint
        </p>
        <p>
            Suite à plus de <?= config_item('max_allowed_attempts') ?> tentatives de connexion échouées, votre compte est temporairement bloqué.
        <p>
        <p>
            Merci de réessayer dans : <?= ( (int) config_item('seconds_on_hold') / 60 ) ?> minutes.
        </p>
    </div>
    </div>
    <?php
=======
<?php
/**
 * Vue 
 * Connexion
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class='text-center form-connexion'>
    <h1>Connexion</h1>
    <?php
    //On s'assure que l'utilisateur n'est pas bloqué, sans quoi on affiche juste le message concerné
    if (!isset($on_hold_message)) {
        //S'il y a une erreur de connexion, il faut l'afficher
        if (isset($login_error_mesg)) {
            $nbRest = config_item('max_allowed_attempts') - $this->authentication->login_errors_count;
            ?>
            <div class="alert alert-warning" role="alert">
                Mail ou mot de passe incorrect </br> <?= $nbRest ?> essai<?= ($nbRest > 1) ? 's' : '' ?> restant<?= ($nbRest > 1) ? 's' : '' ?>
                <?php if ($nbRest < 3) { ?>
                    <p>
                        Attention : pensez aux majuscules/minuscules
                    </p>
                <?php } ?>
            </div>
            <?php
        }

        //Si cette page est appelée pour déconnexion, on affiche le bon message
        if ($this->input->get('logout')) {
            ?>

            <div class="alert alert-success" role="alert">
                Vous êtes déconnectés
            </div>
            <?php
        }

        //Initialisation du formulaire Community auth
        echo form_open($login_url, ['class' => 'std-form']);
        ?>

        <div>

            <input placeholder='E-mail' type="text" name="login_string" value="<?php echo set_value('login_string'); ?>" class='form-control'/>

            <input placeholder='Mot de passe' type="password" name="login_pass" value="" class='form-control'/>

            <button class='btn btn-lg btn-block btn-primary' type="submit" />Envoyer</button>

        </div>
    </form>
    <p><a href='<?php echo site_url('recuperation'); ?>' class='btn btn-lg btn-block btn-secondary'>Mot de passe oublié ?</a></p>
    <?php
} else {
    // Message affiché en cas de trop nombreuses tentatives de connexion
    ?>

    <div class="alert alert-danger" role="alert">
        <h3>Attention</h3>
        <p>
            Nombre maximal de tentatives atteint
        </p>
        <p>
            Suite à plus de <?= config_item('max_allowed_attempts') ?> tentatives de connexion échouées, votre compte est temporairement bloqué.
        <p>
        <p>
            Merci de réessayer dans : <?= ( (int) config_item('seconds_on_hold') / 60 ) ?> minutes.
        </p>
    </div>
    </div>
    <?php
>>>>>>> 7ff721c (initial commit)
}