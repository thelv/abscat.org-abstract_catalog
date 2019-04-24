var Parser=(function()
{		
	var str=false;
	var m={};
	
	var Parser=function(f, options_)
	{
		options_=options_ || {};
		return function(param, options)
		{						
			if(f.length<2){options=param;}
			options=options || {};	
			if(options.str) Parser.str(options.str);
			for(var i in options_)
			{
				if(options[i]==undefined) options[i]=options_[i];
			}				
			var m_=m;
			m={w: 1, str: function(){}};				

			var str_=str;
			if(! options.trim) str=str.trim();												
			m.str=function(){return str_.substr(0, str_.length-str.length).trim()};
							
			try
			{
				var isMatch=false;
				try
				{
					var res=(f.length>1 ? f(param, m) : f(m));
					isMatch=true;						
				}
				catch(e)
				{
					if(e.Parser=='return')
					{
						isMatch=true;
						res=e.res;
					}
					else if(! e.Parser || ! options.not) throw e;
				}
				if(isMatch && options.not) throw {Parser: true};
					
				if(options.returning!==undefined)
				{
					res=options.returning;
				}
			}
			catch(e)
			{
				if(e.Parser)
				{
					if(e.Parser=='critical')
					{
						if(! e.str) e.str=str;
						throw e;
					}
					else if(options.critical)
					{
						throw {Parser: 'critical'};
					}
					else if(options.necessary)
					{			
						throw {Parser: true};
					}						
				}
				else throw e;
			}
							
			m=m_;
			
			if(options.not)
			{
				str=str_;
				return ! isMatch;
			}
			
			if(isMatch)
			{			
				if(options.notEat) str=str_;
				var m_={};
				for(var i in m) if(+i==i) m_[+i+1]=m[i];												
				for(var i in m_) m[i]=m_[i];
				m[0]=res;
				return res;					
			}
			else
			{
				str=str_;
				return false;
			}
		}
	}
		
	Parser.str=function(str_)
	{
		if(str_!==undefined)
		{
			str=str_;
		}
		else return str;
	}
	
	Parser.return=function(res)
	{
		throw {Parser: 'return', res: res};
	}		
	
	Parser.throw=function(res)
	{
		throw {Parser: true};
	}		
	
	Parser.match=Parser(function(str_, m_)
	{
		var found=false;
		if(typeof str_=='string')
		{
			if(str.startsWith(str_))
			{
				found=true;
				var m=str_;											
			}					
		}
		else
		{
			var m=false;			
			if((m=str.match(str_)) && m[0]!==undefined)
			{
				var m=m[0];					
				found=true;	
			}
		}
		
		if(found)
		{
			str=str.substr(m.length);
			return m;				
		}
		else
		{
			Parser.throw();
		}				
	});
	return Parser;
})();

