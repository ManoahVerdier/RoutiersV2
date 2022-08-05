<?php

/**
 * Classe Utilisateur : gestion des disponibilités, contacts, options et informations
 * i.e. liens du footer
 */
class Utilisateur extends MY_Controller {

    /**
     * Constructeur : chargement des helpers form (pour les options)
     * Chargement des modèles utilisateurs et créneaux
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('layout');
        $this->load->model('M_utilisateurs');
        $this->load->model('M_creneau');
        $this->load->model('M_congregations');
    }

    /**
     * Affichage et réglage des dispo a venir
     */
    public function disponibilites() {
        /* Connexion requise */
        if ($this->require_min_level(1)) {
            /* Après soumission */
            if ($this->input->post()) {
                /* Si indispo a ajouter */
                if ($this->input->post()['state'] == 1) {
                    /* Construction de l'objet : idCren + idUtil */
                    $indispo = new stdClass;
                    $indispo->idCren = $this->input->post()['id'];
                    $indispo->idUtil = $this->auth_user_id;
                    /* Le modèle enregistre */
                    $this->M_utilisateurs->addIndispo($indispo);
                } else    //Indispo a enlever
                    $this->M_utilisateurs->deleteIndispo($this->auth_user_id, $this->input->post()['id']);
            }

            /* ID de la congrégation (pour recherche des créneaux */
            $idCong = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;

            /* Dans tous mes cas, chargement des données depuis le modèle */
            /* Indispos déjà renseignées */
            $data['indispos'] = $this->M_utilisateurs->getIndispo($this->auth_user_id);
            /* Liste des prochains créneaux */
            $data['creneaux'] = $this->M_creneau->getProcCrenByCong($idCong);

            /*Infos urgentes*/
            $congId = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
            $data['urgent']=0;
            foreach($this->M_utilisateurs->getInfosCong($congId) as $info){
                if($info->urgent && $info->actif=='on')
                    $data['urgent']=1;
            }
            
            $this->layout->set_container(1);
            $this->layout->view('utilisateur/indispos', $data);
        }
    }

    /* Gestion des contacts : affichage des coordonnées des procl la même congrégation + RP */

    public function contacts() {
        /* Connexion requise */
        if ($this->require_min_level(1)) {
            /* Recherche de la congrégation */
            $congId = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
            /* Chargement de la liste des procl de la congrégation */
            $data['procls'] = $this->M_utilisateurs->getProclsFromCong($congId);
            /* Récupération de l'ID du RP */
            $data['rpp'] = $this->M_utilisateurs->getCongRp($this->auth_user_id)->idRP;

            /*Infos urgentes ?*/
            $congId = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
            $data['urgent']=0;
            foreach($this->M_utilisateurs->getInfosCong($congId) as $info){
                if($info->urgent && $info->actif=='on')
                    $data['urgent']=1;
            }
            
            $this->layout->set_container(1);
            /* Charement de la bibliothèque footable, pour affichage sur mobile */
            $this->layout->ajouter_js('footable.min');
            $this->layout->ajouter_css('footable.bootstrap.min');

            //Affichage
            $this->layout->view('utilisateur/contacts', $data);
        }
    }

    /* Affichage des informations pour l'utilisateur : générales + pour sa congrégation */

    public function infos() {
        /* Connexion requise */
        if ($this->require_min_level(1)) {
            $congId = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
            $data['infos'] = $this->M_utilisateurs->getInfosCong($congId);
            
            /*Infos urgentes ?*/
            $congId = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
            $data['urgent']=0;
            foreach($this->M_utilisateurs->getInfosCong($congId) as $info){
                if($info->urgent && $info->actif=='on')
                    $data['urgent']=1;
            }
            
            $data['congName'] = $this->M_utilisateurs->getCongName($this->auth_user_id)->nom;
            $this->layout->set_container(1);
            $this->layout->view('utilisateur/infos', $data);
        }
    }
    
    /* Affichage des informations pour l'utilisateur : générales + pour sa congrégation */

    public function infosRP() {
        /* Connexion requise */
        if ($this->require_min_level(6)) {
            $congId = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
            $data['maj']=false;
             /* Si le formulaire est soumis (des données sont postée */
            if ($this->input->post()) {
                $info = new stdClass;
                $info->idCong = $congId;
                $info->contenu= $this->input->post()['info'];
                $info->urgent = 0;
                $info->actif  = 'on';
                $this->M_utilisateurs->majInfo($info);
                $info = new stdClass;
                $info->idCong = $congId;
                $info->contenu= $this->input->post()['infoUrg'];
                $info->urgent = 1;
                $info->actif  = $this->input->post()['actifUrg'];
                $this->M_utilisateurs->majInfo($info);
                $data['maj']=true;
            }
            $infos = $this->M_utilisateurs->getInfosCong($congId);
            
            $data['congName'] = $this->M_utilisateurs->getCongName($this->auth_user_id)->nom;
            $infoNorm = '';
            $infoUrg  = '';
            $actif='';
            foreach($infos as $info){
                if($info->idCong==$congId && $info->urgent==0)
                    $infoNorm = $info->contenu;
                if($info->idCong==$congId && $info->urgent==1){
                    $infoUrg  = $info->contenu;
                    $actif    = $info->actif;
                }
            }
            $data['info'] = $infoNorm;
            $data['infoUrg'] = $infoUrg;
            $data['actifUrg'] = $actif;

            
            /*Infos urgentes ?*/
            $congId = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
            $data['urgent']=0;
            foreach($this->M_utilisateurs->getInfosCong($congId) as $info){
                if($info->urgent && $info->actif=='on')
                    $data['urgent']=1;
            }
            
            $this->layout->set_RP();
            $this->layout->set_container(1);
            $this->layout->ajouter_css('toggle');
            $this->layout->view('utilisateur/infosRP', $data);
        }
    }

