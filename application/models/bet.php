<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//Open\nAccepted\nPending\nClosed\nPaid\nTrashed
//-----Status Name-----|-  Description --------------------              | ------ Available Actions------
//        Open         | new bet waiting for other party to accept       |     Accept, Cancel
//       In Progress   | Bets been accepted, bet is in progress          |   You Won, I Won
//       Pending        | both people have agreed on who has won          |   They Paid, I Paid
//       Paid          |     It's been paid - closed
//       Declined	     |   It's been Rejected                            |   None--- change user2


//No More---        Pending -    | One person has said someone has won             |  


class bet extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	
	
	//create a new bet 
	public function create(){
		// grab user input
log_message('debug', 'Open bet param: ' . $this->input->post('open-bet'));
		$against = ( $this->security->xss_clean($this->input->post('open-bet')) =='true' ? 'Open' :   $this->security->xss_clean($this->input->post('against')));
		$title = $this->security->xss_clean($this->input->post('name'));
		$conditions = $this->security->xss_clean($this->input->post('conditions'));
		$user1_wager = $this->security->xss_clean($this->input->post('wagerYou'));
		$user2_wager = $this->security->xss_clean($this->input->post('wagerThem'));
		$event_date = null;
		log_message('debug',$this->input->post('event-date'));
			log_message('debug',$against);
		$ed = $this->input->post('event-date');
		if(isset($ed)){
			log_message('debug',$ed);
			if(strtotime($ed) != false){
				$event_date = date('Y-m-d', strtotime(str_replace('-', '/', $ed)));;
				log_message('debug',$event_date);
			}
			
		}
		
		
		
		if(! is_numeric($user1_wager))
		{
			return 'Please enter a number for your wager';
		}
		
		if(! is_numeric($user2_wager))
		{
			return 'Please enter a number for your challenger\'s wager';
		}
		
		if($against == 'Open'){
			log_message('debug','its an open bet');
			$against = -1;
			$challenger_message = 'Waiting for someone to accept your bet';
			$user2_message = $this->session->userdata('username') . ' created a new open bet';
			}else{
				$this->db->where('username',$against);
				$query = $this->db->get('users');
				$challenger_message = 'Waiting for '.$against.' to accept your bet';
				$user2_message = $this->session->userdata('username') . ' has bet you';
		//Find the user they're challenging
			if($query->num_rows == 1){
				$row = $query->row();
				$against = $row->userid;
			
			}
			else{
				return 'User does not exist';
			}
		}
		
		// Prep the query
		$data = array(
		   'challenger' => $this->session->userdata('userid') ,
		   'user2' => $against ,
		   'title' => $title,
		   'conditions' => $conditions,
		   'user1_wager' => $user1_wager,
		   'user2_wager' => $user2_wager,
		   'updated' => date('Y:m:d H:i:s', time()),
		   'last_action' => 'Bet created by '. $this->session->userdata('username'),
		   'event_date' => $event_date,
		   'challenger_message' => $challenger_message,
		   'user2_message' => $user2_message
		);	
		
		
		// Run the query
		$query = $this->db->insert('bets',$data);
		log_message('debug', $this->db->last_query());
		
		// Let's check if there are any results
		if($query == 1)
		{
		
			return 'Bet Created';
		}
		// If the previous process did not validate
		// then return false.
		return 'Unsuccessful';
	}
	
	public function cancel($betid){
		$this->db->where('betid',$betid);
		
		$data = array(
               'accepted' => -1,
               'status' => 'Canceled',
			   'updated' => date('Y:m:d H:i:s', time()),
			   'last_action' => 'Bet declined by '. $this->session->userdata('username'),
			   	'challenger_message' =>  'You canceled the bet',
			   	'user2_message' => $this->session->userdata('username') . ' canceled the bet.'
            );
		$query = $this->db->update('bets', $data); 
		// Let's check if there are any results
		if($query == 1)
		{
		
			return 'Success';
		}
		// If the previous process did not validate
		// then return false.
		return 'Unsuccessful';
	}

	
		
	
	
	//where the user has been challenged and they haven't accepted yet
	public function challenged(){
		
		$this->db->where('user2', $this->session->userdata('userid'));
		//$this->db->where('accepted',0);
		$this->db->where('status','Open');
		
		$query = $this->db->get('bets');
		
		$results = array();
		
		 $this->load->model('users');
		$users = $this->users->getAll();
		
		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{	
				$results[] = array('betid'=>$row->betid, 'challenger_id'=>$row->challenger, 'challenger'=> $users[$row->challenger],'title'=>$row->title,'conditions'=>$row->conditions,'user1_wager'=>$row->user1_wager,'user2_wager'=>$row->user2_wager,'date_created'=>$row->created_date, 'status'=>$row->status);
			}	
		}
		else {
			return 'None';
		}
			
			return $results;
	}
	
	//where the user is the challenger and the other user hasn't accepted
	public function my_open_challenges(){
		
		$this->db->where('challenger', $this->session->userdata('userid'));
		//Old way -- $this->db->where('accepted',0);
		$this->db->where('status','Open');
		
		$query = $this->db->get('bets');
		
		$results = array();
		
		 $this->load->model('users');
		$users = $this->users->getAllId();
		
		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{	
				$results[] = array('betid'=>$row->betid, 'user2_id'=>$row->user2, 'user2'=> $users[$row->user2],'title'=>$row->title,'conditions'=>$row->conditions,'user1_wager'=>$row->user1_wager,'user2_wager'=>$row->user2_wager,'date_created'=>$row->created_date, 'status'=>$row->status);
			}	
		}
		else {
			return 'None';
		}
			
			return $results;
	}
	
	//get all open bets
	//All bets where there is no user2 and user is not the challenger
	public function getOpen(){
		// this was the old way before statuses
		//$where = "(user2 =  '".  $this->session->userdata('userid') . "' OR challenger = '" .  $this->session->userdata('userid') . "') AND accepted = 1 AND winner is null";
		$where = "(user2 =  '".  $this->session->userdata('userid') . "' OR challenger = '" .  $this->session->userdata('userid') . "' OR user2='-1') AND (status = 'Open')";
		
		$this->db->where($where);
		
		
		$query = $this->db->get('bets');

		$results = array();
		
		$this->load->model('users');
		
		$obet = null;
		$mybet=null;
		
		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{	
				
				
						$result = $this->process_open($row);
					
			
			
				
				$results[] = $result;
		}
		}else {
			return 'None';
		}
			
			return $results;
	}
	
	
		//get all open bets
	//All bets where there is no user2 and user is not the challenger
	public function getBrowse(){
		// this was the old way before statuses
		//$where = "(user2 =  '".  $this->session->userdata('userid') . "' OR challenger = '" .  $this->session->userdata('userid') . "') AND accepted = 1 AND winner is null";
		//$where = "(user2 =  '".  $this->session->userdata('userid') . "' OR challenger = '" .  $this->session->userdata('userid') . "' OR user2='-1') AND (status = 'Open')";
		$where = "(user2='-1' AND challenger <> '" . $this->session->userdata('userid') . "') AND (status = 'Open')";
		$this->db->where($where);
		
		
		$query = $this->db->get('bets');

		$results = array();
		
		$this->load->model('users');
		
		$obet = null;
		$mybet=null;
		
		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{	
				
				
						$result = $this->process_open($row);
					
			
			
				
				$results[] = $result;
		}
		}else {
			return 'None';
		}
			
			return $results;
	}
	
	//All in progress bets for the user
	public function getInProgress(){

		$where = "(user2 =  '".  $this->session->userdata('userid') . "' OR challenger = '" .  $this->session->userdata('userid') . "') AND (status = 'In Progress')";
		$this->db->where($where);
		
		
		$query = $this->db->get('bets');

		$results = array();
		
		$this->load->model('users');
		
		$obet = null;
		$mybet=null;
		
		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{	
				$result = $this->process_in_progress($row);
				$results[] = $result;
			}
		}
		else {
			return 'None';
		}
			
			return $results;
	}
	

	//All pending bets for the user
	public function getPending(){

		$where = "(user2 =  '".  $this->session->userdata('userid') . "' OR challenger = '" .  $this->session->userdata('userid') . "') AND (status = 'Pending')";
		$this->db->where($where);
		
		
		$query = $this->db->get('bets');

		$results = array();
		
		$this->load->model('users');
		
		$obet = null;
		$mybet=null;
		
		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{	
						$result = $this->process_pending($row);
						$results[] = $result;
						}
		}
		else {
			return 'None';
		}
			
			return $results;
	}
	
	
		public function getClosed(){

		$where = "(user2 =  '".  $this->session->userdata('userid') . "' OR challenger = '" .  $this->session->userdata('userid') . "') AND (status = 'Closed' OR status = 'Paid')";
		$this->db->where($where);
		
		
		$query = $this->db->get('bets');

		$results = array();
		
		$this->load->model('users');
		
		$obet = null;
		$mybet=null;
		
		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{	
						$result = $this->process_closed($row);
						$results[] = $result;
						}
		}
		else {
			return 'None';
		}
			
			return $results;
	}
	
	//ACCEPT A BET FOR THE CURRENT USER
	// Must be in Status Open -- moves status to in progress
	public function accept($betid)
	{
		$this->db->where('betid',$betid);
		
		$data = array(
               'accepted' => 1,
               'user2' => $this->session->userdata('userid'),
               'status' => 'In Progress',
			   'updated' => date('Y:m:d H:i:s', time()),
			   'last_action' => 'Bet accepted by '. $this->session->userdata('username'),
			   	'challenger_message' => 'Your bet has been accepted by ' . $this->session->userdata('username'),
			   	'user2_message' => 'You accepted the bet.'
            );
		$query = $this->db->update('bets', $data); 
		// Let's check if there are any results
		if($query == 1)
		{
		
			return 'Success';
		}
		// If the previous process did not validate
		// then return false.
		return 'Unsuccessful';
	}
	
	//Decline a bet for the current user
	public function decline($betid)
	{
		$this->db->where('betid',$betid);
		$data = array(
               'accepted' => -1,
               'status' => 'Declined',
			   'updated' => date('Y:m:d H:i:s', time()),
			   'last_action' => 'Bet declined by '. $this->session->userdata('username'),
			   	'challenger_message' => 'Your bet has been declined by ' . $this->session->userdata('username'),
			   	'user2_message' => 'You declined the bet.'
			   
            );
		$query = $this->db->update('bets', $data); 
		// Let's check if there are any results
		if($query == 1)
		{
		
			return 'Success';
		}
		// If the previous process did not validate
		// then return false.
		return 'Unsuccesful';
	}
	
	//vote for a bet for the current user
	//@betid - the bet to bet on
	//@vote - who to vote for
	public function vote($betid,$vote)
	{
		try{
		log_message('debug','Vote: user ' .  $this->session->userdata('userid') . ' voted for ' . $vote . ' for bet ' . $betid);
			$this->load->model('users');
			
			$voter = $this->session->userdata('userid');
			$bet = $this->get($betid);
			$won = false;
			//Did they vote for themself
			$vote_themself = ($vote == $voter);
			
			$bet_closed = false;
			$data = array('updated' => date('Y:m:d H:i:s', time()));
			$other = '';
			
			
			//check if the voter is the challenger or user2
			if ($voter == $bet->challenger)
			{
				//the voter is the challenger
				$data['challenger_vote'] = $vote;
				log_message('debug','the voter is the challenger');
				
				$other = $this->users->getName($bet->user2);
				//$data['status'] = 'Pending';
				//check if it's over
				if($bet->user2_vote == $vote){
					//it's over
					$data['winner'] = $vote;
					log_message('debug','it\'s over '. $vote .' won ');
					$won = true;
					$winner = $this->users->getName($vote);
					$data['status'] = 'Pending';
					//Check if they voted themself the winner
					if(!$vote_themself){
						//if they didn't then they lost and user2 won
						log_message('debug','they lost and user2 won');
						$this->users->addLoss($voter);
						$this->users->addWin($bet->user2);
						$data['challenger_message'] = 'You lost the bet, pay '. $this->users->getName($bet->user2) .' $'.$bet->user1_wager;
						log_message('debug','changing winnings for user ' . $bet->user2. ' this many $'.(-1*(int)($bet->user1_wager)));
						//subtract from the challenger's winnings
						$this->users->changeWinnings($this->session->userdata('userid'),(-1*(int)$bet->user1_wager));	
						
						$data['user2_message'] = 'You won the bet, collect $' . $bet->user1_wager . ' from ' . $this->session->userdata('username');
						//Add to user2's winnings
							log_message('debug','changing winnings for user ' . $bet->user2. ' this many $'.(int)($bet->user1_wager));
						$this->users->changeWinnings($bet->user2,(int)($bet->user1_wager));	
						
					} else {
						//they did win, then the voter (challenger) won and the other user lost
							log_message('debug','they (challenger) won');
							$this->users->addLoss($bet->user2);
							$this->users->addWin($bet->challenger);
							$data['challenger_message'] = 'You won the bet, collect $' . $bet->user2_wager . " from " . $other;
							//Add to the challenger's winnings
							log_message('debug','changing winnings for user ' . $this->session->userdata('userid'). ' this many $'.(int)($bet->user1_wager));
							$this->users->changeWinnings($this->session->userdata('userid'),(int)($bet->user1_wager));	
						
							$data['user2_message'] = 'You lost the bet, pay ' . $this->session->userdata('username') . " $" .  -1*(int)$bet->user2_wager; 	
							//subtract from user2's winnings
							$this->users->changeWinnings($bet->user2,(-1*(int)$bet->user1_wager));	
						
					}
					
				}else{
					//it's not over
					log_message('debug','it\'s not over');
					$data['challenger_message'] = (($vote_themself) ? 'You said you won the bet, waiting on ' . $other .' to confirm.' : 'You said ' .$other.' won the bet. Waiting on '.$other.' to confirm');
					$data['user2_message'] = (($vote_themself) ? $this->session->userdata('username') . ' said they won the bet, waiting for you to confirm.' : $this->session->userdata('username') . ' said you won the bet, waiting for you to confirm.');
					
				}
						
			} else {
				//the voter is user2
				log_message('debug','the voter is the user2');
				$data['user2_vote'] = $vote;
					//$data['status'] = 'Pending';
				$other = $this->users->getName($bet->challenger);
				//check if it's over
				if($bet->challenger_vote == $vote){
				//it's over
					$data['winner'] = $vote;
					log_message('debug','it\'s over '. $vote .' won ');
					$won = true;
					$data['status'] = 'Pending';
					$winner = $this->users->getName($vote);
					
					if(!$vote_themself){
						log_message('debug','they lost and challenger won');
						//if they didn't then they lost, and the challenger won
						$this->users->addLoss($bet->user2);
						$this->users->addWin($bet->challenger);
						$data['user2_message'] = 'You lost the bet, pay '. $this->users->getName($other) .' $'.$bet->user2_wager;
						//subtract from user2's winnings
							log_message('debug','changing winnings for user ' . $bet->user2. ' this many $'.-1*(int)($bet->user1_wager));
						$this->users->changeWinnings($bet->user2,-1*(int)($bet->user1_wager));	
							
						
						$data['challenger_message'] = 'You won the bet, collect $' . $bet->user2_wager . ' from ' . $this->session->userdata('username');
						//Add to the challenger's winnings
							log_message('debug','changing winnings for user ' . $bet->challenger. ' this many $'.(int)($bet->user1_wager));
						$this->users->changeWinnings($bet->challenger,(int)$bet->user1_wager);
						
						
					} else {
						log_message('debug','they (user2) won');
						//they did win, then the voter (user2) won and the other user (challenger) lost
						$this->users->addLoss($bet->challenger);
						$this->users->addWin($bet->user2);
							$data['user2_message'] = 'You won the bet, collect $' . $bet->user1_wager . " from " . $other;
							//Add to the user2's winnings
							$this->users->changeWinnings($this->session->userdata('userid'),(int)($bet->user1_wager));	
						
							$data['challenger_message'] = 'You lost the bet, pay ' . $this->session->userdata('username') . " $" .  $bet->user1_wager; 	
							//subtract from challenger's winnings
							$this->users->changeWinnings($bet->challenger,(-1*(int)$bet->user1_wager));	
					}
				} else{
					// it's not over
					$data['user2_message'] = (($vote_themself) ? 'You said you won the bet, waiting on ' . $other .' to confirm.' : 'You said ' .$other.' won the bet. Waiting on '.$other.' to confirm');
					$data['challenger_message'] = (($vote_themself) ? $this->session->userdata('username') . ' said they won the bet, waiting for you to confirm.' : $this->session->userdata('username') . ' said you won the bet, waiting for you to confirm.');
					
				}
			}
			
				$data['last_action'] = ''.$this->session->userdata('username') . ' voted for ' . ($vote_themself ? 'themself ' : $other) . (isset($winner) ? ' and '.$winner .' won the bet.' : '.');
		
				$this->db->where('betid',$betid);
				$query = $this->db->update('bets', $data); 
		
			// Let's check if there are any results
			if($query == 1)
			{
				if ($won){
					return $winner;
				}
				
				return 'Success';
			}
			// If the previous process did not validate
			// then return false.
			return 'Unsuccessful';
		
		}
		catch(Exception $e){
		
			return 'Unsuccessful' . $e->getMessage();
		}
		
		
		}
	
	//The bet was paid
	public function paid($betid)
	{
		$user = $this->session->userdata('userid');
		$bet = $this->get($betid);
		
		$oId = (($bet->challenger == $user) ? $bet->user2 : $bet->challenger);
		
		$userIs = (($bet->challenger == $user) ? 'challenger' : 'user2');
		
		$oName = $this->users->getName($oId);
		
	
		$this->db->where('betid',$betid);
		
		$challenger_message = ($userIs == 'challenger' ? 'Bet is closed, ' . $oName .' paid you.' : 'Bet is closed, '.$this->session->userdata('username') . ' got your payment.');
		
				$user2_message = ($userIs != 'challenger' ? 'Bet is closed, ' . $oName .' paid you.' : 'Bet is closed, '.$this->session->userdata('username') . ' got your payment.');
		
		$data = array(
              
               'status' => 'Paid',
			   'updated' => date('Y:m:d H:i:s', time()),
			   'last_action' => 'Bet paid',
			   	'challenger_message' => $challenger_message,
			   	'user2_message' => $user2_message
			   
            );
		$query = $this->db->update('bets', $data); 
		// Let's check if there are any results
		if($query == 1)
		{
		
			return 'Success';
		}
		// If the previous process did not validate
		// then return false.
		return 'Unsuccessful';
	}
	
		
		//get a bet by betid
		public function get($betid)
		{
			$this->db->where('betid',$betid);
			$query = $this->db->get('bets');
			if($query->num_rows == 1)
			{
			
				// If there is a user, then create session data
				$row = $query->row();
				return $row;
			}
			// If the previous process did not validate
			// then return false.
			return false;
		
	
	
	}

	//get recent activity related to the current user
	//@num_of_bets - the number of bets to retrieve
	//Returns an array of bets
	//or returns None
	public function get_recent_activity($num_of_bets){
		$where = "(user2 =  '".  $this->session->userdata('userid') . "' OR challenger = '" .  $this->session->userdata('userid') . "')";
		$this->db->where($where);
		$this->db->order_by("last_action","desc");
		$this->db->limit($num_of_bets);
				
		$query = $this->db->get('bets');
		log_message('debug', 'the number of rows are ' . 'Getting recent Activity');
		log_message('debug', $this->db->last_query());
		log_message('debug', 'the number of rows are ' . $query->num_rows());
		
		$this->load->model('users');
		
if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{	
				
				switch($row->status){
					case "Open":
						$result = $this->process_open($row);
						break;
					case "In Progress":
						$result = $this->process_in_progress($row);
						break;
					case "Pending":
						$result = $this->process_pending($row);
						break;
					case "Paid":
						$result = $this->process_closed($row);
						break;
					case "Declined":
						$result = $this->process_declined($row);
						break;
				}
			
				
				$results[] = $result;
			}	
		}
		else {
			return 'None';
		}
			
			return $results;
	

			}

	
			private function process_open($row){
				if($row->status == 'Open'){
						
						$actions = array();
						
						//check if user is the challenger
						if ($row->challenger == $this->session->userdata('userid'))
						{
							$message = $row->challenger_message;
							//the opponent is user 2
							$oId = $row->user2;
							if ($row->user2 == -1){
									$oName = "Open Challenge";	
								}else {
									$oName = $this->users->getName($oId);
									
								}
							
							//user's wager is user1_wager
							$mybet = $row->user1_wager;
							$obet = $row->user2_wager;
							
							//if the user is the challenger they can only cancel the bet
							$actions[0] = array('name'=>'Cancel','class'=>'cancel', 'id'=>$row->betid);
							
							
						} else {
							//the logged in user is user2
							$message = $row->user2_message;
							//the opponent is the challenger
							$oId = $row->challenger;
							$oName = $this->users->getName($oId);
							
							//user's wager is user2_wager
							$mybet = $row->user2_wager;
							$obet = $row->user1_wager;
							
							//if the user is the challenger they can accept the bet
							$actions[0] = array('name'=>'Accept','class'=>'accept', 'id'=>$row->betid);
							
						}
					
						
					$results = array('betid'=>$row->betid, 'opponent'=> $oId, 'opponent_name'=> $oName,'title'=>$row->title,'conditions'=>$row->conditions,'user1_wager'=>$row->user1_wager,'user2_wager'=>$row->user2_wager,'date_created'=>$row->created_date, 'me'=>$this->session->userdata('userid'),'mybet'=>$mybet,'obet'=>$obet,'actions'=>$actions, 'status'=>$row->status, 'last_action'=>$row->last_action,'updated'=>$row->updated,'event_date'=>$row->event_date,'message'=>$message);
						
			
						
						return $results;
						
					
					
				}else {
					return 0;
					
				}
				
				
				
				
			}
			
					private function process_in_progress($row){
				if($row->status == "In Progress"){
					$myvote=null;
					$ovote=null;
					$actions = array();
					$actions[0] = array('id'=>$row->betid.'_'.$this->session->userdata('userid'),'class'=>'won vote','name'=>'I Won');
					
						
					//check if the user is the challenger
					if ($row->challenger == $this->session->userdata('userid'))
					{
						$message = $row->challenger_message;
						//means the opponent is user2
						$oId = $row->user2;
						$oName = $this->users->getName($oId);
						$actions[1] = array('id'=>$row->betid.'_'.$oId,'class'=>'lost vote','name'=>$oName .' won');
						//user's wager is user1_wager
						$mybet = $row->user1_wager;
						$obet = $row->user2_wager;
						
						
						if(!is_null($row->challenger_vote)){
							$myvote = (($row->challenger == $row->challenger_vote) ? 'me' : 'opp');	
							if($myvote == 'me'){
								$actions[0]['active'] = 'true'; 	
							}	else{
								$actions[1]['active'] = 'true'; 
								
							}				
						}
						if(!is_null($row->user2_vote)){
							$ovote = (($row->challenger == $row->user2_vote) ? 'me' : 'opp');
								
						}
						
					} else {
						//the logged in user is user2
						$message = $row->user2_message;
						$oId = $row->challenger;
						$oName = $this->users->getName($oId);
							$actions[1] = array('id'=>$row->betid.'_'.$oId,'class'=>'lost vote','name'=>$oName .' won');
						//user's wager is user2_wager
						$mybet = $row->user2_wager;
						$obet = $row->user1_wager;
						
						//check the user's vote
						if(!is_null($row->user2_vote)){
							log_message('debug','user 2: '. $row->user2. ' User 2 Vote: '.$row->user2_vote);
							$myvote = (($row->user2 == $row->user2_vote) ? 'me' : 'opp');
							if($myvote == 'me'){
								$actions[0]['active'] = 'true'; 
							}	else{
								$actions[1]['active'] = 'true'; 
							}	
						}
						if(!is_null($row->challenger_vote)){
							$ovote = (($row->user2 == $row->challenger_vote) ? 'me' : 'opp');
								
						}
					}
				
					
				$results = array('betid'=>$row->betid, 'opponent'=> $oId, 'opponent_name'=> $oName,'title'=>$row->title,'conditions'=>$row->conditions,'user1_wager'=>$row->user1_wager,'user2_wager'=>$row->user2_wager,'date_created'=>$row->created_date, 'me'=>$this->session->userdata('userid'),'mybet'=>$mybet,'obet'=>$obet, 'actions'=>$actions, 'myvote'=>$myvote,'ovote'=>$ovote, 'status'=>$row->status,'last_action'=>$row->last_action,'updated'=>$row->updated,'event_date'=>$row->event_date,'message'=>$message);
				//	$results =  $this->put_row_in_array($row);
					return $results;
				
				} else {
					
					return 0;
					
				}
				
				
			}
			
					private function process_pending($row){
						if($row->status == "Pending"){
							$myvote=null;
							$ovote=null;
							$actions = array();
						
							
							//check if the user is the winner or the loser
								if($row->winner == $this->session->userdata('userid')){
									$actions[0] = array('id'=>$row->betid,'class'=>'paid','name'=>'They Paid');
									
								}
								
								
							//check if the user is the challenger
							if ($row->challenger == $this->session->userdata('userid'))
							{
								$message = $row->challenger_message;
								//means the opponent is user2
								$oId = $row->user2;
								$oName = $this->users->getName($oId);
								
								//user's wager is user1_wager
								$mybet = $row->user1_wager;
								$obet = $row->user2_wager;
								
								$myvote = (($row->challenger == $row->challenger_vote) ? 'me' : 'opp');	
								$ovote = (($row->challenger == $row->user2_vote) ? 'me' : 'opp');
								
								
								
							} else {
								//the logged in user is user2
								$message = $row->user2_message;
								$oId = $row->challenger;
								$oName = $this->users->getName($oId);
								
								//user's wager is user2_wager
								$mybet = $row->user2_wager;
								$obet = $row->user1_wager;
								
								
								//check the user's vote
									$myvote = (($row->user2 == $row->user2_vote) ? 'me' : 'opp');
									$ovote = (($row->user2 == $row->challenger_vote) ? 'me' : 'opp');
							
							}
						
							
							$results = array('betid'=>$row->betid, 'opponent'=> $oId, 'opponent_name'=> $oName,'title'=>$row->title,'conditions'=>$row->conditions,'user1_wager'=>$row->user1_wager,'user2_wager'=>$row->user2_wager,'date_created'=>$row->created_date, 'me'=>$this->session->userdata('userid'),'mybet'=>$mybet,'obet'=>$obet, 'actions'=>$actions, 'myvote'=>$myvote,'ovote'=>$ovote, 'status'=>$row->status,'last_action'=>$row->last_action,'updated'=>$row->updated,'event_date'=>$row->event_date,'message'=>$message,'actions'=>$actions);
							
							return $results;
						
						} else {
						
						return 0;
						
					}
				
				
			}
			
			private function process_closed($row){
						if($row->status == "Paid" or $row->status == "Closed"){
							$myvote=null;
							$ovote=null;
							$actions = array();
						
							
								
								
							//check if the user is the challenger
							if ($row->challenger == $this->session->userdata('userid'))
							{
								$message = $row->challenger_message;
								//means the opponent is user2
								$oId = $row->user2;
								$oName = $this->users->getName($oId);
								
								//user's wager is user1_wager
								$mybet = $row->user1_wager;
								$obet = $row->user2_wager;
								
								$myvote = (($row->challenger == $row->challenger_vote) ? 'me' : 'opp');	
								$ovote = (($row->challenger == $row->user2_vote) ? 'me' : 'opp');
								
								
								
							} else {
								//the logged in user is user2
								$message = $row->user2_message;
								$oId = $row->challenger;
								$oName = $this->users->getName($oId);
								
								//user's wager is user2_wager
								$mybet = $row->user2_wager;
								$obet = $row->user1_wager;
								
								
								//check the user's vote
									$myvote = (($row->user2 == $row->user2_vote) ? 'me' : 'opp');
									$ovote = (($row->user2 == $row->challenger_vote) ? 'me' : 'opp');
							
							}
						
							
							$results = array('betid'=>$row->betid, 'opponent'=> $oId, 'opponent_name'=> $oName,'title'=>$row->title,'conditions'=>$row->conditions,'user1_wager'=>$row->user1_wager,'user2_wager'=>$row->user2_wager,'date_created'=>$row->created_date, 'me'=>$this->session->userdata('userid'),'mybet'=>$mybet,'obet'=>$obet, 'actions'=>$actions, 'myvote'=>$myvote,'ovote'=>$ovote, 'status'=>$row->status,'last_action'=>$row->last_action,'updated'=>$row->updated,'event_date'=>$row->event_date,'message'=>$message);
							
							return $results;
						
						} else {
						
						return 0;
						
					}
				
				
			}
			
				private function process_declined($row){
						if($row->status == "Declined"){
							$myvote=null;
							$ovote=null;
							$actions = array();
						
							
								
								
							//check if the user is the challenger
							if ($row->challenger == $this->session->userdata('userid'))
							{
								$message = $row->challenger_message;
								//means the opponent is user2
								$oId = $row->user2;
								$oName = $this->users->getName($oId);
								
								//user's wager is user1_wager
								$mybet = $row->user1_wager;
								$obet = $row->user2_wager;
								
								
								
								
								
							} else {
								//the logged in user is user2
								$message = $row->user2_message;
								$oId = $row->challenger;
								$oName = $this->users->getName($oId);
								
								//user's wager is user2_wager
								$mybet = $row->user2_wager;
								$obet = $row->user1_wager;
								
							
							
							}
						
							
							$results = array('betid'=>$row->betid, 'opponent'=> $oId, 'opponent_name'=> $oName,'title'=>$row->title,'conditions'=>$row->conditions,'user1_wager'=>$row->user1_wager,'user2_wager'=>$row->user2_wager,'date_created'=>$row->created_date, 'me'=>$this->session->userdata('userid'),'mybet'=>$mybet,'obet'=>$obet, 'actions'=>$actions, 'myvote'=>$myvote,'ovote'=>$ovote, 'status'=>$row->status,'last_action'=>$row->last_action,'updated'=>$row->updated,'event_date'=>$row->event_date,'message'=>$message);
							
							return $results;
						
						} else {
						
						return 0;
						
					}
				
				
			}
			
			
			private function put_row_in_array($row){
				
				$results = array('betid'=>$row->betid, 'opponent'=> $oId, 'opponent_name'=> $oName,'title'=>$row->title,'conditions'=>$row->conditions,'user1_wager'=>$row->user1_wager,'user2_wager'=>$row->user2_wager,'date_created'=>$row->created_date, 'me'=>$this->session->userdata('userid'),'mybet'=>$mybet,'obet'=>$obet, 'actions'=>$actions, 'myvote'=>$myvote,'ovote'=>$ovote, 'status'=>$row->status,'last_action'=>$row->last_action,'updated'=>$row->updated,'event_date'=>$row->event_date,'message'=>$message,'actions'=>$actions);
				
				return $results;
			}
		
			private function get_actions($status,$row){
				$actions = array();
				switch($status){
					case "Open":
						if($row->user2 == $this->session->userdata('userid') ){
							$actions[0] = array('name'=>'Accept','id'=>$row->betid);
							return $actions;
						}
						break;
					case "In Progress":
						$actions[0] = array('id'=>$row->betid.'me','class'=>'vote');
						$actions[1] = array('id'=>$row->betid.'opp','class'=>'vote');
						if ($row->challenger == $this->session->userdata('userid'))
						{
							if(!is_null($row->challenger_vote)){
							$myvote = (($row->challenger == $row->challenger_vote) ? 'me' : 'opp');	
							if($myvote == 'me'){
								$actions[0]['active'] = 'true'; 	
								}	else{
								$actions[0]['active'] = 'false'; 
								
								}				
							}
						
						}else{
							if(!is_null($row->user2_vote)){
							$myvote = (($row->user2 == $row->user2_vote) ? 'me' : 'opp');
							if($myvote == 'me'){
									$actions[0]['active'] = 'true'; 
								}	else{
									$actions[0]['active'] = 'false'; 
								}	
							}
							
							
						}
						
				}
				
				
				
				
			}
			
			

}
?>