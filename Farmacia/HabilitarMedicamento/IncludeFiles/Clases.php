<?php
//ADMINISTRACION DE LAS GRANDES AREAS ESPECIALIDADES
class Especialidades{
    function ObtenerEspecialidades(){
	$SQL="select * from mnt_especialidad";
		$resp=mysql_query($SQL);
	return($resp);
    }

    function EspecialidadHabilitada($IdEspecialidad,$IdEstablecimiento,$est = 0){
	if($est!=0){$comp='or Condicion="I"';}else{$comp="";}
	$SQL="select * from mnt_especialidadxestablecimiento 
		where IdEspecialidad=".$IdEspecialidad." 
		and IdEstablecimiento=".$IdEstablecimiento." 
		and (Condicion='H' ".$comp.")";
	$resp=mysql_fetch_array(mysql_query($SQL));
	return($resp[0]);
    }

    function LevantamientoEspecialidad($IdEspecialidad,$IdEstablecimiento,$IdUsuarioReg){
	$SQL="insert into mnt_especialidadxestablecimiento (IdEspecialidad,IdEstablecimiento,IdUsuarioReg,FechaHoraReg) values('$IdEspecialidad','$IdEstablecimiento','$IdUsuarioReg',now())";
	mysql_query($SQL);
    }

    function HabilitarEspecialidad($IdEspecialidad,$IdEstablecimiento,$IdUsuarioMod){
	$SQL="update mnt_especialidadxestablecimiento set Condicion='H', IdUsuarioMod='$IdUsuarioMod',FechaHoraMod=now() where IdEspecialidad=".$IdEspecialidad." and IdEstablecimiento=".$IdEstablecimiento;
	mysql_query($SQL);
    }

    function DeshabilitarEspecialidad($IdEspecialidad,$IdEstablecimiento,$IdUsuarioMod){
 	$SQL="update mnt_especialidadxestablecimiento set Condicion='I', IdUsuarioMod='$IdUsuarioMod',FechaHoraMod=now() where IdEspecialidad=".$IdEspecialidad." and IdEstablecimiento=".$IdEstablecimiento;
	mysql_query($SQL);
    }

}//Especialidades
//********************************************************************************
//ADMINISTRACION DE SUBESPECIALIDADES
class SubEspecialidades{
    function EspecialidadHabilitada($IdEstablecimiento){
	$SQL="select * from mnt_especialidadxestablecimiento 
		where IdEstablecimiento=".$IdEstablecimiento." 
		and Condicion ='H'";
	$resp=mysql_fetch_array(mysql_query($SQL));
	return($resp[0]);
    }

    function Opciones($IdEstablecimiento){
	$SQL="select * from mnt_especialidadxestablecimiento 
		inner join mnt_especialidad
		on mnt_especialidad.IdEspecialidad=mnt_especialidadxestablecimiento.IdEspecialidad 
		where IdEstablecimiento=".$IdEstablecimiento." 
		and Condicion ='H'";
	$resp=mysql_query($SQL);
	$opciones="";
	while($row=mysql_fetch_array($resp)){
	  $opciones.="<option value='".$row["IdEspecialidad"]."'>".$row["NombreEspecialidad"]."</option>";
	}
	return($opciones);
    }
    function ObtenerSubEspecialidades($IdEspecialidad){
	$SQL="select * from mnt_subservicio
		where IdServicio='CONEXT'
		and IdEspecialidad=".$IdEspecialidad;
		$resp=mysql_query($SQL);
	return($resp);
    }

    function SubEspecialidadHabilitada($IdSubEspecialidad,$IdEstablecimiento,$est = 0){
	if($est!=0){$comp='or Condicion="I"';}else{$comp="";}
	$SQL="select * from mnt_subservicioxestablecimiento where IdSubServicio=".$IdSubEspecialidad." and IdEstablecimiento=".$IdEstablecimiento." and (Condicion='H' ".$comp.")";
	$resp=mysql_fetch_array(mysql_query($SQL));
	return($resp[0]);
    }

