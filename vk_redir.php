<?php

	//header('Location: '.$_SERVER['QUERY_STRING']);
	
	header("Cache-control: public");
	header("Expires: " . gmdate("D, d M Y H:i:s", time() + 60*60*24*360) . " GMT");

?>
<script src='/js/jquery.js'></script>
<script>


	function redir()
	{
		setTimeout(function(){window.location.href=p.url;}, 0);
	}
	
	
	var p=JSON.parse(decodeURIComponent(window.location.hash.substr(1)));
			
	window.moveTo(p.left, p.top);
	
	if($(window).height()!=p.height)
	{
		window.resizeTo(p.width, p.height+1);
	}
	else
	{
		redir();
	}
	
	var i=0;
	$(window).resize(function()
	{
		if(i==0)
		{
			i++;
			window.resizeTo(p.width,  p.height+p.height-$(window).height()+1);redir();
		}
	});
</script>
<b>player opens...