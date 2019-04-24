//var sqlRequest=(function()
//{
	objectClone=function(o)
	{
		var c={};
		for(var i in o)
		{
			c[i]=o[i];
		}
		return c;
	}
	
	objectCloneTo=function(o, where)
	{
		for(var i in where)
		{
			delete where[i];
		}
		for(var i in o)
		{
			where[i]=o[i];
		}
	}

	var workItemsOperators=function(items)
	{		
		if(! items) return false;
		for(var i in items)
		{
			var item=items[i];
			if(! item.selectItem) item.selectItem={type: 'field'};
		}			
		if(item.delimeter) items.push({selectItem: {type: 'field'}});
		for(var i in items)
		{
			var item=items[i];		
			if(item.selectItem.a)
			{
				item.selectItem.a=workItemsOperators(item.selectItem.a);
			}			
			item.selectItem.childs=workItemsOperators(item.selectItem.childs);
			item.selectItem.aliasChilds=workItemsOperators(item.selectItem.aliasChilds);
			if(item.selectItem.conds)
			{
				for(var i in item.selectItem.conds)
				{
					var cond=item.selectItem.conds[i];
					if(cond.type==':' || cond.type=='e')
					{
						cond.cont=workItemsOperators(cond.cont);
					}
				}
			}
			
		}
		
		delimeters=['^', '|', 'u', ','];
		for(var j in delimeters)
		{
			var delimeter=delimeters[j];
			w: while(true)
			{
				for(var i in items)
				{
					var item=items[i];
					if(item.delimeter==delimeter)
					{							
						item.selectItem={type: delimeter, a: item.selectItem, b: items[+i+1].selectItem};
						item.delimeter=items[+i+1].delimeter;						
						items.splice(+i+1, 1);
						continue w;
					}
				}
				break;
			}
		}		
		return items[0].selectItem;
	}		

	var selectId=false;
	var resultColumnOrder=false;
	var aliases=false;
	//var 
	funcs=false;
	
	var request=function(str)
	{	
		selectId=0;
		resultColumnOrder=false;
		aliases=[];
		funcs=[];
	
		var req=match.select(false, {str: str});
		console.log(req);
		//return;
		
		//req=[{selectItem: {type: 'field', notSelect: true, connection: "", startPoints: 0, childs: req, copy: "0"}}];		
		req=workItemsOperators(req);
		console.log(req);
	//return;
		result.reset();
		
		var branch0=Branch.add(false, {connection: "", copy: "0"});
		var path0=Path.create();
		Path.addBranch(path0, branch0);
		var paths0=Path.resolveItem(path0, branch0);
		select('init', req, null, branch0);				
		paths=select('data', req, paths0);
		paths=paths.success.concat(paths.fail);
		console.log(paths);		
		
		result.start(req);
		for(var i in paths)
		{			
			var path=paths[i];
			pathToResult(path);
			result.newRow();
			/*Path.each(path, function(pathItem)
			{
				result.add(pathItem.connection, pathItem.objectId);
			});
			result.newRow();*/
		}
		console.log(result.rows);	
		return {rows: result.rows, columns: result.columns};
	}

	function pathsForEach(paths, f, separate, keepMain)
	{
		if(separate)
		{
			var resSuccess=[];
			var resFail=[];
			for(var i in paths)
			{
				var m=f(paths[i]);
				if(keepMain)
				{
					for(var i in m.success)
					{
						m.success[i]._mainObjectId=paths[i]._mainObjectId;
						m.success[i]._mainSelect=paths[i]._mainSelect;
					}
					for(var i in m.fail)
					{
						m.fail[i]._mainObjectId=paths[i]._mainObjectId;
						m.fail[i]._mainSelect=paths[i]._mainSelect;
					}
				}
				resSuccess=resSuccess.concat(m.success);
				resFail=resFail.concat(m.fail);
			}
			return {success: resSuccess, fail: resFail};
		}
		
		var res=[];
		for(var i in paths)
		{
			if(keepMain)
			{
				for(var i in m.success)
				{
					m.success[i]._mainObjectId=paths[i]._mainObjectId;
					m.success[i]._mainSelect=paths[i]._mainSelect;
				}
				for(var i in m.fail)
				{
					m.fail[i]._mainObjectId=paths[i]._mainObjectId;
					m.fail[i]._mainSelect=paths[i]._mainSelect;
				}
			}
			var m=f(paths[i]);
			res=res.concat(m.success, m.fail);
		}
		return res;
	}
	
	function selectEach(selectParams, keepMain)
	{
		var paths=selectParams[2];
		var resSuccess=[];
		var resFail=[];
		for(var i in paths)
		{
			selectParams[2]=[paths[i]];
			var m=select.apply(null, selectParams);
			if(keepMain)
			{
				for(var i in m.success)
				{
					m.success[i]._mainObjectId=paths[i]._mainObjectId;
					m.success[i]._mainSelect=paths[i]._mainSelect;
				}
				for(var i in m.fail)
				{
					m.fail[i]._mainObjectId=paths[i]._mainObjectId;
					m.fail[i]._mainSelect=paths[i]._mainSelect;
				}
			}
			resSuccess=resSuccess.concat(m.success);
			resFail=resFail.concat(m.fail);
		}
		return {success: resSuccess, fail: resFail};
	}
	
	function selectRemoveResult(paths)
	{
		for(var i in paths)
		{
			Path.removeDataColumns(paths[i]);
		}
	}

	var select=function(type, select_, paths, branch, notSelect)
	{		
		if(type=='condAndCol')
		{
			var resCond=select('cond', select_, paths, branch, notSelect);
			var resCondSuccessColumns=select('columns', select_, resCond.success, branch, notSelect);
			return {success: resCondSuccessColumns, fail: resCond.fail};
		}
	
		if(type=='init')
		{
			if(select_.id) {
				console.log('IDDD',select_);alert(222);
				dfsdf.sdfsdf=1;
			}
			select_.id=++selectId;
		}
		
		switch(select_.type)
		{
			case ',':
				if(type=='resultColumnOrder')
				{
					select('resultColumnOrder', select_.a);
					select('resultColumnOrder', select_.b);
				}
				else if(type=='init')
				{
					select('init', select_.a, null, branch, notSelect);
					select('init', select_.b, null, branch, notSelect);
				}
				else if(type=='cond')
				{					
					resA=select('cond', select_.a, paths, null, null);
					return {success: resA.success};
				}
				else if(type=='col')
				{				
					resA=select('col', select_.a, paths, null, null);
					
					var resASuccessB=pathsForEach(resA.success, function(path)
					{					
						return select('data', select_.b, [path], null, null, p);
					}, false, true);
															
					return {
						success: resASuccessB
					};
				}
				else if(type=='data')
				{
					resA=select('data', select_.a, paths, null, null);
					
					var resASuccessB=pathsForEach(resA.success, function(path)
					{					
						return select('data', select_.b, [path], null, null, p);
					}, false, true);
										
					var resAFailB=pathsForEach(resA.fail, function(path)
					{					
						return select('data', select_.b, [path], null, null, p);
					}, false, true);
					
					return {
						success: resASuccessB, 
						fail: resAFailB.success.concat(resAFailB.fail)
					};
				}
				break;
				
			case '!':
				if(type=='resultColumnOrder')
				{
					select('resultColumnOrder', select_.a);
				}
				else if(type=='init')
				{
					select('init', select_.a, null, branch, notSelect);
				}
				else if(type=='cond')
				{								
					var resA=select('cond', select_.a, paths, null, null, p);					
					return {success: resA.fail, fail: resA.success};
				}
				else if(type=='col')
				{
					var resA=select('col', select_.a, paths, null, null, p);
					return {success: resA.success};
				}
				else if(type=='data')
				{
					var resA=select('data', select_.a, paths, null, null, p);					
					return {success: resA.fail, fail: resA.success};
				}
				break;
			
			case '|':
				if(type=='resultColumnOrder')
				{
					select('resultColumnOrder', select_.a);
					select('resultColumnOrder', select_.b);
				}
				else if(type=='init')
				{
					select('init', select_.a, null, branch, notSelect);
					select('init', select_.b, null, branch, notSelect);
				}
				else if(type=='cond')
				{
					resA=select('cond', select_.a, paths, null, null, p);
					if(resA.success.length!=0) return resA;
					
					resB=select('cond', select_.b, paths, null, null, p);				
					return resB;
				}
				else if(type=='data')
				{
					resA=select('data', select_.a, paths, null, null, p);
					if(resA.success.length!=0) return resA;
					
					resB=select('data', select_.b, paths, null, null, p);				
					return resB;
				}
				break;
				
			case '^':
				if(type=='resultColumnOrder')
				{
					select('resultColumnOrder', select_.a);
					select('resultColumnOrder', select_.b);
				}
				else if(type=='init')
				{
					select('init', select_.a, null, branch, notSelect);
					select('init', select_.b, null, branch, notSelect);
				}
				else
				{		
					var p_=objectClone(p);					
					p_.notSelect=true;
					var resA=select('data', select_.a, paths, null, null, p_);
					var resASuccessB=pathsForEach(resA.success, function(path)
					{
						return select('data', select_.b, [path], null, null, p);
					}, true, true);
					
					selectRemoveResult(resASuccessB.fail);
					
					return {
						success: resASuccessB.success,
						fail: resA.fail.concat(resASuccessB.fail)
					}
				}
				break;

			case 'u':
				if(type=='resultColumnOrder')
				{
					select('resultColumnOrder', select_.a);
					select('resultColumnOrder', select_.b);
				}
				else if(type=='init')
				{
					select('init', select_.a, null, branch, notSelect);
					select('init', select_.b, null, branch, notSelect);
				}
				else
				{
					/*var resA=select('data', select_.a, paths, null, null, p);
					var resAsuccessB=select('data', select_.b, resA.success, null, null, p);	
                    var resAfailB=select('data', select_.b, resA.fail, null, null, p);
					return {success: resAsuccessB.success.concat(resAfailB.success), fail: resAfailB.fail};*/
					
					console.log(select_);
					var resA=select('data', select_.a, paths, null, null, p);
					var resB=select('data', select_.b, paths, null, null, p);
					return {success: resA.success.concat(resB.success), fail: []};
				}
				break;
				
			case '(':
				if(type=='resultColumnOrder')
				{
					select('resultColumnOrder', select_.a);
					for(var i in select_.childSelectsInited)
					{
						select('resultColumnOrder', select_.childSelectsInited[i]);
					}
				}
				else if(type=='init')
				{
					if(select_.func)
					{
						funcs[select_.func]=selectClone(select_.a);
					}
					if(select_.aliasChilds)
					{
						console.log('SEL', select_);
						//sdfsd.sdfsdf=1;
						if(! select_.childs)
						{
							select_.childs=select_.aliasChilds;
						}
						else
						{
							alert(444);
							console.log('CH', select_.childs);
							console.log('CHSELE', select_.aliasChilds);
							select_.childs=
							{
								type: ',',
								a: select_.childs,
								b: select_.aliasChilds
							}
						}
					}
					select_.branch=branch;
					select('init', select_.a, null, branch, notSelect || select_.notSelect);
				}
				else
				{
					var resA=select('data', select_.a, paths, null, null);
					
					//продолжение скобки
					var resASuccessChilds=pathsForEach(resA.success, function(path)
					{
						if(path._mainSelect)
						{
							if(! select_.childSelectsInited[path._mainSelect.id])
							{
								var childBranch=Branch.clone(path._mainSelect.branch);
								console.log('childBranch', childBranch);
								console.log(select_.branch);
								if(select_.alias) Branch.setLast(childBranch, {alias: {type: 'string', baseBranch: select_.branch, cont: select_.alias || "()"}});
								
								var childSelect=
								{
									type: 'field', 
									branch: childBranch,
									conds: select_.conds, 
									notSelect: select_.notSelect, notSelectUncond: true,//! select_.alias, //(select_.a.type!='|' && select_.a.type!='^' && select_.a.type!='u'), 
									childs: selectClone(select_.childs)
								};
								select('init', childSelect, null, null, notSelect);
								select_.childSelectsInited[path._mainSelect.id]=childSelect;
							}
							
							var childSelect=select_.childSelectsInited[path._mainSelect.id];
							return select('data', childSelect, [path], null, null, p);
						}
						else
						{
							return {success: [path], fail: []}
						}
					}, true, true);
					
					console.log('((((2', resASuccessChilds);
					
					selectRemoveResult(resASuccessChilds.fail);
														
					//проставить main
					var p_=objectClone(p);
					p_.notSelect=true;		
					pathsForEach(resASuccessChilds.success, function(path)
					{
						if(path._mainSelect)
						{
							var childSelect=select_.childSelectsInited[path._mainSelect.id];
							console.log('ms', path._mainSelect, select_.childSelectsInited);
							return select('data', childSelect, [path], null, null, p_);
						}
						else
						{
							return {success: [], fail: []};
						}
					}, true);
				
					return {success: resASuccessChilds.success, fail: resASuccessChilds.fail.concat(resA.fail)};
				}
				break;
				
			case 'field':
				if(type=='resultColumnOrder')
				{
					if(select_.resultColumn)
					{
						if(select_.resultColumn.order===undefined)
						{
							select_.resultColumn.order=(0+(resultColumnOrder++));
						}
					}
					
					//conds
					for(var i in select_.conds)
					{						
						var cond=select_.conds[i];						
												
						switch(cond.type)
						{
							case ':':
								select('resultColumnOrder', cond.cont);								
								break;
							case 'e':
								select('resultColumnOrder', cond.cont);		
								break;
							
						}
					}
					
					//childs
					if(select_.childs)
					{
						select('resultColumnOrder', select_.childs);
					}
				}
				else if(type=='init')
				{	
					//alias
					if(select_.alias)
					{
						select__=selectClone(select_);
						select__.conds=[];						
						select__.alias=false;
						select__.id=false;
						//select__.notSelectUncond=true;
						
						select_.type='(';
						select_.notSelect=false;
					
						select_.childs=select_.aliasChilds;
						select_.childSelectsInited=[];
						select_.a=select__;
						select_.id=false;
						return select('init', select_, null, branch, notSelect);
					}
			
					//branch
					if(! select_.branch)
					{						
						if(select_.startPoints==1)
						{
							var branch_=Branch.add(false, {connection: "", copy: "0"});
						}
						else
						{
							branch_=branch;
						}
						
						//function
						if(! select_.connectionQuote && select.connectionOpposite===undefined && funcs[select_.connection])
						{
							select__=
							{
								type: 'func',
								func: select_.connection,							
							};
							
							select_.type='(';
							select_.childSelectsInited=[];
							select_.alias=select_.alias || select_.connection;
							select_.a=select__;
							select_.id=false;
							return select('init', select_, null, branch_, notSelect);
						}
						//field
						else if(select_.connection=='this' || (select_.connection===false))
						{
							select_.branch=branch_;
						}
						else
						{
							select_.branch=Branch.add(branch_, {connection: select_.connection, connectionOpposite: select_.connectionOpposite, copy: select_.copy});										
						}
					}
					
					//result column
					if(! notSelect && ! select_.notSelect && ! select_.notSelectUncond)
					{						
						select_.resultColumn=result.addColumn('field', {branch: select_.branch, param: 'text'});
					}
					else
					{
						select_.resultColumn=false;
					}
					
					//conds
					for(var i in select_.conds)
					{						
						var cond=select_.conds[i];						
												
						switch(cond.type)
						{
							case ':':
								//init cond
								select('init', cond.cont, null, select_.branch, notSelect);								
								break;
							case 'e':
								select('init', cond.cont, null, select_.branch, notSelect);		
								break;
							
						}
					}
					
					//childs
					if(select_.childs)
					{
						select('init', select_.childs, null, select_.branch, notSelect);					
					}										
				}
				else if(type=='data')
				{					
					if(false)//p.notConds)
					{
						success=paths;
						fail=[];
					//	res={success: success, fail: fail};
					}
					else
					{
						//resolve paths
						var pathsResolved=[];
						for(var i in paths)
						{
							var path=paths[i];
							Path.addBranch(path, select_.branch);
							pathsResolved=pathsResolved.concat(Path.resolveItem(path, select_.branch));
						}
														
						//conds "=", "<", ">" ...
						var success=[];
						var fail=[];
						for(var i in pathsResolved)
						{					
							var path=pathsResolved[i];
							var pathItem=Path.getBranchItem(path, select_.branch);
							var objectId=pathItem.objectId;
						
							var condsSuccess=true;										
							if(objectId===false)
							{
								condsSuccess=false;
							}
							else if(! (objectId===0))
							{												
								var value=data.objects[objectId].text;
								f: for(var j in select_.conds)
								{						
									var cond=select_.conds[j];
									if(typeof cond.cont=='number')
									{
										var valueTypeCasted=parseFloat(value);
									}
									else
									{
										var valueTypeCasted=value;								
									}
									switch(cond.type)
									{
										case '=':
											if(! (valueTypeCasted==cond.cont))
											{
												condsSuccess=false;
												break f;
											}
											break;
										case '>':
											if(! (valueTypeCasted>cond.cont))
											{
												condsSuccess=false;
												break f;
											}
											break;
										case '<':
											if(! (valueTypeCasted<cond.cont))
											{
												condsSuccess=false;
												break f;
											}
											break;
										case '>=':
											if(! (valueTypeCasted>=cond.cont))
											{
												condsSuccess=false;
												break f;
											}
											break;
										case '<=':
											if(! (valueTypeCasted<=cond.cont))
											{
												condsSuccess=false;
												break f;
											}
											break;
										case '<>':
											if(! (valueTypeCasted!=cond.cont))
											{
												condsSuccess=false;
												break f;
											}
											break;
										
									}
								}
							}											
							if(condsSuccess)
							{				
								if(select_.resultColumn)
								{		
									if(! pathItem.data.results) pathItem.data.results=[];
									pathItem.data.results['text']=value;
								}
								success.push(path);
							}
							else
							{
								fail.push(path);
							}
						}
					}
						
					//conds ":", "e"
					for(var j in select_.conds)
					{						
						var cond=select_.conds[j];						
						
						switch(cond.type)
						{
							case ':':
								//select cond								
								var resCond=pathsForEach(success, function(path)
								{
									return select('data', cond.cont, [path], null, null, p);						
								}, true);
																
								//this filter by cond
								if(! p.notConds)
								{
									success=resCond.success;
									fail=fail.concat(resCond.fail);
								}
								break;
							case 'e':
								//select cond								
								var resCond=pathsForEach(success, function(path)
								{									
									var objectId=Path.getBranchItem(path, select_.branch).objectId;
									console.log('oid', objectId);
									var resCond_=select('data', cond.cont, [path], null, null, p);					
									//console.log('resCond_', resCond_);
									var resCond__={success: [], fail: resCond_.fail};
									for(var i in resCond_.success)
									{										
										var path_=resCond_.success[i];
										console.log('moid', path_._mainObjectId);
										if(path_._mainObjectId==objectId)
										{
											resCond__.success.push(path_);
											//resCond__.fail.push(resCond_.fail);
										}
										else
										{
											resCond__.fail.push(path_);
											//resCond__.fail.push(resCond_.fail);
										}
									}
									return resCond__;
								}, true);
																
								//this filter by cond
								if(! p.notConds)
								{
									success=resCond.success;
									fail=fail.concat(resCond.fail);
								}
								break;
							
						}
					}					
					
					var res={success: success, fail: fail};										
					
					//childs
					if(select_.childs)
					{												
						var resChilds=pathsForEach(res.success, function(path)
						{
							return select('data', select_.childs, [path], null, null, p);						
						}, select_.notSelect);
						
						//this filter by childs or not
						if(select_.notSelect)
						{
							success=resChilds.success;
							fail=res.fail.concat(resChilds.fail);
						}
						else
						{
							success=resChilds;
							fail=res.fail;
						}
					}
					
					//result columns
					if(! p.notSelect && select_.resultColumn)
					{
						for(var i in success)
						{
							var path=success[i];
							var pathItem=Path.getBranchItem(path, select_.branch);
							if(! pathItem.data.columns) pathItem.data.columns=[];
							pathItem.data.columns=pathItem.data.columns.concat({column: select_.resultColumn, param: 'text'});
						}
					}

					if(! select_.notSelect || ! select_.childs)
					{
						for(var i in success)
						{
							var path=success[i];
							var pathItem=Path.getBranchItem(path, select_.branch);
							path._mainObjectId=pathItem.objectId;
							path._mainSelect=select_;
						}
						
						/*for(var i in fail)
						{
							var path=fail[i];
							var pathItem=Path.getBranchItem(path, select_.branch);
							path._mainObjectId=pathItem.objectId;
							path._mainSelect=select_;
						}*/
					}					

					return {success: success, fail: fail};
				}
				break;
				
			case 'func':
				if(type=='init')
				{
					select_.initParams=
					{
						branch: branch,
						notSelect: notSelect,
						p: p
					}
				}
				if(type=='data')
				{
					var initParams=select_.initParams;
					select__=selectClone(funcs[select_.func]);					
					objectCloneTo(select__, select_);
					select('init', select_, null, initParams.branch, initParams.notSelect);		
					return select('data', select_, paths, null, null, p);
				}
				break;
		}
	}

	var selectClone=function(select_)
	{
		if(! select_) return false;
		return $.extend(true, {}, select_);
	}

	pathToResult=function(path)
	{
		if(! path) return;
		
		for(var i in path)
		{
			if(i=='_mainObjectId' || i=='_mainSelect') continue;
			for(var j in path[i])
			{
				var pathItem=path[i][j];
				for(var k in pathItem.data.columns)
				{
					var column=pathItem.data.columns[k];
					result.addValue(column.column, pathItem.data.results[column.param], pathItem.objectId);
				}
				pathToResult(pathItem.childs);
			}
		}
	}

	result=
	{
		row: {},
		rows: [],
		columns: [],
		columnsCount: 0,
		
		addColumn: function(type, cont)
		{
			if(type=='field')
			{
				var columnName=Branch.toStringAlias(cont.branch).substr(2);
				if(this.columns[columnName])
				{
					return this.columns[columnName];
				}
				
				var column={name: columnName, type: type, cont: cont};
				this.columns[columnName]=column;
				return column;
			}
		},
				
		reset: function()
		{
			this.rows=[];
			this.row=[];
			this.columns=[];
		},
		start: function(select_)
		{
			/*this.columnsCount=this.columns.length;
			var order=0;
			for(var i in this.columns)
			{
				var column=this.columns[i];
				column.order=order;
				order++;
			}*/
			resultColumnOrder=0;
			select('resultColumnOrder', select_);
			var columns_=[];
			for(var i in this.columns)
			{
				var column=this.columns[i];
				columns_[column.order]=column;
			}
			this.columns=columns_;
		},
		newRow: function()
		{
			if(this.row.length==0) return;
			this.rows.push(this.row);
			this.row=[];
		},
		addValue: function(column, value, objectId)
		{
			this.row[column.order]={value: value, objectId: objectId};
		}
	}

	return request;
})();

