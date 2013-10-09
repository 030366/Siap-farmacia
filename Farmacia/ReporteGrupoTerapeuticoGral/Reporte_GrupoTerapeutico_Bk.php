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
<title>...:::Consumo por Grupo Terapeutico:::...</title>
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

<div class="style1" id="Layer61">
  <?php

echo"<strong>Nombre de Usuario:</strong>&nbsp;&nbsp; $nombre </br>
<strong>Tipo de Usuario:</strong>&nbsp;&nbsp;$tipoUsuario<br>
<strong>Nick:</strong>&nbsp;&nbsp;$nick<br>";
?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
<div id="Layer3" align="center">
  <?php if($nivel==1){?>
<script webstyle4>document.write('<scr'+'ipt src="../../xaramenu.js">'+'</scr'+'ipt>');document.write('<scr'+'ipt src="../../MenuImages/menu_.js">'+'</scr'+'ipt>');/*img src="MenuImages/Menu.gif" moduleid="Default (Project)\Menu_off.xws"*/</script>
   <?php }elseif($nivel==4){?>
    <script webstyle4>document.write('<scr'+'ipt src="../../xaramenu.js">'+'</scr'+'ipt>');document.write('<scr'+'ipt src="../../MenuImages/menudigitador.js">'+'</scr'+'ipt>');/*img src="MenuImages/MenuConsultaExterna.gif" moduleid="MenuConExt (Project)\MenuConsultaExterna_off.xws"*/</script>
  <?php }else{?>
 <script webstyle4>document.write('<scr'+'ipt src="../../xaramenu.js">'+'</scr'+'ipt>');document.write('<scr'+'ipt src="../../MenuImages/menucoadmin.js">'+'</scr'+'ipt>');/*img src="MenuImages/MenuCoAdmin.gif" moduleid="MenuCoAdmin (Project)\MenuCoAdmin_off.xws"*/</script>
  <?php }?>
</div>
<div id="Layer71">
  <div id="Layer41"><img src="../../images/paisanito.jpg" alt="" width="195" height="94" /></div>
  <span class="style4">Ministerio de Salud P&uacute;blica y Asistencia Social </span></div>
<?php 
//html { font: 100%/2.5 Arial, Helvetica, sans-serif; }
$IdFarmacia=$_REQUEST["farmacia"];

$FechaInicio=explode('-',$_REQUEST["fechaInicio"]);
$FechaFin=explode('-',$_REQUEST["fechaFin"]);
$FechaInicio2=$FechaInicio[2].'-'.$FechaInicio[1].'-'.$FechaInicio[0];
$FechaFin2=$FechaFin[2].'-'.$FechaFin[1].'-'.$FechaFin[0];
$FechaInicio=$_REQUEST["fechaInicio"];
$FechaFin=$_REQUEST["fechaFin"];

if($_REQUEST["area"]!=0){

		$IdArea=$_REQUEST["area"];
		$IdAreaTemp=$_REQUEST["area"];
		$resp=mysql_query("select Area from mnt_areafarmacia where IdArea='$IdArea'");
		$RowArea=mysql_fetch_array($resp);
		$area=$RowArea[0];

}else{

		$IdAreaTemp=0;
		$IdArea=0;
		$area="";
}


?>
<div id="Layer11">
<?php
//     GENERACION DE EXCEL
	$NombreExcel="GrupoTerapeutico_".$nick."_".$area.'_'.date('d_m_Y__h_i_s A');
	$nombrearchivo = "../ReportesExcel/".$NombreExcel.".xls";
	$punteroarchivo = fopen($nombrearchivo, "w+") or die("El archivo de reporte no pudo crearse");
//***********************
$reporte='';

if($IdAreaTemp!=0){
 $reporte.='<table width="967">
      <tr class="MYTABLE">
      <td colspan="11" align="center">HOSPITAL NACIONAL ROSALES<br>
        <strong>CONSUMO DE MEDICAMENTOS POR AREAS</strong> <br>
&Aacute;rea: <strong><h3>'.$area.'</h3></strong><br>
PERIODO DEL: '.$FechaInicio2.' AL '.$FechaFin2.'.-</td></tr>
<tr class="MYTABLE"><td align="right" colspan="11">Fecha de Emisi&oacute;n: '.$DateNow=date("d-m-Y").'</td>
    </tr>
  </table>';
 }
