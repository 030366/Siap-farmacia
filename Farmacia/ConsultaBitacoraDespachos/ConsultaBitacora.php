<?php session_start();

if(!isset($_SESSION["nivel"])){
  echo "ERROR_SESSION";
}else{

include('../Clases/class.php');
include('IncludeFiles/ClaseBitacora.php');
conexion::conectar();
$puntero=new Bitacora;

switch($_GET["Bandera"]){
   case 1:
   //REPORTE DE BITACORA
	$IdFarmacia=$_GET["IdFarmacia"];
	$IdTeraputico=$_GET["IdTerapeutico"];
        $IdMedicina=$_GET["IdMedicina"];
	$FechaInicio=$_GET["fechaInicio"];
	$FechaFin=$_GET["fechaFin"];

	    $Fechas1=date_create($FechaInicio);
	    $Fechas2=date_create($FechaFin);
		$Fecha1=date_format($Fechas1,'d-m-Y');
		$Fecha2=date_format($Fechas2,'d-m-Y');
		
	
	//     GENERACION DE EXCEL
		$NombreExcel="Bitacora_existenciaxareas_".$_SESSION["nick"].'_'.date('d_m_Y__h_i_s A');
		$nombrearchivo = "../ReportesExcel/".$NombreExcel.".xls";
		$punteroarchivo = fopen($nombrearchivo, "w+") or die("El archivo de reporte no pudo crearse");
	
	//LIBREOFFICE
		$nombrearchivo2 = "../ReportesExcel/".$NombreExcel.".ods";
		$punteroarchivo2 = fopen($nombrearchivo2, "w+") or die("El archivo de reporte no pudo crearse");
	
	//***********************
	
	
	
	$reporte='<table width="100%" border="1">
	<tr class="MYTABLE">
	<td colspan="9" align="center"><strong>'.$_SESSION["NombreEstablecimiento"].'</strong><br>
	<strong>BITACORA: DESPACHO DE EXISTENCIAS A FARMACIAS</strong></td></tr>';
		
	
		$reporte.='<tr class="MYTABLE">
	<td colspan="9" align="center" style="vertical-align:middle;"><strong>Periodo: '.$Fecha1.' al '.$Fecha2.'</strong></td></tr>';
	
	
	//<!--  INICIO DE REPORTE  -->
		
	$resp=$puntero->ExisteBitacora($FechaInicio,$FechaFin);
	if($row=mysql_fetch_array($resp)){

		$respGrupo=$puntero->ObtenerGrupos($IdTeraputico,$FechaInicio,$FechaFin,$IdFarmacia);
      if($rowGrupo=mysql_fetch_array($respGrupo)){
	do{

	   $reporte.="<tr class='FONDO'><td colspan=9 align=center><strong><h4>".$rowGrupo[1]."</h4></strong></td></tr>
		<tr class='FONDO'>
		<td align=center><strong>CODIGO</strong></td>
		<td align=center><strong>DESCRIPCION</strong></td>
		<td align=center><strong>UNIDAD DE MEDIDA</strong></td>
		<td align=center><strong>CANTIDAD INGRESADA</strong></td>
		<td align=center><strong>LOTE</strong></td>
		<td align=center><strong>AREA DE ENTREGA</strong></td>
		<td align=center><strong>FECHA DE INGRESO</strong></td>
		<td align=center><strong>HORA DE INGRESO</strong></td>
		<td align=center><strong>ESTADO DE REGISTRO</strong></td>
		</tr>";

	   $resp=$puntero->ObtenerBitacora($IdMedicina,$rowGrupo[0],$FechaInicio,$FechaFin,$IdFarmacia);
	   while($row=mysql_fetch_array($resp)){
		$Medicina=$row["IdMedicina"];
		$Codigo="'".$row["Codigo"]."'";
		$Descripcion=htmlentities($row["Nombre"])." ".htmlentities($row["Concentracion"])."<br>".htmlentities($row["FormaFarmaceutica"])."<br>".htmlentities($row["Presentacion"]);
		$CantidadIngresada=$row["Existencia"];
		$UnidadMedida=$row["Descripcion"];
		$Lote=$row["Lote"];
		$Area=$row["Area"];
		$FechaIngreso=$row["FechaIngreso"];
		$HoraIngreso=$row["HoraIngreso"];
		$Divisor=$row["UnidadesContenidas"];
		$IdExistenciaOrigen=$row["IdExistenciaOrigen"];
		$IdTransferencia=$row["IdTransferencia"];
			$EstadoRegistro="Ok...";
			if($IdExistenciaOrigen==NULL){$EstadoRegistro="Depurado...";}
				if($IdTransferencia!=0){$EstadoRegistro="Transferencia Interna <br> de Farmacias";}

	   $CantidadReal=$CantidadIngresada;
	if($respDivisor=mysql_fetch_array($puntero->ValorDivisor($Medicina))){
		$Divisor=$respDivisor[0];

                $CantidadReal=number_format($CantidadReal,3,'.','');
                
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
	   $CantidadIntro=$CantidadReal/$Divisor;
		$CantidadIntro=$CantidadIntro;
	}


	     $reporte.="<tr class='FONDO'>
			<td align=center>".$Codigo."</td>
			<td>".$Descripcion."</td>
			<td align=center>".$UnidadMedida."</td>
			<td align=center>".$CantidadIntro."</td>
			<td align=center>".$Lote."</td>
			<td align=center><strong>".$Area."</strong></td>
			<td align=center>".$FechaIngreso."</td>
			<td align=center>".$HoraIngreso."</td>
			<td align=center>".$EstadoRegistro."</td>
			</tr>";
		
	   }

	}while($rowGrupo=mysql_fetch_array($respGrupo));//while de GrupoTerapeutico
      }else{$reporte.="<tr class='FONDO'><td colspan=9 align=center><strong>NO EXISTEN MOVIMIENTOS EN LA BITACORA PARA ESTE GRUPO TERAPEUTICO PARA EL PERIODO SELECCIONADO</strong></td></tr>";}

	}else{
	  $reporte.="<tr class='FONDO'><td colspan=9 align=center><strong>AUN NO EXISTEN MOVIMIENTOS ALMACENADOS EN LA BITACORA PARA ESTE PERIODO SELECCIONADO</strong></td></tr>";
	
	}
	$reporte.='</table>';
	
	//CIERRE DE ARCHIVO EXCEL
		fwrite($punteroarchivo,$reporte);
		fclose($punteroarchivo);
	//CIERRE ODS
		fwrite($punteroarchivo2,$reporte);
		fclose($punteroarchivo2);
	
	//***********************
	
	
	echo '<table width=100%>
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
   case 2:
       $IdTerapeutico=$_GET["IdTerapeutico"];
       
       $resp=$puntero->Medicinas($IdTerapeutico);
       
       $combo="<select id='IdMedicina' name='IdMedicina' >
               <option value='0'>[GENERAL...]</option>";
       
       while($row=mysql_fetch_array($resp)){
           $combo.="<option value='".$row["IdMedicina"]."'>".htmlentities($row["Nombre"])." - ".$row["Concentracion"]."-".htmlentities($row["FormaFarmaceutica"])."</option>";
       }
       
       $combo.="</select>";
       
       echo $combo;
       break;
}

conexion::desconectar();
}//ERROR DE SESION



?>