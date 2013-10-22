<?php include("../../Clases/class.php");
class admon{
   function InformacionGral($IdPersonal){
	$SQL='select *, case Nivel when 2 then "Co-Administrador" when 3 then "Tecnico de Farmacia" when 4 then "Digitador de Farmacia" end as TipoNivel,Farmacia,Area 
	from farm_usuarios 
		left join mnt_farmacia
		on mnt_farmacia.IdFarmacia=farm_usuarios.IdFarmacia
		left join mnt_areafarmacia
		on mnt_areafarmacia.IdArea=farm_usuarios.IdArea
	where IdPersonal='.$IdPersonal;
	$resp=mysql_query($SQL);
	return($resp);
   }

   function NivelUsuario($IdPersonal){
	$SQL="select nivel from farm_usuarios where IdPersonal=".$IdPersonal;
	$resp=mysql_fetch_array(mysql_query($SQL));
	return($resp[0]);
   }
   function CambiarNivel($IdPersonal,$Nivel){
	$SQL="update farm_usuarios set Nivel=".$Nivel." where IdPersonal=".$IdPersonal;
	$resp=mysql_query($SQL);
   }

   function CambioPermisos($IdPersonal,$acceso,$campo){
	$SQL="update farm_usuarios set ".$campo."=".$acceso." where IdPersonal=".$IdPersonal;
	mysql_query($SQL);
   }

   function Farmacias($IdModalidad){
	$SQL="select *
		from mnt_farmacia mf
                inner join mnt_farmaciaxestablecimiento mfe
                on mf.IdFarmacia = mfe.IdFarmacia
                
                where IdModalidad=$IdModalidad
		";
	$resp=mysql_query($SQL);
	return($resp);
   }


   function AreasFarmacia($IdFarmacia,$IdPersonal,$IdModalidad,$IdEstablecimiento){
	$SQL="select * 
		from mnt_areafarmacia maf
                inner join mnt_areafarmaciaxestablecimiento mafe
                on mafe.IdArea=maf.IdArea
		where mafe.IdArea not in (select IdArea from farm_usuarios where IdPersonal=".$IdPersonal." )
		and mafe.Habilitado='S' and IdFarmacia=".$IdFarmacia ." 
                and mafe.IdModalidad=$IdModalidad
                and mafe.IdEstablecimiento=$IdEstablecimiento";
	$resp=mysql_query($SQL);
	return ($resp);
   }

   function CambiarArea($IdFarmacia,$IdArea,$IdPersonal){
	$SQL="update farm_usuarios set IdFarmacia=".$IdFarmacia." , IdArea=".$IdArea." 
                where IdPersonal=".$IdPersonal;
	$resp=mysql_query($SQL);
   }

   function DeshabilitarCuenta($IdPersonal,$NuevoEstado){
	$SQL="update farm_usuarios set IdEstadoCuenta='".$NuevoEstado."' where IdPersonal=".$IdPersonal;
	mysql_query($SQL);
   }
}
?>