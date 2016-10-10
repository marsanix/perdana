<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perusahaan extends CI_Controller {

    function __construct() {
        parent::__construct();

        if(!$this->auth->isLogin()) {
            redirect('login');
        }

        if($this->auth->isOperasional()) {
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
    public function index()
    {

        $this->load->model('perusahaan_model');

        $headers = array("css_link" => array(array('href' => assets_url('plugins/fancyapps/source/jquery.fancybox.css', '?v=2.1.5'),
                                                   'attr' => 'type="text/css" media="screen"'),
                                             array('href' => assets_url('plugins/iCheck/all.css'),
                                                   'attr' => 'rel="stylesheet"'),
                                       )
                   );
        load_header($headers);

        $this->load->model('users_model');
        $datas = array("perusahaanList" => $this->perusahaan_model->lists(),
                       "penggunaList" => $this->users_model->lists_pengguna_operasional(),
                      );
        $this->load->view('perusahaan/perusahaan', $datas);

        $perusahaan_js = $this->load->view('perusahaan/perusahaan.js', '', true);
        $footers = array("js_link" => array(assets_url('plugins/fancyapps/lib/jquery.mousewheel-3.0.6.pack.js'),
                                            assets_url('plugins/fancyapps/source/jquery.fancybox.pack.js', '?v=2.1.5'),
                                            assets_url('plugins/iCheck/icheck.min.js'),
                                            assets_url('plugins/input-mask/jquery.inputmask.js'),
                                            assets_url('plugins/input-mask/jquery.inputmask.date.extensions.js'),
                                            assets_url('plugins/input-mask/jquery.inputmask.extensions.js'),
                                            ),
                         "js_script" => array($perusahaan_js)
                        );
        load_footer($footers);

    }

    public function load_data($page = 0) {
        $this->load->library('jquery_pagination');
        $this->load->library('form_validation');
        $this->load->model('perusahaan_model');

        $this->form_validation->set_rules('cari', 'Cari', 'trim|xss_clean')
                              ->set_rules('per_page', 'Per Page', 'trim|xss_clean|numeric')
                              ->set_error_delimiters('<div class="alert alert-warning alert-dismissable">
                                                  <i class="fa fa-warning"></i>
                                                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
        if ($this->form_validation->run() === TRUE) {

            $per_page = $this->form_validation->set_value('per_page');

            $config_search = array("search" => $this->form_validation->set_value('cari'),
                                   "per_page" => $per_page,
                                   "page" => $page);

            $config['base_url'] = base_url('perusahaan/load_data/');
            $config['div'] = '#tableContent';
            $config['additional_param'] = 'serialize_form()';
            $config['total_rows'] = $this->perusahaan_model->select_total($config_search);
            $config['per_page'] = $per_page;
            $config['cur_page'] = $page;
            $config['uri_segment'] = 3;
            $config['num_links'] = 3;
            $this->jquery_pagination->initialize($config);
            $pagination = $this->jquery_pagination->create_links();

            $datas = array("perusahaanList" => $this->perusahaan_model->select($config_search),
                           "pagination" => $pagination,
                           "per_page" => $per_page,
                           "page" => $page);
            $this->load->view('perusahaan/perusahaan_data', $datas);

        } else {
            echo $this->form_validation->error('cari');
        }

    }

    public function add($save = '')
    {

        $this->load->model('perusahaan_model');
        $this->load->model('users_model');

        if($save === 'save') {

            $this->load->model('perusahaan_model');
            $this->load->library('form_validation');

            $this->form_validation->set_rules('nama_perusahaan', 'Nama Perusahaan', 'trim|xss_clean|required')
                                  ->set_rules('penanggung_jawab', 'Penanggung Jawab', 'trim|xss_clean|required')
                                  ->set_rules('daftar_pengguna[]', 'Dafar Pengguna', 'trim|xss_clean|required')
                                  ->set_rules('saldo', 'Saldo', 'trim|xss_clean|required')
                                  ->set_error_delimiters('<div class="alert alert-warning alert-dismissable">
                                                            <i class="fa fa-warning"></i>
                                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
            if ($this->form_validation->run() === TRUE) {

                $daftarPengguna = "";
                $postPengguna = $this->input->post('daftar_pengguna');
                if(!empty($postPengguna)) {
                foreach($postPengguna as $strPengguna) {
                      $daftarPengguna[] = $strPengguna;
                  }
                }

                $datas = array('nama_perusahaan' => $this->form_validation->set_value('nama_perusahaan'),
                                'penanggung_jawab' => $this->form_validation->set_value('penanggung_jawab'),
                                'saldo' => $this->form_validation->set_value('saldo'),
                                "daftarPengguna" => $daftarPengguna
                               );

                //echo json_encode($datas);
                //die();

                $result = $this->perusahaan_model->insert(json_encode($datas), true);
                if ($result !== false) {
                    echo json_encode(array('status' => '1',
                                           'error' => '',
                                           'insert_id' => $result,
                                           'token' => $this->security->get_csrf_hash()));
                } else {
                    echo json_encode(array('status' => '0', 'insert_id' => '', 'token' => $this->security->get_csrf_hash(), 'error' => $result));
                }

            } else {

                $error = $this->form_validation->error('nama_perusahaan');
                $error .= $this->form_validation->error('penanggung_jawab');
                $error .= $this->form_validation->error('daftar_pengguna[]');
                $error .= $this->form_validation->error('saldo');

                echo json_encode(array('status' => '0', 'insert_id' => '', 'token' => $this->security->get_csrf_hash(), 'error' => $error));

            }

        } else {

            $datas = array("operasionalLists" => $this->users_model->lists_pengguna_operasional(),
                     );
            $this->load->view('perusahaan/perusahaan_add', $datas);


        }
    }

    public function edit($id = '', $save = '')
    {

        if(!is_numeric($id)) {
            die('required numeric id');
        }

        $this->load->model('perusahaan_model');
        $this->load->model('users_model');

        if($save === 'save') {

            $this->load->library('form_validation');

            $this->form_validation->set_rules('id', 'ID', 'trim|xss_clean|required|integer')
                                  ->set_rules('saldo', 'Saldo', 'trim|xss_clean|required|numeric')
                                  ->set_rules('nama_perusahaan', 'Nama Perusahaan', 'trim|xss_clean|required')
                                  ->set_rules('penanggung_jawab', 'Penanggung Jawab', 'trim|xss_clean|required')
                                  ->set_error_delimiters('<div class="alert alert-warning alert-dismissable">
                                                            <i class="fa fa-warning"></i>
                                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
            if ($this->form_validation->run() === TRUE) {

                $daftarPengguna = "";
                $postPengguna = $this->input->post('daftar_pengguna');
                if(!empty($postPengguna)) {
                  foreach($postPengguna as $strPengguna) {
                    if(!empty($strPengguna)) {
                      $daftarPengguna[] = $strPengguna;
                    }
                  }
                }

                $datas = array('id' => $this->form_validation->set_value('id'),
                                'saldo' => $this->form_validation->set_value('saldo'),
                                'nama_perusahaan' => $this->form_validation->set_value('nama_perusahaan'),
                                'penanggung_jawab' => $this->form_validation->set_value('penanggung_jawab'),
                                "daftarPengguna" => $daftarPengguna
                               );

                //echo json_encode($datas);
                //die();

                $result = $this->perusahaan_model->update(json_encode($datas));
                if ($result !== false) {
                    echo json_encode(array('status' => '1',
                                           'error' => '',
                                           'token' => $this->security->get_csrf_hash()));
                } else {
                    echo json_encode(array('status' => '0', 'insert_id' => '', 'token' => $this->security->get_csrf_hash(), 'error' => $result));
                }

            } else {

                $error = $this->form_validation->error('id');
                $error .= $this->form_validation->error('saldo');

                echo json_encode(array('status' => '0', 'insert_id' => '', 'token' => $this->security->get_csrf_hash(), 'error' => $error));

            }

        } else {

            $datas = array("data" => $this->perusahaan_model->data($id),
                           "operasionalLists" => $this->users_model->lists_pengguna_operasional(),
                     );
            $this->load->view('perusahaan/perusahaan_edit', $datas);

        }
    }

    public function view($id = "") {
        if(!is_numeric($id)) {
            die('required numeric id');
        }

        $this->load->model('perusahaan_model');

        $datas = array("data" => $this->perusahaan_model->data($id),
                 );
        $this->load->view('perusahaan/perusahaan_view', $datas);
    }

    public function delete($id = '') {
        if(!is_numeric($id)) {
            die('required numeric id');
        }

        if($this->auth->isOperasional()) {
            die('required access for admin');
        }

        $this->load->model('perusahaan_model');
        $result = $this->perusahaan_model->delete($id);
        if($result) {
            echo '1';
        } else {
            echo $result;
        }
    }

}
