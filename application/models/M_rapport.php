<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Modèle rapport
 * @author	Manoah VERDIER
 */
class M_rapport extends CI_Model { 

    private $table_name  = 'rapport';
    private $table_name2 = 'placement';
    private $table_name3 = 'publication';
    private $table_name4 = 'langue';
    private $table_name5 = 'rencontres';
    private $table_name6 = 'faits';
    private $table_part  = 'participation';
    private $table_cren  = 'creneau_date';

    function __construct() {
        parent::__construct();

        $ci = & get_instance();
        $this->table_name = $ci->config->item('db_table_prefix') . $this->table_name;
        $this->table_name2 = $ci->config->item('db_table_prefix') . $this->table_name2;
        $this->table_name3 = $ci->config->item('db_table_prefix') . $this->table_name3;
        $this->table_name4 = $ci->config->item('db_table_prefix') . $this->table_name4;
        $this->table_name5 = $ci->config->item('db_table_prefix') . $this->table_name5;
        $this->table_name6 = $ci->config->item('db_table_prefix') . $this->table_name6;

        $this->table_part = $ci->config->item('db_table_prefix') . $this->table_part;
        $this->table_cren = $ci->config->item('db_table_prefix') . $this->table_cren;
    }

    /**
     * Retourne le nombre total de rapports
     * @return type
     */
    function countAll() {
        return $this->db->count_all($this->table_name);
    }

    /**
     * Retourne les placements d'un rapport
     * @param int $id
     * @return array le result sql
     */
    function getPlacs($id) {
        $this->db->select($this->table_name2 . '.*,' . $this->table_name3 . '.*,' . $this->table_name4 . '.*');
        $this->db->join($this->table_name4, $this->table_name2 . '.idLang = ' . $this->table_name4 . '.id');
        $this->db->join($this->table_name3, $this->table_name2 . '.idPub = ' . $this->table_name3 . '.id');
        $this->db->where($this->table_name2 . '.idRap', $id);

        $query = $this->db->get($this->table_name2);

        return $query->result();
    }
    
    /**
     * Retourne les placements d'un rapport
     * @param int $id
     * @return array le result sql
     */
    function getPlacsLng($id,$idLang) {
        $this->db->select($this->table_name2 . '.*,' . $this->table_name3 . '.*,' . $this->table_name4 . '.*');
        $this->db->join($this->table_name4, $this->table_name2 . '.idLang = ' . $this->table_name4 . '.id');
        $this->db->join($this->table_name3, $this->table_name2 . '.idPub = ' . $this->table_name3 . '.id');
        $this->db->where($this->table_name2 . '.idRap', $id);
        $this->db->where($this->table_name2 . '.idLang', $idLang);

        $query = $this->db->get($this->table_name2);

        return $query->result();
    }
    
    /**
     * Retourne les faits d'un rapport
     * @param int $id
     * @return array le result sql
     */
    function getFaits($id) {
        $this->db->select( $this->table_name6 . '.*,' . $this->table_name4 . '.*');
        $this->db->join($this->table_name6, $this->table_name4 . '.id = ' . $this->table_name6 . '.idLang');
        
        $this->db->where($this->table_name6 . '.idRap', $id);

        $query = $this->db->get($this->table_name4);

        return $query->result();
    }
    
    /**
     * Retourne les faits d'un rapport pour une langue
     * @param int $id
     * @return array le result sql
     */
    function getFaitsLng($id,$idLang) {
        $this->db->select( $this->table_name6 . '.*,' . $this->table_name4 . '.*');
        $this->db->join($this->table_name6, $this->table_name4 . '.id = ' . $this->table_name6 . '.idLang');
        $this->db->where($this->table_name6 . '.idRap', $id);
        $this->db->where($this->table_name6 . '.idLang', $idLang);
        $query = $this->db->get($this->table_name4);

        return $query->result();
    }

    function deleteRapportLng($idRap,$idLang){
        $this->db->where('idRap', $idRap);
        $this->db->where('idLang', $idLang);
        $this->db->delete($this->table_name2);
        
        $this->db->where('idRap', $idRap);
        $this->db->where('idLang', $idLang);
        $this->db->delete($this->table_name6);
        
        $this->db->where('idRap', $idRap);
        $this->db->where('idLang', $idLang);
        $this->db->delete($this->table_name5);
    }
    