var match=(function()
{
	
	return_=Parser.return;		
	
	throw_=Parser.throw;
	
	var match={};
	
	match.str=Parser.match;
	
	match.space=Parser(function()
	{
		return match.str(/\s/, {trim: true});			
	}, {trim: true, notEat: true});
	
	match.spaceOrEnd=Parser(function()
	{
		match.str(/^(\s|$)/, {necessary: 1, trim: true});
		return true;
	}, {critical: 1, necessary: 1, trim: true, notEat: true});
	
	match.strQuote=Parser(function(m)
	{
		var res='';
		match.str('"', {necessary: 1});
		while(1) 				
			res+=match.str('\\\\', {returning: '\\'}) || match.str('\\"', {returning: '"'}) || match.str(/^[^"]/)
			||
		match.str('"', {critical: 1}) && 
		return_(res);					
	});
	
	match.strColumn=Parser(function(m)
	{
		return match.strQuote() || match.str(/^[^\s.,:"<>=]+/, {'necessary': 1});
	});
	
	match.strSingleQuote=Parser(function(m)
	{
		var res='';
		match.str("'", {necessary: 1});
		while(1) 				
			res+=match.str('\\\\', {returning: '\\'}) || match.str("\\'", {returning: "'"}) || match.str(/^[^']/)
			||
		match.str("'", {critical: 1}) && 
		return_(res);
	});
	
	match.number=Parser(function(m)
	{
		var points=0;
		var digits=0;
		while(1) 				
			! points && match.str('.') && ++points ||
			match.str(/^[\d]/) && ++digits
			|| 
		digits
		&& 
			return_(+m.str()) 
		||
			throw_();		
	});
	
	match.path=Parser(function(m)
	{					
		var res=path=[{connection: match.strColumn({necessary: 1}), type: 'object', path: false}];	
		while(1)
			match.str(/^(\.type|\.id)(\s|$)/, {not: 1}) &&
			match.str(/^[.:]/) && 
			match.strColumn({necessary: 1}) &&
			(path=path[0].path=[{connection: m[0], type: m[1]=='.' ? 'object' : 'connection', path: false}]) 
		||
		(/*res=Path.createFromPath(res))*/res=res) &&
		return_(res);
	});
	
	match.field=Parser(function(m)
	{			
		var res={param: 'text', path: match.path({necessary: 1})};
		match.str('.') &&
		match.str(/^(type|id)/, {critical: 1}) &&
		(res.param=m[0]);
		return res;
	});
	
	match.selectItem=Parser(function(m)
	{			
		var res={field: match.field({necessary: 1})};			
		match.space() && match.str(/^as\s/) &&
		(res.alias=match.strColumn());			
		match.space() && (res.join=match.str('join'));
		
		return res;
	});
	
	match.select=Parser(function(m)
	{				
		match.str('select', {necessary: 1});
		match.spaceOrEnd();
		var res=[];
		while(1)
			match.selectItem() && res.push(m[0]) && 
			match.str(',')
		||
		match.spaceOrEnd() &&
		return_(res);
	});
	
	match.whereItem=Parser(function(m)
	{
		var res={};
		Parser(function(m)
		{
			match.strColumn({necessary: 1});
			match.str(/^[.:]/, {not: 1, necessary: 1}); 
			res.aliasPossible=m[0]; 				
		}, {notEat: 1})();
		res.field=match.field({necessary: 1});
		match.str('exists')
		&&
			(res.type='exists')
		||
			match.str(/^[\>\<=]/)
			&&
				(res.type=m[0]) &&
				(res.value=match.number() || match.strSingleQuote({critical: 1}));
		return res;
	});
	
	match.where=Parser(function(m)
	{		
		match.str('where', {necessary: 1});
		match.spaceOrEnd();
		var res=[];
		while(1)
			match.whereItem() && res.push(m[0]) && 
			match.str(',')
		||
		match.spaceOrEnd() &&
		return_(res);
	});
	
	match.orderByItem=Parser(function(m)
	{			
		var res={};
		Parser(function(m)
		{
			match.strColumn({necessary: 1});
			match.str(/^[.:]/, {not: 1, necessary: 1}); 
			res.aliasPossible=m[0]; 				
		}, {notEat: 1})();
		res.field=match.field({necessary: 1});
		res.type=match.str(/^(number|string)/) || 'string';
		res.order=match.str(/^(asc|desc)/) || 'asc';			
		return res;
	});
	
	match.orderBy=Parser(function(m)
	{		
		match.str(/^order\s+by/, {necessary: 1});
		match.spaceOrEnd();
		var res=[];
		while(1)
			match.orderByItem() && res.push(m[0]) && 
			match.str(',')
		||
		match.spaceOrEnd() &&
		return_(res);
	});
	
	match.request=Parser(function(m)
	{
		var res={};
		res.select=match.select({critical: 1});
		res.where=match.where();
		res.orderBy=match.orderBy();
		match.str(/^$/, {critical: 1})
		return res;
	});
	
	return match;
})();