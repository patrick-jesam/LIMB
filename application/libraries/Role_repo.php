<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_repo{
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
		$params = array('table_name' => 'role', 'primary_key' => 'roleId', 'order_by' => 'roleId');
		$this->CI->load->model('global_m', '', FALSE, $params);
	}

	public function get_role(){
		$roles = $this->CI->global_m->get();
		return $roles;
	}

	public function create_role($array){
		$role = $this->CI->global_m->create($array);
		return $role;
	}

	public function edit_role($array, $id){
		$role = $this->CI->global_m->edit($array, $id);
		return $role;
	}

	public function delete_role($id){
		$this->CI->global_m->delete($id);
		return TRUE;
	}
}