<?php
require('include/ClasePersonalReceta.php');
conexion::conectar();
$IdReceta=$_GET["IdReceta"];
$NombrePaciente=$_GET["NombrePaciente"];
$Datos=Obtencion::DetalleReceta($IdReceta);
?>
<table class="MYTABLE" width="935" border="1">
	<tr><td colspan="6" align="center" class="MYTABLE"><strong>Nombre de Paciente: <h4><?php echo $NombrePaciente;?></h4></strong></td></tr>
	<tr class="MYTABLE">
	  <td width="57"><div align="center"><strong>Cantidad</strong></div></td>
      <td width="199"><div align="center"><strong>Nombre de Medicina </strong></div></td>
      <td width="88"><div align="center"><strong>Concentraci&oacute;n</strong></div></td>
      <td width="308"><div align="center"><strong>Presentaci&oacute;n</strong></div></td>
      <td width="168"><strong>Dosificaci&oacute;n</strong></td>
      <td width="89"><div align="center"><strong>Satisfecho</strong></div></td>
    </tr>
    <?php 
	while($row2=mysql_fetch_array($Datos)){
		$cantidad=$row2["Cantidad"];
		$NombreMedicina=$row2["medicina"];
		$Concentracion=$row2["Concentracion"];
		$forma=$row2["FormaFarmaceutica"];
		$presentacion=$row2["Presentacion"];
		$Presentacion=$row2["FormaFarmaceutica"].", ".$row2["Presentacion"];
		$dosis=$row2["Dosis"];
		$idmedicina=$row2["IdMedicina"];
		$Estado=$row2["IdEstado"];
		?>
    <tr class="FONDO2" <?php if($Estado=='I'){?> style="background-color:#FF6633"<?php }//IF ESTADOMEDICINA?>>
	
      <td align="center" style="vertical-align:middle"><span  onmouseover="Tip('Cantidad de Medicamento<br>recetado por Medico')" onmouseout="UnTip()"><?php echo"$cantidad"; ?></span></td>
	  
      <td align="center" style="vertical-align:middle"><span onmouseover="Tip('<?php echo $NombreMedicina;?>')" onmouseout="UnTip()"><?php echo"$NombreMedicina"; ?></span></td>
	  
      <td align="center" style="vertical-align:middle"><span onmouseover="Tip('Concentraci&oacute;n: <?php echo $Concentracion;?>')" onmouseout="UnTip()"><?php echo"$Concentracion"; ?></span></td>
	  
      <td style="vertical-align:middle"><span onmouseover="Tip('Presentaci&oacute;n: <?php echo $forma."<br>".$presentacion;?>')" onmouseout="UnTip()"><?php echo htmlentities($Presentacion); ?></span></td>
	  
      <td><span onmouseover="Tip('Dosificaci&oacute;n: <?php echo $dosis;?>')" onmouseout="UnTip()"><?php echo htmlentities($dosis); ?></span></td>
	  
      <td align="center" style="vertical-align:middle"><?php
		if($Estado=='S'){ echo "<h5>SI</h5>";}else{	echo "<h5>NO</h5>";}?>
		</td>
    </tr>
    <?php } //fin de while?>
    <tr class="MYTABLE">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td align="center">&nbsp;</td>
    </tr>
  </table>
  <?php conexion::desconectar();?>