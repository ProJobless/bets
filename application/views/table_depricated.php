
<?php if($bets == 'None') : ?>
   <h4><?php echo $no; ?></h4>
   
   <?php else : ?>
   
    <table>
    <table id="bet-table" class='table table-bordered table-hover'>
		<thead>
			
			<tr>
				
				<th>Status</th>
				<th>Name</th>
				<th>Against</th>
				<th>Conditions</th>
				<th>Event Date</th>
				<th>Your Wager</th>
				<th>Their Wager</th>
				<th>Action</th>
				<th>Message</th>
				<th>Last Updated</th>
			</tr>
			
		</thead>
		
		<?php
		$echo = '<tbody>';
		
		foreach($bets as $bet){
				
	
		$echo = $echo . "<tr><td>" . $bet['status'] . "</td><td>" . $bet['title'] . "</td><td>" . $bet['opponent_name']  . "</td><td>" . $bet['conditions'] . "</td><td>". ((isset($bet['event_date']))?date('m/d/Y', strtotime($bet['event_date'])) : ' ') . "  </td><td>" . $bet['mybet'] .  "</td><td>" . $bet['obet'] . "</td><td>";
		
		
		if ($bet['actions'] > 0)
		{
			foreach($bet['actions'] as $action){
				$echo = $echo . '<button id="'.$action['id'] . '" name="'.$action['name'].'" class="btn btn-mini '.strtolower($action['class']).' ' . (isset($action['active']) ? ($action['active'] ? 'active' : '' ) : '').'">'.$action['name'].'</button>';
				
				}
			}

		
		$echo = $echo . '</td><td>'. $bet['message'] .'</td><td>'.$bet['updated'] .    '</td></tr>';
	
		
		
		}
		echo $echo;
		
		
	//	array('betid'=>$row->betid, 'opponent'=> $oId, 'opponent_name'=> $oName,'title'=>$row->title,'conditions'=>$row->conditions,'date_created'=>$row->created_date, 'me'=>$this->session->userdata('userid'),'mybet'=>$mybet,'obet'=>$obet, 'status' => $row->status);
		
		
		?>
		<script>
		$('tr').hover(function(){
	$(this).find("button").css({'opacity':'1'});

    }, function() {
        jQuery(this).find("button").css({'opacity':'0.3'});
});

	$('button.cancel').addClass('btn-danger');
	$('button.accept').addClass('btn-success');
	$('button.won').addClass('btn-success');
	$('button.lost').addClass('btn-danger');
	$('button.paid').addClass('btn-success');

$('#bet-table').dataTable( {
        
        
    } );
    
   $(function(){
    $('button.cancel').click(function(e){
	    	var id = this.id;
	    	var other;
	    	console.log('cancel click for id ' + id);
	    	
	    				
			var url = 'bets/cancel/' + id;
			$.post(url, function(data) {
			var text = data['result'];
			try{
				if ( text.substring(0,10) != 'Unsuccesful'){
					generateNoty('error','Bet Canceled');
					console.log(this);
					 	$.get("bets/get_recent_activity", function(data){
						 	$('#recent').html(data);
						 	});										
				}
				else
				{
					generateNoty('error',text);
				}
			 } catch (err) {
				generateNoty('error','Internal Application Error');
			 
			 }
	    
    });
   });
   
   $('button.accept').click(function(e){
	    	var id = this.id;
	    	var other;
	    	console.log('accept click for id ' + id);
	    	
	   
		    				
				var url = 'bets/accept/' + id;
				$.post(url, function(data) {
				var text = data['result'];
				try{
					if ( text.substring(0,10) != 'Unsuccesful'){
						generateNoty('success','Bet Accept');
						console.log(this);
					 //	$.get("bets/get_recent_activity", function(data){
						 //	$('#recent').html(data);
						 //	});
					}
					else
					{
						generateNoty('error',text);
					}
				 } catch (err) {
					generateNoty('error','Internal Application Error');
				 
				 }
		    
	    });
   });
   
   $('button.vote').click(function(e){
	    	var id = this.id;
	    	var other;
	   
	    	
	    	 	var betid = this.id.split("_");
	    	 	 	console.log('vote click for id ' + betid[1]);
			if($(this).hasClass('won')){
				console.log('voted for me');
				other = betid[0] + '_opp';
			}else{
				console.log('voted for opp');
				other = betid[0] + '_me';
			}
	    				
		
			var url = 'bets/vote/' + betid[0] + '/' + betid[1];
			$.post(url, function(data) {
			var text = data['result'];
			try{
				if ( text.substring(0,10) != 'Unsuccesful'){
					generateNoty('success','Vote Submitted');
					
					 //	$.get("bets/get_recent_activity", function(data){
						 //	$('#recent').html(data);
				//});
					
				}
				else
				{
					generateNoty('error',text);
				}
			 } catch (err) {
				generateNoty('error','Internal Application Error');
			 
			 }
   });
   
   });

$('button.paid').click(function(e){
	    	var id = this.id;
	    	var other;
	   
	    	
	    	 
	    console.log('paid click for id ' + id);
		
	    				
		
			var url = 'bets/paid/' + id;
			$.post(url, function(data) {
			var text = data['result'];
			try{
				if ( text.substring(0,10) != 'Unsuccesful'){
					generateNoty('success','You got paid!');
					
					$('#'+id).remove('.btn');
					 	$.get("bets/get_recent_activity", function(data){
			$('#recent').html(data);
			});
					
					
				}
				else
				{
					generateNoty('error',text);
				}
			 } catch (err) {
				generateNoty('error','Internal Application Error');
			 
			 }
   });
   
   });

   
   
   });
    
</script>
<?php endif; ?>










