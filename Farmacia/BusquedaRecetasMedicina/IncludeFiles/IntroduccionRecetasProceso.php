<?php session_start();  
if(isset($_SESSION["IdPersonal"])){


$IdPersonal=$_SESSION["IdPersonal"];
if(isset($_GET["IdArea"])){$IdArea=$_GET["IdArea"];}
$path='../';
require('RecetasProcesoClase.php');
conexion::conectar();
$proceso=new RecetasProceso;
$Bandera=$_GET["Bandera"];

$IdEstablecimiento=$_SESSION["IdEstablecimiento"];
$IdModalidad=$_SESSION["IdModalidad"];

switch($Bandera){

case 1:
	//**************DATOS DE FILTRACION **************
	$IdMedicina=$_GET["IdMedicina"];
	$IdFarmacia=$_GET["IdFarmacia"];
	$IdArea=$_GET["IdArea"];
	$IdSubEspecialidad=$_GET["IdSubEspecialidad"];
	$IdEmpleado=$_GET["IdEmpleado"];
		$nick=$_SESSION["nick"];
	$FechaInicial=$_GET["FechaInicial"];
	$FechaFinal=$_GET["FechaFinal"];
	//************************************************


 $Cierre=$proceso->Cierre($FechaInicial);
 $CierreMes=$proceso->CierreMes($FechaInicial);
	 $respCierre=mysql_fetch_array($Cierre);
	 $respCierreMes=mysql_fetch_array($CierreMes);
 if(($respCierre[0]!=NULL and $respCierre[0]!='') || ($respCierreMes[0]!=NULL and $respCierreMes[0]!='')){
 
 		if($respCierre[0]!=NULL and $respCierre[0]!=''){$c=$respCierre[0];}else{$c=$respCierreMes[0];}
		echo "NO~".$c;
 }else{

	
	//     GENERACION DE EXCEL
		$NombreExcel='BusquedaMedicamentos_'.$nick.'_'.date('m_Y');
			$path='../';
		$nombrearchivo =$path."../ReportesExcel/".$NombreExcel.".xls";
		$punteroarchivo = fopen($nombrearchivo, "w+") or die("El archivo de reporte no pudo crearse");
	//***********************

	
	$resp=$proceso->ObtenerRecetas($IdMedicina,$IdFarmacia,$IdArea,$IdSubEspecialidad,
                        $IdEmpleado,$FechaInicial,$FechaFinal,$IdEstablecimiento,$IdModalidad);
	if($IdMedicina==''){$IdMedicina=0;}
	$reporte='<table width="100%" border="1">
			<tr class="FONDO2"><td colspan="7" align="center"><strong>Busqueda de Medicamento por Periodo de Entrega<br>Periodo del: '.$FechaInicial.' al '.$FechaFinal.'</strong></td></tr>
			<tr class="FONDO2"><td width="12%" align="center"><strong>Receta #</strong></td>
			<td width="21%" align="center"><strong>Medicamento</strong></td>
			<td width="12%" align="center"><strong>Cantidad de Medicamento [Unidades]</strong></td>
			<td width="20%" align="center"><strong>Medico/Enfermera</strong></td>
			<td width="11%" align="center"><strong>Fecha</strong></td>
			<td width="10%" align="center"><strong>Ubicacion de Receta</strong></td>
			<td width="14%" align="center"><strong>Digitado Por</strong></td>
			</tr>';
	while($row=mysql_fetch_array($resp)){

	if($respDivisor=mysql_fetch_array($proceso->ValorDivisor($row["IdMedicina"],$IdEstablecimiento,$IdModalidad))){
		$Divisor=$respDivisor[0];

		if($row["Cantidad"] < 1){
			//Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
		   $TransformaEntero=number_format($row["Cantidad"]*$Divisor,0,'.',',');
			$CantidadTransformada=$TransformaEntero.'/'.$Divisor;
		}else{
			//Si la cantidad es mayor a un frasco se realiza este cambio a quebrados
			$CantidadReal=number_format($row["Cantidad"],2,'.',',');	
		$CantidadBase=explode('.',$CantidadReal);
		
		    $Entero=$CantidadBase[0];//Faccion ENTERA

			$Decimal=$CantidadBase[1];
		    if($Decimal==0){$Decimal="";$Quebrado="";}else{
			
			$Quebrado=number_format(($Decimal/100)*$Divisor,0,'.',',');
			$Quebrado='['.$Quebrado.'/'.$Divisor.']';
		    }

			
		$CantidadTransformada=$Entero.' '.$Quebrado;
		}
	   $CantidadIntro=$CantidadTransformada;
		
	}else{
	   $CantidadIntro=  number_format($row["Cantidad"],0,'.','');
	}
		
		if($row["CorrelativoAnual"]=="" or $row["CorrelativoAnual"]==NULL){$CodigoReceta=$row["IdReceta"];}else{$CodigoReceta=$row["CorrelativoAnual"];}	

		$reporte.='<tr class="FONDO2"><td align="center" style="vertical-align:middle;"><a style="color:#0033FF;" onclick="VentanaBusqueda3('.$row["IdReceta"].','.$IdMedicina.');">'.$CodigoReceta.'</a></td><td style="vertical-align:middle;">'.htmlentities($row["Nombre"]).', '.htmlentities($row["Concentracion"].' - '.$row["FormaFarmaceutica"].' - '.$row["Presentacion"]).'</td><td align="center" style="vertical-align:middle;">'.$CantidadIntro.'</td><td style="vertical-align:middle;"><p>'.htmlentities($row["NombreEmpleado"]).'</p></td><td align="center" style="vertical-align:middle;">'.$row["Fecha"].'</td><td align="center" style="vertical-align:middle;">'.$row["NombreFarmacia"]." -> ".$row["Area"].'</td>
	<td align="center" style="vertical-align:middle;">'.htmlentities($row["Digitador"]).'</td>		
	</tr>';
		
	}//while
	
	$reporte.='</table>';
	//CIERRE DE ARCHIVO EXCEL
		fwrite($punteroarchivo,$reporte);
		fclose($punteroarchivo);
	//***********************
			$nombrearchivo="../ReportesExcel/".$NombreExcel.".xls";
	echo '<a href="'.$nombrearchivo.'"><H5>DESCARGAR REPORTE EXCEL <img src="../images/excel.gif"></H5></a>~'.$reporte;
}//Verificacion de Cierre
break;

case 2:
	//DESPLIEGUE DE DETALLE DE RECETA
	//datos de filtrado
	$IdReceta=$_GET["IdReceta"];
	$IdMedicinaOrigen=$_GET["IdMedicina"];
	
	$resp=$proceso->DetalleReceta($IdReceta,$IdEstablecimiento,$IdModalidad);
	$tabla='
	<table width="100%">
	<tr class="FONDO2">
	<td width="241" align="center"><strong>Medicamento</strong></td>
	<td width="251" align="center"><strong>Presentacion</strong></td>
	<td width="165" align="center"><strong>Cantidad de Medicamento [Unidades]</strong></td>
	<td width="158" align="center"><strong>Estado de Entrega</strong></td>
	<td width="158" align="center"><strong>Eliminar</strong></td>
	</tr>';
	while($row=mysql_fetch_array($resp)){
		$disabled='';
	   if($row['IdMedicina']!=$IdMedicinaOrigen and $IdMedicinaOrigen!=0){$disabled='disabled="disabled"';}
		
		if($row["EstadoMedicina"]=='S'){
		   $comboEstado="<input type='checkbox' id='Estado' name='Estado' checked='true' onclick='CambioEstadoDetalle(".$row["IdMedicinaRecetada"].",\"I\",".$row["IdReceta"].",".$IdMedicinaOrigen.");' ".$disabled."><div id='".$row["IdMedicinaRecetada"]."'></div>";
		}else{
		   $comboEstado="<input type='checkbox' id='Estado' name='Estado' onclick='CambioEstadoDetalle(".$row["IdMedicinaRecetada"].",\"S\",".$row["IdReceta"].",".$IdMedicinaOrigen.");' ".$disabled."><div id='".$row["IdMedicinaRecetada"]."'></div>";
		}
		
	if($respDivisor=mysql_fetch_array($proceso->ValorDivisor($row["IdMedicina"],$IdEstablecimiento,$IdModalidad))){
		$Divisor=$respDivisor[0];

		if($row["Cantidad"] < 1){
			//Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
		   $TransformaEntero=number_format($row["Cantidad"]*$Divisor,0,'.',',');
			$CantidadTransformada=$TransformaEntero.'/'.$Divisor;
		}else{
			//Si la cantidad es mayor a un frasco se realiza este cambio a quebrados
			$CantidadReal=number_format($row["Cantidad"],2,'.',',');	
		$CantidadBase=explode('.',$CantidadReal);
		
		    $Entero=$CantidadBase[0];//Faccion ENTERA

			$Decimal=$CantidadBase[1];
		    if($Decimal==0){$Decimal="";$Quebrado="";}else{
			
			$Quebrado=number_format(($Decimal/100)*$Divisor,0,'.',',');
			$Quebrado='['.$Quebrado.'/'.$Divisor.']';
		    }

			
		$CantidadTransformada=$Entero.' '.$Quebrado;
		}
	   $CantidadIntro=$CantidadTransformada;
		
	}else{
	   $CantidadIntro=$row["Cantidad"];
	}
		
		$tabla.='<tr class="FONDO2"><td style="vertical-align:middle;">'.$row["Nombre"].', '.$row["Concentracion"].'</td><td style="vertical-align:middle;">'.htmlentities($row["FormaFarmaceutica"].', '.$row["Presentacion"]).'</td><td style="vertical-align:middle;" align="center">'.$CantidadIntro.'</td><td style="vertical-align:middle;" align="center">'.$comboEstado.'</td>
		<td style="vertical-align:middle;" align="center"><input type="button" id="Eliminar" name="Eliminar" value="Eliminar" onclick="EliminarMedicina('.$row["IdMedicinaRecetada"].','.$IdReceta.','.$IdMedicinaOrigen.')" '.$disabled.'></td></tr>
		';
	}
     $tabla.='</table>';
	echo $tabla;
break;

case 3:
// Creacion del Combo de Area
	$IdFarmacia=$_GET["IdFarmacia"];
	$resp=$proceso->ObtenerComboAreas($IdFarmacia,$IdEstablecimiento,$IdModalidad);
	$combo='<select id="IdArea" name="IdArea" style="font-style:italic;">
                <option value="0">...::: Area :::...</option>';
	while($row=mysql_fetch_array($resp)){
		$combo.='<option value="'.$row[0].'">'.$row[1].'</option>';
	}
	
	$combo.='</select>';
	echo $combo;
break;

case 4:

break;

case 5:
/* Introduccion de medicina de la Receta */
$Cantidad=$_GET["Cantidad"];
$IdReceta=$_GET["IdReceta"];
$IdMedicina=$_GET["IdMedicina"];
$Dosis=$_GET["Dosis"];
$Satisfecha=$_GET["Satisfecha"];
$Fecha=$_GET["Fecha"];

$proceso->IntroducirMedicinaPorReceta($IdReceta,$IdMedicina,$Cantidad,$Dosis,$Satisfecha,$Fecha);

/*DESPLEGAR DATOS DE RECETA*/
$resp=$proceso->ObtenerMedicinaIntroducida($IdReceta);

$tabla='<table width="744">
		<tr><td colspan="5" align="center"><strong>DETALLE DE RECETA</strong></td></tr>
		<tr class="FONDO"><td width="150" align="center"><strong>Cantidad</strong></td>
		<td width="303" align="center"><strong>Medicina</strong></td>
		<td width="275" align="center"><strong>Dosis</strong></td>
		<td width="275" align="center"><strong>Insatisfecha</strong></td>
		<td width="275" align="center"><strong>Eliminar</strong></td>
		</tr>';
	while($row=mysql_fetch_array($resp)){
	if($row["IdEstado"]=='I'){
		$check='<input id="Insa'.$row["IdMedicinaRecetada"].'" name="Insa'.$row["IdMedicinaRecetada"].'" type="checkbox" value="I" onclick="javascript:CambioEstado('.$row["IdMedicinaRecetada"].','.$row["IdMedicina"].')" checked="checked">';
	}else{
		$check='<input id="Insa'.$row["IdMedicinaRecetada"].'" name="Insa'.$row["IdMedicinaRecetada"].'" type="checkbox" value="I" onclick="javascript:CambioEstado('.$row["IdMedicinaRecetada"].','.$row["IdMedicina"].')">';
	}
	
if($respDivisor=mysql_fetch_array($proceso->ValorDivisor($row["IdMedicina"])) and $_SESSION["TipoFarmacia"]==1){
		$Divisor=$respDivisor[0];

		if($row["Cantidad"] < 1){
			//Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
		   $TransformaEntero=number_format($row["Cantidad"]*$Divisor,0,'.',',');
			$CantidadTransformada=$TransformaEntero.'/'.$Divisor;
		}else{
			//Si la cantidad es mayor a un frasco se realiza este cambio a quebrados
			$CantidadReal=number_format($row["Cantidad"],2,'.',',');	
		$CantidadBase=explode('.',$CantidadReal);
		
		    $Entero=$CantidadBase[0];//Faccion ENTERA

			$Decimal=$CantidadBase[1];
		    if($Decimal==0){$Decimal="";$Quebrado="";}else{
			
			$Quebrado=number_format(($Decimal/100)*$Divisor,0,'.',',');
			$Quebrado='['.$Quebrado.'/'.$Divisor.']';
		    }

			
		$CantidadTransformada=$Entero.' '.$Quebrado;
		}
	   $CantidadIntro=$CantidadTransformada;
		
	}else{
	   $CantidadIntro=$row["Cantidad"];
	}


		$tabla=$tabla.'<tr class="FONDO"><td align="center"><a style="color:red;" onclick="javascript:VentanaBusqueda4(\'ModificaCantidad.php?IdMedicinaRecetada='.$row["IdMedicinaRecetada"].'\')">'.$CantidadIntro.'</a></td><td align="center">'.$row["Nombre"]."<br>".$row["Concentracion"].' - '.$row["FormaFarmaceutica"].' - '.$row["Presentacion"].'</td><td align="center"><a style="color:blue;" onclick="javascript:VentanaBusqueda4(\'ModificaDosis.php?IdMedicinaRecetada='.$row["IdMedicinaRecetada"].'\')">'.$row["Dosis"].'</a></td><td align="center">'.$check.'</td><td align="center"><input type="button" id="BorrarMedicamento" name="BorrarMedicamento" value="Eliminar Medicamento" onclick="javascript:EliminaMedicina('.$row["IdMedicinaRecetada"].')"></td>
		</tr>';
	}//while resp
$tabla=$tabla."</table>";

echo $tabla;
/* FIN DESPLIEGUE DATOS */
break;

case 6:


break;

case 7:
	$IdMedicinaRecetada=$_GET["IdMedicinaRecetada"];
	$proceso->EliminarMedicina($IdMedicinaRecetada);
	
break;

case 8:
	$IdMedicina=$_GET["IdMedicina"];
	$IdReceta=$_GET["IdReceta"];
	$Cantidad=$_GET["Cantidad"];

		if($row=mysql_fetch_array($proceso->ValorDivisor($IdMedicina,$IdEstablecimiento,$IdModalidad))){
		   $Cantidad=$Cantidad/$row[0];
		}	

	$proceso->GuardarNuevaMedicina($IdReceta,$IdMedicina,$Cantidad,$IdEstablecimiento,$IdModalidad);	
	
break;

case 9:


break;

case 10:
$Estado=$_GET["Estado"];
$IdMedicinaRecetada=$_GET["IdMedicinaRecetada"];
$IdMedicina=$_GET["IdMedicina"];
$proceso->UpdateMedicinaRecetada($IdMedicinaRecetada,$Estado,$IdMedicina);

break;

case 11:
	if(isset($_GET["IdMedico"])){
		$IdMedico=$_GET["IdMedico"];
		$resp=$proceso->ObtenerCodigoFarmacia($IdMedico);
	}else{
		$IdSubEspecialidad=$_GET["IdSubEspecialidad"];
		$resp=$proceso->ObtenerEspecialidad($IdSubEspecialidad);
	}
	echo $resp;
break;
case 12:
	$CodigoFarmacia=$_GET["CodigoFarmacia"];
	$resp=$proceso->ObtenerDatosMedico($CodigoFarmacia);
	$respuesta=$resp[0].'/'.$resp[1];
	echo $respuesta;
break;

case 13:
	/*MOSTRAR SUBESPECIALIDADES O SERVICIO ORIGEN DE RECETA*/
		$Codigo=strtoupper($_GET["Codigo"]);
		$query="select mssxe.IdSubServicio,NombreSubServicio, NombreServicio as Ubicacion
			from mnt_subservicio
                        inner join mnt_subservicioxestablecimiento mssxe
                        on mssxe.IdSubServicio=mnt_subservicio.IdSubServicio
                        inner join mnt_servicioxestablecimiento msxe
                        on msxe.IdServicioxEstablecimiento=mssxe.IdServicioxEstablecimiento
			inner join mnt_servicio s
			on s.IdServicio=msxe.IdServicio
			where mssxe.CodigoFarmacia='$Codigo'";

		$resp=mysql_fetch_array(mysql_query($query));
		if($resp["Ubicacion"]!=NULL and $resp["Ubicacion"]!=""){$Ubicacion=$resp["Ubicacion"]." -> ";}else{$Ubicacion="";}
			$NombreSubEspecialidad=$Ubicacion."".$resp["NombreSubServicio"];
		
		echo $resp["IdSubServicio"]."/".strtoupper($NombreSubEspecialidad);
break;

case 14:
	/*	ACTUALIZACIONES */

	if(isset($_GET["IdArea"])){
		$IdArea=$_GET["IdArea"];
		$IdHistorialClinico=$_GET["IdHistorialClinico"];
		$IdReceta=$_GET["IdReceta"];
		
		if($IdReceta=='' or $IdReceta==NULL){
			$salida='N';
		}else{
			$salida=$proceso->ActualizarArea($IdArea,$IdReceta);
		}
		
	}//Actualizacion de Area
	
	if(isset($_GET["IdMedico"])){
		
		$IdHistorialClinico=$_GET["IdHistorialClinico"];
		$IdMedico=$_GET["IdMedico"];
		
		if($IdHistorialClinico=='' or $IdHistorialClinico==NULL){
			$salida='N';
		}else{
			$salida=$proceso->ActualizarMedico($IdHistorialClinico,$IdMedico);
		}
	}//Actualizacion de Medico
	
	if(isset($_GET["IdSubEspecialidad"])){
		$IdHistorialClinico=$_GET["IdHistorialClinico"];
		$IdSubEspecialidad=$_GET["IdSubEspecialidad"];
		
		if($IdHistorialClinico=='' or $IdHistorialClinico==NULL){
			$salida='N';
		}else{
			$salida=$proceso->ActualizarEspecialidad($IdHistorialClinico,$IdSubEspecialidad);
		}
	}//Actualizacoin de Especialidad
	
	echo $salida;
break;
case 15:
	$IdOrigenCambio=$_GET["IdOrigenCambio"];
	$Tools="";
	switch($IdOrigenCambio){
		case 'NombreArea':
			$Tools="<table>
			<tr class='FONDO2'>
			<td>
			<select id='IdArea2' name='IdArea2' onChange='PegarIdArea(this.value);'>
			<option value='0'>[Seleccione ...]</option>";
			$resp=$proceso->ObtenerArea();
			while($row=mysql_fetch_array($resp)){
				$Tools.="<option value='".$row[0]."'>".$row[1]."</option>";
			}			
			$Tools.="</select>
			</td>
	   <td>
	   <input type='button' id='Cambiar3' name='Cambiar3' value='Corregir' onClick='CorregirArea();'>
	   </td>
	   </tr>
	   </table>";
			
		break;
		case 'NombreMedico':
			$Tools='<table>
			<tr class="FONDO2">
			<td>
			<input id="CodigoFarmacia" name="CodigoFarmacia" type="text" maxlength="4" onBlur="javascript:ObtenerDatosMedico();" style="width:50px;" onKeyPress="return Saltos(event,this.id);"><input type="button" id="Buscador" name="Buscador" onClick="javascript:VentanaBusqueda2();" value="...">
		<!-- <input type="text" id="IdEspecialidad" name="IdEspecialidad"> -->
		<input type="hidden" id="IdMedico" name="IdMedico"><strong><div id="NombreMedico2"></div></strong>
	   </td>
	   <td>
	   <input type="button" id="Cambiar1" name="Cambiar1" value="Corregir" onClick="CorregirMedico();">
	   </td>
	   </tr>
	   </table>';
			
			
		break;
		case 'Especialidad':
			$Tools='<table>
			<tr class="FONDO2">
			<td>
			<input id="CodigoSubEspecialidad" name="CodigoSubEspecialidad" type="text" maxlength="4" onBlur="javascript:CargarSubEspecialidad(this.value);" style="width:50px;" onKeyPress="return Saltos(event,this.id);"><input type="button" id="Buscador2" name="Buscador2" onClick="javascript:VentanaBusqueda();" value="...">
	   <input type="hidden" id="IdSubEspecialidad" name="IdSubEspecialidad" ><strong><div id="NombreSubEspecialidad"></div></strong>
	   </td>
	   <td>
	   <input type="button" id="Cambiar2" name="Cambiar2" value="Corregir" onClick="CorregirEspecialidad();">
	   </td>
	   </tr>
	   </table>';
	   
						
		break;
	}//switch

	echo $Tools;
	
break;
case 16:
	//*****Cambio de Estado del Detalle
	$IdMedicinaReceta=$_GET["IdMedicinaRecetada"];
	$IdEstado=$_GET["Estado"];
	$proceso->ActualizaEstadoMedicina($IdMedicinaReceta,$IdEstado);
	
break;
case 17:
	$IdMedicina=$_GET["IdMedicina"];
	$IdArea=$_GET["IdArea"];

	$IdArea=$proceso->ObtenerAreaReceta($IdArea);

	echo $proceso->ObtenerExistencia($IdMedicina,$IdArea);

break;
}//Fin de switch
conexion::desconectar();
}else{

echo "ERROR_SESSION";
}
?>