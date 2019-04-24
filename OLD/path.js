Path=
{
	create: function(path)
	{
		return {
			path: path,
			str: str,
			and: function(path)
			{
				return createFromPath(Path.and(this.path, path.path));
			},
			object: function()
			{
				return Path.object(this.path);
			}
		}
	},
	
	and: function(path1, path2)
	{		
		var result=[];
		for(var i in path1)
		{				
			var path1_=path1[i];
			for(var j in path2)					
			{
				var path2_=path2[j];
				if(path1_.connection==path2_.connection)
				{
					if(path2_.branchs)
					{
						if(path1_.branchs)
						{
							var branchs=[];
							for(var i_ in path1_.branchs)
							{
								var branch1=path1_.branchs[i_];
								for(var j_ in path2_.branchs)
								{
									var branch2=path2_.branchs[j_];
									if(branch1.objectId==branch2.objectId)
									{
										branchs.push(branch1);
									}
								}
							}
							path1_.branchs=branchs;
						}
						else
						{
							path1_.branchs=path2_.branchs;
						}
					}
					if(path1_.type==path2_.type && path1_.path && path2_.path)
					{
						path1_.path=Path.and(path1_.path, path2_.path);
					}
					else if(! path1._path && path2_.path)
					{
						path1_.path=path2_.path;
					}	
					delete path2[j];
				}										
			}
			result.push(path1_);
		}
		for(var i in path2)
		{
			result.push(path2[i]);
		}
		return result;
	},
	
	filter: function(path1, path2)
	{
		var result=[];
		for(var i in path1)
		{				
			var path1_=path1[i];
			for(var j in path2)
			{
				var path2_=path2[j];
				if(path1_.connection==path2_.connection)
				{
					if(path2_.branchs)
					{
						if(path1_.branchs)
						{
							var branchs=[];
							for(var i_ in path1_.branchs)
							{
								var branch1=path1_.branchs[i_];
								for(var j_ in path2_.branchs)
								{
									var branch2=path2_.branchs[j_];
									if(branch1.objectId==branch2.objectId)
									{
										branchs.push(branch1);
									}
								}
							}
							path1_.branchs=branchs;
						}
						else
						{
							path1_.branchs=path2_.branchs;
						}
					}
					
					if(path1_.type==path2_.type && path1_.path && path2_.path)
					{
						path1_.path=Path.filter(path1_.path, path2_.path);							
					}						
				}										
			}
			if((! path1_.branchs || path1_.branchs.length) && (! path1_.path || path1_.path.length))
			{
				result.push(path1_);
			}
		}
		return result;
	},
	
	object: function(path)
	{
		if(! path.path) return data.objects[path.branchs[0].object];
		return Path.object(path.path[0]);
	},
	
	separate: function(path, branch)
	{
		var paths=[];
		var branch=branch || {objectId: 0};
		
		for(var i in path)
		{
			var path_=path[i];
			if(path_.branchs)
			{
				var branchs=path_.branchs;
			}
			else
			{
				try
				{
					if(path_.type=='object')
					{
						var connections=data.connections_.fromObject[branch.objectId][path_.connection];
					}
					else
					{
						var connections=data.connections_.fromConnection[branch.connectionId][path_.connection];
					}
				}
				catch(e)
				{
					var connections=[];
				}
				var branchs=[];
				for(var i in connections)
				{
					branchs.push({objectId: connections[i].to, connectionId: connections[i].id})
				}						
			}
			
			for(var i in branchs)
			{
				var branch=branchs[i];
				if(path_.path)
				{				
					var childs=Path.separate(path_.path, branch);					
					for(var i in childs)
					{
						paths.push([{type: path_.type, connection: path_.connection, branchs: [branch], path: [childs[i][0]]}]);
					}						
				}
				else
				{
					paths.push([{type: path_.type, connection: path_.connection, branchs: [branch]}]);
				}
			}
		}
		return paths;
	}
}