
<?php
if($bets != 'None'){
	foreach($bets as $bet){

	echo "<div class='span8 challenge' id=" . $bet['betid'] . "><div class='span6'>";

	echo "<ul class='unstyled'><li><span class='challenger' id=" . $bet['opponent'] . "> You have a bet against <strong>" . $bet['opponent_name'] . "</strong></span></li>";
	echo "<li><span class='title'>" . $bet['title'] . ":</span>";
	echo "<span class='htw'>" . $bet['detail'] . "</span></li>";
	echo "<li><span class='uwager'>You wager <strong>$" . $bet['user2_wager'] . "</strong></span>";
	echo "<span class='twager'>They wager <strong>$" . $bet['user1_wager'] . "</strong></span></li>";
	echo "<li>Who Won?</li>";
	echo "</div><div class='span2 btn-group' data-toggle='buttons-radio'>";
	echo "<button type='button' class='btn btn-success";
	if(!is_null($bet['mybet'])){
	echo ($bet['mybet'] == 'me' ? ' active' : false);
	}

	echo"' name='".$bet['me']."' data-toggle='button' id='" . $bet['betid'] . "_me'>Me</button>";
	echo "<button type='button' class='btn btn-danger";
	if(!is_null($bet['mybet'])){
	echo ($bet['mybet'] == 'opp' ? ' active' : false);
	}
	echo "' name='".$bet['opponent']."' data-toggle='button' id='" . $bet['betid'] . "_opp'>". $bet['opponent_name'] . "</button>";
	echo "</div><div class='span4'>";
	if(is_null($bet['obet'])){
	echo  $bet['opponent_name'] . " hasn't picked a winner yet";
	} else {
	echo $bet['opponent_name'] . " picked " .($bet['obet']=='me' ? 'you as the winner' : 'himself as the winner.');
	}


	echo "</div></div>";

	}

 } else {
	echo "<h5>You have no open bets</h5>";
 }
?>
<script>
	


	$('.btn').click(function (e){
		
			var id = this.id;
			var other;
			var betid = this.id.split("_");
			if(betid[1] == 'me'){
				other = betid[0] + '_opp';
			}else{
				other = betid[0] + '_me';
			}
			
			var url = 'bets/vote/' + betid[0] + '/' + this.name;
			$.post(url, function(data) {
			var text = data['result'];
			try{
				if ( text.substring(0,10) != 'Unsuccesful'){
					generateNoty('success','Vote Submitted');
					
					$('#'+id).addClass('active');
					$('#'+other).removeClass('active');
					
				}
				else
				{
					generateNoty('error',text);
				}
			 } catch (err) {
				generateNoty('error','Internal Application Error');
			 
			 }
			 
		});

		

})

</script>