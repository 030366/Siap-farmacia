<html>
<?php
$IR=$_REQUEST["IR"];//IdReceta
$F=$_REQUEST["F"];//FechaConsulta
$IdArea=$_REQUEST["IdArea"];

require('../Clases/class.php');
$query=new queries;
conexion::conectar();
$respDatos=$query->ObtenerDatosPacienteReceta(15,$IR,$IdArea);

while($row=mysql_fetch_array($respDatos)){
	//Datos Generales de todos los pacientes.- 
	$paciente=$row["NOMBRE"];
	$paciente=htmlentities(strtoupper($paciente));
	$Expediente=$row["IdNumeroExp"];
	$fechacon=$row["FechaConsulta"];
	$NombreEmpleado=$row["NombreEmpleado"];
	$Especialidad=$row["NombreSubServicio"];
	$Estado=$row["IdEstado"];
	$IdReceta=$row["IdReceta"];
	$NumeroReceta=$row["NumeroReceta"];
	$edad=$row["nac"];
	if($row["sexo"]==1){$sexo="Masculino";} else {$sexo="Femenino";}
/*Datos para Link*/
	$IdHistorialClinico=$row["IdHistorialClinico"];
	$IdReceta=$row["IdReceta"];
	$date=$row["Hoy"];
/****************************/?>
<head>
<style type="text/css">
<!--
@media print {
* { background: #fff; color: #000; } 
P{page-break-before:always;}
#Layer2 { display: none; } 
#span{ color:#FFFFFF}
.style2 {font-size: 7.5pt}
.style3 {font-size: 8pt}

}


.style2 {font-size: 7.5pt}
.style3 {font-size: 8pt}
.style6 {font-size: 9pt}
#Layer1 {
	position:absolute;
	left:0px;
	top:0px;
	width:1203px;
	height:9px;
	z-index:2;
}
#Layer2 {
	position:absolute;
	left:360px;
	top:4px;
	width:641px;
	height:23px;
	z-index:3;
}
.style4 {font-size: 7.5pt}
.style4 {font-size: 7.5pt}
.style5 {font-size: 7.5pt}
.style5 {font-size: 7.5pt}
.style7 {font-size: 7.5pt}
.style7 {font-size: 7.5pt}
.style9 {font-size: 8pt}
.style9 {font-size: 8pt}
.style10 {font-size: 8pt}
.style10 {font-size: 8pt}
.style11 {font-size: 8pt}
.style11 {font-size: 8pt}
-->
</style>
</head>
<body onLoad="javascript:print();" onBlur="this.close()">
<div id="Layer2"></div>

<?php 
		//Detalles de Receta
		$respDetalles=$query->datosReceta($IdReceta,$IdArea);
			while($row2=mysql_fetch_array($respDetalles)){
			$EstadoMedicina=$row2["EstadoMedicina"];
				if($EstadoMedicina!='I'){
				?>
  <table width="348" >
  <tr><td height="28" colspan="2" align="center"><strong><?php echo "Receta No: ".$NumeroReceta." - ";?></strong>
  <span class="style2">&nbsp;<strong><i>HNR Servicio de Farmacia</i><br>Fecha de Preparacion: <?php echo $date;?></strong></span></td>
  </tr>
        <tr>
      <td colspan="2"><span class="style2"><strong>No.Expediente:&nbsp;</strong>&nbsp;&nbsp;<strong><?php echo $Expediente; ?></strong></span></td>
    </tr>
    <tr>
      <td colspan="2"><span class="style2"><strong>Nombre Paciente:&nbsp;</strong>&nbsp;&nbsp;<?php echo"$paciente"; ?></span></td>
    </tr>
    <tr>
      <td colspan="2"><span class="style2"><strong>Especialidad: &nbsp;&nbsp;<?php echo"$Especialidad";?></strong></span></td>
    </tr>
    <tr>
      <td colspan="2"><span class="style2"><strong>Nombre M&eacute;dico:</strong> &nbsp;&nbsp;<?php echo"$NombreEmpleado";?></span></td>
    </tr>

    <?php 
		$cantidad=$row2["Cantidad"];
		$NombreMedicina=htmlentities($row2["medicina"]);
		$Concentracion=$row2["Concentracion"];
		$Presentacion=$row2["FormaFarmaceutica"];//.", ".$row2["Presentacion"];
		$dosis=$row2["Dosis"];
		$idmedicina=$row2["IdMedicina"];
		$IdReceta=$row2["IdReceta"];?>
    <tr style="border:medium;">
      <td width="62" align="center"><span class="style7"><strong>Cantidad</strong>&nbsp;</span></td>
      <td width="274" align="left"><span class="style7"><strong>Medicamento</strong>&nbsp;</span></td>
    </tr>
	<tr>
		<td align="center"><strong><?php echo $cantidad;?></strong></td>
	    <td><span class="style2"><strong><?php echo strtoupper ($NombreMedicina); ?>, <?php //echo strtoupper ($Presentacion); ?><?php echo strtoupper ($Concentracion); ?></strong></span></td>
	</tr>
	<tr>
	<td colspan="2"><span class="style3"><?php echo"$dosis"; ?></span></td>
	</tr>
	  <tr>
    <td colspan="3" align="center">&nbsp;&nbsp;<span class="style2">x</span><span class="style2">&nbsp;&nbsp;&nbsp;Conservarse en un lugar fresco y seco (NO REFRIGERAR) </span></td>
    </tr>
	  <tr>
	    <td colspan="3" align="center"><span class="style2">x</span><span class="style2">&nbsp;&nbsp;&nbsp;NO SACARLO de su envase original (blister o frasco)</span></td>
</tr></table>
	<?php if($EstadoMedicina!='I'){echo "<p>";}?>
	    <?php 
				}//IF IdEstado == S
		
		} //fin de while?>



 <?php }//fin de while respDatos
 
 conexion::desconectar();
 ?>

</body>
</html>