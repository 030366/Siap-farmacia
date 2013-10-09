<?php session_start();

include ('../Clases/class.php');

$opcionSeleccionada=$_GET["valor"];
switch($_GET["Combo"]){

case "mnt_areafarmacia":
	$conexion=new conexion;	
	$conexion->conectar();
		
	$consulta=mysql_query("SELECT distinct mnt_areafarmacia.IdArea,mnt_areafarmacia.Area
				FROM mnt_areafarmacia
				inner join farm_recetas
				on farm_recetas.IdAreaOrigen=mnt_areafarmacia.IdArea
				WHERE farm_recetas.IdFarmacia='$opcionSeleccionada'
				and mnt_areafarmacia.IdArea <> '7'
				
				and Habilitado='S'") or die(mysql_error());
	
	$conexion->desconectar();
	
	// Comienzo a imprimir el select
	echo "<select name='area' id='area'>";
	echo "<option value='0'>SELECCIONE UNA AREA</option>";
	while($registro=mysql_fetch_row($consulta))
	{
		// Convierto los caracteres conflictivos a sus entidades HTML correspondientes para su correcta visualizacion
		$registro[1]=htmlentities($registro[1]);
		// Imprimo las opciones del select
		echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
	}			
	echo "</select>";
	break;
	
	case "mnt_empleados":
	$conexion=new conexion;	
	$conexion->conectar();
	
	if($opcionSeleccionada!=0){
	   $query="select distinct mnt_empleados.IdEmpleado,mnt_empleados.NombreEmpleado
		from mnt_empleados
		inner join sec_historial_clinico
		on sec_historial_clinico.IdEmpleado=mnt_empleados.IdEmpleado
		where sec_historial_clinico.IdSubServicio='$opcionSeleccionada'
		order by mnt_empleados.NombreEmpleado";
	}else{
	   $query="select distinct mnt_empleados.IdEmpleado,NombreEmpleado
		from mnt_empleados
		inner join sec_historial_clinico
		on sec_historial_clinico.IdEmpleado=mnt_empleados.IdEmpleado
		
		where NombreEmpleado is not null
		order by NombreEmpleado";
	}


	$consulta=mysql_query($query) or die(mysql_error());
	
	$conexion->desconectar();
	
	// Comienzo a imprimir el select
	echo "<select name='IdEmpleado' id='IdEmpleado'>";
	echo "<option value='0'>TODOS LOS MEDICOS</option>";
	while($registro=mysql_fetch_row($consulta))
	{
		// Convierto los caracteres conflictivos a sus entidades HTML correspondientes para su correcta visualizacion
		$registro[1]=htmlentities($registro[1]);
		// Imprimo las opciones del select
		echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
	}			
	echo "</select>";

	break;


}
	

?>