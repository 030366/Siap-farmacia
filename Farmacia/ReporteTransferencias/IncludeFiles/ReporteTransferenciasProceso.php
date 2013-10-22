<?php session_start();
if(!isset($_SESSION["Administracion"])){
 echo "ERROR_SESSION";
}else{
require('ReporteTransferenciasClase.php');
conexion::conectar();
/*Periodo de transferencias*/
$FechaInicio=$_GET["FechaInicio"];
$FechaFin=$_GET["FechaFin"];
$Usuarios=$_GET["Usuario"];
$nick=$_SESSION["nick"];


$proceso=new ReporteTransferencias;

/*DIFERENCIAS ENTRE TODOS LOS USUARIOS O UNO PUNTUAL*/
switch($Usuarios){
case 0:
//     GENERACION DE EXCEL
	$NombreExcel='Tranferencias_'.$nick.'_'.date('d_m_Y__h_i_s A');
	
	$nombrearchivo = "../../ReportesExcel/".$NombreExcel.".xls";
	$nombrearchivo2="../ReportesExcel/".$NombreExcel.".xls";
	$punteroarchivo = fopen($nombrearchivo, "wb") or die("El archivo de reporte no pudo crearse");
//***********************
//LIBREOFFICE
	$nombrearchivo3 = "../../ReportesExcel/".$NombreExcel.".ods";
	$nombrearchivo4="../ReportesExcel/".$NombreExcel.".ods";
	$punteroarchivo2 = fopen($nombrearchivo3, "w+") or die("El archivo de reporte no pudo crearse");

//***********************
/*PARA TODOS LOS USUARIOS QUE HAN INTRODUCIDO TRANSFERENIAS [SOLO SON DE NIVEL ADMINISTRATIVO, NO TECNICOS]*/

	$respUsuario=$proceso->ObtenerUsuarios($Usuarios);
	$tbl='<table width="915">
	<tr><td colspan="6" align="right"></td></tr>';
	while($row=mysql_fetch_array($respUsuario)){
		$IdPersonal=$row[0];
		$Nombre=$row[1];
		$tbl.='
	  <tr class="MYTABLE">
			<td colspan="7" align="center"><strong>'.strtoupper($Nombre).'</strong></td>
	  </tr>
		<tr class="FONDO">
			<th width="74">Cantidad</th>
			<th width="189">Medicamento</th>
			<th width="189">Unidad de Medida</th>
			<th width="130">Origen de Transferencia</th>
			<th width="137">Destino de Transferencia</th>
			<th width="244">Justificacion</th>
			<th width="113">Fecha de Transferencia</th>
		</tr>';
		$respTrans=$proceso->ObtenerTransferencias($IdPersonal,$FechaInicio,$FechaFin);		
		while($row2=mysql_fetch_array($respTrans)){
			$Cantidad=$row2["Cantidad"];
			$Medicamento=htmlentities($row2["Nombre"]);
			$Concentracion=$row2["Concentracion"];
			$AreaOrigen=$row2["Area"];
			$AreaDestino=$row2["IdAreaDestino"];
			$Justificacion=$row2["Justificacion"];
			$Fecha=$row2["FechaTransferencia"];
			$Descripcion=$row2["Descripcion"];

	/****************************************************/
	$Divisor=$proceso->UnidadesContenidas($row2["IdMedicina"]);

$TotalExistencia=$row2["Cantidad"];
	if($respDivisor=mysql_fetch_array($proceso->ValorDivisor($row2["IdMedicina"]))){
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
		$CantidadIntro=$TotalExistencia/$Divisor;
	}


						
		$tbl.='<tr>
		<td class="FONDO" align="center">'.$CantidadIntro.'</td>
		<td class="FONDO" align="center">'.$Medicamento.', '.$Concentracion.'</td>
		<td class="FONDO" align="center">'.$Descripcion.'</td>
		<td class="FONDO" align="center">'.$AreaOrigen.'</td>
		<td class="FONDO" align="center">'.$proceso->ObtenerNombreArea($AreaDestino).'</td>
		<td class="FONDO">'.$Justificacion.'</td>
		<td class="FONDO" align="center">'.$Fecha.'</td>		
		</tr>';
			
		}//while de Transferencias
	}//while de Usuarios
	
	$tbl.="<tr class='MYTABLE'><td colspan='7'>&nbsp;</td></tr></table>";
		//CIERRE DE ARCHIVO EXCEL
	fwrite($punteroarchivo,$tbl);
	fclose($punteroarchivo);
//***********************
//CIERRE ODS
	fwrite($punteroarchivo2,$tbl);
	fclose($punteroarchivo2);

//***********************

		//<!--  HIPERVINCULO DE ARCHIVO EXCEL  -->
		$salida='<table><tr><td><a href="'.$nombrearchivo2.'"><H5>DESCARGAR REPORTE EXCEL <img src="../images/excel.gif"></H5></a></td><td><a href="'.$nombrearchivo4.'"><H5>DESCARGAR REPORTE EXCEL <img src="../images/ods.png"></H5></a></td></tr></table>';

	echo $salida."<br>".$tbl;
break;

default:

//     GENERACION DE EXCEL
	$NombreExcel='Tranferencias_'.$nick.'_'.date('d_m_Y__h_i_s A');
	
	$nombrearchivo = "../../ReportesExcel/".$NombreExcel.".xls";
	$nombrearchivo2="../ReportesExcel/".$NombreExcel.".xls";
	$punteroarchivo = fopen($nombrearchivo, "wb") or die("El archivo de reporte no pudo crearse");
//***********************
//LIBREOFFICE
	$nombrearchivo3 = "../../ReportesExcel/".$NombreExcel.".ods";
	$nombrearchivo4="../ReportesExcel/".$NombreExcel.".ods";
	$punteroarchivo2 = fopen($nombrearchivo3, "w+") or die("El archivo de reporte no pudo crearse");

//***********************

	$tbl='<table width="915">
	<tr><td colspan="6" align="right"></td></tr>';
	
	$Nombre=$proceso->ObtenerUsuarios($Usuarios);
		
		$tbl.='
	  <tr class="MYTABLE">
			<td colspan="7" align="center"><strong>'.strtoupper($Nombre).'</strong></td>
	  </tr>
		<tr class="FONDO">
			<th width="74">Cantidad</th>
			<th width="189">Medicamento</th>
			<th width="189">Unidad de Medida</th>
			<th width="130">Origen de Transferencia</th>
			<th width="137">Destino de Transferencia</th>
			<th width="244">Justificacion</th>
			<th width="113">Fecha de Transferencia</th>
		</tr>';

		$respTrans=$proceso->ObtenerTransferencias($Usuarios,$FechaInicio,$FechaFin);		
		while($row2=mysql_fetch_array($respTrans)){
			$Cantidad=$row2["Cantidad"];
			$Medicamento=htmlentities($row2["Nombre"]);
			$Concentracion=$row2["Concentracion"];
			$AreaOrigen=$row2["Area"];
			$AreaDestino=$row2["IdAreaDestino"];
			$Justificacion=$row2["Justificacion"];
			$Fecha=$row2["FechaTransferencia"];
			$Descripcion=$row2["Descripcion"];

$Divisor=$proceso->UnidadesContenidas($row2["IdMedicina"]);

$TotalExistencia=$row2["Cantidad"];
	if($respDivisor=mysql_fetch_array($proceso->ValorDivisor($row2["IdMedicina"]))){
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
		$CantidadIntro=$TotalExistencia/$Divisor;
	}

	
		$tbl.='<tr>
		<td class="FONDO" align="center">'.$CantidadIntro.'</td>
		<td class="FONDO" align="center">'.$Medicamento.', '.$Concentracion.'</td>
		<td class="FONDO" align="center">'.$Descripcion.'</td>
		<td class="FONDO" align="center">'.$AreaOrigen.'</td>
		<td class="FONDO" align="center">'.$proceso->ObtenerNombreArea($AreaDestino).'</td>
		<td class="FONDO">'.$Justificacion.'</td>
		<td class="FONDO" align="center">'.$Fecha.'</td>		
		</tr>';
			
		}//while de Transferencias
	$tbl.="<tr class='MYTABLE'><td colspan='7'>&nbsp;</td></tr></table>";
	//CIERRE DE ARCHIVO EXCEL
	fwrite($punteroarchivo,$tbl);
	fclose($punteroarchivo);
//***********************
//CIERRE ODS
	fwrite($punteroarchivo2,$tbl);
	fclose($punteroarchivo2);

//***********************

		//<!--  HIPERVINCULO DE ARCHIVO EXCEL  -->
		$salida='<table><tr><td><a href="'.$nombrearchivo.'"><H5>DESCARGAR REPORTE EXCEL <img src="../images/excel.gif"></H5></a></td><td><a href="'.$nombrearchivo3.'"><H5>DESCARGAR REPORTE EXCEL <img src="../images/excel.gif"></H5></a></td></tr></table>';

	echo $salida."<br>".$tbl;
break;

}//switch

conexion::desconectar();
}//Error de session
?>
