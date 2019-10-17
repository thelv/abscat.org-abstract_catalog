function arrayAvailableIndex(a)
{
	var m=0;
	for(var i in a) m=i;
	return ++m;
}

function dataGetTypeColor(data, type)
{
	var color='';
	for(var i in data.objects)
	{
		if(data.objects[i].type==type)
		{
			return data.objects[i].color;
		}
	}
}

var styling=
{
	setted: false,
	cached: false,
	style: {background: 'rgb(14, 61, 91) url(http://www.planwallpaper.com/static/images/cool-background.jpg)', textColor: '#ddd', linkColor: 'aaf', border: '#004'},
	
	set: function()
	{		
		return;
		if(! this.cashed)
		{
			var backgroundPos='';//parseInt(Math.random()*10000)+'px '+parseInt(Math.random()*10000)+'px';
			var backgroundPos2='';//parseInt(Math.random()*10000)+'px '+parseInt(Math.random()*10000)+'px';
			$('#css_head').html('#head > div{background:transparent !important;border-color:'+this.style.border+' !important}#head{color:'+this.style.textColor+' !important}#head a{color:'+this.style.linkColor+' !important}');
			$('head').append($('<style class=css_head>').html(''+
			'#head_background2{background:'+this.style.background+' '+backgroundPos+' !important'+'}'+
			'#head_background1{background:'+this.style.background+' '+backgroundPos2+' !important'+'}'));
			$('#head_background1').show();
			this.setted=true;
			this.cashed=true;
		}
		else if(this.setted)
		{
			/*var backgroundPos=parseInt(Math.random()*10000)+'px '+parseInt(Math.random()*10000)+'px';
			$('head').append($('<style class=css_head>').html(''+
			'#'+$('.head_background:visible').attr('id')+'{background:'+this.style.background+' '+backgroundPos+' !important'+'}'));
			var headBackgroundNodeHidden=$('.head_background:hidden').eq(0);
			$('.head_background:visible').hide().css({'background': this.style.background+' '+backgroundPos+' !important'});
			headBackgroundNodeHidden.show();			*/
		}
		else
		{			
			var backgroundPos='';//parseInt(Math.random()*10000)+'px '+parseInt(Math.random()*10000)+'px';
			$('#css_head').html('#head > div{background:transparent !important;border-color:'+this.style.border+' !important}#head{color:'+this.style.textColor+' !important}#head a{color:'+this.style.linkColor+' !important}');
			$('head').append($('<style class=css_head>').html(''+
			'#head_background2{background:'+this.style.background+' '+backgroundPos+' !important'+'}'));			
			$('#head_background2').hide().css({'background': this.style.background+' '+backgroundPos+' !important'});
			$('#head_background1').show();
			this.setted=true;
		}
	},
	clear: function()
	{
		$('head_background').hide();
		this.setted=false;
		$('#css_head').html('');
	}
}			

var state=
{
	page: false
}

var save=new (function()
{
	var timeout=false;
	
	this.save=function(catId, data)
	{
		clearTimeout(timeout);
		$('#save').show();
		$('#save').html('uploading...');
		dataExt.set(catId, data, function()
		{
			$('#save').html('uploaded<!--?-->');
			timeout=setTimeout(function(){$('#save').hide();$('#save').html('');}, 4000/*6500*/);
		});
		help.show(data, catId);
	}
})();

