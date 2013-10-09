<?php 
include('include/ClasePersonalReceta.php');
$IdPersonal=$_GET["IdPersonal"];
?>
<style type="text/css">
<!--
#Layer112 {
position:absolute;
	left:5px;
	top:4px;
	width:897px;
	height:144px;
	z-index:1;
}
#Layer2 {	position:absolute;
	left:3px;
	top:5px;
	width:955px;
	height:30px;
	z-index:2;
}
.style1 {color: #FF0000}
.style3 {color: #0000CC}
#Layer3 {position:absolute;
	left:2px;
	top:190px;
	width:1001px;
	height:30px;
	z-index:6;
}

#Layer41 {position:absolute;
	left:-199px;
	top:-39px;
	width:55px;
	height:31px;
	z-index:7;
}
#Layer71 {position:absolute;
	left:303px;
	top:39px;
	width:596px;
	height:23px;
	z-index:5;
}
-->
</style>

<div id="Layer112">
<?php 
$resp2=Obtencion::ObtenerPersonalReceta($IdPersonal);
$Datos=mysql_fetch_array($resp2);
$NombreEmpleado=$Datos["Nombre"];
$resp=Obtencion::ObtenerDatosRecetas($IdPersonal);
if($test=mysql_fetch_array($resp)){
$resp=Obtencion::ObtenerDatosRecetas($IdPersonal);
?>
<form name="datos" id="datos" action="">
  <table width="896" id="tabla">
    <tr class="MYTABLE">
      <td colspan="7" align="center"><strong>Recetas Preparadas por: <?php echo $NombreEmpleado;?> </strong></td>
    </tr>
    <tr>
      <td width="126" class="FONDO" align="center">Estado</td>
      <td width="126" class="FONDO" align="center">Receta # </td>
      <td colspan="3" class="FONDO" align="center">Nombre Paciente </td>
      <td width="340" align="center" class="FONDO">Nombre Medico </td>
      <td width="203" align="center" class="FONDO">Detalles </td>
    </tr>
<?php while($row=mysql_fetch_array($resp)){
$IdReceta=$row["IdReceta"];
$NombrePaciente=$row["NombrePaciente"];
$Medico=$row["NombreEmpleado"];

?>
    <tr id="ROW<?php echo $IdReceta;?>">
      <td class="FONDO" align="center"><div id="IMG<?php echo $IdReceta;?>"><img src="Iconos/wainting.JPG" /></div></td>
	        <td class="FONDO" align="center">&nbsp;<?php echo $IdReceta;?></td>
      <td colspan="3"  class="FONDO" align="center" style="vertical-align:middle">&nbsp;<?php echo $NombrePaciente;?></td>
      <td  class="FONDO" align="center" style="vertical-align:middle">&nbsp;<?php echo $Medico;?></td>
      <td  class="FONDO" align="center" style="vertical-align:middle"><input type="button" name="add" value="Detalles de Receta" tabindex="6" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099" onclick="DetalleReceta(<?php echo $IdReceta;?>)" /></td>
    </tr>
<?php }//fin de while?>	
    <tr>
      <td class="FONDO">&nbsp;</td>
      <td colspan="4"  class="FONDO">&nbsp;</td>
      <td colspan="4"  class="FONDO">&nbsp;</td>
    </tr>
	
  </table>
</form>
</div>
<?php }
else{
echo "<h2>Este usuario no posee registros ....</h2>";
}
?>
