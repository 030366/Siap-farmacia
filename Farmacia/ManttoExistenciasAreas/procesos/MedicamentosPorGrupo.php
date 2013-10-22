<?php session_start();
if(!isset($_SESSION["IdPersonal"])){
  echo "ERROR_SESSION";
}else{
include("ClaseActualizaLotes.php");
conexion::conectar();

?>

<table width="994" border="1">
   <?php 

$area=$_GET["area"];

$IdTerapeutico=$_GET["IdTerapeutico"];
$Nombre=$_GET["Nombre"];

$IdModalidad=$_SESSION["IdModalidad"];

$selectGrupo="select * from mnt_grupoterapeutico where IdTerapeutico=".$IdTerapeutico;
$Grupo=mysql_query($selectGrupo);

$count=0;

while($DataGrupo=mysql_fetch_array($Grupo)){
	$NombreGrupo=$DataGrupo["GrupoTerapeutico"];
	$IdTerapeutico=$DataGrupo["IdTerapeutico"];
	

	$querySelect="select farm_catalogoproductos.IdMedicina,farm_catalogoproductos.Nombre,farm_catalogoproductos.FormaFarmaceutica,
	farm_catalogoproductos.Concentracion, mnt_areamedicina.IdArea, Presentacion
	from farm_catalogoproductos
	inner join mnt_areamedicina
	on mnt_areamedicina.IdMedicina=farm_catalogoproductos.IdMedicina
	where mnt_areamedicina.IdArea='$area' 
        and farm_catalogoproductos.IdTerapeutico='$IdTerapeutico'
        and mnt_areamedicina.IdEstablecimiento=".$_SESSION["IdEstablecimiento"]."
        and mnt_areamedicina.IdModalidad=$IdModalidad";
	 $resp=mysql_query($querySelect);

		if($resp2=mysql_fetch_array($resp)){
?>
 
<tr class="MYTABLE"><td align="center" colspan="7">&nbsp;<strong><?php echo $NombreGrupo;?></strong></td></tr>
  <tr class="MYTABLE">
    <td width="50" align="center">&nbsp;<strong>Codigo</strong></td>
    <td width="141" align="center">&nbsp;<strong>Medicamento</strong></td>
    <td width="94" align="center">&nbsp;<strong>Concentraci&oacute;n</strong></td>
    <td width="97" align="center">&nbsp;<strong>Presentaci&oacute;n</strong></td>	
    <td width="101" align="center">&nbsp;<strong>Unidad de Medida</strong></td>	
    <td width="169" align="center"><strong>Existencias</strong><strong></strong><strong> </strong></td>
    <td width="296" align="center">&nbsp;<strong>Ingresos</strong></td>
  </tr>
 <?php  if($Nombre!=''){$comp=" and (Nombre like '%$Nombre%' or Codigo='$Nombre')";}else{$comp="";}

			$querySelect="select distinct farm_catalogoproductos.Codigo,
			farm_catalogoproductos.IdMedicina,farm_catalogoproductos.Nombre,farm_catalogoproductos.FormaFarmaceutica,
			farm_catalogoproductos.Concentracion, mnt_areamedicina.IdArea, Presentacion
			from farm_catalogoproductos
			inner join mnt_areamedicina
			on mnt_areamedicina.IdMedicina=farm_catalogoproductos.IdMedicina
			inner join farm_catalogoproductosxestablecimiento fce
			on fce.IdMedicina=farm_catalogoproductos.IdMedicina
			where mnt_areamedicina.IdArea='$area' 
			and farm_catalogoproductos.IdTerapeutico='$IdTerapeutico'
			and fce.Condicion='H'
                        and fce.IdEstablecimiento=".$_SESSION["IdEstablecimiento"]."
                        and fce.IdModalidad=$IdModalidad
			".$comp."
			order by Codigo";
			$resp=mysql_query($querySelect);

 
		 while($Datos=mysql_fetch_array($resp)){
			 $Codigo=$Datos["Codigo"];
			 $Nombre=htmlentities($Datos["Nombre"]);
			 $Concentracion=$Datos["Concentracion"];
			 $Forma=$Datos["FormaFarmaceutica"].' - '.$Datos["Presentacion"];
			 $IdMedicina=$Datos["IdMedicina"];
			 
			 /*Unidad de Medida*/
			$data2=mysql_fetch_array(mysql_query("select farm_unidadmedidas.Descripcion,
			farm_unidadmedidas.UnidadesContenidas as Divisor
			from farm_unidadmedidas
			inner join farm_catalogoproductos
			on farm_catalogoproductos.IdUnidadMedida=farm_unidadmedidas.IdUnidadMedida
			where farm_catalogoproductos.IdMedicina='$IdMedicina'"));
			$UnidadMedida=$data2["Descripcion"];
			$Unidades=$data2["Divisor"];
			/**************************/
	
			$RespEx=mysql_query("select farm_medicinaexistenciaxarea.*,farm_lotes.*
			from farm_medicinaexistenciaxarea
			inner join farm_catalogoproductos
			on farm_catalogoproductos.IdMedicina=farm_medicinaexistenciaxarea.IdMedicina
			inner join farm_lotes
			on farm_lotes.IdLote=farm_medicinaexistenciaxarea.IdLote
			where farm_medicinaexistenciaxarea.IdArea='$area' and farm_medicinaexistenciaxarea.IdMedicina='$IdMedicina'
			and farm_medicinaexistenciaxarea.Existencia <> '0' 
                        and farm_medicinaexistenciaxarea.IdEstablecimiento=".$_SESSION["IdEstablecimiento"]."
                        and farm_medicinaexistenciaxarea.IdModalidad=$IdModalidad
                        
			and left(FechaVencimiento,7) >= left(curdate(),7)
			order by farm_lotes.FechaVencimiento");
			$i=0;
			$Lote="";$existencia_="";$FechaVencimiento="";
					while($data=mysql_fetch_array($RespEx)){
									
						$existencia=$data["Existencia"];
						
						$IdMedicinaLoteAccion=$data["IdMedicina"];
						   $IdLoteExistencia[$i]=$data["IdLote"];
						$IdExistenciaArea[$i]=$data["IdExistencia"];
						
   if($existencia==''){$existencia_[$i]=0;}else{

	if($respDivisor=mysql_fetch_array(Actualiza::ValorDivisor($Datos["IdMedicina"],$IdModalidad))){
		$Divisor=$respDivisor[0];

		if($data["Existencia"] < 1){
			//Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
		   $TransformaEntero=number_format($data["Existencia"]*$Divisor,0,'.',',');
			$CantidadTransformada=$TransformaEntero.'/'.$Divisor;
		}else{
			//Si la cantidad es mayor a un frasco se realiza este cambio a quebrados
			$CantidadReal=number_format($data["Existencia"],2,'.',',');	
		$CantidadBase=explode('.',$CantidadReal);
		
		    $Entero=$CantidadBase[0];//Faccion ENTERA

			$Decimal=$CantidadBase[1];
		    if($Decimal==0){$Decimal="";$Quebrado="";}else{
			
			$Quebrado=number_format(($Decimal/100)*$Divisor,0,'.',',');
			$Quebrado='['.$Quebrado.'/'.$Divisor.']';
		    }

			
		$CantidadTransformada=$Entero.' '.$Quebrado;
		}
	   $CantidadIntro=$CantidadTransformada;
		
	}else{
	   $CantidadIntro=$data["Existencia"]/$Unidades;
	}

$existencia_[$i]=$CantidadIntro;

   }
						if($existencia > 0){$Lote[$i]=$data["Lote"];$FechaVencimiento[$i]=$data["FechaVencimiento"];}
						$i++;
					}//While para despliegue de Lotes
			if($Lote!=NULL){
			$Campos=count($Lote);   //Conteo de los vectores
			}else{$Campos=0;}
			
			
 			$div="saving".$IdMedicina;
			$boton="guardar".$IdMedicina;
			$divExis="existenciaActual".$IdMedicina;
?>
  <tr class="FONDO">
  	<td align="center">&nbsp;<?php echo $Codigo;?></td>
    <td align="center">&nbsp;<?php echo $Nombre;?></td>
    <td align="center">&nbsp;<?php echo $Concentracion;?></td>
    <td align="center">&nbsp;<?php echo htmlentities($Forma);?></td>
	<td align="center">&nbsp;<?php echo $UnidadMedida;?></td>
    <td align="center"><div id="<?php echo $divExis;?>">
	<?php 
	for($i=0;$i<=$Campos-1;$i++){

	if($FechaVencimiento[$i]!=NULL){
	$Date=explode('-',$FechaVencimiento[$i]);
	$Fecha=$Date[2]."-".$Date[1]."-".$Date[0];
	}else{$Fecha="";}
	
	//$SCript="javascript:popUp(\"ActualizaLotes.php?Lote=$Lote[$i]&IdMedicina=$IdMedicina&IdArea=".$area."\");
	$Script='';

	$EliminarExistencia="<u><a style='cursor:hand;' onclick='EliminarMedicamentoExistencia(".$IdMedicinaLoteAccion.",".$IdExistenciaArea[$i].",".$IdLoteExistencia[$i].",".$area.")'>X</a></u>";
	
	echo $EliminarExistencia." Existencia: ".$existencia_[$i]."<br>Lote: <a onclick='".$Script."'>".$Lote[$i]."</a><br>Vencimiento: ".$Fecha."<br><br>";
	}
	?></div></td>
    <td align="left">
		<table width="297">
		<tr class="FONDO"><td width="131">
	Cantidad:</td>
		<td width="227"><input type="text" id="<?php echo $IdMedicina;?>" name="<?php echo $IdMedicina;?>" maxlength="12" size="4" value="0" onFocus="if(this.value=='0'){this.value=''}" onBlur="NoCero(this.id);if(this.value==''){this.value='0'}" onKeyPress="return acceptNum(event)">		</td></tr>
		<tr class="FONDO"><td style="vertical-align:top;">Lote:</td>
	<td>

		<div id="<?php echo "ComboLotes".$IdMedicina;?>"><?php echo queries::LotesExistencias($IdMedicina,$_SESSION["IdEstablecimiento"],$IdModalidad);?></div>
		<div id="<?php echo "LoteExistenciaActual".$IdMedicina;?>"></div>
	<select id="<?php echo "mes".$IdMedicina;?>" name="<?php echo "mes".$IdMedicina;?>" style="width:0; height:0; visibility:hidden;">
      <option value="0">[Seleccione Mes]</option>
      <option value="01">ENERO</option>
      <option value="02">FEBRERO</option>
      <option value="03">MARZO</option>
      <option value="04">ABRIL</option>
      <option value="05">MAYO</option>
      <option value="06">JUNIO</option>
      <option value="07">JULIO</option>
      <option value="08">AGOSTO</option>
      <option value="09">SEPTIEMBRE</option>
      <option value="10">OCTUBRE</option>
      <option value="11">NOVIEMBRE</option>
      <option value="12">DICIEMBRE</option>
    </select>
	<select id="<?php echo "ano".$IdMedicina;?>" name="<?php echo "ano".$IdMedicina;?>" style="width:0; height:0; visibility:hidden;">
      <option value="0">[Seleccione A�o]</option>
      <?php 
			$date=date('Y');
			
			for($i=0;$i<=12;$i++){
			$ano=$date+$i;
		?>
      <option value="<?php echo $ano;?>"><?php echo $ano;?></option>
      <?php }//fin de for
?>
    </select></td>
		</tr>
		<tr class="FONDO"><td>&nbsp;</td>
		<td><input id="<?php echo "Precio".$IdMedicina;?>" name="<?php echo "Precio".$IdMedicina;?>" type="hidden" size="8" value="0" onFocus="if(this.value=='0'){this.value=''}" onBlur="if(this.value==''){this.value='0'}" onKeyPress="return acceptNum2(event)" style="visibility:hidden;">
		  <input id="<?php echo $boton;?>" name="guardar3" type="button" value="Guardar" onClick="javascript:Alerta(<?php echo $IdMedicina;?>,<?php echo $area;?>)"></td>
		</tr></table>
	</td>
  </tr>
 <?php 
			
		}//while
echo "<tr class='MYTABLE'><td colspan=\"7\" align=\"right\"><input type=\"submit\" name=\"guardar".$IdTerapeutico."\" value=\"Guardar\" style=\"border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099\"></tr></td>";

	}//If mysql_fetch_array
}//while Teraputico
 
 ?>
</table>

<?php }//Validcion de Session?>
