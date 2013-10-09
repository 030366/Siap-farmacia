<?php
require($path.'../Clases/class.php');

class RecetasProceso{

	function Combo($Tipo){
		switch($Tipo){
			case 1:
				/*COMBO DE FARMACIA*/
				$query="select IdFarmacia, Farmacia
						from mnt_farmacia where IdFarmacia <> 4";
				$resp=mysql_query($query);
				$combo='';
				while($row=mysql_fetch_array($resp)){
					$combo.='<option value="'.$row[0].'">'.$row[1].'</option>';
				}
				
			break;
			
			case 2:
				/*COMBO DE AREA*/
				/*COMBO DE FARMACIA*/
				$query="SELECT mnt_areafarmacia.IdArea,mnt_areafarmacia.Area
						   FROM mnt_areafarmacia
						   inner join mnt_farmacia
						   on mnt_farmacia.IdFarmacia=mnt_areafarmacia.IdFarmacia
						   WHERE mnt_areafarmacia.IdArea not in(7,12)
						   and Habilitado='S'
						 ";
				$resp=mysql_query($query);
				$combo='';
				while($row=mysql_fetch_array($resp)){
					$combo.='<option value="'.$row[0].'">'.$row[1].'</option>';
				}
			break;

			
		}//switch	
	
	return($combo);
		
	}//Combo

	function ObtenerIdSubServicio($IdSubEspecialidad){
		$querySelect="select mnt_subservicio.IdSubServicio
					from mnt_subservicio
					inner join mnt_subespecialidad
					on mnt_subespecialidad.NombreSubEspecialidad=mnt_subservicio.NombreSubServicio
					where mnt_subespecialidad.IdSubEspecialidad='$IdSubEspecialidad'";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp[0]);	
	}//Obtener IdSubServicio


	function ObtenerEspecialidad($IdSubServicio){
		$query="select NombreSubServicio
				from mnt_subservicio
				where IdSubServicio=".$IdSubServicio;
		$resp=mysql_fetch_array(mysql_query($query));
		return($resp[0]);
	}



/* FIN HISTORIAL CLINICO */

	function Correlativo($Fecha){
		$querySelect="select farm_recetas.NumeroReceta
					from farm_recetas
					where farm_recetas.Fecha='$Fecha'
					and (farm_recetas.IdEstado<>'RE' and farm_recetas.IdEstado<>'ER')
					order by farm_recetas.NumeroReceta desc
					limit 1";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp[0]);
	}

