<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account_repo {
	/*
	| -----------------------------------------------------
	| AUTHOR:			DEV PATRICK/CLOUDSKUL DEVS
	| -----------------------------------------------------
	| EMAIL:			patrickikoi@gmail.com
	| -----------------------------------------------------
	| COPYRIGHT:		RESERVED BY NUGI TECHNOLOGIES
	| -----------------------------------------------------
	*/

	protected $CI;
	protected $login_url;
	public function __construct(){
		# load codeigniter global variable
		$this->CI =& get_instance();
		#load global model and pass parameters for data access
		$params = array('table_name' => 'user', 'primary_key' => 'userId', 'order_by' => 'userId');
		$this->CI->load->model('global_m', '', FALSE, $params);
		$this->login_url = $this->CI->config->item('login_url');
		#load core libraries here
		$this->CI->load->library('session');
		$this->CI->load->library('user_repo');
		$this->CI->load->library('user_role_repo');
		$this->CI->load->helper('security');
		$this->CI->load->library('email');
	}

	public function change_password($array, $userId){
		$result = $this->CI->global_m->edit($array, $userId);
		return TRUE;
	}

	public function login($username, $password){
		if (!isset($username) && !isset($password)) {
			# code...
			return FALSE;
		}else{
			#hash gotten password to read database encryption
			$hash_password = $this->CI->global_m->hash($password);

			//query to check variables in database
			$query = "SELECT * FROM 'users' WHERE 'username'= '.$username.' AND 'password'= '.$hash_password.' LIMIT 1";
			$query_result = $this->CI->global_m->custom_builder($query);

			if ($query_result->num_rows() > 0 && $query_result->num_rows == 1) {
				# code...
				#if passed and user exists get the user details including his roles and permissions
				$user = $query_result->row();
				$userId = $user->userId;
				$user_query_result = $this->CI->user_repo->get_user_inrole($userId);
				$new_userdata = array(
					#set user session here
					'id' => $query_result->userId,
					'username' => $user_query_result->username,
					'is_loggedin' => $user_query_result->isActive,
					'last_login' => $user_query_result->lastLogin,
					'role_id' => $user_query_result->roleID,
					'role' => $user_query_result->name
				);

				$userdata = $this->CI->session->set_userdata($new_userdata);
				return $userdata;
			}else{
				return FALSE;
			}
		}
	}

	public function logout(){
		$this->CI->session->sess_destroy();
		redirect(base_url($this->login_url));
	}
}