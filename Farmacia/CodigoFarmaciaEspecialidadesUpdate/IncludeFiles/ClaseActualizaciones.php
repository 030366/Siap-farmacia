<?php
include('../../Clases/class.php');
class Actualizaciones{
	function DatosGenerales($pagina,$IdEstablecimiento,$IdModalidad){
		$querySelect="select mss.IdSubServicio, msse.Condicion as HabilitadoFarmacia,
                        msse.CodigoFarmacia, concat_ws(' - ',NombreServicio,NombreSubServicio) as SubServicio
			from mnt_subservicio mss
			inner join mnt_subservicioxestablecimiento msse
			on mss.IdSubServicio=msse.IdSubServicio
			inner join mnt_servicioxestablecimiento mse
			on mse.IdServicioxEstablecimiento=msse.IdServicioxEstablecimiento
                        inner join mnt_servicio ms
                        on ms.IdServicio = mse.IdServicio

			where msse.IdEstablecimiento=".$IdEstablecimiento."
                        and msse.IdModalidad=$IdModalidad
			and msse.Condicion='H'
			order by ms.IdServicio,NombreSubServicio
					LIMIT $pagina,20";
		$resp=mysql_query($querySelect);
		return($resp);		
	}//Datos Generales
	
	function BusquedaMedico($CodigoFarmacia,$NombreSubEspecialidad,$IdEstablecimiento,$IdModalidad){
	if($CodigoFarmacia !=''){
		$filtro="msse.CodigoFarmacia='$CodigoFarmacia'";
	}
	if($NombreSubEspecialidad!=''){
		$filtro="NombreSubServicio like '%$NombreSubEspecialidad%'";
	}
		$querySelect="select mss.IdSubServicio, msse.Condicion as HabilitadoFarmacia,
                        msse.CodigoFarmacia, concat_ws(' - ',NombreServicio,NombreSubServicio) as SubServicio
			from mnt_subservicio mss
			inner join mnt_subservicioxestablecimiento msse
			on mss.IdSubServicio=msse.IdSubServicio
			inner join mnt_servicioxestablecimiento mse
			on mse.IdServicioxEstablecimiento=msse.IdServicioxEstablecimiento
                        inner join mnt_servicio ms
                        on ms.IdServicio = mse.IdServicio
			
			where msse.IdEstablecimiento=".$IdEstablecimiento."
                        and msse.IdModalidad=$IdModalidad
			and msse.Condicion='H'
			and ".$filtro."
			order by ms.IdServicio,NombreSubServicio";
		$resp=mysql_query($querySelect);
		return($resp);	}//BusquedaMedico
	
	
	function Tope($IdEstablecimiento,$IdModalidad){
		$querySelect="select count(msse.IdSubServicio)
					from mnt_subservicio mss
					inner join mnt_subservicioxestablecimiento msse
					on msse.IdSubServicio=mss.IdSubServicio
					where msse.Condicion='H'
                                        and IdEstablecimiento=$IdEstablecimiento
                                        and IdModalidad=$IdModalidad
					order by NombreSubServicio";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp[0]);
	}
	
	function CodigoActualFarmacia($IdSubEspecialidad,$IdEstablecimiento,$IdModalidad){
		$querySelect="select msse.CodigoFarmacia
					from mnt_subservicio
					inner join mnt_subservicioxestablecimiento msse
					on msse.IdSubServicio=mnt_subservicio.IdSubServicio
					where mnt_subservicio.IdSubServicio='$IdSubEspecialidad'
					and IdEstablecimiento=".$IdEstablecimiento."
                                        and IdModalidad=$IdModalidad";
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
	
	function ActualizarCodigoFarmacia($IdSubEspecialidad,$CodigoNuevo,$IdEstablecimiento,$IdModalidad){
		$queryUpdate="update mnt_subservicioxestablecimiento set CodigoFarmacia='$CodigoNuevo' 
                            where IdSubServicio='$IdSubEspecialidad' 
                            and IdEstablecimiento=".$IdEstablecimiento." and IdModalidad=$IdModalidad";
		mysql_query($queryUpdate);
	}//Actualiza Codigo
	
	function VerificaCodigo($IdSubEspecialidad,$CodigoNuevo,$IdEstablecimiento,$IdModalidad){
		$querySelect="select msse.IdSubServicio 
                              from mnt_subservicio mss
                              inner join mnt_subservicioxestablecimiento msse
                              on mss.IdSubServicio = msse.IdSubServicio
                              where msse.CodigoFarmacia='$CodigoNuevo' 
                              and msse.IdSubServicio <> '$IdSubEspecialidad' 
                              and IdEstablecimiento=".$IdEstablecimiento."
                              and IdModalidad=$IdModalidad";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp[0]);
	}//varificacion de Codigo
	

	
	function VerificaEstadoMedico($IdSubEspecialidad){
		$querySelect="select HabilitadoFarmacia
					from mnt_subservicio
					where IdSubServicio='$IdSubEspecialidad'";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp[0]);
	}
	
	function ActualizaEstadoCuenta($IdSubEspecialidad,$NuevoEstado){
		$queryUpdate="update mnt_subservicio set HabilitadoFarmacia='$NuevoEstado' where IdSubServicio='$IdSubEspecialidad'";
		mysql_query($queryUpdate);
		
	}
	
}//Clase Actualizaciones

?>