	function ObtenerIdReceta($IdHistorialClinico){
		$querySelect="select farm_recetas.IdReceta
					from farm_recetas
					where farm_recetas.IdHistorialClinico='$IdHistorialClinico'
					and farm_recetas.IdEstado='XX'";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp[0]);
	}
	function ObtenerExpediente($Expediente){
		$querySelect="select mnt_expediente.IdNumeroExp,mnt_datospaciente.PrimerNombre,
					mnt_datospaciente.PrimerApellido,mnt_expediente.IdNumeroExp
					from  mnt_expediente
					inner join mnt_datospaciente
					on mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente
					where mnt_expediente.IdNumeroExp='$Expediente'";
		//Obtenemos el ultimo IdHistorialClinico del Paciente
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp[0]);
	}//ObtenerExpediente
	
	
	function IntroducirRecetaNueva($IdHistorialClinico,$IdMedico,$IdPersonal,$Fecha,$IdArea){
		$Correlativo=RecetasProceso::Correlativo($Fecha);
		$Correlativo++;
		$queryInsert=" insert into farm_recetas(IdHistorialClinico,Fecha,IdEstado,IdArea,NumeroReceta,IdPersonalIntro) values('$IdHistorialClinico','$Fecha','XX','$IdArea','$Correlativo','$IdPersonal')";
		mysql_query($queryInsert);
	}//Intro Receta Nueva
	
	
		
	function ObtenerComboAreas($IdFarmacia){
		$filtro='';
		if($IdFarmacia!=0){$filtro='and farm_recetas.IdFarmacia='.$IdFarmacia;}
		$query="select distinct mnt_areafarmacia.IdArea,Area
				from mnt_areafarmacia
				inner join farm_recetas
				on farm_recetas.IdAreaOrigen=mnt_areafarmacia.IdArea
				where mnt_areafarmacia.IdArea <> 7
				and Habilitado ='S'
				".$filtro;
		$resp=mysql_query($query);
		return($resp);
	}//Combo Areas



	
	
	
	function ObtenerMedicinaIntroducida($IdReceta){
		$querySelect="select IdHistorialClinico, farm_medicinarecetada.*,farm_catalogoproductos.Nombre,farm_catalogoproductos.Concentracion,IdArea,FormaFarmaceutica,Presentacion
						from farm_medicinarecetada
						inner join farm_catalogoproductos
						on farm_catalogoproductos.IdMedicina=farm_medicinarecetada.IdMedicina
						inner join farm_recetas
						on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
						where farm_medicinarecetada.IdReceta = '$IdReceta'
						order by farm_medicinarecetada.IdMedicinaRecetada desc";
		$resp=mysql_query($querySelect);
		return($resp);
	}//Obtener Medicina Introducida
	
	
	function ObtenerDatosGenerales($IdReceta){
		$query="select Area, NombreEmpleado,NombreSubEspecialidad, case IdEspecialidad when 0 then 'CON. EXT.' when 3 then 'CON. EXT.' when 4 then '' end as Origen
				from farm_recetas
				inner join mnt_areafarmacia
				on mnt_areafarmacia.IdArea=farm_recetas.IdArea
				inner join sec_historial_clinico
				on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
				inner join mnt_empleados
				on mnt_empleados.IdEmpleado=sec_historial_clinico.IdEmpleado
				inner join mnt_subespecialidad
				on mnt_subespecialidad.IdSubEspecialidad=sec_historial_clinico.IdSubEspecialidad
				where IdReceta=".$IdReceta;
		$resp=mysql_fetch_array(mysql_query($query));
		return($resp);		
	}
	
	
	
	function RecetaLista($IdReceta){
		$queryUpdate="update farm_recetas set IdEstado='E' where IdReceta='$IdReceta'";
		mysql_query($queryUpdate);
	}//Receta Lista


	function ObtenerRecetas($IdMedicina,$IdFarmacia,$IdArea,$IdSubEspecialidad,$IdEmpleado,$FechaInicial,$FechaFinal){
		$filtro1='';$filtro2='';$filtro3='';$filtro4='';$filtro5='';
		if($IdMedicina!=''){
			$filtro5="and farm_medicinarecetada.IdMedicina='$IdMedicina'";
		}

		if($IdFarmacia!=0){
			$filtro1="and farm_recetas.IdFarmacia='$IdFarmacia'";
		}
		if($IdArea!=0){
			$filtro2="and farm_recetas.IdAreaOrigen='$IdArea'";
		}
		if($IdSubEspecialidad!=''){
			$filtro3="and sec_historial_clinico.IdSubServicio='$IdSubEspecialidad'";
		}
		if($IdEmpleado!=''){
			$filtro4="and sec_historial_clinico.IdEmpleado='$IdEmpleado'";
		}
		//Filtracion de informacion
		
		//****************************
		
		$query="select IdMedicinaRecetada,farm_medicinarecetada.IdMedicina,farm_recetas.IdReceta,farm_recetas.IdReceta, farm_catalogoproductos.Nombre, Concentracion, FormaFarmaceutica, Presentacion, Cantidad,NombreEmpleado, Fecha, Area, CorrelativoAnual, 
		if(farm_usuarios.Nombre is not null,farm_usuarios.Nombre,concat_ws(' ','Dr(a).',NombreEmpleado)) as Digitador,
		case farm_recetas.IdFarmacia 
			when 1 then 'Central' 
			when 2 then 'Con. Externa' 
			when 3 then 'Emergencias' end as NombreFarmacia

				from farm_recetas
				inner join farm_medicinarecetada
				on farm_medicinarecetada.IdReceta=farm_recetas.IdReceta
				inner join farm_catalogoproductos
				on farm_catalogoproductos.IdMedicina=farm_medicinarecetada.IdMedicina
				inner join sec_historial_clinico
				on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
				inner join mnt_empleados
				on mnt_empleados.IdEmpleado=sec_historial_clinico.IdEmpleado
				inner join mnt_areafarmacia
				on mnt_areafarmacia.IdArea=farm_recetas.IdAreaOrigen
				left join farm_usuarios
				on farm_usuarios.IdPersonal=farm_recetas.IdPersonalIntro
				inner join mnt_subservicio
				on mnt_subservicio.IdSubServicio=sec_historial_clinico.IdSubServicio
				
				where Fecha between '$FechaInicial' and '$FechaFinal'
				and farm_recetas.IdFarmacia <> 4
				$filtro5
				$filtro1
				$filtro2
				$filtro3
				$filtro4				
				order by Area,Fecha";
		$resp=mysql_query($query);
		return($resp);
	}//Obtener Recetas 

	
	function DetalleReceta($IdReceta){
		$query="select IdMedicinaRecetada,farm_recetas.IdReceta,farm_medicinarecetada.IdMedicina,Nombre, Concentracion, FormaFarmaceutica,Presentacion,Cantidad,
			case farm_medicinarecetada.IdEstado 
			when 'S' then 'S' 
			when 'I' then 'I' 
			when '' then 'S' end as EstadoMedicina
			
			from farm_recetas
			inner join farm_medicinarecetada
			on farm_medicinarecetada.IdReceta=farm_recetas.IdReceta
			inner join farm_catalogoproductos
			on farm_catalogoproductos.IdMedicina=farm_medicinarecetada.IdMedicina
			where farm_recetas.IdReceta=".$IdReceta;
		$resp=mysql_query($query);
		return($resp);
	}//DetalleReceta

	function IdAreaMedicinaRecetada($IdMedicinaRecetada){
	   $SQL="select IdArea
	   from farm_recetas r
	   inner join farm_medicinarecetada mr
	   on mr.IdReceta=r.IdReceta
	
	   where mr.IdMedicinaRecetada=".$IdMedicinaRecetada;
	   $resp=mysql_fetch_array(mysql_query($SQL));
	   return($resp[0]);
	}
	
	function EliminarMedicina($IdMedicinaRecetada){
		$IdArea=$this->IdAreaMedicinaRecetada($IdMedicinaRecetada);

		$this->AumentarInventario($IdMedicinaRecetada,$IdArea);

		$query="delete from farm_medicinarecetada where IdMedicinaRecetada=".$IdMedicinaRecetada;
		mysql_query($query);
		
	}//Eliminar Medicina
	
	function GuardarNuevaMedicina($IdReceta,$IdMedicina,$Cantidad){

	   $Fecha=mysql_fetch_array(
			mysql_query("select IdArea,Fecha from farm_recetas where IdReceta='$IdReceta'")
		  );
	   $FechaEntrega=$Fecha["Fecha"];
		
	   $query="insert into farm_medicinarecetada (IdReceta,IdMedicina,Cantidad,Dosis,FechaEntrega,IdEstado) values('$IdReceta','$IdMedicina','$Cantidad','-','$FechaEntrega','S')";
	   mysql_query($query);

	    //manejo de existencias
		$IdMedicinaRecetada=mysql_insert_id();
		$IdArea=$Fecha["IdArea"];

		$this->ActualizarInventario($IdMedicina,$IdMedicinaRecetada,$Cantidad,$IdArea,$FechaEntrega);
	   //

		
	}
	
	
	function ObtenerCodigoFarmacia($IdMedico){
		$querySelect="select CodigoFarmacia
					from mnt_empleados
					where IdEmpleado='$IdMedico'";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp[0]);
	}//CodigoFarmacia
	
	
	function ObtenerDatosMedico($CodigoFarmacia){
		$querySelect="select mnt_empleados.IdEmpleado, mnt_empleados.NombreEmpleado
					from mnt_empleados
					where mnt_empleados.CodigoFarmacia='$CodigoFarmacia'";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp);
	}//ObtenerDatosMedico
	
	/*	ACUTALIZACION DE INFORMACION	*/
	function ActualizarArea($IdArea,$IdReceta){
		$query="update farm_recetas set IdArea='$IdArea' where IdReceta='$IdReceta'";
		mysql_query($query);
		$query2="select Area from mnt_areafarmacia where IdArea='$IdArea'";
		$resp=mysql_fetch_array(mysql_query($query2));
		$salida="<a href='#' onClick='javascript:Correcciones(\"NombreArea\");'>".$resp[0]."</a>";
		return($salida);		
	}//actualizaicon de area
	
	function ActualizarMedico($IdHistorialClinico,$IdMedico){
		$query="update sec_historial_clinico set IdEmpleado='$IdMedico' where IdHistorialClinico='$IdHistorialClinico'";
		mysql_query($query);
		$query2="select NombreEmpleado from mnt_empleados where IdEmpleado='$IdMedico'";
		$resp=mysql_fetch_array(mysql_query($query2));
		$salida="<a href='#' onClick='javascript:Correcciones(\"NombreMedico\");'>".$resp[0]."</a>";
		return($salida);
	}

	function ActualizaEstadoMedicina($IdMedicinaRecetada,$IdEstado){
		$query="update farm_medicinarecetada set IdEstado='$IdEstado' where IdMedicinaRecetada=".$IdMedicinaRecetada;
		mysql_query($query);
	}
	
	function ActualizarEspecialidad($IdHistorialClinico,$IdSubEspecialidad){
		$IdSubServicio=RecetasProceso::ObtenerIdSubServicio($IdSubEspecialidad);
		
		$query="update sec_historial_clinico set IdSubServicio='$IdSubServicio',IdSubEspecialidad='$IdSubEspecialidad' where IdHistorialClinico='$IdHistorialClinico'";
		mysql_query($query);
		
		$query2="select NombreSubEspecialidad, case IdEspecialidad when 0 then 'CON. EXT.' when 3 then 'CON. EXT.' when 4 then '' end as Origen
				from mnt_subespecialidad
				where IdSubEspecialidad='$IdSubEspecialidad'";
		$resp=mysql_fetch_array(mysql_query($query2));
		if($resp[1]!='' and $resp[1]!=NULL){$Especialidad=$resp[1].' -> '.$resp[0];}else{$Especialidad=$resp[0];}
		$respuesta="<a href='#' onClick='javascript:Correcciones(\"Especialidad\");'>".$Especialidad."</a>";
		return($respuesta);
	}
	
	function ObtenerArea(){
		$query="select IdArea, Area
		from mnt_areafarmacia
		where IdArea != 7";
		$resp=mysql_query($query);
		return($resp);
	}

	
	function Cierre($Fecha){
		$sql="select AnoCierre
			from farm_cierre
			where AnoCierre=year('".$Fecha."')";
		$resp=mysql_query($sql);
		return($resp);		
	}//Cierre
		
	function CierreMes($Fecha){
		$sql="select MesCierre
			from farm_cierre
			where MesCierre=left('$Fecha',7)";
		$resp=mysql_query($sql);
		return($resp);		
	}//CierreMes

