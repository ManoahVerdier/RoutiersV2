<?php

<<<<<<< HEAD
class Homepage extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('layout');
    }

    /**
     * Page d'accueil
     */
    public function index() {
        /* Connexion requise */
        if ($this->require_min_level(1)) {
            
            /*Quelques infos*/
            $this->load->model('M_participation');
            $this->load->model('M_rapport');
            $this->load->model('M_utilisateurs');
            /*Nombre d'invitations*/
            $data['nbInvit']=sizeof($this->M_participation->getAllInvitByUser($this->auth_user_id));
            
            /*Nombre de rapports a faire*/
            $parts=$this->M_participation->getPastPartCompleteByUser($this->auth_user_id);
            $misses=false;
            $raps = array();
            foreach($parts as $part){
                $rap = new stdClass;
                if($this->M_rapport->isReportMissingByCren($part->idCren)){
                    $rap->missing=1;
                    $rap->idCren=$part->idCren;
                    $rap->date=$part->date;
                    $misses=true;
                }
                $raps[]=$rap;
            }
            $data['nbRap']=sizeof($raps);
            
            /*Infos urgentes ?*/
            $congId = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
            $data['urgent']=0;
            foreach($this->M_utilisateurs->getInfosCong($congId) as $info){
                if($info->urgent && $info->actif=='on')
                    $data['urgent']=1;
            }
            
            //On affiche
            $this->layout->set_container(1);
            $this->layout->view('homepage/index.php', $data);
        }
    }

    /**
     * Page de connexion
     */
    public function connexion() {
        /* Si des données sont passées via post */
        if (strtolower(filter_input(INPUT_SERVER, 'REQUEST_METHOD')) == 'post'){
            if($this->require_min_level(1))
                redirect('/');
        }

        $this->setup_login_form();

        /* Pas d'en-tete (basée sur les données utilisateur) ni de bottom */
        $this->layout->set_top(0);
        $this->layout->set_bottom(0);

        $this->layout->ajouter_css('connexion');
        $this->layout->view('homepage/connexion.php', '');
    }

    /**
     * Gestion de la déconnexion : déco puis redirection a la page d'accueil avec message
     */
    public function deconnexion() {
        $this->authentication->logout();
        // Protocol de la redirection
        $redirect_protocol = USE_SSL ? 'https' : NULL;

        redirect(site_url(LOGIN_PAGE . '?logout=1', $redirect_protocol));
    }

    /**
     * Procédure de récupération de compte (oubli de mdp/blocage)
     */
    public function recuperation() {
        $this->load->model('M_utilisateurs');

        /// Si l'IP ou le mail sont encore bloqués, il faut prévoir un message
        if ($on_hold = $this->authentication->current_hold_status(TRUE)) {
            $view_data['disabled'] = 1;
        } else {
            // Si les données postées semblent OK
            if ($this->tokens->match && $this->input->post('email')) {
                if ($user_data = $this->M_utilisateurs->get_recuperation($this->input->post('email'))) {
                    // Vérification : l'utilisateur est-il banni ?
                    if ($user_data->banned == '1') {
                        // Si oui, écriture dans les logs...
                        $this->authentication->log_error($this->input->post('email', TRUE));

                        // ... et prévoir un message
                        $view_data['banned'] = 1;
                    } else {
                        /**
                         * Création d'une chaine aléatoire de 4*22 caractères
                         * réduite ensuite à 72 caractères
                         */
                        $recovery_code = substr($this->authentication->random_salt()
                                . $this->authentication->random_salt()
                                . $this->authentication->random_salt()
                                . $this->authentication->random_salt(), 0, 72);

                        // Mise à jour des données utilisateur avec le code et l'heure
                        $this->M_utilisateurs->update_user(
                                $user_data->user_id, [
                            'passwd_recovery_code' => $this->authentication->hash_passwd($recovery_code),
                            'passwd_recovery_date' => date('Y-m-d H:i:s')
                                ]
                        );

                        // Réglage du protocole
                        $link_protocol = USE_SSL ? 'https' : NULL;

                        // Réglage de l'URI
                        $link_uri = 'verification/' . $user_data->user_id . '/' . $recovery_code;

                        $view_data['confirmation'] = 1;
                        
                        $this->sendActivationLink(anchor(
                                        site_url($link_uri, $link_protocol), site_url($link_uri, $link_protocol), 'target ="_blank"'
                                ), $this->input->post('email'));
                    }
                }

                // Passé ce point, l'utilisateur n'a pas été trouvé, on prévoit donc un message
                else {
                    // Ecriture des logs
                    $this->authentication->log_error($this->input->post('email', TRUE));

                    $view_data['no_match'] = 1;
                }
            }
        }

        $this->layout->set_container(1);
        /* Pas de top et bottom si pas connecté : données util nécessaires */
        $this->layout->set_top(0);
        $this->layout->set_bottom(0);
        echo $this->layout->view('homepage/recuperation', ( isset($view_data) ) ? $view_data : '', TRUE);
    }

    /**
     * Envoie un mail avec un lien d'activation
     * @param string $link
     * @param string $mail l'adresse mail
     */
    private function sendActivationLink($link = 'test', $mail = 'manoah.verdier@gmail.com') {
        //Chargement de la bibliothèque d'envoi de mail
        $this->load->library('email');

        //Type de mail
        $config['mailtype'] = 'html';

        //Headers et footers html
        $headerHTML = '<html><head></head><body>';
        $footerHTML = '</body></html>';

        $this->email->initialize($config);

        $this->email->from('support@temoignage-normandie.site', 'Routiers');
        $this->email->to($mail);

        $this->email->subject('Réactivation de votre compte');
        $this->email->message($headerHTML .
                'Bonjour<br><br>
		                       Afin de réactiver votre compte, merci de cliquer sur le lien suivant ou de le copier/coller dans votre navigateur : <br><br>' .
                $link . '<br><br>
							   Nous vous souhaitons une bonne journée !<br><br><br>
							   L\'équipe des routiers' .
                $footerHTML);

        //print_r($this->email);
        $this->email->send();
    }

    /**
     * Formulaire de changement de mot de passe et vérification de la validité du lien
     * 
     * @param  int     l'id utilisateur
     * @param  string  le code de récupération
     */
    public function verification($user_id = '', $recovery_code = '') {
        /// Si l'IP est toujours bloquée, il faut prévoir un message
        if ($on_hold = $this->authentication->current_hold_status(TRUE)) {
            $view_data['disabled'] = 1;
        } else {
            $this->load->model('m_utilisateurs');

            if (
            /**
             * On vérifie que l'id est bien un chiffre
             * et qu'il fait au plus 10 caractères
             */
                    is_numeric($user_id) && strlen($user_id) <= 10 &&
                    /**
                     * On vérifie que le code de récupération fait 72 caractères exactement
                     */
                    strlen($recovery_code) == 72 &&
                    /**
                     * On récupère le code de récupération de cet utilisateur et un user_salt
                     */
                    $recovery_data = $this->m_utilisateurs->get_verification($user_id)) {
                /**
                 * On vérifie que ca correspond
                 */
                if ($recovery_data->passwd_recovery_code == $this->authentication->check_passwd($recovery_data->passwd_recovery_code, $recovery_code)) {
                    $view_data['user_id'] = $user_id;
                    $view_data['username'] = $recovery_data->username;
                    $view_data['recovery_code'] = $recovery_data->passwd_recovery_code;
                }

                // Sinon le lien est mauvais, il faut afficher un message
                else {
                    $view_data['recovery_error'] = 1;

                    // Ecriture dans les logs
                    $this->authentication->log_error('');
                }
            }

            // Sinon le lien est corrompu, on prévoit donc un message
            else {
                $view_data['recovery_error'] = 1;

                // Ecriture des logs
                $this->authentication->log_error('');
            }

            /**
             * Si le changement de mot de passe est requis 
             */
            if ($this->tokens->match) {
                $this->changerMdp();
            }
        }
        $this->layout->set_container(1);
        $this->layout->set_top(0);
        $this->layout->set_bottom(0);
        $this->layout->ajouter_css('verification');
        echo $this->layout->view('homepage/formulaire_mdp', $view_data, TRUE);
    }

    /**
     * Fonction interne pour vérifier puis effectuer le changement de mdp en base 
     */
    private function changerMdp() {
        //Bibliothèque pour validation des formulaires
        $this->load->library('form_validation');

        //Règles de validation
        $this->form_validation->set_rules([
            [
                'field' => 'passwd',
                'label' => 'Mot de passe',
                'rules' => [
                    'trim',
                    'required',
                    'matches[passwd_confirm]',
                    [
                        '_check_password_strength', //Callback voir fonction _check_password_strength
                        [$this, '_verifMdp']
                    ]
                ]
            ],
            [
                'field' => 'passwd_confirm',
                'label' => 'Confirmation',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'recovery_code'
            ],
            [
                'field' => 'user_identification'
            ]
        ]);

        // Exécution et test de la validité
        if ($this->form_validation->run() !== FALSE) {
            //Tout est ok, on peut modifier et prévoir un message
            $this->load->vars(['validation_passed' => 1]);
            //Enregistrement par le modèle
            $this->m_utilisateurs->_changer_mdp(
                    set_value('passwd'), set_value('passwd_confirm'), set_value('user_identification'), set_value('recovery_code')
            );
        } else {
            //Quelque chose n'allait pas, il faut afficher les erreurs
            $this->load->vars(['validation_errors' => validation_errors()]);
        }
    }

    /* Vérification de la qualité du mdp */

    public function _verifMdp($password) {
        //Chargement de la bibliothèque appropriée
        $this->config->load('examples/password_strength');
        // Si un max de caractère est requis dans la config
        $max = config_item('max_chars_for_password') > 0 ? config_item('max_chars_for_password') : '';

        //Préparation de la regex et du message avec au moins le mini de caractères
        $regex = '(?=.{' . config_item('min_chars_for_password') . ',' . $max . '})';
        $error = '<li>Au moins ' . config_item('min_chars_for_password') . ' caractères</li>';

        //Maxis de caractères
        if (config_item('max_chars_for_password') > 0)
            $error .= '<li>Pas plus de ' . config_item('max_chars_for_password') . ' caractères</li>';

        // Nombres requis
        if (config_item('min_digits_for_password') > 0) {
            $regex .= '(?=(?:.*[0-9].*){' . config_item('min_digits_for_password') . ',})';
            $plural = config_item('min_digits_for_password') > 1 ? 's' : '';
            $error .= '<li>' . config_item('min_digits_for_password') . ' chiffre' . $plural . '</li>';
        }

        // Minuscules requises
        if (config_item('min_lowercase_chars_for_password') > 0) {
            $regex .= '(?=(?:.*[a-z].*){' . config_item('min_lowercase_chars_for_password') . ',})';
            $plural = config_item('min_lowercase_chars_for_password') > 1 ? 's' : '';
            $error .= '<li>' . config_item('min_lowercase_chars_for_password') . ' minuscule' . $plural . '</li>';
        }

        // Majuscule(s) requise(s)
        if (config_item('min_uppercase_chars_for_password') > 0) {
            $regex .= '(?=(?:.*[A-Z].*){' . config_item('min_uppercase_chars_for_password') . ',})';
            $plural = config_item('min_uppercase_chars_for_password') > 1 ? 's' : '';
            $error .= '<li>' . config_item('min_uppercase_chars_for_password') . ' majuscule' . $plural . '</li>';
        }

        // Symboles requis
        if (config_item('min_non_alphanumeric_chars_for_password') > 0) {
            $regex .= '(?=(?:.*[^a-zA-Z0-9].*){' . config_item('min_non_alphanumeric_chars_for_password') . ',})';
            $plural = config_item('min_non_alphanumeric_chars_for_password') > 1 ? 's' : '';
            $error .= '<li>' . config_item('min_non_alphanumeric_chars_for_password') . ' symbole' . $plural . '</li>';
        }

        //Si la regex passe
        if (preg_match('/^' . $regex . '.*$/', $password)) {
            return TRUE;
        }

        //Sinon, enregistrer le message d'erreur et retourner faux
        $this->form_validation->set_message(
                '_check_password_strength', '<span class="redfield">Le mot de passe</span> doit être composé de :
				<ul>
					' . $error . '
				</ul>
			</span>'
        );

        return FALSE;
    }

