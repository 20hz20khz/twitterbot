<?php
$consumer_key = "XXXXXXXXXXXXXXX";// Redacted
$consumer_secret = "XXXXXXXXXXXXXXX";// Redacted
$access_key = "XXXXXXXXXXXXXXX";// Redacted
$access_secret = "XXXXXXXXXXXXXXX";// Redacted

require_once('twitteroauth.php');

$twitter = new TwitterOAuth ($consumer_key ,$consumer_secret , $access_key , $access_secret );
$my_array = array("I recommend Glenn Miller","I recommend Benny Goodman","I recommend Mitch Miller","I recommend Lawrence Welk","I recommend Perry Como","I recommend Henry Mancini","I recommend Pat Boone");
$output_count = 0;
$myUserInfo = $twitter->get('account/verify_credentials');
$myLastTweet = $twitter->get('statuses/user_timeline', array('user_id' => $myUserInfo->id_str, 'count' => 1));
$search = $twitter->get('search/tweets', array('q' => '%22recommend%20music%20for%20me%22', 'count' => 1, 'since_id' => $myLastTweet[0]->id_str));

foreach($search->statuses as $tweet) {
	shuffle($my_array);
	$status = $my_array[0].' @'.$tweet->user->screen_name;
	if(strlen($status) > 140) $status = substr($status, 0, 139);
	echo $status."\n";
	$twitter->post('statuses/update', array('status' => $status,'in_reply_to_status_id' => $tweet->id_str));
	$output_count++;
}

echo "Success! Check your twitter bot for ".$output_count." new tweets! My last tweet was ".$myLastTweet[0]->id_str;

?>
