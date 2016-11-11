<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//require(APPPATH.'libraries/Admin_Controller.php'); // contains some logic applicable only to the repository

class Account extends My_Controller {

	/*
	| -----------------------------------------------------
	| AUTHOR:			DEV PATRICK/CLOUDSKUL DEVS
	| -----------------------------------------------------
	| EMAIL:			patrickikoi@gmail.com
	| -----------------------------------------------------
	| COPYRIGHT:		RESERVED BY NUGI TECHNOLOGIES
	| -----------------------------------------------------
	*/

	protected $login_url;
	public function __construct(){
		parent::__construct();
		#load global model library
		$params = array('table_name' => 'users', 'primary_key' => 'id', 'order_by' => 'id');
		$this->load->model('global_m', '', FALSE, $params);
		$this->load->library('user_repo');
		$this->load->library('account_repo');

		//get application login url
		$this->login_url = $this->config->item('login_url');
	}

	protected function signin_rules(){
		$rules = array(
			array(
				'field' => 'username',
				'label' => 'username',
				'rules' => 'trim|required|xss_clean|unique[users.username]' 
			),
			array(
				'field' => 'password',
				'label' => 'password',
				'rules' => 'trim|required|xss_clean' 
			)
		);
		return $rules;
	}

	public function  signin(){
		if ($_POST) {
			# code...
			$username = $this->input->post('username');
			$password = $this->input->post('password');
		}else{
			$this->load->view('account/signin');
		}
	}

	public function signout(){
		$this->account_repo->logout();
	}

	public function register(){
		$userarray = array(
			'username' => 'Patrick',
			'password' => '1234',
			'isActive' => 1
		);

		$get_var = $this->user_repo->create_user($userarray);
		echo $get_var;
	}
}
