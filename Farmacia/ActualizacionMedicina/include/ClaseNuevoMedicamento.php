<?php
class NuevoMedicamento{
	function ActualizarDatosGenerales($cnx,$IdMedicina,$codigo,$nombre,$concentracion,$FormaFarmaceutica,$presentacion){
		$query="update farm_catalogoproductos set Codigo='$codigo',Nombre='$nombre', Concentracion='$concentracion', FormaFarmaceutica='$FormaFarmaceutica',Presentacion='$presentacion' where IdMedicina='$IdMedicina'";
		pg_query($cnx,$query);
	}
	function ActualizarGrupo($cnx,$IdGrupo,$IdMedicina){
		$query="update farm_catalogoproductos set IdTerapeutico='$IdGrupo' where IdMedicina='$IdMedicina'";
		pg_query($cnx,$query);
	}
	function ActualizarUnidadMedida($cnx,$IdUnidadMedida,$IdMedicina){
		$query="update farm_catalogoproductos set IdUnidadMedida='$IdUnidadMedida' where IdMedicina='$IdMedicina'";
		pg_query($cnx,$query);
	}

}//Fin de Clase
