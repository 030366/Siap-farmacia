<?php session_start();
include('CierreClase.php');
$Bandera=$_GET["Bandera"];
$proceso=new Proceso;
conexion::conectar();
switch($Bandera){
	case 1:
		$Ano=$_GET["Ano"];
		$IdPersonal=$_GET["IdPersonal"];
		
		$medicinasGeneral=$proceso->ObtenerMedicinasGeneral();
		
		while($row=mysql_fetch_array($medicinasGeneral)){
			$IdMedicina=$row["IdMedicina"];
			
			$Existencia=$proceso->VerificaExistencia($IdMedicina,$Ano);
			
			if($Existencia==false){
				
				$Precio=$proceso->ObtenerPrecioAnterior($IdMedicina);

				$proceso->ConfigurarPrecio($IdMedicina,$Precio,$IdPersonal,$Ano);
			
			
			}else{
			//Nada	
			
			}
								
			
			
		}
		
		echo "Proceso Finalizado con exito!";
		
		
	break;
	case 2:
		//Periodo de Cierre
		$Periodo=$_GET["Periodo"];
		$IdPersonal=$_GET["IdPersonal"];

		$verificacion=$proceso->VerificarPeriodo($Periodo);
		if($resp=mysql_fetch_array($verificacion)){
			echo "El Periodo ".$Periodo." ya fue cerrado por el usuario ".$resp["Nombre"]." en la fecha ".$resp["Fecha"];
		}else{
			$proceso->CierreMes($Periodo,$IdPersonal);
			echo "Cierre Realizado con Exito !";
		}
		
		
	break;	
	
}//switch
conexion::desconectar();
?>