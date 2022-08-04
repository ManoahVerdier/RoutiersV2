<<<<<<< HEAD
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @author	Manoah VERDIER
 * Représente utilisateurs, indispos, congrégations
 */
class M_utilisateurs extends CI_Model {

    private $table_name = 'utilisateur'  ; //Contient les infos utiles
    private $table_name2 = 'indispo'     ; //Contient les indispos pour un créneau et un util
    private $table_name3 = 'users'       ; //Contient les données du système
    private $table_name4 = 'congregation'; //Infos de congrégation
    private $table_name5 = 'util_invit'  ; //Invitations nouveaux utilisateurs
    private $table_part = 'participation'  ; //Participations
    private $table_invit = 'invitation'  ; //Invitations
    private $table_cren = 'creneau_date'  ; //Créneaux
    private $table_info = 'infos'  ; //Créneaux

    function __construct() {
        parent::__construct();

        /* Préfixage */
        $ci = & get_instance();
        $this->table_name = $ci->config->item('db_table_prefix') . $this->table_name;
        $this->table_name2 = $ci->config->item('db_table_prefix') . $this->table_name2;
        $this->table_name3 = $ci->config->item('db_table_prefix') . $this->table_name3;
        $this->table_name4 = $ci->config->item('db_table_prefix') . $this->table_name4;
        $this->table_name5 = $ci->config->item('db_table_prefix') . $this->table_name5;
        $this->table_part = $ci->config->item('db_table_prefix') . $this->table_part;
        $this->table_cren = $ci->config->item('db_table_prefix') . $this->table_cren;
        $this->table_invit = $ci->config->item('db_table_prefix') . $this->table_invit;
        $this->table_info = $ci->config->item('db_table_prefix') . $this->table_info;
    }

    /* Nombre d'utilisateurs */

    function countAll() {
        return $this->db->count_all($this->table_name);
    }

    /**
     * Donne un utilisateur par ID
     * @param int $id
     * @return row
     */
    function get($id) {
        $this->db->select('*');

        $this->db->where('id', $id);

        $query = $this->db->get($this->table_name);

        return $query->row();
    }

    /**
     * Donne le nom de la congregation de l'util passé en param
     * @param int $id l'id du proclamateur
     * @return row
     */
    function getCongName($id) {
        $this->db->select($this->table_name4 . '.nom');
        $this->db->join($this->table_name4, $this->table_name . '.idCong = ' . $this->table_name4 . '.id');
        $this->db->where($this->table_name . '.id', $id);

        $query = $this->db->get($this->table_name);
        
        return $query->row();
    }

    /**
     * Donne l'id de la congregation de l'util passé en param
     * @param int $id
     * @return row
     */
    function getCongId($id) {
        $this->db->select($this->table_name4 . '.id');
        $this->db->join($this->table_name4, $this->table_name . '.idCong = ' . $this->table_name4 . '.id');
        $this->db->where($this->table_name . '.id', $id);

        $query = $this->db->get($this->table_name);

        return $query->row();
    }

    /**
     * Donne l'ID du RP de la congregation de l'util passé en param
     * @param int $id
     * @return row
     */
    function getCongRP($id) {
        $this->db->select($this->table_name4 . '.idRP');
        $this->db->join($this->table_name4, $this->table_name . '.idCong = ' . $this->table_name4 . '.id');
        $this->db->where($this->table_name . '.id', $id);

        $query = $this->db->get($this->table_name);
        return $query->row();
    }

