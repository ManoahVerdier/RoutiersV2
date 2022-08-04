<<<<<<< HEAD
<?php
/**
 * Vue
 * Formulaire de récupération de compte
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<h1>Récupération de compte</h1>

<div class='form-recuperation'>
    <div class='row'>
        <?php
        //Si le compte est bloqué
        if (isset($disabled)) {
            ?>

            <div class="alert alert-danger col-12" role="alert">
                <h3>Attention</h3>
                <p>
                    Récupération de compte indisponible.
                </p>
                <p>
                    Si vous avez dépassé le nombre maximal de tentatives de connexion, ou dépassé
                    le nombre maximal de tentatives de récupération de compte, cette page 
                    est desactivée pendant quelques instants. 
                    Merci de patienter <?= ( (int) config_item('seconds_on_hold') / 60 ) ?>
                    minutes, ou bien contactez nous.
                </p>
            </div>
            <?php
        }
//Si le compte est banni
        else if (isset($banned)) {
            ?>
            <div class="alert alert-danger col-12" role="alert">
                <h3>Attention</h3>
                <p>
                    Compte bloqué.
                </p>
                <p>
                    Vous avez tenté d\'utiliser la récupération de compte en utilisant
                    une adresse mail qui appartient à un compte bloqué.
                    Si vous pensez que ceci est une erreur, merci de nous contacter.
                </p>
            </div>
            <?php
        }
//Si la récupération est valide
        else if (isset($confirmation)) {
            ?>
            <div class="alert alert-success col-12" role="alert">

                Récupération réussie ! Un mail contenant un lien de ré-activation vous a été envoyé. 
                <a href='<?= base_url() ?>'>Retourner à la page de connexion</a>

            </div>
            <?php
        }
//Si le mail ne correspond à rien
        else if (isset($no_match)) {
            ?>
            <div  class="alert alert-danger col-12" role="alert">
                Cette adresse mail ne correspond a aucun compte.
            </div>
            <?php
            $show_form = 1;
        }
//Dans tous les autres cas
        else {
            ?>
            <p>
                Si vous avez oublié votre mot de passe,
                entrez ici l'adresse mail associée à votre compte.
                Vous recevrez alors des instructions pour le réinitialiser.
            </p>
            <?php
            $show_form = 1;
        }
//Si l'affichage du formulaire est requis
        if (isset($show_form)) {
            echo form_open();
            ?>
        </div>
        <div class='row'>
            <?php
            // EMAIL ADDRESS *************************************************

            $input_mail_data = [
                'name' => 'email',
                'id' => 'email',
                'class' => 'form-control form-control-lg col-sm-10',
                'maxlength' => 255,
                'placeholder' => 'Email'
            ];
            echo form_input($input_mail_data);
            ?>


            <?php
            // SUBMIT BUTTON **************************************************************
            $input_submit_data = [
                'name' => 'submit',
                'id' => 'submit_button',
                'class' => 'btn btn-lg btn-primary col-sm-2',
                'value' => 'Envoi'
            ];
            echo form_submit($input_submit_data);
            ?>


        </div>
    </form>
    </div>
    <?php }
?>
=======
<?php
/**
 * Vue
 * Formulaire de récupération de compte
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<h1>Récupération de compte</h1>

<div class='form-recuperation'>
    <div class='row'>
        <?php
        //Si le compte est bloqué
        if (isset($disabled)) {
            ?>

            <div class="alert alert-danger col-12" role="alert">
                <h3>Attention</h3>
                <p>
                    Récupération de compte indisponible.
                </p>
                <p>
                    Si vous avez dépassé le nombre maximal de tentatives de connexion, ou dépassé
                    le nombre maximal de tentatives de récupération de compte, cette page 
                    est desactivée pendant quelques instants. 
                    Merci de patienter <?= ( (int) config_item('seconds_on_hold') / 60 ) ?>
                    minutes, ou bien contactez nous.
                </p>
            </div>
            <?php
        }
//Si le compte est banni
        else if (isset($banned)) {
            ?>
            <div class="alert alert-danger col-12" role="alert">
                <h3>Attention</h3>
                <p>
                    Compte bloqué.
                </p>
                <p>
                    Vous avez tenté d\'utiliser la récupération de compte en utilisant
                    une adresse mail qui appartient à un compte bloqué.
                    Si vous pensez que ceci est une erreur, merci de nous contacter.
                </p>
            </div>
            <?php
        }
//Si la récupération est valide
        else if (isset($confirmation)) {
            ?>
            <div class="alert alert-success col-12" role="alert">

                Récupération réussie ! Un mail contenant un lien de ré-activation vous a été envoyé. 
                <a href='<?= base_url() ?>'>Retourner à la page de connexion</a>

            </div>
            <?php
        }
//Si le mail ne correspond à rien
        else if (isset($no_match)) {
            ?>
            <div  class="alert alert-danger col-12" role="alert">
                Cette adresse mail ne correspond a aucun compte.
            </div>
            <?php
            $show_form = 1;
        }
//Dans tous les autres cas
        else {
            ?>
            <p>
                Si vous avez oublié votre mot de passe,
                entrez ici l'adresse mail associée à votre compte.
                Vous recevrez alors des instructions pour le réinitialiser.
            </p>
            <?php
            $show_form = 1;
        }
//Si l'affichage du formulaire est requis
        if (isset($show_form)) {
            echo form_open();
            ?>
        </div>
        <div class='row'>
            <?php
            // EMAIL ADDRESS *************************************************

            $input_mail_data = [
                'name' => 'email',
                'id' => 'email',
                'class' => 'form-control form-control-lg col-sm-10',
                'maxlength' => 255,
                'placeholder' => 'Email'
            ];
            echo form_input($input_mail_data);
            ?>


            <?php
            // SUBMIT BUTTON **************************************************************
            $input_submit_data = [
                'name' => 'submit',
                'id' => 'submit_button',
                'class' => 'btn btn-lg btn-primary col-sm-2',
                'value' => 'Envoi'
            ];
            echo form_submit($input_submit_data);
            ?>


        </div>
    </form>
    </div>
    <?php }
?>
>>>>>>> 7ff721c (initial commit)
</div>