<?php 
$nombreArchivo=$_GET["nombreArchivo"];
header("Pragma: no-cache");
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$nombreArchivo.xls");
header("Expires: 0");

require('../../Clases/class.php');
$query=new queries;
conexion::conectar();

$FechaInicio=$_REQUEST["fechaInicio"];
$FechaFin=$_REQUEST["fechaFin"];


?>
  <table width="988">
      <tr class="MYTABLE">
      <td colspan="8" align="center">HOSPITAL NACIONAL ROSALES<br>
REPORTE: EXISTENCIAS DE MEDICAMENTOS <br>

PERIODO: <?php echo"$FechaInicio";?> AL <?php echo"$FechaFin";?> .- <br>
<div align="right">Fecha de Emisi&oacute;n: 
  <?php 
$DateNow=date("d-m-Y");
echo"$DateNow";?></div></td>
    </tr>
  </table>
 <?php
//*****QUERY PARA OBTENCION DE AREAS POR FARMACIA
//****************
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
      <td colspan="8" align="center" style="background:#CCCCCC;">
	  <strong><?php echo"$NombreTerapeutico";?></strong></td>
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
	$consumo=0;

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
      <td nowrap align="center" style="vertical-align:middle;"><?php echo $codigoMedicina;?></td>
      <td nowrap align="center" style="vertical-align:middle;"><?php echo $NombreMedicina;?></td>
      <td nowrap align="center" style="vertical-align:middle;"><?php echo $concentracion;?></td>
      <td nowrap align="center" style="vertical-align:middle;"><?php echo $presentacion;?></td>
      <td nowrap align="center" style="vertical-align:middle;"><?php echo $TipoMedida;?></td>
      <?php 
      /*ANALIZAR POSIBLE CAMBIO DE FUNCION PARA LA OBTENCION DE LOS LOTES */
	  $respLotes=queries::ObtenerLotesExistenciasTotal($IdMedicina,1);
	  ?>
	  <td nowrap align="center"><?php
	  while($rowLote=mysql_fetch_array($respLotes)){
          /*    IMPRESION DE LOTES Y EXISTENCIAS DEL MEDICAMENTO    */
	  
	  $Lote=$rowLote["Lote"];
	  $mes=$rowLote["mes"];
	  $ano=$rowLote["ano"];
	  $mes=meses::NombreMes($mes);
	  $fechaVentto=$mes;
      $IdLote=$rowLote["IdLote"];
      $Divisor=$rowLote["UnidadesContenidas"];
      
      $Existencia=0;
	  /*    OBTENCION DE EXISTENCIAS TOTALES DE MEDICAMENTO POR LOTE    */
      $ExistenciaBodegaLote=queries::ExistenciaLotesTotal($IdLote,1);
      $ExistenciaConExtLote=queries::ExistenciaLotesTotal($IdLote,2);
	        $Existencia=$ExistenciaBodegaLote+$ExistenciaConExtLote;
            $Existencia=$Existencia/$Divisor;
      /*****************************************************************/
	  echo "Existencias: ".$Existencia."<br>Lote: ".$Lote."<br>Fecha de Vencimiento: ".$fechaVentto."/".$ano."<br><br>";

	  }//fin de while lotes?></td>
	  
      <td nowrap align="center" style="vertical-align:middle;"><?php echo $consumo/$Divisor;?></td>
      <td nowrap align="center" style="vertical-align:middle;"><?php 
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
?></td>
    </tr>
<?php 
		}//IF codigoMedicina
		
	}//While Medicina
	
	?>   
     <tr class="MYTABLE">
      <td nowrap colspan="8">&nbsp;
	  </td>
    </tr>
<?php  


}//IF NombreTerapeutico!=--

}//while de nombreTera?>
  </table>
<?php  
conexion::desconectar();
?>