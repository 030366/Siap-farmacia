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

$IdArea=$_REQUEST["area"];
$area=$_REQUEST["NomArea"];
$DateNow=date("d-m-Y");

 $reporte=' <table width="968" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="11" align="center">HOSPITAL NACIONAL ROSALES<br>
        <strong>CONSUMO DE MEDICAMENTOS POR GRUPO TERAPEUTICO</strong> <br>
        &Aacute;rea: <strong>'.$area.'</strong><br>
        PERIODO DEL:'.$FechaInicio2.' AL '.$FechaFin2.' .- <br>
        <div align="right">Fecha de Emisi&oacute;n:'.$DateNow.'</div></td>
    </tr>
  ';
  
//*****FILTRACION DE MEDICINA Y GRUPOS  Y FECHAS
$grupoTerapeutico=$_REQUEST["select1"];
if(isset($_REQUEST["select2"])){$medicina=$_REQUEST["select2"];}else{$medicina=0;}
//******************************* QUERIES Y RECORRIDOS
$nombreTera=$query->NombreTera($grupoTerapeutico);
echo $reporte;?>

<table width="968" border="1">
<?php
		//Costo Total de la sumatoria de costos por grupos terapeutico
		$Total=0;
//*************************************
//******************************* QUERIES Y RECORRIDOS
$nombreTera=$query->NombreTera($grupoTerapeutico);
while($grupos=mysql_fetch_array($nombreTera)){
$NombreTerapeutico=$grupos["GrupoTerapeutico"];
$IdTerapeutico=$grupos["IdTerapeutico"];
if($NombreTerapeutico!="--"){

$resp=QueryExterna($IdTerapeutico,$medicina,$IdArea,$FechaInicio,$FechaFin);
if($row=mysql_fetch_array($resp)){
	//Subtotal es el costo por grupo terapeutico
	$SubTotal=0;
?>
	
    <tr class="MYTABLE" style="background:#999999;">
      <td colspan="11" align="center"><P>
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
	  <th width="63">Consumo</th>
      <th width="78" scope="col">Precio[$]</th>
      <?php /*<th width="135" scope="col">Consumo/Lote</th>*/?>
      <th width="136" scope="col">Monto</th>
    </tr>
	<?php
//$resp1=QueryExterna($IdTerapeutico,$medicina,$IdArea,$FechaInicio,$FechaFin);

	do{
$GrupoTerapeutico=$IdTerapeutico;
$Medicina=$row["IdMedicina"];
$codigoMedicina=$row["Codigo"];
$NombreMedicina=$row["Nombre"];
$concentracion=$row["Concentracion"];
$presentacion=$row["FormaFarmaceutica"].' - '.$row["Presentacion"];

$Nrecetas=0;//conteo de recetas
$consumo=0;


$respuesta=ObtenerReporteGrupoTerapeutico($GrupoTerapeutico,$Medicina,$FechaInicio,$FechaFin,$IdArea);
	$Nrecetas=mysql_num_rows($respuesta);
		if($row2=mysql_fetch_array($respuesta)){ /* verificacion de datos */
$precioActual=0;
//$respuesta2=ObtenerReporteGrupoTerapeutico($GrupoTerapeutico,$Medicina,$FechaInicio,$FechaFin,$IdArea);  
//		while($row3=mysql_fetch_array($respuesta2)){
//IdReceta
//$row3=mysql_fetch_array($respuesta2);
$IdReceta=$row2["IdReceta"];
$Divisor=$row2["Divisor"];//Divisor de conversion
$UnidadMedida=$row2["Descripcion"];//Tipo de unidad de Medida
$satisfechas=0;
$insatisfechas=0;

/*Obtencion de recetas satifechas e insatisfechas globales parametros ...,0,0)*/
	$sat=ObtenerRecetasSatisfechas($IdReceta,$Medicina,$FechaInicio,$FechaFin,$IdArea,0,0);
	$insat=ObtenerRecetasInsatisfechas($IdReceta,$Medicina,$FechaInicio,$FechaFin,$IdArea,0,0);

//***********
	
	$Cantidad_Total=SumatoriaMedicamento($Medicina,$IdArea,$FechaInicio,$FechaFin);
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
      <td style="vertical-align:middle">&nbsp;<?php echo $NombreMedicina;?></td>
      <td align="center" style="vertical-align:middle;">&nbsp;<?php echo $concentracion;?></td>
      <td align="center" style="vertical-align:middle;">&nbsp;<?php echo $presentacion;?></td>
	  <td align="center" style="vertical-align:middle;">&nbsp;<?php echo $Nrecetas;?></td>
      <td align="center" style="vertical-align:middle;">&nbsp;<?php echo $sat;?></td>
      <td align="center" style="vertical-align:middle;">&nbsp;<?php echo $insat;?></td>
	  <td align="center" style="vertical-align:middle;"><?php echo $UnidadMedida;?></td>
      <td align="center" style="vertical-align:middle;"><?php echo $CantidadReal;?></td>
	  <td align="right" style="vertical-align:middle;"><?php echo $PrecioNuevo;?></td>
      <td align="right" style="vertical-align:middle;"><?php echo $MontoNuevo;?></td>
    </tr>
<?php	
		}//if row2
	}while($row=mysql_fetch_array($resp));//while de la informacion del medicamento
	$Total+=$SubTotal;
?>     
    <tr class="FONDO2" style="background:#999999;">
      <td colspan="9">&nbsp;</td>
      <td align="right"><strong><em>SubTotal:</em></strong></td>
      <td align="right"><strong><?php echo number_format($SubTotal,2,'.',',');?></strong></td>
    </tr>


  
<?php
	}//nuevo IF test del medicamento
}//IF NombreTerapeutico!=--
}//while de grupos terapeuticos?>
    <tr class="MYTABLE" style="background:#CCCCCC;">
      <td colspan="9">&nbsp;</td>
	  <td align="right"><em><strong>Total:</strong></em></td>
	  <td align="right"><strong><?php echo number_format(round($Total,2),2,'.',',');?></strong></td>
    </tr>
</table>

<?php
conexion::desconectar();

?>
