<?php
class Classquery{

	function ObtenerQuery($Bandera,$IdArea,$q,$IdEstablecimiento,$IdModalidad){
	switch($Bandera){
	/*FILTRACIONES*/
	case 1: 
	$sqlStr = "select mnt_subservicio.IdSubServicio, CodigoFarmacia,NombreSubServicio, ms.IdServicio  as Ubicacion
						from mnt_subservicio
						inner join mnt_subservicioxestablecimiento msse 
                                                on msse.IdSubServicio=mnt_subservicio.IdSubServicio
                            inner join mnt_servicioxestablecimiento mse
                            on mse.IdServicioxEstablecimiento=msse.IdServicioxEstablecimiento
                            inner join mnt_servicio ms
                            on ms.IdServicio=mse.IdServicio
						where NombreSubServicio like '%$q%'
                                                and msse.IdEstablecimiento=".$IdEstablecimiento."
                                                and msse.IdModalidad=$IdModalidad
                                                and msse.CodigoFarmacia is not null";
 break;
 
 /*TOTALES*/
 case 0: 
 $sqlStr = "select mnt_subservicio.IdSubServicio, CodigoFarmacia,NombreSubServicio, ms.IdServicio as Ubicacion
						from mnt_subservicio
						inner join mnt_subservicioxestablecimiento msse 
                                                on msse.IdSubServicio=mnt_subservicio.IdSubServicio
                            inner join mnt_servicioxestablecimiento mse
                            on mse.IdServicioxEstablecimiento=msse.IdServicioxEstablecimiento
                            inner join mnt_servicio ms
                            on ms.IdServicio=mse.IdServicio
						where msse.CodigoFarmacia is not null
                            and msse.IdEstablecimiento=".$IdEstablecimiento."
                            and msse.IdModalidad=$IdModalidad
                            and msse.CodigoFarmacia is not null";
 break;
 
 
      }//switch
 return ($sqlStr);
	}//ObtenerQueryLike
	
	
function ObtenerQueryTotal($Bandera,$IdArea,$q,$IdEstablecimiento,$IdModalidad){
switch($Bandera){
case 1:
 $sqlStrAux = "select count(mnt_subservicio.IdSubServicio) as total
				from mnt_subservicio
						inner join mnt_subservicioxestablecimiento msse 
                                                on msse.IdSubServicio=mnt_subservicio.IdSubServicio
                            inner join mnt_servicioxestablecimiento mse
                            on mse.IdServicioxEstablecimiento=msse.IdServicioxEstablecimiento
                            inner join mnt_servicio ms
                            on ms.IdServicio=mse.IdServicio
				where NombreSubServicio like '%$q%'
				and msse.CodigoFarmacia is not null
                                                and msse.IdEstablecimiento=".$IdEstablecimiento."
                                                and msse.IdModalidad=$IdModalidad
                                and msse.CodigoFarmacia is not null";
 break;
 
 case 0:
 $sqlStrAux = "select count(mnt_subservicio.IdSubServicio) as total
				from mnt_subservicio
						inner join mnt_subservicioxestablecimiento msse 
                                                on msse.IdSubServicio=mnt_subservicio.IdSubServicio
                            inner join mnt_servicioxestablecimiento mse
                            on mse.IdServicioxEstablecimiento=msse.IdServicioxEstablecimiento
                            inner join mnt_servicio ms
                            on ms.IdServicio=mse.IdServicio
				where msse.CodigoFarmacia is not null
                                                and msse.IdEstablecimiento=".$IdEstablecimiento."
                                                and msse.IdModalidad=$IdModalidad
                                and msse.CodigoFarmacia is not null";
 break;
}//switch
return($sqlStrAux);
}//ObtenerQueryTotal


}//clase query