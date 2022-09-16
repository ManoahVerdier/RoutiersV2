<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Modèle : créneaux
 * @author	Manoah VERDIER
 */
class M_creneau extends CI_Model {

    private $table_name     = 'creneau';
    private $table_name2    = 'creneau_date';
    private $table_part     = 'participation';
    private $table_invit    = 'invitation';
    private $table_user     = 'utilisateur';
    private $table_lieu     = 'lieux';
    private $table_inter    = 'intervient';
    private $table_conc     = 'concerne';
    private $table_cong     = 'congregation';
    private $table_indispo  = 'indispo';

    function __construct() {
        parent::__construct();

        $ci = & get_instance();
        /* Préfixage */
        $this->table_name = $ci->config->item('db_table_prefix') . $this->table_name;
        $this->table_name2 = $ci->config->item('db_table_prefix') . $this->table_name2;
        $this->table_part = $ci->config->item('db_table_prefix') . $this->table_part;
        $this->table_invit = $ci->config->item('db_table_prefix') . $this->table_invit;
        $this->table_user = $ci->config->item('db_table_prefix') . $this->table_user;
        $this->table_lieu = $ci->config->item('db_table_prefix') . $this->table_lieu;
        $this->table_inter = $ci->config->item('db_table_prefix') . $this->table_inter;
        $this->table_conc = $ci->config->item('db_table_prefix') . $this->table_conc;
        $this->table_cong = $ci->config->item('db_table_prefix') . $this->table_cong;
    }

    /**
     * Renvoie le nombre de créneau
     * @return int
     */
    function countAll() {
        return $this->db->count_all($this->table_name);
    }

    /**
     * Retourne un créneau par id
     * @param int $id
     * @return array
     */
    function get($id) {
        $this->db->select('*');

        $this->db->where('id', $id);

        $query = $this->db->get($this->table_name);

        return $query->row();
    }
    
    /**
     * Mise à jour d'un créneau
     * @param stdClass $cren
     */
    function majCren($cren) {
        $this->db->where('id', $cren->id)
                ->update($this->table_name, $cren);
        $crenDate = new stdClass;
        $crenDate->heure=$cren->heure;
        $this->db->where('idCreneau', $cren->id);
        $this->db->where('date>', date('Y-m-d'));
        $this->db->update($this->table_name2, $crenDate);
    }
    
    /**
     * Mise à jour d'un créneau
     * @param stdClass $cren
     */
    function majLieu($lieu) {
        $this->db->where('id', $lieu->id)
                ->update($this->table_lieu, $lieu);
    }
    
    function majCrenJour($id,$cren){
        $this->db->where('id', $id)
                ->update($this->table_name2, $cren);
    }
    
    /**
     * Retourne les créneau d'une congregation
     * @param int $id
     * @return array
     */
    function getForCong($idCong) {
        $this->db->select('*');
        $this->db->join($this->table_conc,$this->table_conc.'.idCren = '.$this->table_name.'.id');
        $this->db->where($this->table_conc.'.idCong', $idCong);

        $query = $this->db->get($this->table_name);

        return $query->result();
    }

    /**
     * Retourne un créneau daté par id
     * @param int $id
     * @return array
     */
    function getWithDate($id) {
        $this->db->select('*');
        $this->db->where('id', $id);
        $query = $this->db->get($this->table_name2);
        return $query->row();
    }

    /**
     * Retourne les date d'un créneau daté par id
     * @param int $idCren
     * @return array
     */
    function getDateId($idCren) {
        $this->db->select('*');
        $this->db->where('idCreneau', $idCren);
        $query = $this->db->get($this->table_name2);

        return $query->result();
    }

    /**
     * Supprime un créneau par id
     * @param int $id
     */
    function deleteCren($id) {
        $crens = $this->getDateId($id);
        foreach($crens as $cren){
            if($cren->date>date('Y-m-d')){
                $this->deleteCrenDate($cren->id);
            }
        }
        $this->db->where('idCren', $id);
        $this->db->delete($this->table_conc);
        $this->db->where('id', $id);
        $this->db->delete($this->table_name);
    }

