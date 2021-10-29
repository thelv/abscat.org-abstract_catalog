var sqlRequest=(function()
{
	//var 
	Parser=(function()
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
				if(! options.notTrim) str=str.trim();												
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
		
	//var 
	match=(function()
	{
		
		return_=Parser.return;		
		
		throw_=Parser.throw;
		
		var match={};
		
		match.str=Parser.match;
		
		match.space=Parser(function()
		{
			return match.str(/\s/, {notTrim: true});			
		}, {notTrim: true, notEat: true});
		
		match.spaceOrEnd=Parser(function()
		{
			match.str(/^(\s|$)/, {necessary: 1, notTrim: true});
			return true;
		}, {critical: 1, necessary: 1, notTrim: true, notEat: true});
		
		match.word=Parser(function(p, m)
		{
			match.str(p, {necessary: 1});
			match.str(/^(\W|\Z)/, {notEat: 1, notTrim: 1, necessary: 1});
			return m[1];
		});
		
		match.strQuote=Parser(function(m)
		{
			var res='';
			match.str('"', {necessary: 1});
			while(1) 				
				res+=match.str(" ", {notTrim: 1}) || match.str('\\\\', {returning: '\\'}) || match.str('\\"', {returning: '"'}) || match.str(/^[^"]/)
				||
			match.str('"', {critical: 1}) && 
			return_(res);					
		});
		
		match.strColumn=Parser(function(m)
		{
			return match.strQuote({notTrim: 1}) || match.str(/^[^\s.,:"\)^\/|<>=]+/, {notTrim: 1, necessary: 1});
		});
		
		match.strSingleQuote=Parser(function(m)
		{
			var res='';
			match.str("'", {necessary: 1});
			while(1) 				
				res+=match.str(" ", {notTrim: 1}) || match.str('\\\\', {returning: '\\'}) || match.str("\\'", {returning: "'"}) || match.str(/^[^']/)
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
		
		match.bracketSelect=Parser(function(m)
		{
			var func=Parser(function()
			{
				var r=match.str(/^[\w\-_]+/i, {necessary: 1});
				match.str('==', {necessary: 1});
				return r;
			})();
			match.str('(', {necessary: 1});
			var a=match.select(0, {critical: 1});
			match.str(')', {critical: 1});
			return {type: '(', a: a, childSelectsInited: [], func: func};
		});
		
		match.selectItem=Parser(function(p, m)
		{
			match.word('as', {not: 1, necessary: 1});
			match.word('u', {not: 1, necessary: 1});
			match.word('U', {not: 1, necessary: 1});
			
			match.str('!') && return_({type: '!', a: match.select({inner: 1}, {critical: 1})});
			if(match.bracketSelect())
			{
				var r=m[0];
				r.conds=[];
			}
			else
			{		
				var r={type: 'field', conds: [], copy: "0"};
				r.distinct=match.word('distinct');
				r.concat=match.word('concat');
				r.count=match.word('count');
				r.startPoints=0;
				match.str(/^\s*/);
				while(match.str('.', {notTrim: 1})) ++r.startPoints;
				r.connection=match.strColumn({notTrim: 1});
				match.str(/^\s*/);
				match.str('/') && (match.str(/^\s*/), (r.connectionOpposite=match.strColumn()));
			}
		
			while(1)
				match.str(/^(E(?=\W)|\!E(?=\W)|\:|\!\:|\=\>)/i) &&
					r.conds.push({type: m[0].toLowerCase(), cont: 
						(match.bracketSelect() && 
							[{selectItem: m[0]}]
						|| 
							match.select({inner: 1}, {critical: 1})
						)
					})
				|| match.str(/^(\<\>|\=|\:\=|\<\=|\>\=|\>|\<)/) &&					
					r.conds.push({type: m[0], cont: match.strSingleQuote() || match.number({critical: 1})})
				||
					match.word('remove') && (r.remove=true)
				||
					(match.str('.', {notTrim: 1}) && (r.notSelect=true))
				||
					(! p.innerForAlias && match.word('as') && (r.alias=match.strColumn({critical: 1})))
				||
					m.str() && match.select({inner: 1, innerForAlias: r.notSelect}) && (r[(! r.alias) ? 'childs' : 'aliasChilds']=m[0])
			||
			((m.str())//(r.type!='field' || r.connection || r.connectionOpposite) 
			?
				(
					//(r.childs=match.select({inner: 1, innerForAlias: r.notSelect})) && //(console.log('PPPPPP:', Parser.str()) || 1) &&
						//(! p.innerForAlias && match.word('as') && (r.alias=match.strColumn({critical: 1})))
					//, 
					return_(r)
				)
			: 
				throw_());
		});
		
		
		match.select=Parser(function(p, m)
		{				
			p=p || {};
			var res=[];						
			while(1)
			{
				var item={};
				(item.selectItem=match.selectItem(p, {necessary: 1})) /*&& (item.selectItem.childs=match.select({inner: 1, innerForAlias: item.selectItem.notSelect}))*/;
				res.push(item);
				if(p.inner && match.str(/^(U(?=\W)|\,|\||\^)/i, {notEat: 1}) || match.str(')', {notEat: 1})) break;
				if(match.str(/^(U(?=\W)|\,|\||\^)/i)) item.delimeter=m[0].toLowerCase(); else break;
			}			
			if(res.length==1 && ! res[0].selectItem) throw_();
			return res;
		});		
		return match;
	})();
//}