var buffer=
{
	element: null,
	hided: true,
	
	init: function()
	{
		var this_=this;
		this.node=$('#buffer');
		this.node.find('._clear').click(function()
		{
			this_.clear();
		});
		
		buffer.putFromCookie();				
		$(window).focus(function()
		{
			buffer.putFromCookie();
		});
	},				
	
	put: function(element, fromCookie)
	{
		var this_=this;
		if(! element)
		{
			this.clear();
			return;
		}
		this.element=element;						
		this.node.find('._id_value').text(element.id);
		this.node.find('._text_value').text(element.text);
		this.node.find('._type_value').text(element.type);
		if(true)//this.hided)
		{
			if(fromCookie)
			{
				this.node.show();
			}
			else
			{
				this.node.css({'display': 'block'})
				.animate({"background-color": 'rgba(200, 200, 200, 0.84)'}, 400, function()
				{
					this_.node.animate({"background-color": 'rgba(220, 220, 220, 0.94)'}, 300);
				});
			}
			this.hided=false;			
		}
		$.cookie('buffer', JSON.stringify(element), {path: '/'});
		//console.log($.cookie('buffer'));
	},
	
	putFromCookie: function()
	{
		try
		{
			var element=JSON.parse($.cookie('buffer'));
		}
		catch(e)
		{
			element=false;
		}
		buffer.put(element, true);
	},
	
	clear: function()
	{
		this.element=null;
		this.node.hide();
		this.hided=true;
		$.cookie('buffer', '', {path: '/'});
	},
	
	get: function()
	{
		return this.element;
	}
}