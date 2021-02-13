<?php

class Users extends CI_Controller{
    public function register(){
        // check user is logged in
        if($this->session->userdata('logged_in')/*
                && $this->session->userdata('user_type') == 'Technician'*/){
            redirect('salon_calendar');
        }

        // only accessible for technicians?

    	$data['title'] = 'Register A New Account';

        $this->form_validation->set_rules('username', 'Username', 'required|callback_check_username_exists');
        $this->form_validation->set_rules('forename', 'Forename', 'required');
        $this->form_validation->set_rules('surname', 'Surname', 'required');
    	$this->form_validation->set_rules('email', 'Email', 'required|callback_check_email_exists');
    	$this->form_validation->set_rules('password', 'Password', 'required');
    	$this->form_validation->set_rules('password2', 'Confirm Password', 'matches[password]');

    	if($this->form_validation->run() === FALSE){
    		$this->load->view('templates/header');
            $this->load->view('users/register', $data);
            $this->load->view('templates/footer');
    	} else {
    		$enc_password = md5($this->input->post('password'));

            // generate username from forename/surname (now manual)
            // $forename = substr(str_replace(" ", "", $this->input->post('forename')), 0, 1);
            // $surname = str_replace("-", ".", str_replace(" ", ".", $this->input->post('surname')));
            // $username = substr(strtolower($forename.".".$surname), 0, 32);
            // $i=1;
            // while (!$this->user_model->check_username_exists($username)) {
            //     $username = substr($username, (strlen($username)-1), 3).$i;
            //     $i++;
            // }
            // die($username);

			$this->user_model->register($enc_password);

			$this->session->set_flashdata('user_registered', 'The user is now registered and can log in.');

			redirect('users/login');
    	}
    }

    //check is username exists
    public function check_username_exists($username){
        $this->form_validation->set_message('check_username_exists', '<p style="color:red;">There is already an account with that username.</p>');

        if($this->user_model->check_username_exists($username)) {
            return true;
        } else {
            return false;
        }
    }

    //check is email exists
    public function check_email_exists($email){
        $this->form_validation->set_message('check_email_exists', '<p style="color:red;">The email entered already has an account associated with it.</p>');

        if($this->user_model->check_email_exists($email)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function login(){
        // check user is logged in
        if($this->session->userdata('logged_in')){// === TRUE){
            redirect('salon_calendar');
        }

        $data['title'] = 'Abingdon & Witney College Student Salons';
        $data['subtitle'] = 'Sign In';

        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if($this->form_validation->run() === FALSE){
            $this->load->view('templates/header');
            $this->load->view('users/login', $data);
            $this->load->view('templates/footer');
        } else {
            // login user
            $username = $this->input->post('username');
            $password = md5($this->input->post('password'));
            $user_type = $this->user_model->login($username, $password);

            if($user_type){
                //create user session
                $user_data = array(
                    'username'  => $username,
                    'user_type' => $user_type,
                    'logged_in' => TRUE,
                );
                $this->session->set_userdata($user_data);

                //message
                $this->session->set_flashdata('user_success', 'Welcome '.$username.'.');

                redirect('salon_calendar');
            } else {
                $this->session->set_flashdata('user_failed', 'Invalid log in, username or password not found.');

                redirect('users/login');
            }
        }
    }

    //user log out
    public function logout($data){
        //unset user session data 
        $this->session->unset_userdata('logged_in');
        $this->session->unset_userdata('user_type');
        $this->session->unset_userdata('username');

        //message
        $this->session->set_flashdata('user_warning', 'You are now logged out.');

        redirect("users/login");

    }
}