    /**
     * Supprime un créneau daté par id
     * @param int $id
     */
    function deleteCrenDate($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_name2);
    }
    
    /**
     * Supprime une assemblée d'un créneau
     * @param int $idCren
     * @param int $idCong
     */
    function delConc($idCren,$idCong) {
        $this->db->where('idCren', $idCren);
        $this->db->where('idCong', $idCong);
        $this->db->delete($this->table_conc);
    }
    
    /**
     * Supprime unlieu
     * @param int $idCren
     * @param int $idCong
     */
    function deleteLieu($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_lieu);
    }

    /**
     * Annule un creneau
     * @param int $id
     */
    function annuleCrenDate($id){
        $data = array("annule" => 1);
        $this->db->where('id',$id);
        $this->db->update($this->table_name2, $data);
    }
    
    /**
     * Retourne tous les créneaux
     * @return array double
     */
    function getAllCren() {
        $this->db->select('*');
        $query = $this->db->get($this->table_name);
        return $query->result();
    }

    /**
     * Retourne tous les créneaux datés
     * @return array
     */
    function getAllCrenDate() {
        $this->db->select('*');
        $this->db->order_by('date');
        $query = $this->db->get($this->table_name2);
        return $query->result();
    }
    
    function getAllCrenDateAuto() {
        $this->db->select('*,'.$this->table_name2.'.id as idDate');
        $this->db->join($this->table_name,$this->table_name.'.id = '.$this->table_name2.'.idCreneau');
        $this->db->where('auto','1');
        $this->db->order_by('date');
        $query = $this->db->get($this->table_name2);
        return $query->result();
    }
    
    /**
     * Retourne tous les créneaux datés
     * @return array
     */
    function getAllLieux() {
        $this->db->select('*');
        $query = $this->db->get($this->table_lieu);
        return $query->result();
    }

    /**
     * Ajoute un créneau
     * @param stdClass $cren
     * @return int id
     */
    function addCren($cren) {
        $this->db->set($cren);
        $this->db->insert($this->table_name);
        $id =  $this->db->insert_id();
        $conc = new stdClass;
        $conc->idCong=$cren->idCong;
        $conc->idCren=$id;
        $this->db->set($conc);
        $this->db->insert($this->table_conc);
        return $id;
    }

    /**
     * Ajoute un créneau daté
     * @param stdClass $crenD
     * @return int id
     */
    function addCrenDate($crenD) {
        $this->db->set($crenD);
        $this->db->insert($this->table_name2);
        
        return $this->db->insert_id();
    }

    /**
     * Retourne la congrégation (id) d'un créneau daté
     * @param int $id
     * @return array
     */
    function getCongFromCrenDate($id) {
        $this->db->select($this->table_conc . '.*');
        $this->db->join($this->table_name2 , $this->table_name . '.id = ' . $this->table_name2 . '.idCreneau');
        $this->db->join($this->table_conc , $this->table_name . '.id = ' . $this->table_conc . '.idCren');
        $this->db->where($this->table_name2 . '.id', $id);

        $query = $this->db->get($this->table_name);
        return $query->result();
    }

    /**
     * Retourne la liste des prochains créneaux pour une congrégation (id)
     * @param int $idCong
     * @return array
     */
    function getProcCrenByCong($idCong) {
        $this->db->select($this->table_name . '.*,' . $this->table_name2 . '.*,' . $this->table_cong . '.nom');
        $this->db->join($this->table_name2, $this->table_name . '.id = ' . $this->table_name2 . '.idCreneau');
        $this->db->join($this->table_conc, $this->table_name . '.id = ' . $this->table_conc . '.idCren');
        $this->db->join($this->table_cong, $this->table_name . '.idCong = ' . $this->table_cong . '.id');
        $this->db->where($this->table_conc . '.idCong', $idCong);
        $this->db->where($this->table_name2 . '.date>=', date('Y-m-d'));
        $this->db->where($this->table_name2 . '.date>"2019-03-10"');
        $this->db->order_by('date ASC');
        $query = $this->db->get($this->table_name);
        return $query->result();
    }

    function getProcCrenLibreByCong($idCong){
        $this->db->select($this->table_name . '.*,' . $this->table_name2 . '.*,' . $this->table_cong . '.nom');
        $this->db->join($this->table_name2, $this->table_name . '.id = ' . $this->table_name2 . '.idCreneau');
        $this->db->join($this->table_conc, $this->table_name . '.id = ' . $this->table_conc . '.idCren');
        $this->db->join($this->table_cong, $this->table_name . '.idCong = ' . $this->table_cong . '.id');
        $this->db->where($this->table_conc . '.idCong', $idCong);
        $this->db->where($this->table_name . '.libre', 1);
        $this->db->where($this->table_name2 . '.date>=', date('Y-m-d'));
        $this->db->order_by('date ASC');
        $query = $this->db->get($this->table_name);
        return $query->result();
    }
    
    /**
     * Retourne la liste d'id des creneaux complets pour une congregation
     * @param int $idCong
     * @return array
     */
    public function getIdComplet($idCong) {
        $this->db->select($this->table_name2 . '.id');
        $this->db->join($this->table_name2, $this->table_name . '.id = ' . $this->table_name2 . '.idCreneau');
        $this->db->join($this->table_part, $this->table_part . '.idCren = ' . $this->table_name2 . '.id');
        $this->db->join($this->table_conc, $this->table_conc . '.idCren = ' . $this->table_name . '.id');
        $this->db->where($this->table_conc . '.idCong', $idCong);
        //$this->db->where($this->table_name2 . '.date>', date('Y-m-d'));
        $this->db->where($this->table_name2 . '.date>"2019-03-10"');
		$this->db->where($this->table_part . '.status=1');
        $this->db->order_by($this->table_name2.'.date ASC');
        $this->db->group_by($this->table_name2.'.id,nb');
        $this->db->having('count(*)>nb');
        $query = $this->db->get($this->table_name);
        $ids=array();
        foreach($query->result() AS $result)
            $ids[] = $result->id;
        
        return $ids;
    }
    
    /**
     * Retourne la liste d'id des creneaux incomplets pour une congregation
     * @param int $idCong
     * @return array
     */
    public function getIdPartiel($idCong) {
        $this->db->select($this->table_name2 . '.id');
        $this->db->join($this->table_name2, $this->table_name . '.id = ' . $this->table_name2 . '.idCreneau');
        $this->db->join($this->table_part, $this->table_part . '.idCren = ' . $this->table_name2 . '.id');
        $this->db->join($this->table_conc, $this->table_conc . '.idCren = ' . $this->table_name . '.id');
        $this->db->where($this->table_conc . '.idCong', $idCong);
        //$this->db->where($this->table_name2 . '.date>', date('Y-m-d'));
        $this->db->where($this->table_name2 . '.date>"2019-03-10"');
	$this->db->where($this->table_part . '.status=1');
        $this->db->order_by($this->table_name2.'.date ASC');
        $this->db->group_by($this->table_name2.'.id');
        $this->db->group_by($this->table_name.'.nb');
        $this->db->having('count(*)<'.$this->table_name.'.nb');
        $query = $this->db->get($this->table_name);
        
        $ids=array();
        foreach($query->result() AS $result)
            $ids[] = $result->id;
        
        return $ids;
    }

    /**
     * Retourne la liste d'id des creneaux pour les quels une invitation 
     * a ete envoyee pour une congregation
     * @param int $idCong
     * @return array
     */
    public function getIdInvit($idCong) {
        $this->db->select($this->table_name2 . '.id,ok,count(*) as nb');
        $this->db->join($this->table_name2, $this->table_name . '.id = ' . $this->table_name2 . '.idCreneau');
        $this->db->join($this->table_invit, $this->table_invit . '.idCren = ' . $this->table_name2 . '.id');
        $this->db->join($this->table_conc, $this->table_conc . '.idCren = ' . $this->table_name . '.id');
        $this->db->where($this->table_conc . '.idCong', $idCong);
        $this->db->where($this->table_name2 . '.date>', date('Y-m-d'));
        $this->db->order_by($this->table_name2.'.date ASC');
        $this->db->group_by($this->table_name2.'.id,'.$this->table_invit.'.ok');
        $query = $this->db->get($this->table_name);
        
        $tab=array();
        foreach($query->result() AS $result)
            $tab[] = $result;
        
        return $tab;
    }
    
    public function getDate($id){
        $this->db->select('date,heure');
        $this->db->where('id',$id);
        $query = $this->db->get($this->table_name2);
        return $query->row();
    }
    
    public function getLieu($id){
        $this->db->select('*');
        $this->db->where('id', $id);
        $query = $this->db->get($this->table_lieu);
        return $query->row();
    }
    
    public function addLieu($lieu){
        $this->db->set($lieu);
        $this->db->insert($this->table_lieu);
        return $this->db->insert_id();
    }
    
    /**
     * Retourne les lieux dans lesquels la congregation va effectivement
     * @param type $idCong
     * @return type
     */
    public function getLieuxCrenCong($idCong){
        $this->db->select('*,'.$this->table_name.'.idCong AS idAdmin');
        $this->db->join($this->table_name,$this->table_lieu.'.id = '.$this->table_name.'.idLieu');
        $this->db->join($this->table_conc, $this->table_conc . '.idCren = ' . $this->table_name . '.id');
        $this->db->join($this->table_cong, $this->table_cong . '.id = ' . $this->table_name . '.idCong');
        $this->db->where($this->table_conc.'.idCong', $idCong);
        $this->db->order_by('jour,heure');
        $query = $this->db->get($this->table_lieu);
        return $query->result();
    }
    
    /**
     * Retourne les lieux dans lesquels la congregation va effectivement
     * @param type $idCong
     * @return type
     */
    public function getLieuxCrenCongById($idCren){
        $this->db->select('*,'.$this->table_name.'.idCong AS idAdmin');
        $this->db->join($this->table_name,$this->table_lieu.'.id = '.$this->table_name.'.idLieu');
        $this->db->join($this->table_conc, $this->table_conc . '.idCren = ' . $this->table_name . '.id');
        $this->db->join($this->table_cong, $this->table_cong . '.id = ' . $this->table_name . '.idCong');
        $this->db->where($this->table_name.'.id', $idCren);
        $this->db->order_by('jour,heure');
        $query = $this->db->get($this->table_lieu);
        return $query->row();
    }
    
    /**
     * Retourne les autres congregations intervenant sur le même créneau
     * @param int $idCong
     * @return Array le tableau result
     */
    public function getAutreCongCren($idCong){
        $this->db->select($this->table_conc.'.*,'.$this->table_cong.'.nom');
        $this->db->join($this->table_conc,$this->table_conc.'.idCong = '.$this->table_cong.'.id');
        $this->db->where($this->table_conc.'.idCong!='.$idCong);
        $this->db->where($this->table_conc.'.idCren IN (SELECT idCren FROM rout_concerne WHERE idCong = '.$idCong.')');
        $query = $this->db->get($this->table_cong);
        return $query->result();
    }
    
    public function getLieuByCrenId($idCren){
        $this->db->select($this->table_lieu.'.*');
        $this->db->join($this->table_name,$this->table_lieu.'.id = '.$this->table_name.'.idLieu');
        $this->db->where($this->table_name.'.id', $idCren);
        $query = $this->db->get($this->table_lieu);
        return $query->row();
    }
    
    /**
     * Retourne les lieux dans lesquels la congregation peut aller
     * @param type $idCong
     * @return type
     */
    public function getLieuxCong($idCong){
        $this->db->select('*');
        $this->db->join($this->table_inter,$this->table_lieu.'.id = '.$this->table_inter.'.idLieu');
        $this->db->where('idCong', $idCong);
        $query = $this->db->get($this->table_lieu);
        return $query->result();
    }
    
    /**
     * Retourne les lieux 
     * @param type $idCong
     * @return type
     */
    public function getLieux($idCong){
        $this->db->select('*');
        $query = $this->db->get($this->table_lieu);
        return $query->result();
    }
    
    public function generateCren($cren,$id){
        $jourText = date('D', strtotime("Sunday +{$cren->jour} days"));
        $dateDeb=date('Y-m-d',strtotime('next '.$jourText));
        $dateFin=date('Y-m-d',strtotime($dateDeb.'+6 months'));
        while($dateDeb<=$dateFin){
            $crenDate = new stdClass;
            $crenDate->id='';
            $crenDate->idCreneau=$id;
            $crenDate->date=$dateDeb;
            $crenDate->heure=$cren->heure;
            $crenDate->annule=0;
            $this->addCrenDate($crenDate);
            $dateDeb = date('Y-m-d',strtotime($dateDeb.' +7 days'));
        }
    }
    
    public function addOneCren($cren,$id){
        $this->db->select('max(date) as mx');
        $this->db->where('idCreneau', $id);
        $query = $this->db->get($this->table_name2);
        $lastDate = $query->row()->mx;
        $newDate=date('Y-m-d',strtotime($lastDate.'+7 days'));
        $crenDate = new stdClass;
        $crenDate->date=$newDate;
        $crenDate->heure=$cren->heure;
        $crenDate->idCreneau=$id;
        $this->addCrenDate($crenDate);
    }
    
    public function addConc($idCren,$idCong){
        $conc = new stdClass;
        $conc->idCren = $idCren;
        $conc->idCong = $idCong;
        $this->db->set($conc);
        $this->db->insert($this->table_conc);
    }
    
    function getProclsForCren($idCren,$idDate){
        $this->db->select($this->table_user.'.*');
        $this->db->join($this->table_conc,$this->table_user.'.idCong = '.$this->table_conc.'.idCong');
        $this->db->where($this->table_conc.'.idCren', $idCren);
        $this->db->where($this->db->dbprefix.$this->table_user.'.id NOT IN (SELECT idUtil FROM '.$this->db->dbprefix.$this->table_invit.' WHERE idCren='.$idDate.' AND (ok=1 OR ok IS NULL))');
        $this->db->where($this->db->dbprefix.$this->table_user.'.id NOT IN (SELECT idUtil FROM '.$this->db->dbprefix.$this->table_indispo.' WHERE idCren='.$idDate.')');
        $query = $this->db->get($this->table_user);
        //echo $this->db->last_query();
        return $query->result();
    }
}