    function LevantamientoSubEspecialidad($IdEspecialidad,$IdEstablecimiento,$IdUsuarioReg){
	$SQL="insert into mnt_subservicioxestablecimiento (IdSubServicio,IdEstablecimiento,IdUsuarioReg,FechaHoraReg) values('$IdEspecialidad','$IdEstablecimiento','$IdUsuarioReg',now())";
	mysql_query($SQL);
    }

    function HabilitarSubEspecialidad($IdSubEspecialidad,$IdEstablecimiento,$IdUsuarioMod){
	$SQL="update mnt_subservicioxestablecimiento set Condicion='H', IdUsuarioMod='$IdUsuarioMod',FechaHoraMod=now() where IdSubServicio=".$IdSubEspecialidad." and IdEstablecimiento=".$IdEstablecimiento;
	mysql_query($SQL);
    }

    function DeshabilitarSubEspecialidad($IdSubEspecialidad,$IdEstablecimiento,$IdUsuarioMod){
 	$SQL="update mnt_subservicioxestablecimiento set Condicion='I', IdUsuarioMod='$IdUsuarioMod',FechaHoraMod=now() where IdSubServicio=".$IdSubEspecialidad." and IdEstablecimiento=".$IdEstablecimiento;
	mysql_query($SQL);
    }

}//SubEspecialidades
//*************************************************************************************
//ADMINISTRACION DE SERVICIOS
class Servicios{
function ObtenerServicios(){
	$SQL="select * from mnt_servicio";
		$resp=mysql_query($SQL);
	return($resp);
    }

    function ServicioHabilitado($IdServicio,$IdEstablecimiento,$est = 0){
	if($est!=0){$comp='or Condicion="I"';}else{$comp="";}
	$SQL="select * from mnt_servicioxestablecimiento 
		where IdServicio='".$IdServicio."' 
		and IdEstablecimiento=".$IdEstablecimiento." 
		and (Condicion='H' ".$comp.")";
	$resp=mysql_fetch_array(mysql_query($SQL));
	return($resp[0]);
    }

    function LevantamientoServicio($IdServicio,$IdEstablecimiento,$IdUsuarioReg){
	$SQL="insert into mnt_servicioxestablecimiento (IdServicio,IdEstablecimiento,IdUsuarioReg,FechaHoraReg) values('$IdServicio','$IdEstablecimiento','$IdUsuarioReg',now())";
	mysql_query($SQL);
    }

    function HabilitarServicio($IdServicio,$IdEstablecimiento,$IdUsuarioMod){
	$SQL="update mnt_servicioxestablecimiento set Condicion='H', IdUsuarioMod='$IdUsuarioMod',FechaHoraMod=now() where IdServicio='".$IdServicio."' and IdEstablecimiento=".$IdEstablecimiento;
	mysql_query($SQL);
    }

    function DeshabilitarServicio($IdServicio,$IdEstablecimiento,$IdUsuarioMod){
 	$SQL="update mnt_servicioxestablecimiento set Condicion='I', IdUsuarioMod='$IdUsuarioMod',FechaHoraMod=now() where IdServicio='".$IdServicio."' and IdEstablecimiento=".$IdEstablecimiento;
	mysql_query($SQL);
    }

}
//****************************************************************************
//ADMINISTRACION DE SUBSERVICIOS
class SubServicios{

function ServicioHabilitado($IdEstablecimiento){
	$SQL="select * from mnt_servicioxestablecimiento se
		inner join mnt_servicio s
		on s.IdServicio=se.IdServicio
		where IdEstablecimiento=".$IdEstablecimiento." 
		and Condicion ='H'
		and s.IdServicio not in ('CONEXT','SERFAR','DCOLAB','DCORX')";
	$resp=mysql_fetch_array(mysql_query($SQL));
	return($resp[0]);
    }

