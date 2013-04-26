<?php

post('/sms', function() {

	ini_set('error_log', '/tmp/sms_error.log');

	if (is_numeric($_POST['Body'])) {
		$SQL = "SELECT * from user_record WHERE cell_activation = " . $_POST['Body'];
		$Q = mysql_query($SQL);
		header("Content-type: text/xml");
		
		if (mysql_num_rows($Q) > 0) {
			mysql_query("UPDATE user_record SET cell_phone = '" . $_POST['From'] . 
				"' WHERE cell_activation = ". $_POST['Body']);

			error_log(mysql_error());
			print("<Response><Sms>Your cell phone has been connected to ShopCheck.in</Sms></Response>");
		} else {
			print("<Response><Sms>Your ShopCheck.in activation code could not be found.</Sms></Response>");
			
		}

		die;

	} else {


		$SQL = "SELECT user_id FROM user_record WHERE cell_phone = '" . $_POST['From'] . "'";
		$q = mysql_query($SQL);

		if (mysql_error()) {
			throw new Exception(mysql_error());
		}

		$user_id = mysql_result($q, 0,0);
		error_log($user_id);

		$incoming = $_POST['To'];
		$sql = "
			SELECT 
				il.id 
			FROM 
				item_lists il, 
				phone_numbers pn 
			WHERE 
				il.phone_number_ref = pn.id AND 
				pn.phone_number = '${incoming}' AND 
				il.user_id = ${user_id}
		";

		$q = mysql_query($sql);
		// error_log(mysql_error());

		$item_list_id = mysql_result($q,0,0);

		// error_log(mysql_error());

		$SQL = sprintf(
			"INSERT INTO queued_items (user_id, item_list_id, description) VALUES (%d, %d, '%s')", 
			$user_id, $item_list_id, $_POST['Body']);

		error_log($SQL);
		mysql_query($SQL);

		// include($_SERVER['DOCUMENT_ROOT'] . "/../lib/Pusher/lib/Pusher.php");
		// $pusher = new Pusher(config('pusher_key'), config('pusher_secret'), config('pusher_app_id'));
		// $pusher->trigger('listitem-channel', 'listitem-removeitem', '{"hello":"world"}');

		header('Content-type: text/xml');
		// echo "<Response><Sms>Item Added</Sms></Response>";
		echo "<Response></Response>";
		die;

	}
});

