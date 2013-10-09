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
conexion::conectar();//conecta a la DB
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../../default.css" media="screen" />
<title>...:::Consumo de Medicamentos por Grupo Terapeutico:::...</title>
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
	left:895px;
	top:42px;
	width:52px;
	height:20px;
	z-index:6;
}
#Layer31 {
	position:absolute;
	left:662px;
	top:2px;
	width:320px;
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
?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
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
$FechaInicio=$_REQUEST["FechaInicio"];
$FechaFin=$_REQUEST["FechaFin"];


$resp=mysql_query("select concat_ws('-',day('$FechaInicio'),month('$FechaInicio'),year('$FechaInicio'))as fechaInicio, concat_ws('-',day('$FechaFin'),month('$FechaFin'),year('$FechaFin'))as fechaFin");
$Fechas=mysql_fetch_array($resp);
$FechaInicio2=$Fechas["fechaInicio"];$FechaFin2=$Fechas["fechaFin"];
?>
<div id="Layer11">
  <table width="988">
      <tr class="MYTABLE">
      <td colspan="10" align="center">HOSPITAL NACIONAL ROSALES<br>
REPORTE: EXISTENCIAS DE MEDICAMENTOS <br>
<br>
PERIODO: <?php echo"$FechaInicio2";?> AL <?php echo"$FechaFin2";?> .- <br>
<div align="left">Fecha de Emisi&oacute;n: 
  <?php 
$DateNow=date("d-m-Y");
echo"$DateNow";?></div></td>
    </tr>
  </table>
 <?php
//*****QUERY PARA OBTENCION DE AREAS POR FARMACIA
//****************

?>
<form action="" method="post" name="formulario">
<input id="fechaInicio" name="fechaInicio" type="hidden" value="<?php echo"$FechaInicio";?>">
<input id="fechaFin" name="fechaFin" type="hidden" value="<?php echo"$FechaFin";?>">
<input id="IdFarmacia" name="IdFarmacia" type="hidden" value="<?php echo"$IdFarmacia";?>">

<div id="Layer31" align="right">
<table width="203">
<tr><td>&nbsp;</td><td>
  <input type="button" name="imprimir" value="Vista Previa" onClick="javascript:popUp('reporteTotal_print.php?fechaInicio='+ document.formulario.fechaInicio.value +'&fechaFin='+ document.formulario.fechaFin.value);" onMouseOver="this.style.color='#CC0000';Tip('Vista Previa e Impresi&oacute;n <img src=\'../../images/preview.JPG\'><br> del Reporte',TEXTALIGN,'justify')" onMouseOut="this.style.color='#000000'; UnTip()" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099">
</td></tr>
<tr><td><input type="text" id="NombreArchivo" name="NombreArchivo" maxlength="50" size="15" value="NombreArchivo" onFocus="if(this.value=='NombreArchivo'){this.value=''}" onBlur="if(this.value==''){this.value='NombreArchivo'}"></td>
<td>
<input type="button" name="Exportar" value="Exportar Excel" onClick="javascript:popUp('ReporteExcelTotal.php?fechaInicio='+ document.formulario.fechaInicio.value +'&fechaFin='+ document.formulario.fechaFin.value+'&nombreArchivo='+ document.formulario.NombreArchivo.value);" onMouseOver="this.style.color='#CC0000';Tip('Exportar a Excel<br><img src=\'../../images/excel.GIF\'>',TEXTALIGN,'center')" onMouseOut="this.style.color='#000000'; UnTip()" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099">
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
$nombreTera=$query->NombreTera(0);
?>
	<table width="989">
