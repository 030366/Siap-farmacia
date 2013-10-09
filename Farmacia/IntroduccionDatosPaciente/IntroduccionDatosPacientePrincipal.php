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
if($nivel!=3){?>
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
$conexion=new conexion;

//******Generacion del combo principal

function generaSelect2(){ //creacioon de combo para las Regiones
	$conexion=new conexion;
	$conexion->conectar();
	$consulta=mysql_query("select * from mnt_subespecialidad order by NombreSubEspecialidad");
	$conexion->desconectar();
	// Voy imprimiendo el primer select compuesto por los paises
	echo "<select name='IdEspecialidad' id='IdEspecialidad' onChange='cargaContenido8(this.id)' onmouseover=\"Tip('Selecci&oacute;n de Especialidad')\" onmouseout=\"UnTip()\">";
	echo "<option value='0'>SELECCIONE UNA ESPECIALIDAD</option>";
	while($registro=mysql_fetch_row($consulta)){
		if($registro[1]!="--"){
		echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
		}
	}
	echo "</select>";
}



//**********
//********** VALIDACION DE FECHAS*********
  /* $fechas = array();
   $fechas = explode("-",$fecha0);
   $ano = intval($fechas[0]);
   $mes = intval($fechas[1]);
   $dia = intval($fechas[2]);*/
//*****************

?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<title>...:::Introduccion de Recetas:::...</title>
<!--referencias del estilo del calendario-->
<link rel="stylesheet" type="text/css" media="all" href="calendar/skins/aqua/theme.css" title="Aqua" />
<link rel="alternate stylesheet" type="text/css" media="all" href="calendar/calendar-blue.css" title="blue" />
<!--llamado al archivo de funciones del calendario-->
<script language="javascript" src="IncludeFiles/calendar.js"></script>
<script language="javascript" src="IncludeFiles/IntroPacientes.js"></script>

<style type="text/css">
<!--
#Layer6 {
position:absolute;
	left:25px;
	top:105px;
	width:955px;
	height:30px;
	z-index:2;
}
#Layer1 {
	position:absolute;
	left:113px;
	top:265px;
	width:826px;
	height:192px;
	z-index:1;
}
.style1 {color:#0000CC; font-size:11px; font-family:Arial, Helvetica, sans-serif}
#Layer3 {
position:absolute;
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
#Layer2 {
	position:absolute;
	left:23px;
	top:571px;
	width:944px;
	height:155px;
	z-index:7;
}
-->
</style>
<script language="javascript">
function confirmacion(){
var resp=confirm('Desea Cancelar esta Acción?');
if(resp==1){
window.location='../IndexReportes.php';
}
}//confirmacion
</script>
</head>
<body>
<div id="Layer3" align="center">
  <?php if($nivel==3){?>
  <script webstyle4>document.write('<scr'+'ipt src="../xaramenu.js">'+'</scr'+'ipt>');document.write('<scr'+'ipt src="../MenuImages/menuconsultaexterna.js">'+'</scr'+'ipt>');/*img src="MenuImages/MenuConsultaExterna.gif" moduleid="MenuConExt (Project)\MenuConsultaExterna_off.xws"*/</script>
  <?php }?>
</div>
<div id="Layer71">
  <div id="Layer41"><img src="../images/paisanito.jpg" alt="" width="195" height="94" /></div>
  <span class="style4">Ministerio de Salud P&uacute;blica y Asistencia Social </span></div>

<div class="style1" id="Layer6" align="center">
  <?php
encabezado::top($IdFarmacia,$tipoUsuario,$nick,$nombre);

?>
</div>
<div id="Layer1">
<form action="" method="post" name="formulario">

  <table width="816" border="0">
    <tr class="MYTABLE">
      <td colspan="5" align="center"><strong>INTRODUCCI&Oacute;N DE DATOS</strong></td>
      </tr>
			<tr class="MYTABLE">
			  <td colspan="5" align="center"><strong>1. DATOS GENERALES DEL PACIENTE</strong></td>
			</tr>
			<tr>
			  <td colspan="5" class="FONDO"><strong>Primer Apellido: 
			    <input type="text" id="PrimerApellido" name="PrimerApellido">
		      Segundo Apellido: 
		      <input type="text" id="SegundoApellido" name="SegundoApellido">
			  </strong></td>
			</tr>
			<tr>
			  <td colspan="5" class="FONDO"><strong>Primer Nombre: 
			    <input type="text" id="PrimerNombre" name="PrimerNombre">
		      Segundo Nombre: 
		      <input type="text" id="SegundoNombre" name="SegundoNombre">
		      Tercer Nombre: 
		      <input type="text" id="TercerNombre" name="TercerNombre">
			  </strong></td>
			</tr>
			    <tr>
      <td width="127" class="FONDO"><strong>Sexo: </strong></td>
      <td width="679" colspan="4" class="FONDO"><select id="Sexo" name="Sexo">
	  <option value="0">[Seleccione...]</option>
	  <option value="1">Masculino</option>
	  <option value="2">Femenino</option>
      </select>      </td>
			    </tr>
			<tr class="MYTABLE">
			  <td colspan="5" align="center"><strong>2. FECHA DE NACIMINETO</strong></td>
			</tr>
    <tr>
      <td class="FONDO"><strong>Fecha de Nacimiento: </strong></td>
      <td colspan="4" class="FONDO"><input type="text" name="FechaNacimiento" id="FechaNacimiento" readonly="true" value="" onClick="scwShow (this, event);"/>		</td>
      </tr>
	  <tr class="MYTABLE">
	    <td colspan="5" align="center"><strong>3. FAMILIAR </strong></td>
	  </tr>
	  
	  
	  <tr>
      <td class="FONDO"><strong>Nombre de Madre: </strong></td>
      <td colspan="4" class="FONDO"><input type="text" name="NombreDeMadre" id="NombreDeMadre" size="60"/></td>
      </tr>
	      <tr> 
      <td colspan="5" class="FONDO" align="center">
        <input type='hidden' id='IdPaciente' name='IdPaciente'>
        <input type="button" id="BotonExpediente" name="BotonExpediente" value="Guardar Datos" onClick="javascript:valida();">
        <input type="button" id="Cancelar" name="Cancelar" value="Cancelar" onClick="javascript:window.location='../IntroduccionRecetas/IntroduccionRecetasPrincipal.php';"></td>
      </tr>
	  <tr>
      <td colspan="5" class="FONDO"><div id="IntroduccionExpediente" align="center"></div></td>
      </tr>
    <tr class="MYTABLE">
      <td colspan="5" align="right">&nbsp;</td>
    </tr>
  </table>
  </form>
</div>



 

</body>
</html>
<?php
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel
?>