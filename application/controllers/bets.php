<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class bets extends CI_Controller{
    
    function __construct(){
        parent::__construct();
    }
    
    public function index($msg=null){
        // Load our view to be displayed
        // to the user
		
        $data['msg']  = $msg;
        $data['content'] = 'landing';
        $this->load->view('template', $data);
    }
	
	public function create(){
        // Load the model
		
        $this->load->model('bet');
        // Validate the user can login
        $r = $this->bet->create();
        // Now we verify the result
        $result = array('result' => $r);
        $data['json'] = json_encode($result);;
		
        $this->load->view('json_message', $data);
        }        
    
    	public function get_recent_activity($type){
        // Load the model			
        $this->load->model('bet');

				$r = $this->bet->get_recent_activity(100);
		       
		       
				
				$data['bets'] = $r;
					$data['title'] = 'Recent Activity';
					$data['no'] = 'There has been no recent activity';
					switch ($type) {
						case 'list':
							$this->load->view('list', $data);
							break;
					case 'table':
						$this->load->view('table', $data);
						break;
					case 'json':
						$json = array();
						foreach($r as $bet){
							$json[] = array('status'=>$bet['status'],'title'=>$bet['title'],'opponent_name'=>$bet['opponent_name'],'message'=>$bet['message'],'actions'=>$bet['actions'],'event_date'=>$bet['event_date'],'mybet'=>$bet['mybet'],'obet'=>$bet['obet'],'updated'=>$bet['updated']);
							
						}
					
					/*'betid'=>$row->betid, 'opponent'=> $oId, 'opponent_name'=> $oName,'title'=>$row->title,'conditions'=>$row->conditions,'user1_wager'=>$row->user1_wager,'user2_wager'=>$row->user2_wager,'date_created'=>$row->created_date, 'me'=>$this->session->userdata('userid'),'mybet'=>$mybet,'obet'=>$obet, 'actions'=>$actions, 'myvote'=>$myvote,'ovote'=>$ovote, 'status'=>$row->status,'last_action'=>$row->last_action,'updated'=>$row->updated,'event_date'=>$row->event_date,'message'=>$message,'actions'=>$actions*/
					
					
						$data['json'] = json_encode(array('aaData' => $json));
							$this->load->view('json_message', $data);
						break;
					
			
					}
					
					
					
        } 
    
    
    
		public function challenged(){
        // Load the model			
        $this->load->model('bet');

		$r = $this->bet->challenged();
        // Now we verify the result
		
		$data['bets'] = $r;
		
		 $this->load->view('challenged', $data);
        } 
		
		//Get open challenges on behalf of the online user
		public function openchallenges(){
        // Load the model			
        $this->load->model('bet');
        // Validate the user can login
		$r = $this->bet->open_challenges();
        // Now we verify the result
		
		$data['bets'] = $r;
		
		 $this->load->view('open_challenges', $data);
        } 
		
		public function get($filter){
				$r = null;
				$returnType = $this->uri->segment(4, 0);
				log_message('debug',$returnType);
				switch($filter){
						case '0':
							$r = $this->bet->get_recent_activity(100);
							$data['title'] = 'Recent Activity';
							$data['no'] = 'There has been no recent activity';
							break;
						case '1':
							$r = $this->bet->getOpen();
							$data['title'] = 'Open Bets';
							$data['no'] = 'There are no open bets';
							break;
						case '2':
							$r = $this->bet->getInProgress();
							$data['title'] = 'In Progress Bets';
							$data['no'] = 'You have no bets in progress';
							break;		
						case '3':
							$r = $this->bet->getPending();
							$data['title'] = 'Pending Bets';
							$data['no'] = 'You have no pending bets';
							break;
						case '4':	
							$r = $this->bet->getClosed();
							$data['title'] = 'Closed Bets';
							$data['no'] = 'You have no closed bets';
							$data['bets'] = $r;
							break;
						case '5':	
							$r = $this->bet->getMyBets();
							$data['title'] = 'My Bets';
							$data['no'] = 'You have no bets';
							$data['bets'] = $r;
							break;
						case '6':
							$r = $this->bet->getBrowse();
							$data['title'] = 'Open Bets';
							$data['no'] = 'There are no open bets';
							break;
						
				}
				
					switch ($returnType) {
							case 'list':
								$this->load->view('list', $data);
								break;
							case 'table':
								$this->load->view('table', $data);
								break;
							case 'json':
									$json = array();
									if($r == 0){
										$data['json']=json_encode(array('message' => $data['no']));
										$this->load->view('json_message',$data);
									} else {
									foreach($r as $bet){
										$json[] = array('updated'=>$bet['updated'],'status'=>$bet['status'],'title'=>$bet['title'],'opponent_name'=>$bet['opponent_name'],'message'=>$bet['message'],'actions'=>$bet['actions']);
								
										}
						
									
									
							$data['json'] = json_encode(array('aaData' => $json));
									$this->load->view('json_message', $data);
									}
									break;
						
			
					}
				
			
		}
		
		//Get Open Bets on behalf of the logged in user
		public function open($type){
        // Load the model		
			
			        $this->load->model('bet');
			        // Validate the user can login
					$r = $this->bet->getOpen();
			        // Now we verify the result
					$data['title'] = 'Open Bets';
					$data['no'] = 'There are no open bets';
						switch ($type) {
						case 'list':
							$this->load->view('list', $data);
							break;
					case 'table':
						$this->load->view('table', $data);
						break;
					case 'json':
						$json = array();
						foreach($r as $bet){
							$json[] = array('status'=>$bet['status'],'title'=>$bet['title'],'opponent_name'=>$bet['opponent_name'],'message'=>$bet['message'],'actions'=>$bet['actions'],'event_date'=>$bet['event_date'],'mybet'=>$bet['mybet'],'obet'=>$bet['obet'],'updated'=>$bet['updated']);
							
						}
					
					/*'betid'=>$row->betid, 'opponent'=> $oId, 'opponent_name'=> $oName,'title'=>$row->title,'conditions'=>$row->conditions,'user1_wager'=>$row->user1_wager,'user2_wager'=>$row->user2_wager,'date_created'=>$row->created_date, 'me'=>$this->session->userdata('userid'),'mybet'=>$mybet,'obet'=>$obet, 'actions'=>$actions, 'myvote'=>$myvote,'ovote'=>$ovote, 'status'=>$row->status,'last_action'=>$row->last_action,'updated'=>$row->updated,'event_date'=>$row->event_date,'message'=>$message,'actions'=>$actions*/
					
					
						$data['json'] = json_encode(array('aaData' => $json));
							$this->load->view('json_message', $data);
						break;
					
			
					}
        } 
        
            //Get in progress Bets on behalf of the logged in user
		public function in_progress($type){
        // Load the model		
			
				        $this->load->model('bet');
				        // Validate the user can login
						$r = $this->bet->getInProgress();
				        // Now we verify the result
						$data['title'] = 'In Progress Bets';
						$data['no'] = 'You have no bets in progress';
						$data['bets'] = $r;
							switch ($type) {
						case 'list':
							$this->load->view('list', $data);
							break;
					case 'table':
						$this->load->view('table', $data);
						break;
					case 'json':
						$json = array();
						foreach($r as $bet){
							$json[] = array('status'=>$bet['status'],'title'=>$bet['title'],'opponent_name'=>$bet['opponent_name'],'message'=>$bet['message'],'actions'=>$bet['actions'],'event_date'=>$bet['event_date'],'mybet'=>$bet['mybet'],'obet'=>$bet['obet'],'updated'=>$bet['updated']);
							
						}
					
					/*'betid'=>$row->betid, 'opponent'=> $oId, 'opponent_name'=> $oName,'title'=>$row->title,'conditions'=>$row->conditions,'user1_wager'=>$row->user1_wager,'user2_wager'=>$row->user2_wager,'date_created'=>$row->created_date, 'me'=>$this->session->userdata('userid'),'mybet'=>$mybet,'obet'=>$obet, 'actions'=>$actions, 'myvote'=>$myvote,'ovote'=>$ovote, 'status'=>$row->status,'last_action'=>$row->last_action,'updated'=>$row->updated,'event_date'=>$row->event_date,'message'=>$message,'actions'=>$actions*/
					
					
						$data['json'] = json_encode(array('aaData' => $json));
							$this->load->view('json_message', $data);
						break;
					
			
					}
        } 
        
        //Get Pending Bets on behalf of the logged in user
		public function pending($type){
        // Load the model		
			
				        $this->load->model('bet');
				        // Validate the user can login
						$r = $this->bet->getPending();
				        // Now we verify the result
						$data['title'] = 'Pending Bets';
						$data['no'] = 'You have no pending bets';
						$data['bets'] = $r;
						
							switch ($type) {
						case 'list':
							$this->load->view('list', $data);
							break;
					case 'table':
						$this->load->view('table', $data);
						break;
					case 'json':
						$json = array();
						foreach($r as $bet){
							$json[] = array('status'=>$bet['status'],'title'=>$bet['title'],'opponent_name'=>$bet['opponent_name'],'message'=>$bet['message'],'actions'=>$bet['actions'],'event_date'=>$bet['event_date'],'mybet'=>$bet['mybet'],'obet'=>$bet['obet'],'updated'=>$bet['updated']);
							
						}
					
					/*'betid'=>$row->betid, 'opponent'=> $oId, 'opponent_name'=> $oName,'title'=>$row->title,'conditions'=>$row->conditions,'user1_wager'=>$row->user1_wager,'user2_wager'=>$row->user2_wager,'date_created'=>$row->created_date, 'me'=>$this->session->userdata('userid'),'mybet'=>$mybet,'obet'=>$obet, 'actions'=>$actions, 'myvote'=>$myvote,'ovote'=>$ovote, 'status'=>$row->status,'last_action'=>$row->last_action,'updated'=>$row->updated,'event_date'=>$row->event_date,'message'=>$message,'actions'=>$actions*/
					
					
						$data['json'] = json_encode(array('aaData' => $json));
							$this->load->view('json_message', $data);
						break;
					
			
					}
        } 
		
		        //Get Pending Bets on behalf of the logged in user
		public function closed($type){
        // Load the model		
			
				        $this->load->model('bet');
				        // Validate the user can login
						$r = $this->bet->getClosed();
				        // Now we verify the result
						$data['title'] = 'Closed Bets';
						$data['no'] = 'You have no closed bets';
						$data['bets'] = $r;
						
							switch ($type) {
						case 'list':
							$this->load->view('list', $data);
							break;
					case 'table':
						$this->load->view('table', $data);
						break;
					case 'json':
						$json = array();
						foreach($r as $bet){
							$json[] = array('status'=>$bet['status'],'title'=>$bet['title'],'opponent_name'=>$bet['opponent_name'],'message'=>$bet['message'],'actions'=>$bet['actions'],'event_date'=>$bet['event_date'],'mybet'=>$bet['mybet'],'obet'=>$bet['obet'],'updated'=>$bet['updated']);
							
						}
					
					/*'betid'=>$row->betid, 'opponent'=> $oId, 'opponent_name'=> $oName,'title'=>$row->title,'conditions'=>$row->conditions,'user1_wager'=>$row->user1_wager,'user2_wager'=>$row->user2_wager,'date_created'=>$row->created_date, 'me'=>$this->session->userdata('userid'),'mybet'=>$mybet,'obet'=>$obet, 'actions'=>$actions, 'myvote'=>$myvote,'ovote'=>$ovote, 'status'=>$row->status,'last_action'=>$row->last_action,'updated'=>$row->updated,'event_date'=>$row->event_date,'message'=>$message,'actions'=>$actions*/
					
					
						$data['json'] = json_encode(array('aaData' => $json));
							$this->load->view('json_message', $data);
						break;
					
			
					}
        } 
        

		//Accept a bet on behalf of the logged in user
		//@betid - the bet id to accept
		public function accept($betid){
			// Load the model			
			$this->load->model('bet');
			// Validate the user can login
			$r = $this->bet->accept($betid);
			// Now we verify the result
			$result = array('result' => $r);
			$data['json'] = json_encode($result);;
		
			$this->load->view('json_message', $data);
        }		
		
			//Decline a bet on behalf of the logged in user
		//@betid - the bet id to decline
		public function decline($betid){
			// Load the model			
			$this->load->model('bet');
			// Validate the user can login
			$r = $this->bet->decline($betid);
			// Now we verify the result
			$result = array('result' => $r);
			$data['json'] = json_encode($result);;
		
			$this->load->view('json_message', $data);
        }	
		
		public function cancel($betid){
			// Load the model			
			
			// Validate the user can login
			$r = $this->bet->cancel($betid);
			// Now we verify the result
			$result = array('result' => $r);
			$data['json'] = json_encode($result);;
		
			$this->load->view('json_message', $data);
        }	
		
		public function vote($betid){
        // Load the model		
			//$betid = $this->uri->segment(2, 0);
			$userid = $this->uri->segment(4, 0);
			
			log_message('debug', 'Vote: bet id: ' . $betid . ' userid: ' .$userid);
			if ($betid == 0 || $userid ==0){
				
				$data['type'] = 'error';
				$data['message'] = 'Something went wrong, please refresh the page';
				$this->load->view('alert', $data);
			}
       
        
		$r = $this->bet->vote($betid, $userid);
        // Now we verify the result
		$r = array('result' => $r);
		$data['json'] = json_encode($r);
		$this->load->view('json_message', $data);
        } 
		
		public function paid($betid){
   
			
			log_message('debug', 'paid bet id: ' . $betid);
			if ($betid == 0){
				
				$data['type'] = 'error';
				$data['message'] = 'Something went wrong, please refresh the page';
				$this->load->view('alert', $data);
			}
       
        
		$r = $this->bet->paid($betid);
        // Now we verify the result
		$r = array('result' => $r);
		$data['json'] = json_encode($r);
		$this->load->view('json_message', $data);
        } 
		
		
}
?>