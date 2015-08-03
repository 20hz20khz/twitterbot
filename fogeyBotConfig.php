<?php
$consumer_key = "xxxxxxxx";// Redacted
$consumer_secret = "xxxxxxxx";// Redacted
$access_key = "xxxxxxxx";// Redacted
$access_secret = "xxxxxxxx";// Redacted

require_once('twitteroauth.php');// Use the twitteroauth library

$twitter = new TwitterOAuth ($consumer_key ,$consumer_secret , $access_key , $access_secret );// Connect
// Random replies
$randomReply = array("Glenn Miller","Benny Goodman","Mitch Miller","Lawrence Welk","Perry Como","Henry Mancini","Pat Boone","Duke Ellington","Cab Calloway","Count Basie","Louis Armstrong","Dean Martin","Bobby Darin","Billie Holiday","Ella Fitzgerald","Robert Goulet","Nat King Cole","Andy Williams","Mel Tormé","Frank Sinatra","Liza Minnelli","Tony Bennett","Bing Crosby","Johnny Mathis","Tom Jones","Jimmie Rodgers","Roy Rogers","Merle Travis","Merle Haggard","Hank Snow","Gene Autry","The Carter Family","Hank Williams","Flatt & Scruggs","Bill Monroe","Ralph Stanley","Buck Owens","Bob Wills and His Texas Playboys","Conway Twitty","Chet Atkins","Roger Miller");
$newTweetCount = 0;// Set the tweets count to zero
$newFavs = 0;// Set the favs count to zero
$myUserInfo = $twitter->get('account/verify_credentials');// Get the authorized user's info
$myLastTweet = $twitter->get('statuses/user_timeline', array('user_id' => $myUserInfo->id_str, 'count' => 1));// Use the user's info to get the authorized user's last tweet

// Search the Twitterverse for ppl saying 'recommend me music' and fav them and reply to them
$search = $twitter->get('search/tweets', array('q' => '%22recommend%20music%20for%20me%22OR%22recommend%20music%20to%20me%22OR%22recommend%20me%22AND%22music%22', 'count' => 5, 'since_id' => $myLastTweet[0]->id_str));// Get new search results since the user's last tweet
foreach($search->statuses as $tweet) {// Loop the following for each search result
	shuffle($randomReply);// Shuffle the random reply for each search result
	$status = 'I recommend '.$randomReply[0].' @'.$tweet->user->screen_name.' “'.$tweet->text.'”';// Tweet is composed of: random reply, @screen_name, and “the original tweet”
	if(strlen($status) > 140) $status = substr($status, 0, 139);// If tweet is too long, shorten it.
	if(count($tweet->entities->user_mentions) < 1){// If there are no @mentions
		if(empty($tweet->retweeted_status) and empty($tweet->quoted_status)){// If not retweet and not quote tweet
			$twitter->post('favorites/create', array('id' => $tweet->id_str));// Fav the original tweet
			$twitter->post('statuses/update', array('status' => $status,'in_reply_to_status_id' => $tweet->id_str));// Post tweet
			$newTweetCount++;// Add one to output counter
			echo $status."\n";// Display tweet
			echo "<br/>";
		} else {// If there is 1 or more @mentions
			if($tweet->entities->user_mentions[0]->screen_name == "fogeybot"){// If the first @mention is fogeybot
				$twitter->post('favorites/create', array('id' => $tweet->id_str));// Fav the original tweet
				$twitter->post('statuses/update', array('status' => $status,'in_reply_to_status_id' => $tweet->id_str));// Post tweet
				$newTweetCount++;// Add one to output counter
				echo $status."\n";// Display tweet
				echo "<br/>";
			}
		}
	}
}

// Check for @mentions and fav them
$search = $twitter->get('statuses/mentions_timeline', array('count' => 1, 'since_id' => $myLastTweet[0]->id_str));// Get new @mentions since the user's last tweet
//echo(count($search));echo "<br/>";
//$idString = (string)$search[0]->id_str;
if(count($search) > 0){// If search is not empty
//foreach($search->statuses as $tweet) {// Loop the following for each @mention
	$twitter->post('favorites/create', array('id' => (string)$search[0]->id_str));// Fav the original tweet
	$newFavs++;// Add one to output counter
}

// Make corny joke about new followers
//$userLists = $twitter->get('lists/list');// Get list ID
//echo " userLists ";print_r($userLists);// Print list ID
//$jokedList = $twitter->get('lists/members', array('list_id' => 215467717));// Get members of list
//echo " jokedList ";print_r($jokedList);// Print list ID
//$mostRecentFollower = $twitter->get('followers/list', array('user_id' => $myUserInfo->id_str, 'count' => 1));//
//echo " mostRecentFollower ";print_r($mostRecentFollower);// Print list ID
//foreach($jokedList->users as $usercheck) {// Loop the following for each search result
//	echo array_search($mostRecentFollower->users[0]->id_str,$usercheck->id_str);// true = 2795184106
//}

echo "Success! Check your twitter bot for ".$newTweetCount." new tweets, ".$newFavs." favs.";
?>
