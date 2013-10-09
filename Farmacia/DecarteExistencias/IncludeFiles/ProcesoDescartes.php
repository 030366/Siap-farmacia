<?php session_start();
if(!isset($_SESSION["nivel"])){
echo "ERROR_SESSION";
}else{
include('ClasesDescartes.php');
conexion::conectar();
$proc=new Descartes;

switch($_GET["Bandera"]){
   case 1:
	//
	
   break;





}//switch
conexion::desconectar();
}//session vlida
?>