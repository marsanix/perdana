<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Perusahaan_model extends CI_Model {

    public function __construct() {
        parent::__construct();

        $this->load->database();

    }

    private function filters($config, $set_limit = true) {
        $limit = '';
        $offset = 0;
        if(isset($config['search']) && !empty($config['search'])) {
            $this->db->like('p.nama_perusahaan', $this->db->escape_like_str($config['search']));
            $this->db->or_like('p.penanggung_jawab', $this->db->escape_like_str($config['search']));
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
        $Rows = "";
        $this->filters($config);
        $query = $this->db->select('p.*')
                        ->get('perusahaan p');
        // echo $this->db->last_query();
        if($query != "" && $query->num_rows() > 0) {
            //return $query->result();
            foreach($query->result() as $rows) {
                $rows->daftar_nama_pengguna = $this->up_pengguna_lists($rows->id);
                $Rows[] = $rows;
            }
            return json_decode(json_encode($Rows));
        }
        return false;
    }

    public function select_total($config) {
        $this->filters($config, false);
        $query = $this->db->select('COUNT(p.id) AS total')
                        ->get('perusahaan p');
        // echo $this->db->last_query();
        if($query != "" && $query->num_rows() > 0) {
            return $query->row()->total;
        }
        return false;
    }

    public function lists() {
        $query = $this->db->order_by('nama_perusahaan','ASC')
                          ->get('perusahaan');
        // echo $this->db->last_query();
        if(!empty($query) && $query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function list_by_users($users_id) {
        $query = $this->db->select('p.nama_perusahaan')
                          ->where('up.users_id', $this->db->escape_str($users_id))
                          ->join('perusahaan p', 'p.id = up.perusahaan_id','LEFT')
                          ->order_by('up.perusahaan_id','ASC')
                          ->get('users_perusahaan up');
        // echo $this->db->last_query();
        if(!empty($query) && $query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function data($id, $users_id = '') {
        $rows = '';
        $this->db->select('p.*');
        if($users_id != '') {
            $this->db->select('u.name AS nama_pengguna')
                     ->where('p.id', $this->db->escape_str($id))
                     ->where('up.users_id', $this->db->escape_str($users_id))
                     ->join('users_perusahaan up', 'up.perusahaan_id = p.id', 'LEFT')
                     ->join('users u', 'u.id = up.users_id', 'LEFT');
        } else {
            $this->db->where('p.id', $this->db->escape_str($id));
        }
        $query = $this->db->get('perusahaan p');
        //echo $this->db->last_query();
        if(!empty($query) && $query->num_rows() > 0) {
            $rows = $query->row();
            $rows->daftar_nama_pengguna = $this->up_pengguna_lists($rows->id);

            return $rows;
        }
        return false;
    }


    private function up_pengguna_lists($perusahaan_id) {
         $query = $this->db->select('up.users_id')
                           ->select('(SELECT name FROM users WHERE id = up.users_id) As nama_pengguna')
                           ->where('up.perusahaan_id', $this->db->escape_str($perusahaan_id))
                           ->get('users_perusahaan up');
        // echo $this->db->last_query();
        if(!empty($query) && $query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function get_saldo($id) {
         $query = $this->db->select('p.saldo')
                           ->where('p.id', $this->db->escape_str($id))
                           ->get('perusahaan p');
        // echo $this->db->last_query();
        if(!empty($query) && $query->num_rows() > 0) {
            return $query->row()->saldo;
        }
        return false;
    }

    public function get_data_for_history($id) {
         $query = $this->db->select('p.saldo')
                           ->select('p.nama_perusahaan')
                           ->where('p.id', $this->db->escape_str($id))
                           ->get('perusahaan p');
        // echo $this->db->last_query();
        if(!empty($query) && $query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }

    public function insert($jData, $return_id = false) {
        if (!empty($jData)) {
            $data = json_decode($jData);
            $this->db->trans_begin();
            try {

                $this->db->set('nama_perusahaan', strtoupper($data->nama_perusahaan))
                        ->set('penanggung_jawab', strtoupper($data->penanggung_jawab))
                        ->set('saldo', $data->saldo)
                        ->set('created', UnFormatDateTime(CurrentDateTime(), 1))
                        ->set('created_by', $this->auth->get_user_id())
                        ->set('last_ip', $this->input->ip_address())
                        ->insert('perusahaan');
                        $insert_id = $this->db->insert_id();
                // echo $this->db->last_query();
                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Failed insert into perusahaan: ' . $this->db->_error_message());
                }

                //log_message("error", json_encode($data->daftarPengguna));

                if(!empty($data->daftarPengguna)) {
                    foreach($data->daftarPengguna as $pengguna) {
                        $this->db->set('perusahaan_id', $insert_id)
                                 ->set('users_id', $pengguna)
                                 ->insert('users_perusahaan');
                        // echo $this->db->last_query();
                        log_message("error", $this->db->last_query());
                        if ($this->db->trans_status() === FALSE) {
                            throw new Exception('Failed insert into users perusahaan: ' . $this->db->_error_message());
                        }
                    }
                }

                $this->db->trans_commit();

                if($return_id === true){
                    return $insert_id;
                } else {
                    return true;
                }

            } catch (Exception $e) {
                $this->db->trans_rollback();
                log_message("error", $e->getMessage());
                return false;
            }
        }
        return false;
    }


    private function name_exist($name) {
        $query = $this->db->where('name', $this->db->escape_str($name))
                          ->get('perusahaan');
        // echo $this->db->last_query().'<br />';
        if(!empty($query) && $query->num_rows() > 0) {
            return true;
        }
        return false;
    }

    public function update($jData) {
        if (!empty($jData)) {
            $data = json_decode($jData);

            $dataPerusahaan = $this->get_data_for_history($data->id);
            $saldo_awal = $dataPerusahaan->saldo;

            $this->db->trans_begin();
            try {

                $this->db->set('nama_perusahaan', strtoupper($data->nama_perusahaan))
                        ->set('penanggung_jawab', strtoupper($data->penanggung_jawab))
                        ->set('saldo', $data->saldo)
                        ->set('modified', UnFormatDateTime(CurrentDateTime(), 1))
                        ->set('modified_by', $this->auth->get_user_id())
                        ->set('last_ip', $this->input->ip_address())
                        ->where('id', $this->db->escape_str($data->id))
                        ->update('perusahaan');
                // echo $this->db->last_query();
                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Failed update into perusahaan: ' . $this->db->_error_message());
                }

                $this->db->where('perusahaan_id', $data->id)
                         ->delete('users_perusahaan');
                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Failed clear for renew users perusahaan: ' . $this->db->_error_message());
                }

                if(!empty($data->daftarPengguna)) {
                    foreach($data->daftarPengguna as $pengguna) {
                        $this->db->set('perusahaan_id', $data->id)
                                 ->set('users_id', $pengguna)
                                 ->insert('users_perusahaan');
                        // echo $this->db->last_query();
                        log_message("error", $this->db->last_query());
                        if ($this->db->trans_status() === FALSE) {
                            throw new Exception('Failed insert into users perusahaan: ' . $this->db->_error_message());
                        }
                    }
                }

                if($saldo_awal != $data->saldo) {
                    $this->db->set('datetime', UnFormatDateTime(CurrentDateTime(), 1))
                              ->set('description', "Perubahan saldo perusahaan ".$dataPerusahaan->nama_perusahaan." dari ".FormatCurrency($saldo_awal,0)." menjadi ".FormatCurrency($data->saldo,0)." oleh ".$this->auth->get_username().".")
                              ->set('saldo_awal', $saldo_awal)
                              ->set('saldo_baru', $data->saldo)
                              ->set('perusahaan_id', $data->id)
                              ->set('users_id', $this->auth->get_user_id())
                              ->insert('history_ubah_saldo');
                    // echo $this->db->last_query();
                    if ($this->db->trans_status() === FALSE) {
                        throw new Exception('Failed insert into history ubah saldo: ' . $this->db->_error_message());
                    }
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

    public function delete($id) {
        if(!is_numeric($id)) {
            return false;
        }
        try {
            $this->db->where('id', $this->db->escape_str($id))
                    ->delete('perusahaan');
            // echo $this->db->last_query();
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Failed delete perusahaan: ' . $this->db->_error_message());
            }
            $this->db->trans_commit();
            return true;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message("error", $e->getMessage());
            return false;
        }
    }

}