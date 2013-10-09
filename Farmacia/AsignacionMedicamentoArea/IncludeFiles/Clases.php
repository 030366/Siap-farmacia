<?php

//ADMINISTRACION DE FARMACIA	
class Farmacia{
   function Farmacias($IdEstablecimiento,$IdModalidad){
	$SQL="select distinct mf.IdFarmacia,Farmacia
              from mnt_farmacia mf
              inner join mnt_farmaciaxestablecimiento mfe
              on mfe.IdFarmacia=mf.IdFarmacia
              where mfe.HabilitadoFarmacia='S'
              and mf.IdFarmacia<>4
              and mfe.IdEstablecimiento=".$IdEstablecimiento."
              and mfe.IdModalidad=$IdModalidad";
	$resp=mysql_query($SQL);
	return($resp);
   }

   function Areas($IdFarmacia,$IdEstablecimiento,$IdModalidad){
	$SQL="select maf.IdArea,Area
              from mnt_areafarmacia maf
              inner join mnt_areafarmaciaxestablecimiento mafe
              on mafe.IdArea=maf.IdArea              
              where maf.IdFarmacia=".$IdFarmacia." 
              and mafe.Habilitado='S' 
              and maf.IdArea <> 7
              and mafe.IdEstablecimiento=".$IdEstablecimiento."
              and mafe.IdModalidad=$IdModalidad";
	$resp=mysql_query($SQL);
	return($resp);
   }

    function ObtenerGrupoTerapeutico(){
	$SQL="select * 
		from mnt_grupoterapeutico
		where GrupoTerapeutico <> '--'";
	$resp=mysql_query($SQL);
	return($resp);
    }

    function CatalogoxGrupo($IdGrupoTerapeutico,$IdEstablecimiento,$IdModalidad){
	
	if($IdGrupoTerapeutico!=0){
	$SQL="select * from farm_catalogoproductos cp
		inner join farm_catalogoproductosxestablecimiento cpe
		on cpe.IdMedicina = cp.IdMedicina
		where cp.IdTerapeutico=".$IdGrupoTerapeutico." 
                and cpe.IdEstablecimiento=".$IdEstablecimiento."
                and cpe.IdModalidad=$IdModalidad";
	$resp=mysql_query($SQL);
	return($resp);
	}else{
	 return(false);
	}	
    }
    
    function MedicamentoHabilitado($IdMedicina,$IdArea,$IdEstablecimiento,$IdModalidad){
	$SQL="select *
                from mnt_areamedicina 
		where IdMedicina=".$IdMedicina."
		and IdArea= ".$IdArea."
                and IdEstablecimiento=".$IdEstablecimiento."
                and IdModalidad=$IdModalidad";
	$resp=mysql_fetch_array(mysql_query($SQL));
	return($resp[0]);	
    }

    function DispensadaEn($IdMedicina,$IdArea,$IdModalidad){
	$SQL="select Dispensada, Area
	from mnt_areamedicina am
	inner join mnt_areafarmacia af
	on af.IdArea=am.Dispensada
	where IdMedicina=".$IdMedicina."
	and am.IdArea=".$IdArea."
        and am.IdModalidad=$IdModalidad";
	$resp=mysql_fetch_array(mysql_query($SQL));
	return($resp);
    }

    function AreasDispensar($IdModalidad,$IdAreaDispensada = 0){
	if($IdAreaDispensada!=0){$comp=", ".$IdAreaDispensada;}else{$comp="";}

	$SQL="select afe.IdArea, Area, Farmacia
		from mnt_areafarmacia
		inner join mnt_farmacia
		on mnt_farmacia.IdFarmacia=mnt_areafarmacia.IdFarmacia
                inner join mnt_areafarmaciaxestablecimiento afe
                on afe.IdArea=mnt_areafarmacia.IdArea
		where mnt_areafarmacia.IdArea not in (7,12".$comp.")
		and afe.Habilitado ='S'
                and afe.IdModalidad=$IdModalidad";
	$resp=mysql_query($SQL);
	$combo="";
	while($row=mysql_fetch_array($resp)){
	    $combo.="<option value='".$row["IdArea"]."'>".$row["Area"]." [".$row["Farmacia"]."]</option>";
	}
	return($combo);
    }

    function AgregarMedicamento($IdMedicina,$IdArea,$IdEstablecimiento,$IdModalidad){
	$SQL="insert into mnt_areamedicina(IdMedicina,IdArea,IdEstablecimiento,IdModalidad) 
                                    values('$IdMedicina','$IdArea','$IdEstablecimiento','$IdModalidad')";
	$resp=mysql_query($SQL);
	return($resp);
    }

    function EliminarMedicina($IdMedicina,$IdArea,$IdEstablecimiento,$IdModalidad){
	$SQL="delete from mnt_areamedicina 
              where IdMedicina=".$IdMedicina." and IdArea=".$IdArea." 
              and IdEstablecimiento=".$IdEstablecimiento." and IdModalidad=$IdModalidad";
	mysql_query($SQL);
    }


    function AreaDespacho($IdMedicina,$IdArea,$IdAreaDispensada,$IdAreaOld,$Accion,$IdEstablecimiento,$IdModalidad){
	$verifica=$this->MedicamentoHabilitado($IdMedicina,$IdArea,$IdEstablecimiento,$IdModalidad);
	switch($Accion){
	case 'H':
	   if($verifica[0]!=NULL and $verifica[0]!=''){
		$SQL="update mnt_areamedicina set Dispensada=".$IdArea." 
                      where IdMedicina=".$IdMedicina." and IdArea=".$IdArea." 
                      and IdEstablecimiento=".$IdEstablecimiento." and IdModalidad=$IdModalidad";
		$resp=mysql_query($SQL);
	   }else{
		//si el medicamento no esta previamente habilitado, se habilita antes de convertirlo en 
		//estupefaciente
		$this->AgregarMedicamento($IdMedicina,$IdArea,$IdEstablecimiento);
	
		$SQL="update mnt_areamedicina set Dispensada=".$IdAreaDispensada." 
                      where IdMedicina=".$IdMedicina." and IdArea=".$IdArea." 
                      and IdEstablecimiento=".$IdEstablecimiento." and IdModalidad=$IdModalidad";
		$resp=mysql_query($SQL);
	   }
	break;
	case 'C':
	   $SQL="update mnt_areamedicina set Dispensada=".$IdAreaDispensada." 
                 where IdMedicina=".$IdMedicina." and IdArea='".$IdArea."' and Dispensada=".$IdAreaOld." 
                 and IdEstablecimiento=".$IdEstablecimiento." and IdModalidad=$IdModalidad";
		$resp=mysql_query($SQL);
	break;
	case 'E':
	   //Si el estado es a N (no estupefaciente)
	   $SQL="update mnt_areamedicina set Dispensada='' 
                 where IdMedicina=".$IdMedicina." and IdArea=".$IdArea." and Dispensada=".$IdAreaOld." 
                 and IdEstablecimiento=".$IdEstablecimiento." and IdModalidad=$IdModalidad";
	   $resp=mysql_query($SQL);
           
	break;
	}
	
    }

}
//***************************************************************************

?>