<?php session_start();
if(!isset($_SESSION["nivel"])){?>
<script language="javascript">
window.location='../../signIn.php';
</script>
<?php
}else{
if($_SESSION["nivel"]!=1 and $_SESSION["nivel"]!=2 and $_SESSION["nivel"]!=4){?>
<script language="javascript">
window.location='../../index.php?Permiso=1';
</script>
<?php
}else{
$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
require('../../Clases/class.php');
include('Funciones.php');
$query=new queries;
conexion::conectar();
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../../default.css" media="screen" />
<title>...:::Consumo por Especialidades Con. Ext.:::...</title>
<style type="text/css">
#Layer11 {
	position:absolute;
	left:9px;
	top:255px;
	width:826px;
	height:192px;
	z-index:1;
}
.style1 {color:#0000CC; font-size:11px; font-family:Arial, Helvetica, sans-serif}
#Layer61 {position:absolute;
	left:25px;
	top:105px;
	width:955px;
	height:30px;
	z-index:2;
}
#Layer21 {
	position:absolute;
	left:865px;
	top:44px;
	width:52px;
	height:20px;
	z-index:6;
}
#Layer31 {
	position:absolute;
	left:688px;
	top:2px;
	width:245px;
	height:24px;
	z-index:7;
}
@media print {
* { background: #fff; color: #000; }
html { font: 9pt Arial, Helvetica, sans-serif; }
#Layer61, #Layer21, #Layer31 { display: none; }
#nav, #nav2, #about { display: none; }
#footer { display:none;}
#span{ color:#FFFFFF}
@page { size: 8.5in 11in; margin: 0.5cm }
P{page-break-after:inherit}
}
#Layer3 {position:absolute;
	left:2px;
	top:190px;
	width:1001px;
	height:30px;
	z-index:6;
}
.style4 {font-size: 24px}
#Layer41 {position:absolute;
	left:-199px;
	top:-39px;
	width:55px;
	height:31px;
	z-index:7;
}
#Layer71 {position:absolute;
	left:303px;
	top:39px;
	width:596px;
	height:23px;
	z-index:5;
}
</style>
<script language="javascript">
function popUp(URL) {
day = new Date();
id = day.getTime();
//id=document.formulario.fecha.value;
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=1025,height=500,left = 20,top = 100');");
}//popUp
</script> 
</head>
<body>
<script language="javascript" src="../../tooltip/wz_tooltip.js"></script>

<div class="style1" id="Layer6" align="center">
  <?php
  if(!isset($_SESSION["ADM"])){
  		encabezado::top($_SESSION["IdFarmacia2"],$tipoUsuario,$nick,$nombre);
	}
?>
</div>
<div id="Layer3" align="center">
  <?php  include('../Menu.php');?>
</div>
<div id="Layer71">
  <div id="Layer41"><img src="../../images/paisanito.jpg" alt="" width="195" height="94" /></div>
  <span class="style4">Ministerio de Salud P&uacute;blica y Asistencia Social </span></div>
<?php 
//html { font: 100%/2.5 Arial, Helvetica, sans-serif; }
$FechaInicio=explode('-',$_REQUEST["fechaInicio"]);
$FechaFin=explode('-',$_REQUEST["fechaFin"]);
$FechaInicio2=$FechaInicio[2].'-'.$FechaInicio[1].'-'.$FechaInicio[0];
$FechaFin2=$FechaFin[2].'-'.$FechaFin[1].'-'.$FechaFin[0];
$FechaInicio=$_REQUEST["fechaInicio"];
$FechaFin=$_REQUEST["fechaFin"];
/**********************INFORMACION DE REPORTE**********************************/
if(isset($_POST["IdSubEspecialidad"])){$IdSubEspecialidad=$_POST["IdSubEspecialidad"];}else{$IdSubEspecialidad=0;}
if(isset($_POST["select1"])){$IdGrupoTerapeutico=$_POST["select1"];}else{$IdGrupoTerapeutico=0;}
if(isset($_POST["select2"])){$IdMedicina=$_POST["select2"];}else{$IdMedicina=0;}
/******************************************************************************/
?>
<div id="Layer11">
<?php 
//     GENERACION DE EXCEL
	$NombreExcel='Especialidades_'.$nick.'_'.date('d_m_Y__h_i_s A');
	$nombrearchivo = "../ReportesExcel/".$NombreExcel.".xls";
	$punteroarchivo = fopen($nombrearchivo, "w+") or die("El archivo de reporte no pudo crearse");
//***********************



