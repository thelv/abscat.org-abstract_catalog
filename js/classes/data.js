Data=function(catId, data)
{	
	for(var i in data.connections)
	{
		data.connections[i].id=i;
	}
	
window.data=this;	
	this.objects=data.objects;
	this.connections=data.connections;
	this.connections_=connections_={fromObject: {}, fromConnection: {}};
	this.ext=data;
	
	optimize=function(data)
	{		
		console.log('optim start');	
		var fromObject=connections_.fromObject;
		var fromConnection=connections_.fromConnection;
		
		for(var i in data.objects)
		{
			lib.pushInChild
			(
				lib.pushInChild
				(
					connections_.fromObject, 
					0, {}
				),
				data.objects[i].type, [],
				{id: 0, to: i}
			);
		};
		
		for(var i in data.connections)
		{
			var connection=data.connections[i];
			if(connection.fromObject)
			{				
				lib.pushInChild
				(
					lib.pushInChild
					(
						fromObject, 
						connection.fromObject, {}
					), 
					connection.text, [], 
					{id: i, to: connection.to}
				);
				lib.pushInChild
				(
					lib.pushInChild
					(
						fromObject, 
						connection.to, {}
					), 
					connection.oppositeText, [], 
					{id: i, to: connection.fromObject}
				);
			}
			else
			{				
				lib.pushInChild
				(
					lib.pushInChild
					(
						fromConnection,
						connection.fromConnection, {}
					), 
					connection.text, [], 
					{id: i, to: connection.to}
				);
			}
		}
		console.log('optim end');
	}
	
	var optimizeConnectionAdd=function(connectionId)
	{
		//optimize(data);
	}
	
	var optimizeConnectionRemove=function(connectionId)
	{
		//optimize(data);
	}

	optimize(data);
	
	this.objectCreate=function(object, notSave)
	{
		var objectId=arrayAvailableIndex(data.objects);
		data.objects[objectId]={id: objectId, text: object.text.trim() || '_', type: object.type, color: object.color};
		if(! notSave) save.save(catId, this);
		return objectId;
	}
	
	this.objectEdit=function(objectId, object_)
	{
		
		var object=this.objects[objectId];
		object.text=object_.text;
		object.type=object_.type;
		object.color=object_.color;
		save.save(catId, this);
	}
	
	this.objectRemove=function(objectId)
	{
		delete this.objects[objectId];
		for(var i in data.connections)
		{
			if(data.connections[i].to==objectId || data.connections[i].fromObject==objectId)
			{
				delete data.connections[i];
			}
		}
		save.save(catId, this);
	}
	
	this.connectionCreate=function(connection)
	{
		var connectionId=arrayAvailableIndex(data.connections);
		data.connections[connectionId]={text: connection.text, oppositeText: connection.oppositeText, fromObject: connection.fromObject, fromConnection: connection.fromConnection, to: connection.to};
		save.save(catId, this);
		return connectionId;
	}
	
	this.connectionEdit=function(connectionId, connection_)
	{
		var connection=this.connections[connectionId];
		connection.text=connection_.text;
		connection.oppositeText=connection_.oppositeText;												
		save.save(catId, this);
	}
	
	this.connectionRemove=function(connectionId)
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
}		