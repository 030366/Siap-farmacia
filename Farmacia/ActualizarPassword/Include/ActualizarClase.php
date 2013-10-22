<?php

class Cambios{
	function ActualizarPassword($IdPersonal,$Password){
	
	$query="update farm_usuarios set password=md5('$Password') where IdPersonal='$IdPersonal'";
	mysql_query($query);
		
	}
}//
?>
