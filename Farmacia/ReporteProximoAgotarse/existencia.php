<?php session_start();
if(!isset($_SESSION["nivel"])){
echo "ERROR_SESSION";
}else{
require('../Clases/class.php');
require('IncludeMonitoreo/MonitoreoClass.php');
conexion::conectar();
$area=$_REQUEST["area"];
$farmacia=$_REQUEST["farmacia"];

$querySelectFarmacia="select mnt_farmacia.Farmacia from mnt_farmacia where mnt_farmacia.IdFarmacia='$farmacia'";
$querySelectArea="select mnt_areafarmacia.Area from mnt_areafarmacia where mnt_areafarmacia.IdArea='$area'";
$Dfarmacia=mysql_query($querySelectFarmacia);
$Darea=mysql_query($querySelectArea);
$dataFarmacia=mysql_fetch_array($Dfarmacia);
$dataArea=mysql_fetch_array($Darea);
$NomFarmacia=$dataFarmacia[0];
$NomArea=$dataArea[0];
?>
<div id="Layer1">
  <table width="100%" border="1">
<tr class="MYTABLE"><td align="center" colspan="6">&nbsp;<strong>MEDICAMENTO PROXIMO A AGOTARSE</strong></td></tr>
<tr class="MYTABLE"><td align="center" colspan="6">&nbsp;<strong>MEDICAMENTO AREA: <?php echo $NomArea;?></strong></td></tr>
<tr class="MYTABLE"><td align="right" colspan="6">&nbsp;<strong>Fecha de Reporte: <?php echo date('d-m-Y');?></strong></td></tr>
<tr class="MYTABLE"><td align="right" colspan="6"><div id="BotonesPrinter"><input type="button" id="Printer" name="Printer" value="Imprimir" onclick="Imprimir(1);"><input type="button" id="Finsh" name="Finish" value="Regresar" onclick="Imprimir(2);"></div></td></tr>
<?php

//Datos Generales ***********************************************************

$Grupo=Obtencion::ObtenerGrupos();
//***************************************************************************

//$count=0;

$conteo=0;

while($DataGrupo=mysql_fetch_array($Grupo)){
$NombreGrupo=$DataGrupo["GrupoTerapeutico"];
$IdTerapeutico=$DataGrupo["IdTerapeutico"];

$resp=Obtencion::ObtenerDetalleMedicamentoPorGrupo($farmacia,$area,$IdTerapeutico,$_SESSION["TipoFarmacia"]);


if($Datos=mysql_fetch_array($resp)){?>
<tr class="MYTABLE"><td align="center" colspan="6">&nbsp;<strong><?php echo $NombreGrupo;?></strong></td></tr>

  <tr class="MYTABLE">
    <td width="75" align="center">&nbsp;<strong>Codigo</strong></td>
    <td width="276" align="center">&nbsp;<strong>Medicamento</strong></td>
    <td width="130" align="center">&nbsp;<strong>Unidad <br>de Medida</strong></td>
    <td width="155" align="center">&nbsp;<strong>Existencia</strong></td>
    <td width="155" align="center">&nbsp;<strong>Consumo</strong><br><span style="font-size:x-small;">[Mes Anterior]</span></td>
	<td align="center"><strong>Existencia [%]</strong></td>
  </tr>
 <?php 
 
 do{
 $ConsumoMesActual=0;
 $Codigo=$Datos["Codigo"];
 $Nombre=htmlentities($Datos["Nombre"]);
 $Concentracion=$Datos["Concentracion"];
 $Forma=htmlentities($Datos["FormaFarmaceutica"].' - '.$Datos["Presentacion"]);
 $IdMedicina=$Datos["IdMedicina"];

 $Descripcion=$Datos["Descripcion"];
 $Divisor=$Datos["UnidadesContenidas"];

if($_SESSION["TipoFarmacia"]==2){
	if($farmacia!=4){
   	   $RespEx=Obtencion::ObtenerExistencias($area,$IdMedicina);
	}else{
	   $RespEx=Obtencion::ObtenerExistenciasBodega($IdMedicina);
	}
}else{
   $RespEx=Obtencion::ObtenerExistenciasBodega($IdMedicina);
}
/*OBTENCION DE PORCENTAJES*/
// if($_SESSION["TipoFarmacia"]==2){
//    $Porcentaje=Obtencion::ObtenerPorcentaje($IdMedicina,$area);
// }else{
//    $Porcentaje=Obtencion::ObtenerPorcentajeBodega($IdMedicina);
// }
// 
// if($Porcentaje[2]!=0 and $Porcentaje[2]!=""){
// $ConsumoMesPasado=round(($Porcentaje[0]/($Porcentaje[0]+$Porcentaje[2]))*100,2);
// $ConsumoMesActual=round((($Porcentaje[1]/($Porcentaje[1]+$Porcentaje[2]))*100),2);
// }else{
// $ConsumoMesPasado=0;
// }


$ConsumoCalculado=Obtencion::ObtenerConsumo($IdMedicina,$area,$farmacia,$_SESSION["TipoFarmacia"]);

if($ConsumoCalculado==""){
    $ConsumoCalculado="SM";
}

/***************/

			?>
			<tr class="FONDO" <?php if(($ConsumoMesPasado<$ConsumoMesActual and $ConsumoMesPasado!=0 and $ConsumoMesActual!=0) or ($ConsumoMesActual >= 73 and $ConsumoMesActual <= 100)){echo "style='background-color:#CC3300;'";}?> >
				<td>&nbsp;<?php echo $Codigo;?></td>
				<td>&nbsp;<?php echo $Nombre." - ".$Concentracion.'<br>'.$Forma;?></td>
				<td align="center">&nbsp;<?php echo $Descripcion;?></td>
				<td align="center"><div id="<?php echo $divExis;?>">
	<?php 		
		if($data=mysql_fetch_array($RespEx)){
			$existencia=$data["TotalExistencia"];
			/*$Lote=$data["Lote"];
			$Vencimiento=$data["FechaVencimiento"];
			if($Vencimiento!=''){
			$Fecha=explode('-',$Vencimiento);
			$Fecha_=$Fecha[2].'-'.$Fecha[1].'-'.$Fecha[0];
			}else{$Fecha_='';}*/
			if($existencia==''){$existencia=0;}
			
			$divExis="existenciaActual".$IdMedicina;

			$TotalConsumo=$existencia;
		if($respDivisor=mysql_fetch_array(Obtencion::ValorDivisor($IdMedicina))){
		$Divisor=$respDivisor[0];

                $TotalConsumo=number_format($TotalConsumo,3,'.','');
                
		if($TotalConsumo < 1){
			//Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
		   $TransformaEntero=number_format($TotalConsumo*$Divisor,0,'.',',');
			$CantidadTransformada=$TransformaEntero.'/'.$Divisor;
		}else{
			//Si la cantidad es mayor a un frasco se realiza este cambio a quebrados
				
		$CantidadBase=explode('.',$TotalConsumo);
		
		    $Entero=$CantidadBase[0];//Faccion ENTERA
			if(!isset($CantidadBase[1])){
			   $Decimal=0;
			}else{
			   $Decimal=$CantidadBase[1];
			}
			
		    if($Decimal==0){$Decimal="";$Quebrado="";}else{
			
			$Quebrado=number_format(($Decimal/1000)*$Divisor,0,'.',',');
			$Quebrado='['.$Quebrado.'/'.$Divisor.']';
		    }

			
		$CantidadTransformada=$Entero.' '.$Quebrado;
		}
	   $CantidadIntro=$CantidadTransformada;
		
	}else{
	   $CantidadIntro=$TotalConsumo/$Divisor;
		//$CantidadIntro=number_format($CantidadIntro,2,'.','');
	}
		
	}else{$existencia=0;$CantidadIntro=0;}//while $data

		 echo $CantidadIntro;?>
 <?php 
 		
		?>
		</div></td>
		
<td align="center">
<!-- CONSUMO TOTAL DE MEDICAMENTO DEL MES PASADO -->
<?php if($existencia!=0){
if($existencia!=0 and $ConsumoCalculado!='SM'){
//echo $ConsumoCalculado;
	$TotalConsumo=$ConsumoCalculado;
		if($respDivisor=mysql_fetch_array(Obtencion::ValorDivisor($IdMedicina))){
		$Divisor=$respDivisor[0];

                $TotalConsumo=number_format($TotalConsumo,3,'.','');
                
		if($TotalConsumo < 1){
			//Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
		   $TransformaEntero=number_format($TotalConsumo*$Divisor,0,'.',',');
			$CantidadTransformada=$TransformaEntero.'/'.$Divisor;
		}else{
			//Si la cantidad es mayor a un frasco se realiza este cambio a quebrados
				
		$CantidadBase=explode('.',$TotalConsumo);
		
		    $Entero=$CantidadBase[0];//Faccion ENTERA
			if(!isset($CantidadBase[1])){
			   $Decimal=0;
			}else{
			   $Decimal=$CantidadBase[1];
			}
			
		    if($Decimal==0){$Decimal="";$Quebrado="";}else{
			
			$Quebrado=number_format(($Decimal/1000)*$Divisor,0,'.',',');
			$Quebrado='['.$Quebrado.'/'.$Divisor.']';
		    }

			
		$CantidadTransformada=$Entero.' '.$Quebrado;
		}
	   $CantidadIntro=$CantidadTransformada;
		
	}else{
	   $CantidadIntro=$TotalConsumo/$Divisor;
		//$CantidadIntro=number_format($CantidadIntro,2,'.','');
	}

      $ConsumoCalculado=$CantidadIntro;
}else{$ConsumoCalculado= $ConsumoCalculado;} 

}else{$ConsumoCalculado= "-";} 

echo $ConsumoCalculado;
?>
</td>

<?php
	if($existencia==0){
	  $PorcentajeDeExistencia=0;
	}else{
  	  $PorcentajeDeExistencia=(($existencia-$ConsumoCalculado)/$existencia)*100;
		if($PorcentajeDeExistencia < 0 ){
		   $PorcentajeDeExistencia="Consumo de mes anterior<br>supera existencia actual";
			$color=" style='background-color:red;' ";
		}else{
		   $PorcentajeDeExistencia=number_format($PorcentajeDeExistencia,2,'.','')." [%]";
			$color="";
		}
	}

	
?>

<td <?php echo $color?> >
<?php

  echo $PorcentajeDeExistencia;
//echo $ConsumoMesActual;
?>

</td>
			</tr>
<?php
 }while($Datos=mysql_fetch_array($resp));//while
 }//If mysql_fetch_array
}//while Teraputico
 
 ?>
</table>
</div>
<?php 
conexion::desconectar();
}//Valida Session
?>