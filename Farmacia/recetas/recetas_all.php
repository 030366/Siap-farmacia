<?php session_start();
if(isset($_SESSION["nivel"])==3){
$IdFarmacia=$_SESSION["IdFarmacia2"];
$IdArea=$_SESSION["IdArea"];
$IdPersonal=$_SESSION["IdPersonal"];
?>
<html>
<script language="javascript">
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=500,height=600,left = 450,top = 450');");
}//popUp
</script>
<head>
<!-- <meta http-equiv="refresh" content="10" /> -->
<script type="text/javascript" src="ReLoad.js"></script>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<title>...:::Recetas:::...</title>
<style type="text/css">
<!--
#Recetas {	position:absolute;
	left:-1px;
	top:63px;
	width:946px;
	height:199px;
	z-index:2;
}
.style2 {
	color: #FFFFFF;
	font-size: medium;
}
#Layer3 {position:absolute;
	left:1px;
	top:2px;
	width:861px;
	height:34px;
	z-index:6;
}
#Layer1 {
	position:absolute;
	left:868px;
	top:1px;
	width:56px;
	height:26px;
	z-index:7;
}
#Layer2 {
	position:absolute;
	left:52px;
	top:412px;
	width:23px;
	height:15px;
	z-index:1;
}

-->
</style>
<script language="javascript">
function confirmacion(){
var valor=confirm('             Esta receta sera enviada \n�Son los datos de esta receta correctos?');
	if(valor==1){
		return true;
	}else{
		return false;
	}
}//confirmacion

</script>
</head>
<!-- Bloqueo de Click Derecho del Mouse -->
<body onLoad="recarga()" >
<script type="text/javascript" src="../tooltip/wz_tooltip.js"></script>

  <script webstyle4>document.write('<scr'+'ipt src="../xaramenu.js">'+'</scr'+'ipt>');document.write('<scr'+'ipt src="../MenuImages/menuconsultaexterna.js">'+'</scr'+'ipt>');/*img src="MenuImages/MenuConsultaExterna.gif" moduleid="MenuConExt (Project)\MenuConsultaExterna_off.xws"*/</script>
<br>

<div id="TODO">

<?php 
require('../Clases/class.php');
require('IncludeFiles/DiasClase.php');
$query=new queries;
conexion::conectar();

$respDatos=$query->ObtenerDatosPacienteReceta(1,0,$IdArea);

