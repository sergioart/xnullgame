<?php

function controller($route,$route2='')
{		
	if($route === 'main')
	{
		model_session_engine();
		model_clear_user_from_room();
		$room_list		= model_room_list();
		
		if (empty($_COOKIE[session_name()]))
		{ 		
				header('Location: '.SITE_URL);
				exit;
		}
		
		$include_view = 'main.php';
		include(Main_Dir.'/views/template.php');
		
	}
	
	if($route === 'room')
	{
		//model_session_engine();
		
		if($route2 === 'exit')
		{
			model_clear_user_from_room();
			header('Location: '.SITE_URL);
			exit;
		}
		elseif($route2 === 'ajax_check_start_game_for_me')
		{	
			$game_id = model_ajax_check_start_game_for_me();
			echo(SITE_URL.'/game/'.$game_id);
			exit;
		}
		elseif($route2 === 'ajax_check_new_player_in_room')
		{	
			echo( model_ajax_check_new_player_in_room());
			exit;
		}
		else
		{
			model_add_user_in_room($route2);
			$user_list	= model_user_list($route2);
			$include_view = 'room.php';
		}
		
		include(Main_Dir.'/views/template.php');
	}
	
	if($route === 'load_game')
	{
		//model_session_engine();
		
		$game_id = model_create_game($route2);
		header('Location: '.SITE_URL.'/game/'.$game_id);
		exit;
	}
	
	if($route === 'game')
	{
	
		if($route2 === 'ajax_check_board_position')
		{
			//получим значение позиций на доске и в виде обычной строки 9-ти символов отправим браузеру.
			$arr_for_ajax = model_game_board($_POST['game_id']);
			$arr_for_ajax = $arr_for_ajax[0]['board_position'];
			
			if(isset($arr_for_ajax[8]))
			{
				//Проверим не проиграл ли игрок и добавим флаг.
				$arr_for_ajax = $arr_for_ajax.model_clear_game($_POST['game_id'],'A');
				
				echo($arr_for_ajax);
				exit;
			}
			else{header('Location: '.SITE_URL);exit;}		
		}
		else
		{
			//если пришли данные хода игрока после нажатия на ячейку
			if(!empty($_POST))//номер ячейки хода игрока
			{
				//проверим что пост данные от кнопки хода (b1,b2...).
				$key = key($_POST);
				if($key[0] === 'b')
				{
					$field = $key[1];
				}	
			}
			if(!empty($_POST['game_board_current_stat']))
			{
					model_update_game_board($_POST['game_board_current_stat'],$field,$route2);
			}

			//Проверим условия завершения игры
			if (model_check_game_finish($route2) === 'win')
			{
				model_clear_game($route2,'win');
				header('Location: '.SITE_URL.'/win');
			}
			
			$game_id = $route2;
			$game_status = model_game_status($game_id);
			$game_users = model_game_users($game_id);
			$game_board = model_game_board($game_id);
			
			$include_view = 'game.php';
			include(Main_Dir.'/views/template.php');
			
		}
	}
	
	elseif($route === 'win')
	{
		$include_view = 'win.php';
		include(Main_Dir.'/views/template.php');
	}	
	elseif($route === 'fail')
	{
		$include_view = 'fail.php';
		include(Main_Dir.'/views/template.php');
	}	
	elseif($route === '404')
	{
		$include_view = '404.php';
		include(Main_Dir.'/views/template.php');
	}	
}

?>
