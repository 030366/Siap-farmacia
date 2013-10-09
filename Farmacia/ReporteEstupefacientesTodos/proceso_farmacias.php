<?php session_start();

include '../Clases/class.php';

$opcionSeleccionada=$_GET["valor"];

    switch($_GET["Combo"]){

	
	case "farm_catalogoproductos":
		$tabla="farm_catalogoproductos";
	$conexion=new conexion;	
	$conexion->conectar();
	$consulta=mysql_query("SELECT $tabla.IdMedicina,$tabla.Nombre,Concentracion,$tabla.FormaFarmaceutica, Presentacion, Codigo
				FROM $tabla
				inner join mnt_grupoterapeutico 
				on mnt_grupoterapeutico.IdTerapeutico=$tabla.IdTerapeutico
				inner join farm_catalogoproductosxestablecimiento fcpe
				on fcpe.IdMedicina=$tabla.IdMedicina
				WHERE mnt_grupoterapeutico.IdTerapeutico='$opcionSeleccionada' 
				and Estupefaciente='S'
				order by $tabla.Codigo") or die(mysql_error());
	
	$conexion->desconectar();
	
	// Comienzo a imprimir el select
	echo "<select name='IdMedicina' id='IdMedicina'>";
	echo "<option value='0'>TODAS LAS MEDICINAS</option>";
	while($registro=mysql_fetch_row($consulta))
	{
		// Convierto los caracteres conflictivos a sus entidades HTML correspondientes para su correcta visualizacion
		
		// Imprimo las opciones del select
		echo "<option value='".$registro[0]."'>".$registro[5].' '.htmlentities($registro[1])." ".$registro[2]." - ".htmlentities($registro[3]).'-'.htmlentities($registro[4])."</option>";
	}			
	echo "</select>";
	break;
    }

?>