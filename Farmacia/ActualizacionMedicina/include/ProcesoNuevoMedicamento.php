<?php
include('../../Clases/class.php');
include('ClaseNuevoMedicamento.php');
$Bandera=$_GET["Bandera"];
$new=new NuevoMedicamento;
/*Bandera que determina si es introduccion de nuevo medicamento o asignacion de especialidades*/
conexion::conectar();
switch($Bandera){ 
case 1:
//SI YA ESTA EL MEDICMANETO DENTRO DE LA BASE DE DATOS
$IdMedicina=$_GET["IdMedicina"];

//INTRODUCCION DE MEDICAMENTO
$codigo=strtoupper($_GET["Codigo"]);
$nombre=strtoupper($_GET["Nombre"]);

$concentracion=strtoupper($_GET["Concentracion"]);
$FormaFarmaceutica=strtoupper($_GET["FormaFarmaceutica"]);
$presentacion=strtoupper($_GET["Presentacion"]);

$new->ActualizarDatosGenerales($IdMedicina,$codigo,$nombre,$concentracion,$FormaFarmaceutica,$presentacion);


/*GENERALES DE CAMBIO POR SELECCION*/
$IdGrupo=$_GET["Grupo"];
$IdUnidadMedida=$_GET["UnidadMedida"];
if($IdGrupo!=0){$new->ActualizarGrupo($IdGrupo,$IdMedicina);}
if($IdUnidadMedida!=0){$new->ActualizarUnidadMedida($IdUnidadMedida,$IdMedicina);}



break;

case 2:

//ASIGNACION DE ESPECIALIDADES
$IdMedicina=$_GET["IdMedicina"];
$Especialidad=$_GET["Especialidad"];
echo "<input type='hidden' id='IdMedicina2' name='IdMedicina2' value='".$IdMedicina."'>";
    $Nombre=$new->GetName($IdMedicina);
if($Especialidad==0){
echo $Nombre." - CON - TODAS LAS ESPECIALIDADES";
}else{
	$NombreEspecialidad=$new->GetEspecialidad($Especialidad);
echo $Nombre." - CON - ".$NombreEspecialidad;
}
break;

case 3:
$IdMedicina=$_GET["IdMedicina"];
$IdArea=$_GET["IdArea"];
$queryInsert="insert into mnt_areamedicina (IdArea,IdMedicina) values('$IdArea','$IdMedicina')";
mysql_query($queryInsert);
$querySelect="select IdAreaMedicina 
			from mnt_areamedicina
			order by IdAreaMedicina desc
			limit 1";
$resp=mysql_fetch_array(mysql_query($querySelect));
echo 'Area Asignada<br><input type="hidden" id="IdAreaMedicina" name="IdAreaMedicina" value="'.$resp[0].'">';
break;

case 4:
$IdMedicina=$_GET["IdMedicina"];
$IdArea=$_GET["IdArea"];
$IdAreaMedicina=$_GET["IdAreaMedicina"];

$queryUpdate="update mnt_areamedicina set Dispensada='$IdArea' where IdAreaMedicina='$IdAreaMedicina'";
mysql_query($queryUpdate);
echo "OK";
break;
}//switch
conexion::desconectar();
?>