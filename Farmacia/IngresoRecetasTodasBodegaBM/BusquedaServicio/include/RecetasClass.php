<?php
class Classquery{

	function ObtenerQuery($Bandera,$IdArea,$q){
	switch($Bandera){
	/*FILTRACIONES*/
	case 1: 
	$sqlStr = "select mnt_subservicio.IdSubServicio, CodigoFarmacia,NombreSubServicio, IdServicio  as Ubicacion
				from mnt_subservicio
				inner join mnt_subservicioxestablecimiento
				on mnt_subservicioxestablecimiento.IdSubServicio=mnt_subservicio.IdSubServicio
				where NombreSubServicio like '%$q%'
				and CodigoFarmacia is not null";
 break;
 
 /*TOTALES*/
 case 0: 
 $sqlStr = "select mnt_subservicio.IdSubServicio, CodigoFarmacia,NombreSubServicio, IdServicio as Ubicacion
				from mnt_subservicio
				inner join mnt_subservicioxestablecimiento
				on mnt_subservicioxestablecimiento.IdSubServicio=mnt_subservicio.IdSubServicio
				where CodigoFarmacia is not null";
 break;
 
 
      }//switch
 return ($sqlStr);
	}//ObtenerQueryLike
	
	
function ObtenerQueryTotal($Bandera,$IdArea,$q){
switch($Bandera){
case 1:
 $sqlStrAux = "select count(mnt_subservicio.IdSubServicio) as total
				from mnt_subservicio
inner join mnt_subservicioxestablecimiento
				on mnt_subservicioxestablecimiento.IdSubServicio=mnt_subservicio.IdSubServicio
				where NombreSubServicio like '%$q%'
				and CodigoFarmacia is not null";
 break;
 
 case 0:
 $sqlStrAux = "select count(mnt_subservicio.IdSubServicio) as total
				from mnt_subservicio
inner join mnt_subservicioxestablecimiento
				on mnt_subservicioxestablecimiento.IdSubServicio=mnt_subservicio.IdSubServicio
				where CodigoFarmacia is not null";
 break;
}//switch
return($sqlStrAux);
}//ObtenerQueryTotal


}//clase query