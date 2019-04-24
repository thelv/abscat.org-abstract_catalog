<?php

	//ini_set('display_errors', 1);

	$db=pg_connect('host=localhost dbname=cat user=cat_www password=ifjqohfisfnaqwpdij3498ty2378gf1c123');
	//pg_query($db, 'set search_path to stat');
	
	session_start();
	$user=$_SESSION['user'];
	session_write_close();
	
	if($_GET['type']=='cats')
	{
		$res=pg_query($db, 'select id, user_id from "cat" order by id');
		$rows=pg_fetch_all($res);
		echo json_encode($rows);
	}
	elseif($_GET['type']=='cat')
	{
		$res=pg_query_params($db, 'select * from "cat" where user_id=$1', array($_GET['cat_id']));
		$row=pg_fetch_array($res);
		$data=$row['data'];
		print_r($data);
	}
	elseif($_GET['type']=='cat_save')
	{
		$res=pg_query_params($db, 'update "cat" set data=$1 where user_id=$2 and user_id=$3 returning user_id', array($_POST['data'], $_POST['cat_id'], $user['id']));
		$row=pg_fetch_array($res);
		if($row['user_id']==$user['id']) echo 'OK'; else echo 'ERROR';
	}

?>