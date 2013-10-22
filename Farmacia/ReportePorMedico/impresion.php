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
	width:1018px;
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
	left:852px;
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
//****FILTRACION
$IdArea=$_REQUEST["area"];
$area=$_REQUEST["NomArea"];
$IdSubEspecialidad=$_REQUEST["select1"];
$IdMedico=$_REQUEST["select2"];//IdMedico si no es seleccinado siempre es Cero
if($IdMedico!='0'){

$MedResp=mysql_query("select NombreEmpleado from mnt_empleados where IdEmpleado='$IdMedico'");
$MedRow=mysql_fetch_array($MedResp);
$NomMed=$MedRow["NombreEmpleado"];

}else{$NomMed="";}
if(isset($_REQUEST["select3"])){$IdMedicina=$_REQUEST["select3"];}else{$IdMedicina=0;}
//****FIN FILTRACION

$FechaInicio=explode('-',$_REQUEST["fechaInicio"]);
$FechaFin=explode('-',$_REQUEST["fechaFin"]);
$FechaInicio2=$FechaInicio[2].'-'.$FechaInicio[1].'-'.$FechaInicio[0];
$FechaFin2=$FechaFin[2].'-'.$FechaFin[1].'-'.$FechaFin[0];
$FechaInicio=$_REQUEST["fechaInicio"];
$FechaFin=$_REQUEST["fechaFin"];

$resp=mysql_query("select NombreSubEspecialidad from mnt_subespecialidad where IdSubEspecialidad='$IdSubEspecialidad'");
$RowEsp=mysql_fetch_array($resp);
$NomEsp=$RowEsp[0];

?>
<div id="Layer31" align="right">
	<input type="button" id="imprimir" name="imprimir" value="IMPRIMIR" onClick="javascript:print();" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099">
	</p>
	<input type="button" id="cerrar" name="cerrar" value="CERRAR" onClick="javascript:self.close()" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099">	
</div>
	

  <table width="984" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="11" align="center">HOSPITAL NACIONAL ROSALES<br>      <strong>CONSUMO DE MEDICAMENTOS POR ESPECIALIDADES</strong> <br>
        &Aacute;REA:&nbsp;&nbsp;<strong><?php echo $area;?></strong><br>
        SERVICIO/ESPECIALIDAD:&nbsp;&nbsp;<strong><?php echo $NomEsp;?></strong><br>
        <?php if($NomMed!=""){echo "MEDICO:&nbsp;&nbsp;<strong>".$NomMed."</strong><br>";}?>
        PERIODO: <?php echo $FechaInicio2;?> AL <?php echo $FechaFin2;?> .- <br>
        <div align="right">Fecha de Emisi&oacute;n:
          <?php 
$DateNow=date("d/m/Y");
echo"$DateNow";?>
        </div></td>
    </tr>
  </table>

<!-- CODIGO PHP PARA REPORTE -->
<table width="968" border="1">
<?php
			$Total=0;