/*  $reporte='<table width="967">
      <tr class="MYTABLE">
      <td colspan="11" align="center">HOSPITAL NACIONAL ROSALES<br>
        <strong>CONSUMO DE MEDICAMENTOS POR ESPECIALIDAD </strong> <br>
PERIODO DEL: '.$FechaInicio2.' AL '.$FechaFin2.' .- </td></tr>

<tr class="MYTABLE"><td align="right" colspan="11">Fecha de Emisi&oacute;n: '.$DateNow=date("d-m-Y").'</td>
    </tr>
  </table>';*/
?>
<form action="" method="post" name="formulario">
<input id="fechaInicio" name="fechaInicio" type="hidden" value="<?php echo $FechaInicio;?>">
<input id="fechaFin" name="fechaFin" type="hidden" value="<?php echo $FechaFin;?>">
<input id="IdSubEspecialidad" name="IdSubEspecialidad" type="hidden" value="<?php echo $IdSubEspecialidad;?>">
<input id="select1" name="select1" type="hidden" value="<?php echo $IdGrupoTerapeutico;?>">
<input id="select2" name="select2" type="hidden" value="<?php echo $IdMedicina;?>">

<div id="Layer31" align="right">
<table>
<tr><td>&nbsp;</td><td>
<input type="hidden" name="imprimir" value="Vista Previa" onClick="javascript:popUp('impresion.php?select1='+ document.formulario.select1.value +'&select2='+ document.formulario.select2.value +'&fechaInicio='+ document.formulario.fechaInicio.value +'&fechaFin='+ document.formulario.fechaFin.value+'&IdSubEspecialidad='+document.formulario.IdSubEspecialidad.value);" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099; visibility:hidden;">
</td></tr>
<!-- <tr><td><input type="text" id="NombreArchivo" name="NombreArchivo" maxlength="50" size="15" value="NombreArchivo" onFocus="if(this.value=='NombreArchivo'){this.value=''}" onBlur="if(this.value==''){this.value='NombreArchivo'}"></td>
<td>
<input type="button" name="Exportar" value="Exportar Excel" onClick="javascript:popUp('ReporteExcelServicios.php?select1='+ document.formulario.select1.value +'&select2='+ document.formulario.select2.value +'&fechaInicio='+ document.formulario.fechaInicio.value +'&fechaFin='+ document.formulario.fechaFin.value+'&IdSubEspecialidad='+document.formulario.IdSubEspecialidad.value+'&nombreArchivo='+document.formulario.NombreArchivo.value);"  onMouseOver="this.style.color='#CC0000';Tip('Exportar a Excel<br><img src=\'../../images/excel.GIF\'>',TEXTALIGN,'center')" onMouseOut="this.style.color='#000000'; UnTip()" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099">
</td></tr> -->
<tr><td>&nbsp;</td><td>
<input type="button" name="Regresar" value="Regresar" onClick="javascript:window.location='Rep_Servicios.php'" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099" title="Regresar">
</td></tr>
</table>
</div>
</form>

<?php
$reporte='<table width="968" border="1">';
		$Total=0;

