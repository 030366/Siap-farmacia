<?php
session_start();

// Array que vincula los IDs de los selects declarados en el HTML con el nombre de la tabla donde se encuentra su contenido
$listadoSelects=array(
"farmacia"=>"mnt_farmacia",
"area"=>"mnt_areafarmacia",
"select3"=>"otra"
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
	
	// Comienzo a imprimir el select
	echo "<select name='".$selectDestino."' id='".$selectDestino."' onChange='cargaContenido8(this.id)'>";
	echo "<option value='0'>TODOS LOS GRUPOS</option>";
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
	$consulta=mysql_query("SELECT $tabla.IdArea,$tabla.Area
						   FROM $tabla
						   inner join mnt_farmacia 
						   on mnt_farmacia.IdFarmacia=$tabla.IdFarmacia
                                                   inner join mnt_areafarmaciaxestablecimiento mafe
                                                   on mafe.IdArea=$tabla.IdArea
						   WHERE mnt_farmacia.IdFarmacia='$opcionSeleccionada'
						   and mafe.Habilitado='S' and mafe.IdArea <> 7
                                                   and mafe.IdEstablecimiento=".$_SESSION["IdEstablecimiento"]."
                                                    and mafe.IdModalidad=".$_SESSION["IdModalidad"]) or die(mysql_error());
	
	$conexion->desconectar();
	
	// Comienzo a imprimir el select
	echo "&nbsp;<select name='".$selectDestino."' id='".$selectDestino."' onChange='cargaContenido8(this.id)'>";
	echo "<option value='0'>Seleccione una area</option>";
	while($registro=mysql_fetch_row($consulta))
	{
		// Convierto los caracteres conflictivos a sus entidades HTML correspondientes para su correcta visualizacion
		$registro[1]=htmlentities($registro[1]);
		// Imprimo las opciones del select
		echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
	}			
	echo "</select>";}
	
	
	if($tabla == "otra"){

if($seguridad==1){
$esta2="";
}
else{
$esta2=$_SESSION["estaCod"]; }
      $conexion=new conexion;
	$conexion->conectar();
	//$consulta2=mysql_query("SELECT NOMBRE FROM $tabla WHERE sib='$opcionSeleccionada' ORDER BY nombre") or die(mysql_error());
	if($esta2!='' && $esta2!='0' && $seguridad!='1'){
	$consulta2=mysql_query("SELECT estasib.NOMBRE, tipo_establecimiento.tipo FROM estasib, tipo_establecimiento WHERE estasib.idest='$esta2' and estasib.id_tipo=tipo_establecimiento.id_tipo ORDER BY tipo, NOMBRE") or die(mysql_error());
	}else{
	$consulta2=mysql_query("SELECT estasib.NOMBRE, tipo_establecimiento.tipo FROM estasib, tipo_establecimiento WHERE estasib.sib='$opcionSeleccionada' and estasib.id_tipo=tipo_establecimiento.id_tipo ORDER BY tipo, NOMBRE") or die(mysql_error());}
	$conexion->desconectar();
	
	// Comienzo a imprimir el select
	echo "<select name='".$selectDestino."' id='".$selectDestino."' onChange='cargaContenido8(this.id)'>";
	echo "<option value='0'>Elige</option>";
	while($registro2=mysql_fetch_row($consulta2))
	{
		// Convierto los caracteres conflictivos a sus entidades HTML correspondientes para su correcta visualizacion
		//$registro2[1]=htmlentities($registro2[0]);
		// Imprimo las opciones del select
//		echo "<option>".$registro2[0]."</option>";?>

		<option value="<?php echo $registro2[0]; ?>"><?php echo $registro2[1]." ".$registro2[0]; ?></option>;
<?php
	}			
	echo "</select>";
	
	}
//}
?>