    function Opciones($IdEstablecimiento){
	$SQL="select * from mnt_servicioxestablecimiento 
		inner join mnt_servicio
		on mnt_servicio.IdServicio=mnt_servicioxestablecimiento.IdServicio 
		where IdEstablecimiento=".$IdEstablecimiento." 
		and mnt_servicio.IdServicio not in ('CONEXT','SERFAR','DCOLAB','DCORX')
		and Condicion ='H'";
	$resp=mysql_query($SQL);
	$opciones="";
	while($row=mysql_fetch_array($resp)){
	  $opciones.="<option value='".$row["IdServicio"]."'>".$row["NombreServicio"]."</option>";
	}
	return($opciones);
    }
    function ObtenerSubServicio($IdServicio){
	$SQL="select * from mnt_subservicio
		where IdServicio='".$IdServicio."'";
		$resp=mysql_query($SQL);
	return($resp);
    }

    function SubServicioHabilitado($IdSubServicio,$IdEstablecimiento,$est = 0){
	if($est!=0){$comp='or Condicion="I"';}else{$comp="";}
	$SQL="select * from mnt_subservicioxestablecimiento where IdSubServicio=".$IdSubServicio." and IdEstablecimiento=".$IdEstablecimiento." and (Condicion='H' ".$comp.")";
	$resp=mysql_fetch_array(mysql_query($SQL));
	return($resp[0]);
    }

    function LevantamientoSubServicio($IdSubServicio,$IdEstablecimiento,$IdUsuarioReg){
	$SQL="insert into mnt_subservicioxestablecimiento (IdSubServicio,IdEstablecimiento,IdUsuarioReg,FechaHoraReg) values('$IdSubServicio','$IdEstablecimiento','$IdUsuarioReg',now())";
	mysql_query($SQL);
    }

    function HabilitarSubServicio($IdSubServicio,$IdEstablecimiento,$IdUsuarioMod){
	$SQL="update mnt_subservicioxestablecimiento set Condicion='H', IdUsuarioMod='$IdUsuarioMod',FechaHoraMod=now() where IdSubServicio=".$IdSubServicio." and IdEstablecimiento=".$IdEstablecimiento;
	mysql_query($SQL);
    }

    function DeshabilitarSubServicio($IdSubServicio,$IdEstablecimiento,$IdUsuarioMod){
 	$SQL="update mnt_subservicioxestablecimiento set Condicion='I', IdUsuarioMod='$IdUsuarioMod',FechaHoraMod=now() where IdSubServicio=".$IdSubServicio." and IdEstablecimiento=".$IdEstablecimiento;
	mysql_query($SQL);
    }

}
//*****************************************************************************
//ADMINISTRACION DE LABORATORIO
class Laboratorio{
    function ObtenerAreas(){
	$SQL="select * from lab_areas";
	$resp=mysql_query($SQL);
	return($resp);
    }
    
    function AreaHabilitada($IdArea,$IdEstablecimiento,$est = 0){
	if($est!=0){$comp=" or Condicion='I'";}else{$comp="";}
	$SQL="select *
		from lab_areasxestablecimiento
		where IdArea='".$IdArea."'
		and (Condicion='H' ".$comp.")
		and IdEstablecimiento=".$IdEstablecimiento;
	$resp=mysql_fetch_array(mysql_query($SQL));
	return($resp[0]);
    }
    
    function LevantamientoArea($IdArea,$IdEstablecimiento,$IdUsuarioReg){
	$SQL1="update lab_areas set Habilitado='S',IdUsuarioMod='$IdUsuarioReg',FechaHoraMod=now() where IdARea='$IdArea'";
	$SQL2="insert into lab_areasxestablecimiento (IdArea,IdEstablecimiento,IdUsuarioReg,FechaHoraReg) values('$IdArea','$IdEstablecimiento','$IdUsuarioReg',now())";
	mysql_query($SQL1);
	mysql_query($SQL2);
    }
    
