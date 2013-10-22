<?php
function conectar()
{
	$strCnx = "host=localhost port=5432 dbname=siap_ultimate user=postgres password=admin";
	$cnx = pg_connect($strCnx) or die ("Error de conexion bananero. ". pg_last_error());	return $cnx;
}

function desconectar()
{
	pg_close();
}
?>


