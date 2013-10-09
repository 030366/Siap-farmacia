<?php 
$nombreArchivo=$_GET["nombreArchivo"];
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$nombreArchivo.xls");
header("Pragma: no-cache");
header("Expires: 0");

require('../../Clases/class.php');
include('Funciones.php');
$query=new queries;
conexion::conectar();

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
  <table width="968" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="11" align="center">HOSPITAL NACIONAL ROSALES<br>
        <strong>CONSUMO DE MEDICAMENTOS POR ESPECIALIDAD</strong> <br>
        
        PERIODO DEL: <?php echo"$FechaInicio2";?> AL <?php echo"$FechaFin2";?> .- <br>
        <div align="right">Fecha de Emisi&oacute;n:
          <?php 
$DateNow=date("d-m-Y");
echo"$DateNow";?>
        </div></td>
    </tr>
  </table>
  
<table width="968" border="1">
<?php
		$Total=0;

//*************************************
//******************************* QUERIES Y RECORRIDOS
$respServicios=Servicios($IdSubEspecialidad,$FechaInicio,$FechaFin);
while($rowServicios=mysql_fetch_array($respServicios)){
	$IdSubEspecialidad=$rowServicios[0];
	$NombreSubEspecialidad=$rowServicios[1];?>
			
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

			<tr class="MYTABLE">
			  <td colspan="11" align="center" style="background:#CCCCCC;">
		&nbsp;<strong><?php echo"$NombreTerapeutico";?></strong></td>
			</tr>
				<tr class="FONDO2">
			<th width="37" scope="col">Codigo</th>
			  <th width="182" scope="col">Medicamento</th>
			  <th width="61" scope="col">Concen.</th>
			  <th width="54" scope="col">Prese.</th>
			  <th width="54" scope="col">Recetas</th>
			  <th width="50" scope="col">Satis.</th>
			  <th width="70" scope="col">No Satis.</th>
			  <th width="63">Unidad de Medida</th>
			  <th width="78" scope="col">Consumo</th>
			  <th width="135" scope="col">Precio[$]</th>
			  <th width="136" scope="col">Monto[$]</th>
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

		
		$Divisor=$row2["Divisor"];//Divisor de conversion
		$UnidadMedida=$row2["Descripcion"];//Tipo de unidad de Medida
		$satisfechas=0;
		$insatisfechas=0;
		
		/*Obtencion de recetas satifechas e insatisfechas globales parametros ...,0,0)*/
		$sat=ObtenerRecetasSatisfechas($Medicina,$FechaInicio,$FechaFin,$IdSubEspecialidad,0,0);
		$insat=ObtenerRecetasInsatisfechas($Medicina,$FechaInicio,$FechaFin,$IdSubEspecialidad,0,0);
		
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
			  <td style="vertical-align:middle;">&nbsp;<?php echo $codigoMedicina;?></td>
			  <td style="vertical-align:middle"><?php echo $NombreMedicina;?></td>
			  <td align="center" style="vertical-align:middle;"><?php echo $concentracion;?></td>
			  <td align="center" style="vertical-align:middle;"><?php echo $presentacion;?></td>
			  <td align="center" style="vertical-align:middle;"><?php echo $Nrecetas;?></td>
			  <td align="center" style="vertical-align:middle;"><?php echo $sat;?></td>
			  <td align="center" style="vertical-align:middle;"><?php echo $insat;?></td>
			  <td align="center" style="vertical-align:middle;"><?php echo $UnidadMedida;?></td>
			  <td align="center" style="vertical-align:middle;"><?php echo $CantidadReal;?></td>
			  <td align="right" style="vertical-align:middle;"><?php echo $PrecioNuevo;?></td>
			  <td align="right" style="vertical-align:middle;"><?php echo $MontoNuevo;  ?></td>
			</tr>
		<?php	
				}//if row2
			}//while externo
				$Total+=$SubTotal;

		?>     
    <tr class="FONDO2" style="background:#CCCCCC;">
      <td colspan="9">&nbsp;</td>
      <td align="right"><strong><em>SubTotal:</em></strong></td>
      <td align="right"><strong><?php echo number_format($SubTotal,2,'.',',');?></strong></td>
    </tr>
		  
		<?php
		
	}//while de nombreTera

}//While Servicios?>
    <tr class="MYTABLE" style="background:#CCCCCC;">
      <td colspan="9">&nbsp;</td>
	  <td align="right"><em><strong>Total:</strong></em></td>
	  <td align="right"><strong><?php echo number_format(round($Total,2),2,'.',',');?></strong></td>
    </tr>
</table>
<?php 
conexion::desconectar();
?>
