<?php 
$nombreArchivo=$_GET["nombreArchivo"];
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$nombreArchivo.xls");
header("Pragma: no-cache");
header("Expires: 0");

require('../../Clases/class.php');
$query=new queries;
conexion::conectar();

$FechaInicio=explode('-',$_REQUEST["fechaInicio"]);
$FechaFin=explode('-',$_REQUEST["fechaFin"]);
$FechaInicio2=$FechaInicio[2].'-'.$FechaInicio[1].'-'.$FechaInicio[0];
$FechaFin2=$FechaFin[2].'-'.$FechaFin[1].'-'.$FechaFin[0];
$FechaInicio=$_REQUEST["fechaInicio"];
$FechaFin=$_REQUEST["fechaFin"];

$IdArea=$_REQUEST["area"];
$area=$_REQUEST["NomArea"];
?>
  <table width="968" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="11" align="center">HOSPITAL NACIONAL ROSALES<br>
        <strong>CONSUMO DE MEDICAMENTOS POR GRUPO TERAPEUTICO</strong> <br>
        &Aacute;rea: <strong><?php echo $area;?></strong><br>
        PERIODO DEL: <?php echo"$FechaInicio2";?> AL <?php echo"$FechaFin2";?> .- <br>
        <div align="right">Fecha de Emisi&oacute;n:
          <?php 
$DateNow=date("d-m-Y");
echo"$DateNow";?>
        </div></td>
    </tr>
  </table>
  <?php
