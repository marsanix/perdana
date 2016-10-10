<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beranda extends CI_Controller {

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

		$headers = array("css_link" => array(array('href' => assets_url('plugins/fancyapps/source/jquery.fancybox.css', '?v=2.1.5'),
                                                   'attr' => 'type="text/css" media="screen"'),
											 array('href' => assets_url('plugins/iCheck/all.css'),
                                                   'attr' => 'rel="stylesheet"'),
                                       )
                   );
        load_header($headers);

		switch($this->auth->get_group_id()) {
			case '-1':
				$this->load->model('users_model');
				$datas = array("totals" => $this->users_model->get_totals());
				$this->load->view('beranda/beranda_superadmin', $datas);
			break;
			case '1':
				$this->load->model('users_model');
				$datas = array("totals" => $this->users_model->get_totals());
				$this->load->view('beranda/beranda_admin', $datas);
			break;
			case '2':

				$this->load->model('permintaan_model');

				$config_search = array("status_proses" => '0',
                                   	   "perusahaan_id" => $this->auth->get_perusahaan(),
                                 );
				$permintaanList = $this->permintaan_model->select($config_search);

				$datas = array('permintaanSelesaiTerakhir' => $this->permintaan_model->permintaan_selesai_terakhir(),
							   'permintaanList' => $permintaanList
							   );
				$this->load->view('beranda/beranda', $datas);
			break;

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
}
