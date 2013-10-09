<?php session_start();
if(!isset($_SESSION["IdPersonal"])){
  echo "ERROR_SESSION";
}else{
include("../../Clases/class.php");
conexion::conectar();

class ComboLotes{ 
function VerificaExitenciaLotes($IdMedicina,$IdArea){
	$SQL="select farm_medicinavencida.IdMedicina,Existencia/UnidadesContenidas as Existencia, farm_lotes.IdLote, Lote, Descripcion

	from farm_medicinavencida
	inner join farm_lotes
	on farm_medicinavencida.IdLote=farm_lotes.IdLote
	inner join farm_catalogoproductos
	on farm_catalogoproductos.IdMedicina = farm_medicinavencida.IdMedicina
	inner join farm_unidadmedidas
	on farm_unidadmedidas.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida
	
	where farm_medicinavencida.IdMedicina=".$IdMedicina."
	and IdArea=$IdArea
	order by FechaVencimiento asc";

	$resp=mysql_query($SQL);
	return($resp);
}

function ObtenerArea($IdArea){
   $SQL="select Area
	from mnt_areafarmacia
	where IdArea=".$IdArea;
	$resp=mysql_fetch_array(mysql_query($SQL));
	return($resp[0]);
}

}

	function ValorDivisor($IdMedicina){
	   $SQL="select DivisorMedicina from farm_divisores where IdMedicina=".$IdMedicina;
	   $resp=mysql_query($SQL);
	   return($resp);
    	}


?>
<table width="100%" border="1" style="border:solid;border-collapse:collapse;">
   <?php 
$IdTerapeutico=$_GET["IdTerapeutico"];
$IdArea=$_GET["IdArea"];
$selectGrupo="select * from mnt_grupoterapeutico where IdTerapeutico=".$IdTerapeutico;
$Grupo=mysql_query($selectGrupo);

$count=0;

while($DataGrupo=mysql_fetch_array($Grupo)){
	$NombreGrupo=$DataGrupo["GrupoTerapeutico"];
	$IdTerapeutico=$DataGrupo["IdTerapeutico"];
	
	$querySelect="select farm_catalogoproductos.IdMedicina,Codigo ,farm_catalogoproductos.Nombre,farm_catalogoproductos.FormaFarmaceutica,
	farm_catalogoproductos.Concentracion,Presentacion
	from farm_catalogoproductos
	inner join farm_catalogoproductosxestablecimiento cpe
	on cpe.IdMedicina=farm_catalogoproductos.IdMedicina
	
	where farm_catalogoproductos.IdTerapeutico='".$IdTerapeutico."'
	and cpe.Condicion='H'
	and cpe.IdEstablecimiento=".$_SESSION["IdEstablecimiento"];
	 $resp=mysql_query($querySelect);

		if($Datos=mysql_fetch_array($resp)){
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
 <?php 
			 
		 do{
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
			$Divisor=$data2["Divisor"];
			/**************************/
	
			$RespEx=mysql_query("select farm_medicinavencida.*,farm_lotes.*
			from farm_medicinavencida
			inner join farm_catalogoproductos
			on farm_catalogoproductos.IdMedicina=farm_medicinavencida.IdMedicina
			inner join farm_lotes
			on farm_lotes.IdLote=farm_medicinavencida.IdLote
			where farm_medicinavencida.IdMedicina='$IdMedicina'
			and farm_medicinavencida.Existencia <> '0' 
			order by farm_lotes.FechaVencimiento");
			$i=0;
			$Lote="";$existencia_="";$FechaVencimiento="";$Area="";
					while($data=mysql_fetch_array($RespEx)){	
									
						$existencia=$data["Existencia"];
						$Area[$i]=ComboLotes::ObtenerArea($data["IdArea"]);
						if($existencia==''){$existencia_[$i]=0;}else{$existencia_[$i]=$existencia;}
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
	
		$CantidadReal=$existencia_[$i];
		if($respDivisor=mysql_fetch_array(ValorDivisor($IdMedicina))){
		$Divisor=$respDivisor[0];

		if($CantidadReal < 1){
			//Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
		   $TransformaEntero=number_format($CantidadReal*$Divisor,0,'.',',');
			$CantidadTransformada=$TransformaEntero.'/'.$Divisor;
		}else{
			//Si la cantidad es mayor a un frasco se realiza este cambio a quebrados
				
		$CantidadBase=explode('.',$CantidadReal);
		
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
	   $CantidadIntro=$existencia_[$i]/$Divisor;
		$CantidadIntro=number_format($CantidadIntro,2,'.',',');
	}


	echo "Area:<strong>".$Area[$i]."</strong><br>Existencia: ".$CantidadIntro."<br>Lote: <a onclick='javascript:popUp(\"ActualizaLotes.php?Lote=$Lote[$i]&IdMedicina=$IdMedicina\")'>".$Lote[$i]."</a><br>Vencimiento: ".$Fecha."<br><br>";
	}
	?>
    </div></td>
    <td align="left">
		<table width="297">
		<tr class="FONDO"><td width="131">
	Cantidad:</td>
		<td width="227"><input type="text" id="<?php echo $IdMedicina;?>" name="<?php echo $IdMedicina;?>" maxlength="12" size="4" value="0" onFocus="if(this.value=='0'){this.value=''}" onBlur="NoCero(this.id);if(this.value==''){this.value='0'}" onKeyPress="return acceptNum(event)">
		</td></tr>
		<tr class="FONDO"><td>
	Lote:</td><td>
		<div id="<?php echo "ComboLotesMedicina".$IdMedicina;?>">
		<?php $respLotesExiste=ComboLotes::VerificaExitenciaLotes($IdMedicina,$IdArea);
		if($rowLotesExiste=mysql_fetch_array($respLotesExiste)){
		   $disabled='disabled="true"';
			echo "<select id='Lote".$IdMedicina."' name='Lote".$IdMedicina."' onchange='MostrarOpcionLote(this.value,this.id);'>";
			
			do{

			echo "<option value='".$rowLotesExiste["Lote"]."'>Lote: ".$rowLotesExiste["Lote"]."</option>";
			}while($rowLotesExiste=mysql_fetch_array($respLotesExiste));
			echo "<option value='N'>NUEVO LOTE</option>
				</select>";
		}else{
		//Si no existen lotes se da la opcion de ingresar el lote respectivo
		$disabled='';
		?>	
	
		<input id="<?php echo "Lote".$IdMedicina;?>" name="<?php echo "Lote".$IdMedicina;?>" size="8" value="Lote." onFocus="if(this.value=='Lote.'){this.value='';}" onBlur="if(this.value==''){this.value='Lote.';}">
		<?php }//si no existen lotes ?>
		</div>
		</td></tr>
		<tr class="FONDO"><td>
	Fecha de Ventto.:</td><td>
	<div id="<?php echo "Combos".$IdMedicina;?>">
	<select id="<?php echo "mes".$IdMedicina;?>" name="<?php echo "mes".$IdMedicina;?>" <?php echo $disabled;?> >
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
<select id="<?php echo "ano".$IdMedicina;?>" name="<?php echo "ano".$IdMedicina;?>" <?php echo $disabled;?> >
<option value="0">[Seleccione A&ntilde;o]</option>
<?php 
$date=date('Y');
  $inicial=$date-5;
for($i=$inicial;$i<=$date;$i++){
$ano=$i;
?>
<option value="<?php echo $ano;?>"><?php echo $ano;?></option>
<?php }//fin de for
?>
</select>
</div>
		</td>
		</tr>
		<tr class="FONDO"><td>
	Precio Unitario($):</td><td><input id="<?php echo "Precio".$IdMedicina;?>" name="<?php echo "Precio".$IdMedicina;?>" type="text" size="8" value="0" onFocus="if(this.value=='0'){this.value=''}" onBlur="if(this.value==''){this.value='0'}" onKeyPress="return acceptNum2(event)" <?php echo $disabled;?> >
	<input id="<?php echo $boton;?>" name="guardar" type="button" value="Guardar" onClick="javascript:Alerta(<?php echo $IdMedicina;?>)">
	</td></tr></table>
	</td>
  </tr>
 <?php 
			
		}while($Datos=mysql_fetch_array($resp));//while
echo "<tr class='MYTABLE'><td colspan=\"7\" align=\"right\"><input type=\"submit\" name=\"guardar".$IdTerapeutico."\" value=\"Guardar\" style=\"border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099\"></tr></td>";

	}//If mysql_fetch_array
}//while Teraputico
 
conexion::desconectar();
 ?>
</table>

<?php }//Validcion de Session?>
