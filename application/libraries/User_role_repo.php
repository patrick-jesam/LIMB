<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_role_repo {
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
	public function __construct(){
		# load codeigniter global variable
		$this->CI =& get_instance();
		#load global model and pass parameters for data access
		$params = array('table_name' => 'user_role', 'primary_key' => 'userId', 'order_by' => 'userId');
		$this->CI->load->model('global_m', '', FALSE, $params);
	}

	public function get_user_role($userId){
		$query = "SELECT u.userId, u.roleId FROM user_role ur JOIN roles r ON ur.roleId = r.roleId WHERE ur.userId = $userId";
		$query_result = $this->CI->global_m->custom_builder($query);
		return $query_result;
	}

	public function create_user_role($array){
		$result = $this->CI->global_m->create($array);
		return TRUE;
	}

	public function edit_user_role($array, $userId){
		$result = $this->CI->global_m->edit($array, $userId);
		return TRUE;
	}

	public function delete_user_role($userID){
		$this->CI->global_m->delete($id);
		return TRUE;
	}
}