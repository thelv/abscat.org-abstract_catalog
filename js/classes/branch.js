var Branch=function(data, catId, objectId, connectionId, connectionStraightOrOpposite, parent)
{
	var this_=this;
	
	this.parent=parent;
	this.childs=[];				
	
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
						'<a class=object_menu_icon>\</a></span>\
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
					<span class="connection"><span class="connection_text"></span>:</span>'+
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
		.
			click((user.id!=catId) ? false : function()
			{
				
				menu.openOrClose($(this).parent(), 
				[	
					{
						text: '$@{connect the connection with a new object}',
						action: function()
						{
							windows.objectConnectWithNewObject.open(null, data, function(data_)
							{								
								var childObjectId=data.objectCreate({text: data_.objectText.trim() || '_', type: data_.objectType, color: data_.objectColor}, true);
								var childConnectionId=data.connectionCreate({text: data_.connectionText, oppositeText: data_.connectionOppositeText, fromObject: objectId || 0, fromConnection: connectionId || 0, to: childObjectId});
								this_.showNewChild(childConnectionId);							
							});
						}
					},
					{
						text: '$@{edit the connection}',
						action: function()
						{
							windows.connectionEdit.open(connection, function(data_)
							{
								data.connectionEdit(connection.id, data_);
								this_.nodeConnection.text(connectionStraightOrOpposite ? connection.text : connection.oppositeText);								
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
		;					
	}
	
	this.nodeObject=this.node.find('.object');
	this.nodeObjectText=this.nodeObject.find('.object_text')
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
	;
	
	this.nodeObject.find('.object_play')
	.
		click(function()
		{
			player.open(object.text);
		})
	;
		

//	if(! objectId) return;
	
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
		this.node.find('> .childs').append(child.node);
	}
	
	this.showNewChild=function(connectionId)
	{
		var child=new Branch(data, catId, false, connectionId, true, this);
		this.childs.push(child);
		this.node.find('> .childs').append(child.node);
	}
}