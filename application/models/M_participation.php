<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Modèle : participations et invitations
 * @author	Manoah VERDIER
 */
class M_participation extends CI_Model {

    private $table_name = 'participation';
    private $table_name2 = 'invitation';
    private $table_cren_date = 'creneau_date';
    private $table_cren = 'creneau';
    private $table_user = 'utilisateur';
    private $table_lieu = 'lieux';
    private $table_cong = 'congregation';
    private $table_conc = 'concerne';
    private $table_rap  = 'rapport';
    private $table_plac = 'placement';
    private $table_renc = 'rencontre';

    function __construct() {
        parent::__construct();

        $ci = & get_instance();
        $this->table_name = $ci->config->item('db_table_prefix') . $this->table_name;
        $this->table_name2 = $ci->config->item('db_table_prefix') . $this->table_name2;
        $this->table_cren_date = $ci->config->item('db_table_prefix') . $this->table_cren_date;
        $this->table_cren = $ci->config->item('db_table_prefix') . $this->table_cren;
        $this->table_user = $ci->config->item('db_table_prefix') . $this->table_user;
        $this->table_lieu = $ci->config->item('db_table_prefix') . $this->table_lieu;
        $this->table_cong = $ci->config->item('db_table_prefix') . $this->table_cong;
        $this->table_conc = $ci->config->item('db_table_prefix') . $this->table_conc;
    }

    /**
     * Retourne le nombre de participations
     * @return int
     */
    function countAll() {
        return $this->db->count_all($this->table_name);
    }

    /**
     * Retourne le nombre d'invitations
     * @return int
     */
    function countAllInvit() {
        return $this->db->count_all($this->table_name);
    }

    /**
     * Retourne une particiaption par son id
     * @param int $id
     * @return array la ligne de la base
     */
    function get($id) {
        $this->db->select('*');

        $this->db->where('id', $id);

        $query = $this->db->get($this->table_name);

        return $query->row();
    }

    /**
     * Retourne une invitation par son ID
     * @param int $id
     * @return array la ligne de la base
     */
    function getInvit($id) {
        $this->db->select('*');

        $this->db->where('id', $id);

        $query = $this->db->get($this->table_name2);

        return $query->row();
    }

    /**
     * Retourne une liste d'invitations sur la base de leurs statut (1 ou 0)
     * @param string $status
     * @return array tableau représentant le résultat sql
     */
    function getPartByStatus($status) {
        $this->db->select('*');

        $this->db->where('status', $status);

        $query = $this->db->get($this->table_name);

        return $query->row();
    }

    /**
     * Retourne une liste d'invitations sur la base de leurs statut (1 ou 0)
     * @param string $status
     * @return array tableau représentant le résultat sql
     */
    function getInvitByStatus($status) {
        $this->db->select('*');

        $this->db->where('ok', $status);

        $query = $this->db->get($this->table_name2);

        return $query->row();
    }

