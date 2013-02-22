<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends CI_Controller{
    
    function __construct(){
        parent::__construct();
    }
    
    public function index(){
        // Load our view to be displayed
        // to the user
        $data['content'] = 'landing';
		$this->load->view('template', $data);
		//$this->load->view('landing');
    }
	
	public function login(){
        // Load the model
       // $this->load->model('login_model');
        // Validate the user can login
       $result = $this->users->validate();
        // Now we verify the result
        if(! $result){
	        log_message('debug', 'user couldn\'t login');
	        		$data['content'] = 'landing';
	        		$data['message'] = 'Could not log you in with those credentials';
	        		$this->load->view('template', $data);
            // If user did not validate, then show them login page again
	        
        }else{
            // If user did validate, 
            // Send them to members area
            redirect('home');
        }               
    }
    
    public function logout(){
       
       $this->session->sess_destroy();
       redirect('landing');
                     
    }
}
?>