    /**
     * Supprime l'utilisateur dont l'ID est passé
     * @param type $id
     */
    function deleteUtil($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_name);
        $this->db->where('user_id', $id);
        $this->db->delete($this->table_name3);
        $this->db->where('idUtil', $id);
        $this->db->delete($this->table_name2);
    }
    
    /**
     * Supprime l'assemblee dont l'ID est passé
     * @param type $id
     */
    function deleteCong($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_name4);
    }

    /**
     * Retourne tous les utilisateurs
     * @return resultMySQL
     */
    function getAllUtil() {
        $this->db->select('*');
        $query = $this->db->get($this->table_name);
        return $query->result();
    }
    
    
    /**
     * Retourne toutes les assemblées
     * @return resultMySQL
     */
    function getAllCongs() {
        $this->db->select('*');
        $query = $this->db->get($this->table_name4);
        return $query->result();
    }
    
    /**
     * Retourne tous les RP
     * @return resultMySQL
     */
    function getAllRP() {
        $this->db->select($this->table_name.'.*,'.$this->table_name4.'.nom AS nomCong');
        $this->db->join($this->table_name4,$this->table_name4.'.id = '.$this->table_name.'.idCong');
        $this->db->where('role',6);
        $query = $this->db->get($this->table_name);
        return $query->result();
    }
    
    /**
     * Retourne le mail de l'admin
     * @return mail str
     */
    function getadminMail() {
        $this->db->select($this->table_name.'.mail');
        $this->db->where('role',9);
        $query = $this->db->get($this->table_name);
        return $query->row()->mail;
    }

    /**
     * Retourne la liste des proclamateurs d'une congrégation
     * @param int $idCong
     * @return resultMySQL
     */
    function getProclsFromCong($idCong) {
        $this->db->select('*');
        $this->db->where('idCong', $idCong);
        $query = $this->db->get($this->table_name);
        return $query->result();
    }
    
    /**
     * Retourne la liste des proclamateurs d'une congrégation
     * @param int $idCong
     * @return resultMySQL
     */
    function getRPFromCong($idCong) {
        $this->db->select('*');
        $this->db->where('idCong', $idCong);
        $this->db->where('role', 6);
        $query = $this->db->get($this->table_name);
        return $query->result();
    }
    
    function getUtilByLastPartCong($idCong){
        $this->db->select($this->table_cren.'.date,'.$this->table_name.'.id');
        $this->db->join('('.$this->db->dbprefix($this->table_part).' JOIN '.$this->db->dbprefix($this->table_cren).' ON '.$this->db->dbprefix($this->table_cren).'.`id` = '.$this->db->dbprefix($this->table_part).'.`idCren`)',$this->table_part.'.idUtil = '.$this->table_name.'.id','left');
        $this->db->where('idCong',$idCong);
        $this->db->order_by($this->table_part.'.date DESC');
        $query = $this->db->get($this->table_name);
        return $query->result();
    }
    
    function getUtilByLastInvitCong($idCong){
        $this->db->select($this->table_cren.'.date,'.$this->table_name.'.id');
        $this->db->join('('.$this->db->dbprefix($this->table_invit).' JOIN '.$this->db->dbprefix($this->table_cren).' ON '.$this->db->dbprefix($this->table_cren).'.`id` = '.$this->db->dbprefix($this->table_invit).'.`idCren`)',$this->table_invit.'.idUtil = '.$this->table_name.'.id','left');
        $this->db->where('idCong',$idCong);
        $this->db->order_by($this->table_cren.'.date DESC');
        $query = $this->db->get($this->table_name);
        return $query->result();
    }

    /**
     * Ajoute un utilisateur
     * @param stdClass $util
     * @return int l'ID créé
     */
    function addUtil($util) {
        $this->db->set($util);
        $this->db->insert($this->table_name);
        return $this->db->insert_id();
    }
    
    /**
     * Ajoute une assemblee
     * @param stdClass $util
     * @return int l'ID créé
     */
    function addCong($cong) {
        $this->db->set($cong);
        $this->db->insert($this->table_name4);
        return $this->db->insert_id();
    }
    
    /**
     * Ajoute un utilisateur système
     * @param stdClass $util
     * @return int l'ID créé
     */
    function addUser($util) {
        $this->db->set($util);
        $this->db->insert($this->table_name3);
        return $this->db->insert_id();
    }

    /**
     * Change la congrégation de l'utilisateur
     * @param int $idCong
     * @param int $id
     */
    function setCong($idCong, $id) {
        $data = array("idCong" => $idCong);
        $this->db->where('id', $id);
        $this->db->replace($this->table_name, $data);
    }

    /**
     * Défini si l'utilisateur participe aux routiers ou non
     * @param boolean $isRoutier
     * @param int     $id
     */
    function setRoutiers($isRoutier, $id) {
        $data = array("routier" => $isRoutier);
        $this->db->where('id', $id);
        $this->db->replace($this->table_name, $data);
    }

    /**
     * Défini si l'utilisateur participe aux croisiéristes ou non
     * @param boolean $isCrois
     * @param int     $id
     */
    function setCroisieres($isCrois, $id) {
        $data = array("crois" => $isCrois);
        $this->db->where('id', $id);
        $this->db->replace($this->table_name, $data);
    }

    /**
     * Défini si l'utilisateur participe au TEMU ou non
     * @param boolean $isTemu
     * @param int     $id
     */
    function setTemu($isTemu, $id) {
        $data = array("temu" => $isTemu);
        $this->db->where('id', $id);
        $this->db->replace($this->table_name, $data);
    }

    /**
     * Retourne les indispos d'un utilisateur
     * @param int $id
     * @return resultMySQL
     */
    function getIndispo($id) {
        $this->db->select('*');
        $this->db->where('idUtil', $id);
        $query = $this->db->get($this->table_name2);
        return $query->result();
    }

    /**
     * Ajout d'une indisponibilité
     * @param stdClass(idUtil,idCren) $indispo
     * @return boolean
     */
    function addIndispo($indispo) {
        $this->db->set($indispo);
        $this->db->insert($this->table_name2);
        return $this->db->insert_id();
    }

    /**
     * Suppresion d'une indisponibilité
     * @param int $idUtil
     * @param int $idCren
     */
    function deleteIndispo($idUtil, $idCren) {
        $this->db->where('idCren', $idCren);
        $this->db->where('idUtil', $idUtil);
        $this->db->delete($this->table_name2);
    }

    /**
     * Mise à jour des données user système
     * @param int   $id
     * @param array $user_data
     */
    function update_user($id, $user_data = []) {
        $this->db->where('user_id', $id)
                ->update($this->table_name3, $user_data);
    }

    /**
     * Mise à jour des données utilisateur
     * @param int      $id
     * @param stdClass $user_data
     */
    function majUtilisateur($id, $user_data) {
        $this->db->where('id', $id)
                ->update($this->table_name, $user_data);
    }
    
    /**
     * Mise à jour des données cong
     * @param int      $id
     * @param stdClass $cong_data
     */
    function majCong($id, $cong_data) {
        $this->db->where('id', $id)
                ->update($this->table_name4, $cong_data);
    }

    /**
     * Retourne les données nécessaires à la récupération
     * 
     * @param   string  le mail 
     * @return  mixed   les données ou FAUX
     */
    public function get_recuperation($email) {
        $query = $this->db->select('u.user_id, u.email, u.banned')
                ->from($this->table_name3 . ' u')
                ->where('LOWER( u.email ) =', strtolower($email))
                ->limit(1)
                ->get();

        if ($query->num_rows() == 1)
            return $query->row();

        return FALSE;
    }

    /**
     * retourne les infos de vérifications si le code n'est pas expiré
     * FAUX sinon
     * @param  int  l'ID utilisateur
     */
    public function get_verification($user_id) {
        $recovery_code_expiration = date('Y-m-d H:i:s', time() - config_item('recovery_code_expiration'));

        $query = $this->db->select('username, passwd_recovery_code')
                ->from($this->table_name3)
                ->where('user_id', $user_id)
                ->where('passwd_recovery_date >', $recovery_code_expiration)
                ->limit(1)
                ->get();

        if ($query->num_rows() == 1)
            return $query->row();

        return FALSE;
    }

    /**
     * Changer le mot de passe d'un utilisateur
     * 
     * @param  string  le nouveau mdp
     * @param  string  la confirmation
     * @param  string  l'id Utilisateur
     * @param  string  le code de récupération
     */
    public function _changer_mdp($password, $password2, $user_id, $recovery_code) {
        // Vérification de la bonne forme de l'ID utilisateur
        if (isset($user_id) && $user_id !== FALSE) {
            $query = $this->db->select('user_id')
                    ->from($this->table_name3)
                    ->where('user_id', $user_id)
                    ->where('passwd_recovery_code', $recovery_code)
                    ->get();

            // Si la requête donne un résultat (i.e l'utilisateur existe avec ce code) on procède au changement
            if ($query->num_rows() == 1) {
                $user_data = $query->row();

                $this->db->where('user_id', $user_data->user_id)
                        ->update(
                                $this->table_name3, ['passwd' => $this->authentication->hash_passwd($password)]
                );
            }
        }
    }

    /**
     * Mise à jour du mot de passe par la voie classique
     * @param str $password
     * @param str $password2
     * @param int $user_id
     */
    public function _changer_mdp_self($password, $user_id) {

        $this->db->where('user_id', $user_id)
                ->update(
                        $this->table_name3, ['passwd' => $this->authentication->hash_passwd($password)]
        );
    }

    /**
     * Retourne un ID disponible pour créer un utilisateur
     *
     * @return  int between 1200 and 4294967295
     */
    public function get_id_libre() {
        // Création d'un id unique entre 1200 et 4294967295
        $random_unique_int = 2147483648 + mt_rand(-2147482448, 2147483647);

        // Vérification qu'il n'existe pas déjà
        $query = $this->db->where('user_id', $random_unique_int)
                ->get_where($this->db_table('user_table'));

        if ($query->num_rows() > 0) {
            $query->free_result();

            // Récursivité : si l'id existe déjà, on refait
            return $this->get_unused_id();
        }

        return $random_unique_int;
    }
    
    /**
     * Enregistre l'envoi d'une invitation 
     * @param stdclass $invit
     * @return int
     */
    public function addInvit($invit){
        $this->db->set($invit);
        $this->db->insert($this->table_name5);
        return $this->db->insert_id();
    }
    
    /**
     * Retourne une invitation utilisateur sur la base de l'id
     * @param int $id
     * @return array result sql
     */
    public function getInvit($id){
        $this->db->select('*');
        $this->db->where('id', $id);
        $query = $this->db->get($this->table_name5);
        return $query->row();
    }
    
    /**
     * Vérifie qu'il n'existe pas d'utilisateur avec le mail
     * @param string $email
     * @return boolean
     */
    public function is_unique_email($email){
        $this->db->select('*');
        $this->db->where('email', $email);
        $query = $this->db->get($this->table_name3);
        return $query->num_rows()>0?false:true;
    }
    
    /**
     * Supprime l'invitation dont l'ID est passé
     * @param type $id
     */
    function deleteInvit($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_name5);
    }
    
    function getInfosCong($idCong){
        $this->db->select('*');
        $this->db->where("idCong IN (-1,$idCong)");
        $this->db->order_by("urgent DESC, idCong DESC");
        $query = $this->db->get($this->table_info);
        return $query->result();
    }
    
    function majInfo($info){
        $this->db->set('contenu',$info->contenu);
        $this->db->set('actif',$info->actif);
        $this->db->where('idCong', $info->idCong);
        $this->db->where('urgent', $info->urgent);
        $this->db->update($this->table_info);
    }
    
}