    /**
     * Supprime une participation
     * @param int $id
     */
    function deletePart($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_name);
    }

    /**
     * Supprime une invitation
     * @param int $id
     */
    function annulerInvit($idCren) {
        $data = array("ok" => -1);
        $this->db->where('idCren',$idCren);
        $this->db->where('ok IS NULL');
        $this->db->set($data);
        //echo $this->db->get_compiled_update($this->table_name2);
        $this->db->update($this->table_name2);
    }

    /**
     * Supprime une invitation
     * @param int $id
     */
    function deleteInvit($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_name2);
    }

    /**
     * Retourne toutes les participations
     * @return array correspond au résult sql
     */
    function getAllPart() {
        $this->db->select('*');
        $query = $this->db->get($this->table_name);
        return $query->result();
    }

    /**
     * Retourne toutes les invitations
     * @return array correspond au résult sql
     */
    function getAllInvit() {
        $this->db->select('*');
        $query = $this->db->get($this->table_name2);
        return $query->result();
    }

    /**
     * Ajoute une participation
     * @param stdclass $part
     * @return int l'id
     */
    function addPart($part) {
        $this->db->set($part);
        $this->db->insert($this->table_name);
        return $this->db->insert_id();
    }

    /**
     * Ajoute une invitation
     * @param stdclass $part
     * @return int l'id
     */
    function addInvit($invit) {
        $this->db->set($invit);
        $this->db->insert($this->table_name2);
        return $this->db->insert_id();
    }

    /**
     * Affecte le status d'une participation
     * @param int $status
     * @param int $id
     */
    function setStatusPart($status,$id) {
        $data = array("status" => $status);
        $this->db->where('id',$id);
        $this->db->update($this->table_name, $data);
    }

    /**
     * Affecte le status d'une invitation
     * @param int $status
     */
    function setStatusInv($status,$id) {
        $data = array("ok" => $status);
        $this->db->where('id',$id);
        $this->db->update($this->table_name2, $data);
    }

    /**
     * Retourne toutes les invitations d'un utilisateur
     * @param int $user
     * @return array un tableau d'objets invitations (date+heure+status)
     */
    function getAllInvitByUser($user) {
        $this->db->select($this->table_cren_date . '.date,' . $this->table_cren_date . '.heure,' . $this->table_name2 . '.ok,'.$this->table_lieu . '.intitule,'.$this->table_name2 . '.id');
        $this->db->join($this->table_cren_date, $this->table_name2 . '.idCren = ' . $this->table_cren_date . '.id');
        $this->db->join($this->table_cren, $this->table_cren . '.id = ' . $this->table_cren_date . '.idCreneau');
        $this->db->join($this->table_lieu, $this->table_cren . '.idLieu = ' . $this->table_lieu . '.id');
        $this->db->where('idUtil', $user);
        $this->db->where('ok is null OR ok =-1');
        $this->db->where($this->table_cren_date . '.date >= ', date('Y-m-d'));
        $query = $this->db->get($this->table_name2);
        $invits = array();
        foreach ($query->result() as $row) {
            $invit = new stdClass;
            $invit->date = strtotime($row->date);
            $invit->heure = $row->heure;
            $invit->status = $row->ok;
            $invit->intitule = $row->intitule;
            $invit->id = $row->id;
            $invits[] = $invit;
        }

        return $invits;
    }

    /**
     * Retourne la liste complète des participations d'un utilisateur
     * @param int $user
     * @return array stdClass un tableau d'objets participations
     */
    function getAllPartByUser($user) {
        $this->db->select($this->table_cren_date . '.date,' . $this->table_cren_date . '.heure,' . $this->table_cren_date . '.id');
        $this->db->join($this->table_cren_date, $this->table_name . '.idCren = ' . $this->table_cren_date . '.id');
        $this->db->where('idUtil', $user);
        $this->db->where('status =1');
        $query = $this->db->get($this->table_name);

        $parts = array();
        foreach ($query->result() as $row) {
            $part = new stdClass;
            $part->date = strtotime($row->date);
            $part->heure = $row->heure;
            $part->id = $row->id;
            $parts[] = $part;
        }

        return $parts;
    }

    /**
     * Retourne la liste complète des participations d'un utilisateur
     * @param int $user
     * @return array stdClass un tableau d'objets participations
     */
    function getPartsByMonth($month) {
        $this->db->select('COUNT(rout_creneau_date.date) as nb,nom,COUNT(rout_rapport.id ) as nbRap,sum(COALESCE(qte,0)) as nbPub,sum(COALESCE(renc.nb,0)) as nbRenc');
        $this->db->join($this->table_cren_date, 'rout_part.idCren = ' . $this->table_cren_date . '.id');
        $this->db->join($this->table_cren, $this->table_cren . '.id = ' . $this->table_cren_date . '.idCreneau');
        //$this->db->join($this->table_conc, $this->table_cren . '.id = ' . $this->table_conc . '.idCren');
        $this->db->join($this->table_cong, $this->table_cren . '.idCong = ' . $this->table_cong . '.id');
        $this->db->join($this->table_rap, $this->table_cren_date . '.id = ' . $this->table_rap . '.idCren','left');
        $this->db->join("(SELECT SUM(qte) as qte, idRap FROM `rout_placement` GROUP BY idRap) plac", 'plac.idRap = ' . $this->table_rap . '.id','left');
        $this->db->join("(SELECT SUM(nb) as nb, idRap FROM `rout_rencontres` GROUP BY idRap) renc", 'renc.idRap = ' . $this->table_rap . '.id','left');
        $this->db->where('MONTH(rout_creneau_date.`date`)', $month);
        $this->db->group_by($this->table_cong.'.id,'.$this->table_cong.'.nom');
        $query = $this->db->get(" (SELECT idCren FROM `rout_participation` GROUP BY idCren) rout_part");

        //echo $this->db->last_query();

        return $query->result();
    }

    /**
     * Retourne la liste des dates auxquelles l'utilisateur a eu une participation
     * @param int $user
     * @return array un tableau de dates
     */
    function getPastPartByUser($user) {
        $this->db->select($this->table_cren_date . '.date');
        $this->db->join($this->table_cren_date, $this->table_name . '.idCren = ' . $this->table_cren_date . '.id');
        $this->db->where('idUtil', $user);
        $this->db->where($this->table_cren_date . '.date <= ', date('Y-m-d'));
        $this->db->order_by($this->table_cren_date . '.date', 'DESC');
        $query = $this->db->get($this->table_name);

        $dates = array();

        foreach ($query->result() as $row) {
            $dates[] = $row->date;
        }

        return $dates;
    }

    /**
     * Retourn la liste des participations passées d'un utilisateur
     * @param int $user
     * @return array le result sql
     */
    function getPastPartCompleteByUser($user) {
        $this->db->select('*,' . $this->table_name . '.id AS idPart');
        $this->db->join($this->table_cren_date, $this->table_name . '.idCren = ' . $this->table_cren_date . '.id');
        $this->db->where('idUtil', $user);
        $this->db->where('('.$this->table_cren_date . '.date < '."'". date('Y-m-d')."' OR ($this->table_cren_date.date = '".date('Y-m-d')."' AND rout_creneau_date.heure+1<'".date('H')."'))");
        $this->db->order_by($this->table_cren_date . '.date', 'DESC');
        $query = $this->db->get($this->table_name);

        return $query->result();
    }

    /**
     * Retourn la liste des participations passées d'une assemblee
     * @param int $user
     * @return array le result sql
     */
    function getPastPartCompleteByCong($cong) {
        $this->db->select($this->table_cren_date . '.date,'.$this->table_cren_date . '.id,'.$this->table_cren_date . '.idCreneau');
        $this->db->join($this->table_cren_date, $this->table_name . '.idCren = ' . $this->table_cren_date . '.id');
        $this->db->join($this->table_cren, $this->table_cren_date . '.idCreneau = ' . $this->table_cren . '.id','left');
        $this->db->where("(idCong=$cong OR idCongSeul=$cong)");
        $this->db->where('annule', 0);
        $this->db->where($this->table_cren_date . '.date <= ', date('Y-m-d'));
        $this->db->group_by($this->table_cren_date . '.date,'.$this->table_cren_date . '.id', 'DESC');
        $this->db->order_by($this->table_cren_date . '.id', 'DESC');
        $query = $this->db->get($this->table_name);
        
        return $query->result();
    }

    /**
     * Retourne la reponse a la question : L'assemblee a-t-elle participe la semaine precedente ?
     * @param int $user
     * @return array le result sql
     */
    function hasPartCompleteByCong($cong) {
        $this->db->select($this->table_cren_date . '.date,'.$this->table_cren_date . '.id,'.$this->table_cren_date . '.idCreneau');
        $this->db->join($this->table_cren_date, $this->table_name . '.idCren = ' . $this->table_cren_date . '.id');
        $this->db->join($this->table_cren, $this->table_cren_date . '.idCreneau = ' . $this->table_cren . '.id','left');
        $this->db->where("(idCong=$cong OR idCongSeul=$cong)");
        $this->db->where('annule', 0);
        $this->db->where($this->table_cren_date . '.date >= ', date('Y-m-d',strtotime("last Monday -7 days")));
        $this->db->where($this->table_cren_date . '.date <= ', date('Y-m-d',strtotime("last Monday -1 days")));
        $this->db->group_by($this->table_cren_date . '.date,'.$this->table_cren_date . '.id', 'DESC');
        $this->db->order_by($this->table_cren_date . '.date', 'DESC');
        $query = $this->db->get($this->table_name);

        return sizeof($query->result())>0;
    }

    function getPastRapNoCrenByCong($cong){
        $this->db->select($this->table_cren_date . '.date,'.$this->table_cren_date . '.id,'.$this->table_cren_date . '.idCreneau');
        $this->db->join($this->table_rap,$this->table_rap.'.idCren = '.$this->table_cren_date.'.id');
        $this->db->where('idCongSeul IS NOT NULL');
        $this->db->where('idCongSeul', $cong);
        $this->db->where('annule', 0);
        $this->db->group_by($this->table_cren_date . '.date,'.$this->table_cren_date . '.id', 'DESC');
        $this->db->order_by($this->table_cren_date . '.date', 'DESC');
        $query = $this->db->get($this->table_cren_date);

        return $query->result();
    }

    /**
     * Retourne le nombre total de participations d'un utilisateur
     * @param int $user
     * @return une ligne sql
     */
    function getTotPastPartByUser($user) {
        $this->db->select('COUNT(*) AS Nb');
        $this->db->join($this->table_cren_date, $this->table_name . '.idCren = ' . $this->table_cren_date . '.id');
        $this->db->where('idUtil', $user);
        $this->db->where($this->table_cren_date . '.date <= ', date('Y-m-d'));
        $query = $this->db->get($this->table_name);

        return $query->row();
    }

    /**
     * Retourne le nombre total de participations d'une assemblee
     * @param int $cong
     * @return une ligne sql
     */
    function getTotPastPartByCong($cong) {
        $this->db->select('COUNT(*) AS Nb');
        $this->db->join($this->table_cren_date, $this->table_name . '.idCren = ' . $this->table_cren_date . '.id');
        $this->db->join($this->table_cren, $this->table_cren_date . '.idCreneau = ' . $this->table_cren . '.id','left');
        $this->db->where("(idCong=$cong OR idCongSeul=$cong)");
        $this->db->where('annule', 0);
        $this->db->where($this->table_cren_date . '.date <= ', date('Y-m-d'));
        $this->db->group_by($this->table_cren_date . '.date,'.$this->table_cren_date . '.id', 'DESC');
        $query = $this->db->get($this->table_name);
        return sizeof($query->result());
    }

    /**
     * Retourne le nombre total de participations par assemblée
     * @param int $cong
     * @return une ligne sql
     */
    function getTotPastPartCongs() {
        $start_date=date('m')<9?(date('Y')-1).'-09-01':(date('Y')).'-09-01';
        $this->db->select('COUNT(*)-1 AS Nb,'.$this->table_cong.'.id,'.$this->table_cong.'.nom');
        $this->db->join($this->table_conc, $this->table_cong . '.id = ' . $this->table_conc . '.idCong','left');
        $this->db->join($this->table_cren, $this->table_conc . '.idCren = ' . $this->table_cren.'.id','left');
        $this->db->join($this->table_cren_date, $this->table_cren . '.id = ' . $this->table_cren_date.'.idCreneau','left');
        $this->db->join($this->table_name, $this->table_cren_date . '.id = ' . $this->table_name.'.idCren');
        $this->db->where($this->table_cren_date . '.date <= ', date('Y-m-d'));
        $this->db->where($this->table_cren_date . '.date >= ', $start_date);
        $this->db->or_where($this->table_cren_date . '.date IS NULL ');
        $this->db->group_by($this->table_cong.'.nom,'.$this->table_cong.'.id');

        $query = $this->db->get($this->table_cong);

        return $query->result();
    }

    /**
     * Retourne le nombre total de participations par assemblée
     * @param int $cong
     * @return une ligne sql
     */
    function getTotPastPartCongsNoCren() {
        $start_date=date('m')<9?(date('Y')-1).'-09-01':(date('Y')).'-09-01';
        $this->db->select('COUNT(*)-1 AS Nb,'.$this->table_cong.'.id,'.$this->table_cong.'.nom');
        $this->db->join($this->table_cren_date, $this->table_cren_date . '.idCongSeul = ' . $this->table_cong.'.id','left');
        $this->db->where($this->table_cren_date . '.date <= ', date('Y-m-d'));
        $this->db->where($this->table_cren_date . '.date >= ', $start_date);
        $this->db->or_where($this->table_cren_date . '.date IS NULL ');
        $this->db->group_by($this->table_cong.'.nom,'.$this->table_cong.'.id');

        $query = $this->db->get($this->table_cong);
        return $query->result();
    }

    /**
     * Retourne le nombre total de participations par assemblée
     * @param int $cong
     * @return une ligne sql
     */
    function getTotPastPartNoCrenByCongs($idCong) {
        $this->db->select('COUNT(*)-1 AS Nb,'.$this->table_cong.'.id,'.$this->table_cong.'.nom');
        $this->db->join($this->table_cren_date, $this->table_cren_date . '.idCongSeul = ' . $this->table_cong.'.id','left');
        $this->db->where($this->table_cren_date . '.date <= ', date('Y-m-d'));
        $this->db->where($this->table_cong.'.id',$idCong);
        $this->db->or_where($this->table_cren_date . '.date IS NULL ');
        $this->db->where($this->table_cong.'.id',$idCong);

        $query = $this->db->get($this->table_cong);

        return $query->result();
    }

    /**
     * Retourne le nombre total de participations
     * @return une ligne sql
     * NE FONCTIONNE PAS
     */
    function getTotPastPart() {
        $this->db->select('COUNT(*)-1 AS Nb');
        $this->db->join($this->table_conc, $this->table_cong . '.id = ' . $this->table_conc . '.idCong','left');
        $this->db->join($this->table_cren, $this->table_conc . '.idCren = ' . $this->table_cren.'.id','left');
        $this->db->join($this->table_cren_date, $this->table_cren . '.id = ' . $this->table_cren_date.'.idCreneau','left');
        $this->db->where($this->table_cren_date . '.date <= ', date('Y-m-d'));
        $this->db->or_where($this->table_cren_date . '.date IS NULL ');
        /*
        $this->db->select('COUNT(*) AS Nb');
        $this->db->join($this->table_cren_date, $this->table_name . '.idCren = ' . $this->table_cren_date . '.id');
        $this->db->where($this->table_cren_date . '.date <= ', date('Y-m-d'));*/
        $query = $this->db->get($this->table_cong);

        return $query->row();
    }

    /**
     * Retourne le nombre total de participations
     * @return une ligne sql
     * NE FONCTIONNE PAS
     */
    function getTotPastPartNoCren() {
        $this->db->select('COUNT(*)-1 AS Nb,'.$this->table_cong.'.id,'.$this->table_cong.'.nom');
        $this->db->join($this->table_cren_date, $this->table_cren_date . '.idCongSeul = ' . $this->table_cong.'.id','left');
        $this->db->where($this->table_cren_date . '.date <= ', date('Y-m-d'));
        $this->db->or_where($this->table_cren_date . '.date IS NULL ');
        /*
        $this->db->select('COUNT(*) AS Nb');
        $this->db->join($this->table_cren_date, $this->table_name . '.idCren = ' . $this->table_cren_date . '.id');
        $this->db->where($this->table_cren_date . '.date <= ', date('Y-m-d'));*/
        $query = $this->db->get($this->table_cong);

        return $query->row();
    }

    /**
     * Retourne la liste avec quantités des langues rencontrées par un utilisateur au cours de ses participations
     * @param int $user
     * @return array le result sql
     */
    function getLanguesRencontreesByUser($user) {
        $this->db->select('rout_langue.intitule,SUM(nb) AS Nb');
        $this->db->join($this->table_cren_date, $this->table_name . '.idCren = ' . $this->table_cren_date . '.id');
        $this->db->join('rout_rapport', $this->table_cren_date . '.id = rout_rapport.idCren');
        $this->db->join('rout_rencontres', 'rout_rapport.id = rout_rencontres.idRap');
        $this->db->join('rout_langue', 'rout_rencontres.idLang = rout_langue.id');
        $this->db->where('idUtil', $user);
        $this->db->where($this->table_cren_date . '.date <= ', date('Y-m-d'));
        $this->db->order_by('Nb', 'DESC');
        $this->db->group_by("rout_langue.intitule");
        $query = $this->db->get($this->table_name);

        return $query->result();
    }

    /**
     * Retourne la liste avec quantités des langues rencontrées par une assemblee au cours de ses participations
     * @param int $cong
     * @return array le result sql
     */
    function getLanguesRencontreesByCong($cong) {
        $this->db->select('rout_langue.intitule,SUM(rout_rencontres.nb) AS Nb');
        $this->db->join($this->table_cren_date, $this->table_name . '.idCren = ' . $this->table_cren_date . '.id');
        $this->db->join($this->table_cren, $this->table_cren_date . '.idCreneau = ' . $this->table_cren . '.id','left');
        $this->db->join('rout_rapport', $this->table_cren_date . '.id = rout_rapport.idCren');
        $this->db->join('rout_rencontres', 'rout_rapport.id = rout_rencontres.idRap');
        $this->db->join('rout_langue', 'rout_rencontres.idLang = rout_langue.id');
        $this->db->where("(idCong=$cong OR idCongSeul=$cong)");
        $this->db->where('annule', 0);
        $this->db->where($this->table_cren_date . '.date <= ', date('Y-m-d'));
        $this->db->order_by('Nb', 'DESC');
        $this->db->group_by("rout_langue.intitule");

        $query = $this->db->get($this->table_name);

        return $query->result();
    }

    /**
     * Retourne la liste avec quantités des langues rencontrées
     * @param int $cong
     * @return array le result sql
     */
    function getLanguesRencontrees() {
        $start_date=date('m')<9?(date('Y')-1).'-09-01':(date('Y')).'-09-01';
        $this->db->select('rout_langue.intitule,SUM(nb) AS Nb');
        $this->db->join($this->table_cren_date, $this->table_name . '.idCren = ' . $this->table_cren_date . '.id');
        $this->db->join('rout_rapport', $this->table_cren_date . '.id = rout_rapport.idCren');
        $this->db->join('rout_rencontres', 'rout_rapport.id = rout_rencontres.idRap');
        $this->db->join('rout_langue', 'rout_rencontres.idLang = rout_langue.id');
        $this->db->where($this->table_cren_date . '.date <= ', date('Y-m-d'));
        $this->db->where($this->table_cren_date . '.date >= ', $start_date);
        $this->db->order_by('Nb', 'DESC');
        $this->db->group_by("rout_langue.intitule");

        $query = $this->db->get($this->table_name);

        return $query->result();
    }

    /**
     * Retourne le nombre total de langues rencontrées par un utilisateur au cours de ses participations
     * @param int $user
     * @return array une ligne sql
     */
    function getTotLangRencontreesByUser($user) {
        $this->db->select('SUM(nb) AS Nb');
        $this->db->join($this->table_cren_date, $this->table_name . '.idCren = ' . $this->table_cren_date . '.id');
        $this->db->join('rout_rapport', $this->table_cren_date . '.id = rout_rapport.idCren');
        $this->db->join('rout_rencontres', 'rout_rapport.id = rout_rencontres.idRap');
        $this->db->join('rout_langue', 'rout_rencontres.idLang = rout_langue.id');
        $this->db->where('idUtil', $user);
        $this->db->where($this->table_cren_date . '.date <= ', date('Y-m-d'));
        $query = $this->db->get($this->table_name);

        return $query->row();
    }

    /**
     * Retourne le nombre total de langues rencontrées par une assemblee au cours de ses participations
     * @param int $cpng
     * @return array une ligne sql
     */
    function getTotLangRencontreesByCong($cong) {
        $this->db->select('SUM(rout_rencontres.nb) AS Nb');
        $this->db->join($this->table_cren_date, $this->table_name . '.idCren = ' . $this->table_cren_date . '.id');
        $this->db->join($this->table_cren, $this->table_cren_date . '.idCreneau = ' . $this->table_cren . '.id','left');
        $this->db->join('rout_rapport', $this->table_cren_date . '.id = rout_rapport.idCren');
        $this->db->join('rout_rencontres', 'rout_rapport.id = rout_rencontres.idRap');
        $this->db->join('rout_langue', 'rout_rencontres.idLang = rout_langue.id');
        $this->db->where("(idCong=$cong OR idCongSeul=$cong)");
        $this->db->where('annule', 0);
        $this->db->where($this->table_cren_date . '.date <= ', date('Y-m-d'));

        $query = $this->db->get($this->table_name);

        return $query->row();
    }

    /**
     * Retourne le nombre total de langues rencontrées
     * @param int $cpng
     * @return array une ligne sql
     */
    function getTotLangRencontrees() {
        $start_date=date('m')<9?(date('Y')-1).'-09-01':(date('Y')).'-09-01';
        $this->db->select('SUM(nb) AS Nb');
        $this->db->join($this->table_cren_date, $this->table_name . '.idCren = ' . $this->table_cren_date . '.id');
        $this->db->join('rout_rapport', $this->table_cren_date . '.id = rout_rapport.idCren');
        $this->db->join('rout_rencontres', 'rout_rapport.id = rout_rencontres.idRap');
        $this->db->join('rout_langue', 'rout_rencontres.idLang = rout_langue.id');
        $this->db->where($this->table_cren_date . '.date <= ', date('Y-m-d'));
        $this->db->where($this->table_cren_date . '.date >= ', $start_date);
        $query = $this->db->get($this->table_name);
        return $query->row();
    }

    /**
     * Retourne le nombre total de langues rencontrées par une assemblee au cours de ses participations
     * @param int $cpng
     * @return array une ligne sql
     */
    function getTotRapportByCong($cong) {
        $this->db->select('COUNT(DISTINCT rout_rapport.idCren) AS Nb');
        $this->db->join($this->table_cren_date, $this->table_name . '.idCren = ' . $this->table_cren_date . '.id');
        $this->db->join($this->table_cren, $this->table_cren_date . '.idCreneau = ' . $this->table_cren . '.id','left');
        $this->db->join('rout_rapport', $this->table_cren_date . '.id = rout_rapport.idCren');
        $this->db->where("(idCong=$cong OR idCongSeul=$cong)");
        $this->db->where('annule', 0);
        $this->db->where($this->table_cren_date . '.date <= ', date('Y-m-d'));

        $query = $this->db->get($this->table_name);

        return $query->row();
    }

    /**
     * Retourne le nombre total de langues rencontrées par une assemblee au cours de ses participations
     * @param int $cpng
     * @return array une ligne sql
     */
    function getTotRapport() {
        $start_date=date('m')<9?(date('Y')-1).'-09-01':(date('Y')).'-09-01';
        $this->db->select('COUNT(*) AS Nb');
        $this->db->join($this->table_cren_date, $this->table_name . '.idCren = ' . $this->table_cren_date . '.id');
        $this->db->join('rout_rapport', $this->table_cren_date . '.id = rout_rapport.idCren');
        $this->db->where($this->table_cren_date . '.date <= ', date('Y-m-d'));
        $this->db->where($this->table_cren_date . '.date >= ', $start_date);
        $query = $this->db->get($this->table_name);

        return $query->row();
    }

    /**
     * Retourne la liste des publications laissées par un utilisateur avec quantités
     * @param int $user
     * @return array le result sql
     */
    function getPubLaisseesByUser($user) {
        $this->db->select('rout_publication.titre,SUM(qte) AS Nb');
        $this->db->join($this->table_cren_date, $this->table_name . '.idCren = ' . $this->table_cren_date . '.id');
        $this->db->join('rout_rapport', $this->table_cren_date . '.id = rout_rapport.idCren');
        $this->db->join('rout_placement', 'rout_rapport.id = rout_placement.idRap');
        $this->db->join('rout_publication', 'rout_placement.idPub = rout_publication.id');
        $this->db->where('idUtil', $user);
        $this->db->where($this->table_cren_date . '.date <= ', date('Y-m-d'));
        $this->db->order_by('Nb', 'DESC');
        $this->db->group_by("rout_publication.titre");
        $query = $this->db->get($this->table_name);

        return $query->result();
    }

    /**
     * Retourne la liste des publications laissées par une assemblée avec quantités
     * @param int $cong
     * @return array le result sql
     */
    function getPubLaisseesByCong($cong) {
        $this->db->select('rout_publication.titre,SUM(qte) AS Nb');

        $this->db->join($this->table_cren_date, $this->table_name . '.idCren = ' . $this->table_cren_date . '.id');
        $this->db->join($this->table_cren, $this->table_cren_date . '.idCreneau = ' . $this->table_cren . '.id','left');
        $this->db->join('rout_rapport', $this->table_cren_date . '.id = rout_rapport.idCren');
        $this->db->join('rout_placement', 'rout_rapport.id = rout_placement.idRap');
        $this->db->join('rout_publication', 'rout_placement.idPub = rout_publication.id');
        $this->db->where("(idCong=$cong OR idCongSeul=$cong)");
        $this->db->where('annule', 0);
        $this->db->where($this->table_cren_date . '.date <= ', date('Y-m-d'));

//        $this->db->join($this->table_cren_date, $this->table_name . '.idCren = ' . $this->table_cren_date . '.id');
//        $this->db->join('rout_rapport', $this->table_cren_date . '.id = rout_rapport.idCren');
//        $this->db->join('rout_placement', 'rout_rapport.id = rout_placement.idRap');
//        $this->db->join('rout_publication', 'rout_placement.idPub = rout_publication.id');
//        $this->db->join('rout_utilisateur', $this->table_name.'.idUtil = rout_utilisateur.id');
//        $this->db->where('idCong', $cong);
//        $this->db->where($this->table_cren_date . '.date <= ', date('Y-m-d'));
        $this->db->order_by('Nb', 'DESC');
        $this->db->group_by("rout_publication.titre");
        $query = $this->db->get($this->table_name);

        return $query->result();
    }

    /**
     * Retourne la liste des publications laissées avec quantités
     * @param int $cong
     * @return array le result sql
     */
    function getPubLaissees() {
        $start_date=date('m')<9?(date('Y')-1).'-09-01':(date('Y')).'-09-01';
        $this->db->select('rout_publication.titre,SUM(qte) AS Nb');
        $this->db->join($this->table_cren_date, $this->table_name . '.idCren = ' . $this->table_cren_date . '.id');
        $this->db->join('rout_rapport', $this->table_cren_date . '.id = rout_rapport.idCren');
        $this->db->join('rout_placement', 'rout_rapport.id = rout_placement.idRap');
        $this->db->join('rout_publication', 'rout_placement.idPub = rout_publication.id');
        $this->db->where($this->table_cren_date . '.date <= ', date('Y-m-d'));
        $this->db->where($this->table_cren_date . '.date >= ', $start_date);
        $this->db->order_by('Nb', 'DESC');
        $this->db->group_by("rout_publication.titre");
        $query = $this->db->get($this->table_name);

        //echo $this->db->last_query();

        return $query->result();
    }

    /**
     * Retourne le nombre de publications laissées par un utilisateur au cours de ses participations
     * @param int $user
     * @return array une ligne sql
     */
    function getTotPubLaisseesByUser($user) {
        $this->db->select('SUM(rout_placement.qte) AS Nb');
        $this->db->join($this->table_cren_date, $this->table_name . '.idCren = ' . $this->table_cren_date . '.id');
        $this->db->join('rout_rapport', $this->table_cren_date . '.id = rout_rapport.idCren');
        $this->db->join('rout_placement', 'rout_rapport.id = rout_placement.idRap');
        $this->db->join('rout_publication', 'rout_placement.idPub = rout_publication.id');
        $this->db->where('idUtil', $user);
        $this->db->where($this->table_cren_date . '.date <= ', date('Y-m-d'));
        $query = $this->db->get($this->table_name);

        return $query->row();
    }

    /**
     * Retourne le nombre de publications laissées par une assemblee au cours de ses participations
     * @param int $cong
     * @return array une ligne sql
     */
    function getTotPubLaisseesByCong($cong) {
        $this->db->select('SUM(rout_placement.qte) AS Nb');
        $this->db->join($this->table_cren_date, $this->table_name . '.idCren = ' . $this->table_cren_date . '.id');
        $this->db->join($this->table_cren, $this->table_cren_date . '.idCreneau = ' . $this->table_cren . '.id','left');
        $this->db->join('rout_rapport', $this->table_cren_date . '.id = rout_rapport.idCren');
        $this->db->join('rout_placement', 'rout_rapport.id = rout_placement.idRap');
        $this->db->join('rout_publication', 'rout_placement.idPub = rout_publication.id');
        $this->db->where("(idCong=$cong OR idCongSeul=$cong)");
        $this->db->where('annule', 0);
        $this->db->where($this->table_cren_date . '.date <= ', date('Y-m-d'));
        $query = $this->db->get($this->table_name);

        return $query->row();
    }

    /**
     * Retourne le nombre de publications laissées
     * @return array une ligne sql
     */
    function getTotPubLaissees() {
        $start_date=date('m')<9?(date('Y')-1).'-09-01':(date('Y')).'-09-01';
        $this->db->select('SUM(rout_placement.qte) AS Nb');
        $this->db->join($this->table_cren_date, $this->table_name . '.idCren = ' . $this->table_cren_date . '.id');
        $this->db->join('rout_rapport', $this->table_cren_date . '.id = rout_rapport.idCren');
        $this->db->join('rout_placement', 'rout_rapport.id = rout_placement.idRap');
        $this->db->join('rout_publication', 'rout_placement.idPub = rout_publication.id');
        $this->db->where($this->table_cren_date . '.date <= ', date('Y-m-d'));
        $this->db->where($this->table_cren_date . '.date >= ', $start_date);
        $query = $this->db->get($this->table_name);

        return $query->row();
    }

    /**
     * Retourne les participations liées à un créneau
     * @param int $idCren
     * @return array
     */
    function getPartByCren($idCren){
        $this->db->select('*,'.$this->table_name.'.id as idPart');

        $this->db->join($this->table_user,$this->table_user.'.id = '.$this->table_name.'.idUtil');
        $this->db->where('idCren', $idCren);

        $query = $this->db->get($this->table_name);

        return $query->result();
    }

    /**
     * Retourne la participation d'un utilisateur liée à un créneau
     * @param int $idCren
     * @return array
     */
    function getPartByCrenUtil($idCren,$idUtil){
        $this->db->select('*,'.$this->table_name.'.id as idPart');

        $this->db->join($this->table_user,$this->table_user.'.id = '.$this->table_name.'.idUtil');
        $this->db->where('idCren', $idCren);
        $this->db->where('idUtil', $idUtil);

        $query = $this->db->get($this->table_name);

        return $query->row();
    }

    /**
     * Retourne les invitation liées à un créneau
     * @param int $idCren
     * @return array
     */
    function getInvitationByCren($idCren){
        $this->db->select('*');
    	$this->db->join($this->table_name,$this->table_name2.'.idCren = '.$this->table_name.'.idCren','LEFT');
        $this->db->join($this->table_user,$this->table_user.'.id = '.$this->table_name2.'.idUtil');
		
        $this->db->where($this->table_name.'.idCren', $idCren);
        $this->db->order_by('ok');

        $query = $this->db->get($this->table_name2);

	echo $this->db->last_query();
	    
        //return $query->result();
    }

    /**
     * Retourne les invitation valides (acceptées ou en attente) liées à un créneau
     * @param int $idCren
     * @return array
     */
    function getInvitationValByCren($idCren){
        $this->db->select('*');
        $this->db->join($this->table_user,$this->table_user.'.id = '.$this->table_name2.'.idUtil');
        $this->db->where('idCren', $idCren);
        $this->db->where('ok IS NULL');
        $this->db->order_by('ok');

        $query = $this->db->get($this->table_name2);

        return $query->result();
    }

    /**
     * Retourne les invitation sans réponse liées à un créneau
     * @param int $idCren
     * @return array
     */
    function getInvitationNullByCren($idCren){
        $this->db->select('*,'.$this->table_name2.'.id as idInv');
        $this->db->join($this->table_user,$this->table_user.'.id = '.$this->table_name2.'.idUtil');
        $this->db->where('idCren', $idCren);
        $this->db->where('ok IS NULL');

        $query = $this->db->get($this->table_name2);

        return $query->result();
    }

    function aRefuseInvit($idCren,$idUtil){
        $this->db->select('*');
        $this->db->where('idCren',$idCren);
        $this->db->where('idUtil',$idUtil);
        $query = $this->db->get($this->table_name2);

        if(sizeof($query->result())>0)
            return true;
        else
            return false;
    }
}
