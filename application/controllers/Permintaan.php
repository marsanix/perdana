<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permintaan extends CI_Controller {

	function __construct() {
		parent::__construct();

		if(!$this->auth->isLogin()) {
            redirect('login');
        }

	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
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

        if($this->auth->isOperasional()) {
        	$saldo = '';
        	$perusahaan = $this->perusahaan_model->data($this->auth->get_perusahaan(), $this->auth->get_user_id());
        	$saldo = $perusahaan->saldo;
        	$datas = array("saldo" => $saldo);
        	$this->load->view('permintaan/permintaan', $datas);
        } else {
        	$this->load->model('users_model');
        	$datas = array("perusahaanList" => $this->perusahaan_model->lists(),
        				   "penggunaList" => $this->users_model->lists_pengguna_operasional(),
        				  );
        	$this->load->view('permintaan/permintaan_admin', $datas);
        }

        $permintaan_js = $this->load->view('permintaan/permintaan.js', '', true);
        $footers = array("js_link" => array(assets_url('plugins/fancyapps/lib/jquery.mousewheel-3.0.6.pack.js'),
                                            assets_url('plugins/fancyapps/source/jquery.fancybox.pack.js', '?v=2.1.5'),
                                            assets_url('plugins/iCheck/icheck.min.js'),
                                            ),
                         "js_script" => array($permintaan_js)
                        );
        load_footer($footers);

	}

    public function status($status = 'proses')
	{

		if(!in_array($status, array('proses','selesai'))) {
			die('status not exist');
		}
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
    				   "status" => $status
    				  );
    	$this->load->view('permintaan/permintaan_admin', $datas);

        $permintaan_js = $this->load->view('permintaan/permintaan.js', '', true);
        $footers = array("js_link" => array(assets_url('plugins/fancyapps/lib/jquery.mousewheel-3.0.6.pack.js'),
                                            assets_url('plugins/fancyapps/source/jquery.fancybox.pack.js', '?v=2.1.5'),
                                            assets_url('plugins/iCheck/icheck.min.js'),
                                            assets_url('plugins/autoNumeric/autoNumeric-min.js'),
                                            ),
                         "js_script" => array($permintaan_js)
                        );
        load_footer($footers);

	}

	public function load_data($page = 0) {
        $this->load->library('jquery_pagination');
        $this->load->library('form_validation');
        $this->load->model('permintaan_model');

        $this->form_validation->set_rules('cari', 'Cari', 'trim|xss_clean')
                              ->set_rules('per_page', 'Per Page', 'trim|xss_clean|numeric')
                              ->set_rules('cari_no_aju', 'No. Aju', 'trim|xss_clean')
                              ->set_rules('cari_status_proses','Status Proses','trim|xss_clean')
                              ->set_rules('cari_status_selesai','Status Selesai','trim|xss_clean')
                              ->set_rules('cari_status','Status','trim|xss_clean')
                              ->set_rules('cari_perusahaan','Nama Perusahaan','trim|xss_clean')
                              ->set_rules('cari_pengguna','Nama Pengguna','trim|xss_clean')
                              ->set_rules('cari_status_selesai','Status Selesai','trim|xss_clean')
                              ->set_error_delimiters('<div class="alert alert-warning alert-dismissable">
                                                  <i class="fa fa-warning"></i>
                                                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
        if ($this->form_validation->run() === TRUE) {

            $per_page = $this->form_validation->set_value('per_page');
            $no_aju = $this->form_validation->set_value('cari_no_aju');
            $status_proses = $this->form_validation->set_value('cari_status_proses');
            $status_selesai = $this->form_validation->set_value('cari_status_selesai');
            $status = $this->form_validation->set_value('cari_status');
            $perusahaan_id = $this->form_validation->set_value('cari_perusahaan');
            $created_by = $this->form_validation->set_value('cari_pengguna');

            //$perusahaan_id = '';
            if($this->auth->isOperasional()) {
            	$perusahaan_id = $this->auth->get_perusahaan();
            }

            if($status === 'proses') {
            	$status_proses = '0';
				$status_selesai = '';
            }

            $config_search = array("search" => $this->form_validation->set_value('cari'),
                                   "no_aju" => $no_aju,
                                   "status_proses" => $status_proses,
                                   "status_selesai" => $status_selesai,
                                   "perusahaan_id" => $perusahaan_id,
                                   "created_by" => $created_by,
                                   "per_page" => $per_page,
                                   "page" => $page);

            $config['base_url'] = base_url('permintaan/load_data/');
            $config['div'] = '#tableContent';
            $config['additional_param'] = 'serialize_form()';
            $config['total_rows'] = $this->permintaan_model->select_total($config_search);
            $config['per_page'] = $per_page;
            $config['cur_page'] = $page;
            $config['uri_segment'] = 3;
            $config['num_links'] = 3;
            $this->jquery_pagination->initialize($config);
            $pagination = $this->jquery_pagination->create_links();

            $datas = array("permintaanList" => $this->permintaan_model->select($config_search),
                           "pagination" => $pagination,
                           "status_proses" => $status_proses,
                           "status_selesai" => $status_selesai,
                           "status"	=> $status,
                           "per_page" => $per_page,
                           "page" => $page);
            if($this->auth->isOperasional()) {
            	$this->load->view('permintaan/permintaan_data', $datas);
            } else {
            	$this->load->view('permintaan/permintaan_admin_data', $datas);
            }

        } else {
            echo $this->form_validation->error('cari');
            echo $this->form_validation->error('cari_no_aju');
            echo $this->form_validation->error('cari_status_proses');
            echo $this->form_validation->error('cari_status_selesai');
            echo $this->form_validation->error('cari_perusahaan');
            echo $this->form_validation->error('cari_pengguna');
        }

    }

	public function add($save = '')
	{

		$this->load->model('perusahaan_model');
		$this->load->model('jenis_transaksi_model');

		if($save === 'save') {

      $this->load->model('permintaan_model');
			$this->load->model('perusahaan_model');
            $this->load->library('form_validation');

            $this->form_validation->set_rules('no_aju', 'No. Aju', 'trim|xss_clean')
            				      ->set_rules('nama_kapal', 'Nama Kapal', 'trim|xss_clean|required')
                                  ->set_rules('grt', 'GRT', 'trim|xss_clean|required')
                                  ->set_rules('perkiraan_kegiatan_from', 'Perkiraan Lama Kegiatan Dari', 'trim|xss_clean')
                                  ->set_rules('perkiraan_kegiatan_to', 'Perkiraan Lama Kegiatan Sampai', 'trim|xss_clean|required')
                                  ->set_rules('pemilik_kapal', 'Pemilik Kapal', 'trim|xss_clean|required')
                                  ->set_rules('posisi_kapal', 'Posisi Kapal', 'trim|xss_clean|required')
                                  ->set_rules('jenis_transaksi', 'Jenis Transaksi', 'trim|xss_clean|required')
                                  ->set_rules('jenis_jasa_labuh', 'Jenis Jasa Labuh', 'trim|xss_clean')
                                  ->set_rules('jenis_jasa_tambat', 'Jenis Jasa Tambat', 'trim|xss_clean')
                                  ->set_rules('jumlah_booking', 'Jumlah Booking', 'trim|xss_clean|required')
                                  ->set_error_delimiters('<div class="alert alert-warning alert-dismissable">
                                                            <i class="fa fa-warning"></i>
                                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
            if ($this->form_validation->run() === TRUE) {

            	$jenis_jasa_id = $this->form_validation->set_value('jenis_jasa_labuh') + $this->form_validation->set_value('jenis_jasa_tambat');
              $perusahaan_id = $this->auth->get_perusahaan();

                $datas = array('no_aju' => $this->form_validation->set_value('no_aju'),
                				'perusahaan_id' => $perusahaan_id,
                                'nama_kapal' => $this->form_validation->set_value('nama_kapal'),
								'grt' => $this->form_validation->set_value('grt'),
								'perkiraan_kegiatan_from' => $this->form_validation->set_value('perkiraan_kegiatan_from'),
								'perkiraan_kegiatan_to' => $this->form_validation->set_value('perkiraan_kegiatan_to'),
								'pemilik_kapal' => $this->form_validation->set_value('pemilik_kapal'),
								'posisi_kapal' => $this->form_validation->set_value('posisi_kapal'),
								'jenis_transaksi_id' => $this->form_validation->set_value('jenis_transaksi'),
								'jenis_jasa_id' => $jenis_jasa_id,
								'jumlah' => $this->form_validation->set_value('jumlah_booking'),
                               );

                //echo json_encode($datas);
                //die();

                $result = $this->permintaan_model->insert(json_encode($datas), true);
                if ($result !== false) {

                  $get_saldo = $this->perusahaan_model->get_saldo($perusahaan_id);

                    echo json_encode(array('status' => '1',
                                           'error' => '',
                                           'insert_id' => $result,
                                           'saldo' => FormatCurrency($get_saldo, 2),
                                           'token' => $this->security->get_csrf_hash()));
                } else {
                	echo json_encode(array('status' => '0', 'insert_id' => '', 'token' => $this->security->get_csrf_hash(), 'error' => $result));
                }

            } else {

            	$error = $this->form_validation->error('no_aju');
            	$error .= $this->form_validation->error('nama_kapal');
                $error .= $this->form_validation->error('grt');
                $error .= $this->form_validation->error('perkiraan_kegiatan_from');
                $error .= $this->form_validation->error('perkiraan_kegiatan_to');
                $error .= $this->form_validation->error('pemilik_kapal');
                $error .= $this->form_validation->error('posisi_kapal');
                $error .= $this->form_validation->error('jenis_transaksi');
                $error .= $this->form_validation->error('jenis_jasa_labuh');
                $error .= $this->form_validation->error('jenis_jasa_tambat');
                $error .= $this->form_validation->error('jumlah_booking');

                echo json_encode(array('status' => '0', 'insert_id' => '', 'token' => $this->security->get_csrf_hash(), 'error' => $error));

            }

        } else {

			$headers = array("css_link" => array(array('href' => assets_url('plugins/fancyapps/source/jquery.fancybox.css', '?v=2.1.5'),
	                                                   'attr' => 'type="text/css" media="screen"'),
												 array('href' => assets_url('plugins/iCheck/all.css'),
	                                                   'attr' => 'rel="stylesheet"'),
												 array('href' => assets_url('plugins/datepicker/datepicker3.css'),
	                                                   'attr' => 'rel="stylesheet"'),
												 array('href' => assets_url('plugins/daterangepicker/daterangepicker-bs3.css'),
	                                                   'attr' => 'rel="stylesheet"'),
	                                       )
	                   );
	        load_header($headers);

			$datas = array("perusahaan" => $this->perusahaan_model->data($this->auth->get_perusahaan(),
																		 $this->auth->get_user_id()
																		 ),
						   "jenis_transaksiList" => $this->jenis_transaksi_model->lists(),
					 );
			$this->load->view('permintaan/permintaan_add', $datas);

			$permintaan_js = $this->load->view('permintaan/permintaan_add.js', '', true);
	        $footers = array("js_link" => array(assets_url('plugins/fancyapps/lib/jquery.mousewheel-3.0.6.pack.js'),
	                                            assets_url('plugins/fancyapps/source/jquery.fancybox.pack.js', '?v=2.1.5'),
	                                            assets_url('plugins/iCheck/icheck.min.js'),
	                                            assets_url('js/moment.min.js'),
                                                assets_url('plugins/daterangepicker/daterangepicker.js'),
                                                assets_url('plugins/datepicker/bootstrap-datepicker.js'),
	                                            assets_url('plugins/input-mask/jquery.inputmask.js'),
                                                assets_url('plugins/input-mask/jquery.inputmask.date.extensions.js'),
                                                assets_url('plugins/input-mask/jquery.inputmask.extensions.js'),
                                          ),
	                         "js_script" => array($permintaan_js)
	                        );
	        load_footer($footers);

	    }
	}

	public function edit($id = '', $save = '')
	{

		if(!is_numeric($id)) {
			die('required numeric id');
		}

		$this->load->model('perusahaan_model');
		$this->load->model('permintaan_model');
		$permintaanData = $this->permintaan_model->data($id);

		if(empty($permintaanData)) {
			die('no record match for id: '.$id);
		}

		if($save === 'save') {

            $this->load->library('form_validation');

            $this->form_validation->set_rules('id', 'ID', 'trim|xss_clean|required|integer')
                                  ->set_rules('status', 'Status', 'trim|xss_clean|required')
                                  ->set_error_delimiters('<div class="alert alert-warning alert-dismissable">
                                                            <i class="fa fa-warning"></i>
                                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
            if ($this->form_validation->run() === TRUE) {

                $datas = array('id' => $this->form_validation->set_value('id'),
								'status' => $this->form_validation->set_value('status'),
								'perusahaan_id' => $permintaanData->perusahaan_id,
								'jumlah' => $permintaanData->jumlah
                               );

                //echo json_encode($datas);
                //die();

                $result = $this->permintaan_model->update(json_encode($datas));
                if ($result !== false) {
                    echo json_encode(array('status' => '1',
                                           'error' => '',
                                           'token' => $this->security->get_csrf_hash()));
                } else {
                	echo json_encode(array('status' => '0', 'insert_id' => '', 'token' => $this->security->get_csrf_hash(), 'error' => $result));
                }

            } else {

            	$error = $this->form_validation->error('id');
            	$error .= $this->form_validation->error('status');

                echo json_encode(array('status' => '0', 'insert_id' => '', 'token' => $this->security->get_csrf_hash(), 'error' => $error));

            }

        } else {

			$datas = array("perusahaan" => $this->perusahaan_model->data($permintaanData->perusahaan_id
																		 ),
						   "data" => $permintaanData,
					 );
			$this->load->view('permintaan/permintaan_edit', $datas);

	    }
	}

	public function view($id = "") {
		if(!is_numeric($id)) {
			die('required numeric id');
		}

    $this->load->model('permintaan_model');
		$this->load->model('perusahaan_model');

    $permintaanData = $this->permintaan_model->data($id);
        $datas = array("data" => $permintaanData,
                       "perusahaan" => $this->perusahaan_model->data($permintaanData->perusahaan_id)
                 );
        $this->load->view('permintaan/permintaan_view', $datas);
	}

	public function delete($id = '') {
        if(!is_numeric($id)) {
            die('required numeric id');
        }

        if($this->auth->isOperasional()) {
            die('required access for admin');
        }

        $this->load->model('permintaan_model');

        $permintaanData = $this->permintaan_model->data($id);
        $jumlah_booking = $permintaanData->jumlah;
        $perusahaan_id  = $permintaanData->perusahaan_id;

        $result = $this->permintaan_model->delete($id, $perusahaan_id, $jumlah_booking);
        if($result) {
            echo '1';
        } else {
            echo $result;
        }
    }

}