//*************************************
//******************************* QUERIES Y RECORRIDOS
$nombreTera=GrupoTerapeutico();
while($grupos=mysql_fetch_array($nombreTera)){
	$NombreTerapeutico=$grupos["GrupoTerapeutico"];
	$IdTerapeutico=$grupos["IdTerapeutico"];

//*****Verificacion de numero de datos, asi se rompe el lazo para evitar impresiones de datos no existentes
$respuesta=ObtenerReporteEspecialidades($IdTerapeutico,$IdMedicina,$FechaInicio,$FechaFin,$IdSubEspecialidad,$IdMedico,$IdArea);
	
if($row=mysql_fetch_array($respuesta)) {
//***************
			$SubTotal=0;
?>
    <tr class="MYTABLE" style="background:#999999;">
      <td colspan="11" align="center"><P>
&nbsp;<strong><?php echo $NombreTerapeutico;?>&nbsp;&nbsp;&nbsp;</strong></td>
    </tr>
	    <tr class="FONDO2">
    <th width="37" scope="col">Codigo</th>
      <th width="199" scope="col">Medicamento</th>
      <th width="85" scope="col">Concen.</th>
      <th width="71" scope="col">Prese.</th>
      <th width="82" scope="col">Recetas</th>
      <th width="54" scope="col">Satis.</th>
      <th width="86" scope="col">No Satis.</th>
	  <th width="63">Unidad de Medida</th>
      <th width="74" scope="col">Consumo</th>
      <th width="134" scope="col">Precio[$]</th>
      <th width="102" scope="col">Monto</th>
    </tr>
<?php

do{

$Medicina=$row["IdMedicina"];


/*************      MANEJO DE LOTES Y ORDENAMIENTO LOGICO      ***************/
$satisfechas=0;
$insatisfechas=0;
/*  INICIALIZACION  DE  VECTORES  Y  VARIABLES  */

/* TEMPORALMENTE INHABILITADO

$respLotes=queries::ObtenerLotes($Medicina,$IdReceta,$IdArea,7,$IdSubEspecialidad,$IdMedico,$FechaInicio,$FechaFin);

$Cantidad_1=0;$Cantidad_2=0;$Monto_Total=0;$Monto_Total2=0;$Lote=array();$Lote2_=array();$valor=0;$Cantidad2_=array();$CantidadUnidadMedida=array();$CantidadUnidadMedida2=array();$Cantidad=array();$valor2='';


$i=0;//Posicion inicial de los vectores
$j=0;
			while($rowLotes=mysql_fetch_array($respLotes)){//OBTENGO LOTES RECETAS ETC
				$Cantidad1=$rowLotes["CantidadLote1"];
				$Lote1=$rowLotes["Lote1"];
				
				$Cantidad2=$rowLotes["CantidadLote2"];
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
							///**********************************************************		
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

//********* MANEJO DE LOTES OBTENCION DE LA INFORMACION *************
  
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
			
			*/
			
//****************FIN DE INFORMACION LOTES *********************


/*Obtencion de satisfehcas e insatisfechas por especialidad y/o medico*/
$TotalRecetas=ObtenerTotalRecetas($Medicina,$IdArea,$IdSubEspecialidad,$IdMedico,$FechaInicio,$FechaFin);
$sat=ObtenerRecetasSatisfechas($Medicina,$FechaInicio,$FechaFin,$IdArea,$IdSubEspecialidad,$IdMedico);
$insat=ObtenerRecetasInsatisfechas($Medicina,$FechaInicio,$FechaFin,$IdArea,$IdSubEspecialidad,$IdMedico);

//***********
/*QUERY PAR OBTENER ESTA INFORMACION*/
$row4=$query->ObtenerInfomacionMedicina($Medicina);
$codigoMedicina=$row4["Codigo"];
$NombreMedicina=$row4["Nombre"];
$concentracion=$row4["Concentracion"];
$presentacion=$row4["FormaFarmaceutica"];
$Divisor=$row4["Divisor"];//Divisor de conversion
$UnidadMedida=$row4["Descripcion"];//Tipo de unidad de Medida
//***********

//$Cantidad_Total=$Cantidad_1+$Cantidad_2;//CANTIDAD TOTAL DE MEDICINAS ENTREGADAS

	$Cantidad_Total=SumatoriaMedicamento($Medicina,$IdArea,$IdMedico,$IdSubEspecialidad,$FechaInicio,$FechaFin);
		$CantidadReal=$Cantidad_Total/$Divisor;
	$Ano=date('Y');
	$Precio=ObtenerPrecioMedicina($Medicina,$Ano);
	$Monto=$CantidadReal*$Precio;
		$PrecioNuevo=number_format($Precio,2,'.',',');
		$MontoNuevo=number_format($Monto,2,'.',',');
			$SubTotal+=$Monto;



    ?>
    <tr class="FONDO2">
      <td style="vertical-align:middle;">&nbsp;<?php echo ".$codigoMedicina";?></td>
      <td style="vertical-align:middle">&nbsp;<?php echo"$NombreMedicina";?></td>
      <td style="vertical-align:middle;">&nbsp;<?php echo"$concentracion";?></td>
      <td style="vertical-align:middle;">&nbsp;<?php echo"$presentacion";?></td>
      <td align="center" style="vertical-align:middle;">&nbsp;<?php echo $TotalRecetas;?></td>
      <td align="center" style="vertical-align:middle;">&nbsp;<?php echo $sat;?></td>
      <td align="center" style="vertical-align:middle;">&nbsp;<?php echo $insat;?></td>
	  <td align="center" style="vertical-align:middle;"><?php echo $UnidadMedida;?></td>
      <td align="center" style="vertical-align:middle;">&nbsp;<?php echo $CantidadReal;?></td>
	  <td align="right" style="vertical-align:middle;"><?php echo $PrecioNuevo;
	  
	  /*  TEMPORALMENTE INHABILITADO
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
	  
	  */
	  ?>
	  </td>
      <td align="right" style="vertical-align:middle;"><?php echo $MontoNuevo;
	  /*
	    $tope=count($CantidadUnidadMedida);
	  	for($i=0;$i<$tope;$i++){
            if($CantidadUnidadMedida[$i]!=NULL){
		  $Monto=round($CantidadUnidadMedida[$i]*$PrecioLote[$i],2);
		  $Monto_Total=$Monto_Total+$Monto;
            }
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
	  
	  echo $Monto_Total+$Monto_Total2; */ 
	  ?></td>
    </tr>
<?php	
		//$Cantidad=array();$Cantidad2_=array();
		//$PrecioLote=array();$PrecioLote2=array();
		//$Lote=array();$Lote2_=array();
		//}//if Medicina != tmpMedicina impresion de la tabla de resultados

}while($row3=mysql_fetch_array($respuesta));
   
	$Total+=$SubTotal;
?>     
    <tr class="FONDO2" style="background:#999999;">
      <td colspan="9">&nbsp;</td>
      <td align="right"><strong><em>SubTotal:</em></strong></td>
      <td align="right"><strong><?php echo number_format($SubTotal,2,'.',',');?></strong></td>
    </tr>
<?php  

   }//if de validacion de datos row



}//while de grupo terapeutico?>
    <tr class="MYTABLE" style="background:#CCCCCC;">
      <td colspan="9">&nbsp;</td>
	  <td align="right"><em><strong>Total:</strong></em></td>
	  <td align="right"><strong><?php echo number_format(round($Total,2),2,'.',',');?></strong></td>
    </tr>

  </table>


</body>
</html>
<?php
conexion::desconectar();
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel
?>