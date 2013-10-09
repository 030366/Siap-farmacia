<?php
include($path.'../Clases/class.php');
class Actualizacion{

	function IniciarPantallaP1(){
		
		$query="select IdTerapeutico,GrupoTerapeutico 
				from mnt_grupoterapeutico
				where GrupoTerapeutico <> '--'";
		$resp=mysql_query($query);
		return($resp);
	
	}//Iniciar Pantalla Paso1


	function IniciarPantallaP2($IdTerapeutico){
		
		$query="select IdMedicina,Codigo,Nombre,Concentracion,FormaFarmaceutica,Presentacion
				from farm_catalogoproductos
				where IdEstado='H'
				and IdTerapeutico=".$IdTerapeutico;
		$resp=mysql_query($query);
		return($resp);
		
	}//Iniciar Pantalla Paso 2
	
	
	function ObtenerPrecioActual($IdMedicina,$Ano){
		$Ano2=$Ano-1;
		$query="select Precio 
				from farm_preciosxano
				where IdMedicina='$IdMedicina'
				and (Ano='$Ano' or Ano ='$Ano2')
				order by Ano desc
				limit 1";
		$resp=mysql_fetch_array(mysql_query($query));
		if($resp[0]!=NULL and $resp[0]!=''){$respuesta=$resp[0];}else{$respuesta=false;}
		return($respuesta);
		
	}//Obtener Precio Actual
	
	
	function ObtenerPrecio($IdMedicina,$Ano){
		$query="select Precio 
				from farm_preciosxano
				where IdMedicina='$IdMedicina'
				and Ano='$Ano'";
		$resp=mysql_query($query);
		if($resp=mysql_fetch_array($resp)){
			$respuesta=true;
		}else{
			$respuesta=false;
		}
		
		return($respuesta);
	}//Obtener PRecio para saber si existe
	
	
	function IntroducirPrecio($IdMedicina,$Precio,$Ano,$IdUsuarioReg){
		$query="insert into farm_preciosxano(IdMedicina,Precio,Ano,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod) values('$IdMedicina','$Precio','$Ano','$IdUsuarioReg',current_timestamp,'$IdUsuarioReg',current_timestamp)";
		mysql_query($query);
	}//Introducir PRecio
	
	
	function ActualizarPrecio($IdMedicina,$Precio,$Ano,$IdUsuarioReg){
		$query="update farm_preciosxano set Precio='$Precio',IdUsuarioMod='$IdUsuarioReg',FechaHoraMod=current_timestamp where IdMedicina='$IdMedicina' and Ano='$Ano'";
		mysql_query($query);
		
	}//Actualizar el precio
	
	function ObtenerUnidadMedida($IdMedicina){
		$query="select UnidadesContenidas
				from farm_catalogoproductos
				inner join farm_unidadmedidas
				on farm_catalogoproductos.IdUnidadMedida=farm_unidadmedidas.IdUnidadMedida
				where IdMedicina=".$IdMedicina;
		$resp=mysql_fetch_array(mysql_query($query));
		return($resp[0]);
	}
	
}//Clase Actualizacion
?>