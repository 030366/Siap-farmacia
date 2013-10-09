<?php
$expediente=$_GET["Expediente"];
include ('../../Clases/class.php');
include('ClaseConsultaRecetas.php');
conexion::conectar();
$resp=ConsultaRecetas::ObtencionNombrePaciente($expediente);
$RowNombre=mysql_fetch_array($resp);
$NombrePaciente=$RowNombre['NOMBRE'];
?>
<table width="968" border="1">
  <tr class="MYTABLE">
    <td colspan="4" align="center">RECETAS REPETITIVAS PARA EL PACIENTE:&nbsp;&nbsp;<strong><?php echo $NombrePaciente;?></strong></td>
  </tr>
    <tr class="FONDO2">
    <td width="86" align="center"><strong>CANTIDAD</strong></td>
    <td width="262" align="center"><strong>MEDICAMENTO</strong></td>
    <td width="375" align="center"><strong>MEDICO</strong></td>
    <td width="217" align="center"><strong>FECHA DE RETIRO PROGRAMADA</strong></td>
  </tr>

<?php 
$resp=ConsultaRecetas::ObtencionDatosRecetas($expediente);
$resp2=ConsultaRecetas::ObtencionDatosRecetas($expediente);
$row2=mysql_fetch_array($resp2);
while($row=mysql_fetch_array($resp)){
$row2=mysql_fetch_array($resp2);
$Fechatmp=$row2['Fecha'];

$IdReceta= $row['IdReceta'];
$Cantidad = $row['Cantidad'];
$Medicamento = $row['Nombre'];
$Medico = $row['NombreEmpleado'];	
$FechaProgramada = $row['FechaEntrega'];
$Fecha = $row["Fecha"];
$NombreDia = $row['NombreDia'];
$Hoy = $row['respuesta'];
$NombreDia2 = NombreDia::CambiaNombre($NombreDia);
if($Hoy==1){$FechaProgramada="<strong>EL DIA DE HOY</strong>";}
?>
  <tr <?php if($Hoy==1){?>style="vertical-align:middle;background-color:#993300"<?php }else{?>style="vertical-align:middle"<?php }?>class="FONDO2">
    <td align="center">&nbsp;<?php echo $Cantidad;?></td>
    <td align="center">&nbsp;<?php echo $Medicamento;?></td>
    <td align="center">&nbsp;<?php echo $Medico;?></td>
    <td align="center">&nbsp;<?php echo $FechaProgramada." ".$NombreDia2; 
	if($NombreDia2=="<div style=\"color:#FF0000\">(SABADO)</div>" || $NombreDia2=="<div style=\"color:#FF0000\">(DOMINGO)</div>"){?>
	<input type="hidden" id="<?php echo "fecha".$IdReceta;?>" name="<?php echo "fecha".$IdReceta;?>" value="<?php echo $Fecha;?>" />
<input type="button" id="boton" name="boton" value="Mostrar Fechas" onclick="mostrar(<?php echo $IdReceta;?>)" />
<?php }?></td>
  </tr>
<?php
if($Fecha!=$Fechatmp){?>
<tr><td colspan="4"><hr color="#FF0000"></td></tr>
<?php }

 }?>
  <tr class="MYTABLE">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<?php conexion::desconectar();?>