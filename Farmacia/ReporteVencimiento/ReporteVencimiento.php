<?php session_start();
if(!isset($_SESSION["Administracion"])){
 echo "ERROR_SESSION";
}else{
require('../Clases/class.php');
include('IncludeFiles/ReporteVencimientoClase.php');
conexion::conectar();
$puntero= new ReporteVencimiento;

//********************variables***************************

switch($_GET["Bandera"]){

case 1:
//Generacion de combo Medicina
$IdTerapeutico=$_GET["IdTerapeutico"];
	$resp=$puntero->MedicinasGrupo($IdTerapeutico,$_SESSION["IdEstablecimiento"]);
	$combo="<select id='IdMedicina' name='IdMedicina'>
		<option value=''>[GENERAL...]</option>";
	while($row=mysql_fetch_array($resp)){
	   $combo.="<option value='".$row["IdMedicina"]."'>".$row["Codigo"].' '.htmlentities($row["Nombre"])." - ".$row["Concentracion"]." - ".htmlentities($row["Presentacion"])."</option>";
	}
	$combo.="</select>";
	
	echo $combo;
break;

case 2:

// $IdFarmacia=$_GET["IdFarmacia"];
$IdTerapeutico=$_GET["IdTerapeutico"];
$IdMedicina=$_GET["IdMedicina"];


$FechaInicio=explode('-',$_REQUEST["fechaInicio"]);
$FechaFin=explode('-',$_REQUEST["fechaFin"]);
$FechaInicio2=$FechaInicio[2].'-'.$FechaInicio[1].'-'.$FechaInicio[0];
$FechaFin2=$FechaFin[2].'-'.$FechaFin[1].'-'.$FechaFin[0];
$FechaInicio=$_REQUEST["fechaInicio"];
$FechaFin=$_REQUEST["fechaFin"];

//*****************************

//     GENERACION DE EXCEL
	$NombreExcel='Vencimientos_'.$_SESSION["nick"].'_'.date('d_m_Y__h_i_s A');
	
	$nombrearchivo = "../ReportesExcel/".$NombreExcel.".xls";
	$punteroarchivo = fopen($nombrearchivo, "wb") or die("El archivo de reporte no pudo crearse");
//***********************
//LIBREOFFICE
	$nombrearchivo2 = "../ReportesExcel/".$NombreExcel.".ods";
	$punteroarchivo2 = fopen($nombrearchivo2, "w+") or die("El archivo de reporte no pudo crearse");

//***********************
$reporte='<table width="968" border="1">';

$reporte.='
			<tr class="MYTABLE">
				<td colspan="11" align="center">'.$_SESSION["NombreEstablecimiento"].'<br>
				<strong>REPORTE DE MEDICAMENTOS PROXIMOS A VENCER</strong> <br>
					PERIODO DE VENCIMIENTO: '.$FechaInicio2.' AL '.$FechaFin2.' .- </td></tr>
	
				<tr class="MYTABLE"><td align="right" colspan="11">Fecha de Emisi&oacute;n: '.$DateNow=date("d-m-Y").'
				</td>
		    </tr>

			</tr>';
$Total=0;
$respGrupos=$puntero->GrupoTerapeutico($IdTerapeutico);
if($rowGrupo=mysql_fetch_array($respGrupos)){

do{

//
$respMedicina=$puntero->MedicinasGrupo($rowGrupo["IdTerapeutico"],$_SESSION["IdEstablecimiento"]);
//*******************
$contador=0;
$SubTotal=0;
$SubTotalB=0;
if($rowMedicina=mysql_fetch_array($respMedicina)){



do{


$resp=$puntero->ObtenerInformacionVencimientoProximo(0,$rowMedicina["IdMedicina"],$FechaInicio,$FechaFin);

while($row=mysql_fetch_array($resp)){
$SubTotalB=1;
if($contador==0){
$reporte.='<tr class="MYTABLE">
<th align="center" style="vertical-align:middle;" colspan=7>'.$rowGrupo["GrupoTerapeutico"].'</th>';
$reporte.='<tr class="MYTABLE">
<th align="center" style="vertical-align:middle;">Codigo</th>
<th width="30%" align="center" style="vertical-align:middle;">Medicamento</th>
<th align="center" style="vertical-align:middle;">Existencias</th>
<th align="center" style="vertical-align:middle;">Unidad de Medida</th>
<th align="center" style="vertical-align:middle;">Lote</th>
<th align="center" style="vertical-align:middle;">Fecha de Vencimiento</th>
<th width="10%" align="center" style="vertical-align:middle;">Costo ($)</th>
</tr>';
}
$contador++;
	$Codigo=$row["Codigo"];
	$NombreMedicina=htmlentities($row["Nombre"]);
	    $Concentracion=$row["Concentracion"];
	    $FormaFarmaceutica=htmlentities($row["FormaFarmaceutica"].' - '.$row["Presentacion"]);
	
	$Divisor=$row["Divisor"];
	$Descripcion=$row["Descripcion"];
	
	$respDetalleMedicina=$puntero->ObtenerVencimientoProximo(0,$rowMedicina["IdMedicina"],$FechaInicio,$FechaFin);
		$Existencias=0;$CodigoLote=''; $Vencimiento='';$Costo=0;
	while($rowDetalle=mysql_fetch_array($respDetalleMedicina)){
	$Existencias+=$rowDetalle["Existencia"];
		
	$CodigoLote.=strtoupper($rowDetalle["Lote"])."<br>";
		
	$VencimientoT=$rowDetalle["FechaVencimiento"];
	$tmp=explode('-',$VencimientoT);
	$Vencimiento.=$tmp[1]."/".$tmp[0]."<br>";
		$Costo+=($Existencias/$Divisor)*$rowDetalle["PrecioLote"];
	}
	
$TotalExistencia=$Existencias;
	if($respDivisor=mysql_fetch_array($puntero->ValorDivisor($rowMedicina["IdMedicina"]))){
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


	$respLotes=$puntero->ObtenerLotes($rowMedicina["IdMedicina"],$FechaInicio,$FechaFin);
	$CodigoLote="<hr>";$Vencimiento="<hr>";
	while($rowLotes=mysql_fetch_array($respLotes)){
	   $CodigoLote.=$rowLotes[0]."<br><hr>";
		$VencimientoT=$rowLotes[1];
		$tmp=explode('-',$VencimientoT);
		$Vencimiento.=$tmp[1]."/".$tmp[0]."<br><hr>";
		
	}

$reporte.='<tr class="FONDO">
<td align="center" style="vertical-align:middle;">"'.$Codigo.'"</td>
<td align="center" style="vertical-align:middle;">'.$NombreMedicina."-".$Concentracion." <br> ".$FormaFarmaceutica.'</td>
<td align="center" style="vertical-align:middle;">'.$CantidadIntro.'</td>
<td align="center" style="vertical-align:middle;">'.$Descripcion.'</td>
<td align="center" style="vertical-align:middle;">'.$CodigoLote.'</td>
<td align="center" style="vertical-align:middle;">'.$Vencimiento.'</td>
<td align="center" style="vertical-align:middle;">$ '.$Costo.'</td>
</tr>';
	$SubTotal+=$Costo;
 }//fin de while

}while($rowMedicina=mysql_fetch_array($respMedicina));
}

if($SubTotalB!=0){
$reporte.='<tr class="FONDO"><td align="right" style="vertical-align:middle;" colspan=6>SubTotal: </td><td><strong>$ '.$SubTotal.'</strong></td></tr>';
}

$Total+=$SubTotal;
}while($rowGrupo=mysql_fetch_array($respGrupos));//Grupo terapeutico

}else{

$reporte.="NO EXISTEN DATOS!";

}
$reporte.='<tr class="FONDO"><td align="right" style="vertical-align:middle;" colspan=6>Total: </td><td><strong>$ '.$Total.'</strong></td></tr>';
$reporte.="</table>";

//CIERRE DE ARCHIVO EXCEL
	fwrite($punteroarchivo,$reporte);
	fclose($punteroarchivo);
//***********************
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
break;

}
conexion::desconectar();
}
?>