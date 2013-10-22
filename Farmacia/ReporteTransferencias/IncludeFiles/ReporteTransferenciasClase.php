<?php
require('../../Clases/class.php');
class ReporteTransferencias{
	function ObtenerTransferencias($IdPersonal,$FechaInicio,$FechaFin){
		$querySelect="select farm_usuarios.Nombre,farm_catalogoproductos.Nombre,farm_catalogoproductos.Concentracion,
					farm_transferencias.Cantidad, mnt_areafarmacia.Area, farm_transferencias.IdAreaDestino,
					farm_transferencias.Justificacion,farm_transferencias.FechaTransferencia,farm_catalogoproductos.IdMedicina, Descripcion
					from farm_transferencias
					inner join farm_catalogoproductos
					on farm_catalogoproductos.IdMedicina=farm_transferencias.IdMedicina
					inner join mnt_areafarmacia
					on mnt_areafarmacia.IdArea=farm_transferencias.IdAreaOrigen
					inner join farm_usuarios
					on farm_usuarios.IdPersonal=farm_transferencias.IdPersonal
					inner join farm_unidadmedidas
					on farm_unidadmedidas.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida
					where farm_transferencias.IdPersonal='$IdPersonal'
					and farm_transferencias.FechaTransferencia between '$FechaInicio' and '$FechaFin' 
					order by FechaTransferencia";
		$resp=mysql_query($querySelect);
		return($resp);
	}//ObtenerTransferencias
	
	
	function ObtenerUsuarios($IdPersonal){
	switch($IdPersonal){
		case 0:
		$querySelect="select distinct farm_usuarios.IdPersonal,farm_usuarios.Nombre
					from farm_usuarios
					inner join farm_transferencias
					on farm_transferencias.IdPersonal=farm_usuarios.IdPersonal";
		$resp=mysql_query($querySelect);
		return($resp);
		
		break;
		default:
		$querySelect="select farm_usuarios.IdPersonal,farm_usuarios.Nombre
					from farm_usuarios
					where farm_usuarios.IdPersonal='$IdPersonal'";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp[1]);
		break;
					
	}//switch
	}//ObtenerUsuarios
	
	
	function ObtenerNombreArea($IdArea){
		$querySelect="select Area from mnt_areafarmacia where IdArea='$IdArea'";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		if($resp!=NULL){
			return($resp[0]);
		}else{
			$resp="Fuera de las Areas de Farmacia";
			return($resp);
		}		
	}//NombreArea
	
	function ValorDivisor($IdMedicina){
	   $SQL="select DivisorMedicina from farm_divisores where IdMedicina=".$IdMedicina;
	   $resp=mysql_query($SQL);
	   return($resp);
    	}
	
	function UnidadesContenidas($IdMedicina){
	  $SQL="select UnidadesContenidas,Descripcion
		from farm_unidadmedidas fu
		inner join farm_catalogoproductos fcp
		on fcp.IdUnidadMedida = fu.IdUnidadMedida
		where IdMedicina=".$IdMedicina;
	  $resp=mysql_fetch_array(mysql_query($SQL));
	  return($resp[0]);
	}



}//ReporteTransferencias
?>