var Branch=function(data, catId, objectId, connectionId, connectionStraightOrOpposite, parent)
{
	var this_=this;
	
	this.parent=parent;
	this.childs=[];		
	this.childsLoaded=false;
	this.childsShowed=false;
	
	if(objectId)
	{
		
		this.objectId=objectId;
		var object=data.objects[this.objectId];
		
		this.node=$(
		'\
			<div class="tree branch">\
				<div>\
					<div class=object>\
						<a class="object_select"><input type="checkbox"></a><span class="object_">'
						+(object.type=='song' ? '<a class=object_play></a>' : '')
						+'<a class=object_text onclick="return false;"></a>'+
						'<!-- <a class=object_menu_icon>\</a> --></span>\
					</div>\
				</div>\
				<div class="childs"></div>\
			</div>\
		');
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
		var object=data.objects[this.objectId];
		
		this.node=$(
		'\
			<div class="branch">\
				<div>\
					<span class="connection"><span class="connection_text" title="expand"></span>:</span>'+
					'<div class=object>'+
						'<a class="object_select"><input type="checkbox"></a><span class="object_">'
						+(object.type=='song' ? '<a class=object_play></a>' : '')
						+'<a class=object_text onclick="return false;"></a>'+
						'<a class=object_menu_icon></a></span>\
					</div>\
				</div>\
				<div class="childs"></div>\
			</div>\
		');
		
		this.nodeConnectionText=this.node.find('.connection_text');
		this.nodeConnectionText
		.
			text(connectionText)
		;
		/*this.node.find('.object_menu_icon')
		.	
			click((user.id!=catId) ? false : function()
			{
				
				menu.openOrClose($(this).parent(), 
				[						
					{
						text: '$@{edit the connection}',
						action: function()
						{
							windows.connectionEdit.open(connection, function(data_)
							{
								console.log(data_);
								data.connectionEdit(connection.id, data_);
								this_.nodeConnectionText.text(connectionStraightOrOpposite ? data_.text : data_.oppositeText);								
							}); 
						}
					},
					{
						text: '$@{remove the connection}',
						action: function()
						{
							windows.connectionRemove.open(connection, function(removeOrNot)
							{
								if(removeOrNot)
								{
									data.connectionRemove(connectionId);
									this_.node.remove();									
								}																								
							}); 
						}
					}
				])
			})
		;*/
		this.node.find('.object_menu_icon').function
	}
	
	
	this.nodeChilds=this.node.find('> .childs')
	
	this.nodeObject=this.node.find('.object');
	this.nodeObjectText=this.nodeObject.find('.object_text')
	.
		text(object.text)				
	.
		attr('title', object.type)
	.
		css('color', object.color)
	/*.
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
					text: '$@{connect with an object from the buffer}',
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
						text: '$@{connect with a new object}',
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
						text: '$@{open}'
					}
				,								
				{
					text: '$@{put in buffer}',
					action: function()
					{										
						objectActions.putInBuffer(object);
					}
				},									
				{
					text: '$@{edit}',
					action: function()
					{
						objectActions.edit(object, catId, data, function()
						{											
							this_.nodeObjectText.text(object.text).css('color', object.color);
						}); 
					}
				},
				{
					text: '$@{remove}',
					action: function()
					{
						objectActions.remove(object, catId, data, function(removeOrNot)
						{
							if(removeOrNot)
							{
								this_.node.remove();
								toast.show('object removed');
							}
						}); 
					}
				}
			]);
		})
	*/;
	
	/*this.nodeObject.find('.object_play')
	.
		click(function()
		{
			player.open(object.text);
		})
	;*/

	this.childsShow=function(objectId, parentObjectId)
	{
		if(! this.childsLoaded)
		{
			var childConnections=[];
			for(var i in data.connections)
			{
				var childConnection=data.connections[i];
				if(childConnection.fromObject===objectId && childConnection.to!=parentObjectId/* || (connectionId && childConnection.fromConnection===connectionId)*/)
				{				
					childConnections.push({connection: childConnection, connectionId: i, direction: true});
				}
				else if(childConnection.to==objectId && childConnection.fromObject!=parentObjectId)
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
					return (aObjectText>bObjectText) ? 1 : -1;;
				}
				return (aText>bText) ? 1 : -1;;
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
				this.nodeChilds.append(child.node);
			}
			
			if(childConnections.length==0 && parentObjectId) 
			{
				toast.show('no more connections except parent');
				this.nodeConnectionText.addClass('connection_text_no_childs');
				this.nodeConnectionText.attr('title', '');//no more connection');
			}
			
			this.childsLoaded=this.childsShowed=true;
		}
		else
		{
			if(this.childsShowed) this.nodeChilds.hide(); else this.nodeChilds.show();
			this.childsShowed=! this.childsShowed;
		}
	}
	
	if(objectId) this.childsShow(objectId); else
	{
		/*this.nodeConnectionText.click(function()
		{
			this_.childsShow(this_.objectId, parent.objectId);
		});*/
	}
	
	this.showNewChild=function(connectionId)
	{
		var child=new Branch(data, catId, false, connectionId, true, this);
		this.childs.push(child);
		this.nodeChilds.append(child.node);
	}
}