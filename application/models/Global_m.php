<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Global_m extends MY_Model{
/*
| -----------------------------------------------------
| AUTHOR:			DEV PATRICK/CLOUDSKUL DEVS
| -----------------------------------------------------
| EMAIL:			patrickikoi@gmail.com
| -----------------------------------------------------
| COPYRIGHT:		RESERVED BY NUGI TECHNOLOGIES
| -----------------------------------------------------
*/
	protected $_table_name;
	protected $_primary_key;
	protected $_primary_filter = 'interval';
	protected $_order_by;

	public function __construct($params) {
		parent::__construct();
		##
		##load database helpers from to class variables
		##
		$this->_table_name = $params['table_name'];
		$this->_primary_key = $params['primary_key'];
		$this->_order_by = $params['order_by'];
	}

	public function get($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_by($array){
		$query = parent::get_by($array);
		return $query;
	}
	
	public function get_item($array) {
		$query = parent::get_single($array);
		return $query;
	}
	
	public function create($array){
		$id = parent::insert($array);
		return $id;
	}
	
	public function edit($data, $id){
		parent::update($data, $id);
		return TRUE;
	}
	
	public function delete($id){
		parent::delete($id);
	}

	public function delete_multiple($where){
		parent::delete_multiple($where);
	}

	public function hash($string){
		parent::hash($string);
	}

	public function custom_builder($string){
		$query = $this->db->query($string);
		return $query->result();
	}
}

/* End of file Global_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/core/Global_m.php */