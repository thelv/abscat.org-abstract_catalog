comments_=
{			
	id: 0,
	name: 'Гость',
	lastName: '',			
	image: '/img/noava.gif',
	sig: 'sid=',
	chatId: 0,
	chatIdPostTo: 0,
	chatName: '',
	
	I:0,
	
	postSending: false,
	
	months: ['янв','фев','мар','апр','мая','июн','июл','авг','сен','окт','ноя','дек'],
	
	auth: function(id, name, lastName, image, sig)
	{
		comments_.id=id;
		comments_.name=name;
		comments_.lastName=lastName;
		comments_.image=image;
		comments_.sig=sig;					
		$('#commentsAdd img').attr('src', image);
		$('body').append('<style>#comments[chat="0"][chat_show_all_in_common="0"] .commentsComment[chat_id="'+parseInt(comments_.id)+'"]{display:block}/*#comments.chat[chat_show_all_in_common="0"] .commentsComment[chat_id="'+parseInt(comments_.id)+'"]{display:none}*/</style>');
	},
	
	post: function()
	{
		if(this.postSending) return;
		this.postSending=true;										
		
		comments_.addSongShow(false);
		var text=$('#commentsAdd textarea').val();
		
		comments_.chat.subscribe(comments_.chatId);
		
		$.ajax(
		{
			url: 'http://195.201.88.63:8000/',//'https://abscat.org/comments_server/',
			dataType: 'text',
			data: 
			{
				type: 'post',
				auth: comments_.sig,
				comment:
				{
					user_id: comments_.id,
					user_name: comments_.name,
					user_last_name: comments_.last_name,
					user_image: comments_.image,
					text: text,	
					chat_id: comments_.chatId || comments_.chatIdPostTo,
					chat_name: comments_.chatName
				}
			},
			
			success: function(data)
			{
				comments_.chat.hidePostTo();
			
				if(data=='OK')
				{
					$('#commentsAdd textarea').val('');
				}
				else if(data=='ERROR_AUTH')
				{
					alert('Ошибка авторизации при попытке запостить комментарий.');
				}
				else if(data=='ERROR_BLACK_LIST')
				{
					alert('Вы забанены в разделе комментариев. Музыка при этом по-преженему скачивается.');
				}
				else if(data=='ERROR_COUNTRY')
				{
					alert('Необходимо авторизоваться, чтобы оставлять комментарии. Для этого необходимо нажать "разрешить доступ к своим аудиозаписям" слева.');
				}	
				
				comments_.postSending=false;
			},
			
			error: function()
			{
				alert('Произошла ошибка при отправке комментария. Попробуйте еще раз.');
				
				comments_.postSending=false;
			}
		});										
	},
	
	get: function()
	{
		$.ajax(
		{
			url: 'http://195.201.88.63:8000/',//'https://abscat.org/comments_server/',
			dataType: 'json',
			data: 
			{
				type: 'get', 
				comment_id: comments_.I,
			},
			success: function(comments)
			{																
				var html='';
				for(var i in comments)
				{
					var comment=comments[i];
													
					var name=comment['user_name'] || '';
					var lastName=comment['user_last_name'] || '';
					var image=comment['user_image'] || '';
					var text=comment['text'] || '';
					var time=new Date(comment['time']*1000);
					var id=comment['user_id'];
					var commentId=comment['id'];
					var chatId=parseInt(comment['chat_id']) || 0;
					var chatName=comment['chat_name'] || '';
					time=time.getDate()+' '+comments_.months[time.getMonth()]+' '+time.getFullYear()+' '+time.toTimeString().substr(0,5);//time.getHours()+':'+time.getMinutes();
					text=lib.escapeHtml(text).replace(/\r*\n/g, '<br>').replace(/\[audio=([\d_\-]+)\](.*?)\[\/audio\]/g, function(m0, m1, m2)
					{
						m1=m1.replace(/[\r\n'"<>]/g, '_');
						m2_=lib.escapeHtml(lib.escapeHtml(m2, true));
						m2=lib.escapeHtml(m2, true).replace(/[\r\n'"<>\\]/g, '_');
						return '</span><a class=commentsTextSong oncontextmenu="vk.stop();vk.open(\''+m2+'\');event.preventDefault();" onclick="comments_.audio.play(\''+m1+'\', \''+m2+'\')">'+m2_+'</a><span>';
					}).replace(/(<br>)*\[quote=\&quot\;(.+?)\&quot\;\](.*?)\[\/quote\](<br>)*/g, function(m0, m00, m1, m2)
					{
						return '</span><div class=commentsQuote><b>↦ '+lib.escapeHtml(m1)+'</b><span>'+/*escapeHtml(m2)*/m2+'</span></div><span>';
					});
					/*.replace(/\[to=\&quot\;(.+?)\&quot\;\]/g, function(m0, m1)
					{
						return '<b>'+lib.escapeHtml(m1)+'</b>';
					});*/
					
					html='\
						<div class=commentsComment id=comment'+parseInt(commentId)+' chat_id='+parseInt(chatId)+'>\
							<div class=commentsAva>\
								<a target=_blank href="http://vk.com/id'+parseInt(id)+'"><img src="'+lib.escapeHtml(image)+'" width="50" height="50"></a>\
							</div>\
							<div class=commentsRight>\
								<div class=commentsName><a target=_blank href="http://vk.com/id'+parseInt(id)+'">'+
									lib.escapeHtml(name)+' '+lib.escapeHtml(lastName)+
								'</a></div>\
								<div class=commentsText><span>'+
									text+
								'</span></div>'+
								(
									chatId 
								?
									'<a class=commentsCommentChat onclick="comments_.chat.show('+parseInt(chatId)+', \''+lib.escapeForJs(lib.escapeHtml(chatName))+'\')">'+
											'<div>сообщение со стены '+lib.escapeHtml(chatName)+'</div>'+
									'</a>'
								:
									''
								)+
								'<div class=commentsDate>'+
										lib.escapeHtml(time)+													
										' <span class=commentsAction>- <a class=commentsReply onclick="comments_.reply(this); '+(chatId ? 'comments_.chat.showPostTo('+parseInt(chatId)+', \''+lib.escapeForJs(lib.escapeHtml(chatName))+'\');' : '')+'">ответить</a></span>'+
										' <!-- <span class=commentsAction>- <a class=commentsChat onclick="comments_.chat.show('+parseInt(id)+', \''+lib.escapeForJs(lib.escapeHtml(name)+' '+lib.escapeHtml(lastName))+'\')">стена</a></span>-->'+
										//' <span class=commentsReply_ onclick="comments_.replySimple(this)">- <a class=commentsReply>цитировать</a></span>'+
								'</div>\
							</div>\
						</div><div></div>\
					'+html;
					comments_.I=comment.id;
				}
				//alert(html);
				$('#commentsList').prepend($(html));
				
				setTimeout(function(){comments_.get();}, 2000);
			},
			error: function()
			{
				//alert('comments error');
				setTimeout(function(){comments_.get();}, 7000);
			}
		});
	},
	
	reply: function(elem)
	{
		var comment=$(elem).closest('.commentsComment');
		var name=comment.find('.commentsName').text();
		var text=$('<div>'+comment.find('.commentsText').html()+'</div>');
		text.find('.commentsQuote').remove();
		text=text.text();
		$('#comments').scrollTop(0);
		var m=$('.commentsTextarea textarea');					
		var t=m.val();
		if(/*t && /*t.substr(-1)!=' ' && */t.substr(-1)!='\n') t+='\n';
		m.val(t+'[quote="'+name+'"] '+text+' [/quote]');
		//m.focus();					
	},
	
	replySimple: function(elem)
	{
		var comment=$(elem).closest('.commentsComment');
		var name=comment.find('.commentsName').text();
		var text=$('<div>'+comment.find('.commentsText').html()+'</div>');
		text.find('.commentsQuote').remove();
		text=text.text();
		$('#comments').scrollTop(0);
		var m=$('.commentsTextarea textarea');					
		var t=m.val();
		//if(/*t && /*t.substr(-1)!=' ' && */t.substr(-1)!='\n') t+='\n';
		//m.val('[to="'+comment.attr('id').substr(7)+'"]'+name+'[/to], '+t);
		m.val('[to="'+name+'"], '+t);
		//m.focus();
	},

	audio:
	{
		addShow: function(show)
		{	
			if(show)
			{
				$('body').addClass('comments_audio_add');
			}
			else
			{
				$('body').removeClass('comments_audio_add');
			}
		},
		
		add: function(audio)
		{
			comments_.addSong(audio.owner_id+'_'+audio.aid, audio.artist+' - '+audio.title);
		},
		
		play: function(audioId, audioFullName)
		{
			player.open(audioFullName);
			/*audioId=audioId.split('_');
			VK.api('audio.get', {owner_id: audioId[0], audio_ids: audioId[1]}, function(res)
			{
				if(res.response && res.response[1])
				{
					var audio=res.response[1];
					if(audio.url.substr(0,5)=='https') audio.url='http'+audio.url.substr(5);
					play(audio);
				}
				else
				{
					gui.search(audioFullName);								
				}
			});*/
		}
	},

	addSong: function(id, songName)
	{
		//songName=songName.replace(/\[/g, ' ');
		var m=$('.commentsTextarea textarea');
		var t=m.val();
		if(t && /*t.substr(-1)!=' ' && */t.substr(-1)!='\n') t+='\n';
		m.val(t+'[audio='+id+'] '+songName+' [/audio]');
	},


	addSongShow: function(show)
	{
		if(show)
		{
			$('body').addClass('commentsAddSongShowed');
		}
		else
		{
			$('body').removeClass('commentsAddSongShowed');

		}
		comments_.audio.addShow(show);
	},

	chat:
	{
		styleNode: null,
		
		init: function()
		{
			//this.settings.showInCommonList=lib.cookie('comments_chat_show_in_common') || {};
			//this.settings.hideInCommonList=lib.cookie('comments_chat_hide_in_common') || {};						
			/*out='';
			for(var i in this.settings.showInCommonList)
			{
				out+='<style>commentsComment[chat_id="'+parseInt(i)+'"]{display:block}</style>';
			}
			for(var i in this.settings.hideInCommonList)
			{
				out+='<style>comments[chat_show_all_in_common="1"] commentsComment[chat_id="'+parseInt(i)+'"]{display:none}</style>';
			}
			$('body').append(out);*/						
			
			$("#comments").attr('chat_show_all_in_common', lib.cookie("comments_chat_show_all_in_common") ? "1" : "0");
			$("#comments_chat_settings_show_all_in_common > input").attr('checked', lib.cookie("comments_chat_show_all_in_common") ? true : false);
			this.styleNode=$('<style></style>');
			$('body').append(this.styleNode);
		},
	
		show: function(id, name)
		{
			this.hidePostTo();
			comments_.chatId=id;
			comments_.chatName=name;
			$('#comments_chat_header > span').text('Стена '+name);
			$('#comments').addClass('chat');
			$('#comments').attr('chat', '1');
			$('#comments').scrollTop(0);
			this.styleNode.html('#comments.chat .commentsComment[chat_id="'+parseInt(id)+'"]{display:block}/*#comments.chat[chat_show_all_in_common="0"] div.commentsComment[chat_id="'+parseInt(id)+'"]{display:block}*/');
			if(! lib.paramCookie('comments_chat_help_hide')) $('#comments_chat_help').show();
		},
		
		showPostTo: function(id, name)
		{
			if(! comments_.chatId)
			{
				comments_.chatIdPostTo=id;
				comments_.chatName=name;
				$('#comments_chat_post_to > span').text('отправить на стену '+name);
				$('#comments').addClass('chat_post_to');
			}
		},
		
		hidePostTo: function()
		{
			comments_.chatIdPostTo=0;
			$('#comments').removeClass('chat_post_to');
		},
		
		hide: function()
		{
			comments_.chatId=0;
			$('#comments').removeClass('chat');
			$('#comments').attr('chat', '0');
			this.styleNode.html('');
			$('#comments_chat_help').hide();
		},
		
		subscribeList: {},
		subscribe: function(chatId)
		{
			if(chatId!=0 && ! this.subscribeList[chatId])
			{							
				$('body').append('<style>#comments[chat="0"][chat_show_all_in_common="0"] .commentsComment[chat_id="'+parseInt(chatId)+'"]{display:block}</style>');
				this.subscribeList[chatId]=true;
			}
		},
							
		settings:
		{
			showInCommonList: null,
			hideInCommonList: null,
		
			/*showInCommon: function(showOrHide)
			{
				if(showOrHide) this.showInCommonList[comments_.chatId]=1; else delete this.showInCommonList[comments_.chatId];
				lib.cookie("comments_chat_show_in_common", this.showInCommonList);
			},
			
			hideInCommon: function(showOrHide)
			{
				if(showOrHide) this.hideInCommonList[comments_.chatId]=1; else delete this.hideInCommonList[comments_.chatId];
				lib.cookie("comments_chat_hide_in_common", this.hideInCommonList);
			},*/
			
			showAllInCommon: function(showOrHide)
			{
				lib.cookie("comments_chat_show_all_in_common", showOrHide ? "1" : "");
				$('#comments').attr('chat_show_all_in_common', showOrHide ? "1" : "0");
			}
		}
	},
	
	hide: function(hideOrShow)
	{
		if(hideOrShow)
		{
			$('body').addClass("comments_hided").css('margin-right', -$('body').scrollLeft()+'px');			
		}
		else
		{
			$('body').removeClass("comments_hided").css('margin-right', '0px');			
		}
	}
}

$(function()
{	
	//windows.import.open();
	$('#commentsAdd textarea').keydown(function (e) 
	{
		if (e.ctrlKey && e.keyCode == 13) 
		{
			comments_.post();
		}
	});
	comments_.chat.init();
	if(user && user.photo) $('.commentsAva img').attr('src', user.photo);
	comments_.get();	
	var commentsNode=$('#comments');
	var commentsNodeRow=commentsNode.get(0);
	window.scriptMouseWheelPromise=new Promise(function(then)
	{
		window.scriptMouseWheelOnLoad=then;
	});
	scriptMouseWheelPromise.then(function()
	{
		commentsNode.bind('mousewheel', function(e, d) 
		{
			//var height=commentsNode.height();
			//var scrollHeight=commentsNodeRow.scrollHeight;
			if((this.scrollTop===(commentsNodeRow.scrollHeight-commentsNode.height()) && d<0) || (this.scrollTop===0 && d>0)) 
			{
				e.preventDefault();
			}
		});
	});
	
	$(window).scroll(function(e)
	{
		if($('body').hasClass('comments_hided'))
		{									
			$('#comments_cont').css('margin-right', -$('body').scrollLeft()+'px');
		}
	});
	/*var resize=function()
	{
		$('#comments').css('height', $(window).height()-41+'px');
	}
	//alert($(window).height());
	$(window).resize(resize);
	resize();
	setTimeout(resize, 1000);*/
});	