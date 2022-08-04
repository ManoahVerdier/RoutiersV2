<<<<<<< HEAD
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Modèle
 * Congrégations
 * @author	Manoah VERDIER
 */
class M_congregations extends CI_Model {

    private $table_name = 'congregation';

    function __construct() {
        parent::__construct();

        $ci = & get_instance();
        $this->table_name = $ci->config->item('db_table_prefix') . $this->table_name;
    }

    /**
     * Retourne le nombre de congrégations
     * @return array tableau associatif correspondant au result sql
     */
    function countAll() {
        return $this->db->count_all($this->table_name);
    }

    /**
     * Retourne une congrégation sur la base de son ID
     * @param int $id
     * @return array tableau associatif correspondant à une ligne de la base
     */
    function get($id) {
        $this->db->select('*');

        $this->db->where('id', $id);

        $query = $this->db->get($this->table_name);

        return $query->row();
    }

    /**
     * Supprimer une congrégation par id
     * @param int $id
     */
    function deleteCong($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_name);
    }

    /**
     * Retourne toutes les congrégations
     * @return array tableau associatif correspondant au result
     */
    function getAllCong() {
        $this->db->select('*');
        $this->db->order_by('nom');
        $query = $this->db->get($this->table_name);
        return $query->result();
    }

    /**
     * Ajouter une congrégation
     * @param stdClass $cong
     * @return int l'id de la congragation créée
     */
    function addCong($cong) {
        $this->db->set($cong);
        $this->db->insert($this->table_name);
        return $this->db->insert_id();
    }
    
    function setRP($idCong,$idUtil){
        $cong = new stdclass;
        $cong->idRP = $idUtil;
        $this->db->where('id', $idCong)
                ->update($this->table_name, $cong);
    }

}
=======
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Modèle
 * Congrégations
 * @author	Manoah VERDIER
 */
class M_congregations extends CI_Model {

    private $table_name = 'congregation';

    function __construct() {
        parent::__construct();

        $ci = & get_instance();
        $this->table_name = $ci->config->item('db_table_prefix') . $this->table_name;
    }

    /**
     * Retourne le nombre de congrégations
     * @return array tableau associatif correspondant au result sql
     */
    function countAll() {
        return $this->db->count_all($this->table_name);
    }

    /**
     * Retourne une congrégation sur la base de son ID
     * @param int $id
     * @return array tableau associatif correspondant à une ligne de la base
     */
    function get($id) {
        $this->db->select('*');

        $this->db->where('id', $id);

        $query = $this->db->get($this->table_name);

        return $query->row();
    }

    /**
     * Supprimer une congrégation par id
     * @param int $id
     */
    function deleteCong($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_name);
    }

    /**
     * Retourne toutes les congrégations
     * @return array tableau associatif correspondant au result
     */
    function getAllCong() {
        $this->db->select('*');
        $this->db->order_by('nom');
        $query = $this->db->get($this->table_name);
        return $query->result();
    }

    /**
     * Ajouter une congrégation
     * @param stdClass $cong
     * @return int l'id de la congragation créée
     */
    function addCong($cong) {
        $this->db->set($cong);
        $this->db->insert($this->table_name);
        return $this->db->insert_id();
    }
    
    function setRP($idCong,$idUtil){
        $cong = new stdclass;
        $cong->idRP = $idUtil;
        $this->db->where('id', $idCong)
                ->update($this->table_name, $cong);
    }

}
>>>>>>> 7ff721c (initial commit)