=======


class Homepage extends MY_Controller {



    public function __construct() {

        parent::__construct();

        $this->load->helper('url');

        $this->load->helper('form');

        $this->load->library('layout');

    }



    /**

     * Page d'accueil

     */

    public function index() {

        /* Connexion requise */

        if ($this->require_min_level(1)) {

            

            /*Quelques infos*/

            $this->load->model('M_participation');

            $this->load->model('M_rapport');

            $this->load->model('M_utilisateurs');

            /*Nombre d'invitations*/

            $data['nbInvit']=sizeof($this->M_participation->getAllInvitByUser($this->auth_user_id));

            

            /*Nombre de rapports a faire*/

            $parts=$this->M_participation->getPastPartCompleteByUser($this->auth_user_id);

            $misses=false;

            $raps = array();

            foreach($parts as $part){

                $rap = new stdClass;

                if($this->M_rapport->isReportMissingByCren($part->idCren)){

                    $rap->missing=1;

                    $rap->idCren=$part->idCren;

                    $rap->date=$part->date;

                    $misses=true;

                }

                $raps[]=$rap;

            }

            $data['nbRap']=sizeof($raps);

            

            /*Infos urgentes ?*/

            $congId = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;

            $data['urgent']=0;

            foreach($this->M_utilisateurs->getInfosCong($congId) as $info){

                if($info->urgent && $info->actif=='on')

                    $data['urgent']=1;

            }

            

            //On affiche

            $this->layout->set_container(1);

            $this->layout->view('homepage/index.php', $data);

        }

    }



