<?php

ob_start();

class Auth {

    private $CI = null;
    private $user_id = 0;
    private $name;
    private $username;
    private $group_id;
    private $group_name;
    private $perusahaan = '';
    private $perusahaan_name = '';
    private $penanggung_jawab = '';

    function __construct(){
        $this->CI =& get_instance();
        $this->CI->load->database('default', TRUE);
        $this->CI->load->library('session');

        if($this->isLogin()) {
            $this->set_user_id($this->CI->session->userdata('U__ID'));
            $this->set_name($this->CI->session->userdata('U__NAME'));
            $this->set_username($this->CI->session->userdata('U__USERNAME'));
            $this->set_group_id($this->CI->session->userdata('U__GROUP_ID'));
            $this->set_group_name($this->CI->session->userdata('U__GROUP_NAME'));
            $this->set_perusahaan($this->CI->session->userdata('U__PERUSAHAAN'));
            $this->set_perusahaan_name($this->CI->session->userdata('U__PERUSAHAAN_NAME'));
            $this->set_penanggung_jawab($this->CI->session->userdata('U__PENANGGUNG_JAWAB'));
        }

    }

    public function isLogin() {
        if($this->CI->session->userdata('U__LOGGEDIN') == true) {
            return true;
        } else {

            /**
             * store temporary url to be accessed before the login
             */
            $this->CI->load->helper('cookie');
            $arrCookie = array('name'   => 'current_url',
                               'value'  => $this->CI->uri->uri_string(),
                               'expire' => 86500,
                               'secure' => FALSE
                              );
            $this->CI->input->set_cookie($arrCookie);

            return false;
        }
    }

    public function isSuperAdmin() {
        if($this->CI->session->userdata('U__GROUP_ID') == '-1') {
            return true;
        } else {
            return false;
        }
    }

    public function isAdmin() {
        if($this->CI->session->userdata('U__GROUP_ID') == '1') {
            return true;
        } else {
            return false;
        }
    }

    public function isOperasional() {
        if($this->CI->session->userdata('U__GROUP_ID') == '2') {
            return true;
        } else {
            return false;
        }
    }

    public function login($username, $password, $groups, $perusahaan) {

        if($groups == '2') {
            $this->CI->db->select('u.*')
                    ->select('g.name AS groups_name')
                    ->select('up.perusahaan_id')
                    ->select('p.nama_perusahaan')
                    ->select('p.penanggung_jawab')
                    ->join('groups g','g.id = u.groups_id','LEFT')
                    ->join('users_perusahaan up', 'up.users_id = u.id', 'LEFT')
                    ->join('perusahaan p', 'p.id = up.perusahaan_id', 'LEFT');
            $query = $this->CI->db->get_where('users u',
                                               array('u.username' => $this->CI->db->escape_str(trim($username)),
                                                     'u.password' => md5x($password),
                                                     'u.groups_id' => $this->CI->db->escape_str(trim($groups)),
                                                     'up.perusahaan_id' => $this->CI->db->escape_str(trim($perusahaan)),
                                                     'u.disable' => 0)
                                             );
        } else {
            $this->CI->db->select('u.*')
                    ->select('g.name AS groups_name')
                    ->join('groups g','g.id = u.groups_id','LEFT');
            $query = $this->CI->db->get_where('users u',
                                               array('u.username' => $this->CI->db->escape_str(trim($username)),
                                                     'u.password' => md5x($password),
                                                     'u.groups_id' => $this->CI->db->escape_str(trim($groups)),
                                                     'u.disable' => 0)
                                             );
        }
        //echo $this->CI->db->last_query();
        //die();
        if (!empty($query) && $query->num_rows() == 1) {
            foreach ($query->result() as $val) {

                $this->set_user_id($val->id);
                $this->set_name($val->name);
                $this->set_username($username);
                $this->set_group_id($val->groups_id);
                $this->set_group_name($val->groups_name);
                if($groups == '2') {
                    $this->set_perusahaan($val->perusahaan_id);
                    $this->set_perusahaan_name($val->nama_perusahaan);
                    $this->set_penanggung_jawab($val->penanggung_jawab);
                } else {
                    $this->set_perusahaan($this->perusahaan);
                    $this->set_perusahaan_name($this->perusahaan_name);
                    $this->set_penanggung_jawab($this->penanggung_jawab);
                }

                $this->auth_login();

                $this->setLastLogin();
            }
            return true;
        } else {
            return false;
            log_message('error', "Login failed with username: ". $username." from ip: ".$this->CI->input->ip_address());
        }
        return false;
    }


