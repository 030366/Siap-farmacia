<?php session_start();

if(!isset($_SESSION["nivel"])){
   echo "ERROR_SESSION";
}else{
$IdPersonal=$_SESSION["IdPersonal"];
require('TransferenciasProcesoClase.php');
conexion::conectar();
$proceso=new TransferenciaProceso;
$Bandera=$_GET["Bandera"];
switch($Bandera){

case 1:
/* OBTENCION DE DATOS PARA LA INTRODUCCION DE TRANSFERENCIAS DE MEDICAMENTOS */
	$Cantidad=$_GET["Cantidad"];
	$IdMedicina= $_GET["IdMedicina"];
	$IdEstablecimientoOrigen=$_GET["IdEstablecimientoOrigen"];
	$IdEstablecimientoDestino=$_GET["IdEstablecimientoDestino"];
	$Justificacion=$_GET["Justificacion"];
	$FechaTransferencia=$_GET["Fecha"];
	$Lote=$_GET["Lote"];
	$Unidades=$_GET["Unidades"];
/* INTRODUCCION DE DATOS DE LA TRANSFERENCIA */	
	$Cantidad=$Cantidad*$Unidades;//Convierte lo ingresado a unidades de medidas normadas....

$falta=$proceso->IntroducirTransferencia($Cantidad,$IdMedicina,$IdEstablecimientoOrigen,$IdEstablecimientoDestino,$Justificacion,$FechaTransferencia,$IdPersonal,$Lote);

echo '<select id="IdLote" name="IdLote" disabled="disabled"><option value="0">[Seleccione Lote...]</option></select>~<strong>'.$falta.'</h2></strong>';
break;

case 2:
/* MUESTRA LAS TRANSFERENCIAS INTRODUCIDAD */
	$Fecha=$_GET["Fecha"];
	$IdAreaOrigen=$_GET["IdAreaOrigen"];
$resp=$proceso->ObtenerTransferencias($IdPersonal,$Fecha,$_SESSION["IdEstablecimiento"]);
/*TABLA DE TRANSFERENCIAS*/
if($row=mysql_fetch_array($resp)){
$tabla='<table width="1018" border="1">
		<tr><td colspan="7" align="center"><strong>TRANFERENCIA(S) REALIZADA(S)</strong></td></tr>
		<tr class="FONDO">
		<td width="116" align="center"><strong>Cantidad</strong></td>
		<td width="189" align="center"><strong>Medicamento</strong></td>
		<td width="189" align="center"><strong>Unidad de Medida</strong></td>
		<td width="131" align="center"><strong>Tranf./Lote</strong></td>
		<td width="159" align="center"><strong>Establecimiento Destino</strong></td>
		<td width="200" align="center"><strong>Justificacion</strong></td>
		<td width="74" align="center"><strong>Cancelar</strong></td>
		</tr>';
$resp2=$proceso->ObtenerTransferencias($IdPersonal,$Fecha,$_SESSION["IdEstablecimiento"]);
	while($row=mysql_fetch_array($resp2)){
	/*OBTENCION DE DETALLE DE TRANSFERENCIA POR LOTE*/
	$resp=$proceso->ObtenerDetalleLote($row["IdTransferencia"]);
	$CantidadReal=$resp["Cantidad"];
	$IdLote=$resp["IdLote"];
	$Lote=$resp["Lote"];
	$Unidades=$resp["UnidadesContenidas"];
	$DetalleLotes='';
		
if($respDivisor=mysql_fetch_array($proceso->ValorDivisor($resp["IdMedicina"],$_SESSION["IdEstablecimiento"]))){
		$Divisor=$respDivisor[0];

		if($CantidadReal < 1){
			//Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
		   $TransformaEntero=number_format($CantidadReal*$Divisor,0,'.',',');
			$CantidadTransformada=$TransformaEntero.'/'.$Divisor;
		}else{
			//Si la cantidad es mayor a un frasco se realiza este cambio a quebrados
			$CantidadReal=number_format($CantidadReal,3,'.',',');	
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
	   $CantidadIntro=$CantidadReal/$Unidades;
		$CantidadIntro=number_format($CantidadIntro,2,'.',',');
	}


			$DetalleLotes.="Cant.: ".$CantidadIntro."<br>Lote= ".$Lote."<br><br>";
		
	/****************************************************/
	
		$tabla=$tabla.'<tr class="FONDO"><td align="center">'.$CantidadIntro.'</td><td align="center">'.$row["Nombre"].', '.$row["Concentracion"].' - '.$row["Presentacion"].'</td><td align="center">'.$row["Descripcion"].'</td><td align="center">'.$DetalleLotes.'</td><td align="center">'.$row["EstablecimientoDestino"].'</td><td>'.$row["Justificacion"].'</td><td align="center"><input type="button" id="borrar" name="borrar" value="Eliminar" onclick="javascript:BorrarTransferencia('.$row["IdTransferencia"].')"></td></tr>';
	}//while resp
$tabla=$tabla.'</table>';
}else{
$tabla="";
}
echo $tabla;
break;

case 3:
/*ELIMINACION DE TRANSFERENCIA*/
$IdTransferencia=$_GET["IdTransferencia"];
$resp=$proceso->EliminarTransferencia($IdTransferencia);
echo $resp;
break;

case 4:
/* GENERACION DEL LISTADO DE LOTES HABILITADOS PARA LA TRANSFERENCIA */
$IdMedicina=$_GET["IdMedicina"];
$Cantidad=$_GET["Cantidad"];
$IdAreaOrigen = $_GET["IdAreaOrigen"];
$resp=$proceso->ObtenerLotesMedicamento($IdMedicina,$Cantidad,$IdAreaOrigen);
$combo="<select id='IdLote' name='IdLote'>";
$combo.="<option value='0'>[Seleccione Lote...]</option>";
	$ExistenciaTotal=0;
while($row=mysql_fetch_array($resp)){
$fecha=explode('-',$row[3]);
$fecha=$fecha[2]."-".$fecha[1]."-".$fecha[0];
	$Divisor=$row["UnidadesContenidas"];
	$Unidades=$row["UnidadesContenidas"];

	$CantidadReal=$row[0];
		$ExistenciaTotal+=$row[0];

	if($respDivisor=mysql_fetch_array($proceso->ValorDivisor($IdMedicina,$_SESSION["IdEstablecimiento"]))){
		$Divisor=$respDivisor[0];

		if($CantidadReal < 1){
			//Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
		   $TransformaEntero=number_format($CantidadReal*$Divisor,0,'.',',');
			$CantidadTransformada=$TransformaEntero.'/'.$Divisor;
		}else{
			//Si la cantidad es mayor a un frasco se realiza este cambio a quebrados
				$CantidadReal=number_format($CantidadReal,3,'.',',');	
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
	   $CantidadIntro=$CantidadReal/$Divisor;
		$CantidadIntro=number_format($CantidadIntro,2,'.',',');
	}

	$combo.="<option value='".$row[1]."'>".$CantidadIntro." - ".$row[2]." -> ".$fecha."</option>";
}//while
	$ExistenciaTotal=$ExistenciaTotal/$Unidades;
 $combo.="</select><input type='hidden' id='ExistenciaTotal' value='".$ExistenciaTotal."'>";
echo $combo;

break;

case 5:
/* LIBRE */

break;

case 6:
/* CAMBIO DE ESTADO DE LAS TRANSFERENCIAS */
$resp=$proceso->ObtenerCantidadMedicina($IdPersonal);
while($row=mysql_fetch_array($resp)){
$IdMedicina=$row["IdMedicina"];$IdArea=$row["IdArea"];
/*PARES DE INFORMACION*/
$Cantidad=$row["Cantidad1"];$Lote=$row["IdLote"];
$Cantidad2=$row["Cantidad2"];$Lote2=$row["IdLote2"];
/**********************/
		if($Lote!=0){
			queries::MedicinaExistencias($IdMedicina,$Cantidad,"SI",$IdArea,$Lote);
		}
		if($Lote2!=0){
			queries::MedicinaExistencias($IdMedicina,$Cantidad2,"SI",$IdArea,$Lote2);
		}       
}//fin de while resp
$proceso->FinalizaTransferencia($IdPersonal);
break;

default:
/*LIBRE*/

break;

}//Fin de switch
conexion::desconectar();

}//fin de sesion
?>