    function HabilitarArea($IdArea,$IdEstablecimiento,$IdUsuarioReg){
	$SQL1="update lab_areas set Habilitado='S',IdUsuarioMod='$IdUsuarioReg',FechaHoraMod=now() where IdARea='$IdArea'";
	$SQL2="update lab_areasxestablecimiento set Condicion='H', IdUsuarioMod='$IdUsuarioReg', FechaHoraMod=now()
		where IdArea='$IdArea' and IdEstablecimiento='$IdEstablecimiento'";
	mysql_query($SQL1);
	mysql_query($SQL2);
    }

    function DeshabilitarArea($IdArea,$IdEstablecimiento,$IdUsuarioReg){
	$SQL1="update lab_areas set Habilitado='N',IdUsuarioMod='$IdUsuarioReg',FechaHoraMod=now() where IdARea='$IdArea'";
	$SQL2="update lab_areasxestablecimiento set Condicion='I', IdUsuarioMod='$IdUsuarioReg', FechaHoraMod=now()
		where IdArea='$IdArea' and IdEstablecimiento='$IdEstablecimiento'";
	mysql_query($SQL1);
	mysql_query($SQL2);
    }

    function ObtenerAreasHabilitadas($IdEstablecimiento){
	$SQL="select la.IdArea,NombreArea	
		from lab_areas la
		inner join lab_areasxestablecimiento lae
		on lae.IdArea=la.IdArea
		and Condicion='H'
		and la.IdArea <> 'JEF'
		and IdEstablecimiento=".$IdEstablecimiento;
	$resp=mysql_query($SQL);
	return($resp);
    }

    function ExamenesxArea($IdArea){
	$SQL="select * from lab_examenes where IdArea='$IdArea'";
	$resp=mysql_query($SQL);
	return($resp);
    }
    function ExamenHabilitado($IdExamen,$IdEstablecimiento,$est = 0){
	if($est!=0){$comp=" or Condicion='I'";}else{$comp="";}
	$SQL="select *
	from lab_examenesxestablecimiento
	where IdExamenes='$IdExamen'
	and IdEstablecimiento=".$IdEstablecimiento."
	and (Condicion = 'H' ".$comp." )";
	$resp=mysql_fetch_array(mysql_query($SQL));
	return($resp[0]);
    }

    function LevantamientoExamen($IdExamen,$IdEstablecimiento,$IdUsuarioReg){
	$SQL="insert into lab_examenesxestablecimiento (IdExamenes,IdEstablecimiento,IdUsuarioReg,FechaHoraReg) values('$IdExamen','$IdEstablecimiento','$IdUsuarioReg',now())";
	mysql_query($SQL);
    }

    function HabilitarExamen($IdExamen,$IdEstablecimiento,$IdUsuarioReg){
	$SQL="update lab_examenesxestablecimiento set Condicion='H', IdUsuarioMod='$IdUsuarioReg', FechaHoraMod=now()
		where IdExamenes='$IdExamen' and IdEstablecimiento=".$IdEstablecimiento;
	mysql_query($SQL);
    }

    function DeshabilitarExamen($IdExamen,$IdEstablecimiento,$IdUsuarioReg){
	$SQL="update lab_examenesxestablecimiento set Condicion='I', IdUsuarioMod='$IdUsuarioReg', FechaHoraMod=now()
		where IdExamenes='$IdExamen' and IdEstablecimiento=".$IdEstablecimiento;
	mysql_query($SQL);
    }


}
//*****************************************************************************
//ADMINISTRACION DE FARMACIA	
class Farmacia{
    function ObtenerGrupoTerapeutico(){
	$SQL="select * 
		from mnt_grupoterapeutico
		where GrupoTerapeutico <> '--'";
	$resp=mysql_query($SQL);
	return($resp);
    }

