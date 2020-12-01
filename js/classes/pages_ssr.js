var pages=
{
	about: new (function()
	{		
		var this_=this;												
		
		/*$('#page_about ._my_cat_link').click(function()
		{
			pages.cat.go(user.id);
		});
		
		$('#page_about ._cats_link').click(function()
		{
			pages.cats.go();
		});*/
		
		this.go=function()
		{
			url.set({page: 'about'});
			this.open();
		};
		
		this.open=function()
		{		
			styling.clear();
		
			state.page='about';			
			$('.page').hide();					
			$('#page_about').show();
			if(window.aa) aa(); else alert(1);
		};
	})(),
	
	cats: new (function()
	{	
		var this_=this;								
		
		this.go=function()
		{
			url.set({page: 'cats'});
			this.open();
		};
		
		this.open=function()
		{		
			styling.clear();
		
			state.page='cats';
			$('.page').hide();							
			$('#page_cats .body_body').html('');
			$('#page_cats .body_loading').show();
			$('#page_cats').show();			
			
			$.get('/ajax.php?type=cats', null, function(data)
			{
				$('#page_cats .body_loading').hide();
				for(var i in data)
				{
					var cat=data[i];
					var node=$('<div class=_cat><span></span></div>');
					(function(cat){
						/*node.find('span').text('Catalog '+cat.user_id).click(function()
						{
							pages.cat.go(cat.user_id);
						});*/
					})(cat);
					$('#page_cats .body_body').append(node);
				}
				if(window.aa) aa();
			}, 'json');
		};
	})(),
	
	cat: new (function()
	{
		var catIdPrev=false;
		var filterPrev={text: '', type: ''};
		var this_=this;
		var data=null;
		var catId=false;	
		/*var*/ fixOnScrollNodes=false;
		
		$(function()
		{
			$('#page_catalog .object_filter form').submit(function()
			{
				filter();
				return false;
			})
			.on('reset', function()
			{
				setTimeout(function()
				{
					filter();
				}, 0);
			});
			
			/*$('#page_catalog .body_menu ._object_new').click(function()
			{
				Object_.actions.create(null, catId, data, function(objectId)
				{									
					//pages.catObj.go(catId, objectId);
					filter(true);
					//toast.show('object created');
				}); 
			});*/	

			/*$('#page_catalog .body_menu ._sql').click(function()
			{
				pages.sql.go(catId);
			});*/															
		});
		
		this.go=function(catId)
		{
			url.set({page: 'cat', catId: catId});
			this.open(catId);
		}
		
		this.open=function(catId_)
		{		
			fixOnScrollNodes=$('#page_catalog ._fix_on_scroll');
		
			help.hide();
			styling.set();
			
			state.page='cat';
			catId=catId_;
			$('.page').hide();
			fixOnScroll();
			//$('#page_catalog .object_table').html('');
			$('#page_catalog .body_head').text((catId==user.id) ? '$@{My catalog}' : '$@{Catalog} '+ +catId);
			$('#page_catalog').addClass('_object_filter_hided');			
			$('#page_catalog').show();						
			
			dataExt.get(catId, function(data_)
			{
				data=data_;				
				help.show(data, catId);
				$('#page_catalog').removeClass('_object_filter_hided');
				filter();
				help.show(data, catId);		
				if(window.aa) aa(); else alert(1);
			});			
			
			$('#page_catalog .body_menu ._backup').attr('href', '/ajax.php?type=cat&cat_id='+catId).attr('download', 'abscat.org - catalog #'+catId+' - backup.txt');
		}
								
		//var
		filter=function(forceUpdate)
		{
			var type=$('#page_catalog .object_filter_type').val();
			var text=$('#page_catalog .object_filter_text').val();
			
			if(forceUpdate || ! (catIdPrev===catId && filterPrev.type==type && filterPrev.text==text))
			{
				$('#page_catalog .object_table').html('');							
				$('#page_catalog .body_loading').show();			
				setTimeout(()=>
				{
					$('#page_catalog .body_loading').hide();
					$('#page_catalog .object_table').append(table(data, catId, type, text));				
					catIdPrev=catId;
					filterPrev={type: type, text: text};
					fixOnScrollNodes=$('#page_catalog ._fix_on_scroll');
				});
			}
			else			
			{
				$('#page_catalog').addClass('_object_filter_hided');
				$('#page_catalog .body_loading').show();
				setTimeout(()=>
				{
					$('#page_catalog').removeClass('_object_filter_hided');
					$('#page_catalog .body_loading').hide();
				})
			}
		}					
	})(),
	
	catObj: new (function()
	{
		var this_=this;
		var catId=false;
		var objId=false;												
		
		$(function()
		{
			//$('#page_catalog_object .body_menu > ._back').click(function(){pages.cat.go(catId);});
			//$('#page_catalog_object .body_menu > ._back_history').click(function(){history.back();});
		});
		
		this.go=function(catId, objId)
		{
			url.set({page: 'catObj', catId: catId, objId: objId}, {pageBack: state.page});
			this.open(catId, objId);
		}
		
		this.open=function(catId_, objId_)
		{				
			styling.set();
		
			if(history.state && history.state.pageBack=='catObj')
			{
				$('#page_catalog_object').addClass('_back_history_showed');
			}
			else
			{
				$('#page_catalog_object').removeClass('_back_history_showed');
			}
		
			state.page='catObj';						
			catId=catId_;
			objId=objId_;
			$('.page').hide();
			$('#page_catalog_object .body_head').text(((catId==user.id) ? '$@{My catalog}' : ('$@{Catalog} '+ +catId))+' - $@{Object}');
			$('#page_catalog_object .body_body').html('');
			$('#page_catalog_object .body_loading').show();
			$('#page_catalog_object').show();							
			
			setTimeout(()=>
			{
				dataExt.get(catId, function(data_)
				{								
					data=data_;
					$('#page_catalog_object .body_loading').hide();
					$('#page_catalog_object .body_body').append((new Branch(data, catId, objId)).node);													
					if(window.aa) aa(); else alert(1);
				});
			});
		}
	})(),
	
	sql: new (function()
	{
		var this_=this;
		var catId=false;												
		var sqlEditor=false;
		
		$(function()
		{
			//$('#page_sql .body_menu > ._back').click(function(){pages.cat.go(catId);});
			/*$('#page_sql ._execute').click(function(){
				sqlEditor.replace();
				$('#page_sql ._result').html('');
				dataExt.get(catId, function(data)
				{
					var resultData=sqlRequest(sqlEditor.getText());
					//alert(1);
					var resultNode=resultTable(resultData.columns, resultData.rows, catId);
					//var resultNode=resultTableHTML(resultData, catId);
					//alert(2);
					$('#page_sql ._result').append(resultNode);
				});
			});*/
			sqlEditor=new SqlEditor($('#page_sql .sql_editor'));			
		});
		
		this.go=function(catId)
		{
			url.set({page: 'sql', catId: catId}, {pageBack: state.page});
			this.open(catId);
			this.open(catId);
		}
		
		this.open=function(catId_)
		{			
			fixOnScrollNodes=$('#page_sql ._fix_on_scroll');		
			styling.set();				
		
			state.page='sql';						
			catId=catId_;
			$('.page').hide();
			$('#page_sql .body_head').text(((catId==user.id) ? '$@{My catalog}' : ('$@{Catalog} '+ +catId))+' - SQL');
			//$('#page_sql .body_body').html('');
			//$('#page_sql ._result').html('');
			$('#page_sql .body_loading').show();
			$('#page_sql').show();	
			
			setTimeout(()=>
			{
				dataExt.get(catId, function(data_)
				{								
					data=data_;
					$('#page_sql .body_loading').hide();
					//$('#page_sql .body_body').append(table(data, catId));								
				});
			});
		}
	})()
}

var fixOnScrollNodes=false;
$(window).scroll(function(e)
{
	if(state.page=='cat' || state.page=='sql')
	{									
		fixOnScroll();
	}
});
var	fixOnScroll=function()
{
	fixOnScrollNodes.css('left', $('body').scrollLeft()+'px');
	//$('.table_filtered_cont').css('left', $('body').scrollLeft()+'px');
}