    /**

     * Page de connexion

     */

    public function connexion() {

        /* Si des données sont passées via post */

        if (strtolower(filter_input(INPUT_SERVER, 'REQUEST_METHOD')) == 'post'){

            if($this->require_min_level(1))

                redirect('/');

        }



        $this->setup_login_form();



        /* Pas d'en-tete (basée sur les données utilisateur) ni de bottom */

        $this->layout->set_top(0);

        $this->layout->set_bottom(0);



        $this->layout->ajouter_css('connexion');

        $this->layout->view('homepage/connexion.php', '');

    }



    /**

     * Gestion de la déconnexion : déco puis redirection a la page d'accueil avec message

     */

    public function deconnexion() {

        $this->authentication->logout();

        // Protocol de la redirection

        $redirect_protocol = USE_SSL ? 'https' : NULL;



        redirect(site_url(LOGIN_PAGE . '?logout=1', $redirect_protocol));

    }



    /**

     * Procédure de récupération de compte (oubli de mdp/blocage)

     */

    public function recuperation() {

        $this->load->model('M_utilisateurs');



        /// Si l'IP ou le mail sont encore bloqués, il faut prévoir un message

        if ($on_hold = $this->authentication->current_hold_status(TRUE)) {

            $view_data['disabled'] = 1;

        } else {

            // Si les données postées semblent OK

            if ($this->tokens->match && $this->input->post('email')) {

                if ($user_data = $this->M_utilisateurs->get_recuperation($this->input->post('email'))) {

                    // Vérification : l'utilisateur est-il banni ?

                    if ($user_data->banned == '1') {

                        // Si oui, écriture dans les logs...

                        $this->authentication->log_error($this->input->post('email', TRUE));



                        // ... et prévoir un message

                        $view_data['banned'] = 1;

                    } else {

                        /**

                         * Création d'une chaine aléatoire de 4*22 caractères

                         * réduite ensuite à 72 caractères

                         */

                        $recovery_code = substr($this->authentication->random_salt()

                                . $this->authentication->random_salt()

                                . $this->authentication->random_salt()

                                . $this->authentication->random_salt(), 0, 72);



                        // Mise à jour des données utilisateur avec le code et l'heure

                        $this->M_utilisateurs->update_user(

                                $user_data->user_id, [

                            'passwd_recovery_code' => $this->authentication->hash_passwd($recovery_code),

                            'passwd_recovery_date' => date('Y-m-d H:i:s')

                                ]

                        );



                        // Réglage du protocole

                        $link_protocol = USE_SSL ? 'https' : NULL;



                        // Réglage de l'URI

                        $link_uri = 'verification/' . $user_data->user_id . '/' . $recovery_code;



                        $view_data['confirmation'] = 1;

                        

                        $this->sendActivationLink(anchor(

                                        site_url($link_uri, $link_protocol), site_url($link_uri, $link_protocol), 'target ="_blank"'

                                ), $this->input->post('email'));

                    }

                }



                // Passé ce point, l'utilisateur n'a pas été trouvé, on prévoit donc un message

                else {

                    // Ecriture des logs

                    $this->authentication->log_error($this->input->post('email', TRUE));



                    $view_data['no_match'] = 1;

                }

            }

        }



        $this->layout->set_container(1);

        /* Pas de top et bottom si pas connecté : données util nécessaires */

        $this->layout->set_top(0);

        $this->layout->set_bottom(0);

        echo $this->layout->view('homepage/recuperation', ( isset($view_data) ) ? $view_data : '', TRUE);

    }



