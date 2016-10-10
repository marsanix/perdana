<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trace extends CI_Controller {

	function __construct() {
		parent::__construct();

		if(!$this->auth->isLogin()) {
            redirect('login');
        }
	}

	public function get_token()
	{
		echo $this->security->get_csrf_hash();
	}
}
