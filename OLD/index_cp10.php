<html>
	<head>
		<meta charset="utf-8">
		<script src="js/jquery.js">
		</script>
		<script src="//ulogin.ru/js/ulogin.js"></script>		
		<style>
			*{padding:0;margin:0;}
			body, input, textarea, table{font-family:tahoma;font-size:14px;font-weight:normal;text-decoration:none;color:#000}
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
			
				.catalog_head{display:block;margin-bottom:-1px;5px;font-weight:bold;margin-top:3px}

				.catalog_createobject_link{display:none}
				.catalog_go_back{display:none}
					
				#trees{margin-top:6px}														
					.tree{margin-top:5px}
					.tree .object{color:#000;line-height:21px;cursor:pointer;display:inline-block}
						.tree .object:hover{text-decoration:underline}
							
					.tree .childs{padding-left:20px;border-left:1px dotted #ccc}					
			
					.tree .branch{overflow:hidden;background-:#fff;margin-left:-1px;border-left:1px solid inherit}						
					
						.tree .connection{color:#999;cursor:pointer}
							.tree .connection > span{cursor:pointer}	
							.tree .connection span:hover{text-decoration:underline}
						
						.tree .branch #menu{}
					
							#menu{line-height:22px;border:1px solid #ddd;e7e7e7;display:inline-block;padding:0px 0 0px 0;background:#eee;#f5f5f5;cursor:pointer;position:absolute;margin-top:22px}
								#menu *{color:#333}
								#menu > div{padding:0px 10px 0px}
									#menu > div:first-child{padding-top:2px}
									#menu > div:last-child{padding-bottom:2px}
									#menu > div:hover{background:#e0e0e0;color:#000}							
				
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
									
				#window_object_connect_with_a_new_object{width:547px}
					#window_object_connect_with_a_new_object ._form{width:200px}
					#window_object_connect_with_a_new_object input[type=text]{width:186px}
					#window_object_connect_with_a_new_object ._form:last-child{width:300px}
					#window_object_connect_with_a_new_object textarea{width:286px}
				#window_object_edit{width:327px}
					#window_object_edit ._form{width:200px}
					#window_object_edit input[type=text]{width:186px}
					#window_object_edit ._form:last-child{width:300px}
					#window_object_edit textarea{width:286px}
				#window_object_remove{width:350px}							
				#window_connection_edit{width:327px}
					#window_connection_edit ._form{width:200px}
					#window_connection_edit input[type=text]{width:186px}
					#window_connection_edit ._form:last-child{width:300px}
					#window_connection_edit textarea{width:286px}
				#window_connection_remove{width:350px}
				
			/* logic */
			
				#-buffer, #menu{display:none}
				#window_container{display:none}
				#window_container > div{display:none}
			
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
			<div class="catalog_createobject_link __blue">
				create a new object
			</div>
			<div class="catalog_go_back __blue">
				← back to full catalog
			</div>
			
			<div id=trees>
			</div>
			
			<div id="menu">				
			</div>
			
			<div id="buffer">
				<span class="_head">buffer:</span><span class="_params"><span>id=24<span id="_id_value"></span>,</span><span>text=Mate 7, 32 Gb<span id="_text_value"></span></span></span><span class="_clear">clear</span>
			</div>
			
			<div id="window_container">
				<div id=window_object_connect_with_a_new_object class=window>
					<div class=_head>
						Connect with a new object
					</div>
					<div class=_body>
						<div class=_form>
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
					1: {text: 'Thy Prymordial', type: 'song', color: '#22f'},
					2: {text: 'Helloween - A Tail That wasn\'t right', type: 'song', color: '#22f'},
					3: {text: '70%', type: 'text', color: '#000'},
					4: {text: 'Схожесть заключается в том-то и том-то...', type: 'text', color: '#000'}
				},				
				connections:
				{
					1: {text: 'cover to', oppositeText: 'original for', fromObject: 1, to: 2},
					2: {text: 'percent', oppositeText: '', fromConnection: 1, to: 3},
					3: {text: 'description', oppositeText: '', fromConnection: 1, to: 4}
				}
			}
			
			var Branch=function(objectId, connectionId, connectionStraightOrOpposite, parent)
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
									
					var object=data.objects[objectId];	
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
						this.objectId=connection.from;
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
						click(function()
						{
							
							menu.openOrClose($(this), 
							[	
								{
									text: 'connect the connection with a new object',
									action: function()
									{
										windows.objectConnectWithNewObject.open(function(data_)
										{
											var childObjectId=arrayAvailableIndex(data.objects);
											var childConnectionId=arrayAvailableIndex(data.connections);
											data.objects[childObjectId]={text: data_.objectText.trim() || '_', type: data_.objectType, color: data_.objectColor};
											data.connections[childConnectionId]={text: data_.connectionText, oppositeText: data_.connectionOppositeText, fromObject: objectId || 0, fromConnection: connectionId || 0, to: childObjectId};
											this_.showNewChild(childConnectionId);
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
											}
											this_.node.remove();
										}); 
									}
								}
							])
						})
					;					
				}
				
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
						menu.openOrClose($(this), 
						[
							objectId 
							? 
								{
									text: 'connect with a new object',
									action: function()
									{
										windows.objectConnectWithNewObject.open(function(data_)
										{
											var childObjectId=arrayAvailableIndex(data.objects);
											var childConnectionId=arrayAvailableIndex(data.connections);
											data.objects[childObjectId]={text: data_.objectText.trim() || '_', type: data_.objectType, color: data_.objectColor};
											data.connections[childConnectionId]={text: data_.connectionText, oppositeText: data_.connectionOppositeText, fromObject: objectId || 0, fromConnection: connectionId || 0, to: childObjectId};
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
								text: 'edit',
								action: function()
								{
									windows.objectEdit.open(object, function(data_)
									{
										object.text=data_.text;
										object.type=data_.type;
										object.color=data_.color;
										this_.nodeObject.text(object.text).css('color', object.color);
									}); 
								}
							},
							{
								text: 'remove',
								action: function()
								{
									windows.objectRemove.open(object, function(removeOrNot)
									{
										if(removeOrNot)
										{
											delete data.objects[this_.objectId];
											for(var i in data.connections)
											{
												if(data.connections[i].to==objectId || data.connections[i].from==objectId)
												{
													delete data.connections[i];
												}
											}
										}
										this_.node.remove();
									}); 
								}
							}
						]);
					})
				;
				for(var i in data.connections)
				{
					var childConnection=data.connections[i];
					if(childConnection.fromObject===objectId || childConnection.fromConnection===connectionId)
					{				
						var child=new Branch(false, +i, true, this);											
					}
					else if(childConnection.to==objectId)
					{
						var child=new Branch(false, +i, false, this);
					}
					else
					{
						continue;
					}
					this.childs.push(child);
					this.node.find('> .childs').append(child.node);
				}
				
				this.showNewChild=function(connectionId)
				{
					var child=new Branch(false, connectionId, true, this);
					this.childs.push(child);
					this.node.find('> .childs').append(child.node);
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
						this.node.append
						(
							$('<div>'+options[i].text+'</div>')
							.click(options[i].action)
						);
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
					$('#window_container').show();
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
				objectConnectWithNewObject: new Window('window_object_connect_with_a_new_object').extend(function()
				{
					this.open=function(callback)
					{
						this.show();
												
						this.node.find('input, textarea').val('');
						this.ok=function()
						{							
							callback(
							{
								objectText: $('#window_object_connect_with_a_new_object textarea[name=object_text]').val(),
								objectType: $('#window_object_connect_with_a_new_object input[name=object_type]').val(),
								objectColor: $('#window_object_connect_with_a_new_object input[name=object_color]').val(),
								connectionText: $('#window_object_connect_with_a_new_object input[name=connection_text]').val(),
								connectionOppositeText: $('#window_object_connect_with_a_new_object input[name=connection_opposite_text]').val(),
							});
							this.close();
						};
					}
				}),				
				objectEdit: new Window('window_object_edit').extend(function()			
				{
					this.open=function(object, callback)
					{															
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
								objectText: $('#window_object_connect_with_a_new_object textarea[name=object_text]').val(),
								objectType: $('#window_object_connect_with_a_new_object input[name=object_type]').val(),
								objectColor: $('#window_object_connect_with_a_new_object input[name=object_color]').val(),
								connectionText: $('#window_object_connect_with_a_new_object input[name=connection_text]').val(),
								connectionOppositeText: $('#window_object_connect_with_a_new_object input[name=connection_opposite_text]').val(),
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

			$(function()
			{
				menu.init();
				buffer.init();
				$('#window_container ._button_cancel').click(windows.cancel);
				$('#trees').append((new Branch(1)).node);				
			});	

			function arrayAvailableIndex(a)
			{
				for(var i in a) var m=i;
				return ++m;
			}
		</script>
	
		</div><div id="ulogin_receiver_container" style="margin: 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: default; float: none; position: relative; display: none; width: 0px; height: 0px; left: 0px; top: 0px; box-sizing: content-box;"><iframe name="easyXDM_default2089_provider" id="easyXDM_default2089_provider" frameborder="0" src="https://ulogin.ru/stats.html?r=59930&amp;type=small&amp;xdm_e=http%3A%2F%2Fcatalog.thelv.ru&amp;xdm_c=default2089&amp;xdm_p=1" style="margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; position: absolute; left: 0px; top: 0px; overflow: hidden; width: 100%; height: 100%;"></iframe></div><div class="ulogin-dropdown" id="ul_1488011894939" style="margin: 0px; padding: 0px; outline: none; border: 5px solid rgb(102, 102, 102); border-radius: 4px; cursor: default; float: none; position: absolute; display: none; width: 128px; height: 310px; left: 0px; top: 0px; box-sizing: content-box; z-index: 9999; box-shadow: rgba(0, 0, 0, 0.137255) 0px 2px 2px 0px, rgba(0, 0, 0, 0.2) 0px 3px 1px -2px, rgba(0, 0, 0, 0.117647) 0px 1px 5px 0px;"><iframe name="easyXDM_default2090_provider" id="easyXDM_default2090_provider" frameborder="0" src="https://ulogin.ru/version/2.0/html/drop.html?id=0&amp;redirect_uri=http%3A%2F%2Fcatalog.thelv.ru&amp;callback=&amp;providers=facebook,twitter,google,yandex,livejournal,openid,flickr,lastfm,linkedin,liveid,soundcloud,steam,uid,webmoney,youtube,foursquare,tumblr,vimeo,instagram,wargaming&amp;fields=first_name,last_name&amp;force_fields=&amp;optional=&amp;othprov=vkontakte,odnoklassniki,mailru,facebook&amp;protocol=http&amp;host=catalog.thelv.ru&amp;lang=ru&amp;verify=&amp;sort=relevant&amp;m=0&amp;icons_32=&amp;icons_16=&amp;theme=classic&amp;client=&amp;page=http%3A%2F%2Fcatalog.thelv.ru%2Findex_cp3.1_markers.php&amp;version=1&amp;xdm_e=http%3A%2F%2Fcatalog.thelv.ru&amp;xdm_c=default2090&amp;xdm_p=1" style="margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; position: relative; left: 0px; top: 0px; overflow: hidden; width: 128px; height: 310px;"></iframe><div style="margin: 0px; padding: 0px; outline: none; border: 5px solid rgb(102, 102, 102); border-radius: 0px; cursor: default; float: none; position: absolute; display: inherit; width: 41px; height: 13px; left: initial; top: 100%; box-sizing: content-box; background: rgb(0, 0, 0); right: -5px; text-align: center;"><a href="" target="_blank" style="margin: 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: default; float: none; position: relative; display: inherit; width: 41px; height: 13px; left: 0px; top: 0px; box-sizing: content-box; background: url(&quot;https://ulogin.ru/img/text.png&quot;) no-repeat;"></a></div></div>
	</body>
</html>