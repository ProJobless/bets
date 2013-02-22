<?php if($bets == 'None') : ?>
   <h4><?php echo $no; ?></h4>
   
   <?php else : ?>
  
<div class="bets"><div class="row-fluid">
    <div class="span8">
        <div class="rows">
           <?php foreach($bets as $bet): ?>
        <div class="bet_row row-fluid first" bet_id="<?php echo $bet['betid']; ?>">
        <!--<div class="avatar">
    
    <img src="#" class="img-rounded">
</div>-->
<div class="bet">
    <div class="heading">
        <div class="status span6">
            <?php echo $bet['message']; ?>
   
        </div>
        <div class="title span6">
            
              <span>
                <?php echo $bet['title']; ?>
              </span>
             
         </div>
         <div class="event-date span6">
              <span>  <?php echo ((isset($bet['event_date']))? date('m/d/Y', strtotime($bet['event_date'])) : ' '); ?></span>
              </div>
            

    </div>
    <div class="wager">
    	You wagered $<?php echo $bet['mybet']; ?> to win $<?php echo $bet['obet']; ?> 
    </div>
    <div class="available_actions">
      <?php
      
     	 if ($bet['actions'] > 0)
      	{
					foreach($bet['actions'] as $action){
						echo '<button id="'.$action['id'] . '" name="'.$action['name'].'" class="btn btn-mini '.strtolower($action['class']).' ' . (isset($action['active']) ? ($action['active'] ? 'active' : '' ) : '').'">'.$action['name'].'</button>';
				
				}
			}
			
			?>
      
    
    </div>
</div>
        </div>
<?php endforeach; ?>
</div>
        
    </div>
    </div>

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