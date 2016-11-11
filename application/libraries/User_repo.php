<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_repo {
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
		$params = array('table_name' => 'users', 'primary_key' => 'userId', 'order_by' => 'userId');
		$this->CI->load->model('global_m', '', FALSE, $params);
	}

	public function get_users(){
		$total_users = $this->CI->global_m->get();
		return $total_users;
	}

	public function get_by_username($username){
		$user = $this->CI->global_m->get_item(array('username' => $username));
		return $user;
	}

	public function get_by_id($id){
		$user = $this->CI->global_m->get_item(array('id' => $id));
		return $user;
	}

	public function get_user_inrole($userId){
		$query = "SELECT u.userId, u.roleId FROM user_role ur JOIN roles r ON ur.roleId = r.roleId JOIN users u on ur.userId = u.userId WHERE ur.userId = $userId";
		$query_result = $this->CI->global_m->custom_builder($query);
		return $query;
	}

	public function create_user($array){
		$result = $this->CI->global_m->create($array);
		return $result;
	}

	public function edit_user($array, $id){
		$result = $this->CI->global_m->edit($array, $id);
		return $result;
	}

	public function delete_user($id){
		$this->CI->global_m->delete($id);
		return TRUE;
	}

	public function delete_multiple_users($where){
		$this->CI->global_m->delete_multiple($where);
		return TRUE;
	}
}