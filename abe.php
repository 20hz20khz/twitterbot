<?php
$consumer_key = "RAjxc50PmNEyysQGHobMEd7fY";
$consumer_secret = "p8yrPhcUVvNrsozk6CoaG2MHX8C2cCV5RgJB2muKYNbXTOBN1R";
$access_key = "3270227106-UvjBjD6OqbvzSpkqGB38C389IrzF8dgOHpEeNvy";
$access_secret = "1DMNvbpvIXwRuyiOb8ws3TiFu5h4qA69xfOOk6GhNNuIo";

require_once('twitteroauth.php');

$connection = new TwitterOAuth ($consumer_key ,$consumer_secret , $access_key , $access_secret );

$connection->post('statuses/update', array('status' => "Hello Twitter OAuth!"));
?>
