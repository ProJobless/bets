
<?php
if ($bets != 'None' ){
echo "<h4>Waiting on them</h4>";
foreach($bets as $bet){

echo "<div class='span5' id=" . $bet['betid'] . "><div>";

echo "<ul class='unstyled'><li><span class='user2' id=" . $bet['user2_id'] . "> You challenged <strong>" . $bet['user2'] . "</strong></span></li>";
echo "<li><span class='title'>" . $bet['title'] . ":</span>";
echo "<span class='htw'>" . $bet['howtowin'] . "</span></li>";
echo "<li><span class='uwager'>You wager <strong>$" . $bet['user2_wager'] . "</strong></span>";
echo "<span class='twager'>They wager <strong>$" . $bet['user1_wager'] . "</strong></span></li>";
echo "</div>";



echo "</div>";

}
} else{

echo "<h5>You have no challenges outstanding</h5>";
}
 
?>