//*****FILTRACION DE MEDICINA Y GRUPOS  Y FECHAS
$grupoTerapeutico=$_REQUEST["select1"];
if(isset($_REQUEST["select2"])){$medicina=$_REQUEST["select2"];}else{$medicina=0;}
//******************************* QUERIES Y RECORRIDOS
$nombreTera=$query->NombreTera($grupoTerapeutico);?>
<table width="968" border="1" cellpadding="0.5" cellspacing="0.5">
<?php
while($grupos=mysql_fetch_array($nombreTera)){
$NombreTerapeutico=$grupos["GrupoTerapeutico"];
$IdTerapeutico=$grupos["IdTerapeutico"];
if($NombreTerapeutico!="--"){

$resp=$query->QueryExterna($IdTerapeutico,$medicina,$IdArea,$FechaInicio,$FechaFin);
if($test=mysql_fetch_array($resp)){?>
    <tr>
      <td colspan="11" align="center" style="background:#CCCCCC;"><P>
&nbsp;<strong><?php echo"$NombreTerapeutico";?></strong></td>
    </tr>
	    <tr>
    <th width="57" scope="col">Codigo</th>
      <th width="164" scope="col">Medicamento</th>
      <th width="67" scope="col">Concen.</th>
      <th width="53" scope="col">Prese.</th>
	  <th width="72">Recetas</th>
      <th width="72" scope="col">Recetas</th>
      <th width="54" scope="col">Satis.</th>
      <th width="58" scope="col">Unidade de Medida.</th>
      <th width="72" scope="col">Consumo</th>
      <th width="142" scope="col">Consumo/Lote</th>
      <th width="118" scope="col">Monto[$]</th>
    </tr>
	<?php
$resp1=$query->QueryExterna($IdTerapeutico,$medicina,$IdArea,$FechaInicio,$FechaFin);
	while($row=mysql_fetch_array($resp1)){
$GrupoTerapeutico=$IdTerapeutico;
$Medicina=$row["IdMedicina"];
$codigoMedicina=$row["Codigo"];
$NombreMedicina=$row["Nombre"];
$concentracion=$row["Concentracion"];
$presentacion=$row["FormaFarmaceutica"];
$Nrecetas=0;//conteo de recetas
$consumo=0;
$respuesta=$query->ObtenerReporteGrupoTerapeutico($GrupoTerapeutico,$Medicina,$FechaInicio,$FechaFin,$IdArea);
	$Nrecetas=mysql_num_rows($respuesta);
		if($row2=mysql_fetch_array($respuesta)){
$precioActual=$row2["PrecioActual"];
$respuesta2=$query->ObtenerReporteGrupoTerapeutico($GrupoTerapeutico,$Medicina,$FechaInicio,$FechaFin,$IdArea);  
$row3=mysql_fetch_array($respuesta2);
//IdReceta
$IdReceta=$row3["IdReceta"];
$IdHistorialClinico=$row3["IdHistorialClinico"];
$Divisor=$row3["Divisor"];//Divisor de conversion
$UnidadMedida=$row3["Descripcion"];//Tipo de unidad de Medida
$satisfechas=0;
$insatisfechas=0;
$respLotes=queries::ObtenerConsumosMedicamentoLote($Medicina,$IdArea,$FechaInicio,$FechaFin);
$Cantidad_1=0;$Cantidad_2=0;$Monto_Total=0;$Monto_Total2=0;$Lote=array();$Lote2_=array();$valor=0;$Cantidad2_=array();$CantidadUnidadMedida=array();$CantidadUnidadMedida2=array();$Cantidad=array();

$i=0;//Posicion inicial de los vectores
$j=0;
			while($rowLotes=mysql_fetch_array($respLotes)){//OBTENGO LOTES RECETAS ETC
				$Cantidad1=$rowLotes["TotalLote1"];
				$Lote1=$rowLotes["Lote1"];
				
				$Cantidad2=$rowLotes["TotalLote2"];
				$Lote2=$rowLotes["Lote2"];
									if($Lote1!=NULL and $Cantidad1!=NULL){
										$Lote[$i]=$Lote1;
										switch($i){
											case 0://Posicion inicial del vector
													$Cantidad[$i]=$Cantidad1;
											break;								
											default://posicion mayor que cero para verificacion de lotes iguales
											$ancla=count($Lote2_);
											if($ancla!=0){
												for($x=0;$x<$ancla;$x++){
													if($Lote2_[$x]==$Lote[$i]){
														$valor=$x+1;
														break;
													}
												}//for
											}
												$TamanoLote=count($Lote);
												if($valor!=0){	
												$valor=$valor-1;
												$Cantidad_nueva=$Cantidad1+$Cantidad2_[$valor];
													$Cantidad[$i]=$Cantidad_nueva;
													$Cantidad2_[$valor]=0;
													$Lote2_[$valor]=0;
													$valor=0;
												}elseif($Lote[$i-1]!=$Lote[$i]){ 
													
														$Cantidad[$i]=$Cantidad1; 
													 
												}else{
													$Cantidad_nueva=$Cantidad1+$Cantidad[$i-1];
													$Cantidad[$i-1]=$Cantidad_nueva;
													$Cantidad[$i]='';
													$Lote[$i]='';
													$i-=1;//Decremento de $i para reutilizar la posicion que esta en blanco
												}
											break;
										}//switch
										$Cantidad_1=$Cantidad_1+$Cantidad1;//Suma de cantidades de medicamento entregados satisfechos
									}//Fin IF Lote1 != NULL
									else{
									 $i=-1;   
									}
							///**********************************************************/////		
									if($Cantidad2!=NULL and $Lote2!=NULL and $Cantidad2!=0 and $Lote2!=0){
										$Lote2_[$j]=$Lote2;
										$Cantidad2_[$j]=0;
										switch($j){
											case 0://Posicion inicial del vector
													$Cantidad2_[$j]=$Cantidad2;
											break;								
											default://posicion mayor que cero para verificacion de lotes iguales
											$ancla=count($Lote2_);
											for($x=0;$x<$ancla;$x++){
												if($Lote2_[$x]==$Lote2_[$j]){
													$valor=$x;
													
												}else{
													$valor2='NO';
												}
											}//for
											
												if($valor2=='NO'){			
													$Cantidad2_[$j]=$Cantidad2;
												}else{
													$Cantidad2_nueva=$Cantidad2+$Cantidad2_[$valor];
													$Cantidad2_[$valor]=$Cantidad2_nueva;
													$Cantidad2_[$j]='';
													$Lote2_[$j]='';
													$j-=1;//Decremento de $i para reutilizar la posicion que esta en blanco
												}
											
											break;
										}//switch
										$Cantidad_2+=$Cantidad2;
									$j++;
									}//Swith LOTES 2
						$i++;//aumento de la posicion del vector
						
			}//while lotes
            
$tope=count($Cantidad);//Obtencion de posiciones totales del vector
for($i=0;$i<$tope;$i++){
$LoteActual=$Lote[$i];//Se obtiene el Lote
$PrecioLote[$i]=queries::ObtenerPrecioLote($LoteActual);//Obtencion del precio con el que entra ese lote
}

$tope2=count($Cantidad2_);//Obtencion de posiciones totales del vector
if($tope2!=0 || $tope2!=NULL){
	for($j=0;$j<$tope2;$j++){
		if($Lote2_!=0){
			$LoteActual=$Lote2_[$j];//Se obtiene el Lote
			$PrecioLote2[$j]=queries::ObtenerPrecioLote($LoteActual);//Obtencion del precio con el que entra ese lote
		}
	}//fin de for
}//if tope2

/*Obtencion de recetas satifechas e insatisfechas globales parametros ...,0,0)*/
$sat=$query->ObtenerRecetasSatisfechas($IdReceta,$Medicina,$FechaInicio,$FechaFin,$IdArea,0,0);
$insat=$query->ObtenerRecetasInsatisfechas($IdReceta,$Medicina,$FechaInicio,$FechaFin,$IdArea,0,0);
//***********
$cantidad=$row3["Cantidad"];

$VerSatisfecha=$query->verificaSatisfecha($Medicina,$IdReceta,$IdHistorialClinico);
if($vector=mysql_fetch_array($VerSatisfecha)){$consumo=$consumo+$cantidad;}
			
$costo=$consumo*$precioActual;//}//while row3
$Cantidad_Total=$Cantidad_1+$Cantidad_2;//CANTIDAD TOTAL DE RECETAS SATIS. Y NO SATIS.
	?>

    <tr>
      <td nowrap style="vertical-align:middle;"><?php echo"$codigoMedicina";?></td>
      <td nowrap align="center" style="vertical-align:middle;"><?php echo"$NombreMedicina";?></td>
      <td nowrap style="vertical-align:middle;"><?php echo"$concentracion";?></td>
      <td nowrap style="vertical-align:middle;"><?php echo $presentacion;?></td>
      <td nowrap align="center" style="vertical-align:middle;"><?php echo $Nrecetas;?></td>
      <td nowrap align="center" style="vertical-align:middle;"><?php echo $sat;?></td>
      <td nowrap align="center" style="vertical-align:middle;"><?php echo $insat;?></td>
	  <td nowrap align="center" style="vertical-align:middle;"><?php echo $UnidadMedida;?></td>
      <td nowrap align="center" style="vertical-align:middle;"><?php echo $Cantidad_Total/$Divisor;?></td>
	  <td nowrap align="center" style="vertical-align:middle;">&nbsp;
	  <?php 
	  $Cantidad_de_Lote=0;$j=1;//Comparacion de igualdad

	  for($i=0;$i<$tope;$i++){
          if($PrecioLote[$i]!=NULL){
		  $CodigoLote=queries::CodigoLote($Lote[$i]);
		  $CantidadUnidadMedida[$i]=$Cantidad[$i]/$Divisor;
		  	echo"Cantidad: ".$CantidadUnidadMedida[$i]."<br>Precio($): ".$PrecioLote[$i]."<br>Lote: ".$CodigoLote."<br><br>";
          }
	  }//fin de for
	  
	  if($tope2!=0 || $tope2!=NULL){
		  for($j=0;$j<$tope2;$j++){
				  if($Lote2_[$j]!=0){
		  $CodigoLote=queries::CodigoLote($Lote2_[$j]);
		  $CantidadUnidadMedida2[$j]=$Cantidad2_[$j]/$Divisor;
		  
				echo"Cantidad: ".$CantidadUnidadMedida2[$j]."<br>Precio($): ".$PrecioLote2[$j]."<br>Lote: ".$CodigoLote."<br>";
			  }
		  }//fin de for
	  }//if tope2
	  ?>
	 </td>
      <td align="center" style="vertical-align:middle;"><?php 
	  $tope=count($CantidadUnidadMedida);
	  	for($i=0;$i<$tope;$i++){
		  $Monto=round($CantidadUnidadMedida[$i]*$PrecioLote[$i],2);
		  $Monto_Total=$Monto_Total+$Monto;
	 	}//fin de for	  
	  
	  $tope2=count($CantidadUnidadMedida2);
		for($j=0;$j<$tope2;$j++){
			if($Cantidad2_[$j]!=0){
				$Monto2=round($CantidadUnidadMedida2[$j]*$PrecioLote2[$j],2);
		  	}else{
				$Monto2=0;
			}
		  $Monto_Total2=$Monto_Total2+$Monto2;
	 	}//fin de for	  
	  
	  echo $Monto_Total+$Monto_Total2;
	  ?>&nbsp;</td>
    </tr>
<?php	
		$Cantidad=array();$Cantidad2_=array();
		$PrecioLote=array();$PrecioLote2=array();
		$Lote=array();$Lote2_=array();
		}//if row2
	}//while externo

	}//nuevo IF test "Si hay datos a mostrar"
}//IF NombreTerapeutico!=--

 }//while de nombreTera
 ?>     
</table>
<?php 
conexion::desconectar();
?>