    function CatalogoxGrupo($IdGrupoTerapeutico,$Nombre){
	
	if($IdGrupoTerapeutico!=0){
		if($Nombre!=''){$comp=" and (Nombre like '%$Nombre%' or Codigo='$Nombre')";}else{$comp="";}
	$SQL="select * from farm_catalogoproductos where IdTerapeutico=".$IdGrupoTerapeutico." ".$comp."order by Codigo";
	$resp=mysql_query($SQL);
	return($resp);
	}else{
	 return(false);
	}	
    }
    
    function MedicamentoHabilitado($IdMedicina,$IdEstablecimiento,$IdModalidad,$est = 0){
	if($est!=0){$comp=" or Condicion='I'";}else{$comp="";}
	$SQL="select * from farm_catalogoproductosxestablecimiento 
		where IdMedicina=".$IdMedicina."
		and IdEstablecimiento=".$IdEstablecimiento."
                and IdModalidad = $IdModalidad
		and (Condicion='H' ".$comp.")";
	$resp=mysql_fetch_array(mysql_query($SQL));
	return($resp[0]);	
    }

    function Estupefaciente($IdMedicina,$IdEstablecimiento,$IdModalidad){
	
	$SQL="select * from farm_catalogoproductosxestablecimiento 
		where IdMedicina=".$IdMedicina."
		and IdEstablecimiento=".$IdEstablecimiento."
                and IdModalidad=$IdModalidad
		and Estupefaciente='S'";
	$resp=mysql_fetch_array(mysql_query($SQL));
	return($resp[0]);	
    }

    function LevantamientoMedicina($IdMedicina,$IdEstablecimiento,$IdUsuarioReg,$IdModalidad){
	$SQL="insert into farm_catalogoproductosxestablecimiento(IdMedicina,IdEstablecimiento,IdModalidad,IdUsuarioReg,FechaHoraReg) values('$IdMedicina','$IdEstablecimiento','$IdModalidad','$IdUsuarioReg',now())";
	mysql_query($SQL);
    }

    function HabilitarMedicina($IdMedicina,$IdEstablecimiento,$IdUsuarioMod,$IdModalidad){
	$SQL="update farm_catalogoproductosxestablecimiento set Condicion='H', IdUsuarioMod='$IdUsuarioMod', FechaHoraMod=now() 
              where IdMedicina=".$IdMedicina." and IdEstablecimiento=".$IdEstablecimiento." and IdModalidad=$IdModalidad";
	mysql_query($SQL);
    }
    function DeshabilitarMedicina($IdMedicina,$IdEstablecimiento,$IdUsuarioMod,$IdModalidad){
	$SQL="update farm_catalogoproductosxestablecimiento set Condicion='I', IdUsuarioMod='$IdUsuarioMod', FechaHoraMod=now() 
              where IdMedicina=".$IdMedicina." and IdEstablecimiento=".$IdEstablecimiento." and IdModalidad=$IdModalidad";
	mysql_query($SQL);
    }
    function EstadoEstupefaciente($IdMedicina,$IdEstablecimiento,$Estado,$IdUsuarioMod,$IdModalidad){
	$verifica=$this->MedicamentoHabilitado($IdMedicina,$IdEstablecimiento,$IdModalidad,1);
	switch($Estado){
	case 'S':
	   if($verifica!=NULL and $verifica!=''){
		$SQL="update farm_catalogoproductosxestablecimiento set Estupefaciente='S',Condicion='H',IdUsuarioMod='$IdUsuarioMod', FechaHoraMod=now() 
                      where IdMedicina=".$IdMedicina." and IdEstablecimiento=".$IdEstablecimiento." and IdModalidad=$IdModalidad";
		$resp=mysql_fetch_array(mysql_query($SQL));
	   }else{
		//si el medicamento no esta previamente habilitado, se habilita antes de convertirlo en 
		//estupefaciente
		$this->LevantamientoMedicina($IdMedicina,$IdEstablecimiento,$IdUsuarioMod,$IdModalidad);
	
		$SQL="update farm_catalogoproductosxestablecimiento set Estupefaciente='S',IdUsuarioMod='$IdUsuarioMod', FechaHoraMod=now() 
                      where IdMedicina=".$IdMedicina." and IdEstablecimiento=".$IdEstablecimiento." and IdModalidad=$IdModalidad";
		$resp=mysql_fetch_array(mysql_query($SQL));
	   }
	break;
	default:
	   //Si el estado es a N (no estupefaciente)
	   $SQL="update farm_catalogoproductosxestablecimiento set Estupefaciente='N',IdUsuarioMod='$IdUsuarioMod', FechaHoraMod=now() 
                 where IdMedicina=".$IdMedicina." and IdEstablecimiento=".$IdEstablecimiento." and IdModalidad=$IdModalidad";
	   $resp=mysql_fetch_array(mysql_query($SQL));
	break;
	}
	
    }


