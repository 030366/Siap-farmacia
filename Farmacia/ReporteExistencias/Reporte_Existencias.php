<?php session_start();
if(!isset($_SESSION["nivel"])){?>
<script language="javascript">
window.location='../../signIn.php';
</script>
<?php
}else{
if($_SESSION["nivel"]!=1 and $_SESSION["nivel"]!=2){?>
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
$query=new queries;
conexion::conectar();
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../../default.css" media="screen" />
<title>...:::Existencia de Medicamentos:::...</title>
<style type="text/css">
#Layer11 {
	position:absolute;
	left:11px;
	top:254px;
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
	left:895px;
	top:42px;
	width:52px;
	height:20px;
	z-index:6;
}
#Layer31 {
	position:absolute;
	left:703px;
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
#Layer3 {position:absolute;
	left:2px;
	top:190px;
	width:1001px;
	height:30px;
	z-index:6;
}
</style>
<script language="javascript">
function popUp(URL) {
day = new Date();
id = day.getTime();
//id=document.formulario.fecha.value;
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=1000,height=500,left = 20,top = 100');");
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
?></div>
<div id="Layer71">
  <div id="Layer41"><img src="../../images/paisanito.jpg" alt="" width="195" height="94" /></div>
  <span class="style4">Ministerio de Salud P&uacute;blica y Asistencia Social </span></div>
<div id="Layer3" align="center">
  <?php if($nivel==1){?>
<script webstyle4>document.write('<scr'+'ipt src="../../xaramenu.js">'+'</scr'+'ipt>');document.write('<scr'+'ipt src="../../MenuImages/menu_.js">'+'</scr'+'ipt>');/*img src="MenuImages/Menu.gif" moduleid="Default (Project)\Menu_off.xws"*/</script>
  <?php }else{?>
<script webstyle4>document.write('<scr'+'ipt src="../../xaramenu.js">'+'</scr'+'ipt>');document.write('<scr'+'ipt src="../../MenuImages/menucoadmin.js">'+'</scr'+'ipt>');/*img src="MenuImages/MenuCoAdmin.gif" moduleid="MenuCoAdmin (Project)\MenuCoAdmin_off.xws"*/</script>
  <?php }?>
</div>
<?php 
$FechaInicio=explode('-',$_REQUEST["fechaInicio"]);
$FechaFin=explode('-',$_REQUEST["fechaFin"]);
$FechaInicio2=$FechaInicio[2].'-'.$FechaInicio[1].'-'.$FechaInicio[0];
$FechaFin2=$FechaFin[2].'-'.$FechaFin[1].'-'.$FechaFin[0];
$FechaInicio=$_REQUEST["fechaInicio"];
$FechaFin=$_REQUEST["fechaFin"];

$Area=$_REQUEST["select2"];
$Farmacia=$_REQUEST["select1"];
if($Area==0){?><script language="javascript">window.location="reporteTotal.php?IdFarmacia=<?php echo $Farmacia;?>&fechaInicio=<?php echo $FechaInicio;?>&fechaFin=<?php echo $FechaFin;?>";</script><?php }
//conexion::conectar();
$NomArea=mysql_query("select Area from mnt_areafarmacia where IdArea='$Area'");
$N=mysql_fetch_array($NomArea);
//conexion::desconectar();
$Area=$N["Area"];
?>
<div id="Layer11">
  <table width="967">
      <tr class="MYTABLE">
      <td colspan="10" align="center">HOSPITAL NACIONAL ROSALES<br>
        <strong>REPORTE DE EXISTENCIAS DE MEDICAMENTOS </strong><br>
AREA: <strong><?php echo $Area?></strong><br>
PERIODO: <?php echo"$FechaInicio2";?> AL <?php echo"$FechaFin2";?> .- <br>
<div align="left">Fecha de Emisi&oacute;n: 
  <?php 
$DateNow=date("d/m/Y");
echo"$DateNow";?></div></td>
    </tr>
  </table>
 <?php
//*****FILTRACION DE EXISTENCIAS POR FARMACIA, AREA Y MEDICAMENTO
$farmacia=$_REQUEST["select1"]; //combo de farmacias
$area=$_REQUEST["select2"];

//COMBO GRUPO TERA
if(isset($_REQUEST["select3"])){$grupoTerapeutico=$_REQUEST["select3"];}else{$grupoTerapeutico=0;}
//COMBO MEDICAMENTO
if(isset($_REQUEST["select4"])){$medicina=$_REQUEST["select4"];}else{$medicina=0;}

?>
<form action="" method="post" name="formulario">
<input name="fechaInicio" type="hidden" value="<?php echo"$FechaInicio";?>">
<input name="fechaFin" type="hidden" value="<?php echo"$FechaFin";?>">
<input name="select1" type="hidden" value="<?php echo"$farmacia";?>">
<input name="select2" type="hidden" value="<?php echo"$area";?>">
<input name="select3" type="hidden" value="<?php echo"$grupoTerapeutico";?>">
<input name="select4" type="hidden" value="<?php echo"$medicina";?>">

<div id="Layer31" align="right">
<table width="203">
<tr><td>&nbsp;</td><td>
<input type="button" name="imprimir" value="Vista Previa" onClick="javascript:popUp('impresion.php?select1='+ document.formulario.select1.value +'&select2='+ document.formulario.select2.value +'&select3='+ document.formulario.select3.value +'&select4='+document.formulario.select4.value+'&fechaInicio='+ document.formulario.fechaInicio.value +'&fechaFin='+ document.formulario.fechaFin.value);" onMouseOver="this.style.color='#CC0000';Tip('Vista Previa e Impresi&oacute;n <img src=\'../../images/preview.JPG\'><br> del Reporte',TEXTALIGN,'justify')" onMouseOut="this.style.color='#000000'; UnTip()" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099">
</td></tr>
<tr><td><input type="text" id="NombreArchivo" name="NombreArchivo" maxlength="50" size="15" value="NombreArchivo" onFocus="if(this.value=='NombreArchivo'){this.value=''}" onBlur="if(this.value==''){this.value='NombreArchivo'}"></td>
<td>
<input type="button" name="Exportar" value="Exportar Excel" onClick="javascript:popUp('ReporteExcelExistencias.php?select1='+ document.formulario.select1.value +'&select2='+ document.formulario.select2.value +'&select3='+ document.formulario.select3.value +'&select4='+document.formulario.select4.value+'&fechaInicio='+ document.formulario.fechaInicio.value +'&fechaFin='+ document.formulario.fechaFin.value+'&nombreArchivo='+ document.formulario.NombreArchivo.value);" onMouseOver="this.style.color='#CC0000';Tip('Exportar a Excel<br><img src=\'../../images/excel.GIF\'>',TEXTALIGN,'center')" onMouseOut="this.style.color='#000000'; UnTip()" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099">
</td></tr>
<tr><td>&nbsp;</td><td>
<input type="button" name="Regresar" value="Regresar" onClick="javascript:window.location='Rep_Existencias.php'" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099" title="Regresar">
</td></tr>
</table>
</div>
</form>
<?php
//*************************************
//******************************* QUERIES Y RECORRIDOS
$nombreTera=$query->NombreTera($grupoTerapeutico);?>
<table width="968" border="1" cellpadding="0" cellspacing="0">
<?php
while($grupos=mysql_fetch_array($nombreTera)){
$NombreTerapeutico=$grupos["GrupoTerapeutico"];
$IdTerapeutico=$grupos["IdTerapeutico"];
//if($NombreTerapeutico!="--"){
//*****Verificacion de numero de datos, asi se rompe el lazo para evitar impresiones de datos no existentes
//$respuesta2=$query->ObtenerReporteExistencias($IdTerapeutico,$area,$medicina,$FechaInicio,$FechaFin);
//if($row=mysql_fetch_array($respuesta2)){
//***************
?>
    <tr class="MYTABLE">
      <td colspan="10" align="center"><P>
&nbsp;<strong><?php echo"$NombreTerapeutico";?></strong></td>
    </tr>
	    <tr class="FONDO2">
    <th width="52" scope="col">Codigo</th>
      <th width="142" scope="col">Medicamento</th>
      <th width="61" scope="col">Concen.</th>
      <th width="64" scope="col">Prese.</th>
      <th width="63" scope="col">Unidad de Medida</th>
      <th width="79" scope="col">Existencia Total</th>
      <th width="168" scope="col">Existencia/Lote</th>
      <th width="86" scope="col">Consumo</th>
	  <th width="126" scope="col">Sugerencia de Decisi&oacute;n </th>
      <th width="105" scope="col">Cobertura [Meses]</th>
    </tr>
<?php
//TENGO TODOS LOS DEL GRUPO TERAPEUTICO
$respuesta=$query->ObtenerReporteExistencias($IdTerapeutico,$area,$medicina,$FechaInicio,$FechaFin);

	while($row3=mysql_fetch_array($respuesta)){
		$consumo=0;
		$IdHistorialClinico=0;
		$existencias=0;
		$Divisor=$row3["Divisor"];//Divisor de conversion
		$UnidadMedida=$row3["Descripcion"];//Tipo de unidad de Medida
		//****
		$Medicina=$row3["IdMedicina"];
		//****
		$codigoMedicina=$row3["Codigo"];
		$NombreMedicina=$row3["Nombre"];
		$concentracion=$row3["Concentracion"];
		$presentacion=$row3["FormaFarmaceutica"];
		//***********

$consumo=$query->MedicinaEntregada($Medicina,$area,$FechaInicio,$FechaFin);

//***********		

			//if($tmpMedicina!=$Medicina){
			?>
    <tr class="FONDO2">
      <td align="center" style="vertical-align:middle;">&nbsp;<?php echo $codigoMedicina;?></td>
      <td align="center" style="vertical-align:middle;">&nbsp;<?php echo $NombreMedicina;?></td>
      <td align="center" style="vertical-align:middle;">&nbsp;<?php echo $concentracion;?></td>
      <td align="center" style="vertical-align:middle;">&nbsp;<?php echo $presentacion;?></td>
	  <td align="center" style="vertical-align:middle;"><?php echo $UnidadMedida;?></td>
      <?php 
	  $respLotes=queries::ObtenerLotesExistencias($Medicina,$area);
	  ?>
	  <td align="center"><?php echo queries::ObtenerExistenciaTotal($Medicina,$area)/$Divisor;?></td>
	  <td align="center"><?php
	  while($rowLote=mysql_fetch_array($respLotes)){
	  $Existencia=$rowLote["Existencia"];
	  $Lote=$rowLote["Lote"];
	  $mes=meses::NombreMes($rowLote["mes"]);
	  $ano=$rowLote["ano"];
	  $existencias+=$Existencia;
	  $ExistenciaLote=$Existencia/$Divisor;
	  
	  
	  echo "Existencias: ".$ExistenciaLote."<br>Lote: ".$Lote."<br>Fecha de Vencimiento: ".$mes."/".$ano."<br><br>";

	  }//fin de while lotes?>
	  </td>
	  <?php
	  /* MEDICION DE COBERTURA Y EXISTENCIA A TRANFERIR */
		$datos=queries::Transferencias($Medicina,$consumo,$existencias);
		$ConsumoAproximado=$datos[0];
		$Transferencia=$datos[1];
		$CoberturaEstimada=$datos[2];	  
		
	  ?>
	  
      <td align="center">&nbsp;<?php echo $consumo/$Divisor;?></td>
<?php if($consumo!=0){$cobertura=round($existencias/$consumo,0);}else{$cobertura="No hay consumo";}?>


<td align="center">&nbsp;<?php 
if($consumo !=0){
echo "Cobertura Estimada en Meses: ".$CoberturaEstimada."<br>";
echo "Consumo Aproximado: ".$ConsumoAproximado/$Divisor."<br>";
echo "Cantidad a ser Tranferida: ".$Transferencia/$Divisor." Aprox.";
}else{
echo "aun sin consumo <br>";
echo "Cantidad a ser Tranferido.".$Transferencia/$Divisor." Aprox.<br>";
echo "por vencimiento";
}

?></td>


      <td align="center">&nbsp;<?php if($CoberturaEstimada!=''){echo $CoberturaEstimada;}else{echo $cobertura;}?></td>
    </tr>
<?php		//}//conparacion de vectores
		}//while row3	
?>   
    <tr class="MYTABLE">
      <td colspan="10">&nbsp;
	  </td>
    </tr>
<?php  
//}//si hay datos de ese grupo

//}//IF NombreTerapeutico!=--

}//while de nombreTera?>
  </table>
</div>
</body>
</html>
<?php
conexion::desconectar();
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel
?>