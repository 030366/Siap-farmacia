<?php
include('../../Clases/class.php');
class Proceso{
	function Verificar($Ano,$IdEstablecimiento,$IdModalidad){
		$query="select Nombre,DATE_FORMAT(date(FechaHoraReg),'%d-%m-%Y') as Fecha
				from farm_usuarios
				inner join farm_cierre
				on farm_cierre.IdUsuarioReg=farm_usuarios.IdPersonal
				
				where AnoCierre='$Ano'
                                and farm_cierre.IdEstablecimiento=".$IdEstablecimiento." 
                                and farm_cierre.IdModalidad=$IdModalidad";
		$resp=mysql_query($query);
		return($resp);
	}

	function Cierre($Ano,$IdPersonal,$IdEstablecimiento,$IdModalidad){
		$query="insert into farm_cierre (AnoCierre,IdUsuarioReg,FechaHoraReg,IdEstablecimiento,IdModalidad) 
                                          values('$Ano','$IdPersonal',now(),$IdEstablecimiento,$IdModalidad)";
		mysql_query($query);
		
	}//Cierre


	function VerificarPeriodo($Periodo,$IdEstablecimiento,$IdModalidad){
		$query="select Nombre,DATE_FORMAT(date(FechaHoraReg),'%d-%m-%Y') as Fecha
				from farm_usuarios
				inner join farm_cierre
				on farm_cierre.IdUsuarioReg=farm_usuarios.IdPersonal
				
				where MesCierre='$Periodo'
                                and farm_cierre.IdEstablecimiento=".$IdEstablecimiento."
                                and farm_cierre.IdModalidad=$IdModalidad";
		$resp=mysql_query($query);
		return($resp);
	}

	function CierreMes($Periodo,$IdPersonal,$IdEstablecimiento,$IdModalidad){
		$query="insert into farm_cierre (MesCierre,IdUsuarioReg,FechaHoraReg,IdEstablecimiento,IdModalidad) 
                                          values('$Periodo','$IdPersonal',now(),$IdEstablecimiento,$IdModalidad)";
		mysql_query($query);
		
	}//Cierre


	
}//clase	


?>