<?php  session_start();
require('IncludeFiles/RepetitivasClase.php');
$query=new Repetitivas;
$query2=new queries;

conexion::conectar();
$IdPersonal=$_SESSION["IdPersonal"];
$IdArea=$_GET["IdArea"];
/**VALORES POR POST**/
$IdNumeroExp=$_GET["IdNumeroExp"];
$IdEstado=$_GET["IdEstado"];
if($IdEstado=='R'){$Estado="and (farm_recetas.IdEstado='R' or farm_recetas.IdEstado='P' or farm_recetas.IdEstado='L')";}else{$Estado="and (farm_recetas.IdEstado='RE' or farm_recetas.IdEstado='RP' or farm_recetas.IdEstado='RL')";}

$date=$_GET["Fecha"];


$resp1=mysql_query("select * from farm_cierre where AnoCierre=year('$date')");
$resp2=mysql_query("select * from farm_cierre where MesCierre=left('$date',7)");

if($row1=mysql_fetch_array($resp1)){
	echo "EL AñO EN CUESTION SE ENCUENTRA CERRADO !";

}else{


	if($row2=mysql_fetch_array($resp2)){
		echo "<h3>EL MES SELECCIONADO SE ENCUENTRA CERRADO !";
	}else{




$resp=mysql_query("select distinct farm_recetas.IdReceta
		from farm_recetas
		inner join sec_historial_clinico
		on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
		inner join farm_medicinarecetada
		on farm_medicinarecetada.IdReceta=farm_recetas.IdReceta
		where sec_historial_clinico.IdNumeroExp='$IdNumeroExp'
		and left(Fecha,7) = left('$date',7)
		and IdArea='$IdArea'
		$Estado
		order by FechaConsulta");



		if(isset($_GET["Ok"])){
			$IdReceta=$_GET["IdReceta"];
			if($IdEstado=='R'){$EstadoUp='P';}else{$EstadoUp='RP';}
			mysql_query("update farm_recetas set IdEstado='$EstadoUp' where IdReceta='$IdReceta'");
			mysql_query("update farm_recetas set IdPersonal='$IdPersonal' where IdReceta='$IdReceta'");
		}


while($row=mysql_fetch_array($resp)){

$IdReceta=$row[0];
$respDatos=$query->ObtenerDatosPacienteRecetaProceso($IdReceta);

while($row=mysql_fetch_array($respDatos)){
	//Datos Generales de todos los pacientes.-
	$paciente=$row["NOMBRE"];
	$paciente=htmlentities(strtoupper($paciente));
	$NumeroReceta=$row["NumeroReceta"];
	$expediente=$row["IdNumeroExp"];
	$fechacon=$row["FechaConsulta"];
	$NombreEmpleado=htmlentities($row["NombreEmpleado"]);
	$SubEspecialidad=$row["NombreSubServicio"];
	$Estado=$row["IdEstado"];
	$IdReceta=$row["IdReceta"];
	$Fecha=$row["Fecha"];
	$edad=$row["nac"];
	$FechaDeEntrega=$row["FechaDeEntrega"];
	if($row["sexo"]==1){$sexo="Masculino";} else {$sexo="Femenino";}
/*Datos para Link*/
	$IdHistorialClinico=$row["IdHistorialClinico"];
	$IdReceta=$row["IdReceta"];
	//$date=date("d-m-Y");
/****************************/?>
<div id="<?php echo $IdReceta;?>">
<table width="1005">
<tr><td height="95" align="center">

  <table class="MYTABLE" width="995" style="color:#000000">
   <tr class="FONDO"><td colspan="4" align="center"><strong>RECETA No:&nbsp;&nbsp;<?php echo $NumeroReceta;?>,&nbsp;Fecha de Entrega:&nbsp;<?php echo $FechaDeEntrega;?></strong></td></tr>
	<?php if($Estado=='RE' || $Estado=='RP'){?>
    	<tr class="MYTABLE"><td colspan="4" align="center"><strong>RECETA REPETITIVA</strong> Fecha de Entrega: <strong><?php echo $FechaDeEntrega;?></strong></td></tr>
	<?php }?>
	<tr>
      <td width="412"><strong>Expediente:</strong>&nbsp;&nbsp;&nbsp;<?php echo"$expediente";?></td>
      <td width="214"><strong>Fecha de Consulta:&nbsp;</strong>&nbsp;&nbsp;<?php echo"$fechacon"; ?></td>
      <td width="167">&nbsp;</td>
      <td width="182">&nbsp;<input type="text" id="expediente" name="expediente" size="25" onkeypress="return acceptNum(event)" value="Digite '/' para la busqueda" />
        <input type="hidden" id="buscar" name="buscar" value="Buscar" /></td>
    </tr>
    <tr>
      <td><strong>Nombre Paciente:&nbsp;</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $paciente; ?></td>
      <td colspan="3"><strong>Especialidad:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $SubEspecialidad;//"$date";?></strong></td>
    </tr>
    <tr>
      <td><strong>Edad:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo"$edad";?> </td>
      <td colspan="3"><strong>Nombre Medico:</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo"$NombreEmpleado";?></td>
    </tr>
    <tr>
      <td height="26"><strong>Sexo:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo"$sexo";?> </td>
      <td colspan="3"><div align="right"><span class="FONDO">
	  <?php
		  if($Estado=='RE'){?>
<input type="button" name="<?php echo "procesar".$IdHistorialClinico;?>" onclick="Procesar(<?php echo $IdReceta;?>); javascript:popUp('impresion.php?IH=<?php echo $IdHistorialClinico;?>&IR=<?php echo $IdReceta;?>&F=<?php echo $fechacon;?>&IdArea=<?php echo $IdArea;?>');" value="PROCESAR"/>
<input type="button" name="<?php echo "verificar".$IdHistorialClinico;?>" value="VERIFICAR RECETA" onclick="" />
<?php }
		  if($Estado=="RP" or $Estado=='P'){
		  $RowName=mysql_fetch_array($query2->NombreTecnico($IdReceta));
		  $Corr=$RowName["IdPersonal"];
		  $NombreTecnico=$RowName["NombreTecnico"];
		  if(!isset($Corr)){$Corr=0;}
		  if(!isset($NombreTecnico)){$NombreTecnico="";}
		  ?>
<input style="font-size:18px;text-align:center; <?php 
$par=($IdReceta % 2 ==0)? 1 : 0;
if($par==0){?>background-color:#00FF00; border:#00FF00<?php }else{?>background-color:#FF9900; border:#FF9900<?php }?>" value="<?php echo"$NumeroReceta &nbsp;&nbsp;&nbsp;";?>....:::: EN PROCESO ::::.... por: <?php echo $NombreTecnico;?>" readonly="true" name="<?php echo"verde".$IdHistorialClinico;?>" id="<?php echo"verde".$IdHistorialClinico;?>" type="text" size="65" />

<input type="button" id="<?php echo"listo".$IdHistorialClinico;?>" name="<?php echo"listo".$IdHistorialClinico;?>" value="LISTO" onClick="javascript:Listo(<?php echo $IdReceta;?>,'<?php echo $Estado;?>')"/>
<?php 


}?>
      </span></div></td>
    </tr>
  </table>
</td></tr>
<tr><td align="center">
<?php 
		//Detalles de Receta
		$respDetalles=$query->datosReceta($IdReceta,$IdArea);?>
  <table class="MYTABLE" width="1079" border="1">
    <tr class="MYTABLE">
      <td width="58"><div align="center"><strong>Cantidad</strong></div></td>
      <td width="149"><div align="center"><strong>Nombre de Medicina </strong></div></td>
      <td width="95"><div align="center"><strong>Concentraci&oacute;n</strong></div></td>
      <td width="199"><div align="center"><strong>Presentaci&oacute;n</strong></div></td>
      <td width="256"><strong>Dosificaci&oacute;n</strong></td>
      <td width="138"><div align="center"><strong>Satisfecho</strong></div></td>
      <td width="138" align="center"><strong>Eliminar</strong></td>
    </tr>
    <?php 
	while($row2=mysql_fetch_array($respDetalles)){
			$IdMedicinaRecetada=$row2["IdMedicinaRecetada"];
		$cantidad=$row2["Cantidad"];
		$NombreMedicina=$row2["medicina"];
		$Concentracion=$row2["Concentracion"];
		$forma=$row2["FormaFarmaceutica"];
		$presentacion=$row2["Presentacion"];
		$Presentacion=$row2["FormaFarmaceutica"];//.", ".$row2["Presentacion"];
		$dosis=htmlentities($row2["Dosis"]);
		$idmedicina=$row2["IdMedicina"];
		$IdReceta=$row2["IdReceta"];
		$EstadoMedicina=$row2["IdEstado"];?>
    <tr class="FONDO2" <?php if($EstadoMedicina=='I'){?> style="background-color:#FF6633"<?php }//IF ESTADOMEDICINA?>>
      <td align="center"><?php echo"$cantidad"; ?></td>
      <td align="center"><a onClick="ActualizaMedicina(<?php echo $IdReceta;?>,<?php echo $idmedicina;?>);"><?php echo"$NombreMedicina"; ?></a></td>
      <td align="center"><?php echo"$Concentracion"; ?></td>
      <td><?php echo htmlentities($Presentacion); ?></td>
      <td><?php echo"$dosis"; ?></td>
      <td align="center"><?php
		if($Estado=='RP'){
			$combo=$IdHistorialClinico.$idmedicina; //Nombre generico del ComboBox 
			$NombreIdReceta='IdReceta'.$IdHistorialClinico;?>
        <input type="hidden" id="<?php echo $NombreIdReceta;?>" name="<?php echo $NombreIdReceta;?>" value="<?php echo $IdReceta;?>" />
        <?php
		//if($IdPersonal==$Corr){
			if($EstadoMedicina=='I'){

			?>
        <select id="<?php echo "$combo"; ?>" name="<?php echo "$combo"; ?>" onchange="actualizaEstado(<?php echo $IdHistorialClinico;?>,<?php echo $idmedicina;?>,this.value)">
          <option value="NO" selected="selected">NO</option>
          <option value="SI">SI</option>
        </select>
        <?php 
		 }else{ ?>
        <select id="<?php echo "$combo"; ?>" name="<?php echo "$combo"; ?>" onchange="actualizaEstado(<?php echo $IdHistorialClinico;?>,<?php echo $idmedicina;?>,this.value)">
          <option value="SI" selected="selected">SI</option>
          <option value="NO">NO</option>
        </select>
        <?php }//ESTADO MEDICINA
		  }else{?>
        <select id="temp" name="temp" disabled="disabled">
          <option value="SI" selected="selected">SI</option>
        </select>
        <?php }?></td>
      <td align="center"><input type="button" id="Eliminar" name="Eliminar" value="Anular Receta" onclick="Eliminar(<?php echo $IdMedicinaRecetada;?>);"/></td>
    </tr>
    <?php } //fin de while?>
    <tr class="MYTABLE">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
    </tr>
	<tr>
	  <td colspan="7" align="center"><a href="../ConsultaRecetas/ConsultaPrincipal.php">VERIFICAR RECETA REPETITIVA</a>&nbsp;</td>
	</tr>
  </table>
  </td></tr>
  <tr><td><hr style="color:#FF0000"></td></tr>
</table>
</div>

 <?php 
 }//While de IdRecetas
 echo"<br>";
}//fin de while respDatos

	}//MEs Cerrado

}//Ano Cerrado

conexion::desconectar();
?>

