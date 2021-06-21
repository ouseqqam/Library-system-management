 <?php

 	include 'connect.php';

	$tpl = 'includes/templates/';
	$css = 'layout/css/';
	$func = 'includes/function/';
	$js = 'layout/js/';

	include $func . 'function.php';
	include $tpl . 'header.php';

	if (!isset($nonavbar))
	{
		include $tpl . 'navbar.php';
	}
	
	include $tpl . 'footer.php';

?>