    function Divisor($IdMedicina){
	$SQL="select *
	from farm_catalogoproductos
	where (Presentacion like '%frasco%' or Presentacion like'%fco%') and Presentacion not like '%ml%'
	and IdTerapeutico <> 0
	and IdUnidadMedida = 1
	and Presentacion not like '%Frasco vial%'
	and IdMedicina = ".$IdMedicina;
	$resp=mysql_query($SQL);
	return($resp);
    }

    function IngresaDivisor($IdMedicina,$Divisor,$IdEstablecimiento,$IdModalidad){
	$row=mysql_fetch_array($this->ValorDivisor($IdMedicina,$IdEstablecimiento,$IdModalidad));
	if(($row[0]!=NULL and $Divisor == 0)){
	   $SQL="delete from farm_divisores 
                 where IdMedicina=".$IdMedicina." 
                 and IdEstablecimiento=".$IdEstablecimiento." and IdModalidad=$IdModalidad";
	   $resp=mysql_query($SQL);
	}else{
	   if($Divisor > 0 and $row[0]==NULL){
		$SQL="insert into farm_divisores (IdMedicina,DivisorMedicina,IdEstablecimiento,IdModalidad) 
                                           values('$IdMedicina','$Divisor',$IdEstablecimiento,$IdModalidad)";
		$resp=mysql_query($SQL);
	}
	}	  
	
    }

    function ValorDivisor($IdMedicina,$IdEstablecimiento,$IdModalidad){
	$SQL="select DivisorMedicina from farm_divisores 
              where IdMedicina=".$IdMedicina." 
              and IdEstablecimiento=".$IdEstablecimiento."
              and IdModalidad=$IdModalidad";
	$resp=mysql_query($SQL);
	return($resp);
    }

}
//***************************************************************************
//PERMISOS ESPECIALES
class Especiales{
   function MostrarEspecialidades($IdEspecialidad,$NombreEspecialidad){
	if($IdEspecialidad!=0){$comp='where e.IdEspecialidad='.$IdEspecialidad; }else{$comp='';}
	if($NombreEspecialidad!=''){$comp2='where e.NombreEspecialidad like "%'.$NombreEspecialidad.'%"';}else{$comp2='';}

	$SQL="SELECT distinct e.IdEspecialidad, NombreEspecialidad, 
	case ee.Condicion 
	when 'H' then 'Habilitado' 
	when 'I' then 'Deshabilitado' 
	else 'No Seleccionado' end as Condicion
	FROM mnt_especialidad e
	left join mnt_especialidadxestablecimiento ee
	on  e.IdEspecialidad = ee.IdEspecialidad
	".$comp." 
	".$comp2;
	$resp=mysql_query($SQL);
	return($resp);
   }
   function AgregarEspecialidad($NombreEspecialidad,$IdUsuarioReg){
	$SQL="insert into mnt_especialidad (NombreEspecialidad,IdUsuarioReg,FechaHoraReg) values('$NombreEspecialidad','$IdUsuarioReg',now())";
	mysql_query($SQL);
   }