//**************	MANEJO DE EXISTENCIAS POR LOTES		**********************************
	
	//Actualizacion de inventario e identificacion de lote utilizado por medicamento
	
	function ActualizarInventario($IdMedicina,$IdMedicinaRecetada,$Cantidad,$IdArea,$Fecha){
		$queryLote="select fl.IdLote,Existencia,FechaVencimiento
			from farm_lotes fl
			inner join farm_medicinaexistenciaxarea fme
			on fme.IdLote=fl.IdLote
			where fme.IdMedicina=$IdMedicina
			and Existencia <> 0
			and left('$Fecha',7) <= left(FechaVencimiento,7)
			and fme.IdArea=$IdArea
			order by FechaVencimiento asc";
		$lotes=mysql_query($queryLote);
		$lotesA=mysql_fetch_array($lotes);

		if($Cantidad <= $lotesA["Existencia"]){
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
				$actualiza="update farm_medicinaexistenciaxarea set Existencia='0' where IdLote='$IdLote' and IdArea='$IdArea'";
				mysql_query($actualiza);
				
				//se ingresa el lote utilizado
			$query="insert into farm_medicinadespachada (IdMedicinaRecetada,IdLote,CantidadDespachada) values('$IdMedicinaRecetada','$IdLote','$existencia_old')";
			mysql_query($query);
				
			//Se recorren los siguiente lotes... Modo iterativo
			while($lotesA=mysql_fetch_array($lotes)){
				if($restante <= $lotesA["Existencia"]){
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
						where IdArea='$IdArea' and IdLote='$IdLote'";
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
						where IdArea='$IdArea' and IdLote='$IdLote'";	
				mysql_query($actualizaExistencia);
				
			    //Eliminacion del movimiento de farm_medicinadespachada
				$eliminaMovimiento="delete from farm_medicinadespachada where IdMedicinaDespachada=".$IdMedicinaDespachada;
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

	function ValorDivisor($IdMedicina){
	   $SQL="select DivisorMedicina from farm_divisores where IdMedicina=".$IdMedicina;
	   $resp=mysql_query($SQL);
	   return($resp);
    	}


function ObtenerAreaReceta($IdReceta){
	$SQL="select IdArea from farm_recetas where IdReceta=".$IdReceta;
	$resp=mysql_fetch_array(mysql_query($SQL));
	return($resp[0]);
}

function ObtenerExistencia($IdMedicina,$IdArea){
	$SQL="SELECT sum(Existencia) as Existencia
 	FROM farm_medicinaexistenciaxarea 
	where IdMedicina=".$IdMedicina."
	and IdArea=".$IdArea;
	$resp=mysql_fetch_array(mysql_query($SQL));

	if($row=mysql_fetch_array($this->ValorDivisor($IdMedicina))){
	   $respuesta=number_format($resp[0]*$row[0],0,'.','');
		
	}else{
	   $respuesta=$resp[0];
	}
	
	return($respuesta);
}

}//Clase RecetasProceso


?>