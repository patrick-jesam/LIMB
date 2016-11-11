<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {
    /*
    | -----------------------------------------------------
    | AUTHOR:           DEV PATRICK/CLOUDSKUL DEVS
    | -----------------------------------------------------
    | EMAIL:            patrickikoi@gmail.com
    | -----------------------------------------------------
    | COPYRIGHT:        RESERVED BY NUGI TECHNOLOGIES
    | -----------------------------------------------------
    */

    public function __construct(){
        parent::__construct();
    }
 
    #extends theb form validation class to include a unique method
    #it can be invoked like this from a controller
    #$this->form_validation->set_rules('email', 'E-mail', 'required|valid_email|unique[User.email]');
    #where User is the name of the table and email is the field we wish to check
    public function unique($value, $params) {
 
        $CI =& get_instance();
        $CI->load->database();
 
        $CI->form_validation->set_message('unique',
            'The %s is already being used.');
 
        list($table, $field) = explode(".", $params, 2);
 
        $query = $CI->db->select($field)->from($table)
            ->where($field, $value)->limit(1)->get();
 
        if ($query->row()) {
            return false;
        } else {
            return true;
        }
 
    }
}