    function setLastLogin() {
        $this->CI->load->library('user_agent');
        $this->CI->db->trans_begin();
        try {

            $datas = array("date" => UnFormatDateTime(CurrentDateTime(),1),
                           "ip" => $this->CI->input->ip_address(),
                           "browser" => $this->CI->agent->agent_string());
            $this->CI->db->set('last_login', json_encode($datas));
            $this->CI->db->where('id', $this->CI->db->escape_str($this->get_user_id()));
            $this->CI->db->update('users');
            //echo $this->db->last_query();
            if ($this->CI->db->trans_status() != TRUE) {
                throw new Exception('Failed set last login: ' . $this->CI->db->_error_message());
            }

            $this->CI->db->trans_commit();
            return true;
        } catch (Exception $e) {
            $this->CI->db->trans_rollback();
            return $e->getMessage();
        }

    }

    function auth_login(){
        $this->CI->session->set_userdata('U__LOGGEDIN', TRUE);
        $this->CI->session->set_userdata('U__ID', $this->user_id);
        $this->CI->session->set_userdata('U__NAME', $this->name);
        $this->CI->session->set_userdata('U__USERNAME', $this->username);
        $this->CI->session->set_userdata('U__GROUP_ID', $this->group_id);
        $this->CI->session->set_userdata('U__GROUP_NAME', $this->group_name);
        $this->CI->session->set_userdata('U__PERUSAHAAN', $this->perusahaan);
        $this->CI->session->set_userdata('U__PERUSAHAAN_NAME', $this->perusahaan_name);
        $this->CI->session->set_userdata('U__PENANGGUNG_JAWAB', $this->penanggung_jawab);
    }

    function destroyAll() {
        $this->CI->session->sess_destroy();
        redirect();
    }

    /**
     *
     * @return type
     * Setter - Getter
     *
     */
    public function get_user_id() {
        return $this->user_id;
    }
    public function set_user_id($user_id) {
        $this->user_id = $user_id;
    }
    public function get_name() {
        return $this->name;
    }
    public function set_name($name) {
        $this->name = $name;
    }
    public function get_username() {
        return $this->username;
    }
    public function set_username($username) {
        $this->username = $username;
    }
    public function get_password() {
        return $this->password;
    }
    public function set_password($password) {
        $this->password = $password;
    }
    public function get_group_id() {
        return $this->group_id;
    }
    public function set_group_id($group_id) {
        $this->group_id = $group_id;
    }
    public function get_group_name() {
        return $this->group_name;
    }
    public function set_group_name($group_name) {
        $this->group_name = $group_name;
    }
    public function get_perusahaan() {
        return $this->perusahaan;
    }
    public function set_perusahaan($perusahaan) {
        $this->perusahaan = $perusahaan;
    }
    public function get_perusahaan_name() {
        return $this->perusahaan_name;
    }
    public function set_perusahaan_name($perusahaan_name) {
        $this->perusahaan_name = $perusahaan_name;
    }
    public function get_penanggung_jawab() {
        return $this->penanggung_jawab;
    }
    public function set_penanggung_jawab($penanggung_jawab) {
        $this->penanggung_jawab = $penanggung_jawab;
    }

}