   function ActualizarEspecialidad($NombreEspecialidad,$IdEspecialidad,$IdUsuarioMod){
	$SQL="update mnt_especialidad set NombreEspecialidad='$NombreEspecialidad', IdUsuarioMod='$IdUsuarioMod', FechaHoraMod=now() where IdEspecialidad=".$IdEspecialidad;
	mysql_query($SQL);
   }


//SUBESPECIALIDADES

   function EspecialidadesHabilitadas(){
	$SQL="select e.IdEspecialidad, NombreEspecialidad
		from mnt_especialidad e";
	$resp=mysql_query($SQL);
	return($resp);
   }

   function MostrarSubEspecialidades($IdSubEspecialidad,$NombreSubEspecialidad){
	if($IdSubEspecialidad!=0){$comp='where e.IdSubServicio='.$IdSubEspecialidad; }else{$comp='';}
	if($NombreSubEspecialidad!=''){$comp2='where e.NombreSubServicio like "%'.$NombreSubEspecialidad.'%"';}else{$comp2='';}

	$SQL="SELECT distinct e.IdSubServicio, NombreSubServicio, NombreEspecialidad,
	case ee.Condicion 
	when 'H' then 'Habilitado' 
	when 'I' then 'Deshabilitado' 
	else 'No Seleccionado' end as Condicion
	FROM mnt_subservicio e
	left join mnt_subservicioxestablecimiento ee
	on  e.IdSubServicio = ee.IdSubServicio
	inner join mnt_especialidad esp
	on esp.IdEspecialidad=e.IdEspecialidad
	".$comp." 
	".$comp2;
	$resp=mysql_query($SQL);
	return($resp);
   }


   function AgregarSubEspecialidad($NombreSubEspecialidad,$IdEspecialidad,$IdUsuarioReg){
	$SQL="insert into mnt_subservicio (IdServicio,NombreSubServicio,IdEspecialidad,IdUsuarioReg,FechaHoraReg) values('CONEXT','$NombreSubEspecialidad','$IdEspecialidad','$IdUsuarioReg',now())";
	mysql_query($SQL);
   }

   function ActualizarSubEspecialidad($NombreSubEspecialidad,$IdSubEspecialidad,$IdEspecialidad,$IdUsuarioMod){
	$SQL="update mnt_subservicio set NombreSubServicio='$NombreSubEspecialidad', IdUsuarioMod='$IdUsuarioMod', FechaHoraMod=now() where IdSubServicio=".$IdSubEspecialidad;
	mysql_query($SQL);

	if($IdEspecialidad!=0){
		$SQL="update mnt_subservicio set IdEspecialidad='$IdEspecialidad', IdUsuarioMod='$IdUsuarioMod', FechaHoraMod=now() where IdSubServicio=".$IdSubEspecialidad;
		mysql_query($SQL);
	}

   }

//ADMINISTRACION DE SERVICIOS	

function MostrarServicios($IdServicio,$NombreServicio){
	if($IdServicio!='0'){$comp='where e.IdServicio="'.$IdServicio.'"'; }else{$comp='';}
	if($NombreServicio!=''){$comp2='where e.IdServicio="'.$NombreServicio.'" or NombreServicio like "%'.$NombreServicio.'%"';}else{$comp2='';}

	$SQL="SELECT distinct e.IdServicio,Nombre, NombreServicio, 
	case ee.Condicion 
	when 'H' then 'Habilitado' 
	when 'I' then 'Deshabilitado' 
	else 'No Seleccionado' end as Condicion
	FROM mnt_servicio e
	left join mnt_servicioxestablecimiento ee
	on  e.IdServicio = ee.IdServicio
	left join mnt_tiposervicio ts
	on ts.IdTipoServicio=e.IdTipoServicio
	".$comp." 
	".$comp2."";
	$resp=mysql_query($SQL);
	return($resp);
   }

