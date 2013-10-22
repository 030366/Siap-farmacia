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
<style type="text/css">
#Layer11 {
	position:absolute;
	left:0px;
	top:0px;
	width:970px;
	height:144px;
	z-index:1;
}
#Layer61 {position:absolute;
	left:21px;
	top:12px;
	width:847px;
	height:34px;
	z-index:5;
}
#Layer21 {
	position:absolute;
	left:901px;
	top:128px;
	width:52px;
	height:20px;
	z-index:6;
}
#Layer31 {
	position:absolute;
	left:855px;
	top:10px;
	width:63px;
	height:24px;
	z-index:7;
}
@media print {
* { background: #fff; color: #000; }
html { font: 8pt Arial, Helvetica, sans-serif; }
#Layer61, #Layer21, #Layer31 { display: none; }
#nav, #nav2, #about { display: none; }
#footer { display:none;}
#span{ color:#FFFFFF}
@page { size: 8.5in 11in; margin: 1.0cm }
/*P{page-break-after:inherit}*/
}
#Layer1 {
	position:absolute;
	left:858px;
	top:40px;
	width:62px;
	height:22px;
	z-index:2;
}
#Layer2 {
	position:absolute;
	left:9px;
	top:293px;
	width:1011px;
	height:17px;
	z-index:8;
}
</style>
<script src="../PrintReport.js" language="javascript"></script>
</head>
<body>
<div id="Layer2"></div>
<script language="javascript" src="../../tooltip/wz_tooltip.js"></script>
<?php 
$FechaInicio=explode('-',$_REQUEST["fechaInicio"]);
$FechaFin=explode('-',$_REQUEST["fechaFin"]);
$FechaInicio2=$FechaInicio[2].'-'.$FechaInicio[1].'-'.$FechaInicio[0];
$FechaFin2=$FechaFin[2].'-'.$FechaFin[1].'-'.$FechaFin[0];
$FechaInicio=$_REQUEST["fechaInicio"];
$FechaFin=$_REQUEST["fechaFin"];

$Area=$_REQUEST["select2"];

$NomArea=mysql_query("select Area from mnt_areafarmacia where IdArea='$Area'");
$N=mysql_fetch_array($NomArea);

$Area=$N["Area"];
?>
<form>
  <table border="0" cellpadding="0" cellspacing="0" width="967">
      <tr>
      <td colspan="10" align="center">HOSPITAL NACIONAL ROSALES<br>
        <strong>REPORTE DE EXISTENCIAS DE MEDICAMENTOS </strong><br>
AREA: <?php echo $Area?><br>
PERIODO: <?php echo"$FechaInicio2";?> AL <?php echo"$FechaFin2";?> .- <br>
<div align="right">Fecha de Emisi&oacute;n: 
  <?php 
$DateNow=date("d/m/Y");
echo"$DateNow";?></div></td>
    </tr>
  </table>
</form>
 <?php
//*****FILTRACION DE EXISTENCIAS POR FARMACIA, AREA Y MEDICAMENTO
$farmacia=$_REQUEST["select1"]; //combo de farmacias
$area=$_REQUEST["select2"];

//COMBO GRUPO TERA
if(isset($_REQUEST["select3"])){$grupoTerapeutico=$_REQUEST["select3"];}else{$grupoTerapeutico=0;}
//COMBO MEDICAMENTO
if(isset($_REQUEST["select4"])){$medicina=$_REQUEST["select4"];}else{$medicina=0;}
?>
<div id="Layer31" align="right">
<input type="button" id="imprimir" name="imprimir" value="IMPRIMIR" onClick="ImprimirReporte(this.form)" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099">
</p>
  <input type="button" id="cerrar" name="cerrar" value="CERRAR" onClick="javascript:self.close()" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099">
