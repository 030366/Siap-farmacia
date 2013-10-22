<?php
//session_start();

// Array que vincula los IDs de los selects declarados en el HTML con el nombre de la tabla donde se encuentra su contenido
$listadoSelects=array(
"farmacia"=>"mnt_farmacia",
"area"=>"mnt_areafarmacia",
"select1"=>"mnt_grupoterapeutico",
"select2"=>"farm_catalogoproductos"
);

function validaSelect($selectDestino)
{
	// Se valida que el select enviado via GET exista
	global $listadoSelects;
	if(isset($listadoSelects[$selectDestino])) return true;
	else return false;
}

function validaOpcion($opcionSeleccionada)
{
	// Se valida que la opcion seleccionada por el usuario en el select tenga un valor numerico
	if(is_numeric($opcionSeleccionada)) return true;
	else return false;
}
include '../../Clases/class.php';
$selectDestino=$_REQUEST["select"]; $opcionSeleccionada=$_REQUEST["opcion"];

//if(validaSelect($selectDestino) && validaOpcion($opcionSeleccionada))
//{
	$tabla=$listadoSelects[$selectDestino];
	
	if ($tabla == "mnt_farmacia"){
	$conexion=new conexion;
	$conexion->conectar();
	$consulta=mysql_query("SELECT * FROM $tabla'") or die(mysql_error());
	$conexion->desconectar();
	
	// Comienzo a imprimir el selec
	echo "<select name='".$selectDestino."' id='".$selectDestino."' onChange='cargaContenido(this.id)'>";
	echo "<option value='0'>TODAS LAS FARMACIAS</option>";
	while($registro=mysql_fetch_row($consulta))
	{
		// Convierto los caracteres conflictivos a sus entidades HTML correspondientes para su correcta visualizacion
		$registro[1]=htmlentities($registro[1]);
		// Imprimo las opciones del select
		echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
	}			
	echo "</select>";}
	
	
	
	if($tabla=="mnt_areafarmacia"){
	$conexion=new conexion;	
	$conexion->conectar();
	$consulta=mysql_query("SELECT mnt_areafarmacia.IdArea,mnt_areafarmacia.Area
						   FROM mnt_areafarmacia
						   inner join mnt_farmacia
						   on mnt_farmacia.IdFarmacia=mnt_areafarmacia.IdFarmacia
						   WHERE mnt_farmacia.IdFarmacia='$opcionSeleccionada'
						    and mnt_areafarmacia.IdArea <> '7'") or die(mysql_error());
	
	$conexion->desconectar();
	
	// Comienzo a imprimir el select
	echo "<select name='".$selectDestino."' id='".$selectDestino."' onChange='cargaContenido(this.id)' onmouseover=\"Tip('Selecci&oacute;n de &Aacute;rea')\" onmouseout=\"UnTip()\">";
	echo "<option value='0'>TODAS LAS AREAS</option>";
	while($registro=mysql_fetch_row($consulta))
	{
		// Convierto los caracteres conflictivos a sus entidades HTML correspondientes para su correcta visualizacion
		$registro[1]=htmlentities($registro[1]);
		// Imprimo las opciones del select
		echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
	}			
	echo "</select>";}
	
	
		if ($tabla == "mnt_grupoterapeutico"){
	$conexion=new conexion;
	$conexion->conectar();
	$consulta=mysql_query("SELECT * FROM $tabla ORDER BY GrupoTerapeutico") or die(mysql_error());
	$conexion->desconectar();
	
	// Comienzo a imprimir el select
	echo "<select name='".$selectDestino."' id='".$selectDestino."' onChange='cargaContenido(this.id)'>";
	echo "<option value='0'>TODOS LOS GRUPOS</option>";
	while($registro=mysql_fetch_row($consulta))
	{
		// Convierto los caracteres conflictivos a sus entidades HTML correspondientes para su correcta visualizacion
		$registro[1]=htmlentities($registro[1]);
		// Imprimo las opciones del select
		if($registro[1]!="--"){
		echo "<option value='".$registro[0]."'>".$registro[1]."</option>";}
	}			
	echo "</select>";}
	
	
	
if($tabla=="farm_catalogoproductos"){
	$conexion=new conexion;	
	$conexion->conectar();
	$consulta=mysql_query("SELECT $tabla.IdMedicina,$tabla.Nombre,$tabla.FormaFarmaceutica
						   FROM $tabla
						   inner join mnt_grupoterapeutico 
						   on mnt_grupoterapeutico.IdTerapeutico=$tabla.IdTerapeutico
						   WHERE mnt_grupoterapeutico.IdTerapeutico='$opcionSeleccionada' order by $tabla.Nombre") or die(mysql_error());
	
	$conexion->desconectar();
	
	// Comienzo a imprimir el select
	echo "<select name='".$selectDestino."' id='".$selectDestino."' onChange='cargaContenido(this.id)' onmouseover=\"Tip('Selecci&oacute;n de Medicamento')\" onmouseout=\"UnTip()\">";
	echo "<option value='0'>TODAS LAS MEDICINAS</option>";
	while($registro=mysql_fetch_row($consulta))
	{
		// Convierto los caracteres conflictivos a sus entidades HTML correspondientes para su correcta visualizacion
		$registro[1]=htmlentities($registro[1]);
		// Imprimo las opciones del select
		echo "<option value='".$registro[0]."'>".$registro[1].", ".$registro[2]."</option>";
	}			
	echo "</select>";}	
	

?>