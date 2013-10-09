<?php session_start();
if(!isset($_SESSION["nivel"])){?>
<script language="javascript">
window.location='../../signIn.php';
</script>
<?php
}else{
if($_SESSION["nivel"]!=1 and $_SESSION["nivel"]!=2 and $_SESSION["nivel"]!=4){?>
<script language="javascript">
window.location='../../index.php?Permiso=1';
</script>
<?php
}else{
$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
require('../../Clases/class.php');
include('Funciones.php');
$query=new queries;
conexion::conectar();
?>
<html>
<head>
<style type="text/css">
#Layer11 {
	position:absolute;
	left:0px;
	top:0px;
	width:1015px;
	height:232px;
	z-index:1;
}
#Layer61 {position:absolute;
	left:21px;
	top:12px;
	width:847px;
	height:34px;
	z-index:5;
}
#Layer21 {
	position:absolute;
	left:901px;
	top:128px;
	width:52px;
	height:20px;
	z-index:6;
}
#Layer31 {
	position:absolute;
	left:855px;
	top:10px;
	width:63px;
	height:24px;
	z-index:7;
}
@media print {
* { background: #fff; color: #000; }
html { font: 9pt Arial, Helvetica, sans-serif; }
#Layer61, #Layer21, #Layer31 { display: none; }
#nav, #nav2, #about { display: none; }
#footer { display:none;}
#span{ color:#FFFFFF}
@page { size: 8.5in 11in; margin: 0.5cm }
/*P{page-break-after:inherit}*/
}
</style>
<script src="../PrintReport.js" language="javascript"></script>
</head>
<body>
<script language="javascript" src="../../tooltip/wz_tooltip.js"></script>
<?php 
$FechaInicio=explode('-',$_REQUEST["fechaInicio"]);
$FechaFin=explode('-',$_REQUEST["fechaFin"]);
$FechaInicio2=$FechaInicio[2].'-'.$FechaInicio[1].'-'.$FechaInicio[0];
$FechaFin2=$FechaFin[2].'-'.$FechaFin[1].'-'.$FechaFin[0];
$FechaInicio=$_REQUEST["fechaInicio"];
$FechaFin=$_REQUEST["fechaFin"];

/**********************INFORMACION DE REPORTE**********************************/
if(isset($_GET["IdSubEspecialidad"])){$IdSubEspecialidad=$_GET["IdSubEspecialidad"];}else{$IdSubEspecialidad=0;}
if(isset($_GET["select1"])){$IdGrupoTerapeutico=$_GET["select1"];}else{$IdGrupoTerapeutico=0;}
if(isset($_GET["select2"])){$IdMedicina=$_GET["select2"];}else{$IdMedicina=0;}
/******************************************************************************/

?>
<div id="Layer31" align="right">
	<input type="button" id="imprimir" name="imprimir" value="IMPRIMIR" onClick="ImprimirReporte(this.form);" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099">
</p>
	<input type="button" id="cerrar" name="cerrar" value="CERRAR" onClick="javascript:self.close()" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099">	
</div>

<div id="Layer11">

  <table width="967" cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td colspan="11" align="center">HOSPITAL NACIONAL ROSALES<br>
        <strong>CONSUMO DE MEDICAMENTOS POR SERVICIOS </strong> <br>
        PERIODO DEL: <?php echo"$FechaInicio2";?> AL <?php echo"$FechaFin2";?> .- <br>
        <div align="left">Fecha de Emisi&oacute;n:
          <?php 
$DateNow=date("d/m/Y");
echo"$DateNow";?>
        </div></td>
    </tr>
  </table>
 
<table width="1004" border="1">
<?php
	$Total=0;
//*************************************
//******************************* QUERIES Y RECORRIDOS
$respServicios=Servicios($IdSubEspecialidad,$FechaInicio,$FechaFin);
while($rowServicios=mysql_fetch_array($respServicios)){
	$IdSubEspecialidad=$rowServicios[0];
	$NombreSubEspecialidad=$rowServicios[1];
		$SubTotalServicio=0;
	?>
			
			<tr class="MYTABLE">
			  <td colspan="11" align="center" style="background:#666666;">
		<strong><h2><?php echo $NombreSubEspecialidad;?></h2></strong></td>
			</tr>	

	<?php 
	$nombreTera=NombreTera($IdGrupoTerapeutico,$IdSubEspecialidad,$FechaInicio,$FechaFin);
	while($grupos=mysql_fetch_array($nombreTera)){
		$NombreTerapeutico=$grupos["GrupoTerapeutico"];
		$IdTerapeutico=$grupos["IdTerapeutico"];
			$SubTotal=0;
		?>

			<tr style="background:#CCCCCC;">
			  <td colspan="11" align="center">
		&nbsp;<strong><?php echo"$NombreTerapeutico";?></strong></td>
			</tr>
				<tr class="FONDO2">
			<th width="47" scope="col">Codigo</th>
			  <th width="182" scope="col">Medicamento</th>
			  <th width="68" scope="col">Concen.</th>
			  <th width="52" scope="col">Prese.</th>
			  <th width="53" scope="col">Recetas</th>
			  <th width="54" scope="col">Satis.</th>
			  <th width="69" scope="col">No Satis.</th>
			  <th width="75">Unidad de Medida</th>
			  <th width="88" scope="col">Consumo</th>
			  <th width="138" scope="col">Precio</th>
			  <th width="108" scope="col">Monto[$]</th>
			</tr>
			<?php
		$resp1=QueryExterna($IdTerapeutico,$IdMedicina,$IdSubEspecialidad,$FechaInicio,$FechaFin);
			while($row=mysql_fetch_array($resp1)){
		$GrupoTerapeutico=$IdTerapeutico;
		$Medicina=$row["IdMedicina"];
		$codigoMedicina=$row["Codigo"];
		$NombreMedicina=$row["Nombre"];
		$concentracion=$row["Concentracion"];
		$presentacion=$row["FormaFarmaceutica"];
		
		$Nrecetas=0;//conteo de recetas
		$consumo=0;
		
		
		$respuesta=ObtenerReporteGrupoTerapeutico($IdTerapeutico,$Medicina,$FechaInicio,$FechaFin,$IdSubEspecialidad);
			
				if($row2=mysql_fetch_array($respuesta)){ /* verificacion de datos */
		$precioActual=0;
		$Nrecetas=mysql_num_rows($respuesta); 
        
		$IdReceta=$row2["IdReceta"];
		$Divisor=$row2["Divisor"];//Divisor de conversion
		$UnidadMedida=$row2["Descripcion"];//Tipo de unidad de Medida
		$satisfechas=0;
		$insatisfechas=0;
		
	
		
		/*Obtencion de recetas satifechas e insatisfechas globales parametros ...,0,0)*/
		$sat=ObtenerRecetasSatisfechas($IdReceta,$Medicina,$FechaInicio,$FechaFin,$IdSubEspecialidad,0,0);
		$insat=ObtenerRecetasInsatisfechas($IdReceta,$Medicina,$FechaInicio,$FechaFin,$IdSubEspecialidad,0,0);
		
		//***********
		
		//***********
	$Cantidad_Total=SumatoriaMedicamento($Medicina,$IdSubEspecialidad,$FechaInicio,$FechaFin);
		$CantidadReal=$Cantidad_Total/$Divisor;
	$Ano=date('Y');
	$Precio=ObtenerPrecioMedicina($Medicina,$Ano);
	$Monto=$CantidadReal*$Precio;
		$PrecioNuevo=number_format($Precio,2,'.',',');
		$MontoNuevo=number_format($Monto,2,'.',',');
			$SubTotal+=$Monto;
			?>
		
			<tr class="FONDO2">
			  <td>&nbsp;<?php echo $codigoMedicina;?></td>
			  <td align="center" style="vertical-align:middle">&nbsp;<?php echo $NombreMedicina;?></td>
			  <td>&nbsp;<?php echo $concentracion;?></td>
			  <td>&nbsp;<?php echo $presentacion;?></td>
			  <td align="center">&nbsp;<?php echo $Nrecetas;?></td>
			  <td align="center">&nbsp;<?php echo $sat;?></td>
			  <td align="center">&nbsp;<?php echo $insat;?></td>
			  <td align="center"><?php echo $UnidadMedida;?></td>
			  <td align="center"><?php echo $CantidadReal;?></td>
			  <td align="right"><?php echo $PrecioNuevo;?></td>
			  <td align="right"><?php echo $MontoNuevo;?></td>
			</tr>
		<?php	

				}//if row2
			}//while externo
			$SubTotalServicio+=$SubTotal;
		?>     
    <tr style="background:#CCCCCC;">
      <td colspan="9">&nbsp;</td>
      <td align="right"><strong><em>SubTotal:</em></strong></td>
      <td align="right"><strong><?php echo number_format($SubTotal,2,'.',',');?></strong></td>
    </tr>
		  
		<?php
		
	}//while de nombreTera
	$Total+=$SubTotalServicio;
	?>
    <tr style="background:#999999;">
      <td colspan="9">&nbsp;</td>
      <td align="right"><strong><em>SubTotal Servicio:</em></strong></td>
      <td align="right"><strong><?php echo number_format($SubTotalServicio,2,'.',',');?></strong></td>
    </tr>
<?php }//While Servicios ?>
    <tr class="MYTABLE">
      <td colspan="9">&nbsp;</td>
      <td align="right"><strong><em>Total:</em></strong></td>
      <td align="right"><strong><?php echo number_format($Total,2,'.',',');?></strong></td>
    </tr>

</table>
</div>
</body>
</html>
<?php
conexion::desconectar();
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel
?>