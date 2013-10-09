<?php session_start();
if(!isset($_SESSION["nivel"])){
echo "ERROR_SESSION";
}else{

$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
require('../Clases/class.php');
include('Funciones.php');
$query=new queries;
conexion::conectar();


$IdFarmacia=$_REQUEST["farmacia"];

$FechaInicio=explode('-',$_REQUEST["fechaInicio"]);
$FechaFin=explode('-',$_REQUEST["fechaFin"]);
$FechaInicio2=$FechaInicio[2].'-'.$FechaInicio[1].'-'.$FechaInicio[0];
$FechaFin2=$FechaFin[2].'-'.$FechaFin[1].'-'.$FechaFin[0];
$FechaInicio=$_REQUEST["fechaInicio"];
$FechaFin=$_REQUEST["fechaFin"];

if($_REQUEST["area"]!=0){

		$IdArea=$_REQUEST["area"];
		$IdAreaTemp=$_REQUEST["area"];
		$resp=mysql_query("select Area from mnt_areafarmacia where IdArea='$IdArea'");
		$RowArea=mysql_fetch_array($resp);
		$area=$RowArea[0];

}else{

		$IdAreaTemp=0;
		$IdArea=0;
		$area="";
}



//     GENERACION DE EXCEL
	$NombreExcel="GrupoTerapeutico_".$nick."_".$area.'_'.date('d_m_Y__h_i_s A');
	$nombrearchivo = "../ReportesExcel/".$NombreExcel.".xls";
	$punteroarchivo = fopen($nombrearchivo, "w+") or die("El archivo de reporte no pudo crearse");

//LIBREOFFICE
	$nombrearchivo2 = "../ReportesExcel/".$NombreExcel.".ods";
	$punteroarchivo2 = fopen($nombrearchivo2, "w+") or die("El archivo de reporte no pudo crearse");

//***********************

$reporte='';


//*****FILTRACION DE MEDICINA Y GRUPOS  Y FECHAS
if(isset($_REQUEST["IdTerapeutico"])){$grupoTerapeutico=$_REQUEST["IdTerapeutico"];}else{$grupoTerapeutico=0;}
if(isset($_REQUEST["IdMedicina"])){$Idmedicina=$_REQUEST["IdMedicina"];}else{$Idmedicina=0;}


$reporte.='<table width="968" border="1">';
//OBTENCION DE AREAS DE LA FARMACIA

	$TotalRecetasGlobal2=0;
	$TotalSatisGlobal2 = 0;
	$TotalInsatGlobal2 = 0;
	$TotalGlobal = 0;

	$respAreas=ObtenerAreasFarmacia($IdFarmacia,$IdArea,$FechaInicio,$FechaFin);
	while($rowAreas=mysql_fetch_array($respAreas)){
		$IdArea=$rowAreas["IdArea"];
		$NombreDeArea=$rowAreas["Area"];
		$IdFarmacia2=$rowAreas["IdFarmacia"];
$Farmacia=mysql_fetch_array(mysql_query("select Farmacia from mnt_farmacia where IdFarmacia=".$IdFarmacia2));
$Farmacia=$Farmacia[0];
			$reporte.='
			      <tr class="MYTABLE">
      <td colspan="11" align="center">'.$_SESSION["NombreEstablecimiento"].'<br>
        <strong>CONSUMO DE MEDICAMENTOS POR GRUPO TERAPEUTICO</strong> <br>

		Farmacia Despacho:&nbsp;&nbsp;<strong>'.$Farmacia.'</strong><br>';
	if($IdArea!=0){
	    $reporte.='&Aacute;rea Origen: <strong>'.$NombreDeArea.'</strong><br>';
	}


$reporte.='PERIODO DEL: '.$FechaInicio2.' AL '.$FechaFin2.'.-</td></tr>
<tr class="MYTABLE"><td align="right" colspan="11">Fecha de Emisi&oacute;n: '.$DateNow=date("d-m-Y").'</td>
    </tr>';

//**********************************



		//Costo Total de la sumatoria de costos por grupos terapeutico
		$Total=0;
					$TotalRecetas2=0;
					$TotalSatis2=0;
					$TotalInsat2=0;
					$TotalConsumo2=0;

//*************************************
//******************************* QUERIES Y RECORRIDOS
$nombreTera=$query->NombreTera($grupoTerapeutico);
while($grupos=mysql_fetch_array($nombreTera)){
$NombreTerapeutico=$grupos["GrupoTerapeutico"];
$IdTerapeutico=$grupos["IdTerapeutico"];
if($NombreTerapeutico!="--"){

$resp=QueryExterna($IdFarmacia2,$IdTerapeutico,$Idmedicina,$IdArea,$FechaInicio,$FechaFin);
if($row=mysql_fetch_array($resp)){
	//Subtotal es el costo por grupo terapeutico
	$SubTotal=0;
	$TotalRecetas=0;
	$TotalSatis=0;
	$TotalInsat=0;
	$TotalConsumo=0;


	
    $reporte.='<tr class="FONDO2" style="background:#999999;">
      <td colspan="11" align="center"><P>
&nbsp;<strong>'.$NombreTerapeutico.'</strong></td>
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
      <th width="136" scope="col">Monto</th>
    </tr>';
	

	do{
$GrupoTerapeutico=$IdTerapeutico;
$Medicina=$row["IdMedicina"];
$codigoMedicina=$row["Codigo"];
$NombreMedicina=$row["Nombre"];
$concentracion=$row["Concentracion"];
$presentacion=$row["FormaFarmaceutica"].' - '.$row["Presentacion"];

$Nrecetas=0;//conteo de recetas
$consumo=0;


$respuesta=ObtenerReporteGrupoTerapeutico($IdFarmacia2,$GrupoTerapeutico,$Medicina,$FechaInicio,$FechaFin,$IdArea);
	
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
	$sat=ObtenerRecetasSatisfechas($IdFarmacia2,$IdReceta,$Medicina,$FechaInicio,$FechaFin,$IdArea,0,0);
	$insat=ObtenerRecetasInsatisfechas($IdFarmacia2,$IdReceta,$Medicina,$FechaInicio,$FechaFin,$IdArea,0,0);
		$Nrecetas=$sat+$insat;


		/********Calculo de Recetas Insatisfechas [Total Estimada]
		$respInsatEstimada=InsatisfechasEstimadas($Medicina,$FechaInicio,$FechaFin);
		$Estimadas=0;
		while($rowEstimadas=mysql_fetch_array($respInsatEstimada)){
		  $Estimadas+=$rowEstimadas["PromedioRecetas"];
		}
		//Insatisfechas FINAL
		   $insat+=$Estimadas;
		/*****************************/


/***********Manejo antiguo de costos
	
	$Cantidad_Total=SumatoriaMedicamento($Medicina,$IdArea,$FechaInicio,$FechaFin);
		$CantidadReal=$Cantidad_Total/$Divisor;
	$Ano=date('Y');
	$Precio=ObtenerPrecioMedicina($Medicina,$Ano);
	$Monto=$CantidadReal*$Precio;
		$PrecioNuevo=number_format($Precio,2,'.',',');
		$MontoNuevo=number_format($Monto,3,'.',',');

//****************************************************/
	$resp2=SumatoriaMedicamento($IdFarmacia2,$Medicina,$IdArea,$FechaInicio,$FechaFin);
	if($row2=mysql_fetch_array($resp2)){
		$CantidadReal=0;
		$Costo=0;
		$Lotes="";
	  do{
		$CantidadReal+=$row2["TotalMedicamento"];

	if($respDivisor=mysql_fetch_array(ValorDivisor($Medicina))){
		$Divisor=$respDivisor[0];

		if($CantidadReal < 1){
			//Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
		   $TransformaEntero=number_format($CantidadReal*$Divisor,0,'.',',');
			$CantidadTransformada=$TransformaEntero.'/'.$Divisor;
		}else{
			//Si la cantidad es mayor a un frasco se realiza este cambio a quebrados
			$CantidadReal=$CantidadReal;
		$CantidadBase=explode('.',$CantidadReal);
		
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
	   $CantidadIntro=$CantidadReal;
		$CantidadIntro=$CantidadIntro;
	}

		$Costo+=$row2["Costo"];
		//Informacion del o los lotes utilizados
		$Lotes.="Lote: ".$row2["Lote"]."<br> $".$row2["PrecioLote"]."<br><br>";
		
	  }while($row2=mysql_fetch_array($resp2));
	
        }

	$PrecioNuevo=$Lotes;
	$MontoNuevo=number_format($Costo,3,'.',',');

			$SubTotal+=$Costo;
			        $TotalRecetas+=$Nrecetas;
					$TotalSatis+=$sat;
					$TotalInsat+=$insat;
					$TotalConsumo+=$CantidadReal;

	if($respDivisor=mysql_fetch_array(ValorDivisor($Medicina))){
		$Divisor=$respDivisor[0];

                $CantidadReal=number_format($CantidadReal,3,'.','');
                
		if($CantidadReal < 1){
			//Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
		   $TransformaEntero=number_format($CantidadReal*$Divisor,0,'.',',');
			$CantidadTransformada=$TransformaEntero.'/'.$Divisor;
		}else{
			//Si la cantidad es mayor a un frasco se realiza este cambio a quebrados
			//$CantidadReal=number_format($CantidadReal,2,'.',',');	
		$CantidadBase=explode('.',$CantidadReal);
		
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
	   $CantidadIntro=$CantidadReal;
		//$CantidadIntro=number_format($CantidadIntro,2,'.',',');
	}


   $reporte.='<tr class="FONDO2">
      <td style="vertical-align:middle">&nbsp;"'.$codigoMedicina.'"</td>
      <td align="left" style="vertical-align:middle">&nbsp;'.htmlentities($NombreMedicina).'</td>
      <td style="vertical-align:middle">&nbsp;'.htmlentities($concentracion).'</td>
      <td style="vertical-align:middle">&nbsp;'.htmlentities($presentacion).'</td>
	  <td align="center" style="vertical-align:middle">'.$Nrecetas.'</td>
      <td align="center" style="vertical-align:middle">'.$sat.'</td>
      <td align="center" style="vertical-align:middle">'.$insat.'</td>
	  <td align="center" style="vertical-align:middle">'.$UnidadMedida.'</td>
      <td align="right" style="vertical-align:middle">'.$CantidadIntro.'</td>
	  <td align="right" style="vertical-align:middle">'.$PrecioNuevo.'</td>
      <td align="right" style="vertical-align:middle">'.$MontoNuevo.'</td>
    </tr>';
	
		}//if row2
	}while($row=mysql_fetch_array($resp));//while de la informacion del medicamento
	$Total+=$SubTotal;
			        $TotalRecetas2+=$TotalRecetas;
					$TotalSatis2+=$TotalSatis;
					$TotalInsat2+=$TotalInsat;
					$TotalConsumo2+=$TotalConsumo;

   $reporte.=' <tr class="FONDO2"  style="background:#999999;">
      <td colspan="4" align="right"><em><strong> SubTotal:</strong></em></td>
	  <td align="right">'.$TotalRecetas.'</td>
	  <td align="right">'.$TotalSatis.'</td>
	  <td align="right">'.$TotalInsat.'</td>
	  <td>&nbsp;</td>
	  <td align="right">&nbsp;</td>
	  <td align="right">&nbsp;</td>
      <td align="right"><strong>'.number_format($SubTotal,3,'.',',').'</strong></td>
    </tr>';

	}//nuevo IF test del medicamento
}//IF NombreTerapeutico!=--
}//while de grupos terapeuticos  
	$TotalRecetasGlobal2+=$TotalRecetas2;
	$TotalSatisGlobal2 += $TotalSatis2;
	$TotalInsatGlobal2 += $TotalInsat2;
	$TotalGlobal += $Total;
    $reporte.='<tr class="FONDO2" style="background:#CCCCCC;">
      <td colspan="4" align="right"><em><strong> Total de Area '.$NombreDeArea.':</strong></em></td>
	  <td align="right">'.$TotalRecetas2.'</td>
	  <td align="right">'.$TotalSatis2.'</td>
	  <td align="right">'.$TotalInsat2.'</td>
	  <td>&nbsp;</td>
	  <td align="right">&nbsp;</td>
	  <td align="right">&nbsp;</td>
	  <td align="right"><strong>'.number_format($Total,3,'.',',').'</strong></td>
    </tr>';
	
	// FIN DEL RECORRIDO DE LAS AREAS DE FARMACIA
	
		}//while Areas
	//*******************************************	
    $reporte.='<tr class="FONDO2" style="background:#CCCCCC;">
      <td colspan="4" align="right"><em><strong> Total Global de Farmacia:</strong></em></td>
	  <td align="right">'.$TotalRecetasGlobal2.'</td>
	  <td align="right">'.$TotalSatisGlobal2.'</td>
	  <td align="right">'.$TotalInsatGlobal2.'</td>
	  <td>&nbsp;</td>
	  <td align="right">&nbsp;</td>
	  <td align="right">&nbsp;</td>
	  <td align="right"><strong>'.number_format($TotalGlobal,3,'.',',').'</strong></td>
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

conexion::desconectar();
}//Fin de IF nivel == 1

?>