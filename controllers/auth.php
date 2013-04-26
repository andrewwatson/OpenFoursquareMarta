<?php

get('/marta/auth', function() {

	   $auth_url = "https://foursquare.com/oauth2/authenticate?client_id=" .
		config('foursquare_id') .
      "&response_type=code&redirect_uri=" . config('redirect_url');

		redirect($auth_url);
});

get('/marta/auth/code', function() {

	$verify_url = "https://foursquare.com/oauth2/access_token?client_id=" . config('foursquare_id') .
     "&response_type=code&client_secret=" . config('foursquare_secret') ."&redirect_uri=".  config('redirect_url').
     "&grant_type=authorization_code&code=". $_GET['code'];

      $ch = curl_init($verify_url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($ch);

		$response = json_decode($response);
		if (property_exists($response, 'access_token')) {
	      $access_token = $response->access_token;

		} else {
         error(500, "oops");
      }

      $ch = curl_init("https://api.foursquare.com/v2/users/self?oauth_token=".$access_token);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      $response = curl_exec($ch);
      $json = json_decode($response);

		$user_id = $json->response->user->id;

		$_SESSION['user_id'] = $user_id;
		$_SESSION['logged_in'] = true;

		$first = $json->response->user->firstName;
		$last = $json->response->user->lastName;
		$email = $json->response->user->contact->email;

		$dbpath = config('db_path');

		$db = new PDO("sqlite:${dbpath}");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

		$statement = $db->prepare("INSERT INTO user (foursquare_id, email, oauth_key) values (:fid, :email, :oauth)");
      $statement->execute(array(":fid" => $user_id, ":email" => $email, ":oauth" => $access_token));

      $_SESSION['auth_id'] = $user_id;
      redirect('/marta/success');
});

