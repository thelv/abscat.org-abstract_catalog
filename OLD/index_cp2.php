<?php
	if($_POST['token'])
	{
		$s = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
		$user = json_decode($s, true);
	}
	//$user['network'] - соц. сеть, через которую авторизовался пользователь
	//$user['identity'] - уникальная строка определяющая конкретного пользователя соц. сети
	//$user['first_name'] - имя пользователя
	//$user['last_name'] - фамилия пользователя
?>
<html>
	<head>
		<meta charset=utf-8>
		<script src='js/jquery.js'>
		</script>
		<script src="//ulogin.ru/js/ulogin.js"></script>		
		<style>
			*{font-family:tahoma;font-size:14px;font-weight:normal;padding:0;margin:0;text-decoration:none;color:#000}
			.__blue{color:#22f;cursor:pointer}
				.__blue:hover{text-decoration:underline}
			b{font-weight:bold}
			
			#head{position:fixed;top:0;left:0;width:100%}
			#head > div{font-size:15px;overflow:hidden;margin:0px 0px;border-bottom:1px solid #ddd;padding:6px 10px 6px 10px;background:#eee;height:18px}
				#head *{font-size:15px;font-weight:-bold}
				h1{font-weight:bold;float:left}
				#head_menu{float:left;margin-left:10px;}
					#head_menu *{color:#000;color: 	#22f}
					#head_menu > div{margin-right:10px;float:left}
					#head_menu > div > ._slash{margin:0px 5px}
					#head_menu > div > a{cursor:pointer;text-decoration:-underline}
					#head_menu > div > a:hover{text-decoration:underline}
					
				#profile{float:right}
					#profile ._name{font-weight-:bold}
					#uLogin{float:right;margin-left:5px}
					#logout{margin-left:7px;color:#22f;cursor:pointer}
						#logout:hover{text-decoration:underline}
			
			#body{font-size:14px;overflow:hidden;margin:6px 10px;margin-top:37px;}							
					
				#help{border:1px solid #ccc;padding:5px 10px;display:inline-block;margin:4px 0 6px 0}
					
				#tree
					/*#root{float:left;margin-right:3px;cursor:pointer}
						#root:hover{text-decoration:underline}*/					
					#root{color:#000}
					.element{color:#22f;cursor:pointer;display:inline-block}
						.element:hover{text-decoration:underline}					
					.branchs{margin-left:20px}					
			
					.branch{overflow:hidden}
						.branch #element_menu{}
					
							#element_menu{border:1px solid #e7e7e7;display:inline-block;padding:0px 0 0px 0;background:#f5f5f5;cursor:pointer;position:absolute;margin-top:20px}
								#element_menu *{color:#444}
								#element_menu > div{padding:0px 10px 5px}
									#element_menu > div:first-child{padding-top:5px}
									#element_menu > div:last-child{padding-bottom:5px}
									#element_menu > div:hover{background:#e0e0e0;color:#000}
							
				
				#buffer{padding:5px 10px;position:fixed;bottom:0;left:0;background:rgba(220, 220, 220, 0.94)}
					#buffer > ._head{font-weight:bold;margin-right:5px}
					#buffer > ._params{margin-right-:5px}
						#buffer > ._params > span{margin-right:5px}
					#buffer > ._clear{color:#22f;cursor:pointer;margin-left:2px}
						#buffer > ._clear:hover{text-decoration:underline;}
			/* logic */
			
				#-buffer, #element_menu{display:none}
			
			/* ! logic */
		</style>
	</head>
	<body>
		<div id=head><div>
			<h1>
				Abstract Catalog<!-- ▪&nbsp; -->
			</h1>
			<div id=head_menu>
				<!-- <div>
					<a>
						root
					</a>
				</div> -->
				<div>
					<a>
						about project
					</a>
				</div>
				<div>
					<a>
						all catalogs
					</a>
				</div>
				<div>
					<a>
						my catalog
					</a>
				</div>
				<div>
					<a>
						settings
					</a>
				</div>
				<div>
					<!-- <a>import</a><span class=_slash>/</span> --><a>export</a>
				</div>
			</div>		
			<div id=profile>				
				<?php 
				
					if($user['first_name'])
					{
					
				?>
						
					<span class=_name><?= $user['first_name'].' '.$user['last_name'] ?></span><span id=logout>logout</span>
				
				<?php
				
					}
					else
					{
						
				?>
				
					login: <div id="uLogin" data-ulogin="display=small;theme=classic;fields=first_name,last_name;providers=vkontakte,odnoklassniki,mailru,facebook;hidden=other;redirect_uri=http%3A%2F%2Fcatalog.thelv.ru;mobilebuttons=0;"></div>
			
				<?php
					
					}
					
				?>
			</div>
		</div></div>	
		
		<div id=body>	
			<!-- <div id=help>
				<b>Attention:</b> your catalog is public, anyone from the Internet can see it. <a class=__blue>I understand, it's OK</a> or <a class=__blue>change privacy settings</a>.
			</div>-->
			<!-- <div id=help>
				<b>Notice:</b> you are not logged in, your catalog datas stores just in your browser memory. To keep it safe on our server please login.
			</div> -->
			
			<!-- <div id=help>
				<b style='color:red'>Warning:</b> where is some catalog you build while beeing NOT logged in. To do NOT lose it you can <a class=__blue>export it in file</a>. Or just <a class=__blue>send it to trash</a>.
			</div>-->
			
			<div id=help>
				<b>Notice:</b> catalog you build while not logged in was transfered to your account and now stores on our server. <a class=__blue>ok</a>
			</div>

			<!-- <div id=help>
				<b>Help:</b> to start creating your catalog click on the blue square, this is the root element of your catalog. Then add new connected element to it.
			</div> -->
			<div id=tree>
				<!-- <div id=root title=root>
					...
				</div> -->
				<!-- <div class=branch>
					<div class=element id=root title=root>
						...
					</div> 
					<div class=branchs> -->
						<div class=branch>
							<div class=element>
								■
							</div>
							<div class=branchs>
							<div class=branch>
									<div class=element>
										songs
									</div>
								</div>
								<div id=class>
									<div class=element>
										gunres
									</div>
								</div>
							</div>
						</div>
					<!-- </div>
				</div> -->
			</div>
			
			<div id=element_menu>
				<div>
					open like root
				</div>
				<div>
					add new connected element
				</div>
				<div>
					add connected element from buffer
				</div>
				<div>
					add this element to buffer
				</div>
				<div>
					rename this element
				</div>
				<div>
					remove this element
				</div>
				<div>
					change connection params to above element
				</div>
				<div>
					remove connection to above element
				</div>
			</div>
			
			<div id=buffer>
				<span class=_head>buffer:</span><span class=_params><span>id=24<span id=_id_value></span>,</span><span>text=Mate 7, 32 Gb<span id=_text_value></span></span></span><span class=_clear>clear</span>
			</div>
		</div>		
		
		<div id="body_hide" onclick="$(this).hide();" style='display-:none;position:fixed;left:0;top:0;background:rgba(0,0,0,0.5);width:100%;height:100%'>
			<!-- <div class=window style='position:absolute;left:50%;top:50%;border:1pxc solid #ccc;display:inline-block;margin:auto'>
				<div style='left:-50%;top:-50%;position:relative;background:#fff;'>
					<div id=_head>Add new connected element to "root"</div>
					<div id=_body>
					
					</div>
					<div id=_buttons>
				</div>			
			</div> -->
			<style>
				.window input[type=checkbox]{vertical-align:-10%;margin-right:3px}
				.window button{padding: 3px 9px}
			</style>
			<div onclick="event.stopPropagation();" class=window style='margin:100px auto;background:#fff;border:1px solid #ccc;width:450px;'>
					<div id=_head style='font-size:15px;font-weight-:bold;padding:5px 0;border-bottom:1px solid #ddd;background:#eee;text-indent:10px'>Add new connected element to "root".</div>
					<div id=_body style='padding:0px 10px;margin-top:6px'>
						<div style='margin-bottom:10px;vertical-align:top'>
							<span style='vertical-align:top'>Element text: <br><textarea style='display:inline-block;margin-top:5px;width:428px'></textarea></span>
						</div>
						<!-- <div style='margin-bottom:10px;vertical-align:top'>
							<span style='vertical-align:top'>Element color: 
							 <div style='margin-bottom:5px'></div>
							<input type="checkbox">blue <input type="checkbox">black <input type="checkbox">gray
						</div>						-->
						<div style='margin-bottom:10px;vertical-align:top'>
							<span style='vertical-align:top'>Connection order priority (bigger - higher, 0 - always the lowest): 
							<input style='display:inline-block;margin-top:5px;width:60px'></span>
						</div>
						<div style='margin-bottom:10px'>
							<b style='display:block;margin-bottom:7px'>Is this connection is a part of array of one type connections</b>
							<input type="checkbox">Yes (adding element is a child)
							<div style='margin-bottom:5px'></div>
							<input type="checkbox">Yes (adding element is a parent)
							<span style='display:block;margin-top:5px;color:gray'>An array of connections will be hided by default when showing the tree.</span>
						</div>
						<div>
							<b style='display:block;margin-bottom:7px'>Is this connection needs to be hidden by default</b>							
							<input type="checkbox">Yes, in straight direction (to adding element)<br>
							<div style='margin-bottom:5px'></div>
							<input type="checkbox">Yes, in opposite direction (from adding element)
							<span style='display:block;margin-top:5px;color:gray'>Note that all not array connections will be shown if the current element is in the root position of the tree.</span>
						</div>						
						<div style='margin:10px 0 10px'>
							<button style='margin-right:7px'>Add</button><button>Cancel</button>
						</div>
						
					</div>
					<div id=_buttons>
				</div>	
		</div>
		
		<script>				
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
				},								
				
				put: function(element)
				{
					this.element=element;
					
					this.node.find('#_id_value').text(element.id);
					this.node.find('#_text_value').text(element.text);
					this.node.show();
				},
				
				clear: function()
				{
					this.element=null;
					this.node.hide();
				},
				
				get: function()
				{
					return element;
				}
			}
			
			var elementMenu=
			{
				node: null,
				elementId: -1,
				elementNode: null,
				
				init: function()
				{
					this.node=$('#element_menu');
				},
				
				openOrClose: function(elementNode, elementId)
				{	
					var close=(this.elementNode && this.elementNode.get(0)==elementNode.get(0));
					if(this.elementNode)
					{						
						this.close();
					}
					if(! close)
					{
						this.elementId=elementId;
						this.elementNode=elementNode;
						elementNode.before(this.node);
						elementMenu.node.show();
						setTimeout(function()
						{
							elementMenu.closeBind();
						},0);
					}
				},
											
				close: function()
				{
					this.elementId=-1;
					this.elementNode=null;
					this.node.hide();
					this.closeUnbind();
				},
				closeForBind: function(){elementMenu.close();},
				closeBind: function()
				{
					$('body').click(elementMenu.closeForBind);
				},
				closeUnbind()
				{
					$('body').unbind('click', this.closeForBind);
				}
			}	

			$(function()
			{
				elementMenu.init();
				buffer.init();
				$('.element').click(function()
				{
					elementMenu.openOrClose($(this), 283);
				});
			});
		</script>
	</body>
</html>