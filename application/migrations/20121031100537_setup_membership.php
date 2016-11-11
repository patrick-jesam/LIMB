<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Setup_Membership extends CI_Migration {
	/*
	| -----------------------------------------------------
	| AUTHOR:			DEV PATRICK/CLOUDSKUL DEVS
	| -----------------------------------------------------
	| EMAIL:			patrickikoi@gmail.com
	| -----------------------------------------------------
	| COPYRIGHT:		RESERVED BY NUGI TECHNOLOGIES
	| -----------------------------------------------------
	*/

	public function __construct(){
		parent::__construct();
	}

	public function up(){
		if (! $this->db->table_exists('users')) {
			# code...
			$users = array(
				'userId' => array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE, 'auto_increment' => TRUE),
				'username' => array('type' => 'VARCHAR', 'constraint' => 250),
				'password' => array('type' => 'VARCHAR', 'constraint' => 250),
				'isActive' => array('type' => 'INT', 'constraint' => 9),
				'lastLogin' => array('type' => 'DATETIME'),
			);

			$this->dbforge->add_field($users, TRUE);
			$this->dbforge->add_key('userId', TRUE);
            $this->dbforge->create_table('users');
		}

		if(! $this->db->table_exists('roles')) {
			# code...
			$roles = array(
				'roleId' => array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE, 'auto_increment' => TRUE),
				'name' => array('type' => 'VARCHAR', 'constraint' => 200)
			);

			$this->dbforge->add_field($roles, TRUE);
			$this->dbforge->add_key('roleId', TRUE);
			$this->dbforge->create_table('roles');
		}

		if(! $this->db->table_exists('user_role')){
			# code...
			$userRole = array(
				'userId' => array('type' => 'INT', 'constraint' => 9),
				'roleId' => array('type' => 'INT', 'constraint' => 9)
			);

			$this->dbforge->add_field($userRole, TRUE);
			$this->dbforge->create_table('user_role');
		}

		if(! $this->db->table_exists('permissions')){
			# code...
			$permissions = array(
				'permId' => array('type' => 'INT', 'constraint' => 9, 'unsigned' => TRUE, 'auto_increment' => TRUE),
				'permDesc' => array('type' => 'VARCHAR', 'constraint' => 400),
			);

			$this->dbforge->add_field($permissions, TRUE);
			$this->dbforge->add_key('permId', TRUE);
			$this->dbforge->create_table('permissions');
		}

		if(! $this->db->table_exists('role_perm')){
			# code...
			$rolePerm = array(
				'permId' => array('type' => 'INT', 'constraint' => 9),
				'roleId' => array('type' => 'INT', 'constraint' => 9)
			);

			$this->dbforge->add_field($rolePerm, TRUE);
			$this->dbforge->create_table('role_perm');
		}
	}

	public function down(){
		$this->dbforge->drop_table('users');
		$this->dbforge->drop_table('roles');
		$this->dbforge->drop_table('user_role');
		$this->dbforge->drop_table('permissions');
		$this->dbforge->drop_table('role_perm');
	}
}