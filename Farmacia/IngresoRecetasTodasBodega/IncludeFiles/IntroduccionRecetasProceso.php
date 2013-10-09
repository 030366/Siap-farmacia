<?php session_start();  

if(!isset($_SESSION["nivel"])){
   echo "ERROR_SESSION";
}else{
//$IdPersonal=$_SESSION["IdPersonal"];
if(isset($_GET["IdArea"])){$IdArea=$_GET["IdArea"];}else{$IdArea="";}
require('RecetasProcesoClase.php');
conexion::conectar();
$proceso=new RecetasProceso;
$Bandera=$_GET["Bandera"];
switch($Bandera){

case 1: 
/* Creacion de una Receta y manejo de sec_historial_clinico */
	$Expediente= $_GET["Expediente"];
	$IdMedico=$_GET["IdMedico"];
	$Fecha=$_GET["Fecha"];
	$IdSubServicio=$_GET["IdSubServicio"];
	$IdFarmacia=$_GET["IdFarmacia"];
		$IdPersonal=$_GET["IdPersonal"];
		$IdEstablecimiento=$_GET["IdEstablecimiento"];
	$IdAreaOrigen=$_GET["IdAreaOrigen"];
/* Creacion de un IdHistorialClinico */	
 $Cierre=$proceso->Cierre($Fecha);
 $CierreMes=$proceso->CierreMes($Fecha);
	 $respCierre=mysql_fetch_array($Cierre);
	 $respCierreMes=mysql_fetch_array($CierreMes);
 if(($respCierre[0]!=NULL and $respCierre[0]!='') || ($respCierreMes[0]!=NULL and $respCierreMes[0]!='')){
 		if($respCierre[0]!=NULL and $respCierre[0]!=''){ $c=$respCierre[0];	}else{ $c=$respCierreMes[0]; }
		echo "NO~".$c;
 }else{
 $IdHistorialClinico=$proceso->IntroducirHistorialClinico($Expediente,$IdMedico,$IdSubServicio,$Fecha,$IdPersonal,$IdEstablecimiento);
 $proceso->IntroducirRecetaNueva($IdHistorialClinico,$IdMedico,$IdPersonal,$Fecha,$IdArea,$IdFarmacia,$IdAreaOrigen);
 $IdReceta=$proceso->ObtenerIdReceta($IdHistorialClinico,$IdPersonal);
  
	$CorrelativoAnual=$proceso->ObtenerCorrelativoAnual($IdReceta,$Fecha);

	echo $IdHistorialClinico."~".$IdReceta."~".$CorrelativoAnual;
}
break;

case 2:
/* Introduccion de Medicina de una Receta */
	$IdMedicina=$_GET["IdMedicina"];
	$IdReceta=$_GET["IdReceta"];
	$Cantidad=$_GET["Cantidad"];
	$BanderaRepetitiva=$_GET["BanderaRepetitiva"];
	if($BanderaRepetitiva==1){$NumeroMeses=$_GET["Repetitiva"];}else{$NumeroMeses=0;}

break;

case 3:
/* Recetas Repetitivas */
$Cantidad=$_GET["Cantidad"];
$IdReceta=$_GET["IdReceta"];
$IdMedicina=$_GET["IdMedicina"];
$Dosis=$_GET["Dosis"];
$NumeroRepetitiva=$_GET["NumeroRepetitiva"];
$IdHistorialClinico= $_GET["IdHistorialClinico"];
$IdMedico=$_GET["IdMedico"];


$proceso->IntroducirMedicinaPorReceta($IdReceta,$IdMedicina,$Cantidad,$Dosis);
$resp=$proceso->ObtenerMedicinaIntroducida($IdReceta);
$tabla='<table width="744">
		<tr><td colspan="4" align="center"><strong>RECETA DEL DIA</strong></td></tr>
		<tr class="FONDO"><td width="150" align="center"><strong>Cantidad</strong></td>
		<td width="303" align="center"><strong>Medicina</strong></td>
		<td width="275" align="center"><strong>Dosis</strong></td>
		<td width="275" align="center"><strong>Eliminar</strong></td>
		</tr>';
	while($row=mysql_fetch_array($resp)){
		$tabla=$tabla.'<tr class="FONDO"><td align="center"><a style="color:red;" onclick="">'.$row["Cantidad"].'</a></td><td align="center">'.$row["Nombre"].', '.$row["Concentracion"].'</td><td align="center"><a style="color:blue;" onclick="javascript:VentanaBusqueda4(\'ModificaDosis.php?IdMedicinaRecetada='.$row["IdMedicinaRecetada"].'\')">'.$row["Dosis"].'</a></td><td align="center"><input type="button" id="BorrarMedicamento" name="BorrarMedicamento" value="Eliminar Medicamento" onclick="javascript:EliminaMedicina('.$row["IdMedicinaRecetada"].')"></td></tr>';
	}//while resp
$tabla=$tabla.'</table>';


//REPETITIVAS
for($i=1; $i<=$NumeroRepetitiva;$i++){
$Fecha=$proceso->ObtenerFecha($i);//Aumento de la fecha

$IdReceta=$proceso->ObtenerIdRecetaRepetitiva($IdHistorialClinico,$Fecha);
	if($IdReceta==NULL){
		$proceso->IntroducirRecetaNuevaRepetitiva($IdHistorialClinico,$IdMedico,$IdPersonal,$Fecha,$IdArea);
		$IdReceta=$proceso->ObtenerIdRecetaRepetitiva($IdHistorialClinico,$Fecha);
		$proceso->IntroducirMedicinaPorReceta($IdReceta,$IdMedicina,$Cantidad,$Dosis);
	}else{//ELSE IdReceta NULL
		$proceso->IntroducirMedicinaPorReceta($IdReceta,$IdMedicina,$Cantidad,$Dosis);
	}//fin de else IdReceta NULL
}//fin del for

$resp=$proceso->ObtenerRecetaRepetitiva($IdHistorialClinico,$IdPersonal);
$resp2=$proceso->ObtenerRecetaRepetitiva($IdHistorialClinico,$IdPersonal);
$tabla2='<table width="744">
		<tr><td colspan="4" align="center"><strong>RECETAS REPETITIVAS</strong></td></tr>
		<tr class="FONDO"><td width="150" align="center"><strong>Cantidad</strong></td>
		<td width="303" align="center"><strong>Medicina</strong></td>
		<td width="275" align="center"><strong>Dosis</strong></td>
		<td width="275" align="center"><strong>Fecha</strong></td>
		<td width="275" align="center"><strong>Eliminar</strong></td>
		</tr>';
		$row2=mysql_fetch_array($resp2);	
	while($row=mysql_fetch_array($resp)){

// 

		$tabla2=$tabla2.'<tr class="FONDO"><td align="center"><a style="color:red;" onclick="">'.$row["Cantidad"].'</a></td><td align="center">'.$row["Nombre"].', '.$row["Concentracion"].'</td><td align="center"><a style="color:blue;" onclick="javascript:VentanaBusqueda4(\'ModificaDosis.php?IdMedicinaRecetada='.$row["IdMedicinaRecetada"].'\')">'.$row["Dosis"].'</a></td><td align="center">'.$row["Fecha"].'</td><td align="center"><input type="button" id="BorrarMedicamento" name="BorrarMedicamento" value="Eliminar Medicamento" onclick="javascript:EliminaMedicina('.$row["IdMedicinaRecetada"].')"></td></tr>';
		$row2=mysql_fetch_array($resp2);	
			if($row2["Fecha"]!=$row["Fecha"]){$tabla2=$tabla2.'<tr><td colspan="4"><hr></td>
			</tr>';}	
	}//while resp
$tabla2=$tabla2.'</table>';

echo $tabla."<br>".$tabla2;

break;

case 4:
/* ELIMINAR RECETA */
$IdHistorialClinico=$_GET["IdHistorialClinico"];
$IdReceta=$_GET["IdReceta"];
	$IdPersonal=$_GET["IdPersonal"];
$IdArea=$_GET["IdArea"];


$proceso->EliminarReceta($IdHistorialClinico,$IdPersonal,$IdReceta,$IdArea);

$RecetasIngresadas=$proceso->RecetasIngresadasConteo($IdPersonal);

echo $RecetasIngresadas;

break;

case 5:
/* Introduccion de medicina de la Receta */
$Cantidad=$_GET["Cantidad"];
$IdReceta=$_GET["IdReceta"];
$IdMedicina=$_GET["IdMedicina"];
$Dosis=$_GET["Dosis"];
$Satisfecha=$_GET["Satisfecha"];
$Fecha=$_GET["Fecha"];
	
//**********************************************************************************************
//	INTRODUCCION DE MEDICAMENTO ENTREGADOS

		if($row=mysql_fetch_array($proceso->ValorDivisor($IdMedicina)) and $_SESSION["TipoFarmacia"]==1){
		   $Cantidad=$Cantidad/$row[0];
		}


$IdMedicinaRecetada=$proceso->IntroducirMedicinaPorReceta($IdReceta,$IdMedicina,$Cantidad,$Dosis,$Satisfecha,$Fecha);

//*******************************************************
//	ACTUALIZACION DE EXISTENCIAS
$IdArea=$_GET["IdArea"]; //Area de farmacia que se actualizara la existencia

	$proceso->ActualizarInventario($IdMedicina,$IdMedicinaRecetada,$Cantidad,$IdArea,$Fecha);


//*******************************************************
$IdPersonal=$_SESSION["IdPersonal"];
$RecetasIngresadas=$proceso->RecetasIngresadasConteo($IdPersonal);

//*******************************************************


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
				
		$CantidadBase=explode('.',$row["Cantidad"]);
		
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

// 

		$tabla=$tabla.'<tr class="FONDO"><td align="center"><a style="color:red;" onclick="">'.$CantidadIntro.'</a></td><td align="center">'.$row["Nombre"]."<br>".$row["Concentracion"].' - '.htmlentities($row["FormaFarmaceutica"].' - '.$row["Presentacion"]).'</td><td align="center"><a style="color:blue;" onclick="javascript:VentanaBusqueda4(\'ModificaDosis.php?IdMedicinaRecetada='.$row["IdMedicinaRecetada"].'\')">'.$row["Dosis"].'</a></td><td align="center">'.$check.'</td><td align="center"><input type="button" id="BorrarMedicamento" name="BorrarMedicamento" value="Eliminar Medicamento" onclick="javascript:EliminaMedicina('.$row["IdMedicinaRecetada"].')"></td>
		</tr>';
	}//while resp
$tabla=$tabla."</table>";

echo $tabla.'~'.$RecetasIngresadas;
/* FIN DESPLIEGUE DATOS */
break;

case 6:

/* CAMBIO DE ESTADO DE LA RECETA INTRODUCIDA */
$IdReceta=$_GET["IdReceta"];
$proceso->RecetaLista($IdReceta);

break;

case 7:
/* MOSTRAR RECETAS */
$IdMedicinaRecetada=$_GET["IdMedicinaRecetada"];
$IdReceta=$_GET["IdReceta"];
$IdHistorialClinico=$_GET["IdHistorialClinico"];
	$IdPersonal=$_GET["IdPersonal"];

$IdArea=$_GET["IdArea"];


//***	ELIMINACION DE MEDICAMENTO		***/

$proceso->AumentarInventario($IdMedicinaRecetada,$IdArea);

//***	Eliminaicion del registro de la receta introducidad	******/

$proceso->EliminarMedicinaRecetada($IdMedicinaRecetada);

$RecetasIngresadas=$proceso->RecetasIngresadasConteo($IdPersonal);

//*************************************************/

$resp=$proceso->ObtenerMedicinaIntroducida($IdReceta);
if($row=mysql_fetch_array($resp)){

$tabla='<table width="744">
		<tr><td colspan="5" align="center"><strong>RECETA DEL DIA</strong></td></tr>
		<tr class="FONDO"><td width="150" align="center"><strong>Cantidad</strong></td>
		<td width="303" align="center"><strong>Medicina</strong></td>
		<td width="275" align="center"><strong>Dosis</strong></td>
		<td width="275" align="center"><strong>Insatisfecha</strong></td>
		<td width="275" align="center"><strong>Eliminar</strong></td>
		</tr>';
	do{
	
	if($row["IdEstado"]=='I'){
		$check='<input id="Insa'.$row["IdMedicinaRecetada"].'" name="Insa'.$row["IdMedicinaRecetada"].'" type="checkbox" value="I" onclick="javascript:CambioEstado('.$row["IdMedicinaRecetada"].','.$row["IdMedicina"].')" checked="checked">';
	}else{
		$check='<input id="Insa'.$row["IdMedicinaRecetada"].'" name="Insa'.$row["IdMedicinaRecetada"].'" type="checkbox" value="I" onclick="javascript:CambioEstado('.$row["IdMedicinaRecetada"].','.$row["IdMedicina"].')">';
	}

	if($respDivisor=mysql_fetch_array($proceso->ValorDivisor($row["IdMedicina"])) and $_SESSION["TipoFarmacia"]){
		$Divisor=$respDivisor[0];

		if($row["Cantidad"] < 1){
			//Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
		   $TransformaEntero=number_format($row["Cantidad"]*$Divisor,0,'.',',');
			$CantidadTransformada=$TransformaEntero.'/'.$Divisor;
		}else{
			//Si la cantidad es mayor a un frasco se realiza este cambio a quebrados
				
		$CantidadBase=explode('.',$row["Cantidad"]);
		
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
	
		$tabla=$tabla.'<tr class="FONDO"><td align="center"><a style="color:red;" onclick="">'.$CantidadIntro.'</a></td><td align="center">'.$row["Nombre"].', '.$row["Concentracion"].' - '.htmlentities($row["FormaFarmaceutica"].' - '.$row["Presentacion"]).'</td><td align="center"><a style="color:blue;" onclick="javascript:VentanaBusqueda4(\'ModificaDosis.php?IdMedicinaRecetada='.$row["IdMedicinaRecetada"].'\')">'.$row["Dosis"].'</a></td><td align="center">'.$check.'</td><td align="center"><input type="button" id="BorrarMedicamento" name="BorrarMedicamento" value="Eliminar Medicamento" onclick="javascript:EliminaMedicina('.$row["IdMedicinaRecetada"].')"></td></tr>';
	}while($row=mysql_fetch_array($resp));//while resp
$tabla=$tabla.'</table>';
}else{
$tabla=' ';
}

//REPETITIVAS
$tabla2="";
/*
$resp=$proceso->ObtenerRecetaRepetitiva($IdHistorialClinico,$IdPersonal);
if($tmp2=mysql_fetch_array($resp)){
$resp=$proceso->ObtenerRecetaRepetitiva($IdHistorialClinico,$IdPersonal);
$resp2=$proceso->ObtenerRecetaRepetitiva($IdHistorialClinico,$IdPersonal);
$tabla2='<table width="744">
		<tr><td colspan="5" align="center"><strong>RECETAS REPETITIVAS</strong></td></tr>
		<tr class="FONDO"><td width="150" align="center"><strong>Cantidad</strong></td>
		<td width="303" align="center"><strong>Medicina</strong></td>
		<td width="275" align="center"><strong>Dosis</strong></td>
		<td width="275" align="center"><strong>Fecha</strong></td>
		<td width="275" align="center"><strong>Eliminar</strong></td>
		</tr>';
		$row2=mysql_fetch_array($resp2);	
	while($row=mysql_fetch_array($resp)){
		$tabla2=$tabla2.'<tr class="FONDO"><td align="center"><a style="color:red;" onclick="">'.$row["Cantidad"].'</a></td><td align="center">'.$row["Nombre"]."<br>".$row["Concentracion"].' - '.$row["FormaFarmaceutica"].' - '.$row["Presentacion"].'</td><td align="center"><a style="color:blue;" onclick="javascript:VentanaBusqueda4(\'ModificaDosis.php?IdMedicinaRecetada='.$row["IdMedicinaRecetada"].'\')">'.$row["Dosis"].'</a></td><td align="center">'.$row["Fecha"].'</td><td align="center"><input type="button" id="BorrarMedicamento" name="BorrarMedicamento" value="Eliminar Medicamento" onclick="javascript:EliminaMedicina('.$row["IdMedicinaRecetada"].')"></td></tr>';
		$row2=mysql_fetch_array($resp2);	
			if($row2["Fecha"]!=$row["Fecha"]){$tabla2=$tabla2.'<tr><td colspan="4"><hr></td>
			</tr>';}	
	}//while resp
$tabla2=$tabla2.'</table>';
}else{
$tabla2=' ';
}*/
echo $tabla."<br>".$tabla2.'<br>'.$RecetasIngresadas;

break;

case 8:
/* CAMBIO DE DOSIS */
$IdMedicinaRecetada=$_GET["IdMedicinaRecetada"];
$IdReceta=$_GET["IdReceta"];
$IdHistorialClinico=$_GET["IdHistorialClinico"];
$Dosis=$_GET["NuevaDosis"];

$proceso->UpdateDosis($IdMedicinaRecetada,$Dosis);

$resp=$proceso->ObtenerMedicinaIntroducida($IdReceta);
if($tmp1=mysql_fetch_array($resp)){
$resp=$proceso->ObtenerMedicinaIntroducida($IdReceta);
$tabla='<table width="744">
		<tr><td colspan="5" align="center"><strong>RECETA DEL DIA</strong></td></tr>
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
				
		$CantidadBase=explode('.',$row["Cantidad"]);
		
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

		$tabla=$tabla.'<tr class="FONDO"><td align="center"><a style="color:red;" onclick="">'.$CantidadIntro.'</a></td><td align="center">'.$row["Nombre"]."<br>".$row["Concentracion"].' - '.$row["FormaFarmaceutica"].' - '.$row["Presentacion"].'</td><td align="center"><a style="color:blue;" onclick="javascript:VentanaBusqueda4(\'ModificaDosis.php?IdMedicinaRecetada='.$row["IdMedicinaRecetada"].'\')">'.$row["Dosis"].'</a></td><td align="center">'.$check.'</td><td align="center"><input type="button" id="BorrarMedicamento" name="BorrarMedicamento" value="Eliminar Medicamento" onclick="javascript:EliminaMedicina('.$row["IdMedicinaRecetada"].')"></td></tr>';
	}//while resp
$tabla=$tabla.'</table>';
}else{
$tabla=' ';
}

//REPETITIVAS
$tabla2="";
/*
$resp=$proceso->ObtenerRecetaRepetitiva($IdHistorialClinico,$IdPersonal);
if($tmp2=mysql_fetch_array($resp)){
$resp=$proceso->ObtenerRecetaRepetitiva($IdHistorialClinico,$IdPersonal);
$resp2=$proceso->ObtenerRecetaRepetitiva($IdHistorialClinico,$IdPersonal);
$tabla2='<table width="744">
		<tr><td colspan="5" align="center"><strong>RECETAS REPETITIVAS</strong></td></tr>
		<tr class="FONDO"><td width="150" align="center"><strong>Cantidad</strong></td>
		<td width="303" align="center"><strong>Medicina</strong></td>
		<td width="275" align="center"><strong>Dosis</strong></td>
		<td width="275" align="center"><strong>Fecha</strong></td>
		<td width="275" align="center"><strong>Eliminar</strong></td>
		</tr>';
		$row2=mysql_fetch_array($resp2);	
	while($row=mysql_fetch_array($resp)){
		$tabla2=$tabla2.'<tr class="FONDO"><td align="center"><a style="color:red;" onclick="">'.$row["Cantidad"].'</a></td><td align="center">'.$row["Nombre"].', '.$row["Concentracion"].'</td><td align="center"><a style="color:blue;" onclick="javascript:VentanaBusqueda4(\'ModificaDosis.php?IdMedicinaRecetada='.$row["IdMedicinaRecetada"].'\')">'.$row["Dosis"].'</a></td><td align="center">'.$row["Fecha"].'</td><td align="center"><input type="button" id="BorrarMedicamento" name="BorrarMedicamento" value="Eliminar Medicamento" onclick="javascript:EliminaMedicina('.$row["IdMedicinaRecetada"].')"></td></tr>';
		$row2=mysql_fetch_array($resp2);	
			if($row2["Fecha"]!=$row["Fecha"]){$tabla2=$tabla2.'<tr><td colspan="4"><hr></td>
			</tr>';}	
	}//while resp
$tabla2=$tabla2.'</table>';
}else{
$tabla2=' ';
}*/
echo $tabla."<br>".$tabla2;
break;

case 9:
//*********	CAMBIO DE CANTIDAD DE MEDICAMENTO INGRESADO	*****************
$IdMedicinaRecetada=$_GET["IdMedicinaRecetada"];
$IdReceta=$_GET["IdReceta"];
$IdHistorialClinico=$_GET["IdHistorialClinico"];
$Cantidad=$_GET["Cantidad"];

//**************	Actualizacion de existencias	****************************
	$IdArea=$_GET["IdArea"];
	$proceso->ActualizacionInventarioCantidad($IdMedicinaRecetada,$Cantidad,$IdArea);


//**************	Actualizacion de la cantidad introducidad	**********************
$proceso->UpdateCantidad($IdMedicinaRecetada,$Cantidad);

//**********************************************************************************
$resp=$proceso->ObtenerMedicinaIntroducida($IdReceta);
if($row=mysql_fetch_array($resp)){

$tabla='<table width="744">
		<tr><td colspan="5" align="center"><strong>RECETA DEL DIA</strong></td></tr>
		<tr class="FONDO"><td width="150" align="center"><strong>Cantidad</strong></td>
		<td width="303" align="center"><strong>Medicina</strong></td>
		<td width="275" align="center"><strong>Dosis</strong></td>
		<td width="275" align="center"><strong>Insatisfecha</strong></td>
		<td width="275" align="center"><strong>Eliminar</strong></td>
		</tr>';
	do{
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
				
		$CantidadBase=explode('.',$row["Cantidad"]);
		
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

		$tabla=$tabla.'<tr class="FONDO"><td align="center"><a style="color:red;" onclick="">'.$CantidadIntro.'</a></td><td align="center">'.$row["Nombre"].', '.$row["Concentracion"].'</td><td align="center"><a style="color:blue;" onclick="javascript:VentanaBusqueda4(\'ModificaDosis.php?IdMedicinaRecetada='.$row["IdMedicinaRecetada"].'\')">'.$row["Dosis"].'</a></td><td align="center">'.$check.'</td><td align="center"><input type="button" id="BorrarMedicamento" name="BorrarMedicamento" value="Eliminar Medicamento" onclick="javascript:EliminaMedicina('.$row["IdMedicinaRecetada"].')"></td></tr>';
	}while($row=mysql_fetch_array($resp));//while resp
$tabla=$tabla.'</table>';
}else{
$tabla=' ';
}

//REPETITIVAS
$tabla2="";
/*
$resp=$proceso->ObtenerRecetaRepetitiva($IdHistorialClinico,$IdPersonal);
if($tmp2=mysql_fetch_array($resp)){
$resp=$proceso->ObtenerRecetaRepetitiva($IdHistorialClinico,$IdPersonal);
$resp2=$proceso->ObtenerRecetaRepetitiva($IdHistorialClinico,$IdPersonal);
$tabla2='<table width="744">
		<tr><td colspan="5" align="center"><strong>RECETAS REPETITIVAS</strong></td></tr>
		<tr class="FONDO"><td width="150" align="center"><strong>Cantidad</strong></td>
		<td width="303" align="center"><strong>Medicina</strong></td>
		<td width="275" align="center"><strong>Dosis</strong></td>
		<td width="275" align="center"><strong>Fecha</strong></td>
		<td width="275" align="center"><strong>Eliminar</strong></td>
		</tr>';
		$row2=mysql_fetch_array($resp2);	
	while($row=mysql_fetch_array($resp)){
		$tabla2=$tabla2.'<tr class="FONDO"><td align="center"><a style="color:red;" onclick="">'.$row["Cantidad"].'</a></td><td align="center">'.$row["Nombre"].', '.$row["Concentracion"].'</td><td align="center"><a style="color:blue;" onclick="javascript:VentanaBusqueda4(\'ModificaDosis.php?IdMedicinaRecetada='.$row["IdMedicinaRecetada"].'\')">'.$row["Dosis"].'</a></td><td align="center">'.$row["Fecha"].'</td><td align="center"><input type="button" id="BorrarMedicamento" name="BorrarMedicamento" value="Eliminar Medicamento" onclick="javascript:EliminaMedicina('.$row["IdMedicinaRecetada"].')"></td></tr>';
		$row2=mysql_fetch_array($resp2);	
			if($row2["Fecha"]!=$row["Fecha"]){$tabla2=$tabla2.'<tr><td colspan="4"><hr></td>
			</tr>';}	
	}//while resp
$tabla2=$tabla2.'</table>';
}else{
$tabla2=' ';
}*/
echo $tabla."<br>".$tabla2;

break;

case 10:
$Estado=$_GET["Estado"];
$IdMedicinaRecetada=$_GET["IdMedicinaRecetada"];
$IdMedicina=$_GET["IdMedicina"];
$proceso->UpdateMedicinaRecetada($IdMedicinaRecetada,$Estado,$IdMedicina);

break;

case 11:
	$IdMedico=$_GET["IdMedico"];
	$Codigo=$proceso->ObtenerCodigoFarmacia($IdMedico);
	echo $Codigo;
break;
case 12:
	$CodigoFarmacia=$_GET["CodigoFarmacia"];
	$respuesta="/Medico no seleccionado";
	if($CodigoFarmacia!=''){
	$resp=$proceso->ObtenerDatosMedico($CodigoFarmacia);
	$respuesta=$resp[0].'/'.htmlentities($resp[1]);
	}
	echo $respuesta;
break;

case 13:
	/*MOSTRAR SUBESPECIALIDADES O SERVICIO ORIGEN DE RECETA*/
		$Codigo=strtoupper($_GET["Codigo"]);
		$query="select IdSubServicio,NombreSubServicio, IdServicio  as Ubicacion
			from mnt_subservicio
			where CodigoFarmacia='$Codigo'";

		$resp=mysql_fetch_array(mysql_query($query));
		if($resp["Ubicacion"]!=NULL and $resp["Ubicacion"]!=""){$Ubicacion=$resp["Ubicacion"]." -> ";}else{$Ubicacion="";}
			$NombreSubServicio=$Ubicacion."".$resp["NombreSubServicio"];
		
		echo $resp["IdSubServicio"]."/".$NombreSubServicio;
break;

case 14:
	/*	ACTUALIZACIONES */
	$salida='S';
	if(isset($_GET["IdArea"])){
		$IdArea=$_GET["IdArea"];
		$IdFarmacia=$_GET["IdFarmacia"];
		$IdHistorialClinico=$_GET["IdHistorialClinico"];
		$IdReceta=$_GET["IdReceta"];
		
		if($IdReceta=='' or $IdReceta==NULL){
			$salida='N';
		}else{
			$proceso->ActualizarArea($IdArea,$IdReceta,$IdFarmacia);
		}
		
	}//Actualizacion de Area
	
	if(isset($_GET["IdMedico"])){
		
		$IdHistorialClinico=$_GET["IdHistorialClinico"];
		$IdMedico=$_GET["IdMedico"];
		
		if($IdHistorialClinico=='' or $IdHistorialClinico==NULL){
			$salida='N';
		}else{
			$proceso->ActualizarMedico($IdHistorialClinico,$IdMedico);
		}
	}//Actualizacion de Medico
	
	if(isset($_GET["IdSubEspecialidad"])){
		$IdHistorialClinico=$_GET["IdHistorialClinico"];
		$IdSubServicio=$_GET["IdSubServicio"];
		
		if($IdHistorialClinico=='' or $IdHistorialClinico==NULL){
			$salida='N';
		}else{
			$proceso->ActualizarSubServicio($IdHistorialClinico,$IdSubServicio);
		}
	}//Actualizacoin de Especialidad
	
	echo $salida;
break;

case 15:
	$IdReceta=$_GET["IdReceta"];
	$resp=$proceso->VerificaRecetas($IdReceta);
	if($resp==NULL or $resp==''){
		echo 'NO';
	}else{
		echo 'SI';
	}
	
break;

case 16:
	$IdMedicina=$_GET["IdMedicina"];
	$IdArea=$_GET["IdArea"];
	echo $proceso->ObtenerExistencia($IdMedicina,$_SESSION["TipoFarmacia"]);

break;

case 17:
	//Listado de Areas
	$IdArea=$_GET["IdArea"];
	echo $proceso->AreaOrigen($IdArea);

break;
case 18:
	$salida='S';
	$IdArea=$_GET["IdAreaOrigen"];
	$IdReceta=$_GET["IdReceta"];
	
	if($IdReceta=='' or $IdReceta==NULL){
		$salida='N';
	}else{
		$proceso->ActualizarAreaOrigen($IdArea,$IdReceta);
	}

 	echo $salida;
break;

default:

break;

}//Fin de switch
conexion::desconectar();
}
?>