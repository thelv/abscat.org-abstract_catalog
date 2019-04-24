var menu=
{
	node: null,				
	elementNode: null,
	
	init: function()
	{
		this.node=$('#menu');
	},
	
	openOrClose: function(elementNode, options)
	{						
		this.node.html('');
		for(var i in options)
		{
			(function(i)
			{
				if(options[i].separ)
				{
					menu.node.append
					(
						$('<div class=_separ></div>')												
					);
				}
				else
				{
					menu.node.append
					(
						$('<div>'+options[i].text+'</div>')
						.click(function(e)
						{
							e.stopPropagation();
							options[i].action();
							menu.close();
						})							
					);
				}								
			})(i);
		}
		var close=(this.elementNode && this.elementNode.get(0)==elementNode.get(0));
		if(this.elementNode)
		{						
			this.close();
		}
		if(! close)
		{
			$('#tree').addClass('menu_opened');						
			this.elementNode=elementNode;
			//elementNode.prepend(this.node);
			var menuHeight=menu.node.height();
			var menuWidth=menu.node.width();
			var elemPos=elementNode.offset();
			var elemY=elemPos.top-$(window).scrollTop();							
			var elemHeight=elementNode.height();
			var elemWidth=elementNode.width();
			var windowHeight=$(window).height();
			var menuBottomY=windowHeight-elemY-22-menuHeight;
			var menuTopY=elemY-menuHeight;						
			menu.node.css('left', Math.max($('body').scrollLeft(), elemPos.left+elemWidth-menuWidth+20)+'px');
			if(menuBottomY<0 && menuTopY>0)
			{
				//menu.node.css('margin-top', -menuHeight-5+'px');
				menu.node.css('top', elemPos.top-menuHeight-3+'px');
			}
			else
			{
				//menu.node.css('margin-top', '22px');
				menu.node.css('top', elemPos.top+24+'px');
			}
			menu.node.show();
			setTimeout(function()
			{
				menu.closeBind();
			},0);
		}
	},
								
	close: function()
	{
		$('#tree').removeClass('menu_opened');
		this.elementId=0;
		this.parentId=0;
		this.elementNode=null;
		this.node.hide();
		this.closeUnbind();
	},
	closeForBind: function(){menu.close();},
	closeBind: function()
	{
		$('html').click(menu.closeForBind);
	},
	closeUnbind: function()
	{
		$('html').unbind('click', this.closeForBind);
	}
}