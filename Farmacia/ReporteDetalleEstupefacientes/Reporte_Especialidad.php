<?php session_start();
if(!isset($_SESSION["IdFarmacia2"])){
echo "ERROR_SESSION";
}else{
	if($_SESSION["Reportes"]!=1){?>
	<script language="javascript">
	window.location='../Principal/index.php?Permiso=1';
	</script>
<?php
	}else{
$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
require('../Clases/class.php');
include('Funciones.php');
$query=new queries;
conexion::conectar();

//****FILTRACION
$IdFarmacia=$_REQUEST["IdFarmacia"];
$IdArea=$_REQUEST["area"];
$IdSubEspecialidad=$_REQUEST["select1"];
$IdMedico=$_REQUEST["select2"];//IdMedico si no es seleccinado siempre es Cero
	if($IdMedico!='0'){
		$MedRow=mysql_fetch_array(mysql_query("select NombreEmpleado from mnt_empleados where IdEmpleado='$IdMedico'"));
		$NomMed=$MedRow["NombreEmpleado"];
	}else{$NomMed="";}
	
if(isset($_REQUEST["select3"])){$IdMedicina=$_REQUEST["select3"];}else{$IdMedicina=0;}

if($IdFarmacia!=0){
$Farmacia=mysql_fetch_array(mysql_query("select Farmacia from mnt_farmacia where IdFarmacia=".$IdFarmacia));
$Farmacia=$Farmacia[0];
}else{
$Farmacia="GENERAL";
}
//****FIN FILTRACION
//* FECHAS PARA MOSTRAR EN REPORTE 
$FechaInicio=explode('-',$_REQUEST["fechaInicio"]);
$FechaFin=explode('-',$_REQUEST["fechaFin"]);
$FechaInicio2=$FechaInicio[2].'-'.$FechaInicio[1].'-'.$FechaInicio[0];
$FechaFin2=$FechaFin[2].'-'.$FechaFin[1].'-'.$FechaFin[0];

/*FECHAS PARA QUERIES*/
$FechaInicio=$_REQUEST["fechaInicio"];
$FechaFin=$_REQUEST["fechaFin"];

/* OBTENCION DE NOMBRE SUBESPECIALIDAD*/
if($IdSubEspecialidad!=0){
$RowEsp=mysql_fetch_array(mysql_query("select NombreSubServicio from mnt_subservicio where IdSubServicio='$IdSubEspecialidad'"));
$NomEsp=$RowEsp[0];
}else{
$NomEsp="GENERAL";
}

/* OBTENCION DE NOMBRE DE AREA */
if($IdArea!=0){
$RowArea=mysql_fetch_array(mysql_query("select Area from mnt_areafarmacia where IdArea='$IdArea'"));
$area=$RowArea[0];
}





// INICIO DE TABLA PARA REPORTE 

//     GENERACION DE EXCEL
	$NombreExcel='Detalle_Estupefacientes_'.$nick.'_'.date('d_m_Y__h_i_s A');
	$nombrearchivo = "../ReportesExcel/".$NombreExcel.".xls";
	$punteroarchivo = fopen($nombrearchivo, "w+") or die("El archivo de reporte no pudo crearse");
//***********************
//LIBREOFFICE
	$nombrearchivo2 = "../ReportesExcel/".$NombreExcel.".ods";
	$punteroarchivo2 = fopen($nombrearchivo2, "w+") or die("El archivo de reporte no pudo crearse");

//***********************
//*************ENCABEZADO DE REPORTE**************

  $reporte='<table width="967">
      <tr class="MYTABLE">
      <td colspan="11" align="center">'.$_SESSION["NombreEstablecimiento"].'<br>
	<strong>CONSUMO DE MEDICAMENTOS ESTUPEFACIENTES POR ESPECIALIDADES</strong><br>';
	$reporte.='Farmacia Despacho:&nbsp;&nbsp;<strong>'.$Farmacia.'</strong><br>';
	if($IdArea!=0){
	    $reporte.='Area Origen:&nbsp;&nbsp;<strong>'.$area.'</strong><br>';
	}

$reporte.='SERVICIO/ESPECIALIDAD:&nbsp;&nbsp;<strong>'.$NomEsp.'</strong><br>';
if($NomMed!=""){$reporte.="MEDICO:&nbsp;&nbsp;<strong>".$NomMed."</strong><br>";}
$reporte.='PERIODO: '.$FechaInicio2.' AL '.$FechaFin2.' .- </td></tr>
<tr class="MYTABLE"><td align="right" colspan="11">Fecha de Emisi&oacute;n: '.$DateNow=date("d-m-Y").'</td>
    </tr>
  </table>';

//**********FIN ENCABEZADO DE REPORTE******************

//********************CUERPO DE REPORTE*****************
$reporte.='<table width="968" border="1">';
			$Total=0;


//******************************* QUERIES Y RECORRIDOS
$nombreTera=GrupoTerapeutico($IdMedicina);
while($grupos=mysql_fetch_array($nombreTera)){
	$NombreTerapeutico=$grupos["GrupoTerapeutico"];
	$IdTerapeutico=$grupos["IdTerapeutico"];

//*****Verificacion de numero de datos, asi se rompe el lazo para evitar impresiones de datos no existentes
$respuesta=ObtenerReporteEspecialidades($IdTerapeutico,$IdMedicina,$FechaInicio,$FechaFin,$IdSubEspecialidad,$IdMedico,$IdArea,$IdFarmacia);
	
    if($row=mysql_fetch_array($respuesta)) {
	
//***************
			$SubTotal=0;

    $reporte.='<tr class="FONDO2" style="background:#999999;">
      <td colspan="11" align="center"><strong>'.$NombreTerapeutico.'</strong></td>
    </tr>
	    <tr class="FONDO2">
    <th width="37" scope="col">Codigo</th>
      <th width="199" scope="col">Medicamento</th>
      <th width="85" scope="col">Concen.</th>
      <th width="71" scope="col">Prese.</th>
      <th width="82" scope="col">Recetas</th>
      <th width="54" scope="col">Satis.</th>
      <th width="86" scope="col">No Satis.</th>
	  <th width="63">Unidad de Medida</th>
      <th width="74" scope="col">Consumo</th>
      <th width="134" scope="col">Precio[$]</th>
      <th width="102" scope="col">Monto</th>
    </tr>';

	
do{
	
$Medicina=$row["IdMedicina"];


/*************      MANEJO DE LOTES Y ORDENAMIENTO LOGICO      ***************/
$satisfechas=0;
$insatisfechas=0;
/*  INICIALIZACION  DE  VECTORES  Y  VARIABLES  */


/*Obtencion de satisfehcas e insatisfechas por especialidad y/o medico*/
//$TotalRecetas=ObtenerTotalRecetas($Medicina,$IdArea,$IdSubEspecialidad,$IdMedico,$FechaInicio,$FechaFin);
$sat=ObtenerRecetasSatisfechas($Medicina,$FechaInicio,$FechaFin,$IdArea,$IdSubEspecialidad,$IdMedico,$IdFarmacia);
$insat=ObtenerRecetasInsatisfechas($Medicina,$FechaInicio,$FechaFin,$IdArea,$IdSubEspecialidad,$IdMedico,$IdFarmacia);
	$TotalRecetas=$sat+$insat;
//***********
/*QUERY PAR OBTENER INFORMACION GENERAL DEL MEDICAMENTO: NOMBRE, CONCENTRACION, ETC.*/
$row4=$query->ObtenerInfomacionMedicina($Medicina);
$codigoMedicina=$row4["Codigo"];
$NombreMedicina=$row4["Nombre"];
$concentracion=$row4["Concentracion"];
$presentacion=$row4["FormaFarmaceutica"].' - '.$row4["Presentacion"];
$Divisor=$row4["Divisor"];//Divisor de conversion
$UnidadMedida=$row4["Descripcion"];//Tipo de unidad de Medida
//***********

//$Cantidad_Total=$Cantidad_1+$Cantidad_2;//CANTIDAD TOTAL DE MEDICINAS ENTREGADAS

/*	$Cantidad_Total=SumatoriaMedicamento($Medicina,$IdArea,$IdMedico,$IdSubEspecialidad,$FechaInicio,$FechaFin,$IdFarmacia);
		$CantidadReal=$Cantidad_Total/$Divisor;
	$Ano=date('Y');
	$Precio=ObtenerPrecioMedicina($Medicina,$Ano);
	$Monto=$CantidadReal*$Precio;
*/
 	$respSum=SumatoriaMedicamento($Medicina,$IdArea,$IdMedico,$IdSubEspecialidad,$FechaInicio,$FechaFin,$IdFarmacia);
	if($row2=mysql_fetch_array($respSum)){
		$Lote="";	
		$CantidadReal=0;
		$Monto=0;
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

		$Monto+=$row2["Costo"];
		$Lote.=" Lote: ".$row2["Lote"]."<br> $".$row2["PrecioLote"]."<br><br>";
	    }while($row2=mysql_fetch_array($respSum));
	}


		$PrecioNuevo=$Lote;
		$MontoNuevo=number_format($Monto,3,'.',',');
			$SubTotal+=$Monto;

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
      <td style="vertical-align:middle">&nbsp;'.$NombreMedicina.'</td>
      <td align="center" style="vertical-align:middle">&nbsp;'.$concentracion.'</td>
      <td align="center" style="vertical-align:middle">&nbsp;'.htmlentities($presentacion).'</td>
      <td align="center" style="vertical-align:middle">&nbsp;'.$TotalRecetas.'</td>
      <td align="center"style="vertical-align:middle">&nbsp;'.$sat.'</td>
      <td align="center" style="vertical-align:middle">&nbsp;'.$insat.'</td>
	  <td align="center" style="vertical-align:middle">'.$UnidadMedida.'</td>
      <td align="right" style="vertical-align:middle">&nbsp;'.$CantidadIntro.'</td>
	  <td align="right" style="vertical-align:middle">'.$PrecioNuevo.'</td>
      <td align="right" style="vertical-align:middle">'.$MontoNuevo.'</td>
    </tr>';


}while($row=mysql_fetch_array($respuesta));
   
	$Total+=$SubTotal;
   
    $reporte.='<tr class="FONDO2" style="background:#999999;">
      <td colspan="9">&nbsp;</td>
      <td align="right"><strong><em>SubTotal:</em></strong></td>
      <td align="right"><strong>'.number_format($SubTotal,3,'.',',').'</strong></td>
    </tr>';


   }//



}//while de nombreTera
    $reporte.='<tr class="FONDO2" style="background:#CCCCCC;">
      <td colspan="9">&nbsp;</td>
	  <td align="right"><em><strong>Total:</strong></em></td>
	  <td align="right"><strong>'.number_format($Total,3,'.',',').'</strong></td>
    </tr>

  </table>';
  
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
}//Fin de IF isset de Nivel
?>