    public function infosAdmin() {
        /* Connexion requise */
        if ($this->require_min_level(9)) {
            $data['maj']=false;
             /* Si le formulaire est soumis (des données sont postée */
            if ($this->input->post()) {
                $info = new stdClass;
                $info->idCong = -1;
                $info->contenu= $this->input->post()['info'];
                $info->urgent = 0;
                $info->actif  = 'on';
                $this->M_utilisateurs->majInfo($info);
                $info = new stdClass;
                $info->idCong = -1;
                $info->contenu= $this->input->post()['infoUrg'];
                $info->urgent = 1;
                $info->actif  = $this->input->post()['actifUrg'];
                $this->M_utilisateurs->majInfo($info);
                $data['maj']=true;
            }
            $infos = $this->M_utilisateurs->getInfosCong(-1);
            
            $infoNorm = '';
            $infoUrg  = '';
            foreach($infos as $info){
                if($info->idCong==-1 && $info->urgent==0)
                    $infoNorm = $info->contenu;
                if($info->idCong==-1 && $info->urgent==1){
                    $infoUrg  = $info->contenu;
                    $actif    = $info->actif;
                }
            }
            $data['info'] = $infoNorm;
            $data['infoUrg'] = $infoUrg;
            $data['actifUrg'] = $actif;

            $this->layout->set_Admin();
            $this->layout->set_container(1);
            $this->layout->ajouter_css('toggle');
            $this->layout->view('utilisateur/infosRP', $data);
        }
    }

    /* Modification des données utilisateur */

    public function options() {
        /* Connexion requise */
        if ($this->require_min_level(1)) {
            /* Chargement de la bibliothèque pour gestion du formulaire */
            $this->load->library('form_validation');
            /* Règles de validations du formulaire */
            $this->form_validation->set_rules([
                [
                    'field' => 'pass1',
                    'label' => 'Mot de passe',
                    'rules' => [
                        'trim',
                        'matches[pass2]',
                        [
                            '_check_password_strength',
                            [$this, '_verifMdp'] //Callback voir fonction _verifMdp
                        ]
                    ]
                ],
                [
                    'field' => 'pass2',
                    'label' => 'Confirmation',
                    'rules' => 'trim'
                ],
                [
                    'field' => 'user_identification',
                    'rules' => 'required'
                ],
                [
                    'field' => 'nom',
                    'rules' => 'trim|required'
                ],
                [
                    'field' => 'prenom',
                    'rules' => 'trim|required'
                ],
                [
                    'field' => 'phone',
                    'label' => 'Téléphone',
                    'rules' => 'required|trim|numeric|min_length[10]|max_length[10]'
                ],
                [
                    'field' => 'mail',
                    'rules' => 'required|trim|valid_email'
                ]
            ]);

            /* Chargement de l'ID de l'utilisateur */
            $data['id'] = $this->auth_user_id;

            /* Si le formulaire est soumis (des données sont postée) */
            if ($this->input->post()) {
                // Exécution et test de la validité
                if ($this->form_validation->run() !== FALSE) {
                    //Chargement de l'info : validation réussie
                    $data['validation_passed'] = 1;
                    //Si le mot de passe est modifié (optionnel pour soumission du form)
                    if (isset($this->input->post()['pass1']) && $this->input->post()['pass1'] != '') {
                        /* Le modèle met à jour le mdp */
                        $this->M_utilisateurs->_changer_mdp_self(
                                set_value('pass1'), set_value('user_identification')
                        );
                    }
                    /* Dans tous les cas, création du nouvel objet utilisateur (anciennes données préchargées dans le formulaire) */
                    $user = new stdClass;
                    $user->nom = set_value('nom');
                    $user->prenom = set_value('prenom');
                    $user->telephone = set_value('phone');
                    $user->mail = set_value('mail');
                    /* Envoi de l'objet au modèle pour enregistrement */
                    $this->M_utilisateurs->majUtilisateur($this->auth_user_id, $user);
                } else {
                    //Quelque chose n'allait pas, il faut charger les erreurs
                    $data['validation_errors'] = validation_errors();
                }
            }

            /* Récupération de l'objet utilisateur en cours pour affichage (pré rempli le formulaire) */
            $data['user'] = $this->M_utilisateurs->get($this->auth_user_id);
            
            /*Infos urgentes ?*/
            $congId = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
            $data['urgent']=0;
            foreach($this->M_utilisateurs->getInfosCong($congId) as $info){
                if($info->urgent && $info->actif=='on')
                    $data['urgent']=1;
            }
            
            $this->layout->set_container(1);
            $this->layout->view('utilisateur/options', $data);
        }
    }

    /**
     * Vérifie la force du mdp : longueur, variété et nombre de caractères
     * @param string $password
     * @return boolean
     */
    public function _verifMdp($password) {
        //Si mdp vide i.e. pas de souhait de modification
        if ($password == '')
            return true;

        /* Chargement des paramètres pour la sécurité du mdp */
        $this->config->load('password_strength');
        // Si un max est défini, le charger
        $max = config_item('max_chars_for_password') > 0 ? config_item('max_chars_for_password') : '';
        /* Préparation de la regex : nb caractères entre mini et maxi */
        $regex = '(?=.{' . config_item('min_chars_for_password') . ',' . $max . '})';
        /* Préparation du message associé */
        $error = '<li>Au moins ' . config_item('min_chars_for_password') . ' caractères</li>';

        /* Si max de caractères définie */
        if (config_item('max_chars_for_password') > 0)
            $error .= '<li>Pas plus de ' . config_item('max_chars_for_password') . ' caractères</li>'; //Msg adapté




            
// Nombres de chiffres requis
        if (config_item('min_digits_for_password') > 0) {
            /* Regex adaptée */
            $regex .= '(?=(?:.*[0-9].*){' . config_item('min_digits_for_password') . ',})';
            $plural = config_item('min_digits_for_password') > 1 ? 's' : '';
            /* msg aussi */
            $error .= '<li>' . config_item('min_digits_for_password') . ' chiffre' . $plural . '</li>';
        }

        // Nombre de minuscules requises
        if (config_item('min_lowercase_chars_for_password') > 0) {
            $regex .= '(?=(?:.*[a-z].*){' . config_item('min_lowercase_chars_for_password') . ',})';
            $plural = config_item('min_lowercase_chars_for_password') > 1 ? 's' : '';
            $error .= '<li>' . config_item('min_lowercase_chars_for_password') . ' minuscule' . $plural . '</li>';
        }

        // Nombre de majuscule(s) requise(s)
        if (config_item('min_uppercase_chars_for_password') > 0) {
            $regex .= '(?=(?:.*[A-Z].*){' . config_item('min_uppercase_chars_for_password') . ',})';
            $plural = config_item('min_uppercase_chars_for_password') > 1 ? 's' : '';
            $error .= '<li>' . config_item('min_uppercase_chars_for_password') . ' majuscule' . $plural . '</li>';
        }

        // Nombre de symboles requis
        if (config_item('min_non_alphanumeric_chars_for_password') > 0) {
            $regex .= '(?=(?:.*[^a-zA-Z0-9].*){' . config_item('min_non_alphanumeric_chars_for_password') . ',})';
            $plural = config_item('min_non_alphanumeric_chars_for_password') > 1 ? 's' : '';
            $error .= '<li>' . config_item('min_non_alphanumeric_chars_for_password') . ' symbole' . $plural . '</li>';
        }

        /* Execution de la regex */
        if (preg_match('/^' . $regex . '.*$/', $password)) {
            return TRUE;
        }

        /* Si ne passe pas, defini le message et retourne faux */
        $this->form_validation->set_message(
                '_check_password_strength', '<span class="redfield">Le mot de passe</span> doit être composé de :
				<ul>
					' . $error . '
				</ul>
			</span>'
        );

        return FALSE;
    }

