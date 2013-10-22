<?php
class Repetitivas{
function ObtenerDatosPacienteRecetaProceso($IdReceta){
$querySelect="select distinct mnt_expediente.IdNumeroExp,concat_ws(', ',CONCAT_WS(' ',mnt_datospaciente.PrimerApellido,mnt_datospaciente.SegundoApellido),CONCAT_WS(' ',mnt_datospaciente.PrimerNombre,mnt_datospaciente.SegundoNombre))as NOMBRE,
mnt_datospaciente.sexo,mnt_empleados.NombreEmpleado,mnt_subservicio.NombreSubServicio,
farm_recetas.IdReceta,NumeroReceta,farm_recetas.IdEstado,farm_recetas.Fecha,farm_recetas.IdHistorialClinico,(year(curdate())-year(mnt_datospaciente.FechaNacimiento))-(right(curdate(),5)<right(mnt_datospaciente.FechaNacimiento,5)) as nac,date_format(sec_historial_clinico.FechaHoraReg,'%d-%m-%Y %h:%i:%s %p') as FechaConsulta,DATE_FORMAT(farm_recetas.Fecha,'%d-%m-%Y') as FechaDeEntrega
from sec_historial_clinico
inner join mnt_expediente
on mnt_expediente.IdNumeroExp=sec_historial_clinico.IdNumeroExp
inner join farm_recetas
on farm_recetas.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
inner join mnt_datospaciente
on mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente
inner join mnt_empleados
on mnt_empleados.IdEmpleado=sec_historial_clinico.IdEmpleado
inner join mnt_subservicio
on mnt_subservicio.IdSubServicio=sec_historial_clinico.IdSubServicio
where farm_recetas.IdReceta='$IdReceta'";
$resp=mysql_query($querySelect);
return($resp);
}

function ObtenerDatosPacienteReceta($bandera,$IdReceta,$IdArea){
$FechaAtras=queries::Atras();//Obtiene 3 fechas atras del dia de accion (vida util de recetas en dado caso no son entregadas)

switch($bandera){
case 1:
/*TODAS LAS RECETAS A MOSTRAR*/
$querySelect="select distinct mnt_expediente.IdNumeroExp,concat_ws(', ',CONCAT_WS(' ',mnt_datospaciente.PrimerApellido,mnt_datospaciente.SegundoApellido),CONCAT_WS(' ',mnt_datospaciente.PrimerNombre,mnt_datospaciente.SegundoNombre))as NOMBRE,
mnt_datospaciente.sexo,mnt_empleados.NombreEmpleado,mnt_subservicio.NombreSubServicio,
farm_recetas.IdReceta,NumeroReceta,farm_recetas.IdEstado,farm_recetas.Fecha,farm_recetas.IdHistorialClinico,(year(curdate())-year(mnt_datospaciente.FechaNacimiento))-(right(curdate(),5)<right(mnt_datospaciente.FechaNacimiento,5)) as nac,date_format(sec_historial_clinico.FechaHoraReg,'%d-%m-%Y %h:%i:%s %p') as FechaConsulta,DATE_FORMAT(farm_recetas.Fecha,'%d-%m-%Y') as FechaDeEntrega,
date_format(now(),'%d-%m-%Y %h:%i:%s %p') as Hoy
from sec_historial_clinico
inner join mnt_expediente
on mnt_expediente.IdNumeroExp=sec_historial_clinico.IdNumeroExp
inner join farm_recetas
on farm_recetas.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
inner join mnt_datospaciente
on mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente
inner join mnt_empleados
on mnt_empleados.IdEmpleado=sec_historial_clinico.IdEmpleado
inner join mnt_usuarios
on mnt_empleados.IdEmpleado=mnt_usuarios.IdEmpleado
inner join mnt_subservicio
on mnt_subservicio.IdSubServicio=sec_historial_clinico.IdSubServicio
where (farm_recetas.IdEstado='R' or farm_recetas.IdEstado='P') 
and farm_recetas.Fecha = curdate()
and year(farm_recetas.Fecha)=year(curdate())
and farm_recetas.IdArea='$IdArea'
order by farm_recetas.Fecha desc, farm_recetas.NumeroReceta asc";

break;
case 15:
$querySelect="select distinct mnt_expediente.IdNumeroExp,concat_ws(', ',CONCAT_WS(' ',mnt_datospaciente.PrimerApellido,mnt_datospaciente.SegundoApellido),CONCAT_WS(' ',mnt_datospaciente.PrimerNombre,mnt_datospaciente.SegundoNombre))as NOMBRE,
mnt_datospaciente.sexo,mnt_empleados.NombreEmpleado,mnt_subservicio.NombreSubServicio,
farm_recetas.IdReceta,NumeroReceta,farm_recetas.IdEstado,farm_recetas.Fecha,farm_recetas.IdHistorialClinico,(year(curdate())-year(mnt_datospaciente.FechaNacimiento))-(right(curdate(),5)<right(mnt_datospaciente.FechaNacimiento,5)) as nac,date_format(sec_historial_clinico.FechaHoraReg,'%d-%m-%Y %h:%i:%s %p') as FechaConsulta,DATE_FORMAT(farm_recetas.Fecha,'%d-%m-%Y') as FechaDeEntrega,
date_format(now(),'%d-%m-%Y %h:%i:%s %p') as Hoy
from sec_historial_clinico
inner join mnt_expediente
on mnt_expediente.IdNumeroExp=sec_historial_clinico.IdNumeroExp
inner join farm_recetas
on farm_recetas.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
inner join mnt_datospaciente
on mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente
inner join mnt_empleados
on mnt_empleados.IdEmpleado=sec_historial_clinico.IdEmpleado
inner join mnt_usuarios
on mnt_usuarios.IdEmpleado=mnt_empleados.IdEmpleado
inner join mnt_subservicio
on mnt_subservicio.IdSubServicio=sec_historial_clinico.IdSubServicio
where (farm_recetas.IdEstado='L' or farm_recetas.IdEstado='RL') 
and farm_recetas.Fecha between ".$FechaAtras." and curdate()
and year(farm_recetas.Fecha)=year(curdate())
and farm_recetas.IdArea='$IdArea'
order by farm_recetas.Fecha desc, farm_recetas.NumeroReceta asc";

break; 
default:
/*PARA LA IMPRESION DE VINETAS*/
$querySelect="select distinct mnt_expediente.IdNumeroExp,concat_ws(', ',CONCAT_WS(' ',mnt_datospaciente.PrimerApellido,mnt_datospaciente.SegundoApellido),CONCAT_WS(' ',mnt_datospaciente.PrimerNombre,mnt_datospaciente.SegundoNombre))as NOMBRE,
mnt_datospaciente.sexo,mnt_empleados.NombreEmpleado,mnt_subservicio.NombreSubServicio,
farm_recetas.IdReceta,NumeroReceta,farm_recetas.IdEstado,farm_recetas.Fecha,farm_recetas.IdHistorialClinico,(year(curdate())-year(mnt_datospaciente.FechaNacimiento))-(right(curdate(),5)<right(mnt_datospaciente.FechaNacimiento,5)) as nac,date_format(sec_historial_clinico.FechaHoraReg,'%d-%m-%Y %h:%m:%s %p') as FechaConsulta,DATE_FORMAT(farm_recetas.Fecha,'%d-%i-%Y') as FechaDeEntrega,
date_format(now(),'%d-%m-%Y %h:%i:%s %p') as Hoy
from sec_historial_clinico
inner join mnt_expediente
on mnt_expediente.IdNumeroExp=sec_historial_clinico.IdNumeroExp
inner join farm_recetas
on farm_recetas.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
inner join mnt_datospaciente
on mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente
inner join mnt_empleados
on mnt_empleados.IdEmpleado=sec_historial_clinico.IdEmpleado
inner join mnt_usuarios
on mnt_usuarios.IdEmpleado=mnt_empleados.IdEmpleado
inner join mnt_subservicio
on mnt_subservicio.IdSubServicio=sec_historial_clinico.IdSubServicio

where (farm_recetas.IdEstado='R' or farm_recetas.IdEstado='P') 
and farm_recetas.Fecha = curdate()
and farm_recetas.IdReceta='$IdReceta'
and farm_recetas.IdArea='$IdArea'
order by farm_recetas.Fecha desc, farm_recetas.NumeroReceta asc";
break;
}//fin de switch
//Para consulta del mes en where month(FechaConsulta)=month(curdate())...Para despues
$resp=mysql_query($querySelect);
return($resp);
}//ObtenerDatosPacienteReceta

function datosReceta($IdReceta,$IdArea){
$querySelect="select farm_recetas.IdReceta,NumeroReceta,farm_recetas.IdEstado,farm_recetas.Fecha,farm_recetas.IdHistorialClinico, farm_catalogoproductos.Nombre as medicina,
farm_catalogoproductos.Concentracion,farm_catalogoproductos.Presentacion,farm_catalogoproductos.IdMedicina, farm_catalogoproductos.FormaFarmaceutica, farm_medicinarecetada.Cantidad,farm_medicinarecetada.Dosis,farm_medicinarecetada.IdEstado,
farm_medicinarecetada.IdEstado as EstadoMedicina
from  farm_recetas
inner join sec_historial_clinico
on farm_recetas.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
inner join farm_medicinarecetada
on farm_medicinarecetada.IdReceta=farm_recetas.IdReceta
inner join farm_catalogoproductos
on farm_medicinarecetada.IdMedicina=farm_catalogoproductos.IdMedicina
where farm_recetas.IdReceta='$IdReceta'";
$respuesta=mysql_query($querySelect);
return($respuesta);
}//fin de datosReceta


function MedicinaReceta($IdReceta){
	$querySelect="select farm_medicinarecetada.IdMedicina,farm_medicinarecetada.IdReceta,farm_medicinarecetada.IdEstado,IdHistorialClinico, IdMedicinaRecetada,Cantidad,IdArea
				from farm_medicinarecetada
				inner join farm_recetas
				on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
				where farm_recetas.IdReceta='$IdReceta'";
	$resp=mysql_query($querySelect);
	return($resp);
	
	
}//MedicinaReceta

}//Fin clase Repetitivas


