<?php session_start();
require('../Clases/class.php');
require('IncludeMonitoreo/MonitoreoClass.php');
conexion::conectar();
?>
<div id="Layer1">
  <table width="477" border="1">
  
<tr class="MYTABLE"><td align="center" colspan="4">&nbsp;<strong>MEDICAMENTO AREA: CONSULTA EXTERNA</strong></td></tr>

<?php
$area=$_REQUEST["area"];
$farmacia=$_REQUEST["farmacia"];
//Datos Generales ***********************************************************

$querySelectFarmacia="select mnt_farmacia.Farmacia from mnt_farmacia where mnt_farmacia.IdFarmacia='$farmacia'";
$querySelectArea="select mnt_areafarmacia.Area from mnt_areafarmacia where mnt_areafarmacia.IdArea='$area'";
$Dfarmacia=mysql_query($querySelectFarmacia);
$Darea=mysql_query($querySelectArea);

$Grupo=Obtencion::ObtenerGrupos();
//***************************************************************************
$dataFarmacia=mysql_fetch_array($Dfarmacia);
$dataArea=mysql_fetch_array($Darea);
$NomFarmacia=$dataFarmacia[0];
$NomArea=$dataArea[0];
//$count=0;

$conteo=0;
while($DataGrupo=mysql_fetch_array($Grupo)){
$NombreGrupo=$DataGrupo["GrupoTerapeutico"];
$IdTerapeutico=$DataGrupo["IdTerapeutico"];

$resp=Obtencion::ObtenerDetalleMedicamentoPorGrupo($area,$IdTerapeutico);


if($Datos=mysql_fetch_array($resp)){?>
<tr class="MYTABLE"><td align="center" colspan="4">&nbsp;<strong><?php echo $NombreGrupo;?></strong></td></tr>
  <tr class="MYTABLE">
    <td width="75" align="center">&nbsp;<strong>Codigo</strong></td>
    <td width="276" align="center">&nbsp;<strong>Medicamento</strong></td>
    <td width="155" align="center">&nbsp;<strong>Existencias [Unitario] </strong></td>
	<td align="center"><strong>Consumo [%]</strong></td>
  </tr>
 <?php 
 
 do{
 $ConsumoMesActual=0;
 $Codigo=$Datos["Codigo"];
 $Nombre=htmlentities($Datos["Nombre"]);
 $Concentracion=$Datos["Concentracion"];
 $Forma=htmlentities($Datos["FormaFarmaceutica"].' - '.$Datos["Presentacion"]);
 $IdMedicina=$Datos["IdMedicina"];

if($_SESSION["TipoFarmacia"]==2){
   $RespEx=Obtencion::ObtenerExistencias($area,$IdMedicina);
}else{
   $RespEx=Obtencion::ObtenerExistenciasBodega($IdMedicina);
}
/*OBTENCION DE PORCENTAJES*/
if($_SESSION["TipoFarmacia"]==2){
   $Porcentaje=Obtencion::ObtenerPorcentaje($IdMedicina,$area);
}else{
   $Porcentaje=Obtencion::ObtenerPorcentajeBodega($IdMedicina);
}

if($Porcentaje[2]!=0 and $Porcentaje[2]!=""){
$ConsumoMesPasado=round(($Porcentaje[0]/($Porcentaje[0]+$Porcentaje[2]))*100,2);
$ConsumoMesActual=round((($Porcentaje[1]/($Porcentaje[1]+$Porcentaje[2]))*100),2);
}else{
$ConsumoMesPasado=0;
}
/***************/

			?>
			<tr class="FONDO" <?php if(($ConsumoMesPasado<$ConsumoMesActual and $ConsumoMesPasado!=0 and $ConsumoMesActual!=0) or ($ConsumoMesActual >= 73 and $ConsumoMesActual <= 100)){echo "style='background-color:#CC3300;'";}?> >
				<td>&nbsp;<?php echo $Codigo;?></td>
				<td>&nbsp;<?php echo $Nombre." - ".$Concentracion.'<br>'.$Forma;?></td>
				<td align="center"><div id="<?php echo $divExis;?>">
	<?php 
			while($data=mysql_fetch_array($RespEx)){
			$existencia=$data["Existencia"];
			$Lote=$data["Lote"];
			$Vencimiento=$data["FechaVencimiento"];
			if($Vencimiento!=''){
			$Fecha=explode('-',$Vencimiento);
			$Fecha_=$Fecha[2].'-'.$Fecha[1].'-'.$Fecha[0];
			}else{$Fecha_='';}
			if($existencia==''){$existencia=0;}
			
			$divExis="existenciaActual".$IdMedicina;

			$TotalConsumo=$existencia;
		if($respDivisor=mysql_fetch_array(Obtencion::ValorDivisor($IdMedicina))){
		$Divisor=$respDivisor[0];

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
			
			$Quebrado=number_format(($Decimal/100)*$Divisor,0,'.',',');
			$Quebrado='['.$Quebrado.'/'.$Divisor.']';
		    }

			
		$CantidadTransformada=$Entero.' '.$Quebrado;
		}
	   $CantidadIntro=$CantidadTransformada;
		
	}else{
	   $CantidadIntro=$TotalConsumo;
		$CantidadIntro=number_format($CantidadIntro,0,'.',',');
	}

		 echo "Existencia: ".$CantidadIntro."<br>Lote: ".$Lote."<br>Vencimiento: ".$Fecha_."<br><br>";?>
 <?php 
 		}//while $data
		?>
		</div></td>
		
<td>
<?php

echo $ConsumoMesActual;
?>
[%]
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
/*
}//Else $IdFarmacia!=0
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel
*/
?>