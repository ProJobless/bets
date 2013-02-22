<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class landing extends CI_Controller{
    
    function __construct(){
        parent::__construct();
    }
    
    public function index($msg=null){
     
		
			$data['msg']  = $msg;
			log_message('debug', 'landing\index');
			
        if(isset($this->session->userdata)){
	        log_message('debug', 'didnt validate');
            // If user did not validate, then show them login page again
	        	$data['content'] = 'landing';
	        	$this->load->view('template', $data);
        }else{
        		log_message('debug', 'user ' .$this->session->userdata('userid').' validated');
            // If user did validate, 
            // Send them to members area
            	redirect('home');
        }        
	
    }
	
	public function login(){
        // Load the model
		
        $this->load->model('users');
        // Validate the user can login
        $result = $this->users->validate();
        // Now we verify the result
        if(! $result){
		log_message('debug', 'didnt validate');
            // If user did not validate, then show them login page again
	        $this->index();
        }else{
            // If user did validate, 
            // Send them to members area
            redirect('home');
        }        
    }
	
	public function register(){
        $password = $this->security->xss_clean($this->input->post('password'));
		$cpassword = $this->security->xss_clean($this->input->post('cpassword'));
		
		if($password == $cpassword){
		// Load the model
		$this->load->model('users');
        // Validate the user can login
        $result = $this->users->register();
		
		}
        // Now we verify the result
		redirect('home');
             
    }
}
?>