    /**

     * Envoie un mail avec un lien d'activation

     * @param string $link

     * @param string $mail l'adresse mail

     */

    private function sendActivationLink($link = 'test', $mail = 'manoah.verdier@gmail.com') {

        //Chargement de la bibliothèque d'envoi de mail

        $this->load->library('email');



        //Type de mail

        $config['mailtype'] = 'html';



        //Headers et footers html

        $headerHTML = '<html><head></head><body>';

        $footerHTML = '</body></html>';



        $this->email->initialize($config);



        $this->email->from('support@temoignage-normandie.site', 'Routiers');

        $this->email->to($mail);



        $this->email->subject('Réactivation de votre compte');

        $this->email->message($headerHTML .

                'Bonjour<br><br>

		                       Afin de réactiver votre compte, merci de cliquer sur le lien suivant ou de le copier/coller dans votre navigateur : <br><br>' .

                $link . '<br><br>

							   Nous vous souhaitons une bonne journée !<br><br><br>

							   L\'équipe des routiers' .

                $footerHTML);



        //print_r($this->email);

        $this->email->send();

    }



    /**

     * Formulaire de changement de mot de passe et vérification de la validité du lien

     * 

     * @param  int     l'id utilisateur

     * @param  string  le code de récupération

     */

    public function verification($user_id = '', $recovery_code = '') {

        /// Si l'IP est toujours bloquée, il faut prévoir un message

        if ($on_hold = $this->authentication->current_hold_status(TRUE)) {

            $view_data['disabled'] = 1;

        } else {

            $this->load->model('m_utilisateurs');



            if (

            /**

             * On vérifie que l'id est bien un chiffre

             * et qu'il fait au plus 10 caractères

             */

                    is_numeric($user_id) && strlen($user_id) <= 10 &&

                    /**

                     * On vérifie que le code de récupération fait 72 caractères exactement

                     */

                    strlen($recovery_code) == 72 &&

                    /**

                     * On récupère le code de récupération de cet utilisateur et un user_salt

                     */

                    $recovery_data = $this->m_utilisateurs->get_verification($user_id)) {

                /**

                 * On vérifie que ca correspond

                 */

                if ($recovery_data->passwd_recovery_code == $this->authentication->check_passwd($recovery_data->passwd_recovery_code, $recovery_code)) {

                    $view_data['user_id'] = $user_id;

                    $view_data['username'] = $recovery_data->username;

                    $view_data['recovery_code'] = $recovery_data->passwd_recovery_code;

                }



                // Sinon le lien est mauvais, il faut afficher un message

                else {

                    $view_data['recovery_error'] = 1;



                    // Ecriture dans les logs

                    $this->authentication->log_error('');

                }

            }



            // Sinon le lien est corrompu, on prévoit donc un message

            else {

                $view_data['recovery_error'] = 1;



                // Ecriture des logs

                $this->authentication->log_error('');

            }



            /**

             * Si le changement de mot de passe est requis 

             */

            if ($this->tokens->match) {

                $this->changerMdp();

            }

        }

        $this->layout->set_container(1);

        $this->layout->set_top(0);

        $this->layout->set_bottom(0);

        $this->layout->ajouter_css('verification');

        echo $this->layout->view('homepage/formulaire_mdp', $view_data, TRUE);

    }



