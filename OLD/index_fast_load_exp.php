<?php

	//ini_set('display_errors', 1);	

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
	
	$cache=true;
?>
<html>
	<head>
		<meta charset="utf-8">
		<meta name=viewport content='width=700px'>
		<title>Abstract Catalog</title>				
		<link rel="stylesheet" type="text/css" href="/css/main.css?3<?php echo ! $cache ? rand(1,1000000) : ''; ?>" />
		<style id="css_head"></style>
		<script>		
			var user=<?php echo $user ? json_encode($user) : '{id: 0}'; ?>;
			function loadScript(url, callback, onBodyLoad) 
			{
				var s=document.createElement('script');
				s.src=url;
				if(onBodyLoad) window.bodyOnLoad=function(){document.head.appendChild(s);}; else
				document.head.appendChild(s);
				if(callback) s.onload=callback;
			}
			
			document.addEventListener('DOMContentLoaded', function() 
			{
				bodyOnLoad();
			}, false);
		
			//setTimeout(function()
			//{
				loadScript("/js/jquery_with_cookie.js?3", function()
				{
					loadScript("/js/main.js?3<?php echo ! $cache ? rand(1,1000000) : ''; ?>");						
					<?php
		
						if(! $user)
						{
							
					?>
							$(function()
							{
								$.getScript('//ulogin.ru/js/ulogin.js', function()
								{
									$('body').append('<div id="ulogin_receiver_container" style="margin: 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: default; float: none; position: relative; display: none; width: 0px; height: 0px; left: 0px; top: 0px; box-sizing: content-box;"><iframe name="easyXDM_default2089_provider" id="easyXDM_default2089_provider" frameborder="0" src="https://ulogin.ru/stats.html?r=59930&amp;type=small&amp;xdm_e=http%3A%2F%2Fabscat.org<?php echo urlencode($_SERVER['REQUEST_URI']); ?>&amp;xdm_c=default2089&amp;xdm_p=1" style="margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; position: absolute; left: 0px; top: 0px; overflow: hidden; width: 100%; height: 100%;"></iframe></div><div class="ulogin-dropdown" id="ul_1488011894939" style="margin: 0px; padding: 0px; outline: none; border: 5px solid rgb(102, 102, 102); border-radius: 4px; cursor: default; float: none; position: absolute; display: none; width: 128px; height: 310px; left: 0px; top: 0px; box-sizing: content-box; z-index: 9999; box-shadow: rgba(0, 0, 0, 0.137255) 0px 2px 2px 0px, rgba(0, 0, 0, 0.2) 0px 3px 1px -2px, rgba(0, 0, 0, 0.117647) 0px 1px 5px 0px;"><iframe name="easyXDM_default2090_provider" id="easyXDM_default2090_provider" frameborder="0" src="https://ulogin.ru/version/2.0/html/drop.html?id=0&amp;redirect_uri=http%3A%2F%2Fabscat.org<?php echo urlencode($_SERVER['REQUEST_URI']); ?>&amp;callback=&amp;providers=facebook,twitter,google,yandex,livejournal,openid,flickr,lastfm,linkedin,liveid,soundcloud,steam,uid,webmoney,youtube,foursquare,tumblr,vimeo,instagram,wargaming&amp;fields=first_name,last_name&amp;force_fields=&amp;optional=&amp;othprov=vkontakte,odnoklassniki,mailru,facebook&amp;protocol=http&amp;host=abscat.org<?php echo urlencode($_SERVER['REQUEST_URI']); ?>&amp;lang=ru&amp;verify=&amp;sort=relevant&amp;m=0&amp;icons_32=&amp;icons_16=&amp;theme=classic&amp;client=&amp;page=http%3A%2F%2Fabscat.org%2Findex_cp3.1_markers.php&amp;version=1&amp;xdm_e=http%3A%2F%2Fabscat.org&amp;xdm_c=default2090&amp;xdm_p=1" style="margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; position: relative; left: 0px; top: 0px; overflow: hidden; width: 128px; height: 310px;"></iframe><div style="margin: 0px; padding: 0px; outline: none; border: 5px solid rgb(102, 102, 102); border-radius: 0px; cursor: default; float: none; position: absolute; display: inherit; width: 41px; height: 13px; left: initial; top: 100%; box-sizing: content-box; background: rgb(0, 0, 0); right: -5px; text-align: center;"><a href="" target="_blank" style="margin: 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: default; float: none; position: relative; display: inherit; width: 41px; height: 13px; left: 0px; top: 0px; box-sizing: content-box; background: url(&quot;https://ulogin.ru/img/text.png&quot;) no-repeat;"></a></div></div>');
								});
							});											
					<?php
					
						}
						
					?>
						
						
				}, true)
			//}, 0);
		</script>
	</head>
	<body onload="bodyOnLoad()">
		<div id="head"><div><div class=head_background id=head_background1></div><div class=head_background id=head_background2></div>
			<h1>
				$@{Abstract Catalog}
			</h1>
			<div id="head_menu">			
				<div>
					<a class="_about">
						$@{about project}
					</a>
				</div>
				<div class="_cats">
					<a>
						$@{all catalogs}
					</a>					
				</div>
				<div>
					<a class="_my_cat">
						$@{my catalog}
					</a>
				</div>		
			</div>		
			<div id="profile">				
					<!--
				 --><?php
					
						if(! $user)
						{
							
					?><!--
					
						-->$@{login}: <!--
						--><div id="uLogin" data-ulogin="display=small;theme=classic;fields=first_name,last_name,photo;providers=vkontakte,odnoklassniki,mailru,facebook;hidden=other;redirect_uri=http%3A%2F%2Fabscat.org<?php echo urlencode($_SERVER['REQUEST_URI']); ?>;mobilebuttons=0;"><span style='color:#888'>...</span></div>
					<?
					
						}else{
							
					?><!--
						
						--><span id=profile_header>$@{User}:</span><span class=_name><?= $user['first_name'].' '.$user['last_name'] ?></span><a id=logout>$@{logout}</a>
						
					<?
					
						}
						
					?>
					
			</div>
		</div></div>	
		
		<div id="body">
			<div id="initial_loading">
				<div></div>
				<img src="/img/loading.gif">
			</div>
			<div id="page_about" class="page">
			<div class="body_head">
					$@{About Project}
				</div>
				<div class="body_body">
					$@{You can create} <a class="_my_cat_link _link">$@{your own catalog}</a> $@{or discover other people} <a class="_cats_link _link">$@{catalogs}</a>.
				</div>				
			</div>
			<div id="page_cats" class="page">
				<div class="body_head">
					$@{All Catalogs}
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
						$@{My catalog}
					</div>
					<div class="body_menu">
						<a class=_object_new>
							$@{create new object}
						</a><!--
						--><a class=_backup>
							$@{backup}
						</a>
					</div>
				</div>
				<div class="body_body">
					<!-- <div class=help>
						<b style='color:rgb(238, 68, 68);'>Warning:</b> where is some catalog you build while beeing NOT logged in. To do NOT lose it you can <a class=__blue>export it in file</a>. Or just <a class=__blue>send it to trash</a>.
					</div> -->
					<div class=_fix_on_scroll>
						<!-- <div class="object_filter">
							<form>
								Catalog view: <input type=radio checked=checked> object lists &nbsp; <input type=radio> song table  &nbsp; <input type=radio> song catalog with song counters 
							</form>
						</div> -->
						<div class="object_filter">
							<form>
								$@{Object filter: type}~ <input class=object_filter_type type=text>, $@{text}~ <input class=object_filter_text type=text>
								<input type=submit value=$@{ok}> <input type=reset value=$@{clear}>
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
					$@{My catalog} - $@{Object}
				</div>
				<div class="body_menu">
					<a class='_back'>
						← $@{back}
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
			
			<div id=comments_cont><table cellpadding=0 cellspacing=0><tr><td id=comments_cont_padding></td></tr><tr><td id=comments_cont_><div id=comments>
			
				<!-- LIKES -->
					<div id=likes>
										
						<div class="like" id="tweeter_like">
							<div id="widget">
								<div class="btn-o" data-scribe="component:button" style="width: 61px;"><a href="https://twitter.com/intent/tweet?original_referer=http%3A%2F%2Fabscat.org%2Fcat48&amp;ref_src=twsrc%5Etfw&amp;text=Cat%2048%20%7C%20Abstract%20Catalog&amp;tw_p=tweetbutton&amp;url=http%3A%2F%2Fabscat.org%2Fcat48" class="btn" id="b"><i></i><span class="label" id="l">Tweet</span></a></div>
							</div>	
						</div>

						<div class=like>
						
							<a id="telegram_like" class="telegram-share" href="javascript:window.open('https://t.me/share/url?url='+encodeURIComponent(window.location.href), '_blank')">
							  <i></i>
							  <span>Telegram</span>
							</a>
							
						</div>
												
						<div class="like" id="facebook_like">
						</div>
						
						<div class="like" id="google_plus_like">
						</div>
						
						<div  class="like" id="vk_like">							
						</div>
					</div>
					
				<!-- //LIKES -->		
			
				<!-- COMMENTS -->				

					<a id=comments_show  onclick='comments_.hide(false)'>$@{comments}</a>
				
					<div id=commentsHeader style="line-height:20px;clear:left;displ-ay:none;padding: 0px 0px 5px 0;margin-bottom: 3px;margin-right:8px;margin-top: 0px;overflow: hidden;font-weight: bold;-color: #222;"> 
						<a id=comments_hide onclick='comments_.hide(true)' style="float:right;color:#22f;font-weight: normal;-padding-right: 5px;">$@{hide}</a>
						$@{Comments}						
					</div>

					<div id=commentsComments><noindex>
					
						<div id=comments_chat_header onclick="comments_.chat.hide()">
							<span>
								Стена
							</span>
						</div>
						
						<div class=comments_chat_settings_option id=comments_chat_settings_show_all_in_common>
							<input type="checkbox" onchange="comments_.chat.settings.showAllInCommon($(this).attr('checked'))">
							отображать сообщения всех стен в общем чате
						</div>
					
						<div id=commentsAdd>
							<div class=commentsAva>
								<img src="/img/noava.gif" width="50" height="50">
							</div>
							<div class=commentsRight>
								<div class=commentsTextarea>
									<textarea></textarea>
								</div>
								<div id=comments_chat_post_to>
									<span onclick="comments_.chat.show(comments_.chatIdPostTo, comments_.chatName)">
										отправить на стену 
									</span>
									<div onclick="comments_.chat.hidePostTo()"></div>
								</div>
								<div class=commentsAddButton>
									<button onclick="comments_.post();">
										$@{Send}
									</button>
									
								</div>
							</div>
						</div>
						<div id=commentsList>
							<!-- -->
						</div>
					</noindex></div>
				<!-- //COMMENTS -->
			</div></td></td></table></div>
			
			<div id=players>
			
				<div id=player>
					<div id=player_head>
						<a class="_action_hide">$@{hide}</a>											
						<a class="_action_lock"></a>
						<div>
							<div class=_head_title>
								The Cancel - Love is
							</div>
						</div>						
					</div>
					<div id="vk">
						<div class=_head>
							<div class=_head_title>$@{vk}</div> <!--
							--><a class="_action_stop">$@{stop}</a><a class="_action_play" title="play in vk">$@{play in vk}</a><a class="_action_open_vk">$@{vk window}</a>
						</div>					
					</div>
					
					<div id="youtube_" class="_hided">
						<div class=_head>
							<div class=_head_title>$@{youtube}:&nbsp;</div><!--
							--><a class="_action_close">$@{disable}</a><a class="_action_show">$@{video}</a><a class="_action_hide">$@{hide}</a><!-- <a class="_action_stop">$@{stop}</a> -->
							<div class=_select_cont><select><option>Mage - The time Is Always Running Out</option></select></div>
						</div>
						<div class=_video>					
							<div class="_hint_video _hided_first"><!-- hover mouse to control --></div>
							<div id=youtube_iframe_cont><iframe id=youtube_iframe frameborder=0 src=''></iframe></div>
							<div class="_hint _hided_first"><!-- hover mouse to control --></div>
						</div>
					</div>
				</div>
				
				<div id="save">
				</div>
				
				<div id=player_bar>
					<a class="_action_close">$@{close}</a>
					<a class="_action_open">$@{open}</a>											
					<a class="_action_stop">$@{stop}</a>											
					<span>The Cancel - Love is</span>
				</div>											
				
			</div></div>
			
			
			
			<div id="buffer">
				<span class="_head">$@{buffer}:</span><span class="_params"><span>id=<span class="_id_value"></span>,</span><span>$@{text}="<span class="_text_value"></span>",</span><span>type="<span class="_type_value"></span>"</span></span><span class="_clear">$@{clear}</span>
			</div>
			
			<div id="menu">				
			</div>
			
			<div id="window_container">
				<div id=window_alert class=window>
					<div class=_head>
						$@{Message}
					</div>
					<div class=_body>					
						<div class="_form _form_last">
							<div class=_head>
														
							</div>
						</div>
						<div class=_actions>
							<button class=_button_ok>$@{OK}</button><button class=_button_cancel>$@{Cancel}</button>
						</div>
					</div>
				</div>
				<div id=window_object_new class=window>
					<div class=_head>
						$@{Create a new object}
					</div>
					<div class=_body>						
						<div class="_form _form_last">
							<div class=_head>
								$@{Object}
							</div>
							<div class=_body>
								<div class=_element>
									<div class=_head>
										$@{Text}:						
									</div>
									<div class=_body>
										<textarea name=object_text type=text placeholder="Ms. Smith"></textarea>
									</div>
								</div>
								<div class=_element>
									<div class=_head>
										$@{Type}:						
									</div>
									<div class=_body>
										<input name=object_type type=text placeholder="person">									
									</div>
								</div>
								<div class=_element>
									<div class=_head>
										$@{Color}:						
									</div>
									<div class=_body>
										<input name=object_color type=text placeholder="#XXXXXX">									
									</div>
								</div>
							</div>
						</div>						
						<div class=_actions>
							<button class=_button_ok>$@{OK}</button><button class=_button_cancel>$@{Cancel}</button>
						</div>
					</div>
				</div>
				<div id="window_object_connect_with_new_object" class="window">
					<div class="_head">
						$@{Connect with a new object}
					</div>
					<div class="_body">
						<div class="_form">
							<div class="_head">
								$@{Connection}
							</div>
							<div class="_body">
								<div class="_element">
									<div class="_head">
										$@{Text}:
									</div>
									<div class=_body>
										<input name=connection_text type=text placeholder="mother">									
									</div>
								</div>
								<div class=_element>
									<div class=_head>
										$@{Text for the opposite direction}:
									</div>
									<div class=_body>
										<input name=connection_opposite_text type=text placeholder="child">									
									</div>
								</div>								
							</div>
						</div>
						<div class="_form _form_last">
							<div class=_head>
								$@{Object}
							</div>
							<div class=_body>
								<div class=_element>
									<div class=_head>
										$@{Text}:
									</div>
									<div class=_body>
										<textarea name=object_text type=text placeholder="Ms. Smith"></textarea>
									</div>
								</div>
								<div class=_element>
									<div class=_head>
										$@{Type}:
									</div>
									<div class=_body>
										<input name=object_type type=text placeholder="person">									
									</div>
								</div>
								<div class=_element>
									<div class=_head>
										$@{Color}:
									</div>
									<div class=_body>
										<input name=object_color type=text placeholder="#XXXXXX">									
									</div>
								</div>
							</div>
						</div>						
						<div class=_actions>
							<button class=_button_ok>$@{OK}</button><button class=_button_cancel>$@{Cancel}</button>
						</div>
					</div>
				</div>
				<div id=window_object_connect_with_object_from_buffer class=window>
					<div class=_head>
						$@{Connect with an object from the the buffer}
					</div>
					<div class="_body">
						<div class="_form">
							<div class="_head">
								$@{Connection}
							</div>
							<div class="_body">
								<div class="_element">
									<div class="_head">
										$@{Text}:
									</div>
									<div class=_body>
										<input name=connection_text type=text placeholder="mother">									
									</div>
								</div>
								<div class=_element>
									<div class=_head>
										$@{Text for the opposite direction}:
									</div>
									<div class=_body>
										<input name=connection_opposite_text type=text placeholder="child">									
									</div>
								</div>								
							</div>
						</div>
						<div class="_form _form_last">
							<div class=_head>
								$@{Object}
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
										$@{Text}:
									</div>
									<div class=_body>
										<input disabled name=object_text type=text placeholder="person">									
									</div>
								</div>
								<div class=_element>
									<div class=_head>
										$@{Type}:
									</div>
									<div class=_body>
										<input disabled name=object_type type=text placeholder="#XXXXXX">									
									</div>
								</div>
							</div>
						</div>						
						<div class=_actions>
							<button class=_button_ok>$@{OK}</button><button class=_button_cancel>$@{Cancel}</button>
						</div>
					</div>
				</div>
				<div id=window_object_edit class=window>
					<div class=_head>
						$@{Edit an object}
					</div>
					<div class=_body>						
						<div class="_form _form_last">
							<div class=_head>
								$@{Object}
							</div>
							<div class=_body>
								<div class=_element>
									<div class=_head>
										$@{Text}:
									</div>
									<div class=_body>
										<textarea name=object_text type=text placeholder="Ms. Smith"></textarea>
									</div>
								</div>
								<div class=_element>
									<div class=_head>
										$@{Type}:
									</div>
									<div class=_body>
										<input name=object_type type=text placeholder="person">									
									</div>
								</div>
								<div class=_element>
									<div class=_head>
										$@{Color}:
									</div>
									<div class=_body>
										<input name=object_color type=text placeholder="#XXXXXX">									
									</div>
								</div>
							</div>
						</div>						
						<div class=_actions>
							<button class=_button_ok>$@{OK}</button><button class=_button_cancel>$@{Cancel}</button>
						</div>
					</div>
				</div>
				<div id=window_object_remove class=window>
					<div class=_head>
						$@{Remove an object}
					</div>
					<div class=_body>					
						<div class="_form _form_last">
							<div class=_head>
								$@{Remove the object}
							</div>
						</div>
						<div class=_actions>
							<button class=_button_ok>$@{OK}</button><button class=_button_cancel>$@{Cancel}</button>
						</div>
					</div>
				</div>
				<div id=window_connection_edit class=window>
					<div class=_head>
						$@{Edit a connection}
					</div>
					<div class=_body>						
						<div class="_form _form_last">
							<div class=_head>
								$@{Connection}
							</div>
							<div class=_body>
								<div class=_element>
									<div class=_head>
										$@{Text}:	
									</div>
									<div class=_body>
										<textarea name=text type=text placeholder="mother"></textarea>
									</div>
								</div>
								<div class=_element>
									<div class=_head>
										$@{Text for the opposite direction}:	
									</div>
									<div class=_body>
										<input name=opposite_text type=text placeholder="child">									
									</div>
								</div>								
							</div>
						</div>						
						<div class=_actions>
							<button class=_button_ok>$@{OK}</button><button class=_button_cancel>$@{Cancel}</button>
						</div>
					</div>
				</div>	
				<div id=window_connection_remove class=window>
					<div class=_head>
						$@{Remove a connection}
					</div>
					<div class=_body>					
						<div class="_form _form_last">
							<div class=_head>
								$@{Remove the connection}
							</div>
						</div>
						<div class=_actions>
							<button class=_button_ok>$@{OK}</button><button class=_button_cancel>$@{Cancel}</button>
						</div>
					</div>
				</div>				
			</div>			
		</div>
	</body>
	<!-- <script src="/js/jquery_with_cookie.js">
	</script> -->
	<!-- <script src='/js/main.js?3<?php echo ! $cache ? rand(1,1000000) : ''; ?>'>
	</script> -->
</html>