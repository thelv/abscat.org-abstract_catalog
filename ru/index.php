<?php

	//ini_set('display_errors', 1);	

	//auth through ulogin
	
		if($_GET['logout'])
		{
			session_start();
			$_SESSION['user']=false;
			header('Location: /');
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
<html>
	<head>
		<meta charset="utf-8">
		<meta name=viewport content='width=700px'>
		<script src="/js/jquery.js">
		</script>		
		<script src="/js/jquery.cookie.js">
		</script>
		<script src="//ulogin.ru/js/ulogin.js"></script>		
		<style>
			*{padding:0;margin:0;text-decoration:none;}
			body{display:table}
			body, input, textarea, table{font-family:tahoma;font-size:14px;font-weight:normal;text-decoration:none;color:#000}
			.__blue{color:#22f;cursor:pointer}
				.__blue:hover{text-decoration:underline}
			
			#head{position:fixed;top:0;left:0;width:100%}
			#head > div{font-size:15px;overflow:hidden;margin:0px 0px;border-bottom:1px solid #ddd;padding:6px 10px 6px 10px;background:#eee;height:18px}
				#head *{font-size:15px;font-weight:-bold}
				h1{font-weight:bold;float:left}
				h1 a{color:#000}
				#head_menu{float:left;margin-left:10px;}
					#head_menu *{color:#000;color: 	#22f}
					#head_menu > div{margin-right:10px;float:left}
					#head_menu > div > ._slash{margin:0px 5px}
					#head_menu > div > a{cursor:pointer;text-decoration:-underline}
					#head_menu > div > a:hover{text-decoration:underline}
					
				#profile{float:right}
					#profile_header{color:#888;margin-right:3px;display:none}
					#profile ._name{font-weight-:bold}
					#uLogin{float:right;margin-left:5px}
					#logout{margin-left:7px;color:#22f;cursor:pointer}
						#logout:hover{text-decoration:underline}
					#save{-margin-right:7px;-color:#888}
					#save{padding:5px 10px;position:fixed;bottom:0;right:0;background:rgba(220, 220, 220, 0.94);margin-bottom:27px}
			
			#body{font-size:14px;overflow:hidden;margin:6px 10px;margin-top:37px;}							
			
				/*.help{border:1px solid #ccc;padding:5px 10px;display:inline-block;margin:4px 0 6px 0}				
				.__blue{color:#22f;cursor:pointer}
				.__blue:hover{text-decoration:underline}*/

			
				.body_head{display:block;margin-bottom:0;5px;font-weight:bold;margin-top:3px}
				.body_loading{height:37px}					
					.body_loading div{position:absolute;width:200px;height:20px;background:rgba(255, 255, 255, 0.87)}
				.body_menu{margin-top:6px;}
					.body_menu a{display:inline-block;margin-right:10px;color:#22f;cursor:pointer}
						.body_menu a:hover{text-decoration:underline}
				.body_body{margin-top:6px}				
					.body_head + .body_body{margin-top:7px}
				
					#page_about{}
						#page_about ._link{color:#22f;cursor:pointer}
							#page_about ._link:hover{text-decoration:underline}
					
					#page_cats{}
						#page_cats ._cat{margin-bottom:7px;color:#22f;}
							#page_cats ._cat span:hover{text-decoration:underline;cursor:pointer}
						#page_cats .body_loading{margin-top:11px}
						#page_cats .body_body{margin-top:7px}
					
					#page_catalog{}						
						.object_filter{margin-top:11px}
							.object_filter input[type=text]{width:100px;text-indent:2px}
							.object_filter input[type=submit]{padding: 0 10px;margin-left:1px}
							.object_filter input[type=reset]{padding: 0 7px;margin-left:1px}
							
						/*.object_table{overflow-x:auto}*/
						.table{vertical-align:top;margin-top:15px;11px}
							.table tr{vertical-align:top}
							.table td{vertical-align:top;}
							.table td:first-child + td{border-left:1px solid #ccc}
							.table td:first-child + td > div{padding-left:15px}
							.table td:first-child .table_type_head{border-bottom:1px solid #ccc;padding-right:15px;color:#888;padding-right:0}
							.table td:first-child .table_type_objects{margin-right:15px;padding-right:0;color:#888;}
							.table td:last-child .table_type_head, table td:last-child .table_type_object{padding-right:25px}
							table td:last-child .table_type_object img{right:3px !important}
							.table td:last-child{padding-right:5px}
							.table td:first-child .table_type_head{margin-bottom:7px}
							.table_type_head{font-weight-:bold;font-style-:italic;margin-bottom:/*7px*/5px;padding-bottom:8px;border-bottom:1px solid #ccc;padding-right:29px;color:#000}							
								.table_type_head a{cursor:pointer;color:#000}
								.table_type_head a:hover{cursor:pointer;text-decoration:underline}
							.table_type_objects{}							
								.table_type_object{/*margin-bottom:5px;*/padding-bottom:3px;padding-top:2px;max-width:200px;padding-right:29px}
									.table_type_object > div{overflow:hidden}
									.table_type_object a{cursor:pointer;white-space:nowrap;color:#000}
									.table_type_object a:hover{text-decoration:underline}
									.table_type_object img{display:none;cursor:pointer}
									.table_type_object:hover img{display:block}
									
						#page_catalog .body_loading{margin-top:16px}
						
					#page_catalog_object{}
						#trees{}														
							.tree{margin-top:5px}
							.tree .object{color:#000;line-height:21px;cursor:pointer;display:inline-block}
								.tree .object:hover{text-decoration:underline}
									
							.tree .childs{padding-left:20px;border-left:1px dotted #ccc}					
					
							.tree .branch{overflow:hidden;background-:#fff;margin-left:-1px;border-left:1px solid inherit}						
							
								.tree .connection{color:#999;cursor:pointer}
									.tree .connection > span{cursor:pointer}	
									.tree .connection span:hover{text-decoration:underline}
								
								.tree .branch #menu{}
								
						#page_catalog_object .body_loading{margin-top:10px}
							
				#menu{line-height:22px;border:1px solid #ddd;e7e7e7;display:inline-block;padding:0px 0 0px 0;background:#eee;cursor:pointer;position:absolute;/*margin-top:22px*/}
					#menu *{color:#333}
					#menu > div{padding:0px 10px 0px;white-space:nowrap}
						#menu > div:first-child{padding-top:2px}
						#menu > div:last-child{padding-bottom:2px}					
						#menu > div:hover{background:#e0e0e0;color:#000}							
						#menu ._separ{border-bottom:1px solid #ddd;margin:5px 10px 4px 10px}
				
				#buffer{padding:5px 10px;position:fixed;bottom:0;left:0;background:rgba(220, 220, 220, 0.94)}
					#buffer > ._head{font-weight:bold;margin-right:5px}
					#buffer > ._params{margin-right-:5px}
						#buffer > ._params > span{margin-right:5px}
					#buffer > ._clear{color:#22f;cursor:pointer;margin-left:2px}
						#buffer > ._clear:hover{text-decoration:underline;}
				
				#youtube_{position:fixed;right:0;bottom:0;width:360px;height:233px;border:1px solid #ccc;border-width:1px 0 0 1px;background:#eee;z-index:}
				#youtube_._hided{height:27px;background:rgba(220, 220, 220, 0.94);border:0}
					#youtube_._hided ._video{height:0}
					#youtube_._hided ._head{height:17px;padding:5px 10px}
					
					#youtube_ ._head{padding:5px 7px 5px 7px;height:20px}
						#youtube_ ._head_title{float:left;font-weight:bold}
					#youtube_ ._head a{color:#22f;margin-left:7px;cursor:pointer;float:right;}
					#youtube_ ._head a:hover{color:#22f;text-decoration:underline}					
						#youtube_ ._head select{padding:0 2px;width:150px;margin-top:-1px}
						#youtube_ iframe{font-weight:normal;width:360px;height:203px}
						
					#youtube_ ._action_show{display:none;}
					#youtube_ ._action_stop{display:none;}
					#youtube_._hided ._action_hide{display:none;}					
					#youtube_._hided ._action_show{display:block;}
					#youtube_._hided ._action_stop{display:block;}
						
			#window_container{display-:none;position:fixed;left:0;top:0;background:rgba(0,0,0,0.5);width:100%;height:100%;display:flex;align-items:center;justify-content:center}
				.window{/*margin: 100px auto;*/background:#fff;border:1px solid #ccc;width: 1152px;margin-bottom:75px}
					.window > ._head{font-size:15px;font-weight-:bold;padding:5px 0;border-bottom:1px solid #ddd;background:#eee;text-indent:24px;10px}
					.window > ._body{padding:0px 10px;margin-top:6px;overflow:hidden}
						.window ._form{padding-right:20px;display:inline-block;vertical-align:top}
						.window ._form_last{padding-right:0}
							.window ._form > ._head{margin-bottom:6px;padding-left:14px;font-weight:bold;margin-top:3px}
							.window ._form > ._body{}
								.window ._form  ._element{margin-bottom:10px;vertical-align:-top;padding-left:12px;background: url(/img/list3.png) no-repeat 0px 6px;margin-left:-2px; padding-left:16px;}
									.window ._form ._element > ._head{margin-bottom:5px}
									.window ._form ._element > ._body{}
									.window ._form ._element > ._help{}
						.window input[type=text]{text-indent:2px}
						.window textarea{padding:0 3px}
					.window ._actions{margin:15px 0 10px;text-align:right;clear:both;}
						.window ._actions button{padding: 3px 9px;margin-right:7px;}
						.window ._actions button:last-child{margin-right:0px;}
						.window ._actions ._button_ok{margin-right:7px;padding:3px 12px}
									
				#window_object_connect_with_new_object{width:547px}
					#window_object_connect_with_new_object ._form{width:200px}
					#window_object_connect_with_new_object input[type=text]{width:186px}
					#window_object_connect_with_new_object ._form:last-child{width:300px}
					#window_object_connect_with_new_object textarea{width:286px}
				#window_object_connect_with_object_from_buffer{width:547px}
					#window_object_connect_with_object_from_buffer ._form{width:200px}
					#window_object_connect_with_object_from_buffer input[type=text]{width:186px}
					#window_object_connect_with_object_from_buffer ._form:last-child{width:300px}
					#window_object_connect_with_object_from_buffer textarea{width:286px}
				#window_object_edit{width:327px}
					#window_object_edit ._form{width:200px}
					#window_object_edit input[type=text]{width:186px}
					#window_object_edit ._form:last-child{width:300px}
					#window_object_edit textarea{width:286px}
				#window_object_new{width:327px}
					#window_object_new ._form{width:200px}
					#window_object_new input[type=text]{width:186px}
					#window_object_new ._form:last-child{width:300px}
					#window_object_new textarea{width:286px}
				#window_object_remove, #window_alert{width:350px}							
				#window_connection_edit{width:327px}
					#window_connection_edit ._form{width:200px}
					#window_connection_edit input[type=text]{width:186px}
					#window_connection_edit ._form:last-child{width:300px}
					#window_connection_edit textarea{width:286px}
				#window_connection_remove{width:350px}
				
			/* logic */
			
				#buffer, #menu{display:none}
				#window_container{display:none}
				#window_container > div{display:none}
				.page{display:none}				
				#save{display:none}
			
			/* ! logic */
		</style>		
	</head>
	<body>
		<div id="head"><div>
			<h1>
				Music Catalog
			</h1>
			<div id="head_menu">			
				<div>
					<a class="_about">
						о проекте
					</a>
				</div>
				<div class="_cats">
					<a>
						все каталоги
					</a>					
				</div>
				<div>
					<a class="_my_cat">
						мой каталог
					</a>
				</div>		
			</div>		
			<div id="profile">				
					<!--
				 --><?php
					
						if(! $user)
						{
							
					?><!--
					
						-->войти: <!--<div id="uLogin" data-ulogin="display=small;theme=classic;fields=first_name,last_name;providers=vkontakte,odnoklassniki,mailru,facebook;hidden=other;redirect_uri=http%3A%2F%2Fabscat.org;mobilebuttons=0;" data-ulogin-inited="1488011826525" style="position: relative;"><div class="ulogin-buttons-container" style="margin: 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: default; float: none; position: relative; display: inline-block; width: 105px; height: 16px; left: 0px; top: 0px; box-sizing: content-box; max-width: 100%; min-height: 16px; vertical-align: top; line-height: 0;"><div class="ulogin-button-googleplus" data-uloginbutton="googleplus" role="button" title="Google+" style="margin: 0px 5px 5px 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: pointer; float: left; position: relative; display: inherit; width: 16px; height: 16px; left: 0px; top: 0px; box-sizing: content-box; background: url(&quot;https://ulogin.ru/version/2.0/img/providers-16-classic.png?version=img.2.0.0&quot;) 0px -358px / 16px no-repeat;"></div><div class="ulogin-button-mailru" data-uloginbutton="mailru" role="button" title="Mail.ru" style="margin: 0px 5px 5px 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: pointer; float: left; position: relative; display: inherit; width: 16px; height: 16px; left: 0px; top: 0px; box-sizing: content-box; background: url(&quot;https://ulogin.ru/version/2.0/img/providers-16-classic.png?version=img.2.0.0&quot;) 0px -52px / 16px no-repeat;"></div><div class="ulogin-button-odnoklassniki" data-uloginbutton="odnoklassniki" role="button" title="Odnoklassniki" style="margin: 0px 5px 5px 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: pointer; float: left; position: relative; display: inherit; width: 16px; height: 16px; left: 0px; top: 0px; box-sizing: content-box; background: url(&quot;https://ulogin.ru/version/2.0/img/providers-16-classic.png?version=img.2.0.0&quot;) 0px -35px / 16px no-repeat;"></div><div class="ulogin-button-vkontakte" data-uloginbutton="vkontakte" role="button" title="VK" style="margin: 0px 5px 5px 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: pointer; float: left; position: relative; display: inherit; width: 16px; height: 16px; left: 0px; top: 0px; box-sizing: content-box; background: url(&quot;https://ulogin.ru/version/2.0/img/providers-16-classic.png?version=img.2.0.0&quot;) 0px -18px / 16px no-repeat;"></div><img class="ulogin-dropdown-button" src="https://ulogin.ru/img/blank.gif" style="margin: 0px 5px 5px 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: pointer; float: none; position: relative; display: inline; width: 16px; height: 16px; left: 0px; top: 0px; box-sizing: content-box; background: url(&quot;https://ulogin.ru/version/2.0/img/providers-16-classic.png?version=img.2.0.0&quot;) 0px -1px / 16px no-repeat; vertical-align: baseline;"></div><img src="https://ulogin.ru/img/link.png" style="margin: 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: default; float: none; position: absolute; display: none; width: 8px; height: 4px; left: 0px; top: 0px; box-sizing: content-box; z-index: 9999;"></div>
						--><div id="uLogin" data-ulogin="display=small;theme=classic;fields=first_name,last_name;providers=vkontakte,odnoklassniki,mailru,facebook;hidden=other;redirect_uri=http%3A%2F%2Fabscat.org%2F;mobilebuttons=0;"></div>
					<?
					
						}else{
							
					?><!--
						
						--><span id=profile_header>Пользователь:</span><span class=_name><?= $user['first_name'].' '.$user['last_name'] ?></span><span id=logout>выйти</span>
						
					<?
					
						}
						
					?>
					
			</div>
		</div></div>	
		
		<div id="body">
			<div id="page_about" class="page">
			<div class="body_head">
					О проекте
				</div>
				<div class="body_body">
					You can create <a class="_my_cat_link _link">your own catalog</a> or discover other people <a class="_cats_link _link">catalogs</a>.
				</div>				
			</div>
			<div id="page_cats" class="page">
				<div class="body_head">
					Все каталоги
				</div>
				<div class="body_body">					
					<!-- -->
				</div>
				<div class="body_loading">
					<div></div>
					<img src="/img/loading.gif">
				</div>
			</div>
			<div id="page_catalog" class="page">
				<div class=_fix_on_scroll>
					<div class="body_head">
						Мой каталог
					</div>
					<div class="body_menu">
						<a class=_object_new>
							создать новый объект
						</a>
					</div>
				</div>
				<div class="body_body">
					<!-- <div class=help>
						<b style='color:rgb(238, 68, 68);'>Warning:</b> where is some catalog you build while beeing NOT logged in. To do NOT lose it you can <a class=__blue>export it in file</a>. Or just <a class=__blue>send it to trash</a>.
					</div> -->
					<div class=_fix_on_scroll>
						<div class="object_filter">
							<form>
								Фильтр объектов: тип~ <input class=object_filter_type type=text>, текст~ <input class=object_filter_text type=text>
								<input type=submit value=ок> <input type=reset value=очистить>
							</form>
						</div>
					</div>
					<div class="object_table">
					</div>
				</div>
				<div class="body_loading">
					<div></div>
					<img src="/img/loading.gif">
				</div>
			</div>
			<div id="page_catalog_object" class="page">
				<div class="body_head">
					Мой каталог - Объект
				</div>
				<div class="body_menu">
					<a class='_back'>
						← назад
					</a>
				</div>
				<div class="body_body">
					<div id=trees>
					</div>
				</div>
				<div class="body_loading">
					<div></div>
					<img src="/img/loading.gif">
				</div>
			</div>										
			
			<div id="youtube_" class="_hided">
				<div class=_head>
					<div class=_head_title>видео:</div>&nbsp;<select><option>Mage - The time Is Always Running Out</option></select><!--
					--><a class="_action_close">закрыть</a><a class="_action_show">показать</a><a class="_action_hide">скрыть</a><a class="_action_stop">стоп</a>
				</div>
				<div class=_video>
					<iframe frameborder=0 src=''></iframe>
				</div>
			</div>
			
			<div id="buffer">
				<span class="_head">буффер:</span><span class="_params"><span>id=<span class="_id_value"></span>,</span><span>text="<span class="_text_value"></span>",</span><span>type="<span class="_type_value"></span>"</span></span><span class="_clear">очистить</span>
			</div>
			
			<div id="save">
			</div>
			
			<div id="menu">				
			</div>
			
			<div id="window_container">
				<div id=window_alert class=window>
					<div class=_head>
						Сообщение
					</div>
					<div class=_body>					
						<div class="_form _form_last">
							<div class=_head>
														
							</div>
						</div>
						<div class=_actions>
							<button class=_button_ok>OK</button><button class=_button_cancel>Cancel</button>
						</div>
					</div>
				</div>
				<div id=window_object_new class=window>
					<div class=_head>
						Создать новый объект
					</div>
					<div class=_body>						
						<div class="_form _form_last">
							<div class=_head>
								Объект					
							</div>
							<div class=_body>
								<div class=_element>
									<div class=_head>
										Текст:						
									</div>
									<div class=_body>
										<textarea name=object_text type=text placeholder="Ms. Smith"></textarea>
									</div>
								</div>
								<div class=_element>
									<div class=_head>
										Тип:						
									</div>
									<div class=_body>
										<input name=object_type type=text placeholder="person">									
									</div>
								</div>
								<div class=_element>
									<div class=_head>
										Цвет:						
									</div>
									<div class=_body>
										<input name=object_color type=text placeholder="#XXXXXX">									
									</div>
								</div>
							</div>
						</div>						
						<div class=_actions>
							<button class=_button_ok>ОК</button><button class=_button_cancel>Отмена</button>
						</div>
					</div>
				</div>
				<div id="window_object_connect_with_new_object" class="window">
					<div class="_head">
						Connect with a new object
					</div>
					<div class="_body">
						<div class="_form">
							<div class="_head">
								Connection
							</div>
							<div class="_body">
								<div class="_element">
									<div class="_head">
										Text:						
									</div>
									<div class=_body>
										<input name=connection_text type=text placeholder="mother">									
									</div>
								</div>
								<div class=_element>
									<div class=_head>
										Text for the opposite direction:
									</div>
									<div class=_body>
										<input name=connection_opposite_text type=text placeholder="child">									
									</div>
								</div>								
							</div>
						</div>
						<div class="_form _form_last">
							<div class=_head>
								Object						
							</div>
							<div class=_body>
								<div class=_element>
									<div class=_head>
										Text:						
									</div>
									<div class=_body>
										<textarea name=object_text type=text placeholder="Ms. Smith"></textarea>
									</div>
								</div>
								<div class=_element>
									<div class=_head>
										Type:						
									</div>
									<div class=_body>
										<input name=object_type type=text placeholder="person">									
									</div>
								</div>
								<div class=_element>
									<div class=_head>
										Color:						
									</div>
									<div class=_body>
										<input name=object_color type=text placeholder="#XXXXXX">									
									</div>
								</div>
							</div>
						</div>						
						<div class=_actions>
							<button class=_button_ok>OK</button><button class=_button_cancel>Cancel</button>
						</div>
					</div>
				</div>
				<div id=window_object_connect_with_object_from_buffer class=window>
					<div class=_head>
						Connect with an object from the the buffer
					</div>
					<div class="_body">
						<div class="_form">
							<div class="_head">
								Connection
							</div>
							<div class="_body">
								<div class="_element">
									<div class="_head">
										Text:						
									</div>
									<div class=_body>
										<input name=connection_text type=text placeholder="mother">									
									</div>
								</div>
								<div class=_element>
									<div class=_head>
										Text for the opposite direction:
									</div>
									<div class=_body>
										<input name=connection_opposite_text type=text placeholder="child">									
									</div>
								</div>								
							</div>
						</div>
						<div class="_form _form_last">
							<div class=_head>
								Object						
							</div>
							<div class=_body>
								<div class=_element>
									<div class=_head>
										Id:						
									</div>
									<div class=_body>
										<input disabled name=object_id type=text placeholder="Ms. Smith"></textarea>
									</div>
								</div>
								<div class=_element>
									<div class=_head>
										Text:						
									</div>
									<div class=_body>
										<input disabled name=object_text type=text placeholder="person">									
									</div>
								</div>
								<div class=_element>
									<div class=_head>
										Type:						
									</div>
									<div class=_body>
										<input disabled name=object_type type=text placeholder="#XXXXXX">									
									</div>
								</div>
							</div>
						</div>						
						<div class=_actions>
							<button class=_button_ok>OK</button><button class=_button_cancel>Cancel</button>
						</div>
					</div>
				</div>
				<div id=window_object_edit class=window>
					<div class=_head>
						Edit an object
					</div>
					<div class=_body>						
						<div class="_form _form_last">
							<div class=_head>
								Object						
							</div>
							<div class=_body>
								<div class=_element>
									<div class=_head>
										Text:						
									</div>
									<div class=_body>
										<textarea name=object_text type=text placeholder="Ms. Smith"></textarea>
									</div>
								</div>
								<div class=_element>
									<div class=_head>
										Type:						
									</div>
									<div class=_body>
										<input name=object_type type=text placeholder="person">									
									</div>
								</div>
								<div class=_element>
									<div class=_head>
										Color:						
									</div>
									<div class=_body>
										<input name=object_color type=text placeholder="#XXXXXX">									
									</div>
								</div>
							</div>
						</div>						
						<div class=_actions>
							<button class=_button_ok>OK</button><button class=_button_cancel>Cancel</button>
						</div>
					</div>
				</div>
				<div id=window_object_remove class=window>
					<div class=_head>
						Remove an object
					</div>
					<div class=_body>					
						<div class="_form _form_last">
							<div class=_head>
								Remove the object						
							</div>
						</div>
						<div class=_actions>
							<button class=_button_ok>OK</button><button class=_button_cancel>Cancel</button>
						</div>
					</div>
				</div>
				<div id=window_connection_edit class=window>
					<div class=_head>
						Edit a connection
					</div>
					<div class=_body>						
						<div class="_form _form_last">
							<div class=_head>
								Connection						
							</div>
							<div class=_body>
								<div class=_element>
									<div class=_head>
										Text:						
									</div>
									<div class=_body>
										<textarea name=text type=text placeholder="mother"></textarea>
									</div>
								</div>
								<div class=_element>
									<div class=_head>
										Text for the opposite direction:						
									</div>
									<div class=_body>
										<input name=opposite_text type=text placeholder="child">									
									</div>
								</div>								
							</div>
						</div>						
						<div class=_actions>
							<button class=_button_ok>OK</button><button class=_button_cancel>Cancel</button>
						</div>
					</div>
				</div>	
				<div id=window_connection_remove class=window>
					<div class=_head>
						Remove a connection
					</div>
					<div class=_body>					
						<div class="_form _form_last">
							<div class=_head>
								Remove the connection						
							</div>
						</div>
						<div class=_actions>
							<button class=_button_ok>OK</button><button class=_button_cancel>Cancel</button>
						</div>
					</div>
				</div>				
			</div>			
		
			<script>		
				var data=
				{
					objects: 
					{
						1: {id: 1, text: 'Thy Prymordial', type: 'song', color: '#22f'},
						2: {id: 2, text: 'Helloween - A Tail That wasn\'t right', type: 'song', color: '#22f'},
						3: {id: 3, text: '70%', type: 'text', color: '#000'},
						4: {id: 4, text: 'Схожесть заключается в том-то и том-то...', type: 'text', color: '#000'}
					},				
					connections:
					{
						1: {text: 'cover to', oppositeText: 'original for', fromObject: 1, to: 2},
						2: {text: 'percent', oppositeText: '', fromConnection: 1, to: 3},
						3: {text: 'description', oppositeText: '', fromConnection: 1, to: 4}
					}
				}
				
				var Branch=function(data, catId, objectId, connectionId, connectionStraightOrOpposite, parent)
				{
					var this_=this;
					
					this.parent=parent;
					this.childs=[];				
					
					if(objectId)
					{
						this.node=$(
						'\
							<div class="tree branch">\
								<div>\
									<span class="object"></span>\
								</div>\
								<div class="childs"></div>\
							</div>\
						');
															
						this.objectId=objectId;
					}
					else
					{
						var connection=data.connections[connectionId];
						if(connectionStraightOrOpposite)
						{
							var connectionText=connection.text;
							var object=data.objects[connection.to];
							this.objectId=connection.to;
						}
						else
						{
							var connectionText=connection.oppositeText;
							var object=data.objects[connection.from];
							if(connection.fromConnection)
							{
								this.node=$('<div style="display:none"></div>');
								return;
							}
							this.objectId=connection.fromObject;
						}					
						
						this.node=$(
						'\
							<div class="branch">\
								<div>\
									<span class="connection"><span></span>:</span>\
									<span class="object"></span>\
								</div>\
								<div class="childs"></div>\
							</div>\
						');
						
						this.nodeConnection=this.node.find('.connection > span');
						this.nodeConnection
						.
							text(connectionText)
						.
							click((user.id!=catId) ? false : function()
							{
								
								menu.openOrClose($(this).parent(), 
								[	
									{
										text: 'connect the connection with a new object',
										action: function()
										{
											windows.objectConnectWithNewObject.open(null, data, function(data_)
											{
												var childObjectId=arrayAvailableIndex(data.objects);
												var childConnectionId=arrayAvailableIndex(data.connections);
												data.objects[childObjectId]={id: childObjectId, text: data_.objectText.trim() || '_', type: data_.objectType, color: data_.objectColor};
												data.connections[childConnectionId]={text: data_.connectionText, oppositeText: data_.connectionOppositeText, fromObject: objectId || 0, fromConnection: connectionId || 0, to: childObjectId};												
												this_.showNewChild(childConnectionId);
												save.save(catId, data);
											});
										}
									},
									{
										text: 'edit the connection',
										action: function()
										{
											windows.connectionEdit.open(connection, function(data_)
											{
												connection.text=data_.text;
												connection.oppositeText=data_.oppositeText;												
												this_.nodeConnection.text(connectionStraightOrOpposite ? connection.text : connection.oppositeText);												
												save.save(catId, data);
											}); 
										}
									},
									{
										text: 'remove the connection',
										action: function()
										{
											windows.connectionRemove.open(connection, function(removeOrNot)
											{
												if(removeOrNot)
												{
													delete data.connections[connectionId];
													for(var i in data.connections)
													{
														if(data.connections[i].fromConnection && data.connections[i].from==connectionId)
														{
															delete data.connections[i];
														}
													}
													this_.node.remove();
													save.save(catId, data);
												}																								
											}); 
										}
									}
								])
							})
						;					
					}
					
					var object=data.objects[this.objectId];
					
					this.nodeObject=this.node.find('.object');				
					this.nodeObject
					.
						text(object.text)				
					.
						attr('title', object.type)
					.
						css('color', object.color)
					.
						click(function()
						{
							if(! objectId || user.id!=catId)
							{
								pages.catObj.go(catId, this_.objectId);
								return;
							}
							
							menu.openOrClose($(this).parent(), 
							[
								{
									text: 'connect with an object from the buffer',
									action: function()
									{
										objectActions.connectWithObjectFromBuffer(object, catId, data, function(childConnectionId)
										{																													
											this_.showNewChild(childConnectionId);
										}); 
									}
								},
								objectId 
								? 
									{
										text: 'connect with a new object',
										action: function()
										{
											objectActions.connectWithNewObject(object, catId, data, function(childConnectionId)
											{
												this_.showNewChild(childConnectionId);
											}); 
										}
									}
								:
									{
										text: 'open'
									}
								,								
								{
									text: 'put in buffer',
									action: function()
									{										
										objectActions.putInBuffer(object);
									}
								},									
								{
									text: 'edit',
									action: function()
									{
										objectActions.edit(object, catId, data, function()
										{											
											this_.nodeObject.text(object.text).css('color', object.color);
										}); 
									}
								},
								{
									text: 'remove',
									action: function()
									{
										objectActions.remove(object, catId, data, function(removeOrNot)
										{
											if(removeOrNot)
											{
												this_.node.remove();
											}
										}); 
									}
								}
							]);
						})
					;
				
					var childConnections=[];
					for(var i in data.connections)
					{
						var childConnection=data.connections[i];
						if(childConnection.fromObject===objectId || (connectionId && childConnection.fromConnection===connectionId))
						{				
							childConnections.push({connection: childConnection, connectionId: i, direction: true});
						}
						else if(childConnection.to==objectId)
						{
							childConnections.push({connection: childConnection, connectionId: i, direction: false});
						}						
					}
					childConnections=childConnections.sort(function(a, b)
					{						
						var aText=(a.direction ? a.connection.text : a.connection.oppositeText).toLowerCase();
						var bText=(b.direction ? b.connection.text : b.connection.oppositeText).toLowerCase();
						if(aText==bText)
						{
							var aObjectText=(a.direction ? data.objects[a.connection.to] : data.objects[a.connection.fromObject]).text.toLowerCase();
							var bObjectText=(b.direction ? data.objects[b.connection.to] : data.objects[b.connection.fromObject]).text.toLowerCase();
							return aObjectText>bObjectText;
						}
						return aText>bText;
					});
					
					for(var i in childConnections)
					{
						var childConnection=childConnections[i];
						if(childConnection.direction)
						{				
							var child=new Branch(data, catId, false, +childConnection.connectionId, true, this);
						}
						else
						{
							var child=new Branch(data, catId, false, +childConnection.connectionId, false, this);
						}					
						this.childs.push(child);
						this.node.find('> .childs').append(child.node);
					}
					
					this.showNewChild=function(connectionId)
					{
						var child=new Branch(data, catId, false, connectionId, true, this);
						this.childs.push(child);
						this.node.find('> .childs').append(child.node);
					}
				}
				
				var table=function(data, catId, filterType, filterText)
				{
					var types=[];
					
					for(var i in data.objects)
					{
						var object=data.objects[i];
						
						if((filterType && object.type.toLowerCase().indexOf(filterType)===-1) || (filterText && object.text.toLowerCase().indexOf(filterText)===-1)) continue;
						
						if(types[object.type])
						{
							types[object.type].objects.push(object);
						}
						else
						{
							types[object.type]={type: object.type, objects: [object]};
						}										
					}								
					
					var types_=[];
					for(var i in types)
					{
						types_.push(types[i]);
					}
					types=types_;				
					types=types.sort(function(a, b){return ((a.type.toLowerCase()>b.type.toLowerCase()) || (!a.type)) && (!!b.type);});
					
					for(var i in types)
					{
						var objects=types[i].objects;
						objects=objects.sort(function(a, b){return a.text>b.text;});
					}
					
					var tableNode=$('<table class=table cellpadding=0 cellspacing=0><tr></tr></table>');					
					var tableTrNode=tableNode.find('tr');
					tableTrNode.append('\
							<td>\
								<div class=table_type_head>\
									Типы:\
								</div>\
								<div class=table_type_objects>\
									Объекты:\
								</div>\
							</td>\
						');
					
					for(var i in types)
					{				
						var type=types[i];
						var typeNode=$(
						'\
							<td>\
								<div class=table_type_head>\
									<a></a>\
								</div>\
								<div class=table_type_objects>\
								</div>\
							</td>\
						');
						typeNode.find('.table_type_head a').text(type.type || '_');
						var typeObjectsNode=typeNode.find('.table_type_objects');
						
						for(var i in type.objects)
						{
							var object=type.objects[i];
							var objectNode=$('<div class=table_type_object><div><a onclick="return false;"></a></div></div>');
							objectNode.find('a').text(object.text).css('color', object.color).attr('title', object.type+'\n'+object.text).attr('href', url.gen({page: 'catObj', catId: catId, objId: object.id}));
							objectNode.css('position', 'relative');
							objectNode.prepend('<img height=20  style="position:absolute;right:7px;margin-top:0px" src="/img/menu2.png">');
							(function(object, objectNode)
							{
								objectNode.find('a')
								.
									click(function()
									{
											pages.catObj.go(catId, object.id);
									})
								;
								objectNode.find('img')
								.
									click(function()
									{
										menu.openOrClose
										(
											$(this).parent()
										, 
											[	
												/*{
													text: 'open',
													action: function()
													{
														objectActions.open(object, catId);													
													}
												},
												{
													text: 'open in new tab',
													action: function()
													{
														objectActions.openInNewTab(object, catId);													
													}
												},*/
												{
													text: 'добавить в буффер',
													action: function()
													{
														buffer.put(object);
													}
												},
												{
													separ: true
												},
												{
													text: 'связать с объектом из буффера',
													action: function()
													{
														objectActions.connectWithObjectFromBuffer(object, catId, data, function(childConnectionId)
														{																													
															//
														}); 
													}
												},
												{
													text: 'связать с новым объектом',
													action: function()
													{
														objectActions.connectWithNewObject(object, catId, data, function(childConnectionId)
														{
															//
														}); 
													}
												},											
												{
													text: 'изменить',
													action: function()
													{
														objectActions.edit(object, catId, data, function()
														{											
															objectNode.find('a').text(object.text).css('color', object.color);
														}); 
													}
												},
												{
													text: 'удалить',
													action: function()
													{
														objectActions.remove(object, catId, data, function(removeOrNot)
														{
															if(removeOrNot)
															{
																objectNode.remove();
															}
														}); 
													}
												}										
											]
											.concat
											(
												object.type=='song'
												?
													[														
														{
															separ: true
														},
														{
															text: 'слушать здесь через Ютуб',
															action: function()
															{
																youtube.open(object.text);
															}
														},
														{
															text: 'слушать на vk.com',
															action: function()
															{
																//window.open('https://vk.com/audios0?q='+encodeURIComponent(object.text))
																window.open('https://m.vk.com/audio?q='+encodeURIComponent(object.text));
															}
														}
													]
												:
													[]
												//
											)
										);										
										return false;
									})
								;
							})(object, objectNode);
							typeObjectsNode.append(objectNode);						
						}

						tableTrNode.append(typeNode);
					}
					
					return tableNode;
				}
				
				var objectActions=
				{
					create: function(object, catId, data, callback)
					{
						windows.objectNew.open(object, data, function(data_)
						{
							var objectId=arrayAvailableIndex(data.objects);
							data.objects[objectId]={id: objectId, text: data_.text.trim() || '_', type: data_.type, color: data_.color};								
							save.save(catId, data);
							callback(objectId);
						});
					},
					edit: function(object, catId, data, callback)
					{
						windows.objectEdit.open(object, data, function(data_)
						{
							object.text=data_.text;
							object.type=data_.type;
							object.color=data_.color;
							save.save(catId, data);
							callback();
						}); 
					},
					remove: function(object, catId, data, callback)
					{
						windows.objectRemove.open(object, function(removeOrNot)
						{
							if(removeOrNot)
							{
								delete data.objects[object.id];
								for(var i in data.connections)
								{
									if(data.connections[i].to==object.id || data.connections[i].fromObject==object.id)
									{
										delete data.connections[i];
									}
								}
								save.save(catId, data);
							}
							callback(removeOrNot);
						}); 
					},
					connectWithNewObject: function(object, catId, data, callback)
					{
						windows.objectConnectWithNewObject.open(object, data, function(data_)
						{
							var childObjectId=arrayAvailableIndex(data.objects);
							var childConnectionId=arrayAvailableIndex(data.connections);
							data.objects[childObjectId]={id: childObjectId, text: data_.objectText.trim() || '_', type: data_.objectType, color: data_.objectColor};
							data.connections[childConnectionId]={text: data_.connectionText, oppositeText: data_.connectionOppositeText, fromObject: object.id, to: childObjectId};
							save.save(catId, data);
							callback(childConnectionId);							
						}); 
					},
					connectWithObjectFromBuffer: function(object, catId, data, callback)
					{
						windows.objectConnectWithObjectFromBuffer.open(object, data, function(data_)
						{
							var childObjectId=data_.objectId;
							var childConnectionId=arrayAvailableIndex(data.connections);
							data.connections[childConnectionId]={text: data_.connectionText, oppositeText: data_.connectionOppositeText, fromObject: object.id, to: childObjectId};
							save.save(catId, data);											
							callback(childConnectionId);
						}); 
					},
					putInBuffer: function(object)
					{
						buffer.put(object);
					},
					open: function(object, catId)
					{
						pages.catObj.go(catId, object.id);
					},
					openInNewTab: function(object, catId)
					{
						window.open(url.gen({page: 'catObj', catId: catId, objId: object.id}));
					}
				}
				
				var menu=
				{
					node: null,				
					elementNode: null,
					
					init: function()
					{
						this.node=$('#menu');
					},
					
					openOrClose: function(elementNode, options)
					{						
						this.node.html('');
						for(var i in options)
						{
							(function(i)
							{
								if(options[i].separ)
								{
									menu.node.append
									(
										$('<div class=_separ></div>')												
									);
								}
								else
								{
									menu.node.append
									(
										$('<div>'+options[i].text+'</div>')
										.click(function(e)
										{
											e.stopPropagation();
											options[i].action();
											menu.close();
										})							
									);
								}								
							})(i);
						}
						var close=(this.elementNode && this.elementNode.get(0)==elementNode.get(0));
						if(this.elementNode)
						{						
							this.close();
						}
						if(! close)
						{
							$('#tree').addClass('menu_opened');						
							this.elementNode=elementNode;
							//elementNode.prepend(this.node);
							var menuHeight=menu.node.height();
							var elemPos=elementNode.position();
							var elemY=elemPos.top-$(window).scrollTop();							
							var elemHeight=elementNode.height();
							var windowHeight=$(window).height();
							var menuBottomY=windowHeight-elemY-22-menuHeight;
							var menuTopY=elemY-menuHeight;						
							menu.node.css('left', elemPos.left+'px');
							if(menuBottomY<0 && menuTopY>0)
							{
								//menu.node.css('margin-top', -menuHeight-5+'px');
								menu.node.css('top', elemPos.top-menuHeight-3+'px');
							}
							else
							{
								//menu.node.css('margin-top', '22px');
								menu.node.css('top', elemPos.top+24+'px');
							}
							menu.node.show();
							setTimeout(function()
							{
								menu.closeBind();
							},0);
						}
					},
												
					close: function()
					{
						$('#tree').removeClass('menu_opened');
						this.elementId=0;
						this.parentId=0;
						this.elementNode=null;
						this.node.hide();
						this.closeUnbind();
					},
					closeForBind: function(){menu.close();},
					closeBind: function()
					{
						$('body').click(menu.closeForBind);
					},
					closeUnbind()
					{
						$('body').unbind('click', this.closeForBind);
					}
				}	
				
				var buffer=
				{
					element: null,
					
					init: function()
					{
						var this_=this;
						this.node=$('#buffer');
						this.node.find('._clear').click(function()
						{
							this_.clear();
						});
						
						buffer.putFromCookie();				
						$(window).focus(function()
						{
							buffer.putFromCookie();
						});
					},				
					
					put: function(element)
					{
						if(! element)
						{
							this.clear();
							return;
						}
						this.element=element;						
						this.node.find('._id_value').text(element.id);
						this.node.find('._text_value').text(element.text);
						this.node.find('._type_value').text(element.type);
						this.node.show();
						$.cookie('buffer', JSON.stringify(element), {path: '/'});
						//console.log($.cookie('buffer'));
					},
					
					putFromCookie: function()
					{
						try
						{
							var element=JSON.parse($.cookie('buffer'));
						}
						catch(e)
						{
							element=false;
						}
						buffer.put(element);
					},
					
					clear: function()
					{
						this.element=null;
						this.node.hide();
						$.cookie('buffer', '', {path: '/'});
					},
					
					get: function()
					{
						return element;
					}
				}
								
				Window=function(nodeId)
				{
					var this_=this;
					this.node=$('#'+nodeId);
								
					this.node.click(function(){event.stopPropagation();});
								
					this.node.find('._button_cancel').click(function()
					{					
						this_.close();
					});		
					this.show=function()
					{					
						$('#window_container').unbind('click').click(function()
						{
							this_.close();
						});
						$('#window_container').css('display', 'flex').show();
						this.node.show();				
					}
					this.close=function()
					{					
						this.node.hide();					
						$('#window_container').hide();	
					}
					this.node.find('._button_ok').click(function()
					{							
						if(this_.ok) this_.ok();
					});
					this.extend=function(f){f.call(this);return this;};
				}
				
				windows=
				{
					alert: new Window('window_alert').extend(function()
					{
						this.open=function(params, callback)
						{															
							this.node.find('._form ._head').text(params.text);
							
							if(params.buttons=='ok') this.node.find('._button_cancel').hide(); else this.node.find('._button_cancel').show()
							
							this.show();
							
							this.ok=function()
							{							
								if(callback) callback(true);
								this.close();
							};
						}
					}),
					objectNew: new Window('window_object_new').extend(function()			
					{
						var data=false;
						var nodeType=this.node.find('[name=object_type]');
						var nodeColor=this.node.find('[name=object_color]');
						var setTypeColor=function()
						{
							nodeColor.val(nodeColor.val().trim() || dataGetTypeColor(data, nodeType.val().trim()));
						}
						$(function(){nodeType.blur(setTypeColor);});
						
						this.open=function(object, data_, callback)
						{						
							data=data_;
						
							this.node.find('[name=object_text]').val('');
							this.node.find('[name=object_type]').val('');
							this.node.find('[name=object_color]').val('');
							
							this.show();
							
							this.ok=function()
							{							
								callback(
								{
									text: $('#window_object_new textarea[name=object_text]').val(),
									type: $('#window_object_new input[name=object_type]').val(),
									color: $('#window_object_new input[name=object_color]').val(),								
								});
								this.close();
							};
						}
					}),
					objectConnectWithNewObject: new Window('window_object_connect_with_new_object').extend(function()
					{
						var data=false;
						var nodeType=this.node.find('[name=object_type]');
						var nodeColor=this.node.find('[name=object_color]');
						var nodeConnectionText=this.node.find('[name=connection_text]');
						var nodeConnectionOppositeText=this.node.find('[name=connection_opposite_text]');
						
						$(function()
						{
							nodeConnectionOppositeText.add(nodeConnectionText).focus(function()
							{
								if($(this).attr('autofilled'))
								{
									$(this).select();
									$(this).attr('autofilled', '');
								}
							});							
							nodeType.blur(function()
							{
								nodeColor.val(nodeColor.val().trim() || dataGetTypeColor(data, nodeType.val().trim()));							
								if(nodeConnectionText.attr('autofilled'))
								{
									nodeConnectionText.val(nodeType.val());
								}
							});							
						});
						
						this.open=function(object, data_, callback)
						{
							data=data_;					
													
							this.node.find('input, textarea').val('').attr('autofilled', '1');
							if(object) nodeConnectionOppositeText.val(object.type);
							nodeConnectionOppositeText.attr('autofilled', 'yes');
							
							this.show();
							this.ok=function()
							{							
								callback(
								{
									objectText: $('#window_object_connect_with_new_object textarea[name=object_text]').val(),
									objectType: $('#window_object_connect_with_new_object input[name=object_type]').val(),
									objectColor: $('#window_object_connect_with_new_object input[name=object_color]').val(),
									connectionText: $('#window_object_connect_with_new_object input[name=connection_text]').val(),
									connectionOppositeText: $('#window_object_connect_with_new_object input[name=connection_opposite_text]').val(),
								});
								this.close();
							};
						}
					}),				
					objectConnectWithObjectFromBuffer: new Window('window_object_connect_with_object_from_buffer').extend(function()
					{
						var nodeConnectionText=this.node.find('[name=connection_text]');
						var nodeConnectionOppositeText=this.node.find('[name=connection_opposite_text]');
						
						$(function()
						{
							nodeConnectionOppositeText.add(nodeConnectionText).focus(function()
							{
								if($(this).attr('autofilled'))
								{
									$(this).select();
									$(this).attr('autofilled', '');
								}
							});	
						});
						
						this.open=function(object_, data, callback)
						{															
							if(! buffer.element)
							{
								windows.alert.open({text: 'The buffer is empty!', buttons: 'ok'});
								return;
							}
							if(! data.objects[buffer.element.id])
							{
								windows.alert.open({text: 'The object from the buffer doesn\'t exist anymore!', buttons: 'ok'});
								return;
							}
							
							this.node.find('input, textarea').val('').attr('autofilled', '1');
							if(object_) nodeConnectionOppositeText.val(object_.type);							
							
							var object=data.objects[buffer.element.id];														
							this.node.find('[name=object_id]').val(object.id);
							this.node.find('[name=object_text]').val(object.text);
							this.node.find('[name=object_type]').val(object.type);														
							
							nodeConnectionText.val(object.type);
														
							this.ok=function()
							{							
								callback(
								{
									objectId: object.id,									
									connectionText: $('#window_object_connect_with_object_from_buffer input[name=connection_text]').val(),
									connectionOppositeText: $('#window_object_connect_with_object_from_buffer input[name=connection_opposite_text]').val(),
								});
								this.close();
							};
							
							this.show();														
						}
					}),
					objectEdit: new Window('window_object_edit').extend(function()			
					{
						var data=false;
						var nodeType=this.node.find('[name=object_type]');
						var nodeColor=this.node.find('[name=object_color]');
						var setTypeColor=function()
						{
							nodeColor.val(nodeColor.val().trim() || dataGetTypeColor(data, nodeType.val().trim()));
						}
						$(function(){nodeType.blur(setTypeColor);});
						
						this.open=function(object, data_, callback)
						{				
							data=data_;
							this.node.find('[name=object_text]').val(object.text);
							this.node.find('[name=object_type]').val(object.type);
							this.node.find('[name=object_color]').val(object.color);
							
							this.show();
							
							this.ok=function()
							{							
								callback(
								{
									text: $('#window_object_edit textarea[name=object_text]').val(),
									type: $('#window_object_edit input[name=object_type]').val(),
									color: $('#window_object_edit input[name=object_color]').val(),								
								});
								this.close();
							};
						}
					}),
					objectRemove: new Window('window_object_remove').extend(function()
					{
						this.open=function(object, callback)
						{															
							this.node.find('._form ._head').text('Remove the object "'+object.text+'"?');						
							
							this.show();
							
							this.ok=function()
							{							
								callback(true);
								this.close();
							};
						}
					}),
					/*connectionConnectWithNewObject: new Window('window_connection_connect_with_a_new_object').extend(function()
					{
						this.open=function(callback)
						{
							this.show();
													
							this.node.find('input, textarea').val('');
							this.ok=function()
							{							
								callback(
								{
									objectText: $('#window_object_connect_with_new_object textarea[name=object_text]').val(),
									objectType: $('#window_object_connect_with_new_object input[name=object_type]').val(),
									objectColor: $('#window_object_connect_with_new_object input[name=object_color]').val(),
									connectionText: $('#window_object_connect_with_new_object input[name=connection_text]').val(),
									connectionOppositeText: $('#window_object_connect_with_new_object input[name=connection_opposite_text]').val(),
								});
								this.close();
							};
						}
					}),			*/
					connectionEdit: new Window('window_connection_edit').extend(function()			
					{
						this.open=function(connection, callback)
						{															
							this.node.find('[name=text]').val(connection.text);
							this.node.find('[name=opposite_text]').val(connection.oppositeText);
							
							this.show();
							
							this.ok=function()
							{							
								callback(
								{
									text: this.node.find('[name=text]').val(),
									oppositeText: this.node.find('[name=opposite_text]').val()								
								});
								this.close();
							};
						}
					}),
					connectionRemove: new Window('window_connection_remove').extend(function()
					{
						this.open=function(connection, callback)
						{															
							this.node.find('._form ._head').text('Remove the connection "'+connection.text+'"?');						
							
							this.show();
							
							this.ok=function()
							{							
								callback(true);
								this.close();
							};
						}
					}),
				};							
				
				var user=<?php echo $user ? json_encode($user) : '{id: 0}'; ?>;
				
				var state=
				{
					page: false
				}
				
				var pages=
				{
					about: new (function()
					{					
						var this_=this;												
						
						$('#page_about ._my_cat_link').click(function()
						{
							pages.cat.go(user.id);
						});
						
						$('#page_about ._cats_link').click(function()
						{
							pages.cats.go();
						});
						
						this.go=function()
						{
							this.open();
						};
						
						this.open=function()
						{		
							state.page='about';
							url.set({page: 'about'});
							$('.page').hide();					
							$('#page_about').show();
						};
					})(),
					
					cats: new (function()
					{
						var this_=this;								
						
						this.go=function()
						{
							url.set({page: 'cats'});
							this.open();
						};
						
						this.open=function()
						{		
							state.page='cats';
							$('.page').hide();							
							$('#page_cats .body_body').html('');
							$('#page_cats .body_loading').show();
							$('#page_cats').show();			
							
							$.get('/ajax.php?type=cats', null, function(data)
							{
								$('#page_cats .body_loading').hide();
								for(var i in data)
								{
									var cat=data[i];
									var node=$('<div class=_cat><span></span></div>');
									(function(cat){
										node.find('span').text('Catalog '+cat.user_id).click(function()
										{
											pages.cat.go(cat.user_id);
										});
									})(cat);
									$('#page_cats .body_body').append(node);
								}
							}, 'json');
						};
					})(),
					
					cat: new (function()
					{
						var this_=this;
						var data=null;
						var catId=false;	
						var fixOnScrollNodes=false;
						
						$(function()
						{
							$('#page_catalog .object_filter form').submit(function()
							{
								filter();
								return false;
							})
							.on('reset', function()
							{
								setTimeout(function()
								{
									filter();
								}, 0);
							});
							
							$('#page_catalog .body_menu ._object_new').click(function()
							{
								objectActions.create(null, catId, data, function(objectId)
								{									
									pages.catObj.go(catId, objectId);
								}); 
							});
							
							$(window).scroll(function(e)
							{
								if(state.page=='cat')
								{									
									fixOnScroll();
								}
							});
							
							fixOnScrollNodes=$('#page_catalog ._fix_on_scroll');
						});
						
						this.go=function(catId)
						{
							url.set({page: 'cat', catId: catId});
							this.open(catId);
						}
						
						this.open=function(catId_)
						{
							state.page='cat';
							catId=catId_;
							$('.page').hide();
							fixOnScroll();
							$('#page_catalog .object_table').html('');
							$('#page_catalog .body_head').text((catId==user.id) ? 'Мой каталог' : 'Catalog '+ +catId);
							$('#page_catalog .body_loading').show();
							$('#page_catalog').show();							
							
							dataExt.get(catId, function(data_)
							{
								data=data_;
								$('#page_catalog .body_loading').hide();
								filter();
							});
						}
												
						var filter=function()
						{
							$('#page_catalog .object_table').html('');							
							var type=$('#page_catalog .object_filter_type').val();
							var text=$('#page_catalog .object_filter_text').val();
							$('#page_catalog .object_table').append(table(data, catId, type, text));
						}	
						
						var	fixOnScroll=function()
						{
							fixOnScrollNodes.css('margin-left', $('body').scrollLeft()+'px');
						}
					})(),
					
					catObj: new (function()
					{
						var this_=this;
						var catId=false;
						var objId=false;												
						
						$(function()
						{
							$('#page_catalog_object .body_menu > ._back').click(function(){pages.cat.go(catId);});
						});
						
						this.go=function(catId, objId)
						{
							url.set({page: 'catObj', catId: catId, objId: objId});
							this.open(catId, objId);
						}
						
						this.open=function(catId_, objId_)
						{				
							state.page='catObj';						
							catId=catId_;
							objId=objId_;
							$('.page').hide();
							$('#page_catalog_object .body_head').text(((catId==user.id) ? 'Мой каталог' : ('Catalog '+ +catId))+' - Object');
							$('#page_catalog_object .body_body').html('');
							$('#page_catalog_object .body_loading').show();
							$('#page_catalog_object').show();	
							dataExt.get(catId, function(data_)
							{								
								data=data_;
								$('#page_catalog_object .body_loading').hide();
								$('#page_catalog_object .body_body').append((new Branch(data, catId, objId)).node);								
							});	
						}
					})()
				}
				
				var save=new (function()
				{
					var timeout=false;
					
					this.save=function(catId, data)
					{
						clearTimeout(timeout);
						$('#save').show();
						$('#save').html('saving...');
						dataExt.set(catId, data, function()
						{
							$('#save').html('saved <!--✓-->');
							timeout=setTimeout(function(){$('#save').hide();$('#save').html('');}, 6500);
						});
					}
				})();
				
				var dataExt=
				{
					get: function(catId, callback)
					{
						if(catId==0)
						{
							callback(JSON.parse($.cookie('data_guest') || '{"objects": {}, "connections": {}}'));
							return;
						}
						else
						{
							$.get('/ajax.php', {type: 'cat', cat_id: catId}, function(data)
							{
								callback(data);
							}, 'json');
						}
					},
					set: function(catId, data, callback)
					{
						if(catId==0)
						{
							$.cookie('data_guest', JSON.stringify(data), {expires: 1000});
							callback('OK');
						}
						else
						{
							$.post('/ajax.php?type=cat_save', {cat_id: catId, data: JSON.stringify(data)}, function(res_)
							{
								if(res_=='OK')
								{
									callback('OK');
								}
								else
								{
									callback('ERROR');
								}							
							}, 'text');
						}
					}
				}
							
				var url=
				{					
					set: function(urlData)
					{
						history.pushState("", document.title=url.title(urlData), url.gen(urlData));
						
					},
					
					gen: function(urlData)
					{
						if(urlData.page=='cats')
						{
							return '/ru/cats';
						}
						else if(urlData.page=='cat')
						{
							return '/ru/cat'+urlData.catId;
						}
						else if(urlData.page=='catObj')
						{
							return '/ru/cat'+urlData.catId+'/obj'+urlData.objId;
						}
						else if(urlData.page=='about')
						{
							return '/ru/about';
						}
					},
					
					title: function(urlData)
					{
						if(urlData.page=='cats')
						{
							return 'All Catalogs | Abstract Catalog';
						}
						else if(urlData.page=='cat')
						{
							return 'Cat '+ +urlData.catId+' | Abstract Catalog';
						}
						else if(urlData.page=='catObj')
						{
							return 'Cat '+ +urlData.catId+' - Obj '+ +urlData.objId+' | Abstract Catalog';
						}
						else if(urlData.page=='about')
						{
							return 'Abstract Catalog';
						}
					},
					
					loadByUrl: function(url)
					{
						url=url.substr(3);						
						
						var m=url.substr(1).split('/');
						var mm=[];
						for(var i in m)
						{
							var mmm=m[i].match(/([^\d]+)([\d]*)/) || [];
							mm.push([mmm[1], mmm[2]]);
						}
						
						if(! mm[0] || ! mm[0][0])
						{							
							pages.about.go();
							return;
						}
						else if(mm[0][0]=='about')
						{
							var urlData={page: 'about'};
							pages.about.open();
						}
						else if(mm[0][0]=='cats')
						{
							var urlData={page: 'cats'};
							pages.cats.open();
						}
						else if(mm[0][0]=='cat')
						{
							if(mm[1] && mm[1][0]=='obj')
							{
								var urlData={page: 'catObj', catId: +mm[0][1], objId: +mm[1][1]};
							}
							else
							{
								var urlData={page: 'cat', catId: +mm[0][1]};
							}
							
							if(urlData.catId==0 && user.id)
							{
								pages.cat.go(user.id);
								return;
							}			

							if(urlData.page=='cat')
							{						
								pages.cat.open(urlData.catId);
							}
							else if(urlData.page=='catObj')
							{						
								pages.catObj.open(urlData.catId, urlData.objId);
							}
						}
						document.title=this.title(urlData);
					}					
				};
				
				window.onpopstate=function(event)
				{
					alert(1);
					url.loadByUrl(window.location.pathname);
				};

				$(function()
				{
					menu.init();
					buffer.init();
					$('#logout').click(function()
					{
						document.cookie='user_auth=';
						window.location='/?logout=1';
					});
					
					$('#window_container ._button_cancel').click(windows.cancel);									
					
					url.loadByUrl(window.location.pathname);		
					
					$('#head_menu ._about').click(function()
					{
						pages.about.go();
					});
					
					$('#head_menu ._cats').click(function()
					{
						pages.cats.go();
					});
					
					$('#head_menu ._my_cat').click(function()
					{
						pages.cat.go(user.id);
					});
				});	

				function arrayAvailableIndex(a)
				{
					var m=0;
					for(var i in a) m=i;
					return ++m;
				}
				
				function dataGetTypeColor(data, type)
				{
					var color='';
					for(var i in data.objects)
					{
						if(data.objects[i].type==type)
						{
							return data.objects[i].color;
						}
					}
				}
				
				var youtube=
				{	
					open: function(q)
					{
						$.get('https://www.googleapis.com/youtube/v3/search?type=video&part=snippet&key=AIzaSyD_vcwc1q5ozgU7THGtgRWX3B_GGnywijM', {q: q}, function(data)
						{
							/*windows.youtube()
							{
							}*/
							if(data.items && data.items[0])
							{
								var video=data.items[0].id.videoId;
								$('#youtube_ ._video iframe').attr('src', 'https://www.youtube.com/embed/'+video+'?autoplay=1');
							}
						});
					},
					
					close: function(q)
					{
					},
					
					hide: function(q)
					{
					},
					
					show: function(q)
					{
					}
				}
				$(function()
				{
					$('#youtube_ ._action_show').click(function(){$('#youtube_').removeClass('_hided');});
					$('#youtube_ ._action_hide').click(function(){$('#youtube_').addClass('_hided');});
				})
				
			</script>
		
		</div>
		<div id="ulogin_receiver_container" style="margin: 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: default; float: none; position: relative; display: none; width: 0px; height: 0px; left: 0px; top: 0px; box-sizing: content-box;"><iframe name="easyXDM_default2089_provider" id="easyXDM_default2089_provider" frameborder="0" src="https://ulogin.ru/stats.html?r=59930&amp;type=small&amp;xdm_e=http%3A%2F%2Fabscat.org&amp;xdm_c=default2089&amp;xdm_p=1" style="margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; position: absolute; left: 0px; top: 0px; overflow: hidden; width: 100%; height: 100%;"></iframe></div><div class="ulogin-dropdown" id="ul_1488011894939" style="margin: 0px; padding: 0px; outline: none; border: 5px solid rgb(102, 102, 102); border-radius: 4px; cursor: default; float: none; position: absolute; display: none; width: 128px; height: 310px; left: 0px; top: 0px; box-sizing: content-box; z-index: 9999; box-shadow: rgba(0, 0, 0, 0.137255) 0px 2px 2px 0px, rgba(0, 0, 0, 0.2) 0px 3px 1px -2px, rgba(0, 0, 0, 0.117647) 0px 1px 5px 0px;"><iframe name="easyXDM_default2090_provider" id="easyXDM_default2090_provider" frameborder="0" src="https://ulogin.ru/version/2.0/html/drop.html?id=0&amp;redirect_uri=http%3A%2F%2Fabscat.org&amp;callback=&amp;providers=facebook,twitter,google,yandex,livejournal,openid,flickr,lastfm,linkedin,liveid,soundcloud,steam,uid,webmoney,youtube,foursquare,tumblr,vimeo,instagram,wargaming&amp;fields=first_name,last_name&amp;force_fields=&amp;optional=&amp;othprov=vkontakte,odnoklassniki,mailru,facebook&amp;protocol=http&amp;host=abscat.org&amp;lang=ru&amp;verify=&amp;sort=relevant&amp;m=0&amp;icons_32=&amp;icons_16=&amp;theme=classic&amp;client=&amp;page=http%3A%2F%2Fabscat.org%2Findex_cp3.1_markers.php&amp;version=1&amp;xdm_e=http%3A%2F%2Fabscat.org&amp;xdm_c=default2090&amp;xdm_p=1" style="margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; position: relative; left: 0px; top: 0px; overflow: hidden; width: 128px; height: 310px;"></iframe><div style="margin: 0px; padding: 0px; outline: none; border: 5px solid rgb(102, 102, 102); border-radius: 0px; cursor: default; float: none; position: absolute; display: inherit; width: 41px; height: 13px; left: initial; top: 100%; box-sizing: content-box; background: rgb(0, 0, 0); right: -5px; text-align: center;"><a href="" target="_blank" style="margin: 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: default; float: none; position: relative; display: inherit; width: 41px; height: 13px; left: 0px; top: 0px; box-sizing: content-box; background: url(&quot;https://ulogin.ru/img/text.png&quot;) no-repeat;"></a></div></div>
	</body>
</html>