lib=
{
	pushInChild: function(a, key, init, elem, elemKey)
	{
		var m=(a[key]=a[key] || init);
		if(elemKey) m[elemKey]=elem; else if(elem) m.push(elem);
		return m;
	},
	
	selectFill: function(sel, opts)
	{					
		var html='';
		for(var i in opts)
		{
			html+='<option value='+opts[i].value+'>'+opts[i].text+'</option>';
		}
		sel.html(html);
	},
	
	arrayValues: function(a)
	{
		var b=[];
		for(var i in a) b.push(a[i]);					
		return b;
	},
	
	escapeHtml: function(text, invert)
	{
		if(invert)
		{
			return text
				.replace(/\&amp\;/g, "&")
				.replace(/\&lt\;/g, "<")
				.replace(/\&gt\;/g, ">")
				.replace(/\&quot\;/g, '"')
				.replace(/\&\#039\;/g, "'")
			;
		}
		else
		{
			return text
				.replace(/&/g, "&amp;")
				.replace(/</g, "&lt;")
				.replace(/>/g, "&gt;")
				.replace(/"/g, "&quot;")
				.replace(/'/g, "&#039;")
			;
		}						
	},
	
	paramCookie: function(param, value)
	{
		if(value===undefined)
		{
			return $.cookie('param_'+param);
		}
		else
		{
			$.cookie('param_'+param, value, {expires: 1000});
		}
	},
	
	cookie: function(param, value)
	{
		if(value===undefined)
		{
			return JSON.parse($.cookie('param_'+param));
		}
		else
		{
			$.cookie('param_'+param, JSON.stringify(value), {expires: 1000});
		}
	},
	
	wordEnding: function(n, form)
	{
		var n1=n % 10;
		var n2=parseInt(n/10);
		if(n2==1)
		{
			return 'ов';
		}
		else if(n1==1)
		{
			return form==1 ? 'а' : '';
		}
		else if(n1!=0 && n1<5)
		{
			return form==1 ? 'ов' : 'а';
		}
		else
		{
			return 'ов'; 
		}
	},
	
	seconds: function()
	{
		return parseInt((new Date()).getTime()/1000);
	},
	
	objectLength: function(o)
	{
		var l=0;
		for(var i in o)
		{
			l++;
		}
		return l;
	},
	
	objectEmpty: function(o)
	{
		for(var i in o) return false;
		return true;
	},
	
	escapeForJs: function(str)
	{
		return str.replace(/['"\r\n]/g, '');
	}
}