</div>
<?php
//*************************************
//******************************* QUERIES Y RECORRIDOS
$separador=0;
$separador2=0;
$nombreTera=$query->NombreTera($grupoTerapeutico);?>
<table width="968" border="1">
<?php
while($grupos=mysql_fetch_array($nombreTera)){
$NombreTerapeutico=$grupos["GrupoTerapeutico"];
$IdTerapeutico=$grupos["IdTerapeutico"];
if($NombreTerapeutico!="--"){
//*****Verificacion de numero de datos, asi se rompe el lazo para evitar impresiones de datos no existentes
$respuesta2=$query->ObtenerReporteExistencias($IdTerapeutico,$farmacia,$medicina,$FechaInicio,$FechaFin);
if($row=mysql_fetch_array($respuesta2)) {
//***************
?>
    <tr bordercolor="#000000">
      <td colspan="10" align="center"><P>
&nbsp;<strong><?php echo"$NombreTerapeutico";?></strong></td>
    </tr>
	    <tr bordercolor="#000000">
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

$respuesta=$query->ObtenerReporteExistencias($IdTerapeutico,$area,$medicina,$FechaInicio,$FechaFin);
$temp=$query->ObtenerReporteExistencias($IdTerapeutico,$area,$medicina,$FechaInicio,$FechaFin);
$Rowtmp=mysql_fetch_array($temp);
		while($row3=mysql_fetch_array($respuesta)){
$Rowtmp=mysql_fetch_array($temp); //adelante una posicion
$consumo=0;
$IdHistorialClinico=0;
$existencias=0;
$Divisor=$row3["Divisor"];//Divisor de conversion
$UnidadMedida=$row3["Descripcion"];//Tipo de unidad de Medida

//****
if($medicina!=0){$Medicina=$medicina; $tmpMedicina=0;}
else{$Medicina=$row3["IdMedicina"];
$tmpMedicina=$Rowtmp["IdMedicina"];
}
//****
$codigoMedicina=$row3["Codigo"];
$NombreMedicina=$row3["Nombre"];
$concentracion=$row3["Concentracion"];
$presentacion=$row3["FormaFarmaceutica"];
//***********

$consumo=$query->MedicinaEntregada($Medicina,$area,$FechaInicio,$FechaFin);

//***********		

			if($tmpMedicina!=$Medicina){
			?>
    <tr bordercolor="#000000">
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
	  $mes=$rowLote["mes"];
	  $ano=$rowLote["ano"];
	  $mes=meses::NombreMes($mes);
	  $fechaVentto=$mes;
	  $existencias+=$Existencia;
	  
	  echo "Existencias: ".$Existencia/$Divisor."<br>Lote: ".$Lote."<br>Fecha de Vencimiento: ".$fechaVentto."/".$ano."<br><br>";

	  }//fin de while lotes?>
	  </td>
      <td align="center">&nbsp;<?php echo $consumo/$Divisor;?></td>
	  	  <?php
	  /* MEDICION DE COBERTURA Y EXISTENCIA A TRANFERIR */
		$datos=queries::Transferencias($Medicina,$consumo,$existencias);
		$ConsumoAproximado=$datos[0];
		$Transferencia=$datos[1];
		$CoberturaEstimada=$datos[2];	  
		
	  ?>
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
	  
	  
<?php if($consumo!=0){$cobertura=$existencias/$consumo; $cobertura=round($cobertura,0);}else{$cobertura="No hay consumo";}?>
      <td align="center">&nbsp;<?php if($CoberturaEstimada!=''){echo $CoberturaEstimada;}else{echo $cobertura;}?></td>
    </tr>
<?php		}//conparacion de vectores
		}//while row3	

}//si hay datos de ese grupo

}//IF NombreTerapeutico!=--

}//while de nombreTera?>
</table>
</body>
</html>
<?php
conexion::desconectar();
}//Fin de IF nivel == 1 verificacion de nivel de usuario

}//Fin de IF isset de Nivel verificacoin de sesion
?>