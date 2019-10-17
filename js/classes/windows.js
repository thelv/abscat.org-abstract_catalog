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
	import: new Window('window_import').extend(function()
	{
		this.open=function(params, callback)
		{															
			this.show();
		}
	}),
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
			this.node.find('[name=object_type]').val(object ? object.type || '' : '');
			this.node.find('[name=object_color]').val(object ? object.color || '' : '');
			
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
				windows.alert.open({text: '$@{The buffer is empty}!', buttons: 'ok'});
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
			this.node.find('._form ._head').text('$@{Remove the object} "'+object.text+'"?');						
			
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
	typeEdit: new Window('window_type_edit').extend(function()			
	{
		this.open=function(type, data, callback)
		{															
			this.node.find('[name=text]').val(type);
			this.node.find('[name=color]').val(dataGetTypeColor(data, type));
			console.log(data);
			this.show();
			
			this.ok=function()
			{							
				callback(
				{
					text: this.node.find('[name=text]').val(),
					color: this.node.find('[name=color]').val()								
				});
				this.close();
			};
		}
	}),
};