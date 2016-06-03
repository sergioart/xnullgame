<div class="content">

	<p>Список комнат:</p>
	<?php
		//Список комнат
		if(isset($room_list) && is_array($room_list))
		{
			foreach($room_list as $value)
			{	
				echo('<div><a href="'.SITE_URL.'/room/'.$value['name'].'">'.$value['name'].'</a></div>');	
			}
		}
	?>
</div>
