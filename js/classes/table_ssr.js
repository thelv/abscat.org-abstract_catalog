var table=function(data, catId, filterType, filterText)
{	
console.log('start');
	var typesNodeCreate=function(types, headersIs)
	{		
		var tableNode=$('<table class=table cellpadding=0 cellspacing=0><tr></tr></table>');					
		var tableTrNode=tableNode.find('tr');
		tableTrNode.append('\
				<td>\
					<div class=table_type_head>\
						$@{Types}:\
					</div>\
					<div class=table_type_objects>\
						$@{Objects}:\
					</div>\
				</td>\
			');
		
		for(var i in types)
		{				
			var type=types[i];
			var typeNode=$(
			'\
				<td>\
					<div class=table_type_head>\
						<a></a>\
						<div class=_menu_icon></div>\
					</div>\
					<div class="table_type_objects '+(type.type=='song'? 'sssong' : '')+'">\
					</div>\
				</td>\
			');
			typeNode.find('.table_type_head a').text(type.type || '_');
			/*typeNode.find('._menu_icon').click((function(type){return function()
			{						
				menu.openOrClose
				(
					$(this).parent()
				, 
					[	
						{
							text: 'create object of this type',
							action: function()
							{
								Object_.actions.create({type: type.type, color: dataGetTypeColor(data, type.type)}, catId, data, function(objectId)
								{									
									//pages.catObj.go(catId, objectId);
									filter(true);
									//toast.show('object created');
								});													
							}
						},
						{
							text: 'edit type',
							action: function()
							{
								windows.typeEdit.open(type.type, data, function(type_)
								{
									console.log(type_);
									var color='';
									for(var i in data.objects)
									{
										if(data.objects[i].type==type.type)
										{
											data.objects[i].type=type_.text;
											data.objects[i].color=type_.color;
										}
									}
									save.save(catId, data);
									filter(true);
								});								
							}
						}
						
					]
				);				
			};})(type));*/
			
			var typeObjectsNode=typeNode.find('.table_type_objects');
			
			//typeObjectsNode.append('<div class="table_type_object aaa" style="margin: -2px 0 2px 0;white-space:nowrap"><div style="color:#888">sorted by date (desc) ?</div></div>');
			
			var typeObjectsNodeCreate=function(objects)
			{
				var typeObjectsNode=$('<div></div>');
				for(var i in objects)
				{
					var object=objects[i];
					var objectNode=$(
					'\
						<div class=table_type_object>\
							<div class=object>\
								<a class="object_select"><input type="checkbox"></a><span class="object_">'
								+(object.type=='song' ? '<a class=object_play></a>' : '')
								+'<a class=object_text onclick="return false;"></a>'+
								'<a class=object_menu_icon>\</a></span>\
							</div>\
						</div>\
					');
					/*objectNode.find('.object_play')
						.click((function(object){return function(){player.open(object.text);};})(object))
						.contextmenu((function(object){return function(e){vk.stop();vk.open(object.text);e.preventDefault();return false;};})(object))
					;*/
					objectNode.find('.object_text').text(object.text).css('color', object.color).attr('title', object.type+'\n'+object.text).attr('href', url.gen({page: 'catObj', catId: catId, objId: object.id}));
					(function(object, objectNode)
					{
						/*objectNode.find('.object_text')
						.
							click(function()
							{
									pages.catObj.go(catId, object.id);
							})
						;
						objectNode.find('.object_menu_icon')
						.
							click(function()
							{
								menu.openOrClose
								(
									$(this).parent()
								, 
									[											
										{
											text: '$@{put in the buffer}',
											action: function()
											{
												buffer.put(object);
												help.show(data, catId);
											}
										},
										{
											separ: true
										},
										{
											text: '$@{connect with object from buffer}',
											action: function()
											{
												Object_.actions.connectWithObjectFromBuffer(object, catId, data, function(childConnectionId)
												{																													
													//
												}); 
											}
										},
										{
											text: '$@{connect with new object}',
											action: function()
											{
												Object_.actions.connectWithNewObject(object, catId, data, function(childConnectionId)
												{
													//
												}); 
											}
										},											
										{
											text: '$@{edit}',
											action: function()
											{
												Object_.actions.edit(object, catId, data, function()
												{											
													objectNode.find('.object_text').text(object.text).css('color', object.color);
													//toast.show('object edited');
												}); 
											}
										},
										{
											text: '$@{remove}',
											action: function()
											{
												Object_.actions.remove(object, catId, data, function(removeOrNot)
												{
													if(removeOrNot)
													{
														objectNode.remove();
														//toast.show('object removed');
													}
												}); 
											}
										}										
									]
									.concat
									(
										object.type=='song'
										?
											[														
												{
													separ: true
												},
												{
													text: '$@{listen}',
													action: function()
													{
														//youtube.open(object.text);
													}
												},
												{
													text: '$@{listen here through youtube}',
													action: function()
													{
														youtube.open(object.text);
													}
												},
												{
													text: '$@{listen here through vk.com}',
													action: function()
													{
														//window.open('https://vk.com/audios0?q='+encodeURIComponent(object.text))
														vk.open(object.text);
													}
												}
											]
										:
											[]
										//
									)
								);										
								return false;
							})
						;*/
					})(object, objectNode);
					typeObjectsNode.append(objectNode);						
				}
				return typeObjectsNode;
			}
			
			if(type.objectsFiltered) type.objectsFiltered=type.objectsFiltered.slice(0,50);
			if(type.objects) type.objects=type.objects.slice(0,50);
			
			if(type.objectsFiltered.length) typeObjectsNode.append(typeObjectsNodeCreate(type.objectsFiltered).addClass('table_type_objects_filtered'));
			//else typeObjectsNode.append($('<div style="margin-bottom:10px;color:#888">nothing found</div>'));
			if(type.objects.length) typeObjectsNode.append(typeObjectsNodeCreate(type.objects));
			
			tableTrNode.append(typeNode);
		}
		return tableNode;
	}
	
	var types=[];
	
	var filterType_=filterType;
	if(! filterType && ! filterText) filterType='song';
	
	for(var i in data.objects)
	{
		var object=data.objects[i];
		
		if((filterType && object.type.toLowerCase().indexOf(filterType)===-1) || (filterText && object.text.toLowerCase().indexOf(filterText)===-1))
		{
			if(types[object.type])
			{
				types[object.type].objects.push(object);
			}
			else
			{
				types[object.type]={type: object.type, objects: [object], objectsFiltered: []};
			}	
		}
		else
		{
			if(types[object.type])
			{
				types[object.type].objectsFiltered.push(object);
			}
			else
			{
				types[object.type]={type: object.type, objects: [], objectsFiltered: [object]};
			}									
		}
	}				
	
	var typesFirst=types['genre'] ? [types['genre']] : [];
	delete types['genre'];
	
	var types_=[];
	var typesFiltered=[];
	for(var i in types)
	{
		if(types[i].objectsFiltered.length)
		{
			typesFiltered.push(types[i]);
		}
		else
		{
			types_.push(types[i]);
		}
	}
	types=types_;	
	types=types.sort(function(a, b){return (((a.type.toLowerCase()>b.type.toLowerCase()) || (!a.type)) && (!!b.type)) ? 1 : -1;;});
	types=typesFirst.concat(types);
	typesFiltered=typesFiltered.sort(function(a, b){return (((a.type.toLowerCase()>b.type.toLowerCase()) || (!a.type)) && (!!b.type)) ? 1 : -1;;});

	if(typesFiltered.length==0 && (filterType_ || filterText))
	{
		toast.show('nothing\'s found');
	}
	
	var typess=[types, typesFiltered];
	for(var i in typess)
	{
		var types_=typess[i];
		for(var i in types_)
		{
			var objects=types_[i].objects;			
			var objectsFiltered=types_[i].objectsFiltered;	
			objects=objects.sort(function(a, b){return (a.text.toLowerCase()>b.text.toLowerCase()) ? 1 : -1;});
			objectsFiltered=objectsFiltered.sort(function(a, b){return (a.text.toLowerCase()>b.text.toLowerCase()) ? 1 : -1;});
			//console.log(objectsFiltered);
		}
	}
	console.log('endsort');
	if(typesFiltered.length && types.length)
	{
		var tableNode=$('<table cellspacing=0 cellpadding=0 class=table_cont><tr><td><div class="table_filtered_cont"></div></td><td class=table_not_filtered_cont></td></tr></table>');
		tableNode.find('.table_filtered_cont').append(typesNodeCreate(typesFiltered, true));
		if(typesFiltered.length<=2) tableNode.find('.table_filtered_cont').addClass('_fix_on_scroll');
		tableNode.find('.table_not_filtered_cont').append(typesNodeCreate(types, true));
		console.log('end');
		return tableNode;
	}
	else
	{
		console.log('end');
		return typesNodeCreate(typesFiltered.length ? typesFiltered : types);
	}
}