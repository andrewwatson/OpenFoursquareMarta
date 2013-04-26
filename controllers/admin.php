<?php

get('/marta/admin', function() {

		$dbpath = config('db_path');

		$db = new PDO("sqlite:${dbpath}");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

      $stmt = $db->prepare("SELECT count(*) as userCount FROM user");
      $stmt->execute();
      template('userCount', $stmt->fetchObject());

      $stmt = $db->prepare("SELECT count(*) as checkinCount FROM checkins");
      $stmt->execute();
      template('checkinCount', $stmt->fetchObject());

  display('admin');
});
