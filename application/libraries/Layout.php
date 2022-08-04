<?php

<<<<<<< HEAD
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Layout {

    private $CI;
    private $var = array();
    private $theme = 'default';

    /*
      |===============================================================================
      | Constructeur
      |===============================================================================
     */

    public function __construct() {
        $this->CI = & get_instance();

        $this->var['output'] = '';

        //	Le titre est composé du nom de la méthode et du nom du contrôleur
        //	La fonction ucfirst permet d'ajouter une majuscule
        $this->var['titre'] = ucfirst($this->CI->router->fetch_method()) . ' - ' . ucfirst($this->CI->router->fetch_class());

        //	Nous initialisons la variable $charset avec la même valeur que
        //	la clé de configuration initialisée dans le fichier config.php
        $this->var['charset'] = $this->CI->config->item('charset');

        $this->var['css'] = array();
        $this->var['js'] = array();

        //Booleen si container ou pas
        $this->var['container'] = 0;

        //Booleen si top ou pas
        $this->var['top'] = 1;
        //Contenu du top
        $this->var['topContent'] = '';

        //Booleen si bottom ou pas
        $this->var['bottom'] = 1;
        //Contenu du bottom
        $this->var['bottomContent'] = '';

        //Vue Proclamateur
        $this->var['vueProcl'] = 1;
        //Vue Responsable prédication
        $this->var['vueRP'] = 0;
        //Vue administrateur
        $this->var['vueAdmin'] = 0;
        
        $this->ajouter_css($this->theme);
        $this->ajouter_js($this->theme);
    }

    /*
      |===============================================================================
      | Méthodes pour charger les vues
      |	. view
      |	. views
      |===============================================================================
     */

    public function view($name, $data = array()) {
        //Ajout de la vue demandée
        $this->var['output'] .= $this->CI->load->view($name, $data, true);

        if ($this->var['top'])
            $this->set_top(1);
        if ($this->var['bottom'])
            $this->set_bottom(1);
        //Affichage
        $this->CI->load->view('../themes/' . $this->theme, $this->var);
    }

    public function views($name, $data = array()) {
        $this->var['output'] .= $this->CI->load->view($name, $data, true);
        return $this;
    }

    /* Attribution d'un thème par nom */

    public function set_theme($theme) {
        /* On réinitialise les css et js additionnels */
        $this->var['css'] = array();
        $this->var['js'] = array();

        /* On vérifier : que le nom est une chaine, non vide, et qu'un fichier PHP de ce nom existe dans le répertoire thème. */
        if (is_string($theme) AND ! empty($theme) AND file_exists('./application/themes/' . $theme . '.php')) {
            /* On affecte la variable et on charge le css et js */
            $this->theme = $theme;
            $this->ajouter_css($theme);
            $this->ajouter_js($theme);
            return true;
        }
        return false;
    }

    /* Une div wrap de class container doit-elle être chargée ? */

    public function set_container($container) {
        $this->var['container'] = $container;
    }

    /* Faut-il afficher l'en-tête du thème ? */

    public function set_top($top) {
        /* Si la variable est a faux ou qu'aucun utilisateur n'est défini (les infos utilisateurs sont affichées dans l'en-tête) */
        if (!$top || ($top && config_item('auth_user_id') === NULL)) {
            $this->var['top'] = 0;
            return false;
        }
        //On affecte la variable
        $this->var['top'] = $top;
        //On charge la vue correspondant au top du theme si elle existe
        if (file_exists('./application/views/template/' . $this->theme . '-top.php')) {

            $this->var['topContent'] = $this->CI->load->view('themes/' . $this->theme . '/' . $this->theme . '-top', $this->getDataForTop(), true);
        } else {
            $this->var['topContent'] = $this->CI->load->view('themes/default/default-top', $this->getDataForTop(), true); // Vue par défaut
        }
    }

    /* Faut il afficher le footer du thème ? */

    public function set_bottom($bottom) {

        /* On affecte la variable correspondante */
        if (!$bottom) {
            $this->var['bottom'] = 0;
        }
        $this->var['bottom'] = $bottom;
        $data['vueProcl'] = $this->var['vueProcl'];
        $data['vueRP'] = $this->var['vueRP'];
        $data['vueAdmin'] = $this->var['vueAdmin'];
        //On charge la vue correspondant au bottom du theme si elle existe
        if (file_exists('./application/views/template/' . $this->theme . '-bottom.php')) {
            $this->var['bottomContent'] = $this->CI->load->view('themes/' . $this->theme . '/' . $this->theme . '-bottom', $data, true);
        } else {
            $this->var['bottomContent'] = $this->CI->load->view('themes/default/default-bottom', $data, true); // Par défaut
        }
    }

    /*
      |===============================================================================
      | Méthodes pour ajouter des feuilles de CSS et de JavaScript
      |	. ajouter_css
      |	. ajouter_js
      |===============================================================================
     */

    public function ajouter_css($nom) {
        if (is_string($nom) AND ! empty($nom) AND file_exists('./assets/css/' . $nom . '.css')) {
            $this->var['css'][] = base_url() . 'assets/css/' . $nom.'.css';
            return true;
        }
        return false;
    }

    public function ajouter_js($nom) {
        if (is_string($nom) AND ! empty($nom) AND file_exists('./assets/javascript/' . $nom . '.js')) {
            $this->var['js'][] = base_url() . 'assets/javascript/' . $nom.'.js';
            return true;
        }
        return false;
    }

    /*
      |===============================================================================
      | Méthodes pour modifier les variables envoyées au layout
      |	. set_titre
      |	. set_charset
      |===============================================================================
     */

    public function set_titre($titre) {
        if (is_string($titre) AND ! empty($titre)) {
            $this->var['titre'] = $titre;
            return true;
        }
        return false;
    }

    public function set_charset($charset) {
        if (is_string($charset) AND ! empty($charset)) {
            $this->var['charset'] = $charset;
            return true;
        }
        return false;
    }

    /**
     * 
     * Méthode récupérant les données utilisateur utiles dans l'en-tête
     */
    public function getDataForTop() {
        //On charge le modele
        $this->CI->load->model('M_utilisateurs');

        //On récupère la congrégation
        if(isset($_GET['idCong'])){
			$cong = $this->CI->M_congregations->get($_GET['idCong']);
		} else {
				$cong = $this->CI->M_utilisateurs->getCongName(config_item('auth_user_id'));
		}

        $data['cong'] = $cong->nom;

        //On récupère les infos utilisateur
        $user = $this->CI->M_utilisateurs->get(config_item('auth_user_id'));
        $data['nom'] = $user->nom;
        $data['prenom'] = $user->prenom;
        
        $data['vueProcl'] = $this->var['vueProcl'];
        $data['vueRP'] = $this->var['vueRP'];
        $data['vueAdm'] = $this->var['vueAdmin'];
        
        return $data;
    }

    public function set_procl() {
        $this->var['vueProcl'   ] = 1;
        $this->var['vueRP'      ] = 0;
        $this->var['vueAdmin'   ] = 0;
    }
    
    public function set_RP() {
        $this->var['vueProcl'   ] = 0;
        $this->var['vueRP'      ] = 1;
        $this->var['vueAdmin'   ] = 0;
    }
    
    public function set_Admin() {
        $this->var['vueProcl'   ] = 0;
        $this->var['vueRP'      ] = 0;
        $this->var['vueAdmin'   ] = 1;
    }
}

