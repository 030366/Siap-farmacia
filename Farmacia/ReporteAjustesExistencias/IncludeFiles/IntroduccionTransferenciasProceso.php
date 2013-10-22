<?php session_start();

if(!isset($_SESSION["nivel"])){
   echo "ERROR_SESSION";
}else{
//$IdPersonal=$_SESSION["IdPersonal"];
require('TransferenciasProcesoClase.php');
conexion::conectar();
$proceso=new TransferenciaProceso;
$Bandera=$_GET["Bandera"];
$nick=$_SESSION["nick"];

switch($Bandera){

case 1:
    
//     GENERACION DE EXCEL
	$NombreExcel='Ajustes_'.$nick.'_'.date('d_m_Y__h_i_s A');
	
	$nombrearchivo = "../../ReportesExcel/".$NombreExcel.".xls";
	$nombrearchivo2="../ReportesExcel/".$NombreExcel.".xls";
	$punteroarchivo = fopen($nombrearchivo, "wb") or die("El archivo de reporte no pudo crearse");
//***********************
//LIBREOFFICE
	$nombrearchivo3 = "../../ReportesExcel/".$NombreExcel.".ods";
	$nombrearchivo4="../ReportesExcel/".$NombreExcel.".ods";
	$punteroarchivo2 = fopen($nombrearchivo3, "w+") or die("El archivo de reporte no pudo crearse");

//***********************
    
    
    
/* MUESTRA LAS TRANSFERENCIAS INTRODUCIDAD */
	$FechaInicial=$_GET["FechaInicial"];
	$FechaFinal=$_GET["FechaFinal"];
        $IdPersonal=$_GET["IdPersonal"];

        $respPersonal=$proceso->Personal($IdPersonal,$FechaInicial, $FechaFinal);
        
        $tabla='<table width="940" border="1">
		<tr class="FONDO"><td colspan="8" align="center"><strong>AJUSTE(S) REALIZADO(S)</strong></td></tr>';
if($rowPersonal=  mysql_fetch_array($respPersonal)){
        
        do{
            
            $IdPersonal=$rowPersonal["IdPersonal"];
$resp=$proceso->ObtenerAjustes($IdPersonal,$FechaInicial,$FechaFinal);


            $tabla.='<tr class="FONDO">
                    <td colspan="8" align="center"><strong>'.htmlentities($rowPersonal["Nombre"]).'</strong></td>
                </tr>
		<tr class="FONDO">
                <td width="159" align="center"><strong>Acta Numero</strong></td>
		<td width="189" align="center"><strong>Medicamento</strong></td>
		<td width="116" align="center"><strong>Cantidad</strong></td>
                <td width="131" align="center"><strong>Lote</strong></td>
		<td width="189" align="center"><strong>Unidad de Medida</strong></td>
		<td width="114" align="center"><strong>Area</strong></td>
		<td width="200" align="center"><strong>Justificacion</strong></td>
		<td width="74" align="center"><strong>Fecha Ajuste</strong></td>
		</tr>';


/*TABLA DE TRANSFERENCIAS*/
if($row=mysql_fetch_array($resp)){

$resp2=$proceso->ObtenerAjustes($IdPersonal,$FechaInicial,$FechaFinal);
	while($row=mysql_fetch_array($resp2)){
	/*OBTENCION DE DETALLE DE TRANSFERENCIA POR LOTE*/
	$resp=$proceso->ObtenerDetalleLote($row["IdAjuste"]);
	$Cantidad=$resp["Cantidad"];
	$IdLote=$resp["IdLote"];
	$Lote=$resp["Lote"];
        $Acta=$row["ActaNumero"];
        $FechaAjuste=$row["FechaAjuste"];
	$DetalleLotes='';
		
			
		
	/****************************************************/
	$Divisor=$proceso->UnidadesContenidas($row["IdMedicina"]);

$TotalExistencia=$row["Cantidad"];
	if($respDivisor=mysql_fetch_array($proceso->ValorDivisor($row["IdMedicina"]))){
		$Divisor=$respDivisor[0];

		if($TotalExistencia < 1){
			//Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
		   $TransformaEntero=number_format($TotalExistencia*$Divisor,0,'.',',');
			$CantidadTransformada=$TransformaEntero.'/'.$Divisor;
		}else{
			//Si la cantidad es mayor a un frasco se realiza este cambio a quebrados
			$TotalExistencia=number_format($TotalExistencia,3,'.',',');	
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
		$CantidadIntro=$TotalExistencia/$Divisor;
	}


		$DetalleLotes.="".$Lote."";

		$tabla=$tabla.'<tr class="FONDO">
                                <td align="center"  style="vertical-align: middle;">'.$Acta.'</td>
                                <td align="left"    style="vertical-align: middle;">'.htmlentities($row["Nombre"]).', '.htmlentities($row["Concentracion"]).' - '.htmlentities($row["Presentacion"]).'</td>
                                <td align="center"  style="vertical-align: middle;">'.$CantidadIntro.'</td>
                                <td align="center"  style="vertical-align: middle;">'.$DetalleLotes.'</td>
                                <td align="center"  style="vertical-align: middle;">'.htmlentities($row["Descripcion"]).'</td>
                                <td align="center"  style="vertical-align: middle;">'.$row["Area"].'</td>
                                <td align="left"    style="vertical-align: middle;">'.htmlentities($row["Justificacion"]).'</td>
                                <td align="center"  style="vertical-align: middle;">'.$FechaAjuste.'</td>
                               </tr>';
	}//while resp

}

        }while($rowPersonal=mysql_fetch_array($respPersonal));

}else{
    $tabla.="<tr class='FONDO'><td colspan='8' align='center'>NO HAY DATOS INGRESADOS PARA LOS PARAMETROS SELECCIONADOS!</td></td>";
    
}
$tabla=$tabla.'</table>';
fwrite($punteroarchivo,$tabla);
	fclose($punteroarchivo);
//***********************
//CIERRE ODS
	fwrite($punteroarchivo2,$tabla);
	fclose($punteroarchivo2);

//***********************

		//<!--  HIPERVINCULO DE ARCHIVO EXCEL  -->
		$salida='<table><tr><td><a href="'.$nombrearchivo2.'"><H5>DESCARGAR REPORTE EXCEL <img src="../images/excel.gif"></H5></a></td><td><a href="'.$nombrearchivo4.'"><H5>DESCARGAR REPORTE EXCEL <img src="../images/ods.png"></H5></a></td></tr></table>';

	echo $salida."<br>".$tabla;

break;

default:
/*LIBRE*/

break;

}//Fin de switch
conexion::desconectar();

}//fin de sesion
?>