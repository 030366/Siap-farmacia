<?php
require('IncludeFiles/RepetitivasClase.php');

conexion::conectar();


switch($_GET["Bandera"]){

case 1:
	$query="select distinct year(Fecha) as Ano
		from farm_recetas
		where Fecha <> 0
		and Fecha >= '2008'
		order by Ano
		";

	$resp=mysql_query($query);

	$combo="<select id='Ano' name='Ano'>";

	while($row=mysql_fetch_array($resp)){
		$combo.="<option value='".$row[0]."'>".$row[0]."</option>";
		
	}


	$combo.="</select>";

	echo $combo;
break;

}
conexion::desconectar();

?>