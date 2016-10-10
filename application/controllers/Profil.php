<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Profil extends CI_Controller {

    function __construct() {
        parent::__construct();

        if(!$this->auth->isLogin()) {
            redirect('login');
        }

    }

    public function index() {
        $this->load->helper('form');
        $this->load->model('perusahaan_model');
        $headers = array("css_link" => array(array('href' => assets_url('plugins/fancyapps/source/jquery.fancybox.css', '?v=2.1.5'),
                                                   'attr' => 'type="text/css" media="screen"'),
                                             array("href" => assets_url('plugins/jquery-file-upload/css/jquery.fileupload.css'),
                                                   "attr" => 'type="text/css"'),
                                                  )
                                            );
        load_header($headers);

        $datas = array("perusahaanByUsers" => $this->perusahaan_model->list_by_users($this->auth->get_user_id()));
        $this->load->view('profil/profil', $datas);

        $js = $this->load->view('profil/profil.js', '', true);
        $footers = array("js_link" => array(assets_url('plugins/fancyapps/lib/jquery.mousewheel-3.0.6.pack.js'),
                                            assets_url('plugins/fancyapps/source/jquery.fancybox.pack.js', '?v=2.1.5'),
                                            assets_url('plugins/jquery-file-upload/js/jquery.fileupload.js'),
                                            ),
                         "js_script" => array($js)
                        );
        load_footer($footers);

    }

    public function update() {
        $this->load->model('users_model');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Nama', 'trim|xss_clean|required')
                              ->set_error_delimiters('<div class="alert alert-warning alert-dismissable">
                                                        <i class="fa fa-warning"></i>
                                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
        if ($this->form_validation->run() === TRUE) {
            $datas = array('id' => $this->auth->get_user_id(),
                            'name' => $this->form_validation->set_value('name'),
                           );
            $result = $this->users_model->update_profil(json_encode($datas));
            if($result) {

                $this->session->userdata('U__NAME', $this->form_validation->set_value('name'));
                $this->auth->set_name($this->form_validation->set_value('name'));

                echo '1';
            } else {
                echo $result;
            }
        } else {
            echo $this->form_validation->error('name');
        }
    }

    public function change_photo() {
        $this->load->helper('form');
        $this->load->view('profil/profil_change_photo');
    }

    public function change_password() {
        $this->load->model('users_model');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('old_password', 'Password Lama', 'trim|xss_clean|required')
                              ->set_rules('new_password', 'Password Baru', 'trim|xss_clean|required')
                              ->set_rules('new_password2', 'Ulangi Password Baru', 'trim|xss_clean|required')
                              ->set_error_delimiters('<div class="alert alert-warning alert-dismissable">
                                                        <i class="fa fa-warning"></i>
                                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
        if ($this->form_validation->run() === TRUE) {

            $old_password = $this->form_validation->set_value('old_password');
            $new_password = $this->form_validation->set_value('new_password');
            $new_password2 = $this->form_validation->set_value('new_password2');

            $old_password_exist = $this->password_exist($old_password);
            if(!$old_password_exist) {

                $msg = '<div class="alert alert-warning alert-dismissable">
                        <i class="fa fa-warning"></i>
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        Password lama tidak sesuai untuk akun Anda.
                        </div>';
                echo json_encode(array('status' => '0', 'token' => $this->security->get_csrf_hash(), 'error' => $msg));
            } elseif($new_password != $new_password2) {
                $msg = '<div class="alert alert-warning alert-dismissable">
                        <i class="fa fa-warning"></i>
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        Silahkan ulangi password baru dengan benar.
                        </div>';
                echo json_encode(array('status' => '0', 'token' => $this->security->get_csrf_hash(), 'error' => $msg));
            } else {

                $datas = array('id' => $this->auth->get_user_id(),
                                'password' => $new_password2,
                               );
                $result = $this->users_model->change_password(json_encode($datas));
                if($result) {
                    echo json_encode(array('status' => '1', 'token' => $this->security->get_csrf_hash(), 'error' => ''));

                } else {
                    echo json_encode(array('status' => '0', 'token' => $this->security->get_csrf_hash(), 'error' => $result));
                }
            }
        } else {
            $error = $this->form_validation->error('old_password');
            $error .= $this->form_validation->error('new_password');
            $error .= $this->form_validation->error('new_password2');
            echo json_encode(array('status' => '0', 'token' => $this->security->get_csrf_hash(), 'error' => $error));
        }
    }

    private function password_exist($password) {
        $this->load->model('users_model');
        $users_id = $this->auth->get_user_id();
        $password_exist = $this->users_model->password_exist($users_id, $password);
        if($password_exist) {
            return true;
        }
        return false;
    }


}