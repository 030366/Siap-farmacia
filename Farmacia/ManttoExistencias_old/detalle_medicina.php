<?php session_start();
if(!isset($_SESSION["nivel"])){?>
<script language="javascript">
window.location='../signIn.php';
</script>
<?php
}else{
if($_SESSION["nivel"]!=1 and $_SESSION["nivel"]!=2){?>
<script language="javascript">
window.location='../index.php?Permiso=1';
</script>
<?php
}else{
$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
$IdFarmacia=$_SESSION["IdFarmacia"];
$IdFarmacia2=$_SESSION["IdFarmacia2"];
require('../Clases/class.php');
$query=new queries;
conexion::conectar();
$page=$_REQUEST["page"]; 
//echo"$page";
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<title>Mantenimiento</title>
<script language="javascript" src="calendar.js"></script>
<script language="javascript">
var nav4 = window.Event ? true : false;
function acceptNum(evt){	
	var key = nav4 ? evt.which : evt.keyCode;	
	return ((key < 13) || (key >= 48 && key <= 57));
}

function acceptNum2(evt){	
	// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57	
	var key = nav4 ? evt.which : evt.keyCode;	
	return ((key < 13) || (key >= 48 && key <= 57) || key == 46);
}

function fijar(){
	document.formulario.nuevaCantidad.focus();
}

function valida(form){
	if(form.nuevaCantidad.value==0 && form.nuevoPrecio.value==0){
		alert('Para realizar cambios, almenos uno de los campos no deben de ser cero.-');
		form.nuevaCantidad.focus();
		return(false);
	}//If Cantidad == 0
	
	if(form.nuevaCantidad.value < 0){
		alert('La nueva existencia de medicina no puede ser menor que cero.-');
		form.nuevaCantidad.value="";
		form.nuevaCantidad.focus();
		return(false);
	}//Cantidad < 0
	
	if(form.nuevoPrecio.value < 0){
		alert('Introduzca un precio valido.-');
		form.nuevoPrecio.value="";
		form.nuevoPrecio.focus();
		return(false);
	}//nuevoPrecio < 0
}//fin valida

</script>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	left:53px;
	top:255px;
	width:826px;
	height:192px;
	z-index:1;
}
#Layer6 {position:absolute;
	left:25px;
	top:105px;
	width:955px;
	height:30px;
	z-index:2;
}
@media print {
	* { background: #fff; color: #000; }
	html { font: 100%/1.2 Arial, Helvetica, sans-serif; }
	#nav, #nav2, #about { display: none; }
	#footer { display:none;}
	#span{ color:#FFFFFF}
}
.style1 {color:#0000CC; font-size:11px; font-family:Arial, Helvetica, sans-serif}
#Layer3 {position:absolute;
	left:2px;
	top:190px;
	width:1001px;
	height:30px;
	z-index:6;
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
-->
</style>
</head>
<body onLoad="fijar()">
<script language="javascript" src="../tooltip/wz_tooltip.js"></script>
<?php   
//Obtencion del IDMEDICINA
$IdArea=$_SESSION["IdAreaFarmacia"];
$RespArea=mysql_query("select Area from mnt_areafarmacia where IdArea='$IdArea'");
$RowArea=mysql_fetch_array($RespArea);
$Area=$RowArea[0];
$idMedicina=$_REQUEST["p"];
$ventto='';
$respuesta=$query->ConfirmaExistencia($idMedicina,$IdArea,$ventto);
if(mysql_fetch_array($respuesta)){
	$info=$query->ObtenerDatosMedicina2($idMedicina,$IdArea);//obtencion de datos nuevo
}else{
	$info=$query->ObtenerDatosMedicina($idMedicina);//sino hay  datos aun en tabla
}
$med=mysql_fetch_array($info);
$IdMedicina=$med["IdMedicina"];
$CodigoMedicina=$med["Codigo"];
$NombreMedicina=$med["Nombre"];
$Concentracion=$med["Concentracion"];
$Presentacion=$med["FormaFarmaceutica"].", ".$med["Presentacion"];
$PrecioActual=$med["PrecioActual"];
if(isset($med["Existencia"])){$existencia=$med["Existencia"];}else{$existencia="Aun no hay datos";}
$terapeutico=$med["terapeutico"];
//*************************
?>

<form action="envioCantidad.php" method="post" name="formulario" onSubmit="return valida(this)">
<div id="Layer1">
<table width="902">
  <tr class="MYTABLE">
    <td colspan="6" align="center">&nbsp;<strong>FARMACIA: <span style="color:#FF0000"><?php echo $_SESSION["nombreFarmacia"];?></span><br>AREA: <span style="color:#FF0000"><?php echo $Area;?></span></span></strong></td>
    </tr>
  <tr>
    <td width="181" class="FONDO" align="center"><strong>Cod.Medicina</strong></td>
    <td colspan="2" align="center"  class="FONDO"><strong>Nombre</strong></td>
	<td width="170" align="center" class="FONDO"><strong>Concentracion</strong></td>
	<td width="255" colspan="2" align="center" class="FONDO"><strong>Grupo Terapeutico</strong></td>
  </tr>
  <tr>
    <td class="FONDO" align="center"><?php echo"$CodigoMedicina"; ?></td>
    <td colspan="2" align="center" class="FONDO"><?php echo"$NombreMedicina"; ?></td>
    <td class="FONDO" align="center"><?php echo"$Concentracion"; ?></td>
    <td colspan="2" class="FONDO" align="center"><?php echo"$terapeutico";?></td>
  </tr>
  <tr>
  <td colspan="6" class="FONDO" align="center"><span style="color:#000099"><strong>ACTUALIZACI&Oacute;N DE EXISTENCIAS</strong></span></td>
  </tr>
  <tr>
      <td class="FONDO"><strong>Existencia a la Fecha</strong></td>
    <td colspan="2" class="FONDO"><span <?php if($existencia<= 100){echo"style=\"color:#FF0000\"";}else{echo"style=\"color:#0000FF\"";}?>><?php echo"$existencia";?></span></td>
    <td class="FONDO"><strong>Cantidad Entrante </strong></td>
    <td colspan="2" class="FONDO"><input type="text" name="nuevaCantidad" size="4" maxlength="4" value="0" onFocus="if(this.value==0){this.value=''}" onBlur="if(this.value==''){this.value=0}" onKeyPress="return acceptNum(event)">      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr> 
  <tr>
    <td class="FONDO"><strong><strong>Presentacion</strong></strong></td>
    <td colspan="2" class="FONDO"><?php echo"$Presentacion"; ?></td>
    <td colspan="3" class="FONDO"><strong><?php //Fecha Ventto.:?></strong><strong>
<?php //      <input type="text" id="fecha" name="fecha" readonly="true" onClick="scwShow (this, event);Tip('Fecha de Vencimiento<br>de Medicamento',TEXTALIGN,'center')" onBlur="UnTip()">?>
    </strong></td>
    </tr>
  <tr>
  <td align="center" colspan="6" class="FONDO"><span style="color:#000099"><strong>ACTUALIZACI&Oacute;N DE PRECIO</strong></span></td>
  </tr>
  <tr>
    <td class="FONDO"><strong>Precio Actual</strong></td>
    <td width="151" class="FONDO">$&nbsp;<?php echo"$PrecioActual"; ?></td>
    <td width="121" class="FONDO"><strong>Nuevo Precio</strong></td>
    <td colspan="3" class="FONDO">$
      <input type="text" name="nuevoPrecio" size="4" maxlength="5" value="0" onFocus="if(this.value==0){this.value=''}" onBlur="if(this.value==''){this.value=0}" onKeyPress="return acceptNum2(event)"></td>
    </tr>
  <tr>
    <td colspan="6" class="FONDO"><div id="nav">
      <input type="hidden" name="page" id="page" value="<?php echo"$page"; ?>">
      <input type="hidden" name="IdMedicina" id="IdMedicina" value="<?php echo"$IdMedicina"; ?>">
	  <input type="hidden" name="IdArea" id="IdArea" value="<?php echo $IdArea;?>">
    </div></td>
    </tr>
  <tr>
    <td colspan="6" class="MYTABLE">
      <div id="nav2" align="right">
        <input type="submit" name="guardar" value="Guardar" tabindex="2" onMouseOver="Tip('<img src=\'../images/save.png\'>',CENTERMOUSE, true, OFFSETX,0)" onMouseOut="UnTip()" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099">
        &nbsp;
        <input type="button" name="regresar" value="Regresar" onClick="javascript:window.location='buscadorArea.php?page=<?php echo"$page";?>'" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099">
          </div></td>
    </tr>
</table>
</div>
</form>
<div class="style1" id="Layer6" align="center">
<?php encabezado::top($IdFarmacia2,$tipoUsuario,$nick,$nombre);?></div>
<div id="Layer3" align="center">
  <?php if($nivel==1){?>
<script webstyle4>document.write('<scr'+'ipt src="../xaramenu.js">'+'</scr'+'ipt>');document.write('<scr'+'ipt src="../MenuImages/menu_.js">'+'</scr'+'ipt>');/*img src="MenuImages/Menu.gif" moduleid="Default (Project)\Menu_off.xws"*/</script>
  <?php }else{?>
<script webstyle4>document.write('<scr'+'ipt src="../xaramenu.js">'+'</scr'+'ipt>');document.write('<scr'+'ipt src="../MenuImages/menucoadmin.js">'+'</scr'+'ipt>');/*img src="MenuImages/MenuCoAdmin.gif" moduleid="MenuCoAdmin (Project)\MenuCoAdmin_off.xws"*/</script>
  <?php }?>
</div>
<div id="Layer71">
  <div id="Layer41"><img src="../images/paisanito.jpg" alt="" width="195" height="94" /></div>
  <span class="style4">Ministerio de Salud P&uacute;blica y Asistencia Social </span></div>
</body>
</html>
<?php 
conexion::desconectar();
if(isset($_REQUEST["G"])){?>
<script language="javascript">
alert('La Nueva Cantidad ha sido guardada satisfactoriamente');
</script>
<?php }
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel ?>