=======
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @author	Manoah VERDIER
 * Représente utilisateurs, indispos, congrégations
 */
class M_utilisateurs extends CI_Model {

    private $table_name = 'utilisateur'  ; //Contient les infos utiles
    private $table_name2 = 'indispo'     ; //Contient les indispos pour un créneau et un util
    private $table_name3 = 'users'       ; //Contient les données du système
    private $table_name4 = 'congregation'; //Infos de congrégation
    private $table_name5 = 'util_invit'  ; //Invitations nouveaux utilisateurs
    private $table_part = 'participation'  ; //Participations
    private $table_invit = 'invitation'  ; //Invitations
    private $table_cren = 'creneau_date'  ; //Créneaux
    private $table_info = 'infos'  ; //Créneaux

    function __construct() {
        parent::__construct();

        /* Préfixage */
        $ci = & get_instance();
        $this->table_name = $ci->config->item('db_table_prefix') . $this->table_name;
        $this->table_name2 = $ci->config->item('db_table_prefix') . $this->table_name2;
        $this->table_name3 = $ci->config->item('db_table_prefix') . $this->table_name3;
        $this->table_name4 = $ci->config->item('db_table_prefix') . $this->table_name4;
        $this->table_name5 = $ci->config->item('db_table_prefix') . $this->table_name5;
        $this->table_part = $ci->config->item('db_table_prefix') . $this->table_part;
        $this->table_cren = $ci->config->item('db_table_prefix') . $this->table_cren;
        $this->table_invit = $ci->config->item('db_table_prefix') . $this->table_invit;
        $this->table_info = $ci->config->item('db_table_prefix') . $this->table_info;
    }

