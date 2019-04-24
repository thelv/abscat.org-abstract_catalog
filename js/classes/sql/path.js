//var sqlRequest=(function()
//{	
	var Path=
	{
		create: function()
		{
			return {_selectChoises: {}, _aliases: []};
		},	
		
		addBranch: function(path, branch, pathFirst, parent)
		{
			pathFirst=pathFirst || path;
			var path_=false;
			if(! ((path_=path[branch.connection]) && (path_=path_[branch.copy])))
			{		
				path_=(path[branch.connection]={})[branch.copy]=
				{
					objectId: undefined,
					connectionId: undefined,
					connection: branch.connection,
					connectionOpposite: branch.connectionOpposite,
					copy: branch.copy,
					data: {results: false, columns: false},
					parent: parent,
					first: pathFirst,
				}
			}
			
			if(! branch.child) return path_;
			if(! path_.childs) path_.childs={};
			return Path.addBranch(path_.childs, branch.child, pathFirst, path_);
		},	
		
		getBranchItem: function(path, branch)
		{
			var path_=path[branch.connection][branch.copy];	
			if(! branch.child) return path_;
			
			return Path.getBranchItem(path_.childs, branch.child);
		},
		
		resolveItem: function(path, branch)
		{
			var child=Path.getBranchItem(path, branch)
			if(child.objectId!==undefined) return [Path.clone(path)];
			
			var parent=child.parent;
			
			//var connections=[{to: 1, id: 1}];						
			if(! parent)
			{
				var connections=[{to: 0, id: 0}];
			}
			else
			{
				var m=data.connections_.fromObject[parent.objectId] 
				var connections=m ? m[child.connection] : [];
			}
			
			if(! connections || ! connections.length) connections=[{to: false, id: false}];
			
			var paths=[];
			for(var i in connections)
			{
				var connection=connections[i];
				var path_=Path.clone(path);
				pathItem=Path.getBranchItem(path_, branch);
				pathItem.objectId=connection.to;
				pathItem.connectionId=connection.id;
				paths.push(path_);
			}		
			return paths;
		},
		
		clone: function(path, pathFirst, pathParent)
		{
			if(! path) return false;
			
			var path_={};
			for(var i in path)
			{
				if(i=='_mainObjectId' || i=='_mainSelect' || i=='_selectChoises' || i=='_aliases') continue;
				for(var j in path[i])
				{
					var pathItem=path[i][j];
					
					var pathItem_=
					{
						objectId: pathItem.objectId,
						connectionId: pathItem.connectionId,
						connection: pathItem.connection,
						connectionOpposite: pathItem.connectionOpposite,
						copy: pathItem.copy,
						data: {results: pathItem.data.results, columns: pathItem.data.columns},
						parent: pathParent,
						first: pathFirst
					}
					if(! pathFirst) pathFirst=pathItem_;
					pathItem_.pathFirst=pathFirst;
					
					pathItem_.childs=this.clone(pathItem.childs, pathFirst, pathItem_);
					
					if(! path_[i]) path_[i]={};
					path_[i][j]=pathItem_;
				}
			}
			
			if(path._selectChoises)
			{
				path_._selectChoises={};
				for(var i in path._selectChoises)
				{
					path_._selectChoises[i]=path._selectChoises[i];
				}
			}
			
			if(path._aliases)
			{
				path_._aliases=[];
				for(var i in path._aliases)
				{
					path_._aliases[i]=path._aliases[i];
				}
			}
			
			return path_;
		},
		
		each: function(path, f)
		{
			if(! path) return;
			
			for(var i in path)
			{
				for(var j in path[i])
				{
					var pathItem=path[i][j];
					f(pathItem);				
					Path.each(pathItem.childs, f);
				}
			}
		},
		
		removeDataColumns: function(path)
		{
			Path.each(path, function(pathItem)
			{
				pathItem.data.columns=false;
			});
		}
	}

	var Branch=
	{
		add: function(branch, field)
		{
			branch=this.clone(branch);
			
			var branch_=
			{
				connection: field.connection, 
				connectionOpposite: field.connectionOpposite, 
				copy: field.copy,
				alias: field.alias
			}
			
			if(branch)
			{
				var m=branch.last;
				branch.last=branch.last.child=branch_;
				branch_.parent=m;
			}
			else
			{				
				branch_.first=branch_.last=branch_;
				branch=branch_;
			}
			
			return branch;
		},
		
		setLast: function(branch, field)
		{
			if(field.copy!==undefined)
			{
				branch.last.copy=field.copy;
			}
			
			if(field.alias!==undefined)
			{
				branch.last.alias=field.alias;
			}
		},
		
		removeEnd: function(branch, count, firstCall)
		{			
			if(firstCall) branch=this.clone(branch);
			
			if(! count) return branch;
			
			var m=branch.last.parent;
			if(! m) return branch;
			
			m.child=false;
			branch.last=m;
			
			this.removeEnd(branch, count-1, false);
			
			return branch;
		},
		
		clone: function(branch, parent)
		{
			if(! branch) return false;
			
			var branch_=
			{
				connection: branch.connection,
				connectionOpposite: branch.connectionOpposite,
				copy: branch.copy,
				alias: branch.alias,
				parent: parent
			}
			
			branch_.child=this.clone(branch.child, branch_);
			
			if(branch_.child) branch_.last=branch_.child.last; else branch_.last=branch_;
			
			return branch_;
		},
		
		toString: function(branch)
		{
			if(! branch) return "";
			
			return '.'+branch.connection+''+(branch.connectionOpposite ? '/'+branch.connectionOpposite+'' : "")+this.toString(branch.child);
		},
		
		toStringAlias: function(branch, notFirstCall)
		{
			if(! branch) return "";
			if(! notFirstCall) branch=branch.last;
		
			if(branch.alias) 
			{
				return this.toStringAlias(branch.alias.baseBranch)+'.'+branch.alias.cont;
			}
			else
			{
				return this.toStringAlias(branch.parent, true)+'.'+branch.connection+''+(branch.connectionOpposite ? '/'+branch.connectionOpposite+'' : "");
			}
		},
		
		findAliasBranch: function(branch, alias, notFirstCall)
		{
			if(! notFirstCall) branch=this.clone(branch);
			
			if(! branch) return false;
			
			var m=branch.last;
			
			if(m.alias && (m.alias.cont==alias.cont && Branch.toString(m.alias.baseBranch)==Branch.toString(alias.baseBranch)))
			{
				return branch;
			}
			
			m=m.parent;
			
			if(! m) return false;
						
			m.child=false;
			branch.last=m;
			
			return this.findAliasBranch(branch, alias, true);
		}
	}
	
	var Alias=
	{
		find: function(baseBranch, alias)
		{
			
		}
	}
//}