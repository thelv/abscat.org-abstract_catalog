<html><head>
		<meta charset="utf-8">
		<script src="js/jquery.js">
		</script>
		<script src="//ulogin.ru/js/ulogin.js"></script>		
		<style>
			*{padding:0;margin:0;}
			body, input, table{font-family:tahoma;font-size:14px;font-weight:normal;text-decoration:none;color:#000}
			.__blue{color:#22f;cursor:pointer}
				.__blue:hover{text-decoration:underline}
			
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
			
				.catalog_head{display:block;margin-bottom:5px;5px;font-weight:bold}

				.catalog_create_object_link{display:none}
				.catalog_go_back{display:none}
					
				#trees{margin-top:10px}														
					.tree{margin-top:5px}
					.tree ._object{color:#000;line-height:20px;cursor:pointer;display:inline-block}
						.tree ._object:hover{text-decoration:underline}
							
					.tree ._branchs{padding-left:20px;border-left:1px dotted #ccc}					
						.tree > ._branch > .branchs{border-left-:0}
			
					.tree ._branch{overflow:hidden;background-:#fff;margin-left:-1px;border-left:1px solid inherit}						
					
						.tree ._connection{color:#999}
						
						.tree ._branch #menu{}
					
							#menu{line-height:22px;border:1px solid #ddd;e7e7e7;display:inline-block;padding:0px 0 0px 0;background:#eee;#f5f5f5;cursor:pointer;position:absolute;margin-top:22px}
								#menu *{color:#333}
								#menu > div > div{padding:0px 10px 0px}
									#menu > div > div:first-child{padding-top:2px}
									#menu > div > div:last-child{padding-bottom:2px}
									#menu > div > div:hover{background:#e0e0e0;color:#000}							
				
				#buffer{padding:5px 10px;position:fixed;bottom:0;left:0;background:rgba(220, 220, 220, 0.94)}
					#buffer > ._head{font-weight:bold;margin-right:5px}
					#buffer > ._params{margin-right-:5px}
						#buffer > ._params > span{margin-right:5px}
					#buffer > ._clear{color:#22f;cursor:pointer;margin-left:2px}
						#buffer > ._clear:hover{text-decoration:underline;}
						
			#window_container{display-:none;position:fixed;left:0;top:0;background:rgba(0,0,0,0.5);width:100%;height:100%}
				.window{margin: 100px auto;background:#fff;border:1px solid #ccc;width: 1152px;}
					.window > ._head{font-size:15px;font-weight-:bold;padding:5px 0;border-bottom:1px solid #ddd;background:#eee;text-indent:24px;10px}
					.window > ._body{padding:0px 10px;margin-top:6px;overflow:hidden}
						.window ._form{padding-right:20px;display:inline-block;vertical-align:top}
						.window ._form_last{padding-right:0}
							.window ._form > ._head{margin-bottom:10px;padding-left:14px;font-weight:bold}
							.window ._form > ._body{}
								.window ._form  ._element{margin-bottom:10px;vertical-align:-top;padding-left:12px;background: url(/img/list3.png) no-repeat 0px 6px;margin-left:-2px; padding-left:16px;}
									.window ._form ._element > ._head{margin-bottom:5px}
									.window ._form ._element > ._body{}
									.window ._form ._element > ._help{}
					.window ._actions{margin:15px 0 10px;text-align:right;clear:both;}
						.window ._actions button{padding: 3px 9px;margin-right:7px;}
						.window ._actions button:last-child{margin-right:0px;}
						.window ._actions ._button_ok{margin-right:7px;padding:3px 12px}
									
				#window_connect_with_a_new_element{width:447px}
					#window_connect_with_a_new_element ._form{width:200px}
					#window_connect_with_a_new_element input[type=text]{width:186px;text-indent:2px}
				
			/* logic */
			
				#-buffer, #menu{display:none}
			
			/* ! logic */
		</style>
	</head>
	<body>
		<div id="head"><div>
			<h1>
				Abstract Catalog
			</h1>
			<div id="head_menu">			
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
			</div>		
			<div id="profile">				
								
					login: <div id="uLogin" data-ulogin="display=small;theme=classic;fields=first_name,last_name;providers=vkontakte,odnoklassniki,mailru,facebook;hidden=other;redirect_uri=http%3A%2F%2Fcatalog.thelv.ru;mobilebuttons=0;" data-ulogin-inited="1488011826525" style="position: relative;"><div class="ulogin-buttons-container" style="margin: 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: default; float: none; position: relative; display: inline-block; width: 105px; height: 16px; left: 0px; top: 0px; box-sizing: content-box; max-width: 100%; min-height: 16px; vertical-align: top; line-height: 0;"><div class="ulogin-button-googleplus" data-uloginbutton="googleplus" role="button" title="Google+" style="margin: 0px 5px 5px 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: pointer; float: left; position: relative; display: inherit; width: 16px; height: 16px; left: 0px; top: 0px; box-sizing: content-box; background: url(&quot;https://ulogin.ru/version/2.0/img/providers-16-classic.png?version=img.2.0.0&quot;) 0px -358px / 16px no-repeat;"></div><div class="ulogin-button-mailru" data-uloginbutton="mailru" role="button" title="Mail.ru" style="margin: 0px 5px 5px 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: pointer; float: left; position: relative; display: inherit; width: 16px; height: 16px; left: 0px; top: 0px; box-sizing: content-box; background: url(&quot;https://ulogin.ru/version/2.0/img/providers-16-classic.png?version=img.2.0.0&quot;) 0px -52px / 16px no-repeat;"></div><div class="ulogin-button-odnoklassniki" data-uloginbutton="odnoklassniki" role="button" title="Odnoklassniki" style="margin: 0px 5px 5px 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: pointer; float: left; position: relative; display: inherit; width: 16px; height: 16px; left: 0px; top: 0px; box-sizing: content-box; background: url(&quot;https://ulogin.ru/version/2.0/img/providers-16-classic.png?version=img.2.0.0&quot;) 0px -35px / 16px no-repeat;"></div><div class="ulogin-button-vkontakte" data-uloginbutton="vkontakte" role="button" title="VK" style="margin: 0px 5px 5px 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: pointer; float: left; position: relative; display: inherit; width: 16px; height: 16px; left: 0px; top: 0px; box-sizing: content-box; background: url(&quot;https://ulogin.ru/version/2.0/img/providers-16-classic.png?version=img.2.0.0&quot;) 0px -18px / 16px no-repeat;"></div><img class="ulogin-dropdown-button" src="https://ulogin.ru/img/blank.gif" style="margin: 0px 5px 5px 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: pointer; float: none; position: relative; display: inline; width: 16px; height: 16px; left: 0px; top: 0px; box-sizing: content-box; background: url(&quot;https://ulogin.ru/version/2.0/img/providers-16-classic.png?version=img.2.0.0&quot;) 0px -1px / 16px no-repeat; vertical-align: baseline;"></div><img src="https://ulogin.ru/img/link.png" style="margin: 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: default; float: none; position: absolute; display: none; width: 8px; height: 4px; left: 0px; top: 0px; box-sizing: content-box; z-index: 9999;"></div>
			
							</div>
		</div></div>	
		
		<div id="body">							
			<div class="catalog_head">
				My catalog
			</div>
			<div class="catalog_create_object_link __blue">
				create a new object
			</div>
			<div class="catalog_go_back __blue">
				← back to full catalog
			</div>
			
			<div id=trees>
			</div>
			
			<div id="menu">
				<div class="menu_root">
					<div>
						go to this object page
					</div>
					<div>
						read the whole text
					</div>
					<div onclick="windows.open('connect_with_a_new_element', menu.callback)">
						connect with a new object
					</div>
					<div>
						connect with an object from the buffer
					</div>
					<div>
						put in the buffer
					</div>
					<div>
						change
					</div>
					<div>
						remove
					</div>					
				</div>
				<div class="menu_connection">
					<div>
						go to this object page
					</div>
					<div>
						read the whole text
					</div>
					<div>
						modify the connection with an above element
					</div>
					<div>
						remove the connection with an above element
					</div>
					<div>
						connect this connection with a new object
					</div>
					<div>
						connect this connection with an object from the buffer
					</div>
					<div>
						put in the buffer
					</div>
					<div>
						change
					</div>
					<div>
						remove
					</div>
				</div>
			</div>
			
			<div id="buffer">
				<span class="_head">buffer:</span><span class="_params"><span>id=24<span id="_id_value"></span>,</span><span>text=Mate 7, 32 Gb<span id="_text_value"></span></span></span><span class="_clear">clear</span>
			</div>
			
			<div id="window_container">
				<div id=window_connect_with_a_new_element class=window>
					<div class=_head>
						Connect with a new object
					</div>
					<div class=_body>
						<div class=_form>
							<div class=_head>
								Object						
							</div>
							<div class=_body>
								<div class=_element>
									<div class=_head>
										Text:						
									</div>
									<div class=_body>
										<input name=object_text type=text placeholder="Ms. Smith">									
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
						<div class=_actions>
							<button class=_button_ok>OK</button><button class=_button_cancel>Cancel</button>
						</div>
					</div>
				</div>
			</div>
		</div>				
		
		<script>		
			var data=
			{
				objects: 
				{
					1: {text: 'Thy Prymordial', type: 'song', color: '#22f'},
					2: {text: 'Helloween - A Tail That wasn\'t right', type: 'song', color: '#22f'},
					3: {text: '70%', type: 'text', color: '#000'},
					4: {text: 'Схожесть заключается в том-то и том-то...', type: 'text', color: '#000'}
				},				
				connections:
				{
					1: {text: 'cover to', oppositeText: 'original for', 'from': 1, to: 2, fromObjectOrConnection: true},
					2: {text: 'percent', oppositeText: '', 'from': 1, to: 3, fromObjectOrConnection: false},
					3: {text: 'description', oppositeText: '', 'from': 1, to: 4, fromObjectOrConnection: false}
				}
			}
			
			var Tree=function(objectId)
			{				
				this.node=$(
				'\
					<div class=tree>\
						<div class="_root">\
							<span class="_object"></span>\
						</div>\
					</div>\
				');
				
				var object=data.objects[objectId];
				
				this.node.find('._object')
				.
					text(object.text)				
				.
					attr('title', object.type)
				.
					css('color', object.color)
				.
					click(function()
					{
							menu.openOrClose($(this), 'root', objectId);
					})
				;
				
				this.node.append((new Branchs(objectId, true)).node);
			}
			
				var Branchs=function(parentId, objectOrConnection)
				{
					this.node=$('<div class=_branchs></div>');
					for(var i in data.connections)
					{
						var connectionData=data.connections[i]
						if(connectionData.from==parentId && connectionData.fromObjectOrConnection==objectOrConnection)
						{
							var connectionText=connectionData.text;
							var child=data.objects[connectionData.to];
						}
						else if(objectOrConnection && connectionData.to==parentId)
						{
							var connectionText=connectionData.oppositeText;
							var child=data.objects[connectionData.from];
						}
						else
						{
							continue;
						}
						
						var branchNode=$(
						'\
							<div class=_branch>\
								<div class=_element>\
									<span class="_connection"></span>\
									<span class="_object"></span>\
								</div>\
							</div>\
						');
						branchNode.find('._connection').text(connectionText+':');
						branchNode.find('._object')
						.
							text(child.text)						
						.
							attr('title', child.type)
						.
							css('color', child.color)					
						.
							click(function()
							{
								menu.openOrClose($(this), 'connection', i, parentId)
							})
						;
						branchNode.append(new Branchs(i, false).node);
						this.node.append(branchNode);
					}
				}
			
			//!
			
			var menu=
			{
				node: null,
				elementId: 0,
				parentId: 0,
				callback: null,
				elementNode: null,
				
				init: function()
				{
					this.node=$('#menu');
				},
				
				openOrClose: function(elementNode, type, elementId, parentId, callback)
				{	
					this.node.find('> div').hide();
					this.node.find('.menu_'+type).show();
					var close=(this.elementNode && this.elementNode.get(0)==elementNode.get(0));
					if(this.elementNode)
					{						
						this.close();
					}
					if(! close)
					{
						$('#tree').addClass('menu_opened');
						this.elementId=elementId;
						this.parentId=parentId;
						this.callback=callback;
						this.elementNode=elementNode;
						elementNode.prepend(this.node);
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
			
			windows=
			{
				open: function(type, params, callback)
				{	
					$('#window_container > div').hide();					
					$('#window_container').show();
					if(type=='connect_with_a_new_element')
					{
						$('#window_connect_with_a_new_element').show();
						$('#window_connect_with_a_new_element input').val('');						
						$('#window_connect_with_a_new_element ._button_cancel').click(windows.cancel);
					}
				},
				cancel: function()
				{
					$('#window_container').hide();
				}
			}

			$(function()
			{
				menu.init();
				buffer.init();
				$('#window_container ._button_cancel').click(windows.cancel);
				$('#trees').append((new Tree(1)).node);
				$('#trees').append((new Tree(1)).node);
			});			
		</script>
	

</div><div id="ulogin_receiver_container" style="margin: 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: default; float: none; position: relative; display: none; width: 0px; height: 0px; left: 0px; top: 0px; box-sizing: content-box;"><iframe name="easyXDM_default2089_provider" id="easyXDM_default2089_provider" frameborder="0" src="https://ulogin.ru/stats.html?r=59930&amp;type=small&amp;xdm_e=http%3A%2F%2Fcatalog.thelv.ru&amp;xdm_c=default2089&amp;xdm_p=1" style="margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; position: absolute; left: 0px; top: 0px; overflow: hidden; width: 100%; height: 100%;"></iframe></div><div class="ulogin-dropdown" id="ul_1488011894939" style="margin: 0px; padding: 0px; outline: none; border: 5px solid rgb(102, 102, 102); border-radius: 4px; cursor: default; float: none; position: absolute; display: none; width: 128px; height: 310px; left: 0px; top: 0px; box-sizing: content-box; z-index: 9999; box-shadow: rgba(0, 0, 0, 0.137255) 0px 2px 2px 0px, rgba(0, 0, 0, 0.2) 0px 3px 1px -2px, rgba(0, 0, 0, 0.117647) 0px 1px 5px 0px;"><iframe name="easyXDM_default2090_provider" id="easyXDM_default2090_provider" frameborder="0" src="https://ulogin.ru/version/2.0/html/drop.html?id=0&amp;redirect_uri=http%3A%2F%2Fcatalog.thelv.ru&amp;callback=&amp;providers=facebook,twitter,google,yandex,livejournal,openid,flickr,lastfm,linkedin,liveid,soundcloud,steam,uid,webmoney,youtube,foursquare,tumblr,vimeo,instagram,wargaming&amp;fields=first_name,last_name&amp;force_fields=&amp;optional=&amp;othprov=vkontakte,odnoklassniki,mailru,facebook&amp;protocol=http&amp;host=catalog.thelv.ru&amp;lang=ru&amp;verify=&amp;sort=relevant&amp;m=0&amp;icons_32=&amp;icons_16=&amp;theme=classic&amp;client=&amp;page=http%3A%2F%2Fcatalog.thelv.ru%2Findex_cp3.1_markers.php&amp;version=1&amp;xdm_e=http%3A%2F%2Fcatalog.thelv.ru&amp;xdm_c=default2090&amp;xdm_p=1" style="margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; position: relative; left: 0px; top: 0px; overflow: hidden; width: 128px; height: 310px;"></iframe><div style="margin: 0px; padding: 0px; outline: none; border: 5px solid rgb(102, 102, 102); border-radius: 0px; cursor: default; float: none; position: absolute; display: inherit; width: 41px; height: 13px; left: initial; top: 100%; box-sizing: content-box; background: rgb(0, 0, 0); right: -5px; text-align: center;"><a href="" target="_blank" style="margin: 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: default; float: none; position: relative; display: inherit; width: 41px; height: 13px; left: 0px; top: 0px; box-sizing: content-box; background: url(&quot;https://ulogin.ru/img/text.png&quot;) no-repeat;"></a></div></div></body></html>