    public function listeRP() {
        /* Connexion requise */
        if ($this->require_min_level(6)) {
            //Récupération idCong
		if(isset($_GET['idCong'])){
			$congId = $_GET['idCong'];
		} else {
	            	$congId = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
		}
            //Récupération des proclamateurs de cette cong
            $data['procls'] = $this->M_utilisateurs->getProclsFromCong($congId);
            $data['messageListeRP'] = $this->session->flashdata('messageListeRP');
            $this->session->set_flashdata('messageListeRP', "");
            $this->layout->set_container(1);
            $this->layout->set_RP();
            $this->layout->ajouter_js('bootstrap-confirmation');
            $this->layout->view('utilisateur/listeRP', $data);
        }
    }
    
    public function gestionAssemblee(){
        /* Connexion requise + Admin */
        if ($this->require_min_level(9)) {
            if($this->input->get())
            {
                $type=$this->input->get()['type'];
                if($type=='suppr'){
                    $id  =$this->input->get()['id'];
                    $this->M_utilisateurs->deleteCong($id);
                }
                else if($type=='maj'){
                    $id  =$this->input->get()['id'];
                    $cong = new stdClass;
                    $cong->nom = $this->input->get()['nom'];
                    $this->M_utilisateurs->majCong($id,$cong);
                }
                else if($type=='new'){
                    $cong = new stdClass;
                    $cong->nom = $this->input->get()['nom'];
                    $this->M_utilisateurs->addCong($cong);
                }
            }
            $data['assemblees'] = $this->M_utilisateurs->getAllCongs();
            $this->layout->set_container(1);
            $this->layout->set_Admin();
            $this->layout->ajouter_js('bootstrap-confirmation');
            $this->layout->view('utilisateur/gestAssemblees', $data);
        }
    }
    
    public function listeAdmin() {
        /* Connexion requise */
        if ($this->require_min_level(9)) {
            //Récupération des proclamateurs de cette cong
            $data['procls'] = $this->M_utilisateurs->getAllRP();
            $data['messageListeRP'] =   $this->session->flashdata('messageListeRP');
            $this->session->set_flashdata('messageListeRP', "");
            $this->layout->set_container(1);
            $this->layout->set_Admin();
            $this->layout->ajouter_js('bootstrap-confirmation');
            $this->layout->view('utilisateur/listeAdmin', $data);
        }
    }

    public function modifProcl($id) {
        /* Connexion requise */
        if ($this->require_min_level(6)) {
            //Récupération cong du RP actuel
            $congId = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
            //Récupération cong du procl a modif
            $userCong = $this->M_utilisateurs->getCongId($id)->id;
            //Si congrégation différente, modif interdite
            if ($congId != $userCong && ! isset($_GET['idCong'])) {
                show_404();
            } else {
                /* Chargement de la bibliothèque pour gestion du formulaire */
                $this->load->library('form_validation');
                /* Règles de validations du formulaire */
                $this->form_validation->set_rules([
                    [
                        'field' => 'user_identification',
                        'rules' => 'required'
                    ],
                    [
                        'field' => 'nom',
                        'rules' => 'trim|required'
                    ],
                    [
                        'field' => 'prenom',
                        'rules' => 'trim|required'
                    ],
                    [
                        'field' => 'phone',
                        'label' => 'Téléphone',
                        'rules' => 'required|trim|numeric|min_length[10]|max_length[10]'
                    ],
                    [
                        'field' => 'mail',
                        'rules' => 'required|trim|valid_email'
                    ]
                ]);
                /* Si le formulaire est soumis (des données sont postée */
                if ($this->input->post()) {
                    // Exécution et test de la validité
                    if ($this->form_validation->run() !== FALSE) {
                        //Chargement de l'info : validation réussie
                        $data['validation_passed'] = 1;
                        /* Création du nouvel objet utilisateur (anciennes données préchargées dans le formulaire) */
                        $user = new stdClass;
                        $user->nom = set_value('nom');
                        $user->prenom = set_value('prenom');
                        $user->telephone = set_value('phone');
                        $user->mail = set_value('mail');
                        $user->idCong = set_value('idCong');
                        if (isset($this->input->post()['rout'])) {
                            $user->routier = 1;
                        } else {
                            $user->routier = 0;
                        }
                        /* Envoi de l'objet au modèle pour enregistrement */
                        $this->M_utilisateurs->majUtilisateur($this->M_utilisateurs->get($id)->id, $user);
                    } else {
                        //Quelque chose n'allait pas, il faut charger les erreurs
                        $data['validation_errors'] = validation_errors();
                    }
                }
                $data['procl'] = $this->M_utilisateurs->get($id);
                $data['congs'] = $this->M_congregations->getAllCong();
                $data['userCong']=$userCong;
                $this->layout->set_container(1);
                $this->layout->set_RP();
                $this->layout->view('utilisateur/modifProcl', $data);
            }
        }
    }
    