    /* Nombre d'utilisateurs */

    function countAll() {
        return $this->db->count_all($this->table_name);
    }

    /**
     * Donne un utilisateur par ID
     * @param int $id
     * @return row
     */
    function get($id) {
        $this->db->select('*');

        $this->db->where('id', $id);

        $query = $this->db->get($this->table_name);

        return $query->row();
    }

    /**
     * Donne le nom de la congregation de l'util passé en param
     * @param int $id l'id du proclamateur
     * @return row
     */
    function getCongName($id) {
        $this->db->select($this->table_name4 . '.nom');
        $this->db->join($this->table_name4, $this->table_name . '.idCong = ' . $this->table_name4 . '.id');
        $this->db->where($this->table_name . '.id', $id);

        $query = $this->db->get($this->table_name);
        
        return $query->row();
    }

    /**
     * Donne l'id de la congregation de l'util passé en param
     * @param int $id
     * @return row
     */
    function getCongId($id) {
        $this->db->select($this->table_name4 . '.id');
        $this->db->join($this->table_name4, $this->table_name . '.idCong = ' . $this->table_name4 . '.id');
        $this->db->where($this->table_name . '.id', $id);

        $query = $this->db->get($this->table_name);

        return $query->row();
    }

    /**
     * Donne l'ID du RP de la congregation de l'util passé en param
     * @param int $id
     * @return row
     */
    function getCongRP($id) {
        $this->db->select($this->table_name4 . '.idRP');
        $this->db->join($this->table_name4, $this->table_name . '.idCong = ' . $this->table_name4 . '.id');
        $this->db->where($this->table_name . '.id', $id);

        $query = $this->db->get($this->table_name);
        return $query->row();
    }

