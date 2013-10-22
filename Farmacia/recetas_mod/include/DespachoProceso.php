<?php 
include('../../Clases/class.php');
include('DespachoClases.php');
conexion::conectar();
$proceso=new Despacho;
	
	switch($_GET["Bandera"]){
	   case 1:
		$IdNumeroExp=$_GET["IdNumeroExp"];
		$IdArea=$_GET["IdArea"];
		$resp=$proceso->ObtenerReceta($IdNumeroExp,$IdArea);
		
	//Muestra el detalle del medicamento a ser despachado
		
		
		
		
	   break;
	
	   case 2:
		
		
		
	   break;
	   case 3:
		
		
		
	   break;
	   case 4:
		
		
		
	   break;
	   case 5:
		
		
		
	   break;
	   case 6:
		
		
		
	   break;
	   case 7:
		
		
		
	   break;	
	
	}//switc
conexion::desconectar();
?>