<?php session_start();
if(!isset($_SESSION["IdPersonal"])){
  echo "ERROR_SESSION";
}else{
require('./ClaseActualizaLotes.php');
conexion::conectar();

switch($_GET["Bandera"]){
case 1:
	//Eliminar existencia de area de farmacia
	$IdMedicina=$_GET["IdMedicina"];
	$IdLote=$_GET["IdLote"];
	$IdArea=$_GET["IdArea"];
	$IdExistenciaArea=$_GET["IdExistencia"];
		Actualiza::EliminarExistenciaxArea($IdMedicina,$IdExistenciaArea,$IdLote,$IdArea,$_SESSION["IdEstablecimiento"]);
		
		//echo "Eliminado".$IdMedicina." Lote".$IdLote." Area:".$IdArea;
	
break;

case 70:
	function ActualizaDatosLotes($Lote,$PrecioLote,$Vencimiento,$LoteOld){
	if($Lote!=''){
		$Lote=strtoupper($Lote);
		$queryUpdate="update farm_lotes set Lote='$Lote' where IdLote='$LoteOld'";
		mysql_query($queryUpdate);
	}
	if($PrecioLote!=0){
		$queryUpdate="update farm_lotes set PrecioLote='$PrecioLote' where IdLote='$LoteOld'";
		mysql_query($queryUpdate);
	}
	
	if($Vencimiento!='Ventto.'){
		$queryUpdate="update farm_lotes set FechaVencimiento='$Vencimiento' where IdLote='$LoteOld'";
		mysql_query($queryUpdate);
	}
	
	}//funcion ActualizaDatosLotes
	
	
	
	$Lote=$_GET["Lote"];
	$PrecioLote=$_GET["PrecioLote"];
	$FechaVencimiento=$_GET["FechaVencimiento"];
	$LoteOld=$_GET["LoteOld"];
	
	
	ActualizaDatosLotes($Lote,$PrecioLote,$FechaVencimiento,$LoteOld);
break;
}
conexion::desconectar();
}
?>