//*****FILTRACION DE MEDICINA Y GRUPOS  Y FECHAS
if(isset($_REQUEST["select1"])){$grupoTerapeutico=$_REQUEST["select1"];}else{$grupoTerapeutico=0;}
if(isset($_REQUEST["select2"])){$Idmedicina=$_REQUEST["select2"];}else{$Idmedicina=0;}

	echo $reporte;
?>
<form action="" method="post" name="formulario">
<input name="fechaInicio" type="hidden" value="<?php echo"$FechaInicio";?>">
<input name="fechaFin" type="hidden" value="<?php echo"$FechaFin";?>">
<input name="select1" type="hidden" value="<?php echo"$grupoTerapeutico";?>">
<input name="select2" type="hidden" value="<?php echo"$Idmedicina";?>">
<input name="area" type="hidden" value="<?php echo $IdArea;?>">
<input name="NomArea" type="hidden" value="<?php echo $area;?>">
<div id="Layer31" align="right">
<table>
<tr><td>&nbsp;</td><td>
<input type="button" name="imprimir" value="Vista Previa" onClick="javascript:popUp('impresion.php?select1='+ document.formulario.select1.value +'&select2='+ document.formulario.select2.value +'&fechaInicio='+ document.formulario.fechaInicio.value +'&fechaFin='+ document.formulario.fechaFin.value+'&area='+document.formulario.area.value+'&NomArea='+document.formulario.NomArea.value);" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099; visibility:visible;">
</td></tr>
<!-- <tr><td><input type="text" id="NombreArchivo" name="NombreArchivo" maxlength="80" size="15" value="NombreArchivo" onFocus="if(this.value=='NombreArchivo'){this.value=''}" onBlur="if(this.value==''){this.value='NombreArchivo'}"></td>
<td>
<input type="button" name="Exportar" value="Exportar Excel" onClick="javascript:popUp('ReporteExcelGrupos.php?select1='+ document.formulario.select1.value +'&select2='+ document.formulario.select2.value +'&fechaInicio='+ document.formulario.fechaInicio.value +'&fechaFin='+ document.formulario.fechaFin.value+'&area='+document.formulario.area.value+'&NomArea='+document.formulario.NomArea.value+'&nombreArchivo='+document.formulario.NombreArchivo.value);"  onMouseOver="this.style.color='#CC0000';Tip('Exportar a Excel<br><img src=\'../../images/excel.GIF\'>',TEXTALIGN,'center')" onMouseOut="this.style.color='#000000'; UnTip()" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099">
</td></tr> -->
<tr><td>&nbsp;</td><td>
<input type="button" name="Regresar" value="Regresar" onClick="javascript:window.location='Rep_GrupoTerapeutico.php'" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099" title="Regresar">
</td></tr>
</table>
</div>
</form>
<?php $reporte.='<table width="968" border="1">';
//OBTENCION DE AREAS DE LA FARMACIA
	$respAreas=ObtenerAreasFarmacia($IdFarmacia,$IdArea,$FechaInicio,$FechaFin);
	while($rowAreas=mysql_fetch_array($respAreas)){
		$IdArea=$rowAreas["IdArea"];
		$NombreDeArea=$rowAreas["Area"];
		
		if($IdAreaTemp==0){
			$reporte.='
			      <tr class="MYTABLE">
      <td colspan="11" align="center">HOSPITAL NACIONAL ROSALES<br>
        <strong>CONSUMO DE MEDICAMENTOS POR GRUPO TERAPEUTICO</strong> <br>
&Aacute;rea: <strong><h3>'.$NombreDeArea.'</h3></strong><br>
PERIODO DEL: '.$FechaInicio2.' AL '.$FechaFin2.'.-</td></tr>
<tr class="MYTABLE"><td align="right" colspan="11">Fecha de Emisi&oacute;n: '.$DateNow=date("d-m-Y").'</td>
    </tr>';
		}
//**********************************



		//Costo Total de la sumatoria de costos por grupos terapeutico
		$Total=0;
//*************************************
//******************************* QUERIES Y RECORRIDOS
$nombreTera=$query->NombreTera($grupoTerapeutico);
while($grupos=mysql_fetch_array($nombreTera)){
$NombreTerapeutico=$grupos["GrupoTerapeutico"];
$IdTerapeutico=$grupos["IdTerapeutico"];
if($NombreTerapeutico!="--"){

$resp=QueryExterna($IdTerapeutico,$Idmedicina,$IdArea,$FechaInicio,$FechaFin);
if($row=mysql_fetch_array($resp)){
	//Subtotal es el costo por grupo terapeutico
	$SubTotal=0;

	
    $reporte.='<tr class="FONDO2" style="background:#999999;">
      <td colspan="11" align="center"><P>
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
	  <th width="63">Consumo</th>
      <th width="78" scope="col">Precio[$]</th>
      <th width="136" scope="col">Monto</th>
    </tr>';
	

	do{
$GrupoTerapeutico=$IdTerapeutico;
$Medicina=$row["IdMedicina"];
$codigoMedicina=$row["Codigo"];
$NombreMedicina=$row["Nombre"];
$concentracion=$row["Concentracion"];
$presentacion=$row["FormaFarmaceutica"];

$Nrecetas=0;//conteo de recetas
$consumo=0;


$respuesta=ObtenerReporteGrupoTerapeutico($GrupoTerapeutico,$Medicina,$FechaInicio,$FechaFin,$IdArea);
	$Nrecetas=mysql_num_rows($respuesta);
		if($row2=mysql_fetch_array($respuesta)){ /* verificacion de datos */
$precioActual=0;
//$respuesta2=ObtenerReporteGrupoTerapeutico($GrupoTerapeutico,$Medicina,$FechaInicio,$FechaFin,$IdArea);  
//		while($row3=mysql_fetch_array($respuesta2)){
//IdReceta
//$row3=mysql_fetch_array($respuesta2);
$IdReceta=$row2["IdReceta"];
$Divisor=$row2["Divisor"];//Divisor de conversion
$UnidadMedida=$row2["Descripcion"];//Tipo de unidad de Medida
$satisfechas=0;
$insatisfechas=0;

/*Obtencion de recetas satifechas e insatisfechas globales parametros ...,0,0)*/
	$sat=ObtenerRecetasSatisfechas($IdReceta,$Medicina,$FechaInicio,$FechaFin,$IdArea,0,0);
	$insat=ObtenerRecetasInsatisfechas($IdReceta,$Medicina,$FechaInicio,$FechaFin,$IdArea,0,0);

//***********
	
	$Cantidad_Total=SumatoriaMedicamento($Medicina,$IdArea,$FechaInicio,$FechaFin);
		$CantidadReal=$Cantidad_Total/$Divisor;
	$Ano=date('Y');
	$Precio=ObtenerPrecioMedicina($Medicina,$Ano);
	$Monto=$CantidadReal*$Precio;
		$PrecioNuevo=number_format($Precio,2,'.',',');
		$MontoNuevo=number_format($Monto,2,'.',',');
			$SubTotal+=$Monto;
	

   $reporte.='<tr class="FONDO2">
      <td style="vertical-align:middle">&nbsp;'.$codigoMedicina.'</td>
      <td align="left" style="vertical-align:middle">&nbsp;'.$NombreMedicina.'</td>
      <td style="vertical-align:middle">&nbsp;'.$concentracion.'</td>
      <td style="vertical-align:middle">&nbsp;'.$presentacion.'</td>
	  <td align="center" style="vertical-align:middle">'.$Nrecetas.'</td>
      <td align="center" style="vertical-align:middle">'.$sat.'</td>
      <td align="center" style="vertical-align:middle">'.$insat.'</td>
	  <td align="center" style="vertical-align:middle">'.$UnidadMedida.'</td>
      <td align="right" style="vertical-align:middle">'.number_format($CantidadReal,2,'.',',').'</td>
	  <td align="right" style="vertical-align:middle">'.$PrecioNuevo.'</td>
      <td align="right" style="vertical-align:middle">'.$MontoNuevo.'</td>
    </tr>';
	
		}//if row2
	}while($row=mysql_fetch_array($resp));//while de la informacion del medicamento
	$Total+=$SubTotal;

   $reporte.=' <tr class="FONDO2"  style="background:#999999;">
      <td colspan="9">&nbsp;</td>
      <td align="right"><strong><em>SubTotal:</em></strong></td>
      <td align="right"><strong>'.number_format($SubTotal,2,'.',',').'</strong></td>
    </tr>';

	}//nuevo IF test del medicamento
}//IF NombreTerapeutico!=--
}//while de grupos terapeuticos
    $reporte.='<tr class="FONDO2" style="background:#CCCCCC;">
      <td colspan="9">&nbsp;</td>
	  <td align="right"><em><strong>Total:</strong></em></td>
	  <td align="right"><strong>'.number_format(round($Total,2),2,'.',',').'</strong></td>
    </tr>';
	
	// FIN DEL RECORRIDO DE LAS AREAS DE FARMACIA
	
		}//while Areas
	//*******************************************	
$reporte.='</table>';

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