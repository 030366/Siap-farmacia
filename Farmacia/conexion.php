<?php
function conectar()
{
	mysql_connect("localhost","farma","farm4");
	mysql_select_db("siap");
}

function desconectar()
{
	mysql_close();
}
?>