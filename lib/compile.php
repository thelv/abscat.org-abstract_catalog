<?php

	$ru=array
	(
		"Abstract Catalog" => "������� ������",
		"about project" => "� �������",
		"all catalogs" => "��� ��������",
		"my catalog" => "��� �������",
		"login" => "�����",
		"User" => "������������",
		"logout" => "�����",
		"About Project" => "� �������",
		"You can create" => "�� ������ �������",
		"your own catalog" => "���� ����������� �������",
		"or discover other people" => "��� ���������� ������",
		"catalogs" => "��������",
		"All Catalogs" => "��� ��������",
		"My catalog" => "��� �������",
		"create new object" => "������� ����� ������",
		"Object filter: type" => "������ ��������: ���",
		"text" => "�����",
		"Object" => "������",
		"back" => "�����",
		"video" => "�����",
		"close" => "�������",
		"show" => "��������",
		"hide" => "������",
		"stop" => "����",
		"buffer" => "������",
		"clear" => "��������",
		"Message" => "���������",
		"OK" => "��",
		"Cancel" => "������",
		"Create a new object" => "������� ����� ������",
		"Text" => "�����",
		"Type" => "���",
		"Color" => "����",
		"Connect with a new object" => "������� � ����� ��������",
		"Connection" => "�����",
		"Text for the opposite direction" => "����� ��� ��������� �����������",
		"Connect with an object from the the buffer" => "������� � �������� �� �������",
		"Edit an object" => "�������� ������",
		"Remove an object" => "������� ������",
		"Remove the object" => "������� ������",
		"Edit a connection" => "�������� �����",
		"Remove a connection" => "������� �����",
		"Remove the connection" => "������� �����",
		"connect the connection with a new object" => "������� ����� � ����� ��������",
		"edit the connection" => "�������� �����",
		"remove the connection" => "������� �����",
		"connect with an object from the buffer" => "������� � �������� �� �������",
		"connect with a new object" => "������� � ����� ��������",
		"open" => "�������",
		"put in buffer" => "�������� � ������",
		"edit" => "��������",
		"remove" => "�������",
		"Types" => "����",
		"Objects" => "�������",
		"put in the buffer" => "�������� � ������",
		"connect with object from buffer" => "������� � �������� �� �������",
		"connect with new object" => "������� � ����� ��������",
		"listen here through youtube" => "������� ����� ����� ����",
		"listen on vk.com" => "������� �� vk.com",
		"listen with vk.com" => "������� ����� vk.com",
		"ok" => "��",
		"The buffer is empty" => "������ ����",
		"Catalog" => "�������",
		"show vk window" => "�������� ���� ��",
		"vk window" => "���� ��",
		"listen" => "�������",
		"listen here through vk.com" => "������� ����� ����� vk.com",
		"youtube" => "����",
		"Comments" => "�����������",
		"vk" => "��",
		"yt" => "yt",
		"play" => "�������",
		"audio" => "�����",
		"play in vk" => "������� ����� ��",
		"disable" => "���������",
		"comments" => "�����������",
		"backup" => "�����",
		"Send" => "���������",
		
		""=>""
	);	
	
	function compile($file)
	{
		$file='./'.$file;
		if((strpos($file, '..')!==false) || ($file[0]=='/')) die();
		$f=file_get_contents($file);	
		
		$f=preg_replace_callback('/\$\@(\d*)\{(.*)\}/iUs', function($match)
		{
			global $ru;
			global $lang;
			if(! $lang) return $match[2];
			if($res=$ru[$m=($match[1] ? (int)$match[1] : $match[2])])
			{
				return $res;
			}
			else
			{
				echo '<textarea>"'.$m.'" => "",
			</textarea>';
				echo '<script src=/js/jquery.js></script>
					<script>
						$(function()
						{
							$("textarea").select();
						});
					</script>
					';
				die();
			}
		}, $f);
	
		file_put_contents(__DIR__.'/../compile/temp', $f);
	
		include __DIR__.'/../compile/temp';
	}

?>