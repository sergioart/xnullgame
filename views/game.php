<div>
	<?php	
		
		echo 'Участники раунда : ';
		if(isset($game_users)  && is_array($game_users))
		{
			foreach($game_users as $value)
			{	
				echo($value['session_name'].'______');	
			}
		}
		echo '<br />-----------------------------------------------------------';
		echo('<br />ID раунда : '.$game_id);
		echo '<br />-----------------------------------------------------------';
		echo('<div id = "board"><form action="'.FULL_PATH.'" method="post">');
			$str = 1;
			$sym = 1;
			
			for ($i = 0; $i < 9; $i++) 
			{
				
				if($game_board[0]['board_position'][$i] === 'x' or $game_board[0]['board_position'][$i] === 'o')
				{
					echo('<input disabled id="but'.$i.'" type="submit" name="b'.$i.'" value="'.$game_board[0]['board_position'][$i].'">');
				}
				else
				{
					echo('<input onclick="BrdStatUpd()" id="but'.$i.'" type="submit" name="b'.$i.'" value="'.$game_board[0]['board_position'][$i].'">');
				}
				if($sym === 1 or $sym === 2){echo('      |      ');} $sym++;
				if($sym === 4){echo('<br />-------------------<br />');$sym = 1;}
			}
		
		echo('<input id="game_board_current_stat" name="game_board_current_stat" type="text" hidden size="9">');
		echo('</form></div>');
	?>
		
</div>	 
 
<script>		
			function BrdStatus(){
				ajax_data = "game_id=" + "<?php echo $game_id;?>";
				request = new XMLHttpRequest();
				request.open("POST", "<?php echo(SITE_URL.'/game/ajax_check_board_position');?>", true);
				request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
				request.setRequestHeader("Content-length", ajax_data.length);
				request.setRequestHeader("Connection", "close");
				
				request.onreadystatechange = function(){
					if (this.readyState == 4){
						if (this.status == 200){
							if (this.responseText != null){
									Check_St(this.responseText);
									//console.log(document.getElementById('submit_but'));
									console.log(this.responseText);
							}
						else alert("Achtung! Ajax Error!")
						}
					else alert( "Achtung! Ajax Error!: " + this.statusText)
					}
				}
				request.send(ajax_data)
			}
				function Check_St(responseText)
				{
					this_board_status = BrdStatUpd();
					
					/* если противник сделал ход то перегрузим страничку (доска обновится) */
					if(responseText !== this_board_status)
					{
						
						if(responseText[9] === 'F')
						{window.location = "<?php echo(SITE_URL).'/fail';?>";}
						else
						{window.location = "<?php echo(SITE_URL.'/game/'.$game_id);?>";}
					}
				}	

				/* отправляем положение всех элементов доски при ходе пользователя. */
				function BrdStatUpd()
				{
					this_board_status =
					document.getElementById('but0').value+document.getElementById('but1').value+document.getElementById('but2').value+
					document.getElementById('but3').value+document.getElementById('but4').value+document.getElementById('but5').value+
					document.getElementById('but6').value+document.getElementById('but7').value+document.getElementById('but8').value;
					document.getElementById('game_board_current_stat').value = this_board_status;
					return(this_board_status);
					
					/* console.log(document.getElementById('game_board_current_stat').value); */
				}	

				setInterval(function(){BrdStatus();}, 5000)
		
</script>
