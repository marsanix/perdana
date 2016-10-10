<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Groups_model extends CI_Model {

    public function __construct() {
        parent::__construct();

        $this->load->database();

    }

    public function lists() {
        $query = $this->db->order_by('name','ASC')
                          ->get('groups');
        // echo $this->db->last_query();
        if(!empty($query) && $query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

}