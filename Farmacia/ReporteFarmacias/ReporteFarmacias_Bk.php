<?php session_start();


$path="";
include('IncludeFiles/ClasesReporteFarmacias.php');
conexion::conectar();


/*	PARAMETROS	*/
if(isset($_GET["Bandera"])){$Bandera=$_GET["Bandera"];}else{$Bandera=0;}

$IdFarmacia=$_GET["IdFarmacia"];

$IdTerapeutico=$_GET["IdTerapeutico"];
$IdMedicina=$_GET["IdMedicina"];

$FechaInicial=$_GET["FechaInicial"];
$FechaFinal=$_GET["FechaFinal"];

$F1=explode('-',$FechaInicial);
$F2=explode('-',$FechaFinal);

/***********/
switch($IdFarmacia){
	case 0:
		$NombreFarmacia="Consumo General";
	break;
	case 1:
		$NombreFarmacia="Farmacia Central";
	break;
	case 2:
		$NombreFarmacia="Farmacia Consulta Externa";
	break;
	case 3:
		$NombreFarmacia="Farmacia Emergencia";
	break;
}//switch
$RespGrupos=ReporteFarmacias::GruposTerapeuticos($IdTerapeutico);
$MontoTotal=0;
$MontoSubTotal=0;




?>
<html>
<head>
<title>Reporte Por Farmacias</title>
<?php if($Bandera!=0){echo '<script language="javascript" src="IncludeFiles/ReporteFarmacias.js"></script>';}?>
</head>

<body>
<?php 

//     GENERACION DE EXCEL
	$NombreExcel="Farmacias_".$NombreFarmacia."_".$_SESSION["nick"].'_'.date('d_m_Y__h_i_s A');
	$nombrearchivo = "../ReportesExcel/".$NombreExcel.".xls";
	$punteroarchivo = fopen($nombrearchivo, "w+") or die("El archivo de reporte no pudo crearse");
//***********************


$reporte='<table width="990" border="1">
  <tr class="MYTABLE">
    <td colspan="11" align="center"><strong>HOSPITAL NACIONAL ROSALES</strong><br>
