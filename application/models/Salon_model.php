<?php

// Accesses salon table and workspace table and student-workspace table
class Salon_Model extends CI_Model{
	public function __construct(){
		$this->load->database();
	}

	//get salon session data from student_workspace table
	public function get_salons($id = FALSE){
		//if no variable is passed in, return whole table
		if($id === FALSE){
			$query = $this->db->get('salon');
			return $query->result_array();
		}
		//else return row for specific ID
		$query = $this->db->get_where('salon', array('Salon_Room_Name' => $id));
		return $query->row_array();
	}

	//get workspaces from workspace table
	public function get_workspaces($id = FALSE){
		//if no variable is passed in, return whole table
		if($id === FALSE){
			$query = $this->db->get('workspace');
			return $query->result_array();
		}
		//else return row for specific ID
		$query = $this->db->get_where('workspace', array('Workspace_ID' => $id));
		return $query->row_array();
	}


	//get salon session data from student_workspace table
	public function get_student_workspaces($id = FALSE){
		//if no variable is passed in, return whole table
		if($id === FALSE){
			$query = $this->db->get('student_workspace');
			return $query->result_array();
		}
		//else return row for specific ID
		$query = $this->db->get_where('student_workspace', array('Student_Workspace_ID' => $id));
		return $query->row_array();
	}


}