    public function modifAdmin($id) {
        /* Connexion requise */
        if ($this->require_min_level(9)) {
            //Récupération cong du RP actuel
            $congId = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
            //Récupération cong du procl a modif
            $userCong = $this->M_utilisateurs->getCongId($id)->id;
            //Si congrégation différente, modif interdite
            /* Chargement de la bibliothèque pour gestion du formulaire */
            $this->load->library('form_validation');
            /* Règles de validations du formulaire */
            $this->form_validation->set_rules([
                [
                    'field' => 'user_identification',
                    'rules' => 'required'
                ],
                [
                    'field' => 'nom',
                    'rules' => 'trim|required'
                ],
                [
                    'field' => 'prenom',
                    'rules' => 'trim|required'
                ],
                [
                    'field' => 'phone',
                    'label' => 'Téléphone',
                    'rules' => 'required|trim|numeric|min_length[10]|max_length[10]'
                ],
                [
                    'field' => 'mail',
                    'rules' => 'required|trim|valid_email'
                ]
            ]);
            /* Si le formulaire est soumis (des données sont postée */
            if ($this->input->post()) {
                // Exécution et test de la validité
                if ($this->form_validation->run() !== FALSE) {
                    //Chargement de l'info : validation réussie
                    $data['validation_passed'] = 1;
                    /* Création du nouvel objet utilisateur (anciennes données préchargées dans le formulaire) */
                    $user = new stdClass;
                    $user->nom = set_value('nom');
                    $user->prenom = set_value('prenom');
                    $user->telephone = set_value('phone');
                    $user->mail = set_value('mail');
                    $user->idCong = set_value('idCong');

                    /* Envoi de l'objet au modèle pour enregistrement */
                    $this->M_utilisateurs->majUtilisateur($this->M_utilisateurs->get($id)->id, $user);
                } else {
                    //Quelque chose n'allait pas, il faut charger les erreurs
                    $data['validation_errors'] = validation_errors();
                }
            }
            $data['procl'] = $this->M_utilisateurs->get($id);
            $data['congs'] = $this->M_congregations->getAllCong();
            $this->layout->set_container(1);
            $this->layout->set_RP();
            $this->layout->view('utilisateur/modifRP', $data);
            
        }
    }

    public function supprProcl($id) {
        /* Connexion requise + RP */
        if ($this->require_min_level(6)) {
            /* Enregistrement du message en session pour affichage après redirection */
            $this->session->set_flashdata('messageListeRP', "Proclamateur supprimé");
            /* Suppression en base par le modèle */
            $this->M_utilisateurs->deleteUtil($id);
            /* Redirection a la liste */
            redirect('proclamateurs');
        }
    }

    public function ajoutProcl() {
        /* Connexion requise + RP */
        if ($this->require_min_level(6)) {
            /* Chargement de la bibliothèque pour gestion du formulaire */
            $this->load->library('form_validation');
            /* Règles de validations du formulaire */
            $this->form_validation->set_rules([
                [
                    'field' => 'mail',
                    'rules' => 'required|trim|valid_email'
                ]
            ]);

            $data = array();

            /* Si le formulaire est soumis (des données sont postée */
            if ($this->input->post()) {
                // Exécution et test de la validité
                if ($this->form_validation->run() !== FALSE) {
                    //Chargement de l'info : validation réussie
                    $mail = set_value('mail');
                    $codeLink = substr($this->authentication->random_salt()
                            . $this->authentication->random_salt()
                            . $this->authentication->random_salt()
                            . $this->authentication->random_salt(), 0, 36);

                    $code = substr($this->authentication->random_salt(), 0, 6);

                    $invit = new stdClass();
                    $invit->date_limite = date('Y-m-d', strtotime(date('Y-m-d') . ' + 7 days'));
                    $invit->mail = set_value('mail');
                    $invit->code = $code;
                    $invit->link = $codeLink;
                    $invit->idCong = $this->M_utilisateurs->getCongId($this->auth_user_id)->id;
                    $id = $this->M_utilisateurs->addInvit($invit);

                    $link = site_url('inscription') . "/$id/$codeLink";

                    if (set_value('prenom') === null)
                        $this->sendInvitLink($mail, $code, $link);
                    else
                        $this->sendInvitLink($mail, $code, $link, set_value('prenom'));
                    $data['validation_passed'] = 1;
                } else {
                    //Quelque chose n'allait pas, il faut charger les erreurs
                    $data['validation_errors'] = validation_errors();
                }
            }

            $data['dest'] = 'Proclamateur';
            $this->layout->set_container(1);
            $this->layout->set_RP();
            $this->layout->view('utilisateur/ajouterProcl', $data);
        }
    }
    /*
    public function BDajoutProcl() {
        $mail='manoah.verdier@gmail.com';
        
        
            $this->load->library('form_validation');
        
            $this->form_validation->set_rules([
                [
                    'field' => 'mail',
                    'rules' => 'required|trim|valid_email'
                ]
            ]);

            $data = array();

        
            
                // Exécution et test de la validité


                    $codeLink = substr($this->authentication->random_salt()
                            . $this->authentication->random_salt()
                            . $this->authentication->random_salt()
                            . $this->authentication->random_salt(), 0, 36);

                    $code = substr($this->authentication->random_salt(), 0, 6);

                    $invit = new stdClass();
                    $invit->date_limite = date('Y-m-d', strtotime(date('Y-m-d') . ' + 7 days'));
                    $invit->mail = $mail;
                    $invit->code = $code;
                    $invit->link = $codeLink;
                    $invit->idCong = 1;
                    $id = $this->M_utilisateurs->addInvit($invit);

                    $link = site_url('inscription') . "/$id/$codeLink";

                    if (set_value('prenom') === null)
                        $this->sendInvitLink($mail, $code, $link);
                    else
                        $this->sendInvitLink($mail, $code, $link, set_value('prenom'));
                    $data['validation_passed'] = 1;

            

            $data['dest'] = 'Proclamateur';
            $this->layout->set_container(1);
            $this->layout->set_RP();
            $this->layout->view('utilisateur/ajouterProcl', $data);
        
    }*/
    
