<?php session_start();
include('../../Clases/class.php');
include('ActualizarClase.php');
conexion::conectar();
$IdPersonal=$_SESSION["IdPersonal"];
$Contra=$_GET["Contra"];
$update=new Cambios;

$update->ActualizarPassword($IdPersonal,$Contra);

echo "Cambio Realizado ! <br> Los Cambios tendran efecto la proxima vez que inicie sesion";

conexion::desconectar();
?>
