<?php
$path='../';
include('ClasesActualizacion.php');
conexion::conectar();

/*	ACTUALIZACION DE PRECIOS	*/
	
	$Bandera=$_GET["Bandera"];
	$actualizar=new Actualizacion;
	
	switch($Bandera){
		case 1:
			$IdMedicina=$_GET["IdMedicina"];
			$Precio=$_GET["Precio"];
			$Ano=date('Y');
			$IdUsuarioReg=$_GET["IdUsuarioReg"]; 
			
			//	$Multiplicador=$actualizar->ObtenerUnidadMedida($IdMedicina);
			//	$Precio=$Precio*$Multiplicador;
			
			$PrecioActual=$actualizar->ObtenerPrecio($IdMedicina,$Ano);
			if($PrecioActual!=false){
				/*Si existen el precio para el ano actual se hace una actualizacion del precio*/
				$actualizar->ActualizarPrecio($IdMedicina,$Precio,$Ano,$IdUsuarioReg);
			}else{
				/*Si no hay precio introducido aun se ingresa el precio de la medicina a la base de datos*/
				$actualizar->IntroducirPrecio($IdMedicina,$Precio,$Ano,$IdUsuarioReg);
			}
			
			echo $actualizar->ObtenerPrecioActual($IdMedicina,$Ano);			
		break;
		
		
		
	}//switch



conexion::desconectar();
?>