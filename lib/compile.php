<?php

	$ru=array
	(
		"Abstract Catalog" => "Каталог музыки",
		"about project" => "о проекте",
		"all catalogs" => "все каталоги",
		"my catalog" => "мой каталог",
		"login" => "войти",
		"User" => "Пользователь",
		"logout" => "выйти",
		"About Project" => "О проекте",
		"You can create" => "Вы можете создать",
		"your own catalog" => "свой собственный каталог",
		"or discover other people" => "или посмотреть другие",
		"catalogs" => "каталоги",
		"All Catalogs" => "Все каталоги",
		"My catalog" => "Мой каталог",
		"create new object" => "создать новый объект",
		"Object filter: type" => "Фильтр объектов: тип",
		"text" => "текст",
		"Object" => "Объект",
		"back" => "назад",
		"video" => "видео",
		"close" => "закрыть",
		"show" => "показать",
		"hide" => "скрыть",
		"stop" => "стоп",
		"buffer" => "буффер",
		"clear" => "очистить",
		"Message" => "Сообщение",
		"OK" => "ОК",
		"Cancel" => "Отмена",
		"Create a new object" => "Создать новый объект",
		"Text" => "Текст",
		"Type" => "Тип",
		"Color" => "Цвет",
		"Connect with a new object" => "Связать с новым объектом",
		"Connection" => "Связь",
		"Text for the opposite direction" => "Текст для обратного направления",
		"Connect with an object from the the buffer" => "Связать с объектом из буффера",
		"Edit an object" => "Изменить объект",
		"Remove an object" => "Удалить объект",
		"Remove the object" => "Удалить объект",
		"Edit a connection" => "Изменить связь",
		"Remove a connection" => "Удалить связь",
		"Remove the connection" => "Удвлить связь",
		"connect the connection with a new object" => "связать связь с новым объектом",
		"edit the connection" => "изменить связь",
		"remove the connection" => "удалить связь",
		"connect with an object from the buffer" => "связать с объектом из буффера",
		"connect with a new object" => "связать с новым объектом",
		"open" => "открыть",
		"put in buffer" => "добавить в буффер",
		"edit" => "изменить",
		"remove" => "удалить",
		"Types" => "Типы",
		"Objects" => "Объекты",
		"put in the buffer" => "добавить в буффер",
		"connect with object from buffer" => "связать с объектом из буффера",
		"connect with new object" => "связать с новым объектом",
		"listen here through youtube" => "слушать здесь через Ютуб",
		"listen on vk.com" => "слушать на vk.com",
		"listen with vk.com" => "слушать через vk.com",
		"ok" => "ок",
		"The buffer is empty" => "Буффер пуст",
		"Catalog" => "Каталог",
		"show vk window" => "показать окно вк",
		"vk window" => "окно вк",
		"listen" => "слушать",
		"listen here through vk.com" => "слушать здесь через vk.com",
		"youtube" => "ютуб",
		"Comments" => "Комментарии",
		"vk" => "вк",
		"yt" => "yt",
		"play" => "слушать",
		"audio" => "аудио",
		"play in vk" => "слушать через вк",
		"disable" => "отключить",
		"comments" => "комментарии",
		"backup" => "бэкап",
		"Send" => "Отправить",
		
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