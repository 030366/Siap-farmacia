<?php 
include('../../Clases/class.php');
class Actualizaciones{
	function DatosGenerales($pagina){
		$querySelect="select IdMedicina,Nombre,Concentracion,FormaFarmaceutica,Presentacion,IdEstado
					from farm_catalogoproductos
					where (IdEstado='H' or IdEstado='I')
					order by Nombre
					LIMIT $pagina,20";
		$resp=mysql_query($querySelect);
		return($resp);		
	}//Datos Generales
	
	function BusquedaMedico($CodigoFarmacia,$NombreMedicina){
	if($CodigoFarmacia !=''){
		$filtro="CodigoFarmacia='$CodigoFarmacia'";
	}
	if($NombreMedicina!=''){
		$filtro="Nombre like '%$NombreMedicina%'";
	}
		$querySelect="select IdMedicina,Nombre,Concentracion,FormaFarmaceutica,Presentacion,IdEstado
					from farm_catalogoproductos
					where (IdEstado='H' or IdEstado='I')
					and $filtro
					order by Nombre";
		$resp=mysql_query($querySelect);
		return($resp);	}//BusquedaMedico
	
	
	function Tope(){
		$querySelect="select count(IdMedicina)
					from farm_catalogoproductos
					where(IdEstado='H' or IdEstado='I')
					order by Nombre";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp[0]);
	}
	
	function CodigoActualFarmacia($IdSubEspecialidad){
		$querySelect="select CodigoFarmacia
					from mnt_subespecialidad
					where IdSubEspecialidad='$IdSubEspecialidad'";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp[0]);
	}
	
	function SubEspecialidad($IdSubEspecialidad){
		$querySelect="select IdSubEspecialidad,NombreSubEspecialidad
					from mnt_subespecialidad
					where IdSubEspecialidad=".$IdSubEspecialidad;
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp[1]);		
	}//SubEspecialidad
	
	function MedicoSubEspecialidad($IdEmpleado){
		$querySelect="select NombreSubEspecialidad
					from mnt_subespecialidad
					inner join mnt_empleados
					on mnt_empleados.IdSubEspecialidad=mnt_subespecialidad.IdSubEspecialidad
					where IdEmpleado='$IdEmpleado'";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp[0]);		
	}//MedicoSubEspecialidad
	
	function ActualizarCodigoFarmacia($IdSubEspecialidad,$CodigoNuevo){
		$queryUpdate="update mnt_subespecialidad set CodigoFarmacia='$CodigoNuevo' where IdSubEspecialidad='$IdSubEspecialidad'";
		mysql_query($queryUpdate);
	}//Actualiza Codigo
	
	function VerificaCodigo($IdSubEspecialidad,$CodigoNuevo){
		$querySelect="select IdSubEspecialidad from mnt_subespecialidad where CodigoFarmacia='$CodigoNuevo' and IdSubEspecialidad <> '$IdSubEspecialidad'";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp[0]);
	}//varificacion de Codigo
	

	
	function VerificaEstadoMedico($IdMedicina){
		$querySelect="select IdEstado
					from farm_catalogoproductos
					where IdMedicina='$IdMedicina'";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp[0]);
	}
	
	function ActualizaEstadoCuenta($IdMedicina,$NuevoEstado){
		$queryUpdate="update farm_catalogoproductos set IdEstado='$NuevoEstado' where IdMedicina='$IdMedicina'";
		mysql_query($queryUpdate);
		
	}
	
}//Clase Actualizaciones

?>