var dataExt=
{
	cache: 
	{
		data: {},		
		get: function(catId)
		{
			return this.data[catId] || false;	
		},
		set: function(catId, data)
		{
			this.data={};
			this.data[catId]=data;
		}
	},
	
	get: function(catId, callback)
	{		
		var cache=false;
		if(cache=this.cache.get(catId))
		{
			callback(cache);
			return;
		}
		
		if(catId==0)
		{
			var data=false;
			data=JSON.parse($.cookie('data_guest') || '{"objects": {}, "connections": {}}');			
			data=new Data(catId, data);
			callback(data);
			//callback(JSON.parse(localStorage.getItem('data_guest') || '{"objects": {}, "connections": {}}'));
			dataExt.cache.set(catId, data);
			return;
		}
		else
		{
			$.get('/ajax.php', {type: 'cat', cat_id: catId}, function(data)
			{			
				console.log(data);
				data=new Data(catId, data);
				dataExt.cache.set(catId, data);								
				callback(data);
			}, 'json');
		}		
	},
	set: function(catId, data, callback)
	{
		dataExt.cache.set(catId, data);
		
		if(catId==0)
		{
			$.cookie('data_guest', JSON.stringify(data.ext), {expires: 1000});
			//localStorage.setItem('data_guest', JSON.stringify(data));
			callback('OK');
		}
		else
		{
			$.post('/ajax.php?type=cat_save', {cat_id: catId, data: JSON.stringify(data.ext)}, function(res_)
			{
				if(res_=='OK')
				{
					callback('OK');
				}
				else
				{
					callback('ERROR');
				}							
			}, 'text');
		}
	}
}
			
var url=
{				
	langPrefix: '',
	
	set: function(urlData, state)
	{
		history.pushState(state, document.title=url.title(urlData), url.gen(urlData));
		
	},
	
	gen: function(urlData)
	{
		if(urlData.page=='cats')
		{
			var res='/cats';
		}
		else if(urlData.page=='cat')
		{
			res='/cat'+urlData.catId;
		}
		else if(urlData.page=='catObj')
		{
			res='/cat'+urlData.catId+'/obj'+urlData.objId;
		}
		else if(urlData.page=='about')
		{
			res='/about';
		}
		else if(urlData.page=='sql')
		{
			res='/cat'+urlData.catId+'/sql';
		}
		
		return url.langPrefix+res;
	},
	
	title: function(urlData)
	{
		if(urlData.page=='cats')
		{
			return 'All Catalogs | Abstract Catalog';
		}
		else if(urlData.page=='cat')
		{
			return 'Cat '+ +urlData.catId+' | Abstract Catalog';
		}
		else if(urlData.page=='catObj')
		{
			return 'Cat '+ +urlData.catId+' - Obj '+ +urlData.objId+' | Abstract Catalog';
		}
		else if(urlData.page=='about')
		{
			return 'Abstract Catalog';
		}
		else if(urlData.page=='sql')
		{
			return 'Cat '+ +urlData.catId+' - SQL | Abstract Catalog';
		}
	},
	
	loadByUrl: function(url_)
	{
		if(url_.indexOf('/ru/')==0)
		{
			url.langPrefix='/ru';
			url_=url_.substr(3);
		}
		
		var m=url_.substr(1).split('/');
		var mm=[];
		for(var i in m)
		{
			var mmm=m[i].match(/([^\d]+)([\d]*)/) || [];
			mm.push([mmm[1], mmm[2]]);
		}
		
		if(! mm[0] || ! mm[0][0])
		{							
			pages.about.go();
			return;
		}
		else if(mm[0][0]=='about')
		{
			var urlData={page: 'about'};
			pages.about.open();
		}
		else if(mm[0][0]=='cats')
		{
			var urlData={page: 'cats'};
			pages.cats.open();
		}
		else if(mm[0][0]=='cat')
		{
			if(mm[1] && mm[1][0]=='obj')
			{
				var urlData={page: 'catObj', catId: +mm[0][1], objId: +mm[1][1]};
			}
			else if(mm[1] && mm[1][0]=='sql')
			{
				var urlData={page: 'sql', catId: +mm[0][1]};
				pages.cats.open();
			}
			else
			{
				var urlData={page: 'cat', catId: +mm[0][1]};
			}
			
			if(urlData.catId==0 && user.id)
			{
				pages.cat.go(user.id);
				return;
			}			

			if(urlData.page=='cat')
			{						
				pages.cat.open(urlData.catId);
			}
			else if(urlData.page=='catObj')
			{						
				pages.catObj.open(urlData.catId, urlData.objId);
			}
			else if(urlData.page=='sql')
			{
				pages.sql.open(urlData.catId);
			}
		}
		if(urlData) document.title=this.title(urlData);
	}					
};

window.onpopstate=function(event)
{
	url.loadByUrl(window.location.pathname);
};

