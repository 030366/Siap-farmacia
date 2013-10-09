<?php include('../../Clases/class.php');
include('MonitoreoClass.php');
conexion::conectar();
$combos=new Combos;
switch($_GET["Bandera"]){

case 1:
//Farmacias
   $resp=$combos->Farmacias();
   $out="<table><tr><td>Farmacia: </td><td><select id='IdFarmacia' onchange='CargarAreas(this.value)'>
		<option value='0'>[SELECCIONE]</option>";
   while($row=mysql_fetch_array($resp)){
	$out.="<option value='".$row[0]."'>".$row[1]."</option>";
   }
   $out.="</select></td></tr>
    <tr><td>Area: </td><td><div id='ComboAreas'><select id='IdArea' disabled='true'>
		<option value='0'>[SELECCIONE]</option></select></div></td></tr></table>";
   echo $out;
break;
case 2:
//areas
$IdFarmacia=$_GET["IdFarmacia"];
$resp=$combos->Areas($IdFarmacia);
   $out="<select id='IdArea' onchange='LoadMedicamento();'>
		<option value='0'>[SELECCIONE]</option>";
   while($row=mysql_fetch_array($resp)){
	$out.="<option value='".$row[0]."'>".$row[1]."</option>";
   }
   $out.="</select>";
	echo $out;
break;
}

conexion::desconectar();
?>