<?php session_start();

if(isset($_SESSION["nivel"])){

$path="";
include('IncludeFiles/ClasesReporteFarmacias.php');
conexion::conectar();


/*	PARAMETROS	*/
if(isset($_GET["Bandera"])){$Bandera=$_GET["Bandera"];}else{$Bandera=0;}


$IdNumeroExp=$_GET["IdNumeroExp"];

$IdFarmacia=$_GET["IdFarmacia"];

$IdTerapeuticoGET=$_GET["IdTerapeutico"];
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
	case 4:
		$NombreFarmacia="Bodega";
	break;
}//switch

//Datos generales de paciente




?>
<html>
<head>
<title>Reporte Por Farmacias</title>
<?php if($Bandera!=0){echo '<script language="javascript" src="IncludeFiles/ReporteFarmacias.js"></script>';}?>
</head>

<body>
<?php 
$MontoTotal=0;
$MontoTotal_35=0;
		$TotalRecetas3=0;
		$TotalSatis3=0;
		$TotalInsat3=0;

//     GENERACION DE EXCEL
	$NombreExcel="Farmacias_".$NombreFarmacia."_".$_SESSION["nick"].'_'.date('d_m_Y__h_i_s A');
	$nombrearchivo = "../ReportesExcel/".$NombreExcel.".xls";
	$punteroarchivo = fopen($nombrearchivo, "w+") or die("El archivo de reporte no pudo crearse");

//LIBREOFFICE
	$nombrearchivo2 = "../ReportesExcel/".$NombreExcel.".ods";
	$punteroarchivo2 = fopen($nombrearchivo2, "w+") or die("El archivo de reporte no pudo crearse");

//************************/
//Datos Generales de Pacientes
$respPacientes=ReporteFarmacias::PacientesBM($IdNumeroExp,$FechaInicial,$FechaFinal,$IdFarmacia,$_SESSION["TipoExpediente"]);

//***********************/
$reporte='<table width="990" border="1">
  <tr class="MYTABLE"><td colspan="12" align="center"><strong>'.$_SESSION["NombreEstablecimiento"].'</strong></td></tr>
  <tr class="MYTABLE">
    <td colspan="12" align="center"><strong>CONSUMO DE MEDICAMENTOS EN BIENESTAR MAGISTERIAL</strong></td></tr>
	
  <tr class="MYTABLE">
    <td colspan="12" align="center" style="vertical-align:middle;"><strong><h3>'.$NombreFarmacia.'</h3></strong></td>
  </tr>';
