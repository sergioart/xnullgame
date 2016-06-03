<div class="content">

	<p>Список пользователей:</p>
	<?php
		if(isset($user_list)  && is_array($user_list))
		{
			foreach($user_list as $value)
			{	
				echo('<div><a href="'.SITE_URL.'/load_game/'.$value['session_name'].'">'.$value['session_name'].'</a></div>');	
			}
		}
	?>
	
	<form action="<?php echo SITE_URL.'/room/exit'; ?>"  method="post">
					<button type="submit" value="exit" name="room_exit" title="Выйти из комнаты" alt="exit">Выйти из комнаты</button>
	</form>	
</div>

<script>
			function get_Dir_Status(){
				ajax_data = "str=1";
				request = new XMLHttpRequest();
				request.open("POST", "<?php echo(SITE_URL.'/room/ajax_check_start_game_for_me');?>", true);
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
					//document.getElementById('status_button').value = responseText;
					//console.log(document.getElementById('message'));
					if(responseText !== "<?php echo SITE_URL.'/game/0'; ?>")
					{
						window.location = responseText;
					}
				}
			
			setInterval(function(){get_Dir_Status();}, 5000)
</script>
