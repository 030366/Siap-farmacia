<?php session_start();
if(isset($_SESSION["nivel"])){
include 'conexion.php';
$id=$_SESSION["ID"];
$nombre=$_REQUEST["nombre"];
$contra=$_REQUEST["contra"];
$contra=md5($contra);
$pregunta=$_REQUEST["pregunta"];
$respuesta=$_REQUEST["respuesta"];
$respuesta=strtoupper ($respuesta);

$queryUpdate="update farm_usuarios set password='$contra',Nombre='$nombre', primeraVez='2' where IdPersonal='$id' ";
$queryInsert="insert into usr_respquestion (Respuesta, IdSecretQuestion,IdPersonal) values('$respuesta','$pregunta','$id')";

conectar();
mysql_query($queryUpdate);
mysql_query($queryInsert);
desconectar();
$_SESSION["primera"]=2;
?>
<script language="javascript">
window.location='index.php?Updated=1';
</script>

<?php
}
else{
?>
<script language="javascript">
window.location='signIn.php';
</script>
<?php
}
?>