    public function ajoutRP() {
        /* Connexion requise + Admin */
        if ($this->require_min_level(9)) {
            /* Chargement de la bibliothèque pour gestion du formulaire */
            $this->load->library('form_validation');
            /* Règles de validations du formulaire */
            $this->form_validation->set_rules([
                [
                    'field' => 'mail',
                    'rules' => 'required|trim|valid_email'
                ]
            ]);

            $data = array();

            /* Si le formulaire est soumis (des données sont postée */
            if ($this->input->post()) {
                // Exécution et test de la validité
                if ($this->form_validation->run() !== FALSE) {
                    //Chargement de l'info : validation réussie
                    $mail = set_value('mail');
                    $codeLink = substr($this->authentication->random_salt()
                            . $this->authentication->random_salt()
                            . $this->authentication->random_salt()
                            . $this->authentication->random_salt(), 0, 36);

                    $code = substr($this->authentication->random_salt(), 0, 6);

                    $invit = new stdClass();
                    $invit->date_limite = date('Y-m-d', strtotime(date('Y-m-d') . ' + 7 days'));
                    $invit->mail = set_value('mail');
                    $invit->code = $code;
                    $invit->link = $codeLink;
                    $invit->idCong = set_value('idCong');
                    $id = $this->M_utilisateurs->addInvit($invit);

                    $link = site_url('inscriptionRP') . "/$id/$codeLink";

                    if (set_value('prenom') === null)
                        $this->sendInvitLinkRP($mail, $code, $link);
                    else
                        $this->sendInvitLinkRP($mail, $code, $link, set_value('prenom'));
                    $data['validation_passed'] = 1;
                } else {
                    //Quelque chose n'allait pas, il faut charger les erreurs
                    $data['validation_errors'] = validation_errors();
                }
            }
            $data['dest'] ='RP';
            $data['congs']=$this->M_congregations->getAllCong();
            $this->layout->set_container(1);
            $this->layout->set_RP();
            $this->layout->view('utilisateur/ajouterProcl', $data);
        }
    }

    public function changeRP($idCong,$oldRP){
        /* Connexion requise + Admin */
        if ($this->require_min_level(9)) {
            /* Chargement de la bibliothèque pour gestion du formulaire */
            $this->load->library('form_validation');
            /* Règles de validations du formulaire */
            $this->form_validation->set_rules([
                [
                    'field' => 'nouvRP',
                    'rules' => 'required'
                ]
            ]);

            $data = array();

            /* Si le formulaire est soumis (des données sont postée */
            if ($this->input->post()) {
                // Exécution et test de la validité
                if ($this->form_validation->run() !== FALSE) {
                    //Chargement de l'info : validation réussie
                    $this->M_congregations->setRP($idCong,set_value('nouvRP'));
                    $user=new stdClass;
                    $user->id=$oldRP;
                    $user->role=1;
                    $this->M_utilisateurs->majUtilisateur($oldRP,$user);
                    $user=new stdClass;
                    $user->user_id=$oldRP;
                    $user->auth_level=1;
                    $this->M_utilisateurs->update_user($oldRP,$user);
                    $user=new stdClass;
                    $user->id=set_value('nouvRP');
                    $user->role=6;
                    $this->M_utilisateurs->majUtilisateur(set_value('nouvRP'),$user);
                    $user=new stdClass;
                    $user->user_id=set_value('nouvRP');
                    $user->auth_level=6;
                    $this->M_utilisateurs->update_user(set_value('nouvRP'),$user);
                    $this->session->set_flashdata('messageListeRP', "Responsable prédication mis à jour");
                    $oldUser = $this->M_utilisateurs->get($oldRP);
                    $newUser = $this->M_utilisateurs->get(set_value('nouvRP'));
                    $this->sendInfoLink($oldUser->mail,$oldUser->nom,'Ton compte est désormais un compte proclamateur');
                    $this->sendInfoLink($newUser->mail,$newUser->nom,'Ton compte est désormais un compte responsable predication');
                    redirect(site_url('rp'));
                } else {
                    //Quelque chose n'allait pas, il faut charger les erreurs
                    $data['validation_errors'] = validation_errors();
                }
            }
            $data['old'] = $oldRP;
            $data['procls']=$this->M_utilisateurs->getProclsFromCong($idCong);
            $data['nom']=$this->M_congregations->get($idCong)->nom;
            $this->layout->set_container(1);
            $this->layout->set_RP();
            $this->layout->view('utilisateur/changeRP', $data);
        }
    }
    
    public function ctcRP($idUtil){
       if ($this->require_min_level(9)) {
           /* Si le formulaire est soumis (des données sont postée */
            $data['messageRP'] =   $this->session->flashdata('messageListeRP');
            if ($this->input->post()) {
                $objet = $this->input->post()['objet'];
                $corps = nl2br($this->input->post()['corps']);
                if($corps==''){
                    $data['messageRPError'] = "Erreur : le message est vide";
                }
                else{
                    if($idUtil=-1){
                        $users = $this->M_utilisateurs->getAllRP();
                        foreach($users as $u){
                            $this->sendMailRP($u->mail, $corps, $objet,$u->prenom.' '.$u->nom);
                        }
                    }
                    else
                    {
                        $user = $this->M_utilisateurs->get($idUtil);
                        $this->sendMailRP($user->mail, $corps, $objet,$user->prenom.' '.$user->nom);
                    }

                    $data['messageRP'] = "Message envoyé";
                }
            }
            $this->session->set_flashdata('messageRP', "");
            $this->layout->set_container(1);
            $this->layout->set_Admin();
            $this->layout->view('utilisateur/ctcRP', $data);
        } 
    }
    
