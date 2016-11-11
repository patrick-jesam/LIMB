<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//require(APPPATH.'libraries/Account_repo.php'); // contains some logic applicable only to the repository
//require(APPPATH.'libraries/Role_repo.php'); // contains some logic applicable only to the repository
//require(APPPATH.'libraries/User_repo.php'); // contains some logic applicable only to the repository
//require(APPPATH.'libraries/User_role_repo.php'); // contains some logic applicable only to the repository

class MY_Model extends CI_Model {
/*
| -----------------------------------------------------
| AUTHOR:			DEV PATRICK/CLOUDSKUL DEVS
| -----------------------------------------------------
| EMAIL:			patrickikoi@gmail.com
| -----------------------------------------------------
| COPYRIGHT:		RESERVED BY NUGI TECHNOLOGIES
| -----------------------------------------------------
*/

	protected $_table_name = '';
	protected $_primary_key = '';
	protected $_primary_filter = 'intval';
	protected $_order_by = '';
	public $rules = array();

	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
	}

	#returns data array of posted form feilds
	public function array_from_post($fields) {
        $data = array();
        foreach ($fields as $field) {
            $data[$field] = $this->input->post($field);
        }
        return $data;
    }

	#get records from database as a result set or row set
	#where id is null a result set of all data is returned
	public function get($id = NULL){

		if ($id != NULL) {
			$filter = $this->_primary_filter;
			$id = $filter($id);
			$this->db->where($this->_primary_key, $id);
			$method = 'row';
		}else {
			$method = 'result';
		}

		if (!count($this->db->qb_orderby)) {
			$this->db->order_by($this->_order_by);
		}
		return $this->db->get($this->_table_name)->$method();
	}

	#checks if single data is present and returns a row set
	#$where = paramerter to check data
	#$tbl_name = name of table to check
	public function get_by($where) {
        $this->db->where($where);
        return $this->get(NULL, $single);
    }

	#returns an ordered result set
	#gets an array as argument
	#also gets the item to oder the query
	public function get_order_by($array=NULL, $order_result) {
		if($array != NULL) {
			$this->db->select()->from($this->_table_name)->where($array)->order_by($this->order_result);
			$query = $this->db->get();
			return $query->result();
		} else {
			$this->db->select()->from($this->_table_name)->order_by($this->order_result);
			$query = $this->db->get();
			return $query->result();
		}
	}

	#returns a single row or a result set
	#using an array with multiple parameters to query the database
	public function get_single($array=NULL) {
		if($array != NULL) {
			$this->db->select()->from($this->_table_name)->where($array);
			$query = $this->db->get();
			return $query->row();
		} else {
			$this->db->select()->from($this->_table_name)->order_by($this->_order_by);
			$query = $this->db->get();
			return $query->result();
		}
	}

	#inserts a new record to the datbase
	#returns the id of the last inserted row
	public function insert($array){
		$this->db->insert($this->_table_name, $array);
		$id = $this->db->insert_id();
		return $id;
	}

	#updates a record available in the database
	#takes the primary key as argument to check data existence
	public function update($data, $id = NULL) {
		$filter = $this->_primary_filter;
		$id = $filter($id);
		$this->db->set($data);
		$this->db->where($this->_primary_key, $id);
		$this->db->update($this->_table_name);
	}

	#deletes a single row from the database
	#using the Id as argument to target the row
	public function delete($id){
		$filter = $this->_primary_filter;
		$id = $filter($id);

		if (!$id) {
			return FALSE;
		}
		$this->db->where($this->_primary_key, $id);
		$this->db->limit(1);
		$this->db->delete($this->_table_name);
	}

	#deletes multiple records from the database
	#takes an argument that is similar to multiple rows in the datbase
	public function delete_multiple($where) {
        $this->db->where($where);
        $this->db->delete($this->_table_name);
    }

    #returns a hashed string
	public function hash($string) {
		return hash("sha512", $string . config_item("encryption_key"));
	}

	#checks if data exists in the database
	#if yes an update query is trigered
	#if NO then a new record is inserted into the database
	public function save($data, $id = NULL) {

		// Set timestamps
		if ($this->_timestamps == TRUE) {
			$now = date('Y-m-d H:i:s');
			$id || $data['created'] = $now;
			$data['modified'] = $now;
		}

		// Insert
		if ($id === NULL) {
			!isset($data[$this->_primary_key]) || $data[$this->_primary_key] = NULL;
			$this->db->set($data);
			$this->db->insert($this->_table_name);
			$id = $this->db->insert_id();
		}
		// Update
		else {
			$filter = $this->_primary_filter;
			$id = $filter($id);
			$this->db->set($data);
			$this->db->where($this->_primary_key, $id);
			$this->db->update($this->_table_name);
		}

		return $id;
	}

	#uploads image to a directory, it gets 3 arguments
	#first the name of the field
	#the path to the directory where image should be saved
	#the maximum size for the image
	public function uploadImage($field, $path, $max_size) {
		$config['upload_path'] = $path;
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size'] = $max_size;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($field)) {
            $error = $this->upload->display_errors();
            $type = "error";
            $message = $error;
            set_message($type, $message);
            return FALSE;
            // uploading failed. $error will holds the errors.
        } else {
            $fdata = $this->upload->data();
            $img_data ['path'] = $config['upload_path'] . $fdata['file_name'];
            $img_data ['fullPath'] = $fdata['full_path'];
            return $img_data;
            // uploading successfull, now do your further actions
        }
    }

    #uploads a file to a directory, it gets 3 arguments
	#first the name of the field
	#the path to the directory where file should be saved
	#the maximum size for the files
    public function uploadFile($field, $path, $max_size) {
        $config['upload_path'] = $path;
        $config['allowed_types'] = 'pdf|docx|doc|gif|jpg|jpeg|png';
        $config['max_size'] = $max_size;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($field)) {
            $error = $this->upload->display_errors();
            $type = "error";
            $message = $error;
            set_message($type, $message);
            return FALSE;
            // uploading failed. $error will holds the errors.
        } else {
            $fdata = $this->upload->data();
            $file_data ['fileName'] = $fdata['file_name'];
            $file_data ['path'] = $config['upload_path'] . $fdata['file_name'];
            $file_data ['fullPath'] = $fdata['full_path'];
            return $file_data;
            // uploading successfull, now do your further actions
        }
    }

    #uploads all types of files to a directory
    public function uploadAllType($field, $path, $max_size) {
        $config['upload_path'] = 'img/uploads/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '2048';

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($field)) {
            $error = $this->upload->display_errors();
            $type = "error";
            $message = $error;
            set_message($type, $message);
            return FALSE;
            // uploading failed. $error will holds the errors.
        } else {
            $fdata = $this->upload->data();
            $file_data ['fileName'] = $fdata['file_name'];
            $file_data ['path'] = $config['upload_path'] . $fdata['file_name'];
            $file_data ['fullPath'] = $fdata['full_path'];
            return $file_data;
            // uploading successfull, now do your further actions
        }
    }

    #multiple uploads of all file types to a directory
    public function multi_uploadAllType($field, $path, $max_size) {
        $config['upload_path'] = 'img/uploads/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '2048';
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_multi_upload($field)) {
            $error = $this->upload->display_errors();
            $type = "error";
            $message = $error;
            set_message($type, $message);
            return FALSE;
            // uploading failed. $error will holds the errors.
        } else {
            $multi_fdata = $this->upload->get_multi_upload_data();
            
            foreach ($multi_fdata as $fdata) {

                $file_data ['fileName'] = $fdata['file_name'];
                $file_data ['path'] = $config['upload_path'] . $fdata['file_name'];
                $file_data ['fullPath'] = $fdata['full_path'];
                $file_data ['size'] = $fdata['file_size'];
                $file_data ['ext'] = $fdata['file_ext'];
                $file_data ['is_image'] = $fdata['is_image'];
                $file_data ['image_width'] = $fdata['image_width'];
                $file_data ['image_height'] = $fdata['image_height'];
                $result[] = $file_data;
            }
            return $result;
            // uploading successfull, now do your further actions
        }
    }

    #returns total count of items in a table
    public function get_total_count() {
    	$this->db->select('*', FALSE);
        $this->db->from($_table_name);
        $query_result = $this->db->get();
        $result = $query_result->result();
        return count($result);
    }
 	
 	#returns total count of items in a table
 	#using the $where parameter to filter the query result
	public function get_total_count_where($where) {
		$this->db->select('*', FALSE);
        $this->db->from($_table_name);
        $this->db->where($where);
        $query_result = $this->db->get();
        $result = $query_result->result();
        return count($result);
	}

	#returns query result with a limit which is specified dynamically
	#recieves the limit as an argument into the function
	#it gets total result without restrictions
	public function get_limit($limit){
		$this->db->select('*');
		$this->db->from($_table_name);
		$this->db->limit($limit);
		$this->db->order_by($_order_by);
		$query_result = $this->db->get();
		$result = $query_result->result();
		return $result;
	}

	#returns a query result with a limit but it also accepts
	#an extra argument of '$where' which spcifies the filters
	#on which the query will be returned
	public function get_limit_where($where, $limit){
		$this->db->select('*');
        $this->db->from($_table_name);
        $this->db->limit($limit);
        $this->db->where($where);
        $this->db->order_by($_order_by, "DESC");
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
	}

}

/* End of file MY_Model.php */
/* Location: .//D/xampp/htdocs/school/mvc/core/MY_Model.php */