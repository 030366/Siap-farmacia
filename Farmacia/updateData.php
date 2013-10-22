<?php  session_start();
if(isset($_SESSION["primera"])){
    if($_SESSION["nivel"]==1){?>
	<script language="javascript">
	window.location='index.php';
	</script>
	<?php
	}//-nivel

	if($_SESSION["primera"]==1){
$tipoUsuario=$_SESSION["tipo_usuario"];
if(isset($_SESSION["nombre"])){
$nombre=$_SESSION["nombre"];}
else{
$nombre="<strong>Aun no esta actulizado su perfil.-</strong>";
}

	?>

<html>
<head>
<title>...::: Actualización de Datos Personales :::...</title>
<style type="text/css">
#Layer2 {position:absolute;
	left:38px;
	top:17px;
	width:715px;
	height:30px;
	z-index:2;
}
#Layer1 {
	position:absolute;
	left:55px;
	top:76px;
	width:697px;
	height:165px;
	z-index:3;
}
.style1 {color: #FF0000}
.style2 {
	font-size: xx-small;
	color: #CC6600;
}
</style>
<script language="javascript">
function valida(form){
if(form.nombre.value==""){
alert('Introduzca su nombre Completo.-');
form.nombre.focus();
return(false);
}//nombre

if(form.contra.value==""){
alert('Debe introducir una contraseña valida');
form.contra.focus();
return(false);
}//contra

if(form.contra2.value==""){
alert('Confirme su contraseña');
form.contra2.focus();
return(false);
}//contra2

if(form.contra.value != form.contra2.value){
alert('La confirmación de la contraseña no es igual\n o ha cometido un error de escritura\n Vuelva a introducir su nueva contraseña');
form.contra.focus();
return(false);
}


if(form.pregunta.value==0){
alert('Debe seleccionar una pregunta secreta\n Esto le ayudara en dado caso olvidara su contraseña');
form.pregunta.focus();
return(false);
}//pregunta

if(form.respuesta.value==""){
alert('Debe proporcionar una respuesta valida a la pregunta seleccionada');
form.respuesta.focus();
return(false);
}//respuesta


}//valida

function fijar(){
document.form.nombre.focus();
}

</script>

</head>
<body onLoad="fijar()">
<div id="Layer2">
  <?php
 include 'conexion.php';
 $nick=$_SESSION["nick"];
echo"<strong>Nombre de Usuario:</strong>&nbsp;&nbsp; $nombre </br>
<strong>Tipo de Usuario:</strong>&nbsp;&nbsp;$tipoUsuario<br>
<strong>Nick:</strong>&nbsp;&nbsp;$nick<br>";
?>
</div>
<div id="Layer1">
<form action="actualizacion.php" method="post" name="form" onSubmit="return valida(this)">
  <table width="682" border="0">
    <tr>
      <td colspan="4" align="center"><strong>Actualizaci&oacute;n de Datos</strong> </td>
    </tr>
    <tr>
      <td width="201">Usuario (Nick): </td>
      <td width="339"><input type="text" name="textfield" size="40" readonly="true" value="<?php echo "$nick"; ?>"></td>
      <td width="82">&nbsp;</td>
      <td width="42">&nbsp;</td>
    </tr>
    <tr>
      <td>Nombre:</td>
      <td><p>
        <input type="text" name="nombre" maxlength="50" size="50" tabindex="1">
        <span class="style1">*</span><br>
        <span class="style2">Ej: Aguilar Melendez, Juan Pablo </span></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Nueva Contrase&ntilde;a (Password): </td>
      <td><input type="password" name="contra" maxlength="15" tabindex="2">
        <span class="style1">*</span></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Confirmaci&oacute;n de Contrase&ntilde;a: </td>
      <td><input type="password" name="contra2" maxlength="15" tabindex="3">
        <span class="style1">      *</span></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Pregunta Secreta: </td>
      <td>
	  <?php 
	  conectar();
	  $resp=mysql_query("select * from usr_secretquestion");
	   desconectar();
	   ?>
	  <select name="pregunta" tabindex="4">
	  <option value="0">Seleccione una Pregunta</option>
	  <?php while($row=mysql_fetch_array($resp)){?>
	  <option value="<?php echo $row["IdSecretQuestion"];?>"><?php echo $row["SecretQuestion"]; ?></option>
	  <?php } ?>
      </select>
	  <span class="style1">*</span> </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="26">Respuesta:</td>
      <td><input type="text" name="respuesta" maxlength="50" size="50" tabindex="5">
        <span class="style1">*</span></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" align="right"><input type="submit" name="guardar" value="Actualizar Datos" tabindex="6"></td>
      </tr>
  </table>
 </form>
</div>
</body>
</html>
	<?php
	}//sessiop primer == 1
	else{
	?>
	<script language="javascript">
	window.location='index.php';
	</script>	
	<?php	
	}


}//Primera
else{
?>
<script language="javascript">
window.location='signIn.php';
</script>
<?php
}

?>
