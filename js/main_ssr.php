<?php
	
/*	header("Content-Type: application/javascript");
	header("Cache-control: public");
	header("Expires: " . gmdate("D, d M Y H:i:s", time() + 60*60*24*360) . " GMT");
	include '../lib/compile.php';

	$scripts=array
	(
		'lib', 'data', 'branch', 'table', 'menu', 'object', 'windows', 'buffer', 'player', 'pages', 'comments',
	    'main'
	);
	foreach($scripts as $script)	
	{
		compile('classes/'.$script.'_ssr.js');
		echo "\n";
	}*/
	readfile('main_ssr.js');

?>
//document.body.innerHTML='<?php echo $_SERVER['HTTP_X_REAL_IP']; ?>';