//*************************************
//******************************* QUERIES Y RECORRIDOS
$respServicios=Servicios($IdSubEspecialidad,$FechaInicio,$FechaFin);
while($rowServicios=mysql_fetch_array($respServicios)){
	$TotalEspecialidad=0;
			        $TotalRecetas=0;
					$TotalSatis=0;
					$TotalInsat=0;
					$TotalConsumo=0;

	$IdSubEspecialidad=$rowServicios[0];
	$NombreSubEspecialidad=$rowServicios[1];
		
		$reporte.='<tr class="MYTABLE">
      <td colspan="11" align="center">HOSPITAL NACIONAL ROSALES<br>
        <strong>CONSUMO DE MEDICAMENTOS POR ESPECIALIDAD </strong> <br>
PERIODO DEL: '.$FechaInicio2.' AL '.$FechaFin2.' .- </td></tr>

<tr class="MYTABLE"><td align="right" colspan="11">Fecha de Emisi&oacute;n: '.$DateNow=date("d-m-Y").'</td>
    </tr>';
		
			
		$reporte.='<tr class="FONDO2">
			  <td colspan="11" align="center" style="background:#666666;">
		<strong><h2>'.$NombreSubEspecialidad.'</h2></strong></td>
			</tr>';	

	 
	$nombreTera=NombreTera($IdGrupoTerapeutico,$IdSubEspecialidad,$FechaInicio,$FechaFin);
	if($grupos=mysql_fetch_array($nombreTera)){
		do {$NombreTerapeutico=$grupos["GrupoTerapeutico"];
		$IdTerapeutico=$grupos["IdTerapeutico"];
			$SubTotal=0;
			        $SubTotalRecetas=0;
					$SubTotalSatis=0;
					$SubTotalInsat=0;
					$SubTotalConsu=0;

			$reporte.='<tr class="FONDO2" style="background:#CCCCCC;">
			  <td colspan="11" align="center">
		&nbsp;<strong>'.$NombreTerapeutico.'</strong></td>
			</tr>
				<tr class="FONDO2">
			<th width="37" scope="col">Codigo</th>
			  <th width="182" scope="col">Medicamento</th>
			  <th width="61" scope="col">Concen.</th>
			  <th width="54" scope="col">Prese.</th>
			  <th width="54" scope="col">Recetas</th>
			  <th width="50" scope="col">Satis.</th>
			  <th width="70" scope="col">No Satis.</th>
			  <th width="63">Unidad de Medida</th>
			  <th width="78" scope="col">Consumo</th>
			  <th width="135" scope="col">Precio[$]</th>
			  <th width="136" scope="col">Monto[$]</th>
			</tr>';
			
		$resp1=QueryExterna($IdTerapeutico,$IdMedicina,$IdSubEspecialidad,$FechaInicio,$FechaFin);
			while($row=mysql_fetch_array($resp1)){
		$GrupoTerapeutico=$IdTerapeutico;
		$Medicina=$row["IdMedicina"];
		$codigoMedicina=$row["Codigo"];
		$NombreMedicina=$row["Nombre"];
		$concentracion=$row["Concentracion"];
		$presentacion=$row["FormaFarmaceutica"];
		
		$Nrecetas=0;//conteo de recetas
		$consumo=0;
		
		
		$respuesta=ObtenerReporteGrupoTerapeutico($IdTerapeutico,$Medicina,$FechaInicio,$FechaFin,$IdSubEspecialidad);
			
		    if($row2=mysql_fetch_array($respuesta)){ /* verificacion de datos */
		        $precioActual=0;
		        

		        
		        $Divisor=$row2["Divisor"];//Divisor de conversion
		        $UnidadMedida=$row2["Descripcion"];//Tipo de unidad de Medida
		        $satisfechas=0;
		        $insatisfechas=0;
		        
				        
		        /*Obtencion de recetas satifechas e insatisfechas globales parametros ...,0,0)*/
		        $sat=ObtenerRecetasSatisfechas($Medicina,$FechaInicio,$FechaFin,$IdSubEspecialidad,0,0);
		        $insat=ObtenerRecetasInsatisfechas($Medicina,$FechaInicio,$FechaFin,$IdSubEspecialidad,0,0);
		        	$Nrecetas=$sat+$insat;
		        //***********
	            $Cantidad_Total=SumatoriaMedicamento($Medicina,$IdSubEspecialidad,$FechaInicio,$FechaFin);
		        $CantidadReal=$Cantidad_Total/$Divisor;
	            $Ano=date('Y');
	            $Precio=ObtenerPrecioMedicina($Medicina,$Ano);
	            $Monto=$CantidadReal*$Precio;
		        $PrecioNuevo=number_format($Precio,2,'.',',');
		        $MontoNuevo=number_format($Monto,2,'.',',');
			        $SubTotal+=$Monto;
			        $SubTotalRecetas+=$Nrecetas;
					$SubTotalSatis+=$sat;
					$SubTotalInsat+=$insat;
					$SubTotalConsu+=$CantidadReal;
		        
			       $reporte.='<tr class="FONDO2">
			          <td style="vertical-align:middle">&nbsp;'.$codigoMedicina.'</td>
			          <td align="left" style="vertical-align:middle">'.$NombreMedicina.'</td>
			          <td align="center" style="vertical-align:middle">'.$concentracion.'</td>
			          <td align="center" style="vertical-align:middle">'.$presentacion.'</td>
			          <td align="right" style="vertical-align:middle">'.$Nrecetas.'</td>
			          <td align="right" style="vertical-align:middle">'.$sat.'</td>
			          <td align="right" style="vertical-align:middle">'.$insat.'</td>
			          <td align="center" style="vertical-align:middle">'.$UnidadMedida.'</td>
			          <td align="right" style="vertical-align:middle">'.number_format($CantidadReal,2,'.',',').'</td>
			          <td align="right" style="vertical-align:middle">'.$PrecioNuevo.'</td>
			          <td align="right" style="vertical-align:middle">'.$MontoNuevo.'</td>
			        </tr>';
		        	
				}//if row2
			}//while externo

				$TotalEspecialidad+=$SubTotal;
			        $TotalRecetas+=$SubTotalRecetas;
					$TotalSatis+=$SubTotalSatis;
					$TotalInsat+=$SubTotalInsat;
					$TotalConsumo+=$SubTotalConsu;
					
	     
    $reporte.='<tr class="FONDO2" style="background:#CCCCCC;">
      <td colspan="4" align="right"><strong><em>SubTotal:</em></strong></td>
      <td align="right">'.$SubTotalRecetas.'</td>
      <td align="right">'.$SubTotalSatis.'</td>
      <td align="right">'.$SubTotalInsat.'</td>
      <td>&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td align="right"><strong>'.number_format($SubTotal,2,'.',',').'</strong></td>
    </tr>';
		  
		
		
	}while($grupos=mysql_fetch_array($nombreTera));//Do-while de nombreTera
	}else{
	//SI NO HAY MEDICAMENTO DE ESA ESPECIALIDAD A MOSTRAR
				/*$reporte.='<tr class="FONDO2" style="background:#CCCCCC;">
			  <td colspan="11" align="center">
		&nbsp;<strong>No Hay consumo de Grupo Terapeutico</strong></td>
			</tr>
				<tr class="FONDO2">
			<th width="37" scope="col">Codigo</th>
			  <th width="182" scope="col">Medicamento</th>
			  <th width="61" scope="col">Concen.</th>
			  <th width="54" scope="col">Prese.</th>
			  <th width="54" scope="col">Recetas</th>
			  <th width="50" scope="col">Satis.</th>
			  <th width="70" scope="col">No Satis.</th>
			  <th width="63">Unidad de Medida</th>
			  <th width="78" scope="col">Consumo</th>
			  <th width="135" scope="col">Precio[$]</th>
			  <th width="136" scope="col">Monto[$]</th>
			</tr>
						<tr class="FONDO2">
			          <td style="vertical-align:middle">&nbsp;-----</td>
			          <td align="left" style="vertical-align:middle">-----</td>
			          <td align="center" style="vertical-align:middle">-----</td>
			          <td align="center" style="vertical-align:middle">-----</td>
			          <td align="center" style="vertical-align:middle">-----</td>
			          <td align="center" style="vertical-align:middle">-----</td>
			          <td align="center" style="vertical-align:middle">-----</td>
			          <td align="center" style="vertical-align:middle">-----</td>
			          <td align="right" style="vertical-align:middle">&nbsp;0.00</td>
			          <td align="right" style="vertical-align:middle">0.00</td>
			          <td align="right" style="vertical-align:middle">0.00</td>
			        </tr>';*/
	
	}
	
					$Total+=$TotalEspecialidad;
    $reporte.='<tr class="FONDO2" style="background:#CCCCCC;">
      <td colspan="4" align="right"><em><strong> Total de '.$NombreSubEspecialidad.':</strong></em></td>
	  <td align="right">'.$TotalRecetas.'</td>
	  <td align="right">'.$TotalSatis.'</td>
	  <td align="right">'.$TotalInsat.'</td>
	  <td>&nbsp;</td>
	  <td align="right">&nbsp;</td>
	  <td align="right">&nbsp;</td>
	  <td align="right"><strong>'.number_format(round($TotalEspecialidad,2),2,'.',',').'</strong></td>
    </tr>';
}//While Servicios
    $reporte.='<tr class="FONDO2" style="background:#CCCCCC;">
      <td colspan="9">&nbsp;</td>
	  <td align="right"><em><strong>Total:</strong></em></td>
	  <td align="right"><strong>'.number_format(round($Total,2),2,'.',',').'</strong></td>
    </tr>
</table>
';

//CIERRE DE ARCHIVO EXCEL
	fwrite($punteroarchivo,$reporte);
	fclose($punteroarchivo);
//***********************

?>

<table>
	<tr>
		<td align="center" style="vertical-align:middle;">
		<!--  HIPERVINCULO DE ARCHIVO EXCEL  -->
		<?php echo '<a href="'.$nombrearchivo.'"><H5>DESCARGAR REPORTE EXCEL <img src="../../images/excel.gif"></H5></a>';?>
		</td>
	</tr>
	<tr>
		<td>
		<!--  INFORMACION A MOSTRAR -->
		<?php echo $reporte;?>
		</td>
	</tr>
</table>

</div>
</body>
</html>
<?php
conexion::desconectar();
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel
?>