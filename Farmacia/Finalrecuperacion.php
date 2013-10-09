<?php session_start();
if(isset($_SESSION["ErrS"])){
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="default.css" media="screen" />
<title>....::: Recuperacion de Contraseña</title>
<style type="text/css">
<!--
#Layer2 {	position:absolute;
	left:305px;
	top:104px;
	width:419px;
	height:21px;
	z-index:2;
}
#Layer1 {
	position:absolute;
	left:178px;
	top:148px;
	width:680px;
	height:143px;
	z-index:3;
}
.style1 {color: #FF0000}
.style2 {
	color: #FFFFFF;
	font-weight: bold;
}
-->
</style>
<script language="javascript">
function valida(){
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
}//valida
</script>
</head>
<body>
<?php
include 'conexion.php';
$nick=$_REQUEST["nick"];
$respuesta=$_REQUEST["respuesta"];
$respuesta=strtoupper ($respuesta);


$querySelect="select farm_usuarios.*,usr_respquestion.*,usr_secretquestion.* from farm_usuarios
inner join usr_respquestion
on usr_respquestion.IdPersonal=farm_usuarios.IdPersonal
inner join usr_secretquestion
on usr_secretquestion.IdSecretQuestion=usr_respquestion.IdSecretQuestion
where farm_usuarios.nick='$nick' and usr_respquestion.Respuesta='$respuesta'";
conectar();
$resp=mysql_query($querySelect);
desconectar();


if($row=mysql_fetch_array($resp)){
$id=$row["IdPersonal"];
$_SESSION["ID"]=$id
?>

<div align="center" class="style2" id="Layer2">RECUPERACI&Oacute;N DE CONTRASE&Ntilde;AS </div>
<div id="Layer1">
<form action="recuperacionTerminada.php" method="post" name="form" onSubmit="return valida(this)">
  <table width="669" border="0">
    <tr class="MYTABLE">
      <td colspan="2">&nbsp;</td>
      </tr>
    <tr>
      <td width="236" class="FONDO">&nbsp;Usuario (Nick): </td>
      <td class="FONDO">&nbsp;<input type="text" name="nick" size="50" readonly="true" value="<?php echo $nick; ?>"></td>
      </tr>
    <tr>
      <td class="FONDO">&nbsp;Nueva Contrase&ntilde;a (Password) : </td>
      <td class="FONDO">&nbsp;<input type="password" name="contra" maxlength="15">
        <span class="style1">*</span></td>
      </tr>
    <tr>
      <td class="FONDO">&nbsp;Confirmaci&oacute;n de Contrase&ntilde;a:</td>
      <td class="FONDO">&nbsp;<input type="password" name="contra2" maxlength="15">
        <span class="style1"> *</span></td>
      </tr>
    <tr>
      <td colspan="2" align="right" class="FONDO"><input type="submit" name="enviar" value="Re-Establecer Contraseña" title="Re-Establecer Contraseña"></td>
      </tr>
    <tr class="MYTABLE">
      <td colspan="2" align="center">Paso 3 de 3 </td>
      </tr>
  </table>
  </form>
</div>
</body>
</html>
<?php
}//fin IF fetch_array
else{?>
<script language="javascript">
window.location='recuperacion.php?Err=1&nick=<?php echo"$nick"; ?>&ErrS=1';
</script>
<?php
}


}//fin de IF isset principal
else{
?>
<script language="javascript">
window.location='index.php';
</script>
<?php
}
?>