var resultTable=function(columns, rows, catId)
	{	
		var tableNode=$('<table class=result_table cellpadding=0 cellspacing=0></table>');
		var columnsCount=columns.length;
		if(columnsCount)
		{
			var headersNode=$('<tr>');
			for(var i in columns)
			{		
				var headerNode=$('<td><div>'+columns[i].name+'</div></td>');
				headersNode.append(headerNode);
			}
			tableNode.append(headersNode);
		}	
		for(var i in rows)
		{
			var rowNode=$('<tr>');
			var row=rows[i];
			for(var j=0; j<columnsCount; j++)
			{				
				if(row[j])
				{
					var objectId=row[j].objectId;				
				}
				else
				{
					var objectId=false;
				}
				var object=(objectId ? data.objects[objectId] : {text: ""})
				var columnNode=$('<td>');
				columnNode.append(Object_.createNode(object, catId));
				rowNode.append(columnNode);
			}		
			tableNode.append(rowNode);
		}
		return tableNode;
	}

var resultTableHTML=function(rows, catId)
{	
	var tableNode='<table class=result_table cellpadding=0 cellspacing=0>';
	if(rows[0])
	{
		var headersNode='<tr>';
		var row=rows[0];
		for(var i in row)
		{
			if(row[i]===0) continue;
			var headerNode='<td>'+i+'</td>';
			headersNode+=headerNode;
		}
		tableNode+=headersNode+'</tr>';
	}
	for(var i in rows)
	{
		var rowNode='<tr>';
		var row=rows[i];
		for(var j in row)
		{
			var column=row[j];
			if(column===0) continue;		
			var object=(column ? data.objects[column] : {text: "-"})
			//var columnNode='<td>'+object.text+'</td>';
			//columnNode.append(Object_.createNode(object, catId));
			columnNode='<td><div class=object>\
				<a class="object_select"><input type="checkbox"></a><span class="object_">'
				+(object.type=='song' ? '<a class=object_play></a>' : '')
				+'<a style="color:'+object.color+'" class=object_text onclick="return false;">'+object.text+'</a>'+
				'<a class=object_menu_icon>\</a></span>\
			</div></td>';
			rowNode+=columnNode;
		}
		tableNode+=rowNode+'</tr>';
	}
	return tableNode+'</table>';
}