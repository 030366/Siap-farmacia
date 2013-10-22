<?php
include('../../Clases/class.php');

class IngresoEmpleados{
	function ObtenerIdEmpleado($IdTipoEmpleado,$IdEstablecimiento){
		$query="select Correlativo 
				from mnt_empleados
				where IdTipoEmpleado='$IdTipoEmpleado'
                                and IdEstablecimiento=".$IdEstablecimiento."
				order by Correlativo desc
				limit 1";
		$resp=mysql_query($query);
		if($row=mysql_fetch_array($resp)){
			$Correlativo=$row[0];
			$SiguienteCorrelativo=$Correlativo+1;
		}else{
			$SiguienteCorrelativo=1;
		}
		
		if($SiguienteCorrelativo < 10){ 
			$IdEmpleado=$IdTipoEmpleado."000".$SiguienteCorrelativo;
		}elseif($SiguienteCorrelativo<100){
			$IdEmpleado=$IdTipoEmpleado."00".$SiguienteCorrelativo;			
		}elseif($SiguienteCorrelativo<1000){
			$IdEmpleado=$IdTipoEmpleado."0".$SiguienteCorrelativo;
		}else{
			$IdEmpleado=$IdTipoEmpleado."".$SiguienteCorrelativo;	
		}
		
		$Respuesta=$IdEmpleado."/".$SiguienteCorrelativo;
		return($Respuesta);		
	}//Obtener IdEmpleado
	
	
    function VerificaCodigoFarmacia($CodigoFarmacia){
        $query="select * from mnt_empleados where CodigoFarmacia='$CodigoFarmacia'";
        $resp=mysql_query($query);
        if(mysql_fetch_array($resp)){
            $respuesta=true;   
        }else{
            $respuesta=false;            
        }
        
        return($respuesta);
    }                            
    
	function GuardarEmpleado($IdEmpleado,$IdEstablecimiento,$IdTipoEmpleado,$NombreEmpleado,$Correlativo,$CodigoFarmacia,$IdPersonal){
		$query="insert into mnt_empleados(IdEmpleado,IdEstablecimiento,IdTipoEmpleado,NombreEmpleado,Correlativo,CodigoFarmacia,IdUsuarioReg,FechaHoraReg) values('$IdEmpleado','$IdEstablecimiento','$IdTipoEmpleado','$NombreEmpleado','$Correlativo','$CodigoFarmacia','$IdPersonal',now())";
		mysql_query($query);

	}	
	
	function ObtenerDatos($IdEmpleado,$IdEstablecimiento){
	   $SQL="select IdEmpleado,NombreEmpleado,CodigoFarmacia
			from mnt_empleados
			where IdEmpleado='$IdEmpleado'
                        and IdEstablecimiento=".$IdEstablecimiento;
	   $resp=mysql_query($SQL);
	   return($resp);
	}

	function Empleados($NombreEmpleado,$IdEstablecimiento){
	   $SQL="select * from mnt_empleados 
		where (IdTipoEmpleado='MED' or IdTipoEmpleado='ENF')
		and NombreEmpleado like '%$NombreEmpleado%'
                and IdEstablecimiento=".$IdEstablecimiento."
		order by CodigoFarmacia desc";
	   $resp=mysql_query($SQL);
	  return($resp);
	}

	
}//Clase Ingreso Empleados
?>