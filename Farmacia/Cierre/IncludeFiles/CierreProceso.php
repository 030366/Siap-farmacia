<?php session_start();
include('CierreClase.php');
$Bandera=$_GET["Bandera"];
$proceso=new Proceso;
conexion::conectar();

//var general
$IdModalidad=$_SESSION["IdModalidad"];

switch($Bandera){
	case 1:
		$Ano=$_GET["Ano"];
		$IdPersonal=$_GET["IdPersonal"];
		
		$verificacion=$proceso->Verificar($Ano,$_SESSION["IdEstablecimiento"],$IdModalidad);
		if($resp=mysql_fetch_array($verificacion)){
			echo "El año ".$Ano." ya fue cerrado por el usuario ".$resp["Nombre"]." en la fecha ".$resp["Fecha"];
		}else{
			$proceso->Cierre($Ano,$IdPersonal,$_SESSION["IdEstablecimiento"],$IdModalidad);
			echo "Cierre Realizado con Exito !";
		}
		
		
	break;
	case 2:
		//Periodo de Cierre
		$Periodo=$_GET["Periodo"];
		$IdPersonal=$_GET["IdPersonal"];

		$verificacion=$proceso->VerificarPeriodo($Periodo,$_SESSION["IdEstablecimiento"],$IdModalidad);
		if($resp=mysql_fetch_array($verificacion)){
			echo "El Periodo ".$Periodo." ya fue cerrado por el usuario ".$resp["Nombre"]." en la fecha ".$resp["Fecha"];
		}else{
			$proceso->CierreMes($Periodo,$IdPersonal,$_SESSION["IdEstablecimiento"],$IdModalidad);
			echo "Cierre Realizado con Exito !";
		}
		
		
	break;	
	
}//switch
conexion::desconectar();
?>