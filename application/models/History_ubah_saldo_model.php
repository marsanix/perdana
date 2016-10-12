<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class History_ubah_saldo_model extends CI_Model {

    public function __construct() {
        parent::__construct();

        $this->load->database();

    }

    private function filters($config, $set_limit = true) {
        $limit = '';
        $offset = 0;
        if(isset($config['perusahaan_id']) && !empty($config['perusahaan_id'])) {
            $this->db->where('h.perusahaan_id', $this->db->escape_like_str($config['perusahaan_id']));
        }
        if(isset($config['per_page']) && !empty($config['per_page'])) {
            $limit = $config['per_page'];
        }
        if(isset($config['page']) && !empty($config['page'])) {
            $offset = $config['page'];
        }
        if($set_limit) {
            //if($offset > 0) {
                $this->db->limit($limit, $offset);
            //}
        }
    }

    public function select($config) {
        $this->filters($config);
        $query = $this->db->select('h.*')
                        ->order_by('h.id','DESC')
                        ->get('history_ubah_saldo h');
        // echo $this->db->last_query();
        if($query != "" && $query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function select_total($config) {
        $this->filters($config, false);
        $query = $this->db->select('COUNT(h.id) AS total')
                        ->get('history_ubah_saldo h');
        // echo $this->db->last_query();
        if($query != "" && $query->num_rows() > 0) {
            return $query->row()->total;
        }
        return false;
    }

    public function insert($jData) {
        if (!empty($jData)) {
            $data = json_decode($jData);
            $this->db->trans_begin();
            try {

               $this->db->set('datetime', UnFormatDateTime(CurrentDateTime(), 1))
                          ->set('description', $data->description)
                          ->set('saldo_awal', $data->saldo_awal)
                          ->set('saldo_baru', $data->saldo_baru)
                          ->set('perusahaan_id', $data->perusahaan_id)
                          ->set('users_id', $data->users_id)
                          ->insert('history_ubah_saldo');
                // echo $this->db->last_query();
                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Failed insert into history_ubah_saldo: ' . $this->db->_error_message());
                }

                $this->db->trans_commit();
                return true;
            } catch (Exception $e) {
                $this->db->trans_rollback();
                log_message("error", $e->getMessage());
                return false;
            }
        }
        return false;
    }

}