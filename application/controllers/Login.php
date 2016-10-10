<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

    private $loggedin = false;
    private $username = '';
    private $password = '';
    private $fusername = '';
    private $fpassword = '';
    private $groups = '';
    private $perusahaan = '';
    private $error = '';

    function __construct() {
        parent::__construct();

        $this->login_in();

    }

    public function login_in() {

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->fusername = $this->session->userdata('fusername');
        $this->fpassword = $this->session->userdata('fpassword');

        if($this->fusername == "") {
            $this->fusername = $this->genFieldName('username');
            $this->session->set_userdata('fusername', $this->fusername);
        }
        if($this->fpassword == "") {
            $this->fpassword = $this->genFieldName('password');
            $this->session->set_userdata('fpassword', $this->fpassword);
        }

        $required_perusahaan = '';
        if($this->input->post('groups') == '2') {
        	$required_perusahaan = '|required';
        }

        $this->form_validation->set_rules($this->fusername, 'Username', 'trim|xss_clean|required')
                              ->set_rules($this->fpassword, 'Password', 'trim|xss_clean|required')
                              ->set_rules('groups', 'Tipe Pengguna', 'trim|xss_clean|required')
                              ->set_rules('perusahaan', 'Nama Perusahaan', 'trim|xss_clean'.$required_perusahaan)
                              ->set_error_delimiters('<div style="margin: 4px; padding: 7px;" class="alert alert-warning alert-dismissible" role="alert">
                                                        <button style="right: 0px;" type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span></button>','</div>');
        if ($this->form_validation->run()) {

            $this->fusername 	= $this->session->userdata('fusername');
            $this->fpassword 	= $this->session->userdata('fpassword');

            $this->username 	= $this->form_validation->set_value($this->fusername);
            $this->password 	= $this->form_validation->set_value($this->fpassword);
            $this->groups   	= $this->form_validation->set_value('groups');
            $this->perusahaan	= $this->form_validation->set_value('perusahaan');
            $this->loggedin 	= $this->auth->login($this->username, $this->password, $this->groups, $this->perusahaan);

            if($this->loggedin) {
                log_message('error','Log in success with username: '.$this->username.' from ip: '.$this->input->ip_address());

                // log_activity(current_url(), "Login.");
                $tmp_url = $this->session->userdata('TMP_URL');
                if(!empty($tmp_url)) {
                    redirect($tmp_url);
                }

                redirect('beranda');

            } else {
                $this->error = 'Invalid username or password!';
                log_message('error','Log in failed with username: '.$this->username. ' from ip: '.$this->input->ip_address());
            }

            $this->session->set_userdata('fusername', '');
            $this->session->set_userdata('fpassword', '');

        }

    }


    public function index(){
        $this->form();
    }

    function form(){

    	$this->load->model('groups_model');
		$this->load->model('perusahaan_model');

        if($this->fusername != $this->session->userdata('fusername')) {
            $this->fusername = $this->genFieldName('username');
            $this->session->set_userdata('fusername', $this->fusername);
        }
        if($this->fpassword != $this->session->userdata('fpassword')) {
            $this->fpassword = $this->genFieldName('password');
            $this->session->set_userdata('fpassword', $this->fpassword);
        }

        $datas = array('groupsList' => $this->groups_model->lists(),
					   'perusahaanList' => $this->perusahaan_model->lists(),
					   'name_username' => $this->fusername,
                       'name_password' => $this->fpassword,
                       'error' => $this->error,
                       'client_ip' => $this->input->ip_address(),
                      );
        //echo md5x('operasional123');
        $this->load->view('login', $datas);
    }

    function out() {
        log_message('error','Log out by username: '.$this->auth->get_username().' from ip: '.$this->input->ip_address());
        $this->auth->destroyAll();
    }

    function genFieldName($field) {
        $fieldname = "";
        switch($field) {
            case 'username':
                $fieldname = md5('u'.date('dmYhis').'n4me');
                break;
            case 'password':
                $fieldname = md5('p'.date('dmYhis').'a5swd');
                break;
        }
        return $fieldname;
    }

}
