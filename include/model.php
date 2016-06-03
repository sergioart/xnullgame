<?php

function model_room_list()
{
	return(model_get_data_from_sql('SELECT name FROM room'));	
};

function model_user_list($room_name)
{
	return(model_get_data_from_sql(
			'
			SELECT users.session_name 
			FROM users,room
			WHERE 
				room.name = \''.$room_name.'\'
			AND
				users.room_id = room.id
			AND  
				session_name <> \''.(string)$_COOKIE[session_name()].'\'
			')
			);	
};

function model_create_game($partner_id)
{
	$new_game_id = rand(1000,10000000);
	
	model_get_data_from_sql('insert into game (id, status) values ('.$new_game_id.', 2)');
	
	model_get_data_from_sql('UPDATE users SET room_id = 0, game_type_xo = "x", game_id = '.$new_game_id.' WHERE session_name = \''.(string)$_COOKIE[session_name()].'\'');
	
	model_get_data_from_sql('UPDATE users SET room_id = 0, game_type_xo = "o", game_id = '.$new_game_id.' WHERE session_name = \''.$partner_id.'\'');
	
	return($new_game_id);
};

function model_ajax_check_start_game_for_me()
{
	$game_id = model_get_data_from_sql('SELECT game_id FROM users WHERE session_name = \''.(string)$_COOKIE[session_name()].'\'');
	return($game_id[0]['game_id']);
};

function model_game_users($game_id)
{
	return(model_get_data_from_sql('SELECT session_name FROM users WHERE game_id = '.$game_id.' '));	
};

function model_game_status($game_id)
{	
		$stat = model_get_data_from_sql('SELECT status FROM game WHERE id = '.$game_id.' ');
		$stat = $stat[0]['status'];
		return($stat);
};

function model_game_board($game_id)
{
	return(model_get_data_from_sql('SELECT board_position FROM game WHERE id = '.$game_id.' '));	
};


function model_update_game_board($board_position,$field,$game_id)
{
	//получим из базы значения чем ходит игрок (игрок начавший игру ходит крустиком), и запишем его в позицию на доске, позицию получили также от пост данных.
	$type = model_get_data_from_sql('SELECT game_type_xo FROM users WHERE session_name = \''.(string)$_COOKIE[session_name()].'\'');
	$type = $type[0]['game_type_xo'];
	$board_position[$field] = $type;
	model_get_data_from_sql('UPDATE game SET board_position ="'.$board_position.'" WHERE id = '.$game_id);
};


function model_check_game_finish($game_id)
{
	$type = model_get_data_from_sql('SELECT game_type_xo FROM users WHERE session_name = \''.(string)$_COOKIE[session_name()].'\'');
	$board = model_get_data_from_sql('SELECT board_position FROM game WHERE id = '.$game_id);
	$b = $board[0]['board_position'];

	if ($type[0]['game_type_xo'] === 'x')
	{
		if(
			$b[0].$b[1].$b[2] === 'xxx' or $b[3].$b[4].$b[5] === 'xxx'  or $b[6].$b[7].$b[8] === 'xxx' or
			$b[0].$b[3].$b[6] === 'xxx' or $b[1].$b[4].$b[7] === 'xxx'  or $b[2].$b[5].$b[8] === 'xxx' or
			$b[0].$b[4].$b[8] === 'xxx' or $b[2].$b[4].$b[6] === 'xxx'
		)
		{
			return('win');
		}	
	}
	elseif ($type[0]['game_type_xo'] === 'o')
	{
		
		if(
			$b[0].$b[1].$b[2] === 'ooo' or $b[3].$b[4].$b[5] === 'ooo'  or $b[6].$b[7].$b[8] === 'ooo' or
			$b[0].$b[3].$b[6] === 'ooo' or $b[1].$b[4].$b[7] === 'ooo'  or $b[2].$b[5].$b[8] === 'ooo' or
			$b[0].$b[4].$b[8] === 'ooo' or $b[2].$b[4].$b[6] === 'ooo'
		)
		{
			return('win');
		}
	}
}

function model_clear_game($game_id, $flag)
{
	if($flag === 'win')
	{
		//удалим первого выигравшего.
		model_get_data_from_sql('UPDATE users SET room_id = 0,  game_id = 0, game_type_xo = 0 WHERE game_id = '.$game_id.' AND session_name = "'.(string)$_COOKIE[session_name()].'"');
		
		//Пометим что второй юзер проиграл.
		model_get_data_from_sql('UPDATE users SET game_type_xo = "F" WHERE game_id = '.$game_id);
	}
	else
	{
		$Fail_check = model_get_data_from_sql('SELECT game_type_xo FROM users WHERE game_id = '.$game_id.' AND session_name = "'.(string)$_COOKIE[session_name()].'"');
		
		$Fail_check = $Fail_check[0]['game_type_xo'];
		
		
		if($Fail_check === 'F')
		{
			model_get_data_from_sql('DELETE FROM game WHERE id = '.$game_id);
			//удалим проигравшего.
			model_get_data_from_sql('UPDATE users SET room_id = 0,  game_id = 0, game_type_xo = 0 WHERE game_id = '.$game_id.' AND session_name = "'.(string)$_COOKIE[session_name()].'"');
			return('F');
		}
	}
	return('');
};

function model_clear_user_from_room()
{
	@model_get_data_from_sql('UPDATE users SET room_id = 0, game_id = 0 WHERE session_name = \''.(string)$_COOKIE[session_name()].'\'
	');	
};

function model_add_user_in_room($room_name)
{
	
	//получим из базы id по имени комнаты
	$room_id_arr = model_get_data_from_sql('SELECT id FROM room WHERE name = \''.$room_name.'\'');
	$room_id = $room_id_arr[0]['id'];
	
	//добавим к пользователю ай-ди комнаты
	model_get_data_from_sql('UPDATE users SET room_id = '.$room_id.', game_id = 0 WHERE session_name = \''.(string)$_COOKIE[session_name()].'\'
	');	
};


function model_session_engine()
{
	if (empty($_COOKIE[session_name()]))
	{ 		
		session_start();	
		$now_sid = '\''.session_id().'\'';
		model_get_data_from_sql('INSERT INTO `users` (`session_name`) VALUES ('.$now_sid.')');
		return(1);
	}
}

function model_get_data_from_sql($query_text){
		$connect = mysqli_init();
		$connect->options(MYSQLI_OPT_CONNECT_TIMEOUT, 23);
		$connect->real_connect('127.0.0.1', 'user_xnull', 'xnull', 'xnull');
		$result = $connect->query($query_text);	
		if(is_object($result) && $result->num_rows)
		{	
			$result->data_seek(0);
			while ($row = $result->fetch_array(MYSQLI_ASSOC))
			{
					$data_array[] = $row;
			}
			return($data_array);
		}
		else
		{
			return(1);
		}		
}

?>
