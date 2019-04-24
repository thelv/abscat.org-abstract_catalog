<html><head>
		<meta charset="utf-8">
		<script src="js/jquery.js">
		</script>
		<script src="//ulogin.ru/js/ulogin.js"></script>		
		<style>
			i{color:#999;font-style:normal}
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
					.element{color:#000;#22f;display:inline-block;line-height:20px}
						.element span{cursor:pointer;}
						.element span{cursor:pointer;}
						.element span:hover{text-decoration:underline}					
						.element_color_black span{color:#22f;#000}
						.element_color_gray span{color:#999}
						.___{display:none}
							.element:hover .___{display:inline}
							
					.branchs{padding-left:20px;border-left:1px dotted #ccc}					
						#tree > .branch > .branchs{border-left-:0}
			
					.branch{overflow:hidden;background-:#fff;margin-left:-1px;border-left:1px solid inherit}
						.branch._colored_one{background:#f3f3fa}
						.element_menu_opened .branch._colored_one{background:transparent}
						.element_menu_opened .branch._colored_one > .element{background:#f3f3fa}
						
						.branch #element_menu{}
					
							#element_menu{line-height:22px;border:1px solid #ddd;e7e7e7;display:inline-block;padding:0px 0 0px 0;background:#eee;#f5f5f5;cursor:pointer;position:absolute;margin-top:22px}
								#element_menu *{color:#333}
								#element_menu > div{padding:0px 10px 0px}
									#element_menu > div:first-child{padding-top:2px}
									#element_menu > div:last-child{padding-bottom:2px}
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
		<div id="head"><div>
			<h1>
				Abstract Catalog<!-- ▪&nbsp; -->
			</h1>
			<div id="head_menu">
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
				<!-- <div>
					<a>
						settings
					</a>
				</div> -->				
			</div>		
			<div id="profile">				
								
					login: <div id="uLogin" data-ulogin="display=small;theme=classic;fields=first_name,last_name;providers=vkontakte,odnoklassniki,mailru,facebook;hidden=other;redirect_uri=http%3A%2F%2Fcatalog.thelv.ru;mobilebuttons=0;" data-ulogin-inited="1488011826525" style="position: relative;"><div class="ulogin-buttons-container" style="margin: 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: default; float: none; position: relative; display: inline-block; width: 105px; height: 16px; left: 0px; top: 0px; box-sizing: content-box; max-width: 100%; min-height: 16px; vertical-align: top; line-height: 0;"><div class="ulogin-button-googleplus" data-uloginbutton="googleplus" role="button" title="Google+" style="margin: 0px 5px 5px 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: pointer; float: left; position: relative; display: inherit; width: 16px; height: 16px; left: 0px; top: 0px; box-sizing: content-box; background: url(&quot;https://ulogin.ru/version/2.0/img/providers-16-classic.png?version=img.2.0.0&quot;) 0px -358px / 16px no-repeat;"></div><div class="ulogin-button-mailru" data-uloginbutton="mailru" role="button" title="Mail.ru" style="margin: 0px 5px 5px 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: pointer; float: left; position: relative; display: inherit; width: 16px; height: 16px; left: 0px; top: 0px; box-sizing: content-box; background: url(&quot;https://ulogin.ru/version/2.0/img/providers-16-classic.png?version=img.2.0.0&quot;) 0px -52px / 16px no-repeat;"></div><div class="ulogin-button-odnoklassniki" data-uloginbutton="odnoklassniki" role="button" title="Odnoklassniki" style="margin: 0px 5px 5px 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: pointer; float: left; position: relative; display: inherit; width: 16px; height: 16px; left: 0px; top: 0px; box-sizing: content-box; background: url(&quot;https://ulogin.ru/version/2.0/img/providers-16-classic.png?version=img.2.0.0&quot;) 0px -35px / 16px no-repeat;"></div><div class="ulogin-button-vkontakte" data-uloginbutton="vkontakte" role="button" title="VK" style="margin: 0px 5px 5px 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: pointer; float: left; position: relative; display: inherit; width: 16px; height: 16px; left: 0px; top: 0px; box-sizing: content-box; background: url(&quot;https://ulogin.ru/version/2.0/img/providers-16-classic.png?version=img.2.0.0&quot;) 0px -18px / 16px no-repeat;"></div><img class="ulogin-dropdown-button" src="https://ulogin.ru/img/blank.gif" style="margin: 0px 5px 5px 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: pointer; float: none; position: relative; display: inline; width: 16px; height: 16px; left: 0px; top: 0px; box-sizing: content-box; background: url(&quot;https://ulogin.ru/version/2.0/img/providers-16-classic.png?version=img.2.0.0&quot;) 0px -1px / 16px no-repeat; vertical-align: baseline;"></div><img src="https://ulogin.ru/img/link.png" style="margin: 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: default; float: none; position: absolute; display: none; width: 8px; height: 4px; left: 0px; top: 0px; box-sizing: content-box; z-index: 9999;"></div>
			
							</div>
		</div></div>	
		
		<div id="body">	
			<!-- <div id=help>
				<b>Attention:</b> your catalog is public, anyone from the Internet can see it. <a class=__blue>I understand, it's OK</a> or <a class=__blue>change privacy settings</a>.
			</div>-->
			<!-- <div id=help>
				<b>Notice:</b> you are not logged in, your catalog datas stores just in your browser memory. To keep it safe on our server please login.
			</div> -->
			
			<!-- <div id=help>
				<b style='color:red'>Warning:</b> where is some catalog you build while beeing NOT logged in. To do NOT lose it you can <a class=__blue>export it in file</a>. Or just <a class=__blue>send it to trash</a>.
			</div>-->
			
			<b style="display:block;margin-bottom:5px;5px">My catalog</b>
			<a class="__blue" style="
				margin-right: 4px;
				margin-bottom: 6px;5px;
				display: inline-block;
			">export</a>
			<a class="__blue" style="margin-right: 4px;">import
			</a>
			<a class="__blue" style="margin-right: 4px;">share
			</a>
			<a class="__blue">eval script
			</a>

			
			<!-- <div id=help style='clear:both;display:block'>
				<b>Notice:</b> catalog you build while not logged in was transfered to your account and now stores on our server. <a class=__blue>ok</a>
			</div> -->
<!-- <br>
			<div id=help >
				<b>Help:</b> to start creating your catalog click on the blue square, this is the root element of your catalog. Then add new connected element to it.
			</div> -->
			<div id="tree">
				<!-- <div id=root title=root>
					...
				</div> -->
				<!-- <div class=branch>
					<div class=element id=root title=root>
						...
					</div> 
					<div class=branchs> -->
						<div class="branch">
							<div class="element">
								<span>■</span>
							</div>
							<div class="branchs">
								<div class="branch">
									<div class="element">
										<span>songs</span>
									</div>
								</div>
								<div class="branch">
									<div class="element">
										<span>gunres</span>
									</div>
									<div class="branchs">
										<div class="branch">
											<div class="element element_color_black">
												<span>rock</span>
											</div>
											<div class="branchs">
												<div class="branch">
													<div class="element element_color_black"">
														<span><i>related genre:</i> pop</span>
													</div>
													<div class="branchs">
														<div class="branch">
															<div class="element">
																<span>related genre</span>
															</div>
														</div>
														<div class="branch">
															<div class="element element_color_black">
																<span>pop</span>
															</div>
														</div>
														<div class="branch">
															<div class="element element_color_gray">
																<span>70%</span>
															</div>
															<div class="branchs">
																<div class="branch">
																	<div class="element">
																		<span>percent</span>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="branch">
													
															<div class="element">
																<span>cover on</span>
															</div>
															<div class="branchs">
																<div class="branch">
																	<div class="element">
																		<span>cover</span>
																	</div>
																	<div class="branchs">
																		<div class="branch">
																			<div class="element">
																				<span>cover to</span>
																			</div>
																			<div class="branchs">
																				<div class="branch">
																					<div class="element element_color_black">
																						<span>Madonna</span>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="branch">
																			<div class="element element_color_gray">
																				<span>50%</span>
																			</div>
																			<div class="branchs">
																				<div class="branch">
																					<div class="element">
																						<span>similarity</span>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															
													
														<!-- <div class=branch>
															<div class="element element_color_black">
																<span>Madonna</span>
															</div>
														</div>
														<div class=branch>
															<div class="element element_color_gray">
																<span>70%</span>
															</div>
															<div class=branchs>
																<div class=branch>
																	<div class="element">
																		<span>percent</span>
																	</div>
																</div>
															</div>
														</div> -->
													</div>
												</div>
												<div class="branch">
													<div class="element">
														parent gunre
													</div>
												</div><div class="branch">
													<div class="element element_color_gray">
														  <span>genre of popular music that originated as "rock and roll" in the United States in the 1950s, and developed into a range of different styles in the 1960s and later, particularly...</span>
													</div>
													<div class="branchs">
														<div class="branch">
															<div class="element">
																description <span style="color:#000;-text-decoration:underline">.</span>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div id="class">
											<div class="element element_color_black">
												<span>house</span> <span class="___" style="color:#000;-text-decoration:underline">...</span>
											</div>
										</div>
										<div class="branch">
											<div class="element element_color_black">
												<span>metall</span> <span class="___" style="color:#000;-text-decoration:underline">...</span>
											</div>
										</div>
										<div id="class">
											<div class="element element_color_black">
												<span>electro-clash</span> <span class="___" style="color:#000;-text-decoration:underline">...</span>
											</div>
										</div>
									</div>
								</div>								
							</div>
						</div>
					<!-- </div>
				</div> -->
			</div>
			
			<div id="element_menu">
				<div>
					show in the root position
				</div>
				<div>
					read the whole text
				</div>
				<div onclick="$('#body_hide').show()">
					create a new element and connect with it
				</div>
				<div>
					connect with an element from the buffer
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
				<div>
					modify the connection with an above element
				</div>
				<div>
					remove the connection with an above element
				</div>
			</div>
			
			<div id="buffer">
				<span class="_head">buffer:</span><span class="_params"><span>id=24<span id="_id_value"></span>,</span><span>text=Mate 7, 32 Gb<span id="_text_value"></span></span></span><span class="_clear">clear</span>
			</div>
		</div>		
		
		<div id="body_hide" onclick="$(this).hide();" style="display-:none;position:fixed;left:0;top:0;background:rgba(0,0,0,0.5);width:100%;height:100%">
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
				.window input[type=radio]{vertical-align:-10%;margin-right:3px}
				.window button{padding: 3px 9px}
				table td{width:364px}
				table tr{vertical-align:top}
			</style>
			<div onclick="event.stopPropagation();" class="window" style="margin: 100px auto;background:#fff;border:1px solid #ccc;width: 1152px;">
					<div id="_head" style="font-size:15px;font-weight-:bold;padding:5px 0;border-bottom:1px solid #ddd;background:#eee;text-indent:24px;10px">Create a new element and connect with the "root".</div>
					<div id="_body" style="padding:0px 10px;margin-top:6px">
						<table cellpadding="0" cellspacing="0">
							<tbody><tr>
								<td style="padding-right:20px">
									<div style="margin-bottom:10px;padding-left:14px">
										<b style="text-decoration-:underline">Element</b>
									</div>
									<div style="margin-bottom:10px;vertical-align:top;padding-left:12px;background: url(/img/list3.png) no-repeat 0px 6px;margin-left:-2px; padding-left:16px;">
										<span style="vertical-align:top">Text: <br><textarea style="display:inline-block;margin-top:5px;width:350px"></textarea></span>
									</div>
									<div style="margin-bottom:10px;vertical-align:10%;padding-left:12px;background: url(/img/list3.png) no-repeat 0px 6px;margin-left:-2px; padding-left:16px;">
										<span style="vertical-align:top">Color: </span>
										 <!-- <div style='margin-bottom:7px'></div>
										 <span style='vertical-align:10%'> -->
											<input type="radio">blue<input type="radio" style="margin-left:7px">black<input style="margin-left:7px" type="radio">gray<input type="radio" style="margin-left:7px"><input style="width:65px" placeholder="#XXXXXX">											
										
										<span style="display:block;margin-top:5px;color:gray">Black recommended for groups and tags, gray for param values and texts, blue for the meaningful objects (like the song or the book).</span>
									</div>					
								</td>							
								<td style="
    padding-right: 20px;
">
									<div style="margin-bottom:10px;padding-left:14px">
										<b style="text-decoration-:underline">Connection (in straight direction)</b>
									</div>
									<div style="margin-bottom:10px;vertical-align:top;padding-left:12px;background: url(/img/list3.png) no-repeat 0px 6px;margin-left:-2px; padding-left:16px;">
										<span style="vertical-align:top">Order priority (bigger - higher, 0 - always the lowest): <br>
										<input style="display:inline-block;margin-top:5px;width:60px"></span>
									</div>
									<div style="margin-bottom:10px;padding-left:12px;background: url(/img/list3.png) no-repeat 0px 6px;margin-left:-2px; padding-left:16px;">
										<b style="font-weight:normal;display:block;margin-bottom:7px">Does this connection belong to a group of connections?</b>
										<input type="radio" checked=checked>No
										<div style='margin-bottom:5px'></div>
										<input type="radio">Yes, the group name: <input>										
										<span style="font-weight:normal;display:block;margin-top:5px;color:gray">An array of connections will be hided by default when showing the tree.</span>										
									</div>
									<div style="padding-left:12px;background: url(/img/list3.png) no-repeat 0px 6px;margin-left:-2px;padding-left:16px;margin-bottom: 10px;">
										<b style="font-weight:normal;display:block;margin-bottom:7px">Should this connection be hidden by default?</b>							
										<input type="radio" checked=checked>No
										<div style='margin-bottom:5px'></div>
										<input type="radio">Yes										
										<span style="display:block;margin-top:5px;color:gray">Note that all not array connections will be shown if the current element is in the root position of the tree.</span>
									</div>					
								<!--<div style="padding-left:12px;background: url(/img/list3.png) no-repeat 0px 6px;margin-left:-2px; padding-left:16px;">
										<b style="font-weight:normal;display:block;margin-bottom:7px">Is this connection the only that should be showed by default (on the same line with parent)</b>							
										<input type="checkbox">Yes, the color of child element:<div style="
    /* padding-left: 15px; */
    /* padding-top: 5px; */
    display: inline;
">
<input style="margin-left:7px" type="radio">gray<input type="radio" style="margin-left:7px"><input style="width:65px" placeholder="#XXXXXX"></div><span style="display:block;margin-top:5px;color:gray">Note that all not array connections will be shown if the current element is in the root position of the tree.</span>
									</div>--></td>
							<td style="
    /* padding-right: 29px; */
">
									<div style="margin-bottom:10px;padding-left:14px">
										<b style="text-decoration-:underline">Connection (in opposite direction)</b>
									</div>
									<div style="margin-bottom:10px;vertical-align:top;padding-left:12px;background: url(/img/list3.png) no-repeat 0px 6px;margin-left:-2px; padding-left:16px;">
										<span style="vertical-align:top">Order priority (bigger - higher, 0 - always the lowest): <br>
										<input style="display:inline-block;margin-top:5px;width:60px"></span>
									</div>
									<div style="margin-bottom:10px;padding-left:12px;background: url(/img/list3.png) no-repeat 0px 6px;margin-left:-2px; padding-left:16px;">
										<b style="font-weight:normal;display:block;margin-bottom:7px">Does this connection belong to a group of connections?</b>
										<input type="radio" checked=checked>No
										<div style='margin-bottom:5px'></div>
										<input type="radio">Yes, the group name: <input>																				
									</div>
									<div style="padding-left:12px;background: url(/img/list3.png) no-repeat 0px 6px;margin-left:-2px;padding-left:16px;margin-bottom: 10px;">
										<b style="font-weight:normal;display:block;margin-bottom:7px">Should this connection be hidden by default?</b>							
										<input type="radio" checked=checked>No
										<div style='margin-bottom:5px'></div>
										<input type="radio">Yes										
									
									</div>						
							<!--	<div style="padding-left:12px;background: url(/img/list3.png) no-repeat 0px 6px;margin-left:-2px; padding-left:16px;">
										<b style="font-weight:normal;display:block;margin-bottom:7px">Is this connection the only that should be showed by default (on the same line with parent)</b>							
										<input type="checkbox">Yes, the color of child element:<div style="
    /* padding-left: 15px; */
    /* padding-top: 5px; */
    display: inline;
">
<input style="margin-left:7px" type="radio">gray<input type="radio" style="margin-left:7px"><input style="width:65px" placeholder="#XXXXXX"></div>
									</div>--></td></tr>
						</tbody></table>
						<div style="margin:15px 0 10px;text-align:right">
							<button style="margin-right:7px;padding:3px 12px">OK</button><button>Cancel</button>
						</div>
						
					</div>
					<div id="_buttons">
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
						$('#tree').addClass('element_menu_opened');
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
					$('#tree').removeClass('element_menu_opened');
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
				$('.element').contextmenu(function()
				{
					elementMenu.openOrClose($(this), 283);
					return false;
				});
				$('.element').click(function()				
				{
						$(this).closest('.branch').children('.branchs').toggle();
				});
								
				$('.branch, .branchs, .element').mouseenter(function(e)
				{
					//e.stopPropagation();
					//branchsColored.push(this);
					//$('._colored').removeClass('_colored');
					$(this).addClass('_colored');
					//$(this).parents('.branch').addClass('_colored');
					$('._colored_one').removeClass('_colored_one');
					var a=$('._colored').last();
					if(a.hasClass('branchs') || (a.hasClass('element'))) a.parent().addClass('_colored_one');
				});
				
				$('.branch, .branchs, .element').mouseleave(function(e)
				{
					//e.stopPropagation();
					//branchsColored.push(this);
					$(this).removeClass('_colored');
					$('._colored_one').removeClass('_colored_one');
					var a=$('._colored').last();
					if(a.hasClass('branchs') || (a.hasClass('element'))) a.parent().addClass('_colored_one');
				})
				
				/*$('.element span').mouseenter(function(e)
				{
					//e.stopPropagation();
					//branchsColored.push(this);
					//$('._colored').removeClass('_colored');
					$(this).closest('.branch').addClass('_colored_one');
					//$(this).parents('.branch').addClass('_colored');
					//$('._colored_one').removeClass('_colored_one');
					//$('._colored').last().addClass('_colored_one');
				});
				
				$('.element span').mouseleave(function(e)
				{
					//e.stopPropagation();
					//branchsColored.push(this);
					$(this).closest('.branch').removeClass('_colored_one');
					//$('._colored_one').removeClass('_colored_one');
					//$('._colored').last().addClass('_colored_one');
				})*/
			});						
		</script>
	

</div><div id="ulogin_receiver_container" style="margin: 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: default; float: none; position: relative; display: none; width: 0px; height: 0px; left: 0px; top: 0px; box-sizing: content-box;"><iframe name="easyXDM_default2089_provider" id="easyXDM_default2089_provider" frameborder="0" src="https://ulogin.ru/stats.html?r=59930&amp;type=small&amp;xdm_e=http%3A%2F%2Fcatalog.thelv.ru&amp;xdm_c=default2089&amp;xdm_p=1" style="margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; position: absolute; left: 0px; top: 0px; overflow: hidden; width: 100%; height: 100%;"></iframe></div><div class="ulogin-dropdown" id="ul_1488011894939" style="margin: 0px; padding: 0px; outline: none; border: 5px solid rgb(102, 102, 102); border-radius: 4px; cursor: default; float: none; position: absolute; display: none; width: 128px; height: 310px; left: 0px; top: 0px; box-sizing: content-box; z-index: 9999; box-shadow: rgba(0, 0, 0, 0.137255) 0px 2px 2px 0px, rgba(0, 0, 0, 0.2) 0px 3px 1px -2px, rgba(0, 0, 0, 0.117647) 0px 1px 5px 0px;"><iframe name="easyXDM_default2090_provider" id="easyXDM_default2090_provider" frameborder="0" src="https://ulogin.ru/version/2.0/html/drop.html?id=0&amp;redirect_uri=http%3A%2F%2Fcatalog.thelv.ru&amp;callback=&amp;providers=facebook,twitter,google,yandex,livejournal,openid,flickr,lastfm,linkedin,liveid,soundcloud,steam,uid,webmoney,youtube,foursquare,tumblr,vimeo,instagram,wargaming&amp;fields=first_name,last_name&amp;force_fields=&amp;optional=&amp;othprov=vkontakte,odnoklassniki,mailru,facebook&amp;protocol=http&amp;host=catalog.thelv.ru&amp;lang=ru&amp;verify=&amp;sort=relevant&amp;m=0&amp;icons_32=&amp;icons_16=&amp;theme=classic&amp;client=&amp;page=http%3A%2F%2Fcatalog.thelv.ru%2Findex_cp3.1_markers.php&amp;version=1&amp;xdm_e=http%3A%2F%2Fcatalog.thelv.ru&amp;xdm_c=default2090&amp;xdm_p=1" style="margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; position: relative; left: 0px; top: 0px; overflow: hidden; width: 128px; height: 310px;"></iframe><div style="margin: 0px; padding: 0px; outline: none; border: 5px solid rgb(102, 102, 102); border-radius: 0px; cursor: default; float: none; position: absolute; display: inherit; width: 41px; height: 13px; left: initial; top: 100%; box-sizing: content-box; background: rgb(0, 0, 0); right: -5px; text-align: center;"><a href="" target="_blank" style="margin: 0px; padding: 0px; outline: none; border: none; border-radius: 0px; cursor: default; float: none; position: relative; display: inherit; width: 41px; height: 13px; left: 0px; top: 0px; box-sizing: content-box; background: url(&quot;https://ulogin.ru/img/text.png&quot;) no-repeat;"></a></div></div></body></html>