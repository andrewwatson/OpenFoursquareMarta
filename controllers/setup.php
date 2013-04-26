<?php

	get('/setup/sms', function() {
	
			template('extra_js', array('pusher','setup'));
			template('step', 'phone');
			template('random', $_SESSION['activation']);
			display('profile');
	});

	get('/setup/plan', function() {

			template('step', 'plan');
			display('profile');

	});

	post('/setup/plan', function() {

		$user_plan = $_POST['plan'];
		$user_id = $_SESSION['user_id'];

		$sql = "SELECT id FROM ref_subscription WHERE short_desc = '${user_plan}'";
		$plan_id = mysql_result( mysql_query($sql), 0,0);

		echo $plan_id;

		$sql = "UPDATE user_plan SET plan_chosen = ${plan_id} WHERE user_id = ${user_id}";
		mysql_query($sql);
		redirect('/setup/lists');

	});

	get('/setup/lists', function() {

			$sql = "SELECT plan_chosen, description FROM user_record, ref_subscription WHERE " .
				"ref_subscription.id = user_record.plan_chosen";

			$q = mysql_query($sql);
			$row = mysql_fetch_array($q, MYSQL_ASSOC);

			template('plan_description', $row['description']);
			template('step', 'lists');
			display('profile');
	});

	post('/setup/lists', function() {
		$user_id = $_SESSION['user_id'];

		mysql_query("INSERT INTO item_lists (user_id, description, foursquare_category, phone_number_ref) VALUES 
			('${user_id}', 'First List', 'Grocery or Supermarkets', 1),('$user_id}', 'Second List','Drugstore or Pharmacy',2)");
		echo mysql_error();

		redirect('/setup/lists');
	});

