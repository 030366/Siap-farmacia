<?php
include('../../Clases/class.php');
class Proceso{
	function CargarCierres($IdEstablecimiento,$IdModalidad){
		$query="select *
			from farm_cierre
			where left(MesCierre,4) not in (select AnoCierre 
							from farm_cierre 
							where (AnoCierre is not null or AnoCierre <> '')
                                                        and IdModalidad=$IdModalidad
							)
                        and farm_cierre.IdEstablecimiento=$IdEstablecimiento
                        and farm_cierre.IdModalidad=$IdModalidad
			order by MesCierre
			";
		$resp=mysql_query($query);
		return($resp);
	}



	function EliminarCierre($IdCierre,$IdEstablecimiento,$IdModalidad){
		$query="delete from farm_cierre where IdCierre=".$IdCierre." 
                                                and IdEstablecimiento=".$IdEstablecimiento." 
                                                and IdModalidad=$IdModalidad";
		if(mysql_query($query)){
			return true;
		}else{
			return false;
		}
		
	}//obtener medicina general


	


	
}//clase	


?>