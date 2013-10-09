<?php 
include('../../Clases/class.php');
class Actualizaciones{
	function DatosGenerales($pagina,$IdEstablecimiento){
		$querySelect="select distinct  mnt_empleados.IdEmpleado,CodigoFarmacia,NombreEmpleado,HabilitadoFarmacia
					from mnt_empleados
					
					where (mnt_empleados.IdTipoEmpleado='MED' or mnt_empleados.IdTipoEmpleado='ENF')
					and mnt_empleados.IdEstablecimiento=".$IdEstablecimiento."
					order by NombreEmpleado
					LIMIT $pagina,20";
		$resp=mysql_query($querySelect);
		return($resp);		
	}//Datos Generales
	
	function BusquedaMedico($CodigoFarmacia,$NombreEmpleado,$IdEstablecimiento){
	if($CodigoFarmacia !=''){
		$filtro="CodigoFarmacia='$CodigoFarmacia'";
	}
	if($NombreEmpleado!=''){
		$filtro="NombreEmpleado like '%$NombreEmpleado%'";
	}
		$querySelect="select distinct mnt_empleados.IdEmpleado,CodigoFarmacia,NombreEmpleado,HabilitadoFarmacia
					from mnt_empleados
					
					where (mnt_empleados.IdTipoEmpleado='MED' or mnt_empleados.IdTipoEmpleado='ENF')
					and mnt_empleados.IdEstablecimiento=".$IdEstablecimiento."
					and $filtro
					order by NombreEmpleado";
		$resp=mysql_query($querySelect);
		return($resp);	}//BusquedaMedico
	
	
	function Tope($IdEstablecimiento){
		$querySelect="select count(IdEmpleado)
					from mnt_empleados
					where (IdTipoEmpleado='MED' or IdTipoEmpleado='ENF')
                                        and IdEstablecimiento=$IdEstablecimiento
					order by NombreEmpleado";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp[0]);
	}
	
	function CodigoActualFarmacia($IdEmpleado,$IdEstablecimiento){
		$querySelect="select CodigoFarmacia
					from mnt_empleados
					where IdEmpleado='$IdEmpleado'
                                        and IdEstablecimiento=".$IdEstablecimiento;
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp[0]);
	}
	
	function SubEspecialidad($IdSubEspecialidad){
		$querySelect="select IdSubServicio,NombreSubServicio
					from mnt_subservicio
					where IdSubServicio=".$IdSubEspecialidad;
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp[1]);		
	}//SubEspecialidad
	
	function MedicoSubEspecialidad($IdEmpleado){
		$querySelect="select distinct NombreSubServicio
					from mnt_subservicio
					inner join mnt_usuarios
					on mnt_usuarios.IdSubServicio=mnt_subservicio.IdSubServicio
					inner join mnt_empleados
					on mnt_empleados.IdEmpleado=mnt_usuarios.IdEmpleado
					where mnt_empleados.IdEmpleado='$IdEmpleado'";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp[0]);		
	}//MedicoSubEspecialidad
	
	function ActualizarCodigoFarmacia($IdEmpleado,$CodigoNuevo,$IdEstablecimiento){
		$queryUpdate="update mnt_empleados set CodigoFarmacia='$CodigoNuevo' 
                                where IdEmpleado='$IdEmpleado' and IdEstablecimiento=".$IdEstablecimiento;
		mysql_query($queryUpdate);
	}//Actualiza Codigo
	
	function VerificaCodigo($IdEmpleado,$CodigoNuevo){
		$querySelect="select IdEmpleado from mnt_empleados where CodigoFarmacia='$CodigoNuevo' and IdEmpleado <> '$IdEmpleado'";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp[0]);
	}//varificacion de Codigo
	
	function ComboSubEspecialidades($Combo,$IdEmpleado){
		$querySelect="select IdSubServicio, NombreSubServicio
					from mnt_subservicio
					where IdServicio='CONEXT'
					order by NombreSubServicio";
		$resp=mysql_query($querySelect);
		$combo="<select id='".$Combo."' name='".$Combo."' onblur='EspecialidadMedico(\"".$Combo."\",6);'>
				<option value='0'>[Seleccion ...]</option>";

		while($row=mysql_fetch_array($resp)){
		$combo.="<option value='".$row[0]."'>".htmlentities($row[1])."</option>";
		}//while
		
		$combo.="</select>";		
		return($combo);
	}//COmboSubEspecialidades
	
	function VerificaUbicacionMedico($IdEmpleado,$IdSubEspecialidad){
		$querySelect="select IdEspecialidad
					from mnt_subespecialidad
					inner join mnt_empleados
					on mnt_empleados.IdSubEspecialidad=mnt_subespecialidad.IdSubEspecialidad
					where IdEmpleado='$IdEmpleado'";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp[0]);		
	}//UbicacionMedico
	
	function ActualizarSubEspecialidad($IdEmpleado,$IdSubEspecialidad){
		$queryUpdate="update mnt_empleados set IdSubEspecialidad='$IdSubEspecialidad' where IdEmpleado='$IdEmpleado'";
		mysql_query($queryUpdate);
	}//ActualizarSubEspecialidad
	
	function VerificaEstadoMedico($IdEmpleado,$IdEstablecimiento){
		$querySelect="select HabilitadoFarmacia
					from mnt_empleados
					where IdEmpleado='$IdEmpleado'
                                        and IdEstablecimiento=".$IdEstablecimiento;
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp[0]);
	}
	
	function ActualizaEstadoCuenta($IdEmpleado,$NuevoEstado,$IdEstablecimiento){
		$queryUpdate="update mnt_empleados set HabilitadoFarmacia='$NuevoEstado' where IdEmpleado='$IdEmpleado' and IdEstablecimiento=".$IdEstablecimiento;
		mysql_query($queryUpdate);
		
	}
	
}//Clase Actualizaciones

?>
