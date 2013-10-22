<?php
class Classquery{

	function ObtenerQuery($Bandera,$IdArea,$q,$IdEstablecimiento){
	switch($Bandera){
	/*FILTRACIONES*/
	case 1: 

	$sqlStr = "select mnt_empleados.CodigoFarmacia,mnt_empleados.IdEmpleado,mnt_empleados.NombreEmpleado
				from mnt_empleados

				where (mnt_empleados.NombreEmpleado like '%$q%')
				and mnt_empleados.HabilitadoFarmacia='H'
				and (mnt_empleados.IdTipoEmpleado='MED' or mnt_empleados.IdTipoEmpleado='ENF')
                                and IdEstablecimiento=$IdEstablecimiento
				order by mnt_empleados.NombreEmpleado";

 break;
 
 /*TOTALES*/
 case 0: 
 $sqlStr = "select mnt_empleados.CodigoFarmacia,mnt_empleados.IdEmpleado,mnt_empleados.NombreEmpleado
				from mnt_empleados

				where mnt_empleados.HabilitadoFarmacia='H'
				and (mnt_empleados.IdTipoEmpleado='MED' or mnt_empleados.IdTipoEmpleado='ENF')
                                and IdEstablecimiento=$IdEstablecimiento
				order by mnt_empleados.NombreEmpleado";
 break;
      }//switch
 return ($sqlStr);
	}//ObtenerQueryLike
	
	
function ObtenerQueryTotal($Bandera,$IdArea,$q,$IdEstablecimiento){
switch($Bandera){
case 1:
 $sqlStrAux = "select count(mnt_empleados.IdEmpleado) as total
				from mnt_empleados

				where (mnt_empleados.NombreEmpleado like '%$q%')
				and mnt_empleados.HabilitadoFarmacia='H'
				and (mnt_empleados.IdTipoEmpleado='MED' or mnt_empleados.IdTipoEmpleado='ENF')
                                and IdEstablecimiento=$IdEstablecimiento
				order by mnt_empleados.NombreEmpleado";

 break;
 
 case 0:
 $sqlStrAux = "select count(mnt_empleados.IdEmpleado) as total
				from mnt_empleados

				where  mnt_empleados.HabilitadoFarmacia='H'
				and (mnt_empleados.IdTipoEmpleado='MED' or mnt_empleados.IdTipoEmpleado='ENF')
                                and IdEstablecimiento=$IdEstablecimiento
				order by mnt_empleados.NombreEmpleado";
 break;
}//switch
return($sqlStrAux);
}//ObtenerQueryTotal


}//clase query