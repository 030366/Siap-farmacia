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


// if(isset($_REQUEST["farmacia"])){$IdFarmacia=$_REQUEST["farmacia"];}else{$IdFarmacia=0;}

$FechaBase=FechaBase();

$FechaInicio=$FechaBase.'-01';
$FechaFin=$FechaBase.'-31';


//************	ENCABEZADOS	*****************************************
$IdFarmacia=$_GET["IdFarmacia"];
$IdArea=$_GET["IdArea"];

if($_REQUEST["IdFarmacia"]!=0){

		$IdFarmacia=$_REQUEST["IdFarmacia"];
		$IdFarmaciaTemp=$_REQUEST["IdFarmacia"];
		$resp=mysql_query("select Farmacia from mnt_farmacia where IdFarmacia='$IdFarmacia'");
		$RowArea=mysql_fetch_array($resp);
		$Farmacia=$RowArea[0];
	$NombreFarmacia="<tr><td align='center' colspan='8' class='MYTABLE'><strong><h2>Farmacia: ".$Farmacia."</h2></strong></td></tr>";
}else{

		$NombreFarmacia="";
}



if($_REQUEST["IdArea"]!=0){

		$IdArea=$_REQUEST["IdArea"];
		$IdAreaTemp=$_REQUEST["IdArea"];
		$resp=mysql_query("select Area from mnt_areafarmacia where IdArea='$IdArea'");
		$RowArea=mysql_fetch_array($resp);
		$area=$RowArea[0];
	$NombreArea="<tr><td align='center' colspan='8' class='MYTABLE'><strong><h4> Area: ".$area."</strong></h4></td></tr>";
}else{

		$NombreArea="";
}
//******************************************************************************


//     GENERACION DE EXCEL
	$NombreExcel="ReporteExistenciasFarmacias_".$nick.'_'.date('d_m_Y__h_i_s A');
	$nombrearchivo = "../ReportesExcel/".$NombreExcel.".xls";
	$punteroarchivo = fopen($nombrearchivo, "w+") or die("El archivo de reporte no pudo crearse");
//***********************

//LIBREOFFICE
	$nombrearchivo2 = "../ReportesExcel/".$NombreExcel.".ods";
	$punteroarchivo2 = fopen($nombrearchivo2, "w+") or die("El archivo de reporte no pudo crearse");

//***********************

$reporte='';


//*****FILTRACION DE MEDICINA Y GRUPOS  Y FECHAS
if($_REQUEST["IdTerapeutico"]!=0){$grupoTerapeutico=$_REQUEST["IdTerapeutico"];}else{$grupoTerapeutico=0;}
if($_REQUEST["IdMedicina"]!=0){$Idmedicina=$_REQUEST["IdMedicina"];}else{$Idmedicina=0;}


$reporte.='<table width="968" border="1">';
//OBTENCION DE AREAS DE LA FARMACIA
		

			$reporte.='
			      <tr class="MYTABLE">
      <td colspan="8" align="center">'.$_SESSION["NombreEstablecimiento"].'<br>
        <strong>REPORTE DE EXISTENCIA DE MEDICAMENTOS</strong> <br></td></tr>
	'.$NombreFarmacia.'
	'.$NombreArea.'
