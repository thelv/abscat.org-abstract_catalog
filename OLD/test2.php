<?


	ini_set('display_errors', 1);
	$ch = curl_init(); 
	
	$headers=Array('accept'=>'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,* /*;q=0.8',
		'accept-language'=>'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
		'cache-control'=>'no-cache',
		//'cookie'=>'remixlang=3; remixdt=0; remixsid=80146a166faf2f6c4690770f6d847819c907b5a2037093befd6f4; remixflash=25.0.0; remixscreen_depth=24',
		'pragma'=>'no-cache',
		'content-length'=>'30',
		'referer'=>'https://vk.com/',
		'upgrade-insecure-requests'=>'1',
		'user-agent'=>'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.91 Safari/537.36 OPR/48.0.2685.32'
	);
	
	curl_setopt($ch, CURLOPT_URL, "https://login.vk.com/?act=login&to=ZmVlZA--&_origin=https://m.vk.com&ip_h=6a3c1cfdd6dfde502a&lg_h=699e89ec6e52e7579a&role=pda&utf8=1"); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "email=79851620924&pass=123456Ab");

	//return the transfer as a string 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_HEADER, true); 

	// $output contains the output string 
	echo $output = curl_exec($ch); 

	// close curl resource to free up system resources 
	curl_close($ch);      


?>