class Lotes{

//**************	MANEJO DE EXISTENCIAS POR LOTES		**********************************
	
	//Actualizacion de inventario e identificacion de lote utilizado por medicamento
	
	function ActualizarInventario($IdMedicina,$IdMedicinaRecetada,$Cantidad,$IdArea){
		$queryLote="select fl.IdLote,Existencia,FechaVencimiento
			from farm_lotes fl
			inner join farm_medicinaexistenciaxarea fme
			on fme.IdLote=fl.IdLote
			where fme.IdMedicina=$IdMedicina
			and Existencia <> 0
			and curdate() < FechaVencimiento 
			and fme.IdArea=$IdArea
			order by FechaVencimiento asc";
		$lotes=mysql_query($queryLote);
		$lotesA=mysql_fetch_array($lotes);

		if($Cantidad < $lotesA["Existencia"]){
		//****** Si la cantidad de medicamento no exede el total del primer lote a descagar...
			
			$IdLote=$lotesA["IdLote"];
			$existencia_old=$lotesA["Existencia"];
			$existencia_new=$existencia_old-$Cantidad;
			
			$actualiza="update farm_medicinaexistenciaxarea set Existencia='$existencia_new' where IdLote='$IdLote' and IdArea='$IdArea'";
			mysql_query($actualiza);
			
			//se ingresa el lote utilizado
			$query="insert into farm_medicinadespachada (IdMedicinaRecetada,IdLote,CantidadDespachada) values('$IdMedicinaRecetada','$IdLote','$Cantidad')";
			mysql_query($query);
			
			
		
		}else{
		//****** Si la existencia del lote es menor a lo que se descargara, se debe utilizar el segundo lote...
			
			//Primer lote a agotar...
			$IdLote=$lotesA["IdLote"];
			$existencia_old=$lotesA["Existencia"];
				//Medicina que aun falta por despachar
				$restante=$Cantidad-$existencia_old;
				
				
				
			//Se cierra el lote con existencia = 0
				$actualiza="update farm_medicinaexistenciaxarea set Existencia='0' where IdLote='$IdLote'";
				mysql_query($actualiza);
				
				//se ingresa el lote utilizado
			$query="insert into farm_medicinadespachada (IdMedicinaRecetada,IdLote,CantidadDespachada) values('$IdMedicinaRecetada','$IdLote','$existencia_old')";
			mysql_query($query);
				
			//Se recorren los siguiente lotes... Modo iterativo
			while($lotesA=mysql_fetch_array($lotes)){
				if($restante < $lotesA["Existencia"]){
				//****** Si la cantidad de medicamento no exede el total del primer lote a descagar...
				$IdLote=$lotesA["IdLote"];
				$existencia_old=$lotesA["Existencia"];
				$existencia_new=$existencia_old-$restante;
					
					//se actualiza la existencia del lote en uso
					$actualiza="update farm_medicinaexistenciaxarea set Existencia='$existencia_new' where IdLote='$IdLote' and IdArea='$IdArea'";
					mysql_query($actualiza);
				
					//se ingresa el lote utilizado
					$query="insert into farm_medicinadespachada (IdMedicinaRecetada,IdLote,CantidadDespachada) values('$IdMedicinaRecetada','$IdLote','$restante')";
					mysql_query($query);
				
					//Se termina el lazo porque el lote en cuestion suple la demanda restante
					break;
				}else{
				
				//Primer lote a agotar...
				$IdLote=$lotesA["IdLote"];
				$existencia_old=$lotesA["Existencia"];
				//Medicina que aun falta por despachar
					$restante2=$restante-$existencia_old;
				//Se cierra el lote con existencia = 0
					$actualiza="update farm_medicinaexistenciaxarea set Existencia='0' where IdLote='$IdLote' and IdArea='$IdArea'";
					mysql_query($actualiza);
					
					//se ingresa el lote utilizado
					$query="insert into farm_medicinadespachada (IdMedicinaRecetada,IdLote,CantidadDespachada) values('$IdMedicinaRecetada','$IdLote','$existencia_old')";
					mysql_query($query);
					
					$restante=$restante2;
					
				
				}//else de la comparacion de restante vs existencia
				
			}// Recorrido de los demas lotes con existencia
			
						
		}//else de cantidad vs existencia si no suple la demanda el primer lote
		
		
	}//actualizar inventario
	
	
	