while($row=mysql_fetch_array($respDatos)){
	//Datos Generales de todos los pacientes.-
	$paciente=$row["NOMBRE"];
	$paciente=htmlentities(strtoupper($paciente));
	$NumeroReceta=$row["NumeroReceta"];
	$expediente=$row["IdNumeroExp"];

	$fechacon=$row["FechaConsulta"];
	$NombreEmpleado=htmlentities($row["NombreEmpleado"]);
	$SubEspecialidad=$row["NombreSubEspecialidad"];
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
<?php if($Estado=='RL'){?>
<tr class="FONDO"><td colspan="4" align="center"><strong>RECETA REPETITIVA</strong></td></tr>
<?php } ?>
   <tr class="FONDO"><td colspan="4" align="center"><strong>RECETA No:&nbsp;&nbsp;<?php echo $NumeroReceta;?>,&nbsp;Fecha de Entrega:&nbsp;<?php echo $FechaDeEntrega;?></strong></td></tr>
	<tr>
      <td width="412"><strong>Expediente:</strong>&nbsp;&nbsp;&nbsp;<?php echo"$expediente";?></td>
      <td width="314"><strong>Fecha de Consulta:&nbsp;</strong>&nbsp;&nbsp;<?php echo"$fechacon"; ?></td>
      <td width="157">&nbsp;</td>
      <td width="182"><input type="text" id="expediente" name="expediente" size="25" onKeyPress="return acceptNum(event)" value="Digite '/' para la busqueda" />
        <input type="hidden" id="buscar" name="buscar" value="Buscar"/></td>
    </tr>
    <tr>
      <td><strong>Nombre Paciente:&nbsp;</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo"$paciente"; ?></td>
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
 
		  if($Estado=='L' or $Estado=='RL'){?>
<input type="button" name="<?php echo "procesar".$IdHistorialClinico;?>" onClick="Listo(<?php echo $IdReceta;?>,<?php echo $NumeroReceta;?>);" value="ENTREGAR MEDICAMENTO"/>

<input type="button" name="<?php echo "imprimir".$IdHistorialClinico;?>" value="IMPRIMIR" onClick="javascript:popUp('impresion.php?IR=<?php echo $IdReceta;?>&F=<?php echo $fechacon;?>&IdArea=<?php echo $IdArea;?>');" />
<?php } ?>
      </span></div></td>
    </tr>
  </table>
</td></tr>
<tr><td align="center">
<?php 
		//Detalles de Receta
		$respDetalles=$query->datosReceta($IdReceta,$IdArea);?>
  <table class="MYTABLE" width="935" border="1">
    <tr class="MYTABLE">
      <td width="58"><div align="center"><strong>Cantidad</strong></div></td>
      <td width="149"><div align="center"><strong>Nombre de Medicina </strong></div></td>
      <td width="126"><div align="center"><strong>Concentraci&oacute;n</strong></div></td>
      <td width="193"><div align="center"><strong>Presentaci&oacute;n</strong></div></td>
      <td width="231"><strong>Dosificaci&oacute;n</strong></td>
      <td width="138"><div align="center"><strong>Satisfecho</strong></div></td>
    </tr>
    <?php 
	while($row2=mysql_fetch_array($respDetalles)){
		$cantidad=number_format($row2["Cantidad"],0,'.','');
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
      <td align="center"><?php echo"$NombreMedicina"; ?></td>
      <td align="center"><?php echo"$Concentracion"; ?></td>
      <td><?php echo htmlentities($Presentacion); ?></td>
      <td><?php echo"$dosis"; ?></td>
      <td align="center"><?php
		if($Estado=='P'){
			$combo=$IdHistorialClinico.$idmedicina; //Nombre generico del ComboBox 
			$NombreIdReceta='IdReceta'.$IdHistorialClinico;?>
	<input type="hidden" id="<?php echo $NombreIdReceta;?>" name="<?php echo $NombreIdReceta;?>" value="<?php echo $IdReceta;?>" />
<?php
		//if($IdPersonal==$Corr){
			if($EstadoMedicina=='I'){

			?>
	<select id="<?php echo "$combo"; ?>" name="<?php echo "$combo"; ?>" onChange="actualizaEstado(<?php echo $IdHistorialClinico;?>,<?php echo $idmedicina;?>,this.value)">
            <option value="NO" selected="selected">NO</option>
            <option value="SI">SI</option>
          </select> 
		  
         <?php 
		 }else{ ?>
		<select id="<?php echo "$combo"; ?>" name="<?php echo "$combo"; ?>" onChange="actualizaEstado(<?php echo $IdHistorialClinico;?>,<?php echo $idmedicina;?>,this.value)">
            <option value="SI" selected="selected">SI</option>
            <option value="NO">NO</option>
          </select>
		  <?php }//ESTADO MEDICINA
		  }else{?>
	<select id="temp" name="temp" disabled="disabled">
            <option value="SI" selected="selected">SI</option>
          </select> 		  
		  <?php }?></td>
    </tr>
    <?php } //fin de while?>
    <tr class="MYTABLE">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td align="center">&nbsp;</td>
    </tr>
	
  </table>
  </td></tr>
  <tr><td><hr style="color:#FF0000"></td></tr>
</table>

</div> 
 <?php echo"<br>";
}//fin de while respDatos
conexion::desconectar();
?>

</div>
</body>
</html>
<?php 

}else{?>
<script language="javascript">
window.location='../Principal/index.php?Permiso2=1';
</script>
<?php
}//fin de ELSE Nivel
?>