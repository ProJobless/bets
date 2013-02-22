<div id="newBet" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
              <h3 id="myModalLabel">New Bet</h3>
            </div>
            <div class="modal-body">
             <form class="form-horizontal" action='<?php echo base_url();?>bets/new' method='post' name='betFrom' id='betForm'>
             <input type='hidden' name='open-bet' value='true' id='open-bet'/>
	        <div class="control-group">
						<label class="control-label">Against Who?</label>
						
							<div class="controls">
								
								<div class="btn-group" data-toggle="buttons-radio">
  <button type="button" id="open-challenge" class="btn active">Open Challenge</button>
  <button type="button" id="challenge-user" class="btn">Challenge a User</button>
</div>
							</div>
					</div>
					<div class="control-group" id="user-select" style="display:none;">
						<label class="control-label">Select an opponent</label>
							<?php 
								$array = array();
								foreach ($users as $user){
									$array[]=$user;
								}
							?>
							<div class="controls" >
								
								<input type="text" name="against" id="user-Selector"/>
								<script>
									$('input#user-Selector').autocomplete({source:<?php echo json_encode($users);?>});
									
								</script>
							</div>
					</div>
					 <div class="control-group">
						<label class="control-label" for="name">Name the bet</label>
						<div class="controls">
							<input type="text" name="name">
						</div>
					</div>
					<div class="control-group">
							<label class="control-label" for="conditions">Conditions of the bet - What terms are you offering?</label>
						<div class="controls">
							<textarea name="conditions" rows="3"></textarea>
						</div>
					</div>
						<div class="control-group">
							<label class="control-label" for="event-date">Event Date</label>
						<div class="controls">
							<input type="text" id="datepicker" name="event-date"/>
						</div>
					</div>
						<div class="control-group">
							<label class="control-label" for="wagerYou">You are wagering</label>
							<div class="controls">
							  <input name="wagerYou" type="text" id="wagerYou" placeholder="$">
							</div>
					  </div>
						<div class="control-group">
							<label class="control-label" for="wagerThem">They are wagering</label>
							<div class="controls">
							   <input type="text" id="wagerThem" name="wagerThem" placeholder="$">
							</div>
						</div>
				
					
          
            <div class="modal-footer">
              
						  <input type='Submit' value='Create' class='btn btn-primary'/>	
						  </form>
            </div>
          </div>
          <script>
          
          	$('#challenge-user').click(function(){
	          	if($('#challenge-user').hasClass('active')){
		          		$('#challenge-user').removeClass('active');
		          		$("#open-challenge").addClass('active');
		          		$('#open-bet').val('true');
		          		$('#user-select').hide();
		          	
	          	} else{
		          		$('#challenge-user').addClass('active');
		          		$("#open-challenge").removeClass('active');
		          		$('#open-bet').val('false');
		          		$('#user-select').show();
		          	
	          	}
	          	
	          	
	          	
          	});
          	
          	$('#open-challenge').click(function(){
	          	if($("#open-challenge").hasClass('active')){
		          		$("#open-challenge").removeClass('active');
		          		$("#challenge-user").addClass('active');
		          		$('#open-bet').val('false');
		          		$('#user-select').show();
		          	
	          	} else{
		          		$("#open-challenge").addClass('active');
		          		$("#challenge-user").removeClass('active');
		          		$('#open-bet').val('true');
		          		$('#user-select').hide();
		          	
	          	}
	          	
	          	
	          	
          	})
          	
          	$(function() {
        $( "#datepicker" ).datepicker();
    });
          </script>
</div>