	//MANEJO DE LOTES CUANDO SE ELIMINA UNA RECETA POR CORRECCION
	
	function AumentarInventario($IdMedicinaRecetada,$IdArea){
		$query="select CantidadDespachada,IdLote,IdMedicinaDespachada 
			from farm_medicinadespachada
			where IdMedicinaRecetada=".$IdMedicinaRecetada;
		$resp=mysql_query($query);
		
		while($row=mysql_fetch_array($resp)){
			$CantidadDespacha=$row["CantidadDespachada"];
			$IdLoteDespachado=$row["IdLote"];
			$IdMedicinaDespachada=$row["IdMedicinaDespachada"];
		
		//Obtencion de existencias actuales del lote utilizado
			$queryExistencia="select Existencia 
				from farm_medicinaexistenciaxarea
				where IdArea='$IdArea' and IdLote='$IdLoteDespachado'";
			$datos=mysql_fetch_array(mysql_query($queryExistencia));
			
		//Aumento de existencia
			$Nueva_Existencia=$CantidadDespacha+$datos["Existencia"];
			
		//Ingreso de Nueva Existencia
			
			$query2="update farm_medicinaexistenciaxarea set Existencia='$Nueva_Existencia'
				where IdArea='$IdArea' and IdLote='$IdLoteDespachado'";
			mysql_query($query2);
			
		// Eliminacion de movimiento de despacho
			$AnulacionDespacho="delete from farm_medicinadespachada 
					where IdMedicinaDespachada=".$IdMedicinaDespachada;
			mysql_query($AnulacionDespacho);
			
			
		}//Recorrido de farm_medicinadespachada	
		
		
		
	}//aumento de existencias por eliminacion de recetas...
	
	
	
