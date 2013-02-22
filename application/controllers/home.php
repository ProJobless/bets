<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class Home extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->check_isvalidated();
	}
	
	public function index(){
		// If the user is validated, then this function will run
		
		$this->load->model('users');
		$this->load->model('bet');
		
		$data['users'] =  $this->users->getAll();
		$data['winnings'] = $this->users->getWinnings();
		$data['winningPercentage'] = $this->users->getWinningPercentage();
		$data['recent-activity'] = $this->bet->get_recent_activity(25);
		$data['content'] = 'user_home';
		$this->load->view('template', $data);
		
	}
	
	public function browse(){
		// If the user is validated, then this function will run
		
		
		$data['users'] =  $this->users->getAll();
		$data['content'] = 'browse_open_bets';
		$this->load->view('template', $data);
		
	}

public function my(){
		// If the user is validated, then this function will run
		

		
		$data['users'] =  $this->users->getAll();
		$data['content'] = 'my_bets';
		$this->load->view('template', $data);
		
	}

	
	private function check_isvalidated(){
		if(! $this->session->userdata('validated')){
			redirect('landing');
		}
	}
	
	public function do_logout(){
        $this->session->sess_destroy();
        redirect('landing');
    }
 }
 ?>