<?php include('../../Clases/class.php');
include('MonitoreoClass.php');
conexion::conectar();
$combos=new Combos;
switch($_GET["Bandera"]){

case 1:
//Farmacias
	$TipoFarmacia=$_GET["TipoFarmacia"];
	
   $resp=$combos->Farmacias();
if($TipoFarmacia!=1){
   $out="<table width='850'>
<tr><td>
<div id='CombosPrint'>
	<table width='100%'>
	<tr class='MYTABLE'><td colspan=2 align='center'><strong>REPORTE MEDICAMENTO PROXIMO A AGOTARSE</strong></td></tr>
	<tr class='FONDO'><td align='right'><strong>Farmacia: </strong></td><td><select id='IdFarmacia' onchange='CargarAreas(this.value)'>
		<option value='0'>[SELECCIONE]</option>";
   while($row=mysql_fetch_array($resp)){
	$out.="<option value='".$row[0]."'>".$row[1]."</option>";
   }
   $out.="</select></td></tr>
    <tr class='FONDO'><td align='right'><strong>Area: </strong></td><td><div id='ComboAreas'><select id='IdArea' disabled='true'>
		<option value='0'>[SELECCIONE]</option></select></div></td></tr>
	<tr class='MYTABLE'><td colspan=2 align='right'><input type='button' id='Generar' name='Generar' value='Generar Reporte' onclick='valida();'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
	</table>
</div>
	</td></tr>
	<tr><td align='center'><div id='Monitoreo'></div></td></tr>
	</table>";
}else{

   $out="<table width='850'>
<tr><td>
<div id='CombosPrint'>
	<table width='100%'>
	<tr class='MYTABLE'><td colspan=2 align='center'><strong>REPORTE MEDICAMENTO PROXIMO A AGOTARSE</strong></td></tr>
	<tr class='FONDO'><td align='right'><strong>Farmacia: </strong></td><td><select id='IdFarmacia' disabled='disabled'>
		<option value='0'>[GENERAL]</option>";
$out.="</select></td></tr>
    <tr class='FONDO'><td align='right'><strong>Area: </strong></td><td><div id='ComboAreas'><select id='IdArea' disabled='true'>
		<option value='0'>[GENERAL]</option></select></div></td></tr>
	<tr class='MYTABLE'><td colspan=2 align='right'><input type='button' id='Generar' name='Generar' value='Generar Reporte' onclick='valida();'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
	</table>
</div>
	</td></tr>
	<tr><td align='center'><div id='Monitoreo'></div></td></tr>
	</table>";

}


   echo $out;
break;
case 2:
//areas
$IdFarmacia=$_GET["IdFarmacia"];
$resp=$combos->Areas($IdFarmacia);
   $out="<select id='IdArea' onchange=''>
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