    /**

     * Fonction interne pour vérifier puis effectuer le changement de mdp en base 

     */

    private function changerMdp() {

        //Bibliothèque pour validation des formulaires

        $this->load->library('form_validation');



        //Règles de validation

        $this->form_validation->set_rules([

            [

                'field' => 'passwd',

                'label' => 'Mot de passe',

                'rules' => [

                    'trim',

                    'required',

                    'matches[passwd_confirm]',

                    [

                        '_check_password_strength', //Callback voir fonction _check_password_strength

                        [$this, '_verifMdp']

                    ]

                ]

            ],

            [

                'field' => 'passwd_confirm',

                'label' => 'Confirmation',

                'rules' => 'trim|required'

            ],

            [

                'field' => 'recovery_code'

            ],

            [

                'field' => 'user_identification'

            ]

        ]);



        // Exécution et test de la validité

        if ($this->form_validation->run() !== FALSE) {

            //Tout est ok, on peut modifier et prévoir un message

            $this->load->vars(['validation_passed' => 1]);

            //Enregistrement par le modèle

            $this->m_utilisateurs->_changer_mdp(

                    set_value('passwd'), set_value('passwd_confirm'), set_value('user_identification'), set_value('recovery_code')

            );

        } else {

            //Quelque chose n'allait pas, il faut afficher les erreurs

            $this->load->vars(['validation_errors' => validation_errors()]);

        }

    }