function fixOnCommentsRight()
{
	//$('#players').css('right', scrollBarWidth+'px');
	//$('#head').css('margin-left', '-'+scrollBarWidth+'px');
	//$('#head > div').css('padding-left', 10+scrollBarWidth+'px');
}

$(function()
{
	$('#initial_loading').hide();
	
	window.scrollBarWidth=$("#comments").width()-$("#commentsComments").width();
	fixOnCommentsRight();
	
	menu.init();
	buffer.init();
	$('#logout').click(function()
	{
		document.cookie='user_auth=';
		window.location='?logout=1';
	});
	
	$('#window_container ._button_cancel').click(windows.cancel);									
	
	url.loadByUrl(window.location.pathname);		
	
	$('#head_menu ._about').click(function()
	{
		pages.about.go();
	});
	
	$('#head_menu ._cats').click(function()
	{
		pages.cats.go();
	});
	
	$('#head_menu ._my_cat').click(function()
	{
		pages.cat.go(user.id);
	});
});	

var select=
{
	open: function()
	{
		//cursorWait(true, '.body_menu ._select', function()
		//{
			$('body').addClass('select');	
			//cursorWait(false);
			setTimeout(function()
			{
				$('#select').css({'display': 'inline-block'})
				.animate({"background-color": 'rgba(200, 200, 200, 0.84)'}, 300, function()
				{
					$(this).animate({"background-color": 'rgba(220, 220, 220, 0.94)'}, 300);					
				});
			});
		//});
	},
	
	close: function()
	{
		$('body').removeClass('select');
		$('#select').hide();
	}
}
$(function()
{
	$('.body_menu ._select').click(()=>{select.open();});
});

var help=
{	
	loginHided: false,
	show: function(data, catId)
	{
		$('.help').hide();
		$('#page_catalog').removeClass('_help_hide_table');	
		if(catId!=user.id) return;		
		var objectLength=lib.objectLength(data.objects);
		if(objectLength==0)
		{
			$('#page_catalog').addClass('_help_hide_table');	
			$('#help_create').css('display', 'inline-block');		
		}
		else if(objectLength==1)
		{
			$('#help_create_second').css('display', 'inline-block');
		}
		else if(lib.objectLength(data.connections)==0)
		{
			if(! buffer.get())
			{
				$('#help_connection').css('display', 'inline-block');			
			}
			else
			{
				$('#help_connection2').css('display', 'inline-block');
			}
		}
		else if(! lib.paramCookie('help_open_object_hide'))
		{
			$('#help_open_object').css('display', 'inline-block');
			var f=false;
			$('.table_type_object a').click(f=function()
			{
				lib.paramCookie('help_open_object_hide', 1);
				$('.table_type_object a').unbind('click', f);
			})
		}
		else if(catId==0 && ! this.loginHided)
		{
			$('#help_login').css('display', 'inline-block');
		}
		else
		{
			help={show: function(){}, hide: function(){}};
		}		
	},
	hide: function()
	{
		$('.help').hide();
	},
	loginHide: function()
	{
		$('#help_login').hide();
		this.loginHided=true;
	}
}

var toast=
{
	timeout: false,
	show: function(text)
	{
		$('#toast_text').text(text);
		$('#toast').css({opacity: 1});
		setTimeout(function(){$('#toast').hide().fadeIn(300);}, 0);
		if(this.timeout)
		{
			clearTimeout(this.timeout);
			clearTimeout(this.timeout2);
		}
		this.timeout2=setTimeout(function(){/*$('#toast').animate({opacity: '0.83'}, 800);*/}, 2400);
		this.timeout=setTimeout(function()
		{			
			$('#toast').fadeOut();
		}, /*4150*/3700);
	}
}