    /**
     * Envoi d'un mail personnalisé à un RP
     * @param string $mail
     * @param string $code
     * @param string $link
     * @param string $nom
     */
    private function sendMailRP($mail, $corps, $sujet, $nom = '') {
        //Chargement de la configuration par défaut des mails
        $this->config->load('mailing');
        //Chargement de la bibliothèque d'envoi de mail
        $this->load->library('email');

        //Type de mail
        $config['mailtype'] = 'html';

        //Headers et footers html
        $headerHTML = "<html>"
                . "         <head>"
                . "             <head><style>" . $this->config->item('default_css') . "</style></head>"
                . "         </head>" . $this->config->item('default_head');
        $footerHTML = $this->config->item('default_footer');
        ;

        $this->email->initialize($config);

        $this->email->from('support@verdier-developpement.com', 'Routiers');
        $this->email->to($mail);

        $this->email->subject("$sujet");
        $this->email->message($headerHTML .
                '<table border="0" style="width:100%">
                  <tr>
                    <td style="width:50%"><h1>Témoignage aux routiers</h1></td>
                  <tr>    
                 </table>        
                 <table border="0" cellpadding="0" cellspacing="0">' .
                "<tr><td>Bonjour $nom,</td></tr>
                     <tr><td>&nbsp;</td></tr>
                     <tr><td>Tu as reçu un message de la part de l'équipe routiers : </td></tr>
                     <tr><td>&nbsp;</td></tr>
                 </table>
                 <table border='0' cellpadding='0' cellspacing='0'>
                     
                     " . '<table width="180" border="0" cellpadding="0" cellspacing="0" class="">
                          <tbody>
                            <tr>
                              <td align="left">
                                <table border="0" cellpadding="0" cellspacing="0">
                                  <tbody>
                                    <tr>
                                      <td style="font-size: 14px;padding: 10px;background: #E6EAED;text-align: left;border-radius: 5px;border: 1px solid #D7DCE0;line-height:30px;"> <span class="appleLinks">
                                        ' . $corps . "
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>

                     </td></tr>
                     <tr><td><a href='www.verdier-developpement.fr/dev/routiers' class='btn btn-primary'>Aller sur le site</a></td></tr>
                     <tr><td>&nbsp;</td></tr>
                     <tr><td>Bonne journée !</td></tr>
                     <tr><td>L'équipe des routiers</td></tr>
                     </tbody>
                     </table>
                     </td>
                     </tr>
                  </table>" .
                $footerHTML);

        $this->email->send();
    }
    
    /**
     * Envoi d'un mail avec lien d'inscription et code
     * @param string $mail
     * @param string $code
     * @param string $link
     * @param string $nom
     */
    private function sendInvitLink($mail, $code, $link, $nom = '') {
        //Chargement de la configuration par défaut des mails
        $this->config->load('mailing');
        //Chargement de la bibliothèque d'envoi de mail
        $this->load->library('email');

        //Type de mail
        $config['mailtype'] = 'html';

        //Headers et footers html
        $headerHTML = "<html>"
                . "         <head>"
                . "             <head><style>" . $this->config->item('default_css') . "</style></head>"
                . "         </head>" . $this->config->item('default_head');
        $footerHTML = $this->config->item('default_footer');
        ;

        $this->email->initialize($config);

        $this->email->from('support@temoignage-normandie.site', 'Routiers');
        $this->email->to($mail);

        $this->email->subject('Invitation à participer au témoignage aux routiers');
        $this->email->message($headerHTML .
                '<table border="0" style="width:100%">
                  <tr>
                    <td style="width:50%"><h1>Témoignage aux routiers</h1></td>
                  <tr>    
                 </table>        
                 <table border="0" cellpadding="0" cellspacing="0">' .
                "<tr><td>Bonjour $nom,</td></tr>
                     <tr><td>&nbsp;</td></tr>
                     <tr><td>Tu as été invité par ton responsable prédication à participer au témoignage aux routiers. Pour cela, merci de t'inscrire en cliquant sur le lien suivant : </td></tr>
                     <tr><td>&nbsp;</td></tr>
                 </table>
                 <table width=180 border='0' cellpadding='0' cellspacing='0' class=''>
                     <tr><td><table class='btn btn-primary btn-block text-center'><tbody><tr><td width='100%'><a href='$link' class='btn btn-primary'><span style='width:100%;text-align:center;'>Inscription</a></span></td></tr></tbody></table></td></tr>
                 </table>
                 <table border='0' cellpadding='0' cellspacing='0'>
                     <tr><td>Note qu' un code te sera demandé. Le voici : </td></tr>
                     <tr><td>&nbsp;</td></tr>
                     
                     " . '<table width="180" border="0" cellpadding="0" cellspacing="0" class="">
                          <tbody>
                            <tr>
                              <td align="left">
                                <table border="0" cellpadding="0" cellspacing="0">
                                  <tbody>
                                    <tr>
                                      <td style="font-size: 30px;font-weight: bold;padding: 10px;background: #E6EAED;text-align: center;border-radius: 5px;border: 1px solid #D7DCE0;line-height:30px;"> <span class="appleLinks">
                                        ' . $code . "
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>

                     </td></tr>
                     <tr><td>&nbsp;</td></tr>
                     <tr><td>Note que ce lien n'est valable qu'une semaine. Nous comptons sur ta réactivité !</td></tr>
                     <tr><td>&nbsp;</td></tr>
                     <tr><td>Bonne journée !</td></tr>
                     <tr><td>L'équipe des routiers</td></tr>
                     </tbody>
                     </table>
                     </td>
                     </tr>
                  </table>" .
                $footerHTML);

        $this->email->send();
    }
    
    /**
     * Envoi d'un mail avec lien d'inscription et code
     * @param string $mail
     * @param string $code
     * @param string $link
     * @param string $nom
     */
    private function sendInfoLink($mail, $nom="", $msg) {
        //Chargement de la configuration par défaut des mails
        //$this->config->load('mailing');
        //Chargement de la bibliothèque d'envoi de mail
        $this->load->library('email');

        //Type de mail
        $config['mailtype'] = 'html';
	$config['protocol'] = "smtp";
	$config['smtp_host']="51.254.99.139";
	$config['smtp_user']="tom";
	$config['smtp_pass']="jerry";
	$config['smtp_port']="12301";

        //Headers et footers html
        $headerHTML = "<html>"
                . "         <head>"
                . "             <head><style>" . $this->config->item('default_css') . "</style></head>"
                . "         </head>" . $this->config->item('default_head');
        $footerHTML = $this->config->item('default_footer');
        ;

        $this->email->initialize($config);

        $this->email->from('support@verdier-developpement.com', 'Routiers');
        $this->email->to($mail);

        $this->email->subject('Changement sur ton compte');
        $this->email->message($headerHTML .
                '<table border="0" style="width:100%">
                  <tr>
                    <td style="width:50%"><h1>Témoignage aux routiers</h1></td>
                  <tr>    
                 </table>        
                 <table border="0" cellpadding="0" cellspacing="0">' .
                "<tr><td>Bonjour $nom,</td></tr>
                     <tr><td>&nbsp;</td></tr>
                     <tr><td>Les changements suivants ont été effectués sur ton compte : </td></tr>
                     <tr><td>&nbsp;</td></tr>
                 </table>
                 <table border='0' cellpadding='0' cellspacing='0'>
                     <tr><td>&nbsp;</td></tr>
                     
                     " . '<table width="180" border="0" cellpadding="0" cellspacing="0" class="">
                          <tbody>
                            <tr>
                              <td align="left">
                                <table border="0" cellpadding="0" cellspacing="0">
                                  <tbody>
                                    <tr>
                                      <td style="font-size: 22px;font-weight: bold;padding: 10px;background: #E6EAED;text-align: center;border-radius: 5px;border: 1px solid #D7DCE0;line-height:30px;"> <span class="appleLinks">
                                        ' . $msg . "
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>

                     </td></tr>
                     <tr><td>&nbsp;</td></tr>
                     <tr><td>Note que tu peux toujours te connecter de la manière habituelle.</td></tr>
                     <tr><td>&nbsp;</td></tr>
                     <tr><td>Bonne journée !</td></tr>
                     <tr><td>L'équipe des routiers</td></tr>
                     </tbody>
                     </table>
                     </td>
                     </tr>
                  </table>" .
                $footerHTML);

        $this->email->send();
    }

