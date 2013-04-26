<?php
get(';^/marta/checkin/(\w+)$;', function($app, $params) {

  
		$dbpath = config('db_path');
		$db = new PDO("sqlite:${dbpath}");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   $statement = $db->prepare("SELECT timeTable FROM checkins WHERE checkin_id = :checkin");
   $statement->execute(array(":checkin" => $params[1]));
   $object = $statement->fetchObject();

   $times = json_decode($object->timeTable);

	template('times', $times);
   layout('checkin_layout');
   display('checkin');
});
