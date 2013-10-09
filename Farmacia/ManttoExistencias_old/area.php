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
$IdFarmacia2=0;
if($IdFarmacia2!=0){?>
<script language="javascript">window.location='estableceArea.php';</script>
<?php }else{
unset($_SESSION["IdFarmacia"]);
$IdFarmacia2=$_SESSION["IdFarmacia2"];
$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
require('../Clases/class.php');
$conexion=new conexion;
?>
<html>
<head>
<script language="javascript" src="procesos/Filtro.js"></script>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<title>...:::SELECCION DE FARMACIA:::...</title>
<style type="text/css">
<!--
#Layer6 {
	position:absolute;
	left:26px;
	top:46px;
	width:955px;
	height:30px;
	z-index:2;
}
#Layer1 {
	position:absolute;
	left:301px;
	top:251px;
	width:462px;
	height:93px;
	z-index:6;
}
.style1 {color:#0000CC; font-size:11px; font-family:Arial, Helvetica, sans-serif}
#Layer2 {
	position:absolute;
	left:263px;
	top:149px;
	width:443px;
	height:24px;
	z-index:7;
}
#Layer3 {
	position:absolute;
	left:2px;
	top:190px;
	width:1001px;
	height:30px;
	z-index:6;
}
.style4 {font-size: 24px}
#Layer41 {	position:absolute;
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
.style5 {color: #990000}
#Layer21 {	position:absolute;
	left:25px;
	top:105px;
	width:955px;
	height:30px;
	z-index:2;
}
-->
</style>
<script language="javascript">
function confirmacion(){
var resp=confirm('Desea Cancelar esta Acción?');
if(resp==1){
window.location='../index.php';
}
}//confirmacion

function valida(form){
if(form.area.value==0){
alert('Seleccione una area.-');
form.area.focus();
return(false);
}//
}//valida
</script>
</head>
<body>
<form action="estableceArea.php" name="formulario" method="post" onSubmit="return valida(this)">
  <div id="Layer1">
  <table width="453">
      <tr class="MYTABLE">
      <td colspan="3" align="center"><strong>&nbsp;ACTUALIZACI&Oacute;N DE PRECIOS</strong></td>
    </tr>
    <tr class="MYTABLE">
      <td colspan="3" align="center"><strong>&nbsp;Selección de &Aacute;rea </strong></td>
    </tr>
    <tr>
      <td width="80" class="FONDO">&nbsp;Farmacia:</td>
      <td colspan="2" class="FONDO">&nbsp;<select id="farmacia" name="farmacia" onChange="cargaContenido8(this.id)">
	  <option value="0">Seleccione una Farmacia</option>
	  <?php
	  $conexion->conectar();
	  $resp=mysql_query("select * from mnt_farmacia");
	  $conexion->desconectar();
	   while($row=mysql_fetch_array($resp)){
	  $IdFarmacia=$row["IdFarmacia"];
	  $Farmacia=$row["Farmacia"];
	  ?>
	  <option value="<?php echo"$IdFarmacia";?>"><?php echo"$Farmacia";?></option>
	  <?php }//fin de while?>
      </select>      </td>
    </tr>
	    <tr>
		     <td width="80" class="FONDO">&nbsp;&Aacute;rea:</td>
             <td colspan="2" class="FONDO">&nbsp;<select id="area" name="area" disabled="disabled">
	  <option value="0">Seleccione una Area</option>
      </select>      </td>
		</tr>
      <td colspan="3" class="FONDO" align="right"><input name="guardar" type="submit" value="Acceder" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099"></td>
      </tr>
    <tr class="MYTABLE">
      <td colspan="3" align="right">&nbsp;</td>
      </tr>
  </table>
</div>
</form>
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
<div class="style1" id="Layer21" align="center">
  <?php
encabezado::top($IdFarmacia2,$tipoUsuario,$nick,$nombre);
?><?php
if($_SESSION["primera"]==1){?>
  <br>
  <span class="style5"><a href="updateData.php" title="Actualizar Datos" style="color:#FFFFFF" onMouseOver="this.style.color='#FFCC00'" onMouseOut="this.style.color='#FFFFFF'">Usted ha iniciado Sesion por primera vez, por favor actualice sus datos personales y contrase&ntilde;a.-<br>
    Aqui.-</a></span>
  <?php }?>
</div>
</body>
</html>
<?php
}//Else $IdFarmacia!=0
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel
?>