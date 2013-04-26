<?php

post('/marta/push', function() use ($configuration){

	ini_set('error_log', '/tmp/push_error.log');
   // error_log(print_r($_POST, true));

	$checkin = json_decode($_POST['checkin']);
   $venue_url = $configuration['marta_url'][$checkin->venue->id];
   
   // error_log($venue_url);

	$lib = config('lib');
	include($lib . "/GoogleMapsPage.php");

   $venue_html = file_get_contents($venue_url);
   $x = new GoogleMapsPage($venue_html);

	$allTimes = $x->findAllTimes();

   $times = array();
   foreach ($allTimes as $label => $value) {
     $times[$label] = $value;
   }

		$dbpath = config('db_path');
		$db = new PDO("sqlite:${dbpath}");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$statement = $db->prepare("SELECT oauth_key FROM user WHERE foursquare_id = :id");
    $result = $statement->execute(array(":id" => $checkin->user->id));
   $object = $statement->fetchObject();

   $statement = $db->prepare("INSERT INTO checkins (checkin_id, timeTable) VALUES (:checkin, :times)");
   $statement->execute(array(":checkin" => $checkin->id, ":times" => json_encode($times)));

   $url = "https://andrewmoorewatson.com/marta/checkin/" . $checkin->id;
   error_log($url);

   $fsq_reply = sprintf('https://api.foursquare.com/v2/checkins/%s/reply?oauth_token=%s',
     $checkin->id,
     $object->oauth_key);

   $fsq_ch = curl_init($fsq_reply);
   curl_setopt($fsq_ch, CURLOPT_POST, true);
   curl_setopt($fsq_ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($fsq_ch, CURLOPT_POSTFIELDS, "text=Train Times&url={$url}" );
   $resp = curl_exec($fsq_ch);

   echo $resp;
});
