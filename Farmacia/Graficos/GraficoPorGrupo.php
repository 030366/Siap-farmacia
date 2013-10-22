<?php session_start();?>
<style type="text/css">

@media print {
* { background: #fff; color: #000; }
html { font: 100%/1.3 Arial, Helvetica, sans-serif; }
#nav,#nav1, #nav2, #about { display: none; }
#footer { display:none;}
#span{ color:#FFFFFF;}
p{page-break-before:always;}
}

</style>
<?php
//Datos para ser graficados...
$IdGrupo=$_GET["select1"];
$IdMedicina=$_GET["select2"];
$fechaInicio=$_GET["fechaInicio"];
$fechaFin=$_GET["fechaFin"];
$Gpastel=$_GET["Pastel"];
$Gbarras=$_GET["Barras"];
$Glineas=$_GET["Lineas"];
$TipoInfo=$_GET["TipoInfo"];
?>

<?php //LIBRERIAS
	require_once 'classes/Chart.php'; 			//Clase Padre de Creacion de Imagenes
	require_once 'classes/Point.php'; 			//
	require_once 'classes/Axis.php';			//	
	require_once 'classes/Color.php';			//Clase del color de la imagen
	require_once 'classes/Primitive.php';		//
	require_once 'classes/Text.php';			//Texto de la imagen
	require_once 'classes/PieChart.php';		//Grafico de Pastel
	require_once 'classes/BarChart.php';		//Grafico de barras Verticales
	require_once 'classes/LineChart.php';		//Grafico de Lineas
	require_once 'classes/VerticalChart.php';	//Grafico de barras horizontales
	require_once 'classes/HorizontalChart.php';
	include('IncludeFiles/GraficoClases.php');	//Queries para la generacion de los graficos
	conexion::conectar();

$f1=explode('-',$fechaInicio);
$f2=explode('-',$fechaFin);

$FechaInicio2=$f1[2]."-".$f1[1]."-".$f1[0];
$FechaFin2=$f2[2]."-".$f2[1]."-".$f2[0];

//***********?>
<input type="hidden" id="Gpastel" name="Gpastel" value="<?php echo $Gpastel;?>" />
<input type="hidden" id="Gbarras" name="Gbarras" value="<?php echo $Gbarras;?>" />
<input type="hidden" id="Glineas" name="Glineas" value="<?php echo $Glineas;?>" />
<?php
//*********PASTEL***********

///////SI LA GRAFICA ES POR CONSUMO
if($Gpastel==1 AND $TipoInfo==1){
$resp=Graficacion::QueryGraficaPorMedicamento($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);
$respT=Graficacion::QueryGraficaPorMedicamento($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);
$respu=Graficacion::QueryGraficaPorMedicamento2($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);

if($row=mysql_fetch_array($resp)){
$chart = new PieChart(700, 380);
    $rowT=mysql_fetch_array($respT);
    $rowPu=mysql_fetch_array($respu);
   
do{
   $rowT=mysql_fetch_array($respT);
$IdMedicinaTemp=$rowT["IdMedicina"];
$IdMedicinaAct=$row["IdMedicina"];
$IdMedicinaPu=$rowPu["IdMedicina"];


$Nombre=$row["Nombre"];
$Presentacion=$row["FormaFarmaceutica"];
$Concentracion=$row["Concentracion"];
$M11=$row["Suma"];
$Divisor=$row["Divisor"];
$UnidadMedida=$row["Descripcion"];
//$T=$row["Existencia"];
$mes=$row["MesNombre"];
$ano=$row["ano"];
$mes=meses::NombreMes($mes)."-".$ano;
//$T=round($T,0);
$M1=round($M11);
$M1=$M1;


if($IdMedicinaPu==$IdMedicinaAct){
$chart->addPoint(new Point($Nombre." - ".$mes." \n (".$M1/$Divisor."-".$UnidadMedida.")", $M1));

}

if($IdMedicinaTemp!=$IdMedicinaAct){
   $chart->setTitle("Gr�fica Estad�stica de Medicina Entregada: $Nombre - $Concentracion");
   $chart->render("imagenesGraficos/Pastel".$IdMedicinaAct.".png");
}

if($IdMedicinaTemp!=$IdMedicinaAct){
$chart = new PieChart(700, 380);

}

if($IdMedicinaTemp!=$IdMedicinaAct){
   $rowPu=mysql_fetch_array($respu);
}

	
}while($row=mysql_fetch_array($resp));

}
}//IF Gpastel

/////////GRAFICA POR NUMERO DE RECETAS

if($Gpastel==1 AND $TipoInfo==2){
$resp=Graficacion::QueryGraficaPorMedicamentoRecetas($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);
$respT=Graficacion::QueryGraficaPorMedicamentoRecetas($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);
$respu=Graficacion::QueryGraficaPorMedicamentoRecetas2($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);

if($row=mysql_fetch_array($resp)){
$chart = new PieChart(700, 380);
    $rowT=mysql_fetch_array($respT);
    $rowPu=mysql_fetch_array($respu);
   
do{
   $rowT=mysql_fetch_array($respT);
$IdMedicinaTemp=$rowT["IdMedicina"];
$IdMedicinaAct=$row["IdMedicina"];
$IdMedicinaPu=$rowPu["IdMedicina"];


$Nombre=$row["Nombre"];
$Presentacion=$row["FormaFarmaceutica"];
$Concentracion=$row["Concentracion"];
$M11=$row["TotalRecetas"];
$Divisor=$row["Divisor"];
$UnidadMedida=$row["Descripcion"];
//$T=$row["Existencia"];
$mes=$row["MesNombre"];
$ano=$row["ano"];
$mes=meses::NombreMes($mes)."-".$ano;
//$T=round($T,0);
$M1=round($M11);
$M1=$M1;


if($IdMedicinaPu==$IdMedicinaAct){
$chart->addPoint(new Point($Nombre." - ".$mes." \n (".$M1.")", $M1));

}

if($IdMedicinaTemp!=$IdMedicinaAct){
   $chart->setTitle("Gr�fica Estad�stica de Recetas Despachadas: $Nombre - $Concentracion");
   $chart->render("imagenesGraficos/Pastel".$IdMedicinaAct.".png");
}

if($IdMedicinaTemp!=$IdMedicinaAct){
$chart = new PieChart(700, 380);

}

if($IdMedicinaTemp!=$IdMedicinaAct){
   $rowPu=mysql_fetch_array($respu);
}

	
}while($row=mysql_fetch_array($resp));

}
}//IF Gpastel


///////////////////////////////////////////////////////////
//*******BARRAS***********
if($Gbarras==1 and $TipoInfo==1){


$resp=Graficacion::QueryGraficaPorMedicamento($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);
$respT=Graficacion::QueryGraficaPorMedicamento($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);
$respu=Graficacion::QueryGraficaPorMedicamento2($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);


if($row=mysql_fetch_array($resp)){

$chart2 = new VerticalChart(760, 350);

    $rowT=mysql_fetch_array($respT);
    $rowPu=mysql_fetch_array($respu);

do{
   $rowT=mysql_fetch_array($respT);
$IdMedicinaTemp=$rowT["IdMedicina"];
$IdMedicinaAct=$row["IdMedicina"];
$IdMedicinaPu=$rowPu["IdMedicina"];

$Nombre=$row["Nombre"];
$Presentacion=$row["FormaFarmaceutica"];
$Concentracion=$row["Concentracion"];
$M2=$row["Suma"];
$Divisor=$row["Divisor"];
$UnidadMedida=$row["Descripcion"];

//$T=$row2["Existencia"];
$mes=$row["MesNombre"];
$ano=$row["ano"];
$mes=meses::NombreMes($mes)."-".$ano;
$M2=$M2;


if($IdMedicinaPu==$IdMedicinaAct){
$chart2->addPoint(new Point($mes." (".$M2/$Divisor."-".$UnidadMedida.")", $M2));
}


if($IdMedicinaTemp!=$IdMedicinaAct){
   $chart2->setTitle("Gr�fica Estad�stica de Medicina Entregada: $Nombre - $Concentracion");
   $chart2->render("imagenesGraficos/Barras".$IdMedicinaAct.".png");
}

if($IdMedicinaTemp!=$IdMedicinaAct){
$chart2 = new VerticalChart(760, 350);

}

if($IdMedicinaTemp!=$IdMedicinaAct){
   $rowPu=mysql_fetch_array($respu);
}


}while($row=mysql_fetch_array($resp));

}

}//If GBarra

////////////////SI LA INFORMACION ES POR NUMERO DE RECETAS
if($Gbarras==1 and $TipoInfo==2){


$resp=Graficacion::QueryGraficaPorMedicamentoRecetas($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);
$respT=Graficacion::QueryGraficaPorMedicamentoRecetas($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);
$respu=Graficacion::QueryGraficaPorMedicamentoRecetas2($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);


if($row=mysql_fetch_array($resp)){

$chart2 = new VerticalChart(760, 350);

    $rowT=mysql_fetch_array($respT);
    $rowPu=mysql_fetch_array($respu);

do{
   $rowT=mysql_fetch_array($respT);
$IdMedicinaTemp=$rowT["IdMedicina"];
$IdMedicinaAct=$row["IdMedicina"];
$IdMedicinaPu=$rowPu["IdMedicina"];

$Nombre=$row["Nombre"];
$Presentacion=$row["FormaFarmaceutica"];
$Concentracion=$row["Concentracion"];
$M2=$row["TotalRecetas"];
$Divisor=$row["Divisor"];
$UnidadMedida=$row["Descripcion"];

//$T=$row2["Existencia"];
$mes=$row["MesNombre"];
$ano=$row["ano"];
$mes=meses::NombreMes($mes)."-".$ano;
$M2=$M2;


if($IdMedicinaPu==$IdMedicinaAct){
$chart2->addPoint(new Point($mes." (".$M2.")", $M2));
}


if($IdMedicinaTemp!=$IdMedicinaAct){
   $chart2->setTitle("Gr�fica Estad�stica de Recetas Despachadas: $Nombre - $Concentracion");
   $chart2->render("imagenesGraficos/Barras".$IdMedicinaAct.".png");
}

if($IdMedicinaTemp!=$IdMedicinaAct){
$chart2 = new VerticalChart(760, 350);

}

if($IdMedicinaTemp!=$IdMedicinaAct){
   $rowPu=mysql_fetch_array($respu);
}


}while($row=mysql_fetch_array($resp));

}

}//If GBarra




/////////////////////////////////////////////////////////////
//**************LINEAS
if($Glineas==1 and $TipoInfo==1){
$resp=Graficacion::QueryGraficaPorMedicamento($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);
$respT=Graficacion::QueryGraficaPorMedicamento($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);
$respu=Graficacion::QueryGraficaPorMedicamento2($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);

if($row=mysql_fetch_array($resp)){

$chart2 = new LineChart(700, 350);
    $rowT=mysql_fetch_array($respT);
    $rowPu=mysql_fetch_array($respu);

do{
   $rowT=mysql_fetch_array($respT);
$IdMedicinaTemp=$rowT["IdMedicina"];
$IdMedicinaAct=$row["IdMedicina"];
$IdMedicinaPu=$rowPu["IdMedicina"];

$Nombre=$row["Nombre"];
$Presentacion=$row["FormaFarmaceutica"];
$Concentracion=$row["Concentracion"];
$M2=$row["Suma"];
//$T=$row2["Existencia"];
$mes=$row["MesNombre"];
$Divisor=$row["Divisor"];
$UnidadMedida=$row["Descripcion"];

$ano=$row["ano"];
$mes=meses::NombreMes($mes)."-".$ano;
$M2=$M2;

if($IdMedicinaPu==$IdMedicinaAct){
$chart2->addPoint(new Point($mes." (".$M2/$Divisor."-".$UnidadMedida.")", $M2));
}


if($IdMedicinaTemp!=$IdMedicinaAct){
   $chart2->setTitle("Gr�fica Estad�stica de Medicina Entregada: $Nombre - $Concentracion");
   $chart2->render("imagenesGraficos/Lineas".$IdMedicinaAct.".png");
}

if($IdMedicinaTemp!=$IdMedicinaAct){
$chart2 = new LineChart(700, 350);

}

if($IdMedicinaTemp!=$IdMedicinaAct){
   $rowPu=mysql_fetch_array($respu);
}

}while($row=mysql_fetch_array($resp));

}

}//If lineas

////////////////////SI LA INFORMCION ES NUMERO DE RECETAS
if($Glineas==1 and $TipoInfo==2){
$resp=Graficacion::QueryGraficaPorMedicamentoRecetas($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);
$respT=Graficacion::QueryGraficaPorMedicamentoRecetas($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);
$respu=Graficacion::QueryGraficaPorMedicamentoRecetas2($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);

if($row=mysql_fetch_array($resp)){

$chart2 = new LineChart(700, 350);
    $rowT=mysql_fetch_array($respT);
    $rowPu=mysql_fetch_array($respu);

do{
   $rowT=mysql_fetch_array($respT);
$IdMedicinaTemp=$rowT["IdMedicina"];
	$IdMedicinaAct=$row["IdMedicina"];
$IdMedicinaPu=$rowPu["IdMedicina"];

$Nombre=$row["Nombre"];
$Presentacion=$row["FormaFarmaceutica"];
$Concentracion=$row["Concentracion"];
$M2=$row["TotalRecetas"];
//$T=$row2["Existencia"];
$mes=$row["MesNombre"];
$Divisor=$row["Divisor"];
$UnidadMedida=$row["Descripcion"];

$ano=$row["ano"];
$mes=meses::NombreMes($mes)."-".$ano;
$M2=$M2;

if($IdMedicinaPu==$IdMedicinaAct){
$chart2->addPoint(new Point($mes." (".$M2.")", $M2));
}


if($IdMedicinaTemp!=$IdMedicinaAct){
   $chart2->setTitle("Gr�fica Estad�stica de Recetas Despachadas: $Nombre - $Concentracion");
   $chart2->render("imagenesGraficos/Lineas".$IdMedicinaAct.".png");
}

if($IdMedicinaTemp!=$IdMedicinaAct){
$chart2 = new LineChart(700, 350);

}

if($IdMedicinaTemp!=$IdMedicinaAct){
   $rowPu=mysql_fetch_array($respu);
}

}while($row=mysql_fetch_array($resp));

}

}//If lineas


//////////////////////////////////////////
//****LINEAS FIN

if(isset($_GET["Print"])){
   $style='style="border:solid;"';
}else{$style="";}

?>

<table align="center" <?php echo $style;?> >
<?php if(isset($_GET["Print"])){ 
echo '<tr><td align="right"><div id="nav"><input type="button" id="Imprimir" value="IMPRIMIR GRAFICOS" onclick="window.print();">&nbsp;&nbsp;&nbsp;<input type="button" id="Cerrar" value="CERRAR" onclick="window.close();"></div></td></tr>';
} ?>
<tr><td align="center">

<?php 
if($Gpastel==1){ 
    $respuesta=Graficacion::QueryGraficaPorMedicamento2($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);

    if($row2=mysql_fetch_array($respuesta)){
	do{ 
	    echo '<img src="imagenesGraficos/Pastel'.$row2["IdMedicina"].'.png" style="border: 1px solid gray;"/> <br><br>';
	
	}while($row2=mysql_fetch_array($respuesta)); 

    }else{
	echo "<div id='resp' align='center'><h3>No hay datos para ser graficados</h3></div>";
    } //if array
	
}?>

</td></tr>
</table>
<p>

<table>
<tr><td align="center">
<?php if($Gbarras==1){
    $respuesta=Graficacion::QueryGraficaPorMedicamento2($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);

    if($row2=mysql_fetch_array($respuesta)){
	do{ 
	    echo '<img src="imagenesGraficos/Barras'.$row2["IdMedicina"].'.png" style="border: 1px solid gray;"/> <br><br>';
	
	}while($row2=mysql_fetch_array($respuesta)); 

    }else{
	echo "<div id='resp' align='center'><h3>No hay datos para ser graficados</h3></div>";
    } //if array

}?>

</td>
</tr>

</table>
<p>

<table>
<tr><td align="center">
<?php if($Glineas==1){
    $respuesta=Graficacion::QueryGraficaPorMedicamento2($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin);

    if($row2=mysql_fetch_array($respuesta)){
	do{ 
	    echo '<img src="imagenesGraficos/Lineas'.$row2["IdMedicina"].'.png" style="border: 1px solid gray;"/> <br><br>';
	
	}while($row2=mysql_fetch_array($respuesta)); 

    }else{
	echo "<div id='resp' align='center'><h3>No hay datos para ser graficados</h3></div>";
    } //if array
}?>
 </td></tr>

</table>
<?php 
conexion::desconectar();


?>
</body>

</html>
