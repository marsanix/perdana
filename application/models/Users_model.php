<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    private function filters($config, $set_limit = true) {
        $limit = '';
        $offset = 0;
        if(isset($config['search']) && !empty($config['search'])) {
            $this->db->like('u.name', $this->db->escape_like_str($config['search']));
            $this->db->or_like('u.username', $this->db->escape_like_str($config['search']));
            $this->db->or_like('g.name', $this->db->escape_like_str($config['search']));
        }
        if(isset($config['groups_id']) && !empty($config['groups_id'])) {
            $this->db->where('u.groups_id', $this->db->escape_str($config['groups_id']));
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
        $query = $this->db->select('u.*')
                        ->select('g.name AS groups_name')
                        ->join('groups g','g.id = u.groups_id', 'LEFT')
                        ->get('users u');
        // echo $this->db->last_query();
        if($query != "" && $query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function select_total($config) {
        $this->filters($config, false);
        $query = $this->db->select('COUNT(u.id) AS total')
                        ->join('groups g','g.id = u.groups_id', 'LEFT')
                        ->get('users u');
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

               $this->db->set('name', $data->name)
                        ->set('username', $data->username)
                        ->set('password', md5x($data->password))
                        ->set('groups_id', $data->groups_id)
                        ->set('disable', ($data->disable == 1)?1:0)
                        ->set('created', UnFormatDateTime(CurrentDateTime(), 1))
                        ->set('created_by', $this->auth->get_user_id())
                        ->set('last_ip', $this->input->ip_address())
                        ->insert('users');
                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Failed insert into users: ' . $this->db->_error_message());
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

    public function update($jData) {
        if (!empty($jData)) {
            $data = json_decode($jData);
            $this->db->trans_begin();
            try {

                if(!empty($data->password)) {
                  $this->db->set('password', md5x($data->password));
                }

                $this->db->set('name', $data->name)
                        ->set('groups_id', $data->groups_id)
                        ->set('disable', ($data->disable == 1)?1:0)
                        ->set('modified', UnFormatDateTime(CurrentDateTime(), 1))
                        ->set('modified_by', $this->auth->get_user_id())
                        ->set('last_ip', $this->input->ip_address())
                        ->where('id', $this->db->escape_str($data->id))
                        ->update('users');
                // echo $this->db->last_query();
                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Failed insert into users: ' . $this->db->_error_message());
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

    public function lists_pengguna_operasional() {
        $query = $this->db->select('u.id')
                          ->select('u.name')
                          ->order_by('u.name','ASC')
                          ->where('groups_id', '2')
                          ->get('users u');
        // echo $this->db->last_query();
        if(!empty($query) && $query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function get_totals() {
      /*$sql = "SELECT 'total_permintaan' AS `kode`, COUNT(id) AS total FROM permintaan
                UNION SELECT 'total_permintaan_proses' AS `kode`, COUNT(id) AS total FROM permintaan WHERE `status` = 0
                UNION SELECT 'total_admin' AS `kode`, COUNT(id) AS total FROM users WHERE `groups_id` = '-1' OR groups_id = 1
                UNION SELECT 'total_operasional' AS `kode`, COUNT(id) AS total FROM users WHERE `groups_id` = 2
                UNION SELECT 'total_perusahaan' AS `kode`, COUNT(id) AS total FROM perusahaan";*/
      $sql = "SELECT
              (SELECT COUNT(id) AS total FROM permintaan) AS total_permintaan,
              (SELECT COUNT(id) AS total FROM permintaan WHERE `status` = 0) AS total_permintaan_proses,
              (SELECT COUNT(id) AS total FROM users WHERE `groups_id` = '-1' OR groups_id = 1) AS total_admin,
              (SELECT COUNT(id) AS total FROM users WHERE `groups_id` = 2) AS total_operasional,
              (SELECT COUNT(id) AS total FROM perusahaan) AS total_perusahaan;";
      $query = $this->db->query($sql);
      if(!empty($query) && $query->num_rows() > 0) {
        return $query->row();
      }
      return false;
    }

    public function update_profil($jData) {
        if (!empty($jData)) {
            $data = json_decode($jData);
            $this->db->trans_begin();
            try {
               $this->db->set('name', $data->name)
                        ->set('modified', UnFormatDateTime(CurrentDateTime(), 1))
                        ->set('modified_by', $this->auth->get_user_id())
                        ->set('last_ip', $this->input->ip_address())
                        ->where('id', $this->db->escape_str($data->id))
                        ->update('users');
                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Failed update user profile: ' . $this->db->_error_message());
                }

                $this->db->trans_commit();

                $this->session->userdata('U__NAME', $data->name);
                $this->auth->set_name($data->name);

                return true;
            } catch (Exception $e) {
                $this->db->trans_rollback();
                log_message("error", $e->getMessage());
                return false;
            }
        }
        return false;
    }

    public function data($id) {
        $query = $this->db->select('u.*')
                        ->select('g.name AS groups_name')
                        ->join('groups g','g.id = u.groups_id','LEFT')
                        ->where('u.id', $this->db->escape_str($id))
                        ->get('users u');
        if(!empty($query) && $query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }

    public function get_department_id($name) {
        $query = $this->db->select('id')
                          ->where('name', $this->db->escape_str($name))
                          ->get('departments');
        if(!empty($query) && $query->num_rows() > 0) {
            return $query->row()->id;
            //$this->db->free_result();
        }
        return false;
    }

    public function data_from_mail($email) {
        $query = $this->db->select('u.*')
                        ->select('g.name AS groups_name')
                        ->join('groups g','g.id = u.groups_id','LEFT')
                        ->where('u.mail', $this->db->escape_str($email))
                        ->get('users u');
        //echo $this->db->last_query();
        if(!empty($query) && $query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }

    public function lists($groups_id = '') {
        if($groups_id != '') {
            $this->db->where('groups_id', $this->db->escape_str($groups_id));
        }
        $query = $this->db->get('users');
        if(!empty($query) && $query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function password_exist($users_id, $password) {
        $query = $this->db->select('u.password AS secure')
                          ->where('u.id', $this->db->escape_str($users_id))
                          ->get('users u');
        // echo $this->db->last_query();
        if(!empty($query) && $query->num_rows() > 0) {
            if(md5x($password) == $query->row()->secure) {
                return true;
            }
            return false;
        }
        return false;
    }

    public function change_password($jData) {
        if (!empty($jData)) {
            $data = json_decode($jData);
            $this->db->trans_begin();
            try {
               $this->db->set('password', md5x($data->password))
                        ->set('modified', UnFormatDateTime(CurrentDateTime(), 1))
                        ->set('modified_by', $this->auth->get_user_id())
                        ->set('last_ip', $this->input->ip_address())
                        ->where('id', $this->db->escape_str($data->id))
                        ->update('users');
                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Failed password: ' . $this->db->_error_message());
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
                    ->delete('users');
            // echo $this->db->last_query();
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Failed delete users: ' . $this->db->_error_message());
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