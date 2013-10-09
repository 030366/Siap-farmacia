<?php session_start();
if(!isset($_SESSION["nivel"])){
echo "ERROR_SESSION";
}else{
    
    $IdEstablecimiento=$_SESSION["IdEstablecimiento"];
    $IdModalidad=$_SESSION["IdModalidad"];
    
// Array que vincula los IDs de los selects declarados en el HTML con el nombre de la tabla donde se encuentra su contenido
$listadoSelects=array(
"IdFarmacia"=>"mnt_farmacia",
"IdArea"=>"mnt_areafarmacia",
"IdEspecialidad"=>"mnt_subespecialidad",
"IdMedico"=>"mnt_empleados");

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
	echo "<select name='".$selectDestino."' id='".$selectDestino."' onChange='cargaContenido8(this.id)'>";
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
		$plus='';
	//if($opcionSeleccionada==3){$plus='or mnt_farmacia.IdFarmacia=2';}
	$consulta=mysql_query("SELECT mnt_areafarmacia.IdArea,mnt_areafarmacia.Area
						   FROM mnt_areafarmacia
						   inner join mnt_farmacia
						   on mnt_farmacia.IdFarmacia=mnt_areafarmacia.IdFarmacia
                                                   inner join mnt_areafarmaciaxestablecimiento mafxe
                                                   on mafxe.IdArea=mnt_areafarmacia.IdArea
						   WHERE mnt_farmacia.IdFarmacia='$opcionSeleccionada'
						   and mnt_areafarmacia.IdArea<>7 
                                                   and mafxe.Habilitado = 'S'
                                                   and mafxe.IdEstablecimiento=$IdEstablecimiento
                                                   and mafxe.IdModalidad=$IdModalidad
							".$plus) or die(mysql_error());
	
	$conexion->desconectar();
	
	// Comienzo a imprimir el select
	echo "<select name='".$selectDestino."' id='".$selectDestino."' onChange='javascript:document.getElementById(\"CodigoFarmacia\").focus();CargarAreaOrigen(this.value);'>";
	echo "<option value='0'>[Seleccione ...]</option>";
	while($registro=mysql_fetch_row($consulta))
	{
		// Convierto los caracteres conflictivos a sus entidades HTML correspondientes para su correcta visualizacion
		$registro[1]=htmlentities($registro[1]);
		// Imprimo las opciones del select
		echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
	}			
	echo "</select>";}
	
	
	
	if ($tabla == "mnt_subespecialidad"){
	$conexion=new conexion;
	$conexion->conectar();
	$consulta=mysql_query("SELECT IdSubEspecialidad,NombreSubEspecialidad FROM mnt_subespecialidad order by NombreSubEspecialidad") or die(mysql_error());
	$conexion->desconectar();
	
	// Comienzo a imprimir el select
	echo "<select name='".$selectDestino."' id='".$selectDestino."' onChange='cargaContenido8(this.id)'>";
	echo "<option value='0'>[Seleccione ...]</option>";
	while($registro=mysql_fetch_row($consulta))
	{
		// Convierto los caracteres conflictivos a sus entidades HTML correspondientes para su correcta visualizacion
		$registro[1]=htmlentities($registro[1]);
		// Imprimo las opciones del select
		echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
	}			
	echo "</select>";}
	
	
	
	if($tabla=="mnt_empleados"){
	$conexion=new conexion;	
	$conexion->conectar();
	$consulta=mysql_query("select mnt_empleados.IdEmpleado,mnt_empleados.NombreEmpleado
							from mnt_empleados
							inner join mnt_subespecialidad
							on mnt_subespecialidad.IdSubEspecialidad=mnt_empleados.IdSubEspecialidad
							where mnt_empleados.IdSubEspecialidad='$opcionSeleccionada' order by mnt_empleados.NombreEmpleado") or die(mysql_error());
	
	$conexion->desconectar();
	
	// Comienzo a imprimir el select
	echo "<select name='".$selectDestino."' id='".$selectDestino."'>";
	echo "<option value='0'>[Seleccione ...]</option>";
	while($registro=mysql_fetch_row($consulta))
	{
		// Convierto los caracteres conflictivos a sus entidades HTML correspondientes para su correcta visualizacion
		$registro[1]=htmlentities($registro[1]);
		// Imprimo las opciones del select
		echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
	}			
	echo "</select>";}
	
	
	if($tabla == "farm_recetas"){

    $conexion=new conexion;
	$conexion->conectar();
	//$consulta2=mysql_query("SELECT NOMBRE FROM $tabla WHERE sib='$opcionSeleccionada' ORDER BY nombre") or die(mysql_error());

	$consulta2=mysql_query("select distinct farm_catalogoproductos.IdMedicina, farm_catalogoproductos.Nombre, 							
							farm_catalogoproductos.FormaFarmaceutica
							from farm_catalogoproductos
							inner join farm_medicinarecetada
							on farm_medicinarecetada.IdMedicina=farm_catalogoproductos.IdMedicina
							inner join farm_recetas
							on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
							inner join sec_historial_clinico
							on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
							inner join mnt_empleados
							on mnt_empleados.IdEmpleado=sec_historial_clinico.IdEmpleado
							where mnt_empleados.IdEmpleado='$opcionSeleccionada' 
							and year(farm_recetas.Fecha)=year(curdate())
							and (farm_recetas.IdEstado='E' OR farm_recetas.IdEstado='T' OR farm_recetas.IdEstado='ER' OR farm_recetas.IdEstado='RT')	
							order by farm_catalogoproductos.Nombre") or die(mysql_error());
	$conexion->desconectar();
	
	// Comienzo a imprimir el select
	echo "<select name='".$selectDestino."' id='".$selectDestino."' onChange='cargaContenido8(this.id)' onmouseover=\"Tip('Selecci&oacute;n de Medicamentos')\" onmouseout=\"UnTip()\">";
	echo "<option value='0'>TODAS LAS MEDICINAS</option>";
	while($registro2=mysql_fetch_row($consulta2)){?>
		<option value="<?php echo $registro2[0]; ?>"><?php echo $registro2[1].", ".$registro2[2]; ?></option>;
<?php
	}			
	echo "</select>";
	
	}//if farm_recetas
}
?>