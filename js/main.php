<?php
	
	header("Content-Type: application/javascript");
	header("Cache-control: public");
	header("Expires: " . gmdate("D, d M Y H:i:s", time() + 60*60*24*360) . " GMT");
	include '../lib/compile.php';

	$scripts=array
	(
		'lib', 'data', 'branch', 'table', 'menu', 'object', 'windows', 'buffer', 'player', 'pages', 'comments',
		'sql/parser', 'sql/path', 'sql/request', 'main'
	);
	foreach($scripts as $script)	
	{
		compile('classes/'.$script.'.js');
		echo "\n";
	}

?>