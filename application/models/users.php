<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class users extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function validate(){
		// grab user input
		$username = $this->security->xss_clean($this->input->post('username'));
		$password = $this->security->xss_clean($this->input->post('password'));
		
		// Prep the query
		$where = "password =  '". $password . "' AND (username =  '" . $username . "' OR email =  '" . $username . "')";
		$this->db->where($where);
		
		// Run the query
		$query = $this->db->get('users');
		
		//log_message('debug',  $this->db->last_query());
		
		// Let's check if there are any results
		if($query->num_rows == 1)
		{
		
			// If there is a user, then create session data
			$row = $query->row();
			$data = array(
					'userid' => $row->userid,
					'username' => $row->username,
					'validated' => true
					);
			$this->session->set_userdata($data);
			return true;
		}
		// If the previous process did not validate
		// then return false.
		return false;
	}
	
	public function register(){
		// grab user input
		$username = $this->security->xss_clean($this->input->post('username'));
		$email = $this->security->xss_clean($this->input->post('email'));
		$password = $this->security->xss_clean($this->input->post('password'));
		$cpassword = $this->security->xss_clean($this->input->post('cpassword'));
		

		
		// Prep the query
		$data = array(
		   'email' => $email ,
		   'username' => $username ,
		   'password' => $password
		);	
		$this->db->insert('users', $data); 
		$result = $this->db->affected_rows();
		
		if($result == 1){
			$data = array(
					'userid' => $row->userid,
					'username' => $row->username,
					'validated' => true
					);
			$this->session->set_userdata($data);
			return true;
		}
		
		
		// If the previous process did not validate
		// then return false.
		return false;
	}


	public function getAll(){
		$this->db->select('userid, username');
		$query = $this->db->get('users');
		$users = array();
		foreach ($query->result() as $row)
		{
			$users[] =  $row->username;
		}
		
		return $users;
	
	
	}
	
	public function getAllId(){
		$this->db->select('userid, username');
		$query = $this->db->get('users');
		$users = array();
		foreach ($query->result() as $row)
		{
			$users[$row->userid] =  $row->username;
		}
		
		return $users;
	
	
	}
	
	//add a loss to the user
	public function addLoss($userid){
		$sql = "UPDATE users
						SET loss=loss+1
							WHERE userid = ".$userid;
		$this->db->query($sql);
		log_message('debug',$this->db->last_query());
	
	}
	
	//add a win to the user
	public function addWin($userid){
		$sql = "UPDATE users
						SET win=win+1
							WHERE userid = ".$userid;
							$this->db->query($sql);
		log_message('debug',$this->db->last_query());
	
	}
	
		//Get user's winning percentage
	public function getWinningPercentage(){
		$this->db->select('win, loss');
		$this->db->where('userid',$this->session->userdata('userid'));
		$query = $this->db->get('users');
			if($query->num_rows == 1)
		{
		
			// If there is a user, then create session data
			$row = $query->row();
			if($row->win + $row->loss > 0){
				 $dec = $row->win/($row->win+$row->loss);
				 $dec = $dec*100;
			}else {
				
				$dec=0;
			}
			 return $dec;
		}
			else {
				return 0;
			}	
	
	}
	
	
	//Get user's winnings
	public function getWinnings(){
		$this->db->select('winnings');
		$this->db->where('userid',$this->session->userdata('userid'));
		$query = $this->db->get('users');
			if($query->num_rows == 1)
		{
		
			// If there is a user, then create session data
			$row = $query->row();
			return $row->winnings;
		}
			else {
				return 0;
			}	
	
	}
	
	
	
	//Change user's winnings
	public function changeWinnings($userid,$winnings){
				$sql = "UPDATE users
						SET winnings=winnings+".$winnings."
							WHERE userid = ".$userid;
							$this->db->query($sql);
		log_message('debug',$this->db->last_query());
	
	}
	
		public function getName($userid){
			$this->db->where('userid',$userid);
			$query = $this->db->get('users');
			if($query->num_rows == 1)
			{
		
			// If there is a user, then create session data
			$row = $query->row();
			return $row->username;
			}
		}
	
}
?>