    /* Vérification de la qualité du mdp */



    public function _verifMdp($password) {

        //Chargement de la bibliothèque appropriée

        $this->config->load('examples/password_strength');

        // Si un max de caractère est requis dans la config

        $max = config_item('max_chars_for_password') > 0 ? config_item('max_chars_for_password') : '';



        //Préparation de la regex et du message avec au moins le mini de caractères

        $regex = '(?=.{' . config_item('min_chars_for_password') . ',' . $max . '})';

        $error = '<li>Au moins ' . config_item('min_chars_for_password') . ' caractères</li>';



        //Maxis de caractères

        if (config_item('max_chars_for_password') > 0)

            $error .= '<li>Pas plus de ' . config_item('max_chars_for_password') . ' caractères</li>';



        // Nombres requis

        if (config_item('min_digits_for_password') > 0) {

            $regex .= '(?=(?:.*[0-9].*){' . config_item('min_digits_for_password') . ',})';

            $plural = config_item('min_digits_for_password') > 1 ? 's' : '';

            $error .= '<li>' . config_item('min_digits_for_password') . ' chiffre' . $plural . '</li>';

        }



        // Minuscules requises

        if (config_item('min_lowercase_chars_for_password') > 0) {

            $regex .= '(?=(?:.*[a-z].*){' . config_item('min_lowercase_chars_for_password') . ',})';

            $plural = config_item('min_lowercase_chars_for_password') > 1 ? 's' : '';

            $error .= '<li>' . config_item('min_lowercase_chars_for_password') . ' minuscule' . $plural . '</li>';

        }



        // Majuscule(s) requise(s)

        if (config_item('min_uppercase_chars_for_password') > 0) {

            $regex .= '(?=(?:.*[A-Z].*){' . config_item('min_uppercase_chars_for_password') . ',})';

            $plural = config_item('min_uppercase_chars_for_password') > 1 ? 's' : '';

            $error .= '<li>' . config_item('min_uppercase_chars_for_password') . ' majuscule' . $plural . '</li>';

        }



        // Symboles requis

        if (config_item('min_non_alphanumeric_chars_for_password') > 0) {

            $regex .= '(?=(?:.*[^a-zA-Z0-9].*){' . config_item('min_non_alphanumeric_chars_for_password') . ',})';

            $plural = config_item('min_non_alphanumeric_chars_for_password') > 1 ? 's' : '';

            $error .= '<li>' . config_item('min_non_alphanumeric_chars_for_password') . ' symbole' . $plural . '</li>';

        }



        //Si la regex passe

        if (preg_match('/^' . $regex . '.*$/', $password)) {

            return TRUE;

        }



        //Sinon, enregistrer le message d'erreur et retourner faux

        $this->form_validation->set_message(

                '_check_password_strength', '<span class="redfield">Le mot de passe</span> doit être composé de :

				<ul>

					' . $error . '

				</ul>

			</span>'

        );



        return FALSE;

    }



>>>>>>> 7ff721c (initial commit)
    public function accueilRP(){
        /* Connexion requise */
    	/*if ($this->require_min_level(9)) {
	    //On affiche
            $this->layout->set_container(1);
            $this->load->model('M_participation');
            $this->load->model('M_utilisateurs');
            $data = array();
		if(isset($_GET['idCong'])){
		    $data['part']=$this->M_participation->hasPartCompleteByCong($_GET['idCong']);	
		}else{
		    $data['part']=$this->M_participation->hasPartCompleteByCong($this->M_utilisateurs->getCongId($this->auth_user_id)->id);	
		}
            
            $this->layout->set_RP();
            $this->layout->ajouter_css('accueilRP');
            $this->layout->view('homepage/indexRP.php', $data);
	}
<<<<<<< HEAD
        else */if ($this->require_min_level(6)) {
=======
        else*/ if ($this->require_min_level(6)) {
>>>>>>> 7ff721c (initial commit)
            //On affiche
            $this->layout->set_container(1);
            $this->load->model('M_participation');
            $this->load->model('M_utilisateurs');
            $data = array();
            $data['part']=$this->M_participation->hasPartCompleteByCong($this->M_utilisateurs->getCongId($this->auth_user_id)->id);
            $this->layout->set_RP();
            $this->layout->ajouter_css('accueilRP');
            $this->layout->view('homepage/indexRP.php', $data);
        }
    }
    
<<<<<<< HEAD
    public function accueilAdmin(){
        /* Connexion requise */
        if ($this->require_min_level(9)) {
            //On affiche
            $this->layout->set_container(1);
            $this->layout->set_Admin();
            $this->layout->ajouter_css('accueilRP');
            $this->layout->view('homepage/indexAdmin.php', '');
        }
    }
}
=======

    public function accueilAdmin(){

        /* Connexion requise */

        if ($this->require_min_level(9)) {

            //On affiche

            $this->layout->set_container(1);

            $this->layout->set_Admin();

            $this->layout->ajouter_css('accueilRP');

            $this->layout->view('homepage/indexAdmin.php', '');

        }

    }

}

>>>>>>> 7ff721c (initial commit)