    /**
     * Envoi d'un mail avec lien d'inscription et code
     * @param string $mail
     * @param string $code
     * @param string $link
     * @param string $nom
     */
    private function sendInvitLinkRP($mail, $code, $link, $nom = '') {
        //Chargement de la configuration par défaut des mails
        $this->config->load('mailing');
        //Chargement de la bibliothèque d'envoi de mail
        $this->load->library('email');

        //Type de mail
        $config['mailtype'] = 'html';

        //Headers et footers html
        $headerHTML = "<html>"
                . "         <head>"
                . "             <head><style>" . $this->config->item('default_css') . "</style></head>"
                . "         </head>" . $this->config->item('default_head');
        $footerHTML = $this->config->item('default_footer');
        ;

        $this->email->initialize($config);

        $this->email->from('support@verdier-developpement.com', 'Routiers');
        $this->email->to($mail);

        $this->email->subject('Invitation à t\'inscrire pour le témoignage aux routiers');
        $this->email->message($headerHTML .
                '<table border="0" style="width:100%">
                  <tr>
                    <td style="width:50%"><h1>Témoignage aux routiers</h1></td>
                  <tr>    
                 </table>        
                 <table border="0" cellpadding="0" cellspacing="0">' .
                "<tr><td>Bonjour $nom,</td></tr>
                     <tr><td>&nbsp;</td></tr>
                     <tr><td>Tu as été invité à t'enregistrer en tant que Responsable Prédication pour le témoignage aux routiers. Pour cela, merci de t'inscrire en cliquant sur le lien suivant : </td></tr>
                     <tr><td>&nbsp;</td></tr>
                 </table>
                 <table width=180 border='0' cellpadding='0' cellspacing='0' class=''>
                     <tr><td><table class='btn btn-primary btn-block text-center'><tbody><tr><td width='100%'><a href='$link' class='btn btn-primary'><span style='width:100%;text-align:center;'>Inscription</a></span></td></tr></tbody></table></td></tr>
                 </table>
                 <table border='0' cellpadding='0' cellspacing='0'>
                     <tr><td>Note qu' un code te sera demandé. Le voici : </td></tr>
                     <tr><td>&nbsp;</td></tr>
                     
                     " . '<table width="180" border="0" cellpadding="0" cellspacing="0" class="">
                          <tbody>
                            <tr>
                              <td align="left">
                                <table border="0" cellpadding="0" cellspacing="0">
                                  <tbody>
                                    <tr>
                                      <td style="font-size: 30px;font-weight: bold;padding: 10px;background: #E6EAED;text-align: center;border-radius: 5px;border: 1px solid #D7DCE0;line-height:30px;"> <span class="appleLinks">
                                        ' . $code . "
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>

                     </td></tr>
                     <tr><td>&nbsp;</td></tr>
                     <tr><td>Note que ce lien n'est valable qu'une semaine. Nous comptons sur ta réactivité !</td></tr>
                     <tr><td>&nbsp;</td></tr>
                     <tr><td>Bonne journée !</td></tr>
                     <tr><td>L'équipe des routiers</td></tr>
                     </tbody>
                     </table>
                     </td>
                     </tr>
                  </table>" .
                $footerHTML);

        $this->email->send();
    }
    
