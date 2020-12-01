var player=
{
	state: 'closed',
	stateHidedAuto: false,
	stateWasClosedByUserOnceAndNotOpened: false,
	leaveTimeoutEndTime: 0,
	q: false,
	
	open: function(q)
	{
		q=q.replace(/_/g, ' ');
		player.q=q;
		player.hide(false);
		$('#player_head ._head_title').text(q);
		$('#player_bar span').text(q);
		vk.open(q, true);
		youtube.open(q);
		this.leave(false, 10000);
	},
	
	hide: function(hideOrShow, byUser)
	{							
		if((player.state=='closed' || player.state=='hided') && hideOrShow) return;
		player.state=hideOrShow ? 'hided' : 'showed';						
		if(hideOrShow)
		{
			if(byUser)
			{
				
				$('#player_bar').addClass('_locked');
				player.stateHidedAuto=false;
			}
			else
			{
				$('#player_bar').removeClass('_locked');
				player.stateHidedAuto=true;
			}							
			$('#players').addClass('_player_hided');
			$('#player').hide();
			$('#player_bar').show();
		}
		else
		{
			player.leave();
			$('#players').removeClass('_player_hided');
			$('#player').show();
			$('#player_bar').hide();
		}
	},
	
	stop: function()
	{
		player.state='closed';
		youtube.stop();
		vk.stop();
	},
	
	stop: function()
	{
		youtube.stop();
		vk.stop();
	},
	
	leave: function(enterOrLeave, timeout)
	{		
		timeout=timeout || 5000;
		if(enterOrLeave) 
		{
			if(player.leaveTimeout)
			{
				player.leaveTimoutDisabled=true;
				//clearTimeout(player.leaveTimeout);
				//player.leaveTimeout=false;
			}
			return;
		}
		else
		{
			player.leaveTimoutDisabled=false;
		}																					
	
		//if(! player.stateWasClosedByUserOnceAndNotOpened) return;						
		var time=new Date().getTime();
		var leaveTimeoutEndTime=time+timeout;
		if(player.leaveTimeout)
		{		
			if(time+timeout<player.leaveTimeoutEndTime)
			{
				return;
			}
			clearTimeout(player.leaveTimeout);
			//player.leaveTimeout=false;
		}
		player.leaveTimeoutEndTime=leaveTimeoutEndTime;
		player.leaveTimeout=setTimeout(function()
		{				
			if(youtube.state!='showed' && ! player.leaveTimoutDisabled) 
			{												
				player.hide(true);
			}
			
		}, timeout);
	}
}
$(function()
{
	/*$('#player_head ._action_hide').click(function(){player.stateWasClosedByUserOnceAndNotOpened=true;player.hide(true, true);});
	$('#player_bar ._action_open').click(function(){player.stateWasClosedByUserOnceAndNotOpened=false;player.hide(false);});
	$('#player_bar ._action_stop').click(function(){player.stop();});
	$('#player_bar ._action_close').click(function(){player.state='closed';player.stop();$('#player_bar').hide()});
	$('#player').mouseleave(function(){player.leave();});
	$('#player_bar').mouseenter(function(){if(player.stateHidedAuto){player.hide(false);}});
	$('#player').mouseenter(function(){player.leave(true);});
	$('html').click(function(e){if($(e.target).is('.table_cont td, body, html') && ! menu.elementNode && youtube.state!='showed') player.hide(true);})*/
})


var vk=
{
	window: false,
	windowWas: false,
	windowUrlLast: false, 
	//windowName: 0,
	q: false,
	qOpened: false,
	
	open: function(q, silent)
	{												
		if(q)
		{
			$('#players').addClass('_vk_showed');
			this.q=q;
		}
		else
		{
			q=this.q;
		}
		
		if(silent) return;
		
		if(this.window && ! this.window.closed && this.q==this.qOpened)
		{
			this.window.focus();
			return;
		}
		
		var width=500;
		var height=340;
		var offsetVk=$('#player_head').offset();
		var top=offsetVk.top-height;					
		//var top=window.screen.availHeight-height-57-20-57;
		var left=window.screen.availWidth-width;
		//var windowPrev=this.window;
		//setTimeout(function(){
			
		var top=$(window).height()-(youtube.state=='hided' ? 141 : (youtube.state=='showed' ? 294 : 91))-height;
		
		var p=JSON.stringify(
		{
			width: width,
			height: height,
			top: top,
			left: left,
			url: 'https://m.vk.com/audio?q='+encodeURIComponent(q)
		});
		vk.window=window.open('/vk_redir.php#'+encodeURIComponent(p), 'vk'/*+(++window.name)*/, 'width='+width+',height='+height+',top='+top+',left='+left);
		this.qOpened=this.q;
		//}, 0);
		//this.window.focus();
		//setTimeout(function(){vk.window.focus();}, 1000);
		//setTimeout(function(){vk.window.focus();}, 1000);
		//if(windowPrev) this.stop();
		//$('#vk ._action_youtube').unbind('click').click(function(){youtube.open(q);});
		this.windowWas=true;
		/*window.moveTo(left, top);
		window.resizeTo(width, height);
		setTimeout(function(){alert($(this.window).height());}, 1000);
		window.resizeTo(width,  height+height-$(this.window).height());*/
	},
	
	close: function(q)
	{
		$('#players').removeClass('_vk_showed');
		vk.stop();
		$('#vk').hide();
	},
	
	stop: function()
	{
		if(this.windowWas && (! this.window || this.window.closed)) return;
		var width=500;
		var height=330;
		var top=window.screen.availHeight-height-57-20;
		var left=window.screen.availWidth-width;
		//var windowPrev=this.window;
		this.window=window.open('https://abscat.org/vk_closed.php', 'vk'/*+(++window.name)*/, 'width='+width+',height='+height+',top='+top+',left='+left);
		//this.window=window.open('https://abscat.org/vk_closed.php', 'vk', 'width=500,height=400');							
		this.window.close();
	},
	
	windowOpen: function()
	{
		if(! this.window || this.window.closed)
		{
			this.open(this.q);
		}
		else
		{
			this.window.focus();
		}
	}					
}
$(function()
{
/*	$('#vk ._action_open_vk').click(function(){vk.windowOpen();});
	$('#vk ._action_stop').click(function(){vk.stop();});					
	$('#vk ._action_close').click(function(){vk.close();});	
	$('#vk ._action_play').click(function(){youtube.stop();vk.open();});	*/
})

