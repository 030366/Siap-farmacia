<?php
class NuevoMedicamento{
	function ActualizarDatosGenerales($IdMedicina,$codigo,$nombre,$concentracion,$FormaFarmaceutica,$presentacion){
		$query="update farm_catalogoproductos set Codigo='$codigo',Nombre='$nombre', Concentracion='$concentracion', FormaFarmaceutica='$FormaFarmaceutica',Presentacion='$presentacion' where IdMedicina='$IdMedicina'";
		mysql_query($query);
	}
	function ActualizarGrupo($IdGrupo,$IdMedicina){
		$query="update farm_catalogoproductos set IdTerapeutico='$IdGrupo' where IdMedicina='$IdMedicina'";
		mysql_query($query);
	}
	function ActualizarUnidadMedida($IdUnidadMedida,$IdMedicina){
		$query="update farm_catalogoproductos set IdUnidadMedida='$IdUnidadMedida' where IdMedicina='$IdMedicina'";
		mysql_query($query);
	}

}//Fin de Clase

?>