   function ValidaCodigo($IdServicio, $tipoValidacion = 0, $IdServicioOld = ''){
	if($tipoValidacion==0){
	   $SQL="select * from mnt_servicio where IdServicio='$IdServicio'";
	   $resp=mysql_query($SQL);
	}else{
	   if($IdServicio != $IdServicioOld){
		$SQL="select * from mnt_servicio where IdServicio='$IdServicio'";
	   	$resp=mysql_query($SQL);
		if($row=mysql_fetch_array($resp)){
		   $resp=1;
		}else{
		   $resp=0;
		}
	   }else{
		$resp=0;
	   }
	}
	
	return($resp);
   }

   function AgregarServicio($NombreServicio,$IdServicio,$IdTipoServicio){
	$SQL="insert into mnt_servicio (IdServicio,IdTipoServicio,NombreServicio) values('$IdServicio','$IdTipoServicio','$NombreServicio')";
	mysql_query($SQL);
   }

   function ActualizarServicio($NombreServicio,$IdServicio,$IdTipoServicio,$IdServicioOld){
	if($IdTipoServicio!='0'){$comp="IdTipoServicio='$IdTipoServicio',";}else{$comp="";}
	$SQL="update mnt_servicio set IdServicio='$IdServicio', ".$comp." NombreServicio='$NombreServicio' where IdServicio='".$IdServicioOld."'";
	mysql_query($SQL);
   }


//Tipo Servicios
   function TipoServicio($nombre=''){
	if($nombre!=''){$comp='where IdTipoServicio="'.$nombre.'" or Nombre like "%'.$nombre.'%"';}else{$comp='';}
	$SQL="select * 
		from mnt_tiposervicio
		".$comp;
	$resp=mysql_query($SQL);
	return($resp);
   }

   function AgregarTipoServicio($Nombre,$IdTipoServicio){
	$SQL="insert into mnt_tiposervicio (IdTipoServicio,Nombre) values ('$IdTipoServicio','$Nombre')";
	mysql_query($SQL);
   }


   function ValidaCodigoTipoServicio($IdTipoServicio,$IdTipoServicioOld){
	if($IdTipoServicio!=$IdTipoServicioOld){
	   $SQL="select * 
		from mnt_tiposervicio
		where IdTipoServicio='$IdTipoServicio'";
	   $resp=mysql_query($SQL);
	   if($row=mysql_fetch_array($resp)){
		return(1);
	    }else{
		return(0);
	    }
	   
	}else{
	   return (0);
	}
   }


   function ActualizarTipoServicio($Nombre,$IdTipoServicio,$IdTipoServicioOld){
	$SQL="update mnt_tiposervicio set IdTipoServicio='$IdTipoServicio', Nombre='$Nombre' where IdTipoServicio='$IdTipoServicioOld'";
	mysql_query($SQL);
	//Se hace un cambio en casacada de la tabla mnt_servicios para actualizar el cambio de IdTipoServicio
		$SQL2="update mnt_servicio set IdTipoServicio='$IdTipoServicio' where IdTipoServicio='$IdTipoServicioOld'";
		mysql_query($SQL2);
   }

}

//ADMINISTRACION DE ACCESO DE USUARIOS
class Usuarios{
	
	function AgregarUsuario($IdEmpleado,$login,$password){
		$query="insert into mnt_usuarios (login,password,nivel,modulo,Grupo,IdEmpleado) values('$login','$password','1','SEL','0','$IdEmpleado')";
		mysql_query($query);		
		
	}//Agregar Usuarios al sistema
	
	
	function ObtenerUsuarios($EstadoCuenta){
		$query="select * from usuarios where Nivel=".$EstadoCuenta;
		$resp=mysql_query($query);
		return($resp);		
	}//Obtencion de usuarios
	
	function Bloqueo($iduser){
		$query="update usuarios set Nivel=0 where IdUser=".$iduser;
		mysql_query($query);
	}
	
	function Habilitar($iduser){
		$query="update usuarios set Nivel=1 where IdUser=".$iduser;
		mysql_query($query);
	}
}
//*****************************************************************
?>