    /**
     * Retourne les rencontres d'un rapport
     * @param int $id
     * @return array le result sql
     */
    function getRencs($id) {
        $this->db->select($this->table_name5 . '.*,' . $this->table_name4 . '.*');
        $this->db->join($this->table_name4, $this->table_name5 . '.idLang = ' . $this->table_name4 . '.id');
        $this->db->where($this->table_name5 . '.idRap', $id);

        $query = $this->db->get($this->table_name5);

        return $query->result();
    }

    /**
     * Retourne les utilisateurs d'un rapport
     * @param int $id
     * @return array le result sql
     */
    function getUtils($id) {
        $this->db->select($this->table_part . '.idUtil');
        $this->db->join($this->table_cren, $this->table_name . '.idCren = ' . $this->table_cren . '.id');
        $this->db->join($this->table_part, $this->table_cren . '.id = ' . $this->table_part . '.idCren');
        $this->db->where($this->table_name . '.id', $id);

        $query = $this->db->get($this->table_name);

        return $query->result();
    }

    /**
     * Retourne la participation correspondante d'un rapport
     * @param int $id
     * @return array la ligne sql
     */
    function getPart($id) {
        $this->db->select($this->table_part . '.*,' . $this->table_cren . '.*');
        $this->db->join($this->table_cren, $this->table_name . '.idCren = ' . $this->table_cren . '.id');
        $this->db->join($this->table_part, $this->table_cren . '.id = ' . $this->table_part . '.idCren');
        $this->db->where($this->table_name . '.id', $id);

        $query = $this->db->get($this->table_name);

        return $query->row();
    }
    
    /**
     * Retourne le creneau correspondant d'un rapport
     * @param int $id
     * @return array la ligne sql
     */
    function getCren($id) {
        $this->db->select( $this->table_cren . '.*');
        $this->db->join($this->table_cren, $this->table_name . '.idCren = ' . $this->table_cren . '.id');
        $this->db->where($this->table_name . '.id', $id);

        $query = $this->db->get($this->table_name);

        return $query->row();
    }

    /**
     * Retourne un rapport sur la base du id
     * @param int $id
     * @return array une ligne de base de données
     */
    function get($id) {
        $this->db->select($this->table_name . '.*');

        $this->db->where($this->table_name . '.id', $id);

        $query = $this->db->get($this->table_name);

        return $query->row();
    }

    /**
     * Retourne un rapport sur la base du id du créneau
     * @param int $id
     * @return array une ligne de base de données
     */
    function getByCren($id) {
        $this->db->select($this->table_name . '.*, ' . $this->table_name2 . '.*, ' . $this->table_name3 . '.*, ' . $this->table_name4 . '.*, ' . $this->table_name5 . '.*');

        $this->db->join($this->table_name2, $this->table_name2 . '.idRap = ' . $this->table_name . '.id');
        $this->db->join($this->table_name3, $this->table_name2 . '.idPub = ' . $this->table_name3 . '.id');
        $this->db->join($this->table_name4, $this->table_name2 . '.idLang = ' . $this->table_name4 . '.id');
        $this->db->join($this->table_name5, $this->table_name5 . '.idRap = ' . $this->table_name . '.id');

        $this->db->where($this->table_name . '.idCren', $id);

        $query = $this->db->get($this->table_name);

        return $query->row();
    }

    /**
    * Retourne un rapport (limité au contenu de la table principale) sur la base du id du créneau
    * @param int $id
    * @return array une ligne de base de données
    */
    function getIdByCren($id) {
        $this->db->select($this->table_name . '.*');

        $this->db->where($this->table_name . '.idCren', $id);

        $query = $this->db->get($this->table_name);
        
        return $query->row();
    }

    /**
     * Supprime un rapport et les placements et rencontres associés
     * @param int $id
     */
    function deleteRap($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_name);

        $this->db->where('idRap', $id);
        $this->db->delete($this->table_name2);
        