<?php
while($grupos=mysql_fetch_array($nombreTera)){//While GrupoTera
	$NombreTerapeutico=$grupos["GrupoTerapeutico"];
	$IdTerapeutico=$grupos["IdTerapeutico"];
	if($NombreTerapeutico!="--"){//para los grupos terapeuticos
		
?>
    <tr class="MYTABLE">
      <td colspan="10" align="center"><P>
&nbsp;<strong><?php echo"$NombreTerapeutico";?></strong></td>
    </tr>
	    <tr class="FONDO2">
    <th width="70" scope="col" align="center">Codigo</th>
      <th width="217" scope="col" align="center">Medicamento</th>
      <th width="102" scope="col" align="center">Concen.</th>
      <th width="100" scope="col" align="center">Prese.</th>
      <th width="100" scope="col" align="center">Unidad Medida</th>
      <th width="228" scope="col" align="center">Existencia/Lote</th>
      <th width="102" scope="col" align="center">Consumo</th>
      <th width="133" scope="col" align="center">Cobertura [Meses]</th>
    </tr>
<?php
//*****Verificacion de numero de datos, asi se rompe el lazo para evitar impresiones de datos no existentes}
	$DataMedicamento=$query->MedicinaPorGrupoTotal($IdTerapeutico);
	while($RowMedicina=mysql_fetch_array($DataMedicamento)){//While Medicina AQUI VOY
	$IdMedicina=$RowMedicina["IdMedicina"];
    $codigoMedicina=$RowMedicina["Codigo"];
    $NombreMedicina=$RowMedicina["Nombre"];
    $concentracion=$RowMedicina["Concentracion"];
    $presentacion=$RowMedicina["FormaFarmaceutica"];
    $TipoMedida=$RowMedicina["Descripcion"];
    
	$TotalExistencias=0;
	//$consumo=0;

		/*  TOTAL DE EXISTENCIAS DE MEDICAMETO  */
        
		$ExistenciaBodega=$query->MedicinaPorArea($IdMedicina,1);
        $ExistenciaConExt=$query->MedicinaPorArea($IdMedicina,2);
        
        $ExistenciaTotal=$ExistenciaBodega+$ExistenciaConExt;
        /******************************************/

		$existencias=0;
        

        /***********   OBTENCION DE CONSUMOS    ************/
		
		$consumo=$query->MedicinaEntregadaTotal($IdMedicina,$FechaInicio,$FechaFin);

		/***************************************************/
		
		if($codigoMedicina!=""){
				
			?>

    <tr class="FONDO2">
      <td align="center">&nbsp;<?php echo $codigoMedicina;?></td>
      <td align="center" style="vertical-align:middle;">&nbsp;<?php echo $NombreMedicina;?></td>
      <td align="center">&nbsp;<?php echo $concentracion;?></td>
      <td align="center">&nbsp;<?php echo $presentacion;?></td>
      <td align="center"><?php echo $TipoMedida;?></td>
      <?php 
      /*ANALIZAR POSIBLE CAMBIO DE FUNCION PARA LA OBTENCION DE LOS LOTES */
	  $respLotes=queries::ObtenerLotesExistenciasTotal($IdMedicina,1);
	  ?>
	  <td align="center"><?php
	  while($rowLote=mysql_fetch_array($respLotes)){
          /*    IMPRESION DE LOTES Y EXISTENCIAS DEL MEDICAMENTO    */
	  
	  $Lote=$rowLote["Lote"];
	  $mes=$rowLote["mes"];
	  $ano=$rowLote["ano"];
	  $mes=meses::NombreMes($mes);
	  $fechaVentto=$mes;
      $IdLote=$rowLote["IdLote"];
      $Divisor=$rowLote["UnidadesContenidas"];
	  	if($Divisor==0 or $Divisor==NULL){$Divisor=1;}
      
      $Existencia=0;
	  /*    OBTENCION DE EXISTENCIAS TOTALES DE MEDICAMENTO POR LOTE    */
      $ExistenciaBodegaLote=queries::ExistenciaLotesTotal($IdLote,1);
      $ExistenciaConExtLote=queries::ExistenciaLotesTotal($IdLote,2);
	        $Existencia=$ExistenciaBodegaLote+$ExistenciaConExtLote;
            $Existencia=$Existencia/$Divisor;
      /*****************************************************************/
	  echo "Existencias: ".$Existencia."<br>Lote: ".$Lote."<br>Fecha de Vencimiento: ".$fechaVentto."/".$ano."<br><br>";

	  }//fin de while lotes?>
	  </td>
	  
      <td align="center">&nbsp;<?php echo $consumo/$Divisor;?></td>
      <td align="center">&nbsp;
<?php 
        $datos=queries::TransferenciasTotales($IdMedicina,$consumo,$ExistenciaTotal);
        $ConsumoAproximado=$datos[0];
        $Transferencia=$datos[1];
        $CoberturaEstimada=$datos[2];

      if($consumo!=0){
        echo "Cobertura Estimada en Meses: ".$CoberturaEstimada."<br>";
        echo "Consumo Aproximado: ".$ConsumoAproximado/$Divisor."<br>";
        echo "Cantidad a ser Tranferida: ".$Transferencia/$Divisor." Aprox.";
      }else{
        echo "aun sin consumo <br>";
        echo "Cantidad a ser Tranferido.".$Transferencia/$Divisor." Aprox.<br>";
        echo "por vencimiento";
      }
?>
     
      </td>
    </tr>
<?php 
		}//IF codigoMedicina
		
	}//While Medicina
	
	?>   
     <tr class="MYTABLE">
      <td colspan="10">&nbsp;
	  </td>
    </tr>
<?php  


}//IF NombreTerapeutico!=--

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