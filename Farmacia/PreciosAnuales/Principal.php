<?php session_start();

if(!isset($_SESSION["nivel"])){?>
	<script language="javascript">
	window.location='../signIn.php';
	</script>
<?php
}else{
if(isset($_SESSION["IdFarmacia2"])){
	$IdFarmacia=$_SESSION["IdFarmacia2"];
}
$nivel=$_SESSION["nivel"];
if($nivel!=3 and $nivel !=4 and $nivel !=1 and $nivel!=2){?>
	<script language="javascript">
	window.location='../index.php?Permiso=1';
	</script>
<?php
}else{
$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
require('../Clases/class.php');

	if($_SESSION["IdPersonal"]!=1 and $_SESSION["IdPersonal"]!=39 and $_SESSION["IdPersonal"]!=79 and $_SESSION["IdPersonal"]!=48 and $_SESSION["IdPersonal"]!=65){?>
	<script language="javascript">
		alert('No posee sufientes privilegios para acceder!');
		window.location='../IngresoRecetasTodas/IntroduccionRecetasPrincipal.php';
	</script>	
	<?php }?>
<html>
<head>
<script language="javascript" src="IncludeFiles/Cierre.js"></script>
<title>Inicializacion de Precios Anuales</title>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<style type="text/css">
<!--
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
.style1 {color:#0000CC; font-size:11px; font-family:Arial, Helvetica, sans-serif}
#Layer6 {position:absolute;
	left:25px;
	top:105px;
	width:955px;
	height:30px;
	z-index:2;
}
#Layer3 {position:absolute;
	left:2px;
	top:190px;
	width:1001px;
	height:30px;
	z-index:6;
}
#Layer1 {	position:absolute;
	left:1px;
	top:250px;
	width:1004px;
	height:88px;
	z-index:0;
}
-->
</style>
</head>

<body>
<div id="Layer71">
  <div id="Layer41"><img src="../images/paisanito.jpg" alt="" width="195" height="94" /></div>
  <span class="style4">Ministerio de Salud P&uacute;blica y Asistencia Social </span></div>
<div class="style1" id="Layer6" align="center">
  <?php
encabezado::top($IdFarmacia,$tipoUsuario,$nick,$nombre);

if($_SESSION["primera"]==1){?>
  <br>
  <a href="../updateData.php" title="Actualizar Datos" style="color:#FF0000" onMouseOver="this.style.color='#000099'" onMouseOut="this.style.color='#FF0000'">Usted ha iniciado Sesion por primera vez, por favor actualice sus datos personales y contrase&ntilde;a.-<br>
    Aqui.-</a>
  <?php }?>
</div>
<div id="Layer3" align="center">
  <?php if($nivel==1){?>
  <script webstyle4>document.write('<scr'+'ipt src="../xaramenu.js">'+'</scr'+'ipt>');document.write('<scr'+'ipt src="../MenuImages/menu_.js">'+'</scr'+'ipt>');/*img src="MenuImages/Menu.gif" moduleid="Default (Project)\Menu_off.xws"*/</script>
  <?php }elseif($nivel==4){?>
  <script webstyle4>document.write('<scr'+'ipt src="../xaramenu.js">'+'</scr'+'ipt>');document.write('<scr'+'ipt src="../MenuImages/menudigitador.js">'+'</scr'+'ipt>');/*img src="MenuImages/MenuConsultaExterna.gif" moduleid="MenuConExt (Project)\MenuConsultaExterna_off.xws"*/</script>
  <?php }else{?>
  <script webstyle4>document.write('<scr'+'ipt src="../xaramenu.js">'+'</scr'+'ipt>');document.write('<scr'+'ipt src="../MenuImages/menucoadmin.js">'+'</scr'+'ipt>');/*img src="MenuImages/MenuCoAdmin.gif" moduleid="MenuCoAdmin (Project)\MenuCoAdmin_off.xws"*/</script>
  <?php }?>
</div>
<div id="Layer1" align="center">
  <table width="528" align="center">
    <tr class="MYTABLE">
      <td colspan="2" align="center"><strong>
        <h2>Inicializaci&oacute;n de Precios Anuales </h2>
      </strong></td>
    </tr>
    <tr class="FONDO">
      <td width="246" align="right">A&ntilde;o:</td>
      <td width="435"><input type="text" maxlength="4" id="ano" name="ano" onKeyPress="return acceptNum(event,this.id);">
          <input type="hidden" id="IdPersonal" name="IdPersonal" value="<?php echo $_SESSION["IdPersonal"];?>"></td>
    </tr>
    <tr class="FONDO">
      <td colspan="2" align="right"><input type="button" id="Cerrar" name="Cerrar" style="width:150; height:60" value="Configurar Precios" onClick="valida();"></td>
    </tr>
    <tr class="MYTABLE">
      <td colspan="2"><p>*Importante: La configuracion de precios pueden ser modificados en la opcion del menu principal Mantenimiento -&gt; Actualizacion de Precios</p></td>
    </tr>
    <tr class="MYTABLE">
      <td colspan="2"><div id="Respuesta">&nbsp;</div></td>
    </tr>
  </table>
</div>
</body>
</html>
<?php
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel
?>