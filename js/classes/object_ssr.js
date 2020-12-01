var Object_=
{
	createNode: function(object, catId)
	{
		var objectNode=$(
		'\
			<div class=object>\
				<a class="object_select"><input type="checkbox"></a><span class="object_">'
				+(object.type=='song' ? '<a class=object_play></a>' : '')
				+'<a class=object_text onclick="return false;"></a>'+
				'<a class=object_menu_icon>\</a></span>\
			</div>\
		');
		/*objectNode.find('.object_play')
			.click((function(object){return function(){player.open(object.text);};})(object))
			.contextmenu((function(object){return function(e){vk.stop();vk.open(object.text);e.preventDefault();return false;};})(object))
		;*/
		objectNode.find('.object_text').text(object.text).css('color', object.color).attr('title', object.type+'\n'+object.text).attr('href', url.gen({page: 'catObj', catId: catId, objId: object.id}));
		return objectNode;
	},
	
	actions:
	{
		create: function(object, catId, data, callback)
		{
			windows.objectNew.open(object, data, function(data_)
			{
				var objectId=data.objectCreate(data_);
				callback(objectId);
			});
		},
		edit: function(object, catId, data, callback)
		{
			windows.objectEdit.open(object, data, function(data_)
			{
				data.objectEdit(object.id, data_);			
				callback();
			}); 
		},
		remove: function(object, catId, data, callback)
		{
			windows.objectRemove.open(object, function(removeOrNot)
			{
				if(removeOrNot)
				{
					data.objectRemove(object.id);
				}
				callback(removeOrNot);
			}); 
		},
		connectWithNewObject: function(object, catId, data, callback)
		{
			windows.objectConnectWithNewObject.open(object, data, function(data_)
			{
				var childObjectId=data.objectCreate({id: childObjectId, text: data_.objectText.trim() || '_', type: data_.objectType, color: data_.objectColor}, true);
				var childConnectionId=data.connectionCreate({text: data_.connectionText, oppositeText: data_.connectionOppositeText, fromObject: object.id, to: childObjectId});
				callback(childConnectionId);					
			}); 
		},
		connectWithObjectFromBuffer: function(object, catId, data, callback)
		{
			windows.objectConnectWithObjectFromBuffer.open(object, data, function(data_)
			{
				var childObjectId=data_.objectId;
				var childConnectionId=data.connectionCreate({text: data_.connectionText, oppositeText: data_.connectionOppositeText, fromObject: object.id, to: childObjectId});									
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
}