if($rowPacientes=mysql_fetch_array($respPacientes)){

do{

	$Expediente=$rowPacientes["IdNumeroExp"];
	$NombrePaciente=htmlentities($rowPacientes["NombrePaciente"]);

  		$MontoTotalPaciente=0;
			$MontoTotalPaciente_35=0;
		$TotalRecetasPaciente=0;
		$TotalSatisPaciente=0;
		$TotalInsatPaciente=0;

$RespGrupos=ReporteFarmacias::GruposTerapeuticos($IdTerapeuticoGET);
$MontoSubTotal=0;
$MontoSubTotal_35=0;



$nota="";
$reporte.='<tr class="MYTABLE">
    <td colspan="12" align="center" style="vertical-align:middle;"><strong><h4>'.$Expediente." [ ".$NombrePaciente.' ] </h4></strong></td>
  </tr>
    <tr class="MYTABLE">
    <td colspan="12" align="center" style="vertical-align:middle;"><strong>Periodo: '.$F1[2]."-".$F1[1]."-".$F1[0].' al '.$F2[2]."-".$F2[1]."-".$F2[0].'</strong></td></tr>';
  //<!--  INICIO DE REPORTE  -->
  
  	while($rowGrupos=mysql_fetch_array($RespGrupos)){
		$IdTerapeutico=$rowGrupos[0];
		$GrupoTerapeutico=$rowGrupos[1];
		
		$TotalRecetas2=0;
		$TotalSatis=0;
		$TotalInsat=0;


		//**************VERIFICACION DE MEDICAMENTO DEL GRUPO CONTRA INGRESO
		$resp=ReporteFarmacias::IngresoPorGrupo($IdTerapeutico,$IdFarmacia,$FechaInicial,$FechaFinal,$Expediente);
		if($rowTmp=mysql_fetch_array($resp)){
  
  $reporte.='<tr class="MYTABLE">
	<td colspan="12" align="center" style="background:#999999;"><strong>'.$GrupoTerapeutico.'</strong></td>
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
		<td width="74" align="center"><strong>Monto + 35% ($) </strong></td>
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
		$MontoSubTotal_35=0;
				$TotalConsumo2=0;
  		while($rowMedicina=mysql_fetch_array($RespMedicina)){

            $IdMedicina=$rowMedicina["IdMedicina"];
			
            $Codigo=$rowMedicina["Codigo"];
			$Nombre=$rowMedicina["Nombre"];
			$Concentracion=$rowMedicina["Concentracion"];
			$Presentacion=$rowMedicina["FormaFarmaceutica"].' - '.$rowMedicina["Presentacion"];
			$DescripcionUnidadMedida=$rowMedicina["Descripcion"];
			$UnidadesContenidas=$rowMedicina["UnidadesContenidas"];
			
			/********	CONSUMO DE MEDICAMENTOS ***************/

			
			$ConsumoMedicamento=ReporteFarmacias::ConsumoMedicamento($IdMedicina,$IdFarmacia,$FechaInicial,$FechaFinal,0,$Expediente);
			
			/**************************************************/		
			if($tmp=mysql_fetch_array($ConsumoMedicamento)){
			
			//Consumo realizacon por medicamento con estado Satisfecha valor en Bandera = 1
			$ConsumoMedicamento=ReporteFarmacias::ConsumoMedicamento($IdMedicina,$IdFarmacia,$FechaInicial,$FechaFinal,1,$Expediente);
			
			//Total de recetas Satisfechas e Insatisfechas
			$TotalRecetas=ReporteFarmacias::TotalRecetas($IdMedicina,$IdFarmacia,$FechaInicial,$FechaFinal,$Expediente);
			
			//Recetas Satisfechas e Insatisfechas en Detalle
			$TotalSatisfechas=ReporteFarmacias::TotalSatisfechas($IdMedicina,$IdFarmacia,$FechaInicial,$FechaFinal,$Expediente);
			$TotalInsatisfechas=ReporteFarmacias::TotalInsatisfechas($IdMedicina,$IdFarmacia,$FechaInicial,$FechaFinal,$Expediente);

		//********Calculo de Recetas Insatisfechas [Total Estimada]
		$aviso='';
		$Estimadas=0;
		//Insatisfechas FINAL
		   $TotalInsatisfechas+=$Estimadas;
		   $TotalRecetas+=$Estimadas;
		/*****************************			

			$Ano=date('Y');
			$Precio=ReporteFarmacias::ObtenerPrecio($IdMedicina,$Ano);
			
			$TotalConsumo=$ConsumoMedicamento/$UnidadesContenidas;
				$Monto=$Precio*$TotalConsumo;
				
					//number_format sirve para mostrar dos decimales incluyendo los ceros x.00
				 $MontoNuevo=number_format($Monto,3,'.',',');
				 $PrecioNuevo=number_format($Precio,2,'.',',');
			//************************************************************************/
		//***********************GENERACION DE CONSUMOS Y COSTOS***************************
		$TotalConsumo=0;
		$Monto=0; $PrecioLote="";
		if($row=mysql_fetch_array($ConsumoMedicamento)){
		   do{
			$TotalConsumo+=$row["Total"];
			$Monto+=$row["Costo"];
			
	if($respDivisor=mysql_fetch_array(ReporteFarmacias::ValorDivisor($IdMedicina))){
		$Divisor=$respDivisor[0];

		if($TotalConsumo < 1){
			//Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
		   $TransformaEntero=number_format($TotalConsumo*$Divisor,0,'.',',');
			$CantidadTransformada=$TransformaEntero.'/'.$Divisor;
		}else{
			//Si la cantidad es mayor a un frasco se realiza este cambio a quebrados
			$TotalConsumo=$TotalConsumo;	
		$CantidadBase=explode('.',$TotalConsumo);
		
		    $Entero=$CantidadBase[0];//Faccion ENTERA
			if(!isset($CantidadBase[1])){
			   $Decimal=0;
			}else{
			   $Decimal=$CantidadBase[1];
			}
			
		    if($Decimal==0){$Decimal="";$Quebrado="";}else{
			
			$Quebrado=number_format(($Decimal/1000)*$Divisor,0,'.',',');
			$Quebrado='['.$Quebrado.'/'.$Divisor.']';
		    }

			
		$CantidadTransformada=$Entero.' '.$Quebrado;
		}
	   $CantidadIntro=$CantidadTransformada;
		
	}else{
	   $CantidadIntro=$TotalConsumo;
		$CantidadIntro=number_format($CantidadIntro,2,'.',',');
	}



			$PrecioLote.="Lote: ".strtoupper($row["Lote"])."<br> $".$row["PrecioLote"]."<br>";
		   }while($row=mysql_fetch_array($ConsumoMedicamento));

		}

	if($respDivisor=mysql_fetch_array(ReporteFarmacias::ValorDivisor($IdMedicina))){
		$Divisor=$respDivisor[0];

                $TotalConsumo=number_format($TotalConsumo,3,'.','');
                
		if($TotalConsumo < 1){
			//Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
		   $TransformaEntero=number_format($TotalConsumo*$Divisor,0,'.',',');
			$CantidadTransformada=$TransformaEntero.'/'.$Divisor;
		}else{
			//Si la cantidad es mayor a un frasco se realiza este cambio a quebrados
			$TotalConsumo=$TotalConsumo;	
		$CantidadBase=explode('.',$TotalConsumo);
		
		    $Entero=$CantidadBase[0];//Faccion ENTERA
			if(!isset($CantidadBase[1])){
			   $Decimal=0;
			}else{
			   $Decimal=$CantidadBase[1];
			}
		    if($Decimal==0){$Decimal="";$Quebrado="";}else{
			
			$Quebrado=number_format(($Decimal/1000)*$Divisor,0,'.',',');
			$Quebrado='['.$Quebrado.'/'.$Divisor.']';
		    }

			
		$CantidadTransformada=$Entero.' '.$Quebrado;
		}
	   $CantidadIntro=$CantidadTransformada;
		
	}else{
	   $CantidadIntro=$TotalConsumo;
		$CantidadIntro=$CantidadIntro;
	}


			
			$PrecioNuevo=$PrecioLote;
			
				$Monto_35=$Monto+($Monto*0.35);
			
			$MontoNuevo=number_format($Monto,3,'.',',');
			$MontoNuevo_35=number_format($Monto_35,3,'.',',');
  		//*****************************************************	
		  $reporte.='<tr class="FONDO2">
			<td style="vertical-align:middle;">&nbsp;"'.$Codigo.'"</td>
			<td style="vertical-align:middle">'.htmlentities($Nombre).'</td>
			<td align="center" style="vertical-align:middle;">'.htmlentities($Concentracion).'</td>
			<td align="center" style="vertical-align:middle;">'.htmlentities($Presentacion).'</td>
			<td align="right" style="vertical-align:middle;">'.$TotalRecetas.'</td>
			<td align="right" style="vertical-align:middle;">'.$TotalSatisfechas.'</td>
			<td align="right" style="vertical-align:middle;">'.$TotalInsatisfechas.''.$aviso.'</td>
			<td align="center" style="vertical-align:middle;">'.$DescripcionUnidadMedida.'</td>
			<td style="vertical-align:middle;" align="right">'.$CantidadIntro.'</td>
			<td style="vertical-align:middle;" align="right">'.$PrecioNuevo.'</td>
			<td align="right" style="vertical-align:middle;">'.$MontoNuevo.'</td>
			<td align="right" style="vertical-align:middle;">'.$MontoNuevo_35.'</td>
		  </tr>';

  		$MontoSubTotal=$MontoSubTotal+$Monto;
		$MontoSubTotal_35=$MontoSubTotal_35+$Monto_35;

		$TotalRecetas2+=$TotalRecetas;
		$TotalSatis+=$TotalSatisfechas;
		$TotalInsat+=$TotalInsatisfechas;
		$TotalConsumo2=$TotalConsumo2+$TotalConsumo;
		
			}//Si existe consumo
  		
		}//while Medicamento
  
  
  //<!--  Fin de Medicamentos por Grupo Terapeutico  -->
		//TOTALES POR PACIENTES
  		$MontoTotalPaciente+=$MontoSubTotal;
			$MontoTotalPaciente_35+=$MontoSubTotal_35;
		$TotalRecetasPaciente+=$TotalRecetas2;
		$TotalSatisPaciente+=$TotalSatis;
		$TotalInsatPaciente+=$TotalInsat;
		
		//TOTALES DE SERVICIO BM
		$MontoTotal+=$MontoSubTotal;
			$MontoTotal_35+=$MontoSubTotal_35;
		$TotalRecetas3+=$TotalRecetas2;
		$TotalSatis3+=$TotalSatis;
		$TotalInsat3+=$TotalInsat;
		
	  $reporte.='<tr class="FONDO2">
      <td colspan="4" align="right"><em><strong> Total:</strong></em></td>
	  <td align="right">'.$TotalRecetas2.'</td>
	  <td align="right">'.$TotalSatis.'</td>
	  <td align="right">'.$TotalInsat.'</td>
	  <td>&nbsp;</td>
	  <td align="right">&nbsp;</td>
	  <td align="right">&nbsp;</td>
		<td align="right" style="vertical-align:middle;background:#CCCCCC;"><strong>'.number_format($MontoSubTotal,3,'.',',').'</strong></td>
		<td align="right" style="vertical-align:middle;background:#CCCCCC;"><strong>'.number_format($MontoSubTotal_35,3,'.',',').'</strong></td>
	  </tr>';
	    
  		}//Si hay medicamento de este grupo ingresado
  	}//While de Grupos Terapeuticos

  //SUBTOTAL POR PACIENTE
 $reporte.='<tr class="MYTABLE">
      <td colspan="4" align="right"><h5><em><strong> Total Paciente: '.$Expediente.' [ '.$NombrePaciente.' ] </strong></em></h5></td>
	  <td align="right">'.$TotalRecetasPaciente.'</td>
	  <td align="right">'.$TotalSatisPaciente.'</td>
	  <td align="right">'.$TotalInsatPaciente.'</td>
	  <td>&nbsp;</td>
	  <td align="right">&nbsp;</td>
	  <td align="right">&nbsp;</td>
    <td align="right" style="vertical-align:middle;background:#999999;"><strong>'.number_format($MontoTotalPaciente,3,'.',',').'</strong></td>
    <td align="right" style="vertical-align:middle;background:#999999;"><strong>'.number_format($MontoTotalPaciente_35,3,'.',',').'</strong></td>
  </tr>
  <tr><td class="FONDO" colspan=12>&nbsp;</td></tr>';


}while($rowPacientes=mysql_fetch_array($respPacientes));

  //TOTAL GLOBAL DEL SERVICIO DE BM
if($IdNumeroExp==""){
 $reporte.='<tr class="MYTABLE">
      <td colspan="4" align="right"><em><strong> Total Servicio de BM:</strong></em></td>
	  <td align="right">'.$TotalRecetas3.'</td>
	  <td align="right">'.$TotalSatis3.'</td>
	  <td align="right">'.$TotalInsat3.'</td>
	  <td>&nbsp;</td>
	  <td align="right">&nbsp;</td>
	  <td align="right">&nbsp;</td>
    <td align="right" style="vertical-align:middle;background:#999999;"><strong>'.number_format($MontoTotal,3,'.',',').'</strong></td>
     <td align="right" style="vertical-align:middle;background:#999999;"><strong>'.number_format($MontoTotal_35,3,'.',',').'</strong></td>
  </tr>
</table>';
}

}else{
//IF PACIENTES....
 $reporte.='<tr class="MYTABLE"><td align=center colspan="12"><h3>...NO HAY DATOS...</h3></td>
</table>';

}
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
<?php conexion::desconectar();

}else{echo "ERROR_SESSION";}
?>