    /**
     * Supprime l'utilisateur dont l'ID est passé
     * @param type $id
     */
    function deleteUtil($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_name);
        $this->db->where('user_id', $id);
        $this->db->delete($this->table_name3);
        $this->db->where('idUtil', $id);
        $this->db->delete($this->table_name2);
    }
    
    /**
     * Supprime l'assemblee dont l'ID est passé
     * @param type $id
     */
    function deleteCong($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_name4);
    }

    /**
     * Retourne tous les utilisateurs
     * @return resultMySQL
     */
    function getAllUtil() {
        $this->db->select('*');
        $query = $this->db->get($this->table_name);
        return $query->result();
    }
    
    
    /**
     * Retourne toutes les assemblées
     * @return resultMySQL
     */
    function getAllCongs() {
        $this->db->select('*');
        $query = $this->db->get($this->table_name4);
        return $query->result();
    }
    
    /**
     * Retourne tous les RP
     * @return resultMySQL
     */
    function getAllRP() {
        $this->db->select($this->table_name.'.*,'.$this->table_name4.'.nom AS nomCong');
        $this->db->join($this->table_name4,$this->table_name4.'.id = '.$this->table_name.'.idCong');
        $this->db->where('role',6);
        $query = $this->db->get($this->table_name);
        return $query->result();
    }
    
    /**
     * Retourne le mail de l'admin
     * @return mail str
     */
    function getadminMail() {
        $this->db->select($this->table_name.'.mail');
        $this->db->where('role',9);
        $query = $this->db->get($this->table_name);
        return $query->row()->mail;
    }

    /**
     * Retourne la liste des proclamateurs d'une congrégation
     * @param int $idCong
     * @return resultMySQL
     */
    function getProclsFromCong($idCong) {
        $this->db->select('*');
        $this->db->where('idCong', $idCong);
        $query = $this->db->get($this->table_name);
        return $query->result();
    }
    
    /**
     * Retourne la liste des proclamateurs d'une congrégation
     * @param int $idCong
     * @return resultMySQL
     */
    function getRPFromCong($idCong) {
        $this->db->select('*');
        $this->db->where('idCong', $idCong);
        $this->db->where('role', 6);
        $query = $this->db->get($this->table_name);
        return $query->result();
    }
    
    function getUtilByLastPartCong($idCong){
        $this->db->select($this->table_cren.'.date,'.$this->table_name.'.id');
        $this->db->join('('.$this->db->dbprefix($this->table_part).' JOIN '.$this->db->dbprefix($this->table_cren).' ON '.$this->db->dbprefix($this->table_cren).'.`id` = '.$this->db->dbprefix($this->table_part).'.`idCren`)',$this->table_part.'.idUtil = '.$this->table_name.'.id','left');
        $this->db->where('idCong',$idCong);
        $this->db->order_by($this->table_part.'.date DESC');
        $query = $this->db->get($this->table_name);
        return $query->result();
    }
    
    function getUtilByLastInvitCong($idCong){
        $this->db->select($this->table_cren.'.date,'.$this->table_name.'.id');
        $this->db->join('('.$this->db->dbprefix($this->table_invit).' JOIN '.$this->db->dbprefix($this->table_cren).' ON '.$this->db->dbprefix($this->table_cren).'.`id` = '.$this->db->dbprefix($this->table_invit).'.`idCren`)',$this->table_invit.'.idUtil = '.$this->table_name.'.id','left');
        $this->db->where('idCong',$idCong);
        $this->db->order_by($this->table_cren.'.date DESC');
        $query = $this->db->get($this->table_name);
        return $query->result();
    }

    /**
     * Ajoute un utilisateur
     * @param stdClass $util
     * @return int l'ID créé
     */
    function addUtil($util) {
        $this->db->set($util);
        $this->db->insert($this->table_name);
        return $this->db->insert_id();
    }
    
    /**
     * Ajoute une assemblee
     * @param stdClass $util
     * @return int l'ID créé
     */
    function addCong($cong) {
        $this->db->set($cong);
        $this->db->insert($this->table_name4);
        return $this->db->insert_id();
    }
    
    /**
     * Ajoute un utilisateur système
     * @param stdClass $util
     * @return int l'ID créé
     */
    function addUser($util) {
        $this->db->set($util);
        $this->db->insert($this->table_name3);
        return $this->db->insert_id();
    }

    /**
     * Change la congrégation de l'utilisateur
     * @param int $idCong
     * @param int $id
     */
    function setCong($idCong, $id) {
        $data = array("idCong" => $idCong);
        $this->db->where('id', $id);
        $this->db->replace($this->table_name, $data);
    }

    /**
     * Défini si l'utilisateur participe aux routiers ou non
     * @param boolean $isRoutier
     * @param int     $id
     */
    function setRoutiers($isRoutier, $id) {
        $data = array("routier" => $isRoutier);
        $this->db->where('id', $id);
        $this->db->replace($this->table_name, $data);
    }

    /**
     * Défini si l'utilisateur participe aux croisiéristes ou non
     * @param boolean $isCrois
     * @param int     $id
     */
    function setCroisieres($isCrois, $id) {
        $data = array("crois" => $isCrois);
        $this->db->where('id', $id);
        $this->db->replace($this->table_name, $data);
    }

    /**
     * Défini si l'utilisateur participe au TEMU ou non
     * @param boolean $isTemu
     * @param int     $id
     */
    function setTemu($isTemu, $id) {
        $data = array("temu" => $isTemu);
        $this->db->where('id', $id);
        $this->db->replace($this->table_name, $data);
    }

    /**
     * Retourne les indispos d'un utilisateur
     * @param int $id
     * @return resultMySQL
     */
    function getIndispo($id) {
        $this->db->select('*');
        $this->db->where('idUtil', $id);
        $query = $this->db->get($this->table_name2);
        return $query->result();
    }

    /**
     * Ajout d'une indisponibilité
     * @param stdClass(idUtil,idCren) $indispo
     * @return boolean
     */
    function addIndispo($indispo) {
        $this->db->set($indispo);
        $this->db->insert($this->table_name2);
        return $this->db->insert_id();
    }

    /**
     * Suppresion d'une indisponibilité
     * @param int $idUtil
     * @param int $idCren
     */
    function deleteIndispo($idUtil, $idCren) {
        $this->db->where('idCren', $idCren);
        $this->db->where('idUtil', $idUtil);
        $this->db->delete($this->table_name2);
    }

    /**
     * Mise à jour des données user système
     * @param int   $id
     * @param array $user_data
     */
    function update_user($id, $user_data = []) {
        $this->db->where('user_id', $id)
                ->update($this->table_name3, $user_data);
    }

    /**
     * Mise à jour des données utilisateur
     * @param int      $id
     * @param stdClass $user_data
     */
    function majUtilisateur($id, $user_data) {
        $this->db->where('id', $id)
                ->update($this->table_name, $user_data);
    }
    
    /**
     * Mise à jour des données cong
     * @param int      $id
     * @param stdClass $cong_data
     */
    function majCong($id, $cong_data) {
        $this->db->where('id', $id)
                ->update($this->table_name4, $cong_data);
    }

    /**
     * Retourne les données nécessaires à la récupération
     * 
     * @param   string  le mail 
     * @return  mixed   les données ou FAUX
     */
    public function get_recuperation($email) {
        $query = $this->db->select('u.user_id, u.email, u.banned')
                ->from($this->table_name3 . ' u')
                ->where('LOWER( u.email ) =', strtolower($email))
                ->limit(1)
                ->get();

        if ($query->num_rows() == 1)
            return $query->row();

        return FALSE;
    }

    /**
     * retourne les infos de vérifications si le code n'est pas expiré
     * FAUX sinon
     * @param  int  l'ID utilisateur
     */
    public function get_verification($user_id) {
        $recovery_code_expiration = date('Y-m-d H:i:s', time() - config_item('recovery_code_expiration'));

        $query = $this->db->select('username, passwd_recovery_code')
                ->from($this->table_name3)
                ->where('user_id', $user_id)
                ->where('passwd_recovery_date >', $recovery_code_expiration)
                ->limit(1)
                ->get();

        if ($query->num_rows() == 1)
            return $query->row();

        return FALSE;
    }

    /**
     * Changer le mot de passe d'un utilisateur
     * 
     * @param  string  le nouveau mdp
     * @param  string  la confirmation
     * @param  string  l'id Utilisateur
     * @param  string  le code de récupération
     */
    public function _changer_mdp($password, $password2, $user_id, $recovery_code) {
        // Vérification de la bonne forme de l'ID utilisateur
        if (isset($user_id) && $user_id !== FALSE) {
            $query = $this->db->select('user_id')
                    ->from($this->table_name3)
                    ->where('user_id', $user_id)
                    ->where('passwd_recovery_code', $recovery_code)
                    ->get();

            // Si la requête donne un résultat (i.e l'utilisateur existe avec ce code) on procède au changement
            if ($query->num_rows() == 1) {
                $user_data = $query->row();

                $this->db->where('user_id', $user_data->user_id)
                        ->update(
                                $this->table_name3, ['passwd' => $this->authentication->hash_passwd($password)]
                );
            }
        }
    }

    /**
     * Mise à jour du mot de passe par la voie classique
     * @param str $password
     * @param str $password2
     * @param int $user_id
     */
    public function _changer_mdp_self($password, $user_id) {

        $this->db->where('user_id', $user_id)
                ->update(
                        $this->table_name3, ['passwd' => $this->authentication->hash_passwd($password)]
        );
    }

    /**
     * Retourne un ID disponible pour créer un utilisateur
     *
     * @return  int between 1200 and 4294967295
     */
    public function get_id_libre() {
        // Création d'un id unique entre 1200 et 4294967295
        $random_unique_int = 2147483648 + mt_rand(-2147482448, 2147483647);

        // Vérification qu'il n'existe pas déjà
        $query = $this->db->where('user_id', $random_unique_int)
                ->get_where($this->db_table('user_table'));

        if ($query->num_rows() > 0) {
            $query->free_result();

            // Récursivité : si l'id existe déjà, on refait
            return $this->get_unused_id();
        }

        return $random_unique_int;
    }
    
    /**
     * Enregistre l'envoi d'une invitation 
     * @param stdclass $invit
     * @return int
     */
    public function addInvit($invit){
        $this->db->set($invit);
        $this->db->insert($this->table_name5);
        return $this->db->insert_id();
    }
    
    /**
     * Retourne une invitation utilisateur sur la base de l'id
     * @param int $id
     * @return array result sql
     */
    public function getInvit($id){
        $this->db->select('*');
        $this->db->where('id', $id);
        $query = $this->db->get($this->table_name5);
        return $query->row();
    }
    
    /**
     * Vérifie qu'il n'existe pas d'utilisateur avec le mail
     * @param string $email
     * @return boolean
     */
    public function is_unique_email($email){
        $this->db->select('*');
        $this->db->where('email', $email);
        $query = $this->db->get($this->table_name3);
        return $query->num_rows()>0?false:true;
    }
    
    /**
     * Supprime l'invitation dont l'ID est passé
     * @param type $id
     */
    function deleteInvit($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_name5);
    }
    
    function getInfosCong($idCong){
        $this->db->select('*');
        $this->db->where("idCong IN (-1,$idCong)");
        $this->db->order_by("urgent DESC, idCong DESC");
        $query = $this->db->get($this->table_info);
        return $query->result();
    }
    
    function majInfo($info){
        $this->db->set('contenu',$info->contenu);
        $this->db->set('actif',$info->actif);
        $this->db->where('idCong', $info->idCong);
        $this->db->where('urgent', $info->urgent);
        $this->db->update($this->table_info);
    }
    
}

>>>>>>> 7ff721c (initial commit)
