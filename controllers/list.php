<?php

get('/lists', function() {

	$user_id = $_SESSION['user_id'];

	$sql = "
	SELECT 
		item_lists.id,
		description,
		foursquare_category,
		phone_number ,
		formatted_number
	FROM 
		item_lists, 
		phone_numbers 
	WHERE 
		phone_number_ref = phone_numbers.id AND 
		item_lists.user_id = ${user_id}";

	$q = mysql_query($sql);
	echo mysql_error();

	$item_lists = array();
	while($list = mysql_fetch_array($q, MYSQL_ASSOC)) {
		$item_lists[] = $list;
	}

	$sql = "
	SELECT
		id, description, item_list_id
	FROM
		queued_items qi
	WHERE
		user_id = ${user_id}
	";

	$q = mysql_query($sql);

	$queued_items = array();
	while($row = mysql_fetch_array($q, MYSQL_ASSOC)) {
		if (!isset($queued_items[ $row['item_list_id']]) ) {
			$queued_items[ $row['item_list_id']] = array();
		}

		$queued_items[ $row['item_list_id']][ $row['id']] = $row['description'];
	}

	template('extra_js', array('pusher','lists'));
	template('item_lists', $item_lists);
	template('queued_items', $queued_items);

	display('lists');
});
