<?php session_start();

if(!isset($_SESSION["nivel"])){
   echo "ERROR_SESSION";
}else{
$IdPersonal=$_SESSION["IdPersonal"];
$TipoFarmacia=$_SESSION["TipoFarmacia"];
require('TransferenciasProcesoClase.php');
conexion::conectar();
$proceso=new TransferenciaProceso;
$Bandera=$_GET["Bandera"];

$IdModalidad=$_SESSION["IdModalidad"];

switch($Bandera){

case 1:
/* OBTENCION DE DATOS PARA LA INTRODUCCION DE TRANSFERENCIAS DE MEDICAMENTOS */
	$Cantidad=$_GET["Cantidad"];
	$IdMedicina= $_GET["IdMedicina"];
	$IdAreaOrigen=$_GET["IdAreaOrigen"];
	$Justificacion=$_GET["Justificacion"];
	$FechaDescargo=$_GET["Fecha"];
	$Lote=$_GET["Lote"];
	$Divisor=$_GET["Divisor"];
	$UnidadesContenidas=$_GET["UnidadesContenidas"];
/* INTRODUCCION DE DATOS DE LA TRANSFERENCIA */	

    $Cantidad=$Cantidad*$UnidadesContenidas;

	if($Divisor!=0){
	   $Cantidad=$Cantidad/$Divisor;
	}	

$falta=$proceso->IntroducirDescargos($Cantidad,$IdMedicina,$IdAreaOrigen,$Justificacion,$FechaDescargo,$IdPersonal,$Lote,$TipoFarmacia,$_SESSION["IdEstablecimiento"],$IdModalidad);




echo '<select id="IdLote" name="IdLote" disabled="disabled"><option value="0">[Seleccione Lote...]</option></select>~<strong>'.$falta.'</h2></strong>';
break;

case 2:
/* MUESTRA LAS TRANSFERENCIAS INTRODUCIDAD */
	$Fecha=$_GET["Fecha"];
$resp=$proceso->ObtenerDescargos($IdPersonal,$Fecha,$_SESSION["IdEstablecimiento"],$IdModalidad);
/*TABLA DE TRANSFERENCIAS*/
if($row=mysql_fetch_array($resp)){
$tabla='<table width="1018" border="1">
		<tr><td colspan="8" align="center"><strong>DESCARGO(S) REALIZADO(S)</strong></td></tr>
		<tr class="FONDO">
		<td width="116" align="center"><strong>Movimiento No.</strong></td>
		<td width="116" align="center"><strong>Cantidad</strong></td>
		<td width="189" align="center"><strong>Medicamento</strong></td>
		<td width="189" align="center"><strong>Unidad de Medida</strong></td>
		<td width="131" align="center"><strong>Lote</strong></td>
		<td width="114" align="center"><strong>Area Origen</strong></td>
		<td width="200" align="center"><strong>Justificacion</strong></td>
		<td width="74" align="center"><strong>Cancelar</strong></td>
		</tr>';

	do{
	/*OBTENCION DE DETALLE DE TRANSFERENCIA POR LOTE*/
	$resp2=$proceso->ObtenerDetalleLote($row["IdEntrega"]);
	
	$CantidadReal=$resp2["Existencia"];
	$IdLote=$resp2["IdLote"];
	$Lote=$resp2["Lote"];
	$DetalleLotes='';
		$IdMedicina=$row["IdMedicina"];
	$UnidadesContenidas=$_GET["UnidadesContenidas"];

	if($respDivisor=mysql_fetch_array($proceso->ValorDivisor($IdMedicina,$_SESSION["IdEstablecimiento"],$IdModalidad))){
		$Divisor=$respDivisor[0];

		if($CantidadReal < 1){
			//Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
		   $TransformaEntero=number_format($CantidadReal*$Divisor,0,'.',',');
			$CantidadTransformada=$TransformaEntero.'/'.$Divisor;
		}else{
			//Si la cantidad es mayor a un frasco se realiza este cambio a quebrados
				
		$CantidadBase=explode('.',$CantidadReal);
		
		    $Entero=$CantidadBase[0];//Faccion ENTERA
			if(!isset($CantidadBase[1])){
			   $Decimal=0;
			}else{
			   $Decimal=$CantidadBase[1];
			}
			
		    if($Decimal==0){$Decimal="";$Quebrado="";}else{
			
			$Quebrado=number_format(($Decimal/100)*$Divisor,0,'.',',');
			$Quebrado='['.$Quebrado.'/'.$Divisor.']';
		    }

			
		$CantidadTransformada=$Entero.' '.$Quebrado;
		}
	   $CantidadIntro=$CantidadTransformada;
		
	}else{
	   $CantidadIntro=$CantidadReal/$UnidadesContenidas;
		$CantidadIntro=number_format($CantidadIntro,2,'.',',');
	}

		
			$DetalleLotes.="Cant.: ".$CantidadIntro."<br>Lote= ".$Lote."<br><br>";
	/****************************************************/
	   if($row["Justificacion"]!=NULL and $row["Justificacion"]!=''){
		$justificacion=$row["Justificacion"];
	   }else{
		$justificacion="Por vencimiento de Medicamento";
	   }
	/****************************************************/
	
		$tabla=$tabla.'<tr class="FONDO"><td align="center">'.$row["IdEntrega"].'</td><td align="center">'.$CantidadIntro.'</td><td align="center">'.$row["Nombre"].', '.$row["Concentracion"].' - '.$row["Presentacion"].'</td><td align="center">'.$row["Descripcion"].'</td><td align="center">'.$DetalleLotes.'</td><td align="center">'.$row["Area"].'</td><td>'.htmlentities($justificacion).'</td><td align="center"><input type="button" id="borrar" name="borrar" value="Eliminar" onclick="javascript:BorrarDescarga('.$row["IdEntrega"].')"></td></tr>';
	}while($row=mysql_fetch_array($resp));//while resp
$tabla=$tabla.'</table>';
}else{
$tabla="";
}
echo $tabla;
break;

case 3:
/*ELIMINACION DE TRANSFERENCIA*/
$IdEntrega=$_GET["IdEntrega"];
$resp=$proceso->EliminarDescargo($IdEntrega,$TipoFarmacia,$_SESSION["IdEstablecimiento"],$IdModalidad);
echo $resp;
break;

case 4:
/* GENERACION DEL LISTADO DE LOTES HABILITADOS PARA LA TRANSFERENCIA */
$IdMedicina=$_GET["IdMedicina"];
$Motivo=$_GET["Motivo"];//1:Vencimiento  2: Averiado
$IdArea=$_GET["IdAreaOrigen"];

$resp=$proceso->ObtenerLotesMedicamento($IdMedicina,$Motivo,$IdArea,$TipoFarmacia,$_SESSION["IdEstablecimiento"],$IdModalidad);
$combo="<select id='IdLote' name='IdLote'>";
$combo.="<option value='0'>[Seleccione Lote...]</option>";
	$ExistenciaTotal=0;
while($row=mysql_fetch_array($resp)){
$fecha=explode('-',$row[2]);
$fecha=$fecha[2]."-".$fecha[1]."-".$fecha[0];

	/*$CantidadReal=$row[0];
		$ExistenciaTotal+=$row[0];

	if($respDivisor=mysql_fetch_array($proceso->ValorDivisor($IdMedicina))){
		$Divisor=$respDivisor[0];

		if($CantidadReal < 1){
			//Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
		   $TransformaEntero=number_format($CantidadReal*$Divisor,0,'.',',');
			$CantidadTransformada=$TransformaEntero.'/'.$Divisor;
		}else{
			//Si la cantidad es mayor a un frasco se realiza este cambio a quebrados
				
		$CantidadBase=explode('.',$CantidadReal);
		
		    $Entero=$CantidadBase[0];//Faccion ENTERA
			if(!isset($CantidadBase[1])){
			   $Decimal=0;
			}else{
			   $Decimal=$CantidadBase[1];
			}
			
		    if($Decimal==0){$Decimal="";$Quebrado="";}else{
			
			$Quebrado=number_format(($Decimal/100)*$Divisor,0,'.',',');
			$Quebrado='['.$Quebrado.'/'.$Divisor.']';
		    }

			
		$CantidadTransformada=$Entero.' '.$Quebrado;
		}
	   $CantidadIntro=$CantidadTransformada;
		
	}else{
	   $CantidadIntro=$CantidadReal;
		$CantidadIntro=number_format($CantidadIntro,2,'.',',');
	}*/

	$combo.="<option value='".$row[0]."'>".$row[1]." -> ".$fecha."</option>";
}//while
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