	//***********	Actualizacion de inventario cuando se cambia la Cantidad de medicamento introducido ************* 
	
	function ActualizacionInventarioCantidad($IdMedicinaRecetada,$NuevaCantidad,$IdArea){
	//Obtencion de Cantidad Antigua
	$query="select IdMedicina,Cantidad from farm_medicinarecetada where IdMedicinaRecetada=".$IdMedicinaRecetada;
	$datos=mysql_fetch_array(mysql_query($query));
	//Primera parte cuando se aumenta la Cantidad
		if($datos["Cantidad"] < $NuevaCantidad){
			
			$IdMedicina=$datos["IdMedicina"];
			$CantidadAnterior=$datos["Cantidad"];
		    //Calculo de la entrega extra
			$extra=$NuevaCantidad-$CantidadAnterior;
			
		    $this->ActualizarInventario($IdMedicina,$IdMedicinaRecetada,$extra,$IdArea);
		
		}
	//**************************************************
	//Segunda Parte cuando se disminuye la Cantidad
		if($NuevaCantidad < $datos["Cantidad"]){
			$CantidadAnterior=$datos["Cantidad"];
		    //Calculo del medicamento a disminuir y aumentar en la existencia
			$restante=$CantidadAnterior-$NuevaCantidad;
		    //Obtencion de lotes ordenados por mayor existencia
		    //Son lotes que realmente se utilizaron para esa receta introducida...
			
			$queryLotes="select IdMedicinaDespachada, CantidadDespachada, farm_medicinadespachada.IdLote, Existencia
			from farm_medicinadespachada
			inner join farm_lotes
			on farm_lotes.IdLote=farm_medicinadespachada.IdLote
			inner join farm_medicinaexistenciaxarea
			on farm_medicinaexistenciaxarea.IdLote=farm_lotes.IdLote
			
			where IdMedicinaRecetada='$IdMedicinaRecetada'
			
			order by Existencia desc";	
			
			$resp=mysql_query($queryLotes);
			
			while($row=mysql_fetch_array($resp)){
				
				$IdMedicinaDespachada=$row["IdMedicinaDespachada"];
				
			    if($restante < $row["CantidadDespachada"]){
			    //Cuando la cantidad a disminuir es menor a la despachada por el primer lote
				$IdLote=$row["IdLote"];
				$CantidadDespachada=$row["CantidadDespachada"];
			    //Calculo de restante [Medicina realmente despachada]
				$restante2=$CantidadDespachada-$restante;
				
			    //Disminucion de la medicina despachada en farm_medicinadespachada
				$actualizaDespacho="update farm_medicinadespachada 
						set CantidadDespachada='$restante2'
						where IdMedicinaDespachada='$IdMedicinaDespachada'";
				mysql_query($actualizaDespacho);
				
			    //Aumento de existencias en lote utilizado por el moviemitno anterior
				$ExistenciaAnterior=$row["Existencia"];
				$ExistenciaNueva=$ExistenciaAnterior+$restante;
				
			    //Actualizacion de Existencia
				$actualizaExistencia="update farm_medicinaexistenciaxarea
						set Existencia='$ExistenciaNueva'
						where IdLote='$IdLote'";
				mysql_query($actualizaExistencia);
				
				break;
			    }else{
			    //Cuando la cantidad a disminuir es mayor a la cantidad utilizada 
			    //por el primer Lote. Se debe eliminar el movimiento descrito por la receta
			    //en farm_medicinadespachada
				$IdLote=$row["IdLote"];
				$CantidadDespachada=$row["CantidadDespachada"];
				$restante2=$restante-$CantidadDespachada;
				$prueba=1;
					if($restante2==0){
					   //En dado caso sea exacto al movimiento del lote el restante2 sera = restante
						$prueba=0;
						$restante2=$restante;
					}
			    //Aumento de la existencia del lote utilizado
				$ExistenciaAnterior=$row["Existencia"];
				$ExistenciaNueva=$ExistenciaAnterior+$CantidadDespachada;
				
			    //Actualizacion de la existencia del lote en cuestion
				$actualizaExistencia="update farm_medicinaexistenciaxarea
						set Existencia='$ExistenciaNueva'
						where IdLote='$IdLote'";	
				mysql_query($actualizaExistencia);
				
			    //Eliminacion del movimiento de farm_medicinadespachada
						
			/* Updates */$eliminaMovimiento="delete from farm_medicinadespachada where IdMedicinaDespachada=".$IdMedicinaDespachada;
				mysql_query($eliminaMovimiento);
				
				if($prueba==0){
					break;
				}else{
					$restante=$restante2;
				}
			    }
			
			}//recorrido de lotes
			
		}
	//***************************************************
	}//actualizacion de inventario por cambio de cantidad
	
	
	
//**************	FIN DE MANEJO DE LOTES Y EXISTENCIAS	**********************************


}
?>