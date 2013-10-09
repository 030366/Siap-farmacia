<?php  session_start();
$IdArea=$_SESSION["IdArea"];
require('../Clases/class.php');
require('IncludeFiles/DiasClase.php');
$query=new queries;
$query2=new Repetitivas;
$proceso=new Lotes;
conexion::conectar();
/**VALORES POR POST**/
$Lista=1;
$IdReceta=$_GET["IdReceta"];

	mysql_query("update farm_recetas set IdEstado='E' where IdReceta='$IdReceta'");
/** VALORES PARA AJAX**/

//**********************************************************************
if($Lista==1){
	//Se obtiene el detalle de la receta para determinar el consumo de medicamentos
		$resp=$query2->MedicinaReceta($IdReceta);

		while($row=mysql_fetch_array($resp)){
		
		$IdReceta=$row["IdReceta"];
		$satisfecha=$row["IdEstado"];
		$IdHistorialClinico=$row["IdHistorialClinico"];
		//********** Datos para manejo de lotes ***********
		$IdMedicina=$row["IdMedicina"];
		$IdMedicinaRecetada=$row["IdMedicinaRecetada"];
		$Cantidad=$row["Cantidad"];
		$IdArea=$row["IdArea"];
		//*************************************************

			if($satisfecha != "I"){
			//Satisfechas
			//Se realiza la disminucion de existencias del inventario y se deja constancia
			//en farm_medicinadespachada de los movimientos realizados....
		      $proceso->ActualizarInventario($IdMedicina,$IdMedicinaRecetada,$Cantidad,$IdArea);
				queries::InsertarDatosReceta($IdMedicina,$IdReceta,$IdHistorialClinico);

			}else{
			//Insatisfechas
				queries::InsertarDatosReceta2($IdMedicina,$IdReceta,$IdHistorialClinico);
			}
		}//fin de while


conexion::desconectar();

	}//IF Listo
		 
?>