        $this->db->where('idRap', $id);
        $this->db->delete($this->table_name2);
    }

    /**
     * Supprime une publication
     * @param int $id
     */
    function deletePub($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_name3);
    }

    /**
     * Supprime une langue
     * @param int $id
     */
    function deleteLang($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_name4);
    }

    /**
     * Supprime une rencontre
     * @param int $id
     */
    function deleteRenc($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_name5);
    }

    /**
     * Retourne tous les rapports
     * @return array le result sql
     */
    function getAllRap() {
        $this->db->select($this->table_name . '.*, ' . $this->table_name2 . '.*, ' . $this->table_name3 . '.*, ' . $this->table_name4 . '.*, ' . $this->table_name5 . '.*');

        $this->db->join($this->table_name2, $this->table_name2 . '.idRap = ' . $this->table_name . '.id');
        $this->db->join($this->table_name3, $this->table_name2 . '.idPub = ' . $this->table_name3 . '.id');
        $this->db->join($this->table_name4, $this->table_name2 . '.idLang = ' . $this->table_name4 . '.id');
        $this->db->join($this->table_name5, $this->table_name2 . '.idRap = ' . $this->table_name . '.id');

        $query = $this->db->get($this->table_name);

        return $query->result();
    }

    /**
     * Retourne toutes les publications
     * @return array le result sql
     */
    function getAllPub() {
        $this->db->select('*');
        $this->db->order_by('type,titre');
        $query = $this->db->get($this->table_name3);
        return $query->result();
    }

    /**
     * Retourne toutes les langues
     * @return array le result sql
     */
    function getAllLang() {
        $this->db->select('*');
        $query = $this->db->get($this->table_name4);
        return $query->result();
    }

    /**
     * Retourne toutes les rencontres
     * @return array le result sql
     */
    function getAllRenc() {
        $this->db->select('*');
        $query = $this->db->get($this->table_name5);
        return $query->result();
    }

    /**
     * Ajoute un rapport
     * @param stdclass $rap
     * @return id
     */
    function addRap($rap) {
        $this->db->set($rap);
        $this->db->insert($this->table_name);
        return $this->db->insert_id();
    }

    /**
     * Ajoute un placement
     * @param stdclass $plac
     * @return id
     */
    function addPlac($plac) {
        $this->db->set($plac);
        $this->db->insert($this->table_name2);
        return $this->db->insert_id();
    }

    /**
     * Ajoute une publication
     * @param stdclass $pub
     * @return id
     */
    function addPub($pub) {
        $this->db->set($pub);
        $this->db->insert($this->table_name3);
        return $this->db->insert_id();
    }
    
    /**
     * Ajoute une publication
     * @param stdclass $pub
     * @return id
     */
    function addFait($fait) {
        $this->db->set($fait);
        $this->db->insert($this->table_name6);
        return $this->db->insert_id();
    }
    
    /**
     * Met à jour une publication
     * @param stdclass $pub
     */
    function majPub($pub,$id) {
        $this->db->set($pub);
        $this->db->where('id',$id);
        $this->db->update($this->table_name3);
    }
    
    /**
     * Met à jour une publication
     * @param stdclass $pub
     */
    function majLang($lang,$id) {
        $this->db->set($lang);
        $this->db->where('id',$id);
        $this->db->update($this->table_name4);
    }

    /**
     * Ajoute une rencontre
     * @param stdclass $renc
     * @return id
     */
    function addRenc($renc) {
        $this->db->set($renc);
        $this->db->insert($this->table_name5);
        return $this->db->insert_id();
    }

    /**
     * Ajoute une langue
     * @param stdclass $lang
     * @return id
     */
    function addLang($lang) {
        $this->db->set($lang);
        $this->db->insert($this->table_name4);
        return $this->db->insert_id();
    }

    /**
     * Indique si un rapport est manquant pour une participation
     * @param int $part
     * @return boolean
     */
    function isReportMissing($part) {
        $this->db->select('COUNT(*) AS Nb');
        $this->db->join('rout_creneau_date', $this->table_name . '.idCren = rout_creneau_date.id');
        $this->db->join('rout_participation', 'rout_participation' . '.idCren = rout_creneau_date.id');
        $this->db->where('rout_creneau_date.date < ', date('Y-m-d'));
        $this->db->where('rout_participation.id', $part);
        
        $query = $this->db->get($this->table_name);

        if ($query->row()->Nb === '0')
            return false;
        else
            return true;
    }
    
    /**
     * Indique si un rapport est manquant pour une participation
     * @param int $part
     * @return boolean
     */
    function isReportMissingByCren($cren) {
        $this->db->select('COUNT(*) AS Nb');
        $this->db->join('rout_creneau_date', $this->table_name . '.idCren = rout_creneau_date.id');
        $this->db->join('rout_participation', 'rout_participation' . '.idCren = rout_creneau_date.id');
        $this->db->where('rout_creneau_date.date <= ', date('Y-m-d'));
        $this->db->where('rout_participation.idCren', $cren);
        
        $query = $this->db->get($this->table_name);
        
        if ($query->row()->Nb !== '0')
            return false;
        else
            return true;
    }
}