<strong>CONSUMO DE MEDICAMENTOS </strong></td></tr>
	
  <tr class="MYTABLE">
    <td colspan="11" align="center" style="vertical-align:middle;"><strong><h2>'.$NombreFarmacia.'</h2></strong></td>
  </tr>
    <tr class="MYTABLE">
    <td colspan="11" align="center" style="vertical-align:middle;"><strong>Periodo: '.$F1[2]."-".$F1[1]."-".$F1[0].' al '.$F2[2]."-".$F2[1]."-".$F2[0].'</strong></td></tr>';
  //<!--  INICIO DE REPORTE  -->
  
  	while($rowGrupos=mysql_fetch_array($RespGrupos)){
		$IdTerapeutico=$rowGrupos[0];
		$GrupoTerapeutico=$rowGrupos[1];
		//**************VERIFICACION DE MEDICAMENTO DEL GRUPO CONTRA INGRESO
		$resp=ReporteFarmacias::IngresoPorGrupo($IdTerapeutico,$IdFarmacia,$FechaInicial,$FechaFinal);
		if($rowTmp=mysql_fetch_array($resp)){
  
  $reporte.='<tr class="MYTABLE">
	<td colspan="11" align="center" style="background:#999999;"><strong>'.$GrupoTerapeutico.'</strong></td>
  </tr>
  	 <tr class="MYTABLE">
		<td width="66" align="center"><strong>Codigo</strong></td>
		<td width="141" align="center"><strong>Medicamento</strong></td>
		<td width="78" align="center"><strong>Concen.</strong></td>
		<td width="134" align="center"><strong>Presen.</strong></td>
		<td width="67" align="center"><strong>Recetas</strong></td>
		<td width="67" align="center"><strong>Satis.</strong></td>
		<td width="67" align="center"><strong>No Satis. </strong></td>
		<td width="76" align="center"><strong>Unidad de Medida </strong></td>
		<td width="75" align="center"><strong>Consumo</strong></td>
		<td width="75" align="center"><strong>Precio ($) </strong></td>
		<td width="74" align="center"><strong>Monto ($) </strong></td>
	  </tr>';
  //<!-- Medicamentos Agrupados por Grupo Terapeutico -->
  
  
              if($_GET["IdMedicina"]!=0)
            {
                $IdMedicina=$_GET["IdMedicina"];            
            }else{
                $IdMedicina=0;
            }
  		$RespMedicina=ReporteFarmacias::DatosMedicamentosPorGrupo($IdTerapeutico,$IdFarmacia,$IdMedicina);
		$MontoSubTotal=0;
  		while($rowMedicina=mysql_fetch_array($RespMedicina)){

            $IdMedicina=$rowMedicina["IdMedicina"];
			
            $Codigo=$rowMedicina["Codigo"];
			$Nombre=$rowMedicina["Nombre"];
			$Concentracion=$rowMedicina["Concentracion"];
			$Presentacion=$rowMedicina["FormaFarmaceutica"];
			$DescripcionUnidadMedida=$rowMedicina["Descripcion"];
			$UnidadesContenidas=$rowMedicina["UnidadesContenidas"];
			
			/********	CONSUMO DE MEDICAMENTOS ***************/

			
			$ConsumoMedicamento=ReporteFarmacias::ConsumoMedicamento($IdMedicina,$IdFarmacia,$FechaInicial,$FechaFinal,0);
			
			/**************************************************/		
			if($ConsumoMedicamento!=0 and $ConsumoMedicamento!=NULL){
			
			//Consumo realizacon por medicamento con estado Satisfecha valor en Bandera = 1
			$ConsumoMedicamento=ReporteFarmacias::ConsumoMedicamento($IdMedicina,$IdFarmacia,$FechaInicial,$FechaFinal,1);
			
			//Total de recetas Satisfechas e Insatisfechas
			$TotalRecetas=ReporteFarmacias::TotalRecetas($IdMedicina,$IdFarmacia,$FechaInicial,$FechaFinal);
			
			//Recetas Satisfechas e Insatisfechas en Detalle
			$TotalSatisfechas=ReporteFarmacias::TotalSatisfechas($IdMedicina,$IdFarmacia,$FechaInicial,$FechaFinal);
			$TotalInsatisfechas=ReporteFarmacias::TotalInsatisfechas($IdMedicina,$IdFarmacia,$FechaInicial,$FechaFinal);
			
			//*****************************			
			$Ano=date('Y');
			$Precio=ReporteFarmacias::ObtenerPrecio($IdMedicina,$Ano);
			
			$TotalConsumo=$ConsumoMedicamento/$UnidadesContenidas;
				$Monto=round($Precio*$TotalConsumo,2);
				
					//number_format sirve para mostrar dos decimales incluyendo los ceros x.00
				 $MontoNuevo=number_format($Monto,2,'.',',');
				 $PrecioNuevo=number_format($Precio,2,'.',',');
					//************************************************************************
  
		  $reporte.='<tr class="FONDO2">
			<td style="vertical-align:middle;">&nbsp;'.$Codigo.'</td>
			<td style="vertical-align:middle">'.$Nombre.'</td>
			<td align="center" style="vertical-align:middle;">'.$Concentracion.'</td>
			<td align="center" style="vertical-align:middle;">'.$Presentacion.'</td>
			<td align="center" style="vertical-align:middle;">'.$TotalRecetas.'</td>
			<td align="center" style="vertical-align:middle;">'.$TotalSatisfechas.'</td>
			<td align="center" style="vertical-align:middle;">'.$TotalInsatisfechas.'</td>
			<td align="center" style="vertical-align:middle;">'.$DescripcionUnidadMedida.'</td>
			<td style="vertical-align:middle;" align="right">'.number_format($TotalConsumo,2,'.',',').'</td>
			<td style="vertical-align:middle;" align="right">'.$PrecioNuevo.'</td>
			<td align="right" style="vertical-align:middle;">'.$MontoNuevo.'</td>
		  </tr>';

  		$MontoSubTotal=$MontoSubTotal+$Monto;
			}//Si existe consumo
  		
		}//while Medicamento
  
  
  //<!--  Fin de Medicamentos por Grupo Terapeutico  -->
  		$MontoTotal+=$MontoSubTotal;
	  $reporte.='<tr class="FONDO2">
		<td colspan="9" style="background:#CCCCCC;">&nbsp;</td>
		<td style="background:#CCCCCC;"><em><strong>SubTotal</strong></em></td>
		<td align="right" style="vertical-align:middle;background:#CCCCCC;"><strong>'.number_format($MontoSubTotal,2,'.',',').'</strong></td>
	  </tr>';
	    
  		}//Si hay medicamento de este grupo ingresado
  	}//While de Grupos Terapeuticos

  
 $reporte.='<tr class="MYTABLE">
    <td colspan="9" style="background:#999999;">&nbsp;</td>
    <td style="background:#999999;"><strong>Total</strong></td>
    <td align="right" style="vertical-align:middle;background:#999999;"><strong>'.number_format($MontoTotal,2,'.',',').'</strong></td>
  </tr>
   
</table>';

//CIERRE DE ARCHIVO EXCEL
	fwrite($punteroarchivo,$reporte);
	fclose($punteroarchivo);
//***********************

?>
<table>
	<tr>
		<td align="center" style="vertical-align:middle;">
		<!--  HIPERVINCULO DE ARCHIVO EXCEL  -->
		<?php echo '<a href="'.$nombrearchivo.'"><H5>DESCARGAR REPORTE EXCEL <img src="../../images/excel.gif"></H5></a>';?>
		</td>
	</tr>
	<tr>
		<td>
		<!--  INFORMACION A MOSTRAR -->
		<?php echo $reporte;?>
		</td>
	</tr>
</table>
</body>
</html>
<?php conexion::desconectar();?>