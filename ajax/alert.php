<?php
      
   require '../init.conf';
   $CR->ajaxToken();

	$message = !empty($_POST['m']) ? $_POST['m'] : null;
	$type = !empty($_POST['t']) ? $_POST['t'] : null;
	$remove = !empty($_POST['r']) ? $_POST['r'] : "true";
	$ida = !empty($_POST['ida']) ? $_POST['ida'] : null;

   echo $CR->alert( $message, $type, $remove, null, $ida );

?>