<?php session_start();


$path="";
include('IncludeFiles/ClasesReporteGeneral.php');
conexion::conectar();
$general=new ReporteGeneral;

/*	PARAMETROS	*/

$IdSubEspecialidad=$_GET["IdSubEspecialidad"]; //IdSubServicio

$FechaInicial=$_GET["FechaInicial"];
$FechaFinal=$_GET["FechaFinal"];

$F1=explode('-',$FechaInicial);
$F2=explode('-',$FechaFinal);

?>
<html>
<head>
<title>Reporte de Recetas</title>
</head>

<body>
<?php 

//     GENERACION DE EXCEL
	$NombreExcel="Costo_x_Servicio_".$_SESSION["nick"].'_'.date('d_m_Y__h_i_s A');
	$nombrearchivo = "../ReportesExcel/".$NombreExcel.".xls";
	$punteroarchivo = fopen($nombrearchivo, "w+") or die("El archivo de reporte no pudo crearse");

//LIBREOFFICE
	$nombrearchivo2 = "../ReportesExcel/".$NombreExcel.".ods";
	$punteroarchivo2 = fopen($nombrearchivo2, "w+") or die("El archivo de reporte no pudo crearse");

//***********************

//***********************


$reporte='<table width="665" border="1">
  <tr class="MYTABLE">
    <td colspan="3" align="center"><strong>'.$_SESSION["NombreEstablecimiento"].'</strong><br>
<strong>COSTO POR SERVICIO</strong></td></tr>';
	

	$reporte.='<tr class="MYTABLE">
    <td colspan="3" align="center" style="vertical-align:middle;"><strong>Periodo: '.$F1[2]."-".$F1[1]."-".$F1[0].' al '.$F2[2]."-".$F2[1]."-".$F2[0].'</strong></td></tr>';



  //<!--  INICIO DE REPORTE  -->
  
  
	  
    
        //Variables Globales
  		$CostoGeneral=0;
		$NumeroRecetasTotal=0;

	$respSubEspecialidad=$general->SubEspecialidad($IdSubEspecialidad,$FechaInicial,$FechaFinal);

	while($rowEspecialidad=mysql_fetch_array($respSubEspecialidad)){
		$NumeroRecetasSubTotal=0;
		$CostoFarmacias=0;
	$reporte.='<tr class="MYTABLE">
    	<td colspan="3" align="center" style="vertical-align:middle;"><strong><h2>'.$rowEspecialidad["CodigoFarmacia"].' - <a onClick="Desplegar('.$rowEspecialidad["IdSubServicio"].',\''.$FechaInicial.'\',\''.$FechaFinal.'\')">'.htmlentities($rowEspecialidad["NombreSubServicio"]).'</a></h2></strong></td>
  	</tr>';
	
		$reporte.='<tr class="FONDO2">
		<td width="121" align="center" style="vertical-align:middle;">&nbsp;Farmacia</td>
		<td width="190" align="center" style="vertical-align:middle">Numero de Recetas</td>
		<td width="332" align="right" style="vertical-align:middle;">Costo ($)</td>
		</tr>';
	
	$respFarmacias=$general->Farmacias($_SESSION["TipoFarmacia"]);
	while($rowFarma=mysql_fetch_array($respFarmacias)){
	 $respDetalle=$general->ObtenerRecetasFarmacia($rowFarma["IdFarmacia"],$rowEspecialidad["IdSubServicio"],$FechaInicial,$FechaFinal);

	$TotalRecetasFarmacia=$general->ObtenerNumeroRecetasFarmacia($rowFarma["IdFarmacia"],$rowEspecialidad["IdSubServicio"],$FechaInicial,$FechaFinal);
	
	    $rowDetalle=mysql_fetch_array($respDetalle);
		$reporte.='<tr class="FONDO2">
			<td style="vertical-align:middle;">&nbsp;'.$rowFarma["Farmacia"].'</a></td>
			<td align="right" style="vertical-align:middle">'.$TotalRecetasFarmacia.'</td>
			<td align="right" style="vertical-align:middle;">'.number_format($rowDetalle["Costo"],3,'.',',').'</td>
		</tr>';
		
	    
	
			
   			$NumeroRecetasSubTotal+=$TotalRecetasFarmacia;
			$CostoFarmacias+=$rowDetalle["Costo"];
  	

	
	}

 	$reporte.='<tr class="MYTABLE">
    	<td align="right" style="background:#999999;"><strong>Total</strong></td>
	<td style="background:#999999;" align=right><strong>'.$NumeroRecetasSubTotal.'</strong></td>
    	<td style="background:#999999;" align="right"><strong>'.number_format($CostoFarmacias,3,'.',',').'</strong></td>

  	</tr>';   

  		$CostoGeneral+=$CostoFarmacias;
		$NumeroRecetasTotal+=$NumeroRecetasSubTotal;
	}//Especialidades y Servicios






 $reporte.='<tr class="MYTABLE">
    <td align="right" style="background:#999999;"><strong>Total</strong></td>
	<td style="background:#999999;"><strong>'.$NumeroRecetasTotal.'</strong></td>
    <td style="background:#999999;" align="right"><strong>'.number_format($CostoGeneral,3,'.',',').'</strong></td>

  </tr>';   
$reporte.='</table>';

//CIERRE DE ARCHIVO EXCEL
	fwrite($punteroarchivo,$reporte);
	fclose($punteroarchivo);
//CIERRE ODS
	fwrite($punteroarchivo2,$reporte);
	fclose($punteroarchivo2);

//***********************


echo '<table>
	<tr>
		
		<td align="right" style="vertical-align:middle;">
		
		<a href="'.$nombrearchivo.'"> <H5>OFFICE <img src="../images/excel.gif"></H5></a>
		</td>
		
		<td align="center" style="vertical-align:middle;">
		
		<a href="'.$nombrearchivo2.'"> <H5>LIBRE OFFICE <img src="../images/ods.png"></H5></a>
		</td>
	</tr>
	<tr>
		<td colspan=2>
		'.$reporte.'
		</td>
	</tr>
</table>';
?>
</body>
</html>
<?php conexion::desconectar();?>