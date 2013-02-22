
<?php
if ($bets != 'None' ){
echo "<h4>Waiting for you</h4>";
foreach($bets as $bet){

echo "<div class='span5 challenge' id=" . $bet['betid'] . "><div class='span5'>";

echo "<ul class='unstyled'><li><span class='challenger' id=" . $bet['challenger_id'] . "> You have been challenged by <strong>" . $bet['challenger'] . "</strong></span></li>";
echo "<li><span class='title'>" . $bet['title'] . ":</span>";
echo "<span class='htw'>" . $bet['detail'] . "</span></li>";
echo "<li><span class='uwager'>You wager <strong>$" . $bet['user2_wager'] . "</strong></span>";
echo "<span class='twager'>They wager <strong>$" . $bet['user1_wager'] . "</strong></span></li>";
echo "</div><div class='span2 btn-group btn-group".$bet['betid']."'>";
echo "<button class='btn btn-success accept' name ='accept' data-toggle='button' id=" . $bet['betid'] . ">Accept</button>";
echo "<button class='btn btn-danger decline' name='decline' data-toggle='button' id=" . $bet['betid'] . ">Decline</button>";
echo "</div>";



echo "</div>";

}
} else{

echo "<h5>You have no open challenges</h5>";
}
 
?>
<script>
	$('.accept').button();
	$('.decline').button();

	$('.btn').click(function (e){
		console.log(this.name);
		var btngroup = ".btn-group" + this.id;
		if (this.name == 'accept'){
			var url = 'bets/accept/' + this.id;
			$.post(url, function(data) {
			var text = data['result'];
			if (text == 'Success'){
				$(btngroup).html("<p class='text-success'>Accepted!</p>");
			}
			else
			{
				generateNoty('error',text);
			}
		});

		} else if (this.name == 'decline'){
			var url = 'bets/decline/' + this.id;
			$.post(url, function(data) {
			var text = data['result'];
			if (text == 'Success'){
				$(btngroup).html("<p class='text-error'>Declined!</p>");
			}
			else
			{
				generateNoty('error',text);
			}
			
		});
		}

})

</script>