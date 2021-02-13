<?php

// Accesses class table, student table and session table
class Class_Model extends CI_Model{
	public function __construct(){
		$this->load->database();
	}

	//get class data from class table
	public function get_classes($id = FALSE){
		//if no variable is passed in, return whole table
		if($id === FALSE){
			$query = $this->db->get('class');
			return $query->result_array();
		}
		//else return row for specific ID
		$query = $this->db->get_where('class', array('Class_Id' => $id));
		return $query->row_array();
	}

	//get class session data from class_sessions table
	public function get_class_sessions($id = FALSE){
		//if no variable is passed in, return whole table
		if($id === FALSE){
			$query = $this->db->get('class_session');
			return $query->result_array();
		}
		//else return row for specific ID
		$query = $this->db->get_where('class_session', array('Class_Session_ID' => $id));
		return $query->row_array();
	}


	//get class data from class table
	public function get_students($id = FALSE){
		//if no variable is passed in, return whole table
		if($id === FALSE){
			$query = $this->db->get('student');
			return $query->result_array();
		}
		//else return row for specific ID
		$query = $this->db->get_where('student', array('Student_ID' => $id));
		return $query->row_array();
	}
}