/* End of file layout.php */
/* Location: ./application/libraries/layout.php */
=======


if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class Layout {



    private $CI;

    private $var = array();

    private $theme = 'default';



    /*

      |===============================================================================

      | Constructeur

      |===============================================================================

     */



    public function __construct() {

        $this->CI = & get_instance();



        $this->var['output'] = '';



        //	Le titre est composé du nom de la méthode et du nom du contrôleur

        //	La fonction ucfirst permet d'ajouter une majuscule

        $this->var['titre'] = ucfirst($this->CI->router->fetch_method()) . ' - ' . ucfirst($this->CI->router->fetch_class());



        //	Nous initialisons la variable $charset avec la même valeur que

        //	la clé de configuration initialisée dans le fichier config.php

        $this->var['charset'] = $this->CI->config->item('charset');



        $this->var['css'] = array();

        $this->var['js'] = array();



        //Booleen si container ou pas

        $this->var['container'] = 0;



        //Booleen si top ou pas

        $this->var['top'] = 1;

        //Contenu du top

        $this->var['topContent'] = '';



        //Booleen si bottom ou pas

        $this->var['bottom'] = 1;

        //Contenu du bottom

        $this->var['bottomContent'] = '';



        //Vue Proclamateur

        $this->var['vueProcl'] = 1;

        //Vue Responsable prédication

        $this->var['vueRP'] = 0;

        //Vue administrateur

        $this->var['vueAdmin'] = 0;

        

        $this->ajouter_css($this->theme);

        $this->ajouter_js($this->theme);

    }



    /*

      |===============================================================================

      | Méthodes pour charger les vues

      |	. view

      |	. views

      |===============================================================================

     */



    public function view($name, $data = array()) {

        //Ajout de la vue demandée

        $this->var['output'] .= $this->CI->load->view($name, $data, true);



        if ($this->var['top'])

            $this->set_top(1);

        if ($this->var['bottom'])

            $this->set_bottom(1);

        //Affichage

        $this->CI->load->view('../themes/' . $this->theme, $this->var);

    }



    public function views($name, $data = array()) {

        $this->var['output'] .= $this->CI->load->view($name, $data, true);

        return $this;

    }



    /* Attribution d'un thème par nom */



    public function set_theme($theme) {

        /* On réinitialise les css et js additionnels */

        $this->var['css'] = array();

        $this->var['js'] = array();



        /* On vérifier : que le nom est une chaine, non vide, et qu'un fichier PHP de ce nom existe dans le répertoire thème. */

        if (is_string($theme) AND ! empty($theme) AND file_exists('./application/themes/' . $theme . '.php')) {

            /* On affecte la variable et on charge le css et js */

            $this->theme = $theme;

            $this->ajouter_css($theme);

            $this->ajouter_js($theme);

            return true;

        }

        return false;

    }



    /* Une div wrap de class container doit-elle être chargée ? */



    public function set_container($container) {

        $this->var['container'] = $container;

    }



    /* Faut-il afficher l'en-tête du thème ? */



    public function set_top($top) {

        /* Si la variable est a faux ou qu'aucun utilisateur n'est défini (les infos utilisateurs sont affichées dans l'en-tête) */

        if (!$top || ($top && config_item('auth_user_id') === NULL)) {

            $this->var['top'] = 0;

            return false;

        }

        //On affecte la variable

        $this->var['top'] = $top;

        //On charge la vue correspondant au top du theme si elle existe

        if (file_exists('./application/views/template/' . $this->theme . '-top.php')) {



            $this->var['topContent'] = $this->CI->load->view('themes/' . $this->theme . '/' . $this->theme . '-top', $this->getDataForTop(), true);

        } else {

            $this->var['topContent'] = $this->CI->load->view('themes/default/default-top', $this->getDataForTop(), true); // Vue par défaut

        }

    }



    /* Faut il afficher le footer du thème ? */



    public function set_bottom($bottom) {



        /* On affecte la variable correspondante */

        if (!$bottom) {

            $this->var['bottom'] = 0;

        }

        $this->var['bottom'] = $bottom;

        $data['vueProcl'] = $this->var['vueProcl'];

        $data['vueRP'] = $this->var['vueRP'];

        $data['vueAdmin'] = $this->var['vueAdmin'];

        //On charge la vue correspondant au bottom du theme si elle existe

        if (file_exists('./application/views/template/' . $this->theme . '-bottom.php')) {

            $this->var['bottomContent'] = $this->CI->load->view('themes/' . $this->theme . '/' . $this->theme . '-bottom', $data, true);

        } else {

            $this->var['bottomContent'] = $this->CI->load->view('themes/default/default-bottom', $data, true); // Par défaut

        }

    }



    /*

      |===============================================================================

      | Méthodes pour ajouter des feuilles de CSS et de JavaScript

      |	. ajouter_css

      |	. ajouter_js

      |===============================================================================

     */



    public function ajouter_css($nom) {

        if (is_string($nom) AND ! empty($nom) AND file_exists('./assets/css/' . $nom . '.css')) {

            $this->var['css'][] = base_url() . 'assets/css/' . $nom.'.css';

            return true;

        }

        return false;

    }



    public function ajouter_js($nom) {

        if (is_string($nom) AND ! empty($nom) AND file_exists('./assets/javascript/' . $nom . '.js')) {

            $this->var['js'][] = base_url() . 'assets/javascript/' . $nom.'.js';

            return true;

        }

        return false;

    }



    /*

      |===============================================================================

      | Méthodes pour modifier les variables envoyées au layout

      |	. set_titre

      |	. set_charset

      |===============================================================================

     */



    public function set_titre($titre) {

        if (is_string($titre) AND ! empty($titre)) {

            $this->var['titre'] = $titre;

            return true;

        }

        return false;

    }



    public function set_charset($charset) {

        if (is_string($charset) AND ! empty($charset)) {

            $this->var['charset'] = $charset;

            return true;

        }

        return false;

    }



    /**

     * 

     * Méthode récupérant les données utilisateur utiles dans l'en-tête

     */

    public function getDataForTop() {

        //On charge le modele

        $this->CI->load->model('M_utilisateurs');



        //On récupère la congrégation
	if(isset($_GET['idCong'])){
		$cong = $this->CI->M_congregations->get($_GET['idCong']);
	} else {
	        $cong = $this->CI->M_utilisateurs->getCongName(config_item('auth_user_id'));
	}
        $data['cong'] = $cong->nom;



        //On récupère les infos utilisateur

        $user = $this->CI->M_utilisateurs->get(config_item('auth_user_id'));

        $data['nom'] = $user->nom;

        $data['prenom'] = $user->prenom;

        

        $data['vueProcl'] = $this->var['vueProcl'];

        $data['vueRP'] = $this->var['vueRP'];

        $data['vueAdm'] = $this->var['vueAdmin'];

        

        return $data;

    }



    public function set_procl() {

        $this->var['vueProcl'   ] = 1;

        $this->var['vueRP'      ] = 0;

        $this->var['vueAdmin'   ] = 0;

    }

    

    public function set_RP() {

        $this->var['vueProcl'   ] = 0;

        $this->var['vueRP'      ] = 1;

        $this->var['vueAdmin'   ] = 0;

    }

    

    public function set_Admin() {

        $this->var['vueProcl'   ] = 0;

        $this->var['vueRP'      ] = 0;

        $this->var['vueAdmin'   ] = 1;

    }

}



/* End of file layout.php */

/* Location: ./application/libraries/layout.php */
>>>>>>> 7ff721c (initial commit)
