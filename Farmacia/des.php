<?php session_start();
include('Clases/class.php');
conexion::conectar();
mysql_query("update farm_usuarios set Conectado='N' where IdPersonal=".$_SESSION["IdPersonal"]);
conexion::desconectar();


session_destroy();
echo'<link rel="stylesheet" type="text/css" href="default.css" media="screen" />';
if(isset($_REQUEST["Cuenta"])){?>
<script language="javascript">
window.location='signIn.php?Cuenta=1';
</script>
<?php }
if(isset($_REQUEST["succ"])){
?>
<script language="javascript">
window.location='signIn.php?succ=1';
</script>
<?php
}
else{
?>
<script language="javascript">
window.location='signIn.php';
</script>
<?php  } ?>

