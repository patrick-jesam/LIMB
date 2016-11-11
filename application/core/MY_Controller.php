<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#require(APPPATH.'libraries/Public_Controller.php'); // contains some logic applicable only to `public` controllers
#require(APPPATH.'libraries/Admin_Controller.php'); // contains some logic applicable only to `admin` controllers

class MY_Controller extends CI_Controller {
/*
| -----------------------------------------------------
| AUTHOR:			DEV PATRICK/CLOUDSKUL DEVS
| -----------------------------------------------------
| EMAIL:			patrickikoi@gmail.com
| -----------------------------------------------------
| COPYRIGHT:		RESERVED BY NUGI TECHNOLOGIES
| -----------------------------------------------------
*/

	public $data = array();

	public function __construct() {
		parent::__construct();

		//Define application environment here
		//ENVIRONMENT != 'development' || $this->output->enable_profiler(TRUE);

		//Load Database
		$this->load->database();

		//Load common files for all controlers
		$this->load->library('session');
		$this->load->library('email');
		$this->load->library('form_validation');
		$this->load->helper('security');
		$this->load->helper('language');
		$this->load->helper('date');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('html');
	}

	 # returns true or false if user is logged in or not
    public function is_loggedin($userdata){
    	$userdata = $this->session->userdata();
    	if ($userdata['is_loggedin']) {
    		# code...
    		$this->data['user'] = $this->user_repo->get_by_id($userdata['userId']);
    		$this->data['user_role'] = $this->user_role_repo->get_user_role($userdata['userId']);
    		$this->data['user_in_role'] = $this->user_repo->get_user_inrole($userdata['userId']);
    		return TRUE;
    	}else{
    		redirect($login_url);
    	}
    }

}

/* End of file MY_Controller.php */
/* Location: .//D/xampp/htdocs/school/mvc/core/MY_Controller.php */