SqlEditor=function(node)
{
	var node_=node.get(0);
	node.keydown(function(e)
	{
		if(e.keyCode==9)
		{
			replace('tab');			
			e.preventDefault();
			e.stopPropagation();
		}
		/*else if(e.keyCode==13)
		{
			replace('enter');			
			e.preventDefault();
			e.stopPropagation();
		}*/
	});
	
	node.keydown(function(e)
	{
		//if((e.key.length==1 /*|| e.keyCode==13*/) && ! e.altKey && ! e.ctrlKey) setTimeout(function(){replace();}, 0);
			//replace();
	});
	
	this.getText=function()
	{
		var text=node_.innerText;
		text=text.replace(/⋂/g, '^');
		text=text.replace(/∈/g, ' e ');
		text=text.replace(/≡/g, '==');
		text=text.replace(/⋃/g, ' u ');
		
		return text;
	};	
	this.setText=function()
	{
		
	};
	var i;
	var replace=function(insertion)
	{
		var replace_=function(text, start)
		{			
		dddd=text+'';

			text=text.replace(/==>/g, '⟹');//'⟾');
			text=text.replace(/=>/g, '⟶');			
		//
			text=text.replace(/(\d+\.{0,1}\d+(?=[^\w\'])|(\||!|\*|\%|\?|\^|⋂|\$|∈|\/|alias|as|==|limit all|only|filter|u|offset|apply|if|limit|union|sort|order by|remove|distinct|connect|connection|having|select|not in|in|where|and|or|with|new|not|this)(?=[^\w])|⋃|⟶|⟾|⟹|\[|\]|:| - |--[^\s]+|\se\s|\s\!e\s|\.\.|\.|\,|\(|\)|≡|=|∉|\<|\>)/g, function(m)
			{
				console.log(m);
				var n=m.substr(0);
				if(n=='%') return '<span class="_bracket">%<!--∇--></span>';
				if(n.match(/\s!e\s/)) return '<span class="_bracket"> ∉ </span>';
				if(n=='?') return '<span class="_bracket">?</span>';
				if(n=='^') return '<span class="_bracket">⋂</span>';
				if(n=='$') return '<span class="_bracket">$</span>';
				if(n=='[') return '<span class="_brackket" style="color:#888;font-weight:normal;margin-left:2px">[</span>';
				if(n==']') return '<span class="_brlacket" style="color:#888;font-weight:normal;">]</span>';
				if(n=='==') return '<span class="_bracket">≡</span>';
				if(n=='*') return '<span class="_bracket">*</span>';
				if(n.match(/--[^\s]+/)) return '<span style="color:#336a33">'+n+'</span>';//6a6
				if(n=='u') return '<span class="_bracket">⋃</span>';				
				if(n.match(/\se\s/)) return '<span class="_bracket"> ∈ </span>';				
				if(n.match(/\s\!e\s/)) return '<span class="_bracket"> ∉ </span>';				
				if(n.match(/\s\-\s/)) return '<span class="_bracket"> - </span>';				
				if(['offset','limit all','only', 'sort','alias','not in','order by','$', '*', '%','?', 'limit','if','filter','apply','select','connect','connection', 'remove', 'distinct', 'union','where','connection','and','in','as','or', 'new', 'this', 'with', 'not', 'having'].indexOf(m.substr(0))!=-1) return '<span class="_bold">'+m.substr(0)+'</span>';
				
				if(m=='.') return '<span style="margin-right:1px" class="_bracket _point">.</span>';
				
				if(['(',')'].indexOf(m[0])!=-1) return '<span class="_bracket _bracket_ _point">'+m+'</span>';
				if(['!', '|','⋂', '⋃', '≡','∈','∉','..','⋃','[',']','/', '=', ',', ':','{','}','⟶','⟾','⟹'].indexOf(m)!=-1) return '<span class="_bracket _point">'+m+'</span>';
				
				if(m[0]=='<') return '<span class="_bracket _point">&lt;</span>';
				if(m[0]=='>') return '<span class="_bracket _point">&gt;</span>';
				
				return '<span class="_decimal">'+m+'</span>';
			});
			text=text.replace(/\{\}/g, '<span style="color:#000080;padding-left:1px;">{}</span>');
			text=text.replace(/\'.*?\'/g, function(m)
			{
				return '<span class="_literal">'+m+'</span>';
				
			})
			
			text=text.replace(/article_params/g, function(m)
			{
				return '<span  class="_function">'+m+'</span>';
				
			})
			
			text=text.replace(/rec(?=[\W])/g, function(m)
			{
				return '<span  class="_function">'+m+'</span>';
				
			})
			text=text.replace(/recursion(?=[\W])/g, function(m)
			{
				return '<span  class="_function">'+m+'</span>';
				
			})
			
			/*text=text.replace(/\W\d+\.{0,1}\d+\W/g, function(m)
			{
				
				
			})*/
			
			text=text.replace(/\n$/g, '');		
			
			text=text.replace(/(^\n)/g, '<br></div><div>');
			text=text.replace(/(\n(?=(\n|$)))/g, '</div><div><br>');			
			//if(! start) text=text.replace(/(\n(?=$))/g, '</div><div><br>');			
			text=text.replace(/\n/g, '</div><div>');
			text=text.replace(/\t/g, '<span style="white-space:pre" id="ttt">	</span>');
			//text=text.replace(/\t/g, '&nbsp; &nbsp;&nbsp;');
			//text=text.replace(/_/g, '');
			
			/*text=text.replace(/\(/g, '<span style="margin-right:0px" class="_bracket">(</span>');			
			text=text.replace(/\)/g, '<span style="margin-left:0px" class="_bracket">)</span>');			
			text=text.replace(/\./g, '<span style="margin-right:2px" class="_bracket _point">.</span>');			
			text=text.replace(/\,/g, '<span class="_bracket _point">,</span>');			
			text=text.replace(/where/g, '<span class="_bold">where</span>');			
			text=text.replace(/select/g, '<span class="_bold">select</span>');			
			text=text.replace(/and/g, '<span class="_bold">and</span>');			
			text=text.replace(/or/g, '<span class="_bold">or</span>');	
			text=text.replace(/\=/g, '<span style="margin-right:0px" class="_bracket">=</span>');		*/
			
			
			return text;
		}
		
		var selection=window.getSelection();
		
		/*var m=false;
		var range=selection.getRangeAt(0);
		var cont=range.startContainer;	
		var len=range.startOffset;
		while(true)
		{
			if(cont==node_) break;
			if(m=cont.previousSibling)
			{
				cont=m;
			}
			else 
			{
				cont=cont.parentElement;
				if(! cont) break;
				continue;
				//if(m=cont.lastChild) cont=m;
			}
			
			
			if(cont.tagName=='DIV')
			{
				if(cont.innerHTML) len++;
			}
			else 
			{
				m=cont.nodeValue || cont.innerText;
				len+=m.length;
			}
		}
		console.log(len);*/
		
		var text=node_.innerText;
		text1=text;//.substr(0,len);
		if(insertion=='tab') text1+='\t';
		else if(insertion=='enter') text1+='\n';
		//text2=text.substr(len);
		text1=replace_(text1, 1);
		//text2=replace_(text2);
		if(m=document.getElementById('sql_editor_cursor'+i)) m.remove();
		console.log(ddd=text);
		//alert(1);
		i++;
		node_.innerHTML='<div>'+text1+/*'<span id="sql_editor_cursor'+i+'">_</span>'+text2+*/'</div>';
		console.log('<div>'+text1+/*'<span id="sql_editor_cursor'+i+'">_</span>'+text2+*/'</div>');
				
		
		/*range=document.createRange();
        range.collapse(false);        
        selection.removeAllRanges();
        range.setStart(m=document.getElementById('sql_editor_cursor'+i), 1);
		range.setEnd(m, 1);
        selection.addRange(range);
		//m.remove();*/
	};
	this.replace=replace;
}

$(function()
{
	//$.getScript('//ulogin.ru/js/ulogin.js');//, scriptULoginOnLoad);
	$.getScript('/js/jquery.mousewheel.js', scriptMouseWheelOnLoad);
	$.getScript('https://www.youtube.com/iframe_api');
	$.getScript('/js/jquery.color.js');
	//$.getScript('http://vk.com/js/api/share.js?10', scriptVkShareOnLoad);					
	
	$(window).resize(resize);
	var resize=function()
	{
		$('.__window_width').css('width', $(window).width())
	};
	resize();
});