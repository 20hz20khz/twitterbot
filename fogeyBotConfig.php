<?php
$consumer_key = "XXXXXXXXXXXXXXX";// Redacted
$consumer_secret = "XXXXXXXXXXXXXXX";// Redacted
$access_key = "XXXXXXXXXXXXXXX";// Redacted
$access_secret = "XXXXXXXXXXXXXXX";// Redacted

require_once('twitteroauth.php');// Use the twitteroauth library

$twitter = new TwitterOAuth ($consumer_key ,$consumer_secret , $access_key , $access_secret );// Connect
$randomReply = array("Glenn Miller","Benny Goodman","Mitch Miller","Lawrence Welk","Perry Como","Henry Mancini","Pat Boone","Duke Ellington","Cab Calloway","Count Basie","Louis Armstrong","Dean Martin","Bobby Darin","Billie Holiday","Ella Fitzgerald","Robert Goulet","Nat King Cole","Andy Williams","Mel Tormé","Frank Sinatra","Liza Minnelli","Tony Bennett","Bing Crosby","Johnny Mathis","Tom Jones","Jimmie Rodgers","Roy Rogers","Merle Travis","Merle Haggard","Hank Snow","Gene Autry","The Carter Family","Hank Williams","Flatt & Scruggs","Bill Monroe","Ralph Stanley","Buck Owens","Bob Wills and His Texas Playboys","Conway Twitty","Chet Atkins","Roger Miller"); // Random replies
$output_count = 0;// Set the output count to zero
$myUserInfo = $twitter->get('account/verify_credentials');// Get the authorized user's info
$myLastTweet = $twitter->get('statuses/user_timeline', array('user_id' => $myUserInfo->id_str, 'count' => 1));// Use the user's info to get the authorized user's last tweet
$search = $twitter->get('search/tweets', array('q' => '%22recommend%20music%20for%20me%22OR%22recommend%20music%20to%20me%22OR%22recommend%20me%22AND%22music%22', 'count' => 5, 'since_id' => $myLastTweet[0]->id_str));// Get new search results since the user's last tweet

foreach($search->statuses as $tweet) {// Loop the following for each search result
	shuffle($randomReply);// Shuffle the random reply for each search result
	$status = 'I recommend '.$randomReply[0].' @'.$tweet->user->screen_name.' “'.$tweet->text.'”';// Tweet is composed of: random reply, @screen_name, and “the original tweet”
	if(strlen($status) > 140) $status = substr($status, 0, 139);// If tweet is too long, shorten it.
	echo $status."\n";// Display tweet
	$twitter->post('favorites/create', array('id' => $tweet->id_str));// Fav the original tweet
	$twitter->post('statuses/update', array('status' => $status,'in_reply_to_status_id' => $tweet->id_str));// Post tweet
	$output_count++;// Add one to output counter
}

echo "Success! Check your twitter bot for ".$output_count." new tweets!;

?>
