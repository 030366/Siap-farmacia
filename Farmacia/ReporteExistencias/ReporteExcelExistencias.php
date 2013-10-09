<?php 
$nombreArchivo=$_GET["nombreArchivo"];

   
/*******MINE******/
header("Pragma: no-cache");
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$nombreArchivo.xls");
header("Expires: 0");
/*****/

require('../../Clases/class.php');
$query=new queries;
conexion::conectar();
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
  <table cellpadding="0" cellspacing="0" width="967">
      <tr>
      <td colspan="10" align="center">HOSPITAL NACIONAL ROSALES<br>
        <strong>REPORTE DE EXISTENCIAS DE MEDICAMENTOS </strong><br>
AREA: <?php echo $Area?><br>
PERIODO: <?php echo"$FechaInicio2";?> AL <?php echo"$FechaFin2";?> .- <br>
<div align="right">Fecha de Emisi&oacute;n: 
  <?php 
$DateNow=date("d-m-Y");
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
 <?php
//*************************************
//******************************* QUERIES Y RECORRIDOS
$separador=0;
$separador2=0;
$nombreTera=$query->NombreTera($grupoTerapeutico);?>
<table width="968" border="1" cellpadding="0.5" cellspacing="0.5">
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
    <tr>
      <td colspan="10" align="center" style="background:#CCCCCC;"><P><strong><?php echo"$NombreTerapeutico";?></strong></td>
    </tr>
	    <tr>
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
$Divisor=$row3["Divisor"];//Divisor de conversion
$UnidadMedida=$row3["Descripcion"];//Tipo de unidad de Medida

//***********

$consumo=$query->MedicinaEntregada($Medicina,$area,$FechaInicio,$FechaFin);

//***********		

			if($tmpMedicina!=$Medicina){
			?>
    <tr>
      <td align="center" style="vertical-align:middle;"><?php echo $codigoMedicina;?></td>
      <td align="center" style="vertical-align:middle;"><?php echo $NombreMedicina;?></td>
      <td align="center" style="vertical-align:middle;"><?php echo $concentracion;?></td>
      <td align="center" style="vertical-align:middle;"><?php echo $presentacion;?></td>
	  <td align="center" style="vertical-align:middle;"><?php echo $UnidadMedida;?></td>
      <?php 
	  $respLotes=queries::ObtenerLotesExistencias($Medicina,$area);
	  ?>
	  <td align="center" style="vertical-align:middle;"><?php echo queries::ObtenerExistenciaTotal($Medicina,$area)/$Divisor;?></td>
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

	  }//fin de while lotes?></td>
	  
      <td align="center" style="vertical-align:middle;"><?php echo $consumo/$Divisor;?></td>
	  	  	  <?php
	  /* MEDICION DE COBERTURA Y EXISTENCIA A TRANFERIR */
		$datos=queries::Transferencias($Medicina,$consumo,$existencias);
		$ConsumoAproximado=$datos[0];
		$Transferencia=$datos[1];
		$CoberturaEstimada=$datos[2];	  
		
	  ?>
<td align="center" style="vertical-align:middle;"><?php 
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
      <td align="center" style="vertical-align:middle;"><?php if($CoberturaEstimada!=''){echo $CoberturaEstimada;}else{echo $cobertura;}?></td>
    </tr>
<?php		}//conparacion de vectores
		}//while row3	
}//si hay datos de ese grupo

}//IF NombreTerapeutico!=--

}//while de nombreTera?>
</table>
<?php 
conexion::desconectar();
?>