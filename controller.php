<?php

require_once 'Breeze.php';

$configuration = parse_ini_file('configuration.ini', true);

config('foursquare_id', $configuration['foursquare']['client_id']);
config('foursquare_secret', $configuration['foursquare']['client_secret']);
config('push_secret', $configuration['foursquare']['push_secret']);
config('redirect_url', $configuration['foursquare']['redirect_url']);

config('mysql_db', $configuration['database']['dbname']);

config('pusher_app_id','10383');
config('pusher_key','ad788cf2c7ec44f53493');
config('pusher_secret','a28bde503747a754344d');

config('stripe_key', 'sJVe0YCQ9gvnX4ODBT3w20v5M7NJHTTO');

config('lib', dirname(__FILE__) . "/library");

$db_path = dirname(__FILE__) . "/db/marta.db";
config('db_path', $db_path);

before( function() {
	ini_set("error_log", "/tmp/amw.log");
   session_start();
});

	get('/about', function() {
		display('about');
	});
	
	get('/', function() {
		display('index');
	});

	get('/marta', function() use ($configuration) {
      display('marta');
   });

   get('/privacy', function() {
		display('privacy');
   });

   include("controllers/admin.php");
	include("controllers/auth.php");
	include("controllers/push.php");
	include("controllers/checkin.php");
	include("controllers/loggedin.php");

	run();
