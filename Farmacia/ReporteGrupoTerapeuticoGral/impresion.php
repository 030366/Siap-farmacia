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
<style type="text/css">
#Layer11 {
	position:absolute;
	left:0px;
	top:0px;
	width:1015px;
	height:232px;
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
html { font: 9pt Arial, Helvetica, sans-serif; }
#Layer61, #Layer21, #Layer31 { display: none; }
#nav, #nav2, #about { display: none; }
#footer { display:none;}
#span{ color:#FFFFFF}
@page { size: 8.5in 11in; margin: 0.5cm }
/*P{page-break-after:inherit}*/
}
</style>
<script src="../PrintReport.js" language="javascript"></script>
</head>
<body>
<script language="javascript" src="../../tooltip/wz_tooltip.js"></script>
<?php 
$FechaInicio=explode('-',$_REQUEST["fechaInicio"]);
$FechaFin=explode('-',$_REQUEST["fechaFin"]);
$FechaInicio2=$FechaInicio[2].'-'.$FechaInicio[1].'-'.$FechaInicio[0];
$FechaFin2=$FechaFin[2].'-'.$FechaFin[1].'-'.$FechaFin[0];
$FechaInicio=$_REQUEST["fechaInicio"];
$FechaFin=$_REQUEST["fechaFin"];

$IdArea=$_REQUEST["area"];
$area=$_REQUEST["NomArea"];
?>
<div id="Layer31" align="right">
	<input type="button" id="imprimir" name="imprimir" value="IMPRIMIR" onClick="ImprimirReporte(this.form);" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099">
</p>
	<input type="button" id="cerrar" name="cerrar" value="CERRAR" onClick="javascript:self.close()" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099">	
</div>

<div id="Layer11">

  <table width="967" cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td colspan="10" align="center">HOSPITAL NACIONAL ROSALES<br>
        <strong>CONSUMO DE MEDICAMENTOS POR GRUPO TERAPEUTICO</strong> <br>
        &Aacute;rea: <strong><?php echo $area;?></strong><br>
        PERIODO DEL: <?php echo"$FechaInicio2";?> AL <?php echo"$FechaFin2";?> .- <br>
        <div align="left">Fecha de Emisi&oacute;n:
          <?php 
$DateNow=date("d/m/Y");
echo"$DateNow";?>
        </div></td>
    </tr>
  </table>
  <?php
//*****FILTRACION DE MEDICINA Y GRUPOS  Y FECHAS
$grupoTerapeutico=$_REQUEST["select1"];
if(isset($_REQUEST["select2"])){$medicina=$_REQUEST["select2"];}else{$medicina=0;}

//*************************************
//******************************* QUERIES Y RECORRIDOS?>
<table width="968">
<?php
		//Costo Total de la sumatoria de costos por grupos terapeutico
		$Total=0;
//*************************************
//******************************* QUERIES Y RECORRIDOS
$nombreTera=$query->NombreTera($grupoTerapeutico);
while($grupos=mysql_fetch_array($nombreTera)){
$NombreTerapeutico=$grupos["GrupoTerapeutico"];
$IdTerapeutico=$grupos["IdTerapeutico"];
if($NombreTerapeutico!="--"){

$resp=QueryExterna($IdTerapeutico,$medicina,$IdArea,$FechaInicio,$FechaFin);
if($row=mysql_fetch_array($resp)){
	//Subtotal es el costo por grupo terapeutico
	$SubTotal=0;
?>
	
    <tr class="MYTABLE">
      <td colspan="11" align="center"><P>
&nbsp;<strong><?php echo"$NombreTerapeutico";?></strong></td>
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
      <?php /*<th width="135" scope="col">Consumo/Lote</th>*/?>
      <th width="136" scope="col">Monto</th>
    </tr>
	<?php
//$resp1=QueryExterna($IdTerapeutico,$medicina,$IdArea,$FechaInicio,$FechaFin);

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
	?>

    <tr class="FONDO2">
      <td>&nbsp;<?php echo $codigoMedicina;?></td>
      <td align="center" style="vertical-align:middle">&nbsp;<?php echo $NombreMedicina;?></td>
      <td>&nbsp;<?php echo $concentracion;?></td>
      <td>&nbsp;<?php echo $presentacion;?></td>
	  <td align="center">&nbsp;<?php echo $Nrecetas;?></td>
      <td align="center">&nbsp;<?php echo $sat;?></td>
      <td align="center">&nbsp;<?php echo $insat;?></td>
	  <td align="center"><?php echo $UnidadMedida;?></td>
      <td align="center"><?php echo $CantidadReal;?></td>
	  <td align="right"><?php echo $PrecioNuevo;?></td>
      <td align="right"><?php echo $MontoNuevo;?></td>
    </tr>
<?php	
		}//if row2
	}while($row=mysql_fetch_array($resp));//while de la informacion del medicamento
	$Total+=$SubTotal;
?>     
    <tr class="FONDO2">
      <td colspan="9">&nbsp;</td>
      <td align="right"><strong><em>SubTotal:</em></strong></td>
      <td align="right"><strong><?php echo number_format($SubTotal,2,'.',',');?></strong></td>
    </tr>


  
<?php
	}//nuevo IF test del medicamento
}//IF NombreTerapeutico!=--
}//while de grupos terapeuticos?>
    <tr class="MYTABLE">
      <td colspan="9">&nbsp;</td>
	  <td align="right"><em><strong>Total:</strong></em></td>
	  <td align="right"><strong><?php echo number_format(round($Total,2),2,'.',',');?></strong></td>
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