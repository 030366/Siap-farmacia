<html>
<head>
<style type="text/css">
<!--
#nav2 {
	position:absolute;
	left:707px;
	top:64px;
	width:76px;
	height:32px;
	z-index:1;
}
-->
@media print {
* { background: #fff; color: #000; }
html { font: 100%/1.3 Arial, Helvetica, sans-serif; }
#nav,#nav1, #nav2, #about { display: none; }
#footer { display:none;}
#span{ color:#FFFFFF}

}
.style2 {color: #000000}
.style3 {font-weight: bold}
</style>
</head>
<body onload="javascript:window.print();" onblur="javascript:this.close();">
<?php
$IdGrupo=$_GET["select1"];
$IdMedicina=$_GET["select2"];
$fechaInicio=$_GET["fechaInicio"];
$fechaFin=$_GET["fechaFin"];
$Gpastel=$_GET["Pastel"];
$Gbarras=$_GET["Barras"];
$Glineas=$_GET["Lineas"];
//LIBRERIAS
	require_once 'classes/Point.php';
	require_once 'classes/Axis.php';
	require_once 'classes/Color.php';
	require_once 'classes/Primitive.php';
	require_once 'classes/Text.php';
	require_once 'classes/Chart.php';
	require_once 'classes/PieChart.php';
	require_once 'classes/BarChart.php';
	require_once 'classes/LineChart.php';
	require_once 'classes/VerticalChart.php';
	require_once 'classes/HorizontalChart.php';
	include('IncludeFiles/GraficoClases.php');
	conexion::conectar();
$resp=mysql_query("select concat_ws('-',day('$fechaInicio'),month('$fechaInicio'),year('$fechaInicio'))as fechaInicio, concat_ws('-',day('$fechaFin'),month('$fechaFin'),year('$fechaFin'))as fechaFin");
$Fechas=mysql_fetch_array($resp);
$FechaInicio2=$Fechas["fechaInicio"];$FechaFin2=$Fechas["fechaFin"];

//***********

//*********PASTEL***********
if($Gpastel==1){
$resp=Graficacion::QueryGraficaPorMedicamento($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);
if($test=mysql_fetch_array($resp)){
$resp=Graficacion::QueryGraficaPorMedicamento($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);
$chart = new PieChart(700, 300);
while($row=mysql_fetch_array($resp)){
$Nombre=$row["Nombre"];
$Presentacion=$row["FormaFarmaceutica"];
$M11=$row["Suma"];
$Divisor=$row["Divisor"];
$UnidadMedida=$row["Descripcion"];
//$T=$row["Existencia"];
$mes=$row["MesNombre"];
$mes=meses::NombreMes($mes);
//$T=round($T,0);
$M1=round($M11);
$M1=$M1;
$chart->addPoint(new Point($mes." (".$M1/$Divisor."-".$UnidadMedida.")", $M1));
}

$chart->setTitle("Gráfica Estadística de Medicina Entregada: $Nombre");
$chart->render("prueba.png");
}
}//IF Gpastel

//*******BARRAS***********
if($Gbarras==1){
$resp2=Graficacion::QueryGraficaPorMedicamento($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);

if($test=mysql_fetch_array($resp2)){
$resp2=Graficacion::QueryGraficaPorMedicamento($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);
$chart2 = new VerticalChart(700, 350);
while($row2=mysql_fetch_array($resp2)){

$Nombre=$row2["Nombre"];
$Presentacion=$row2["FormaFarmaceutica"];
$M2=$row2["Suma"];
$Divisor=$row2["Divisor"];
$UnidadMedida=$row2["Descripcion"];

//$T=$row2["Existencia"];
$mes=$row2["MesNombre"];
$mes=meses::NombreMes($mes);
$M2=$M2;
$chart2->addPoint(new Point($mes." (".$M2/$Divisor."-".$UnidadMedida.")", $M2));
}
$chart2->setTitle("Gráfica Estadística de Medicina Entregada: $Nombre");
$chart2->render("prueba2.png");
}

}//If GBarra

//**************LINEAS
if($Glineas==1){
$resp2=Graficacion::QueryGraficaPorMedicamento($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);

if($test=mysql_fetch_array($resp2)){
$resp2=Graficacion::QueryGraficaPorMedicamento($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);
$chart2 = new LineChart(700, 350);
while($row2=mysql_fetch_array($resp2)){

$Nombre=$row2["Nombre"];
$Presentacion=$row2["FormaFarmaceutica"];
$M2=$row2["Suma"];
//$T=$row2["Existencia"];
$mes=$row2["MesNombre"];
$Divisor=$row2["Divisor"];
$UnidadMedida=$row2["Descripcion"];

$mes=meses::NombreMes($mes);
$M2=$M2;
$chart2->addPoint(new Point($mes." (".$M2/$Divisor."-".$UnidadMedida.")", $M2));
}
$chart2->setTitle("Gráfica Estadística de Medicina Entregada: $Nombre");
$chart2->render("prueba3.png");
}

}//If lineas
//****LINEAS FIN
$resp=Graficacion::QueryGraficaPorMedicamento($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);
$resp2=Graficacion::QueryGraficaPorMedicamento($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);
if($test=mysql_fetch_array($resp)|| $test2=mysql_fetch_array($resp2)){
?>
<table width="801" align="left" border="1">
<tr><td height="16" colspan="2">
<table align="center">
<tr><td width="667" align="center">
<?php echo "Tendencia de consumo de medicamento: $Nombre, $Presentacion <br> Periodo: $FechaInicio2 Al $FechaFin2";?>
</tr>
<tr><td>
  <?php if($Gpastel==1){ ?>
  <div align="center">
  <img src="prueba.png" style="border: 1px solid gray;"/>

  </div><?php }?></td></tr>
<tr><td>
<?php if($Gbarras==1){?>
  <div align="center">

  <img src="prueba2.png" style="border: 1px solid gray;"/>

  </div><?php }?></td>
</tr>
<tr><td>
<?php if($Glineas==1){?>
  <div align="center">

  <img src="prueba3.png" style="border: 1px solid gray;"/>

  </div><?php }?>
 </td></tr>

</table>
</td></tr></table>
<?php 
conexion::desconectar();
}//if test
else{echo "<div id='resp' align='center'><h3>No hay datos para ser graficados</h3></div>";}
?>
</body>

</html>
