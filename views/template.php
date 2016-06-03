<?php header("Content-Type: text/html; charset=utf-8");?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="robots" content="noindex, nofollow"/>
        <title>XNULL</title>
    </head>
    <body>
		
		<a href="<?php echo SITE_URL?>">На Главную</a><br />
		-----------------------------------------------------------
		
		<?php
				include(Main_Dir.'/views/'.$include_view);
		?>
    </body>
</html>