<tr class="MYTABLE"><td align="right" colspan="8">Fecha de Emisi&oacute;n: '.$DateNow=date("d-m-Y").'</td>
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
$nombreTera=NombreTera($grupoTerapeutico);
while($grupos=mysql_fetch_array($nombreTera)){
$NombreTerapeutico=$grupos["GrupoTerapeutico"];
$IdTerapeutico=$grupos["IdTerapeutico"];
if($NombreTerapeutico!="--"){

$resp=QueryExterna($IdFarmacia,$IdArea,$IdTerapeutico,$Idmedicina);
if($row=mysql_fetch_array($resp)){
	//Todos los medicamentos
	$SubTotal=0;
	$TotalRecetas=0;
	$TotalSatis=0;
	$TotalInsat=0;
	$TotalConsumo=0;


	
    $reporte.='<tr class="FONDO2" style="background:#999999;">
      <td colspan="8" align="center"><P>
&nbsp;<strong>'.$NombreTerapeutico.'</strong></td>
    </tr>
	    <tr class="FONDO2">
    <th width="37" scope="col">Codigo</th>
      <th width="182" scope="col">Medicamento</th>
      <th width="61" scope="col">Concen.</th>
      <th width="54" scope="col">Prese.</th>
      <th width="54" scope="col">Unidad de Medida</th>
      <th width="50" scope="col">Existencia</th>
      <th width="70" scope="col">Lotes de Medicamento</th>
      <th width="63">Cobertura Estimada*</th>
	  
    </tr>';
	

	do{
$GrupoTerapeutico=$IdTerapeutico;
$Medicina=$row["IdMedicina"];
$codigoMedicina=$row["Codigo"];
$NombreMedicina=htmlentities($row["Nombre"]);
$concentracion=$row["Concentracion"];
$presentacion=$row["FormaFarmaceutica"].' - '.$row["Presentacion"];

$Nrecetas=0;//conteo de recetas
$consumo=0;


$respuesta=ObtenerReporteGrupoTerapeutico($IdFarmacia,$IdArea,$GrupoTerapeutico,$Medicina);
$precioActual=0;
$TotalExistencia=0;
	

	if($row2=mysql_fetch_array($respuesta)){ 
	/* verificacion de datos */
		$UnidadMedida=$row2["Descripcion"];//Tipo de unidad de Medida
		$IdMedicinaEstudio=$row2["IdMedicina"];
		do{
		   $TotalExistencia+=$row2["Total"];
		}while($row2=mysql_fetch_array($respuesta));

	}else{$UnidadMedida="";}//if row2
	

	if($respDivisor=mysql_fetch_array(ValorDivisor($Medicina))){
		
		$Divisor=$respDivisor[0];

                $TotalExistencia=number_format($TotalExistencia,3,'.','');
                
		if($TotalExistencia < 1){
			//Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
		   $TransformaEntero=number_format($TotalExistencia*$Divisor,0,'.',',');
			$CantidadTransformada=$TransformaEntero.'/'.$Divisor;
		}else{
			//Si la cantidad es mayor a un frasco se realiza este cambio a quebrados
			//$TotalExistencia=number_format($TotalExistencia,2,'.',',');	
		$CantidadBase=explode('.',$TotalExistencia);
		
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
	   $CantidadIntro=$TotalExistencia;
		//$CantidadIntro=number_format($CantidadIntro,2,'.',',');
	}


/****************************************************/
	$resp2=SumatoriaMedicamento($Medicina,$FechaInicio,$FechaFin);
	$CantidadReal=0;
	if($row2=mysql_fetch_array($resp2)){
		
		$Costo=0;
		$Lotes="";
	  do{
		$CantidadReal+=$row2["TotalMedicamento"];
		$Costo+=$row2["Costo"];
		//Informacion del o los lotes utilizados
		
	  }while($row=mysql_fetch_array($resp2));
	
        }



/*******************************************************/
  if($CantidadReal==NULL or $CantidadReal==0){$CantidadReal=1;}
$CoberturaEstimada=($TotalExistencia/$CantidadReal);
$CoberturaEstimada=number_format($CoberturaEstimada,2,'.',',');

$respLotes=LotesMedicamento($IdFarmacia,$IdArea,$Medicina);
$Lotes='';
if($rowLote=mysql_fetch_array($respLotes)){
   do{
$Lotes.="Lote: ".$rowLote["Lote"]."<br> Precio: $".$rowLote["PrecioLote"]."<br><br>";
   }while($rowLote=mysql_fetch_array($respLotes));
}else{
  $Lotes="&nbsp;";
}


$decimal=explode('.',$CoberturaEstimada);
$mesExtra=0;
//************CONVERSION DE INFORMACION A MESES DE COBERTURA *********************
if($decimal[1] > 30){
   $dias=$decimal[1]/30;
	$dias=number_format($dias,2,'.',',');
	$salida=explode('.',$dias);
		$mesExtra=$salida[0];

		$dias=($salida[1]/10)*30;
		$salidaDia=explode('.',$dias);
		$dias=$salidaDia[0];
		
			if($dias > 30){
				$dias=$dias/30;
				$dias=number_format($dias,2,'.',',');
				$salida=explode('.',$dias);
				$mesExtra+=$salida[0];

				$dias=($salida[1]/10)*30;
				$salidaDia=explode('.',$dias);
				$dias=$salidaDia[0];
			}
			
			if($dias == 30){
				$mesExtra+=1;
				$dias=0;
			}

}
if($decimal[1] == 30){
   $mesExtra=1;
   $dias=0;
}

if($decimal[1] < 30){
 $dias=$decimal[1];

}
//**************************************************************************

$meses=$decimal[0]+$mesExtra;

	$CoberturaEstimada=$meses.'mes(es) <br> y '.$dias.' dias ';



   $reporte.='<tr class="FONDO2">
      <td style="vertical-align:middle">&nbsp;"'.$codigoMedicina.'"</td>
      <td align="left" style="vertical-align:middle">&nbsp;'.$NombreMedicina.'</td>
      <td style="vertical-align:middle">&nbsp;'.$concentracion.'</td>
      <td style="vertical-align:middle">&nbsp;'.htmlentities($presentacion).'</td>
      <td align="center" style="vertical-align:middle">'.$UnidadMedida.'</td>
      <td align="center" style="vertical-align:middle">'.$CantidadIntro.'</td>
      <td align="center" style="vertical-align:middle">'.$Lotes.'</td>
	  
      <td align="center" style="vertical-align:middle">'.$CoberturaEstimada.'</td>
    </tr>';
	
		
	/*$Total+=$SubTotal;
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
    </tr>';*/

	//}//nuevo IF test del medicamento

}while($row=mysql_fetch_array($resp));//while de la informacion del medicamento
}

}//IF NombreTerapeutico!=--
}//while de grupos terapeuticos  
	

	

	//*******************************************	
$reporte.='<tr><td align="right" colspan="8">* Cobertura estimada con respecto al consumo generado en el mes anterior.-</td></tr></table>';

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