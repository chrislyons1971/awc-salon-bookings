<?php

// Accesses user table and bookings table
class User_Model extends CI_Model{
	public function register($enc_password){
		if ($this->input->post('isadmin') == "2") {
			$isadmin = "Teacher";
		} elseif ($this->input->post('isadmin') == "3") {
			$isadmin = "Technician";
		} else {
			$isadmin = "Client";
		}

		// codeigniter automatically escapes any values passed in using the form methods and the $this->input->post() method
		$data = array(
			'Username' 		=> $this->input->post('username'),
			'Forename'		=> $this->input->post('forename'),
			'Surname'		=> $this->input->post('surname'),
			'Email'			=> $this->input->post('email'),
			'Password' 		=> $enc_password,
			'User_Type' 	=> $isadmin
		);

		return $this->db->insert('user', $data);
	}

	//user log in
	public function login($username, $password){
		//validate using SQL
		$condition = "username =" . "'" . $username . "' AND " . "password =" . "'" . $password . "'";
		// codeigniter automatically escapes any values passed in using the form methods and the $this->input->post() method
		$this->db->select('*');
		$this->db->from('user');
		$this->db->where($condition);
		$this->db->limit(1);
		$result = $this->db->get();

		if ($result->num_rows() == 1) {
			$row = $result->row_array();
			foreach($row as $key => $field)
			   if ($key == 'User_Type') return $field;
		} 
		return false;
	}

	//get customer salon bookings from booking table
	public function get_bookings($id = FALSE){
		//if no variable is passed in, return whole table
		if($id === FALSE){
			$query = $this->db->get('booking');
			return $query->result_array();
		}
		//else return row for specific ID
		$query = $this->db->get_where('booking', array('Booking_ID' => $id));
		return $query->row_array();
	}

	//check username exists
	public function check_username_exists($username){
		$query = $this->db->get_where('user', array('Username' => $username));

		if (is_null($query->row_array())) return true;
		else return false;
	}

	//check email exists
	public function check_email_exists($email){
		$query = $this->db->get_where('user', array('Email' => $email));

		if (is_null($query->row_array())) return true;
		else return false;
	}
}