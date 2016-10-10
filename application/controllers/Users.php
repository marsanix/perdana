<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    function __construct() {
        parent::__construct();

        if(!$this->auth->isLogin()) {
            redirect('login');
        }

        if(!$this->auth->isSuperAdmin()) {
            redirect('beranda');
        }

    }

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/welcome
     *  - or -
     *      http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function index($filter = "")
    {

        if(!in_array($filter, array("admin", "operasional",""))) {
          die("required valid filter segments");
        }

        $this->load->model('users_model');

        $headers = array("css_link" => array(array('href' => assets_url('plugins/fancyapps/source/jquery.fancybox.css', '?v=2.1.5'),
                                                   'attr' => 'type="text/css" media="screen"'),
                                             array('href' => assets_url('plugins/iCheck/all.css'),
                                                   'attr' => 'rel="stylesheet"'),
                                       )
                   );
        load_header($headers);

        $tipe_pengguna = "";
        $selected_groups = "";
        switch($filter) {
          case 'admin':
            $tipe_pengguna = "Daftar Admin";
            $selected_groups = "1";
          break;
          case 'operasional':
            $selected_groups = "2";
          break;
        }

        $this->load->model('users_model');
        $this->load->model('groups_model');
        $datas = array("usersList" => $this->users_model->lists(),
                       "penggunaList" => $this->users_model->lists_pengguna_operasional(),
                       "groupsLists" => $this->groups_model->lists(),
                       "tipe_pengguna" => $tipe_pengguna,
                       "selected_groups" => $selected_groups
                      );
        $this->load->view('users/users', $datas);

        $users_js = $this->load->view('users/users.js', '', true);
        $footers = array("js_link" => array(assets_url('plugins/fancyapps/lib/jquery.mousewheel-3.0.6.pack.js'),
                                            assets_url('plugins/fancyapps/source/jquery.fancybox.pack.js', '?v=2.1.5'),
                                            assets_url('plugins/iCheck/icheck.min.js'),
                                            assets_url('plugins/input-mask/jquery.inputmask.js'),
                                            assets_url('plugins/input-mask/jquery.inputmask.date.extensions.js'),
                                            assets_url('plugins/input-mask/jquery.inputmask.extensions.js'),
                                            ),
                         "js_script" => array($users_js)
                        );
        load_footer($footers);

    }

    public function load_data($page = 0) {
        $this->load->library('jquery_pagination');
        $this->load->library('form_validation');
        $this->load->model('users_model');

        $this->form_validation->set_rules('cari', 'Cari', 'trim|xss_clean')
                              ->set_rules('per_page', 'Per Page', 'trim|xss_clean|numeric')
                              ->set_rules('cari_groups', 'Tipe Pengguna', 'trim|xss_clean|numeric')
                              ->set_error_delimiters('<div class="alert alert-warning alert-dismissable">
                                                  <i class="fa fa-warning"></i>
                                                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
        if ($this->form_validation->run() === TRUE) {

            $per_page = $this->form_validation->set_value('per_page');
            $groups_id = $this->form_validation->set_value('cari_groups');

            $config_search = array("search" => $this->form_validation->set_value('cari'),
                                   "groups_id" => $groups_id,
                                   "per_page" => $per_page,
                                   "page" => $page);

            $config['base_url'] = base_url('users/load_data/');
            $config['div'] = '#tableContent';
            $config['additional_param'] = 'serialize_form()';
            $config['total_rows'] = $this->users_model->select_total($config_search);
            $config['per_page'] = $per_page;
            $config['cur_page'] = $page;
            $config['uri_segment'] = 3;
            $config['num_links'] = 3;
            $this->jquery_pagination->initialize($config);
            $pagination = $this->jquery_pagination->create_links();

            $datas = array("usersList" => $this->users_model->select($config_search),
                           "pagination" => $pagination,
                           "per_page" => $per_page,
                           "groups" => $groups_id,
                           "page" => $page);
            $this->load->view('users/users_data', $datas);

        } else {
            echo $this->form_validation->error('cari');
        }

    }

    public function add($save = '')
    {

        $this->load->model('users_model');
        $this->load->model('groups_model');

        if($save === 'save') {

            $this->load->model('users_model');
            $this->load->library('form_validation');

            $this->form_validation->set_rules('name', 'Nama Pengguna', 'trim|xss_clean|required')
                                  ->set_rules('username', 'Username', 'trim|xss_clean|required')
                                  ->set_rules('password', 'Password', 'trim|xss_clean|required')
                                  ->set_rules('groups', 'Tipe Pengguna', 'trim|xss_clean|required')
                                  ->set_rules('disable', 'Status', 'trim|xss_clean|required')
                                  ->set_error_delimiters('<div class="alert alert-warning alert-dismissable">
                                                            <i class="fa fa-warning"></i>
                                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
            if ($this->form_validation->run() === TRUE) {

                $datas = array('name' => $this->form_validation->set_value('name'),
                                'username' => $this->form_validation->set_value('username'),
                                'password' => $this->form_validation->set_value('password'),
                                'groups_id' => $this->form_validation->set_value('groups'),
                                'disable' => $this->form_validation->set_value('disable'),
                               );

                //echo json_encode($datas);
                //die();

                $result = $this->users_model->insert(json_encode($datas), true);
                if ($result !== false) {
                    echo json_encode(array('status' => '1',
                                           'error' => '',
                                           'insert_id' => $result,
                                           'token' => $this->security->get_csrf_hash()));
                } else {
                    echo json_encode(array('status' => '0', 'insert_id' => '', 'token' => $this->security->get_csrf_hash(), 'error' => $result));
                }

            } else {

                $error = $this->form_validation->error('name');
                $error .= $this->form_validation->error('username');
                $error .= $this->form_validation->error('password');
                $error .= $this->form_validation->error('groups');
                $error .= $this->form_validation->error('disable');

                echo json_encode(array('status' => '0', 'insert_id' => '', 'token' => $this->security->get_csrf_hash(), 'error' => $error));

            }

        } else {

            $datas = array("groupsLists" => $this->groups_model->lists(),
                     );
            $this->load->view('users/users_add', $datas);


        }
    }

    public function edit($id = '', $save = '')
    {

        if(!is_numeric($id)) {
            die('required numeric id');
        }

        $this->load->model('users_model');
        $this->load->model('groups_model');

        if($save === 'save') {

            $this->load->library('form_validation');

            $this->form_validation->set_rules('id', 'ID', 'trim|xss_clean|required|integer')
                                  ->set_rules('name', 'Nama Pengguna', 'trim|xss_clean|required')
                                  ->set_rules('password', 'Password', 'trim|xss_clean')
                                  ->set_rules('change_password', 'Ubah Password', 'trim|xss_clean')
                                  ->set_rules('groups', 'Tipe Pengguna', 'trim|xss_clean|required')
                                  ->set_error_delimiters('<div class="alert alert-warning alert-dismissable">
                                                            <i class="fa fa-warning"></i>
                                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
            if ($this->form_validation->run() === TRUE) {

                $password = "";
                $change_password =  $this->form_validation->set_value('change_password');
                if($change_password) {
                  $password = $this->form_validation->set_value('password');
                }

                $datas = array('id' => $this->form_validation->set_value('id'),
                                'name' => $this->form_validation->set_value('name'),
                                'password' => $password,
                                'groups_id' => $this->form_validation->set_value('groups'),
                                'disable' => $this->form_validation->set_value('disable'),
                               );

                //echo json_encode($datas);
                //die();

                $result = $this->users_model->update(json_encode($datas));
                if ($result !== false) {
                    echo json_encode(array('status' => '1',
                                           'error' => '',
                                           'token' => $this->security->get_csrf_hash()));
                } else {
                    echo json_encode(array('status' => '0', 'insert_id' => '', 'token' => $this->security->get_csrf_hash(), 'error' => $result));
                }

            } else {

                $error = $this->form_validation->error('id');
                $error .= $this->form_validation->error('name');
                $error .= $this->form_validation->error('password');
                $error .= $this->form_validation->error('groups_id');
                $error .= $this->form_validation->error('disable');

                echo json_encode(array('status' => '0', 'insert_id' => '', 'token' => $this->security->get_csrf_hash(), 'error' => $error));

            }

        } else {

            $datas = array("data" => $this->users_model->data($id),
                           "groupsLists" => $this->groups_model->lists(),
                     );
            $this->load->view('users/users_edit', $datas);

        }
    }

    public function view($id = "") {
        if(!is_numeric($id)) {
            die('required numeric id');
        }

        $this->load->model('users_model');

        $datas = array("data" => $this->users_model->data($id),
                 );
        $this->load->view('users/users_view', $datas);
    }

    public function delete($id = '') {
        if(!is_numeric($id)) {
            die('required numeric id');
        }

        if($this->auth->isOperasional()) {
            die('required access for admin');
        }

        $this->load->model('users_model');
        $result = $this->users_model->delete($id);
        if($result) {
            echo '1';
        } else {
            echo $result;
        }
    }

}
