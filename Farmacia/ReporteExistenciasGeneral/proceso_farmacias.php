<?php session_start();
include '../Clases/class.php';

$opcionSeleccionada = $_GET["valor"];

   switch($_GET["Combo"]){
	
	case "mnt_areafarmacia":
	$conexion=new conexion;	
	$conexion->conectar();
	$consulta=mysql_query("SELECT mnt_areafarmacia.IdArea,mnt_areafarmacia.Area
				FROM mnt_areafarmacia
				inner join mnt_farmacia
				on mnt_farmacia.IdFarmacia=mnt_areafarmacia.IdFarmacia
				WHERE mnt_farmacia.IdFarmacia='$opcionSeleccionada'
				and mnt_areafarmacia.IdArea <> '7'
				and Habilitado='S'") or die(mysql_error());
	
	$conexion->desconectar();
	
	// Comienzo a imprimir el select
	echo "<select name='area' id='area'>";
	echo "<option value='0'>TODAS LAS AREAS</option>";
	while($registro=mysql_fetch_row($consulta))
	{
		// Convierto los caracteres conflictivos a sus entidades HTML correspondientes para su correcta visualizacion
		$registro[1]=htmlentities($registro[1]);
		// Imprimo las opciones del select
		echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
	}			
	echo "</select>";

       break;


	
	case "farm_catalogoproductos":
	$conexion=new conexion;	
	$conexion->conectar();
	$consulta=mysql_query("SELECT farm_catalogoproductos.IdMedicina,farm_catalogoproductos.Nombre,farm_catalogoproductos.FormaFarmaceutica
			FROM farm_catalogoproductos
			inner join mnt_grupoterapeutico 
			on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico
			inner join farm_catalogoproductosxestablecimiento fcpe
			on fcpe.IdMedicina=farm_catalogoproductos.IdMedicina
			WHERE mnt_grupoterapeutico.IdTerapeutico='$opcionSeleccionada' 
			and IdEstablecimiento=".$_SESSION["IdEstablecimiento"]."
			order by farm_catalogoproductos.Nombre") or die(mysql_error());
	
	$conexion->desconectar();
	
	// Comienzo a imprimir el select
	echo "<select name='IdMedicina' id='IdMedicina'>";
	echo "<option value='0'>TODAS LAS MEDICINAS</option>";
	while($registro=mysql_fetch_row($consulta))
	{
		// Convierto los caracteres conflictivos a sus entidades HTML correspondientes para su correcta visualizacion
		$registro[1]=htmlentities($registro[1]);
		// Imprimo las opciones del select
		echo "<option value='".$registro[0]."'>".$registro[1].", ".$registro[2]."</option>";
	}			
	echo "</select>";
	break;
   }	
	

?>