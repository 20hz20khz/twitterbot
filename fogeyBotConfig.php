<?php
require_once('twitteroauth.php');

define('CONSUMER_KEY', 'xxxxxxxxxxxxxxxxxxxx'); // Redacted
define('CONSUMER_SECRET', 'xxxxxxxxxxxxxxxxxxxx'); // Redacted
define('ACCESS_TOKEN', 'xxxxxxxxxxxxxxxxxxxx'); // Redacted
define('ACCESS_TOKEN_SECRET', 'xxxxxxxxxxxxxxxxxxxx'); // Redacted

$twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
$my_array = array("I recommend Glenn Miller","I recommend Benny Goodman","I recommend Mitch Miller","I recommend Lawrence Welk","I recommend Perry Como","I recommend Henry Mancini","I recommend Pat Boone");
$output_count = 0;
$twitter->host = "https://api.twitter.com/1.1/";
$search = $twitter->get('search/tweets', array('q' => '%22recommend%20music%20for%20me%22', 'rpp' => 5));

$twitter->host = "https://api.twitter.com/1.1/";
foreach($search->results as $tweet) {
	shuffle($my_array);
	$status = $my_array[0].' @'.$tweet->from_user.' '.$tweet->text;
	if(strlen($status) > 140) $status = substr($status, 0, 139);
	$twitter->post('statuses/update', array('status' => $status));
	$output_count++;
}

$twitter->host = "https://api.twitter.com/1.1/";
$search = $twitter->get('search/tweets', array('q' => '%22recommend%20music%20to%20me%22', 'rpp' => 5));

$twitter->host = "https://api.twitter.com/1.1/";
foreach($search->results as $tweet) {
	shuffle($my_array);
	$status = $my_array[0].' @'.$tweet->from_user.' '.$tweet->text;
	if(strlen($status) > 140) $status = substr($status, 0, 139);
	$twitter->post('statuses/update', array('status' => $status));
	$output_count++;
}

echo "Success! Check your twitter bot for ".$output_count." new tweets!";
print_r( array_values( $search ));
?>
