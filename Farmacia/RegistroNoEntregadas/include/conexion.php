<?php

	$link = mysql_connect("localhost","root","") or die("Ocurrió un error al intentar conectar. Verifica que estén correctamente los datos dentro de <strong>config.php</strong>.");
	mysql_select_db("siap",$link) or die("Error al seleccionar la base de datos. Posiblemente no existe.");
?>