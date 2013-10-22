<?php session_start();
if(!isset($_SESSION["nivel"])){?>
	<script language="javascript">
		window.location="../signIn.php";
	</script>
<?php }else{
include ("../Clases/class.php");

$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
$IdFarmacia=$_SESSION["IdFarmacia2"];

?>
<html>
<head>
<title>...::: PETICION DE MEDICAMENTOS :::...</title>
	<script type="text/javascript" src="scripts/prototype.js"></script>
	<script type="text/javascript" src="scripts/autocomplete.js"></script>
	
	<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="styles/autocomplete.css" />
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
	z-index:10;
}
#Layer1 {
	position:absolute;
	left:2px;
	top:245px;
	width:1002px;
	height:76px;
	z-index:7;
}
.style1 {color:#0000CC; font-size:11px; font-family:Arial, Helvetica, sans-serif}
-->
</style>
</head>
<body onLoad="Fijar();">
<div id="Layer71">
  <div id="Layer41"><img src="../images/paisanito.jpg" alt="" width="195" height="94" /></div>
  <span class="style4">Ministerio de Salud P&uacute;blica y Asistencia Social </span></div>
<div id="Layer1" align="center">
<table width="77%" align="center">
  <tr>
    <td colspan="3" align="center" class="MYTABLE">PETICION DE MEDICAMENTO A ALMACEN </td>
    </tr>
  <tr>
    <td width="15%" class="FONDO">Medicamento:</td>
    <td colspan="2" class="FONDO3"><span class="SaladDataTD">
      <input type="text" id="NombreMedicina" name="NombreMedicina" size="60" onKeyPress="Limpieza(event,this.value);"/>
    </span><span class="SaladAltDataTD">
    <input type="hidden" name="IdMedicina" id="IdMedicina" size="20">
    </span></td>
    </tr>
  <tr class="FONDO2">
    <td class="FONDO">Cantidad:</td>
    <td width="43%" class="FONDO3"><br>
      <input type="text" id="Cantidad" name="Cantidad"><div id="Medida"></div><input type="hidden" id="UnidadesContenidas" name="UnidadesContenidas">
	  <input type="hidden" id="IdPedido" name="IdPedido">      </td>
    <td width="42%" class="FONDO" style="vertical-align:top;"><div align="center"><strong>DETALLE DE EXISTENCIAS</strong></div>
     <br>
     <div id="Informacion" align="center">&nbsp;</div></td>
    </tr>
  <tr class="FONDO2">
    <td>&nbsp;</td>
    <td><input type="button" id="Entrega2" name="Entrega2" onClick="javascript:Validacion2();" value="Agregar Medicamento" style="width:150px; height:40px; font-size:14px; visibility:hidden; width:0px">
      <input type="button" id="Entrega" name="Entrega" onClick="javascript:Validacion();" value="Agregar Medicamento" style="width:150px; height:40px; font-size:14px;"> &nbsp;&nbsp;
      <input type="button" id="Terminar" name="Terminar" onClick="javascript:FinalizarPedido();" value="Terminar Solicitud" style="width:150px; height:40px; font-size:14px;" disabled="disabled"></td>
    <td align="center"><input type="button" id="Cancelar" name="Cancelar" value="Cancelar" onClick="CancelarTodo();" style="visibility:hidden;width:145px; height:40px; font-size:14px;">&nbsp;&nbsp;
      <input type="button" id="imprimir" name="imprimir" value="Imprimir" onClick="Imprimir();document.getElementById('Terminar').disabled=false;" style="visibility:hidden;width:145px; height:40px; font-size:14px;"></td>
    </tr>
  <tr class="FONDO2">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><div id="Movimientos">&nbsp;</div></td>
    </tr>
</table>
</div>
	<script>
		new Autocomplete('NombreMedicina', function() { 
			return 'respuesta.php?q=' + this.value +'&Bandera=1'; 
		});
	</script>
	<div class="style1" id="Layer6" align="center">
      <?php
encabezado::top($IdFarmacia,$tipoUsuario,$nick,$nombre);

?>
    </div>
    <div id="Layer3" align="center">
      <?php if($nivel==1){?>
      <script webstyle4>document.write('<scr'+'ipt src="../xaramenu.js">'+'</scr'+'ipt>');document.write('<scr'+'ipt src="../MenuImages/menu_.js">'+'</scr'+'ipt>');/*img src="MenuImages/Menu.gif" moduleid="Default (Project)\Menu_off.xws"*/</script>
      <?php }else{?>
      <script webstyle4>document.write('<scr'+'ipt src="../xaramenu.js">'+'</scr'+'ipt>');document.write('<scr'+'ipt src="../MenuImages/menucoadmin.js">'+'</scr'+'ipt>');/*img src="MenuImages/MenuCoAdmin.gif" moduleid="MenuCoAdmin (Project)\MenuCoAdmin_off.xws"*/</script>
      <?php }?>
    </div>
</body>
</html>
<?php } //Else isset NIVEL?>&nbsp;&nbsp;&nbsp;