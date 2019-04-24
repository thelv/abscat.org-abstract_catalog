<?php

	//auth through ulogin
	
		if($_GET['logout'])
		{
			session_start();
			$_SESSION['user']=false;
			header('Location: '.str_replace('?logout=1', '', $_SERVER['REQUEST_URI']));
		}
		else if($_POST['token'])
		{		
			$s=file_get_contents('http://ulogin.ru/token.php?token='.$_POST['token'].'&host='.$_SERVER['HTTP_HOST']);						
			
			$user=json_decode($s, true);
			if($user['identity'])
			{			
				$user_auth_cookie=$user['network'].$user['uid'].rand(0, 10000000).rand(0, 10000000).rand(0, 10000000).rand(0, 10000000).rand(0, 10000000).rand(0, 10000000).rand(0, 10000000).rand(0, 10000000);
				
				if(! $db) $db=pg_connect('host=localhost dbname=cat user=cat_www password=ifjqohfisfnaqwpdij3498ty2378gf1c123');
				$res=pg_query_params($db,
				'
					insert into "user" 
					(
						network, network_user_id, network_url, first_name, last_name, auth_cookie
					)
					select 
						$1, $2, $3, $4, $5, $6
					where
						not exists 
						(
							select network, network_user_id from "user" where network=$7 and network_user_id=$8
						)
					returning 
						id
				', array
				(
					$user['network'], $user['uid'], $user['identity'], $user['first_name'], $user['last_name'], $user_auth_cookie, $user['network'], $user['uid']
				));
				
				if($row=pg_fetch_array($res))
				{
					pg_query_params($db,
					'
						insert into "cat" (user_id, data)
						values($1, $2) returning id
					', array
					(
						$row['id'], '{"objects": {}, "connections": {}}'
					));					
				}
				
				$res=pg_query_params($db, 'select * from "user" where network=$1 and network_user_id=$2', array($user['network'], $user['uid']));
				if(! $user=pg_fetch_array($res)) die('auth_error');
								
				setcookie('user_auth', $user['auth_cookie'], time()+1000*24*3600);		
				
				session_start();
				$_SESSION['user']=$user;
				session_write_close();													
			}
		}
		else
		{
			session_start();
			if(! $user=$_SESSION['user'])
			{			
				if($_COOKIE['user_auth'])
				{				
					session_write_close();
					
					if(! $db) $db=pg_connect('host=localhost dbname=cat user=cat_www password=ifjqohfisfnaqwpdij3498ty2378gf1c123');
					$res=pg_query_params($db, 'select * from "user" where auth_cookie=$1', array($_COOKIE['user_auth']));
					$user=pg_fetch_array($res);
										
					session_start();
					$_SESSION['user']=$user;
					session_write_close();
				}
			}
		}
		
	// ! auth
	
?>