    public function inscription($id, $codeLink) {
        if ($id != 0 && $id != '' && $codeLink != '') {
            $this->layout->set_container(0);
            $invit = $this->M_utilisateurs->getInvit($id);
            if (!$invit || $invit === null) {
                show_404();
            } else {
                $cong = $this->M_congregations->get($invit->idCong);
                $data['cong'] = $cong;
                $data['invit'] = $invit;
                if ($invit->link != $codeLink) {
                    $data['invalide'] = 1;
                }
                if (strtotime($invit->date_limite) < strtotime(date('Y-m-d'))) {
                    $data['expire'] = 1;
                }

                $this->load->library('form_validation');
                $this->form_validation->set_rules([
                    [
                        'field' => 'nom',
                        'label' => 'Nom',
                        'rules' => 'trim|required'
                    ],
                    [
                        'field' => 'prenom',
                        'label' => 'Prénom',
                        'rules' => 'trim|required'
                    ],
                    [
                        'field' => 'mail',
                        'label' => 'E-mail',
                        'rules' => [
                            'required',
                            'trim',
                            'valid_email',
                            [
                                'is_unique_email',
                                [$this, 'is_unique_email'] //Callback voir fonction is_unique_email
                            ]
                        ]
                    ],
                    [
                        'field' => 'phone',
                        'label' => 'Téléphone',
                        'rules' => 'required|trim|numeric|min_length[10]|max_length[10]'
                    ],
                    [
                        'field' => 'pass1',
                        'label' => 'Mot de passe',
                        'rules' => [
                            'trim',
                            'matches[pass2]',
                            [
                                '_check_password_strength',
                                [$this, '_verifMdp'] //Callback voir fonction _verifMdp
                            ],
                            'required'
                        ]
                    ],
                    [
                        'field' => 'pass2',
                        'label' => 'Confirmation',
                        'rules' => 'trim|required'
                    ],
                    [
                        'field' => 'code',
                        'label' => 'Code',
                        'rules' => 'required|trim|min_length[6]|max_length[6]'
                    ]
                ]);

            $this->form_validation->set_message('is_unique_email','Un utilisateur utilisant cette adresse existe déjà.');
            
            /* Si le formulaire est soumis (des données sont postée */
            if ($this->input->post()) {
                // Exécution et test de la validité
                if ($this->form_validation->run() !== FALSE) {
                    //Chargement de l'info : validation réussie
                    if($this->input->post()['code'] != $invit->code){
                        $data['mauvaisCode']=1;
                    }
                    else{
                        $data['validation_passed'] = 1;
                        $this->load->helper('auth');
                        $user = new stdClass;
                        $user->passwd=$this->authentication->hash_passwd(set_value('pass1'));
                        $user->email = set_value('mail');
                        $user->auth_level = 1;
                        $user->created_at = date('Y-m-d H:i:s');
                        $user->username = set_value('prenom');
                        $idUser = $this->M_utilisateurs->addUser($user);
                        $util = new stdClass;
                        $util->id = $idUser;
                        $util->nom = set_value('nom');
                        $util->prenom = set_value('prenom');
                        $util->idCong = $invit->idCong;
                        $util->role = 1;
                        $util->routier = 1;
                        $util->crois = 0;
                        $util->temu = 0;
                        $util->telephone = set_value('phone');
                        $util->mail = set_value('mail');
                        $this->M_utilisateurs->addUtil($util);
                        $this->M_utilisateurs->deleteInvit($id);
                        $this->M_utilisateurs->_changer_mdp_self(set_value('pass1'),$idUser);
                    }
                }
                else {
                    //Quelque chose n'allait pas, il faut charger les erreurs
                    $data['validation_errors'] = validation_errors();
                }
            }
                
                $this->layout->set_top(0);
                $this->layout->set_bottom(0);
                $this->layout->view('utilisateur/inscription', $data);
            }
        } else
            show_404();
    }

    public function inscriptionRP($id, $codeLink) {
        if ($id != 0 && $id != '' && $codeLink != '') {
            $this->layout->set_container(0);
            $invit = $this->M_utilisateurs->getInvit($id);
            if (!$invit || $invit === null) {
                show_404();
            } else {
                $cong = $this->M_congregations->get($invit->idCong);
                $data['cong'] = $cong;
                $data['invit'] = $invit;
                if ($invit->link != $codeLink) {
                    $data['invalide'] = 1;
                }
                if (strtotime($invit->date_limite) < strtotime(date('Y-m-d'))) {
                    $data['expire'] = 1;
                }

                $this->load->library('form_validation');
                $this->form_validation->set_rules([
                    [
                        'field' => 'nom',
                        'label' => 'Nom',
                        'rules' => 'trim|required'
                    ],
                    [
                        'field' => 'prenom',
                        'label' => 'Prénom',
                        'rules' => 'trim|required'
                    ],
                    [
                        'field' => 'mail',
                        'label' => 'E-mail',
                        'rules' => [
                            'required',
                            'trim',
                            'valid_email',
                            [
                                'is_unique_email',
                                [$this, 'is_unique_email'] //Callback voir fonction is_unique_email
                            ]
                        ]
                    ],
                    [
                        'field' => 'phone',
                        'label' => 'Téléphone',
                        'rules' => 'required|trim|numeric|min_length[10]|max_length[10]'
                    ],
                    [
                        'field' => 'pass1',
                        'label' => 'Mot de passe',
                        'rules' => [
                            'trim',
                            'matches[pass2]',
                            [
                                '_check_password_strength',
                                [$this, '_verifMdp'] //Callback voir fonction _verifMdp
                            ],
                            'required'
                        ]
                    ],
                    [
                        'field' => 'pass2',
                        'label' => 'Confirmation',
                        'rules' => 'trim|required'
                    ],
                    [
                        'field' => 'code',
                        'label' => 'Code',
                        'rules' => 'required|trim|min_length[6]|max_length[6]'
                    ]
                ]);

            $this->form_validation->set_message('is_unique_email','Un utilisateur utilisant cette adresse existe déjà.');
            
            /* Si le formulaire est soumis (des données sont postée */
            if ($this->input->post()) {
                // Exécution et test de la validité
                if ($this->form_validation->run() !== FALSE) {
                    //Chargement de l'info : validation réussie
                    if($this->input->post()['code'] != $invit->code){
                        $data['mauvaisCode']=1;
                    }
                    else{
                        $data['validation_passed'] = 1;
                        $this->load->helper('auth');
                        $user = new stdClass;
                        $user->passwd=$this->authentication->hash_passwd(set_value('pass1'));
                        $user->email = set_value('mail');
                        $user->auth_level = 6;
                        $user->created_at = date('Y-m-d H:i:s');
                        $user->username = set_value('prenom');
                        $idUser = $this->M_utilisateurs->addUser($user);
                        $util = new stdClass;
                        $util->id = $idUser;
                        $util->nom = set_value('nom');
                        $util->prenom = set_value('prenom');
                        $util->idCong = $invit->idCong;
                        $util->role = 6;
                        $util->routier = 1;
                        $util->crois = 0;
                        $util->temu = 0;
                        $util->telephone = set_value('phone');
                        $util->mail = set_value('mail');
                        $this->M_utilisateurs->addUtil($util);
                        $this->M_utilisateurs->deleteInvit($id);
                        $this->M_utilisateurs->_changer_mdp_self(set_value('pass1'),$idUser);
                        $this->M_congregations->setRP($invit->idCong,$idUser);
                    }
                }
                else {
                    //Quelque chose n'allait pas, il faut charger les erreurs
                    $data['validation_errors'] = validation_errors();
                }
            }
                
                $this->layout->set_top(0);
                $this->layout->set_bottom(0);
                $this->layout->view('utilisateur/inscription', $data);
            }
        } else
            show_404();
    }
    
    public function is_unique_email($mail){
        return $this->M_utilisateurs->is_unique_email($mail);
    }
    
    
}

