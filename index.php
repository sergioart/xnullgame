<?php
define('Main_Dir', __DIR__);

ini_set ('display_errors','Off'); 
ini_set ('display_startup_errors','Off');
ini_set ('html_errors','Off');	
ini_set ('error_log', Main_Dir.'/errors.log');
ini_set ('log_errors', 'On');
error_reporting(-1);

include(Main_Dir.'/include/model.php');
include(Main_Dir.'/include/controller.php');

$path = $_SERVER['REQUEST_URI'];
//$path = str_ireplace("/My_Project/www.xnullgame.my-board.org","",$path);

//Подготовка массива для роутинга
$path_arr = explode('/',trim($path,'/'));
foreach($path_arr as &$val_path)
{
	$val_path = trim(urldecode($val_path));
}

define('CURRENT_PATH',$path);
//define('SITE_URL','http://localhost/My_Project/www.xnullgame.my-board.org');
define('SITE_URL','http://www.xnullgame.my-board.org');
define('FULL_PATH',SITE_URL.CURRENT_PATH);
session_name("xnullgameses");

//Routing
if (empty($path_arr[0]))
{
	controller('main');
}
elseif ($path_arr[0] === 'room' && isset($path_arr[1]))
{
		controller('room',$path_arr[1]);
}
elseif ($path_arr[0] === 'load_game' && isset($path_arr[1]))
{
		controller('load_game',$path_arr[1]);
}
elseif ($path_arr[0] === 'game' && isset($path_arr[1]))
{
		controller('game',$path_arr[1]);
}
elseif ($path_arr[0] === 'wait_user_in_room_ajax')
{
	controller('wait_user_in_room_ajax');
}
elseif ($path_arr[0] === 'step_in_game_ajax')
{
	controller('step_in_game_ajax');
}
elseif ($path_arr[0] === 'win')
{
	controller('win');
}
elseif ($path_arr[0] === 'fail')
{
	controller('fail');
}
else
{
	controller('404');
}
?>