var youtube=
{	
	state: 'hided',
	q: false,
	hintTimeout: false,
	hintTimeoutHover: false,
	player: false,
	
	open: function(q)
	{		
		//vk.close();
		var this_=this;
		$('#youtube_').show();
		$('#players').addClass('_youtube_showed');
		$.get('https://www.googleapis.com/youtube/v3/search?type=video&part=snippet&key=AIzaSyD_vcwc1q5ozgU7THGtgRWX3B_GGnywijM', {q: q}, function(data)
		{
			/*windows.youtube()
			{
			}*/
			if(data.items && data.items[0])
			{																				
				var video=data.items[0].id.videoId;
				var title=data.items[0].snippet.title
				
				if(this_.player) this_.player.destroy();
				$('#youtube_ option').text(title);
				$('#youtube_ select').attr('title', title);
				$('#youtube_ ._hint').text(title);//+' (hover mouse to control)');
				$('#youtube_ ._hint_video').text(title).removeClass('_hided');
				if(this_.hintTimeout) clearTimeout(this_.hintTimeout);
				this_.hintTimeout=setTimeout(function(){$('#youtube_ ._hint_video').addClass('_hided');}, 6000);
				//$('#youtube_ ._video iframe').attr('src', 'https://www.youtube.com/embed/'+video+'?autoplay=1');
				$('#youtube_ ._hint').removeClass('_hided_first');
				$('#youtube_').attr('state_play', 'loading');								
				
				youtubeApiPromise.then(function()
				{												
					this_.player=new YT.Player('youtube_iframe_cont', 
					{	
						width: '360px',
						height: '203px',
						videoId: video,
						playerVars: {autoplay: 1},
						events: 
						{
							onReady: function()
							{
								if($('#youtube_').attr('state_play')=='stopped')
								{
									this_.player.stopVideo();
								}
							},
							onStateChange: function(state)
							{
								console.log(state.data);
								var state=state.data;
								if(state==0 || state==2)
								{
									$('#player_bar span').text(player.q);
									$('#youtube_').attr('state_play', 'stopped');
								}
								else if(state==1)
								{
									$('#player_bar span').text(title);
									$('#youtube_').attr('state_play', 'playing');
								}
							}
						}
					});
				});
			}
		});
		
		/*$('#youtube_ ._action_vk').unbind('click').click(function(){youtube.stop();vk.open(q);});*/
	},
	
	stop: function()
	{
		$('#youtube_').attr('state_play', 'stopped');
		try{this.player.pauseVideo();}catch(e){}
	},
	
	close: function(q)
	{						
		$('#players').removeClass('_youtube_showed');
		$('#youtube_ ._video iframe').removeAttr('src');
		$('#youtube_').hide();
	},
	
	hide: function(q)
	{
	},
	
	show: function(q)
	{
	}
}
var youtubeApiPromise=new Promise(function(then)
{
	window.onYouTubeIframeAPIReady=then;
});
$(function()
{
	/*$('#youtube_ ._action_show').click(function(){$('#youtube_').removeClass('_hided');youtube.state='showed';});
	$('#youtube_ ._action_hide').click(function(){$('#youtube_').addClass('_hided');youtube.state='hided';});
	$('#youtube_ ._action_close').click(function(){youtube.state='closed';$('#youtube_').addClass('_closed');});					
	$('#youtube_ ._video').hover(function()
	{
		$('#youtube_ ._hint_video').removeClass('_hided_hover');
		if(youtube.hintTimeoutHover) clearTimeout(youtube.hintTimeoutHover);
		youtube.hintTimeoutHover=setTimeout(function(){$('#youtube_ ._hint_video').addClass('_hided_hover');}, 3400);
	});*/
})