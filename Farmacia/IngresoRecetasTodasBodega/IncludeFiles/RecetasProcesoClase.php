<?php
require('../../Clases/class.php');

class RecetasProceso{
/*ACTUALIZACION DE HISTORIAL CLINICO*/ 
	function IntroducirHistorialClinico($Expediente,$IdMedico,$IdSubServicio,$FechaConsulta,$IdPersonal,$IdEstablecimiento){
		
				$Ip=$_SERVER['REMOTE_ADDR'];
		
		$queryInsert="insert into sec_historial_clinico (IdNumeroExp,FechaConsulta,IdEmpleado,IdSubServicio,IdUsuarioReg,FechaHoraReg,Piloto,IpAddress,IdEstablecimiento) values('$Expediente','$FechaConsulta','$IdMedico','$IdSubServicio','$IdPersonal',now(),'V','$Ip','$IdEstablecimiento')";
		     //$queryInsert="insert into sec_historial_clinico (IdNumeroExp,FechaConsulta,IdEmpleado,IdSubServicio,IdSubEspecialidad,IdUsuarioReg,FechaHoraReg,Piloto) values('$Expediente','$FechaConsulta','$IdMedico','$IdSubServicio','$IdSubEspecialidad','$IdPersonal',now(),'V')";
		mysql_query($queryInsert);


		$querySelect="select IdHistorialClinico 
						from sec_historial_clinico
						where IdNumeroExp='$Expediente'
						and IdUsuarioReg='$IdPersonal'
						and FechaConsulta='$FechaConsulta'
						and IdEmpleado='$IdMedico'
						and IdSubServicio='$IdSubServicio'
						and IdUsuarioReg='$IdPersonal'
						and Piloto='V'
						order by IdHistorialClinico desc limit 1";
		
		
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp[0]);		
	}//Introducir Historial Clinico


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

	function ObtenerIdReceta($IdHistorialClinico,$IdPersonal){
		$querySelect="select farm_recetas.IdReceta
					from farm_recetas
					where farm_recetas.IdHistorialClinico='$IdHistorialClinico'
					and farm_recetas.IdEstado='E'
					and IdPersonalIntro='$IdPersonal'";
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
	
	
	function IntroducirRecetaNueva($IdHistorialClinico,$IdMedico,$IdPersonal,$Fecha,$IdArea,$IdFarmacia,$IdAreaOrigen){
		$Correlativo=RecetasProceso::Correlativo($Fecha);
		$Correlativo++;
		$queryInsert=" insert into farm_recetas(IdHistorialClinico,Fecha,IdEstado,IdArea,NumeroReceta,IdPersonalIntro,IdFarmacia,IdAreaOrigen) values('$IdHistorialClinico','$Fecha','E','$IdArea','$Correlativo','$IdPersonal','$IdFarmacia','$IdAreaOrigen')";
		mysql_query($queryInsert);
	}//Intro Receta Nueva
	
	
		function IntroducirRecetaNuevaRepetitiva($IdHistorialClinico,$IdMedico,$IdPersonal,$Fecha,$IdArea){
		$Correlativo=RecetasProceso::Correlativo($Fecha);
		if($Correlativo==NULL){$Correlativo=0;}
		$Correlativo++;
		$queryInsert=" insert into farm_recetas(IdHistorialClinico,Fecha,IdEstado,IdArea,NumeroReceta,IdPersonalIntro) values('$IdHistorialClinico','$Fecha','RE','$IdArea','$Correlativo','$IdPersonal')";
		mysql_query($queryInsert);
	}//Intro Receta Nueva

	
/*	ELIMINAR RECETA E HISTORIA CLINICA	*/	
	
	function EliminarReceta($IdHistorialClinico,$IdPersonal,$IdReceta,$IdArea){
	   		
	//*****************		ELIMINACION DE RECETA Y DETALLE		
		$queryDeleteReceta="delete from farm_recetas where farm_recetas.IdReceta='$IdReceta'";

		$queryDeleteMedicina="delete from farm_medicinarecetada where farm_medicinarecetada.IdReceta='$IdReceta'";

		$queryDeleteHistorialClinico="delete from sec_historial_clinico where sec_historial_clinico.IdHistorialClinico='$IdHistorialClinico'";
		
		$querySelect="select * from farm_medicinarecetada 
			      where farm_medicinarecetada.IdReceta='$IdReceta'";
		
		$resp=mysql_query($querySelect);
			
		if($row=mysql_fetch_array($resp)){
		//Si hay medicina recetada en farm_medicinarecetada
		//Se debe actualizar la existencia del medicamento
			do{
			//recorrido de IdMedicinaRecetada
			    $IdMedicinaRecetada=$row["IdMedicinaRecetada"];
			 
			    $this->AumentarInventario($IdMedicinaRecetada,$IdArea);
				
				
			}while($row=mysql_fetch_array($resp));
			
			
			mysql_query($queryDeleteMedicina);
		}		
		mysql_query($queryDeleteReceta);


		
		mysql_query($queryDeleteHistorialClinico);
				
	}//Eliminar Receta



	function ObtenerIdRecetaRepetitivaEliminar($IdHistorialClinico,$IdPersonal){
		$querySelect="select farm_recetas.IdReceta
					from farm_recetas
					where farm_recetas.IdHistorialClinico='$IdHistorialClinico'
					and farm_recetas.IdEstado='RE'
					and IdPersonalIntro='$IdPersonal'";
		$resp=mysql_query($querySelect);
		return($resp);
			
	}//ObtenerIdRecetaRepetitivaEliminar



/* DETALLE DE RECETA */
	function IntroducirMedicinaPorReceta($IdReceta,$IdMedicina,$Cantidad,$Dosis,$Satisfecha,$Fecha){
		$queryInsert="insert into farm_medicinarecetada(IdReceta,IdMedicina,Cantidad,Dosis,FechaEntrega,IdEstado) values('$IdReceta','$IdMedicina','$Cantidad','$Dosis','$Fecha','$Satisfecha')";
		mysql_query($queryInsert);

		$IdMedicinaRecetada=mysql_insert_id();
		return($IdMedicinaRecetada);
		
	}//Introducir Medicina Receta

	
	function ObtenerFecha($intervalo){
	$querySelect="select adddate(curdate(), interval +".$intervalo." month)";
	$resp=mysql_fetch_array(mysql_query($querySelect));
	return($resp[0]);
	}
	
	
	
	function ObtenerMedicinaIntroducida($IdReceta){
		$querySelect="select farm_medicinarecetada.*,farm_catalogoproductos.Nombre,farm_catalogoproductos.Concentracion,Presentacion,FormaFarmaceutica
						from farm_medicinarecetada
						inner join farm_catalogoproductos
						on farm_catalogoproductos.IdMedicina=farm_medicinarecetada.IdMedicina
						where farm_medicinarecetada.IdReceta = '$IdReceta'
						order by farm_medicinarecetada.IdMedicinaRecetada desc";
		$resp=mysql_query($querySelect);
		return($resp);
	}//Obtener Medicina Introducida
	
	
	function RecetaLista($IdReceta){
		$queryUpdate="update farm_recetas set IdEstado='E' where IdReceta='$IdReceta'";
		mysql_query($queryUpdate);
	}//Receta Lista


	function ObtenerIdRecetaRepetitiva($IdHistorialClinico,$Fecha){
		$querySelect="select farm_recetas.IdReceta
					from farm_recetas
					where farm_recetas.IdHistorialClinico='$IdHistorialClinico'
					and farm_recetas.IdEstado='RE'
					and farm_recetas.Fecha='$Fecha'";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp[0]);
	}//Obtener ID Receta

	function ObtenerRecetaRepetitiva($IdHistorialClinico,$IdPersonal){
		$querySelect="select farm_medicinarecetada.Cantidad,farm_catalogoproductos.Nombre,farm_catalogoproductos.Concentracion,
					farm_recetas.Fecha,farm_medicinarecetada.*
					from farm_recetas
					inner join farm_medicinarecetada
					on farm_medicinarecetada.IdReceta=farm_recetas.IdReceta
					inner join farm_catalogoproductos
					on farm_catalogoproductos.IdMedicina=farm_medicinarecetada.IdMedicina
					where farm_recetas.IdHistorialClinico='$IdHistorialClinico'
					and farm_recetas.IdPersonalIntro='$IdPersonal'
					and farm_recetas.IdEstado='RE'
					order by farm_recetas.Fecha";
		$resp=mysql_query($querySelect);
		return($resp);
	
	}

	function EliminarMedicinaRecetada($IdMedicinaRecetada){
		
		
		$queryDelete="delete from farm_medicinarecetada where IdMedicinaRecetada='$IdMedicinaRecetada'";
		mysql_query($queryDelete);
		
		
	}//EliminarMedicinaRecetada
	
	

	function UpdateCantidad($IdMedicinaRecetada,$Cantidad){
		
		$queryUpdate="update farm_medicinarecetada set Cantidad='$Cantidad' where IdMedicinaRecetada='$IdMedicinaRecetada'";
		mysql_query($queryUpdate);

	
	}//UpdateCantidad
	

	
	
//**************	MANEJO DE EXISTENCIAS POR LOTES		**********************************
	
	//Actualizacion de inventario e identificacion de lote utilizado por medicamento
	
	function ActualizarInventario($IdMedicina,$IdMedicinaRecetada,$Cantidad,$IdArea,$Fecha){
		$queryLote="select fl.IdLote,Existencia,FechaVencimiento
			from farm_lotes fl
			inner join farm_entregamedicamento fme
			on fme.IdLote=fl.IdLote
			where fme.IdMedicina=$IdMedicina
			and Existencia <> 0
			and left('$Fecha',7) <= left(FechaVencimiento,7)
			
			order by FechaVencimiento asc";
		$lotes=mysql_query($queryLote);
		$lotesA=mysql_fetch_array($lotes);

		if($Cantidad <= $lotesA["Existencia"]){
		//****** Si la cantidad de medicamento no exede el total del primer lote a descagar...
			
			$IdLote=$lotesA["IdLote"];
			$existencia_old=$lotesA["Existencia"];
			$existencia_new=$existencia_old-$Cantidad;
			
			$actualiza="update farm_entregamedicamento set Existencia='$existencia_new' where IdLote='$IdLote' and IdMedicina='$IdMedicina'";
			mysql_query($actualiza);
			
			//se ingresa el lote utilizado
			//IdMedicinaRecetada es el Id de la tabla farm_medicinarecetada Last_insert()
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
				$actualiza="update farm_entregamedicamento set Existencia='0' where IdLote='$IdLote' and IdMedicina='$IdMedicina'";
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
					$actualiza="update farm_entregamedicamento set Existencia='$existencia_new' where IdLote='$IdLote' and IdMedicina='$IdMedicina'";
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
					$actualiza="update farm_entregamedicamento set Existencia='0' where IdLote='$IdLote' and IdMedicina='$IdMedicina'";
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
		$query="select IdMedicina,CantidadDespachada,IdLote,IdMedicinaDespachada 
			from farm_medicinadespachada
			inner join farm_medicinarecetada fmr
			on fmr.IdMedicinaRecetada=farm_medicinadespachada.IdMedicinaRecetada
			where fmr.IdMedicinaRecetada=".$IdMedicinaRecetada;
		$resp=mysql_query($query);
		
		while($row=mysql_fetch_array($resp)){
			$CantidadDespacha=$row["CantidadDespachada"];
			$IdLoteDespachado=$row["IdLote"];
			$IdMedicinaDespachada=$row["IdMedicinaDespachada"];
			$IdMedicina=$row["IdMedicina"];
		
		//Obtencion de existencias actuales del lote utilizado
			$queryExistencia="select Existencia 
				from farm_entregamedicamento
				where IdMedicina='$IdMedicina' and IdLote='$IdLoteDespachado'";
			$datos=mysql_fetch_array(mysql_query($queryExistencia));
			
		//Aumento de existencia
			$Nueva_Existencia=$CantidadDespacha+$datos["Existencia"];
			
		//Ingreso de Nueva Existencia
			
			$query2="update farm_entregamedicamento set Existencia='$Nueva_Existencia'
				where IdMedicina='$IdMedicina' and IdLote='$IdLoteDespachado'";
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
			$IdMedicina=$datos["IdMedicina"];
		    //Calculo del medicamento a disminuir y aumentar en la existencia
			$restante=$CantidadAnterior-$NuevaCantidad;
		    //Obtencion de lotes ordenados por mayor existencia
		    //Son lotes que realmente se utilizaron para esa receta introducida...
			
			$queryLotes="select IdMedicinaDespachada, CantidadDespachada, farm_medicinadespachada.IdLote, Existencia
			from farm_medicinadespachada
			inner join farm_lotes
			on farm_lotes.IdLote=farm_medicinadespachada.IdLote
			inner join farm_entregamedicamento
			on farm_entregamedicamento.IdLote=farm_lotes.IdLote
			
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
				$actualizaExistencia="update farm_entregamedicamento
						set Existencia='$ExistenciaNueva'
						where IdMedicina='$IdMedicina' and IdLote='$IdLote'";
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
				$actualizaExistencia="update farm_entregamedicamento
						set Existencia='$ExistenciaNueva'
						where IdMedicina='$IdMedicina' and IdLote='$IdLote'";	
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
	function ActualizarArea($IdArea,$IdReceta,$IdFarmacia){

		$queryH="select IdHistorialClinico from farm_recetas where IdReceta='$IdReceta'";
		$respH=mysql_fetch_array(mysql_query($queryH));
			$IdNumeroExp='0'.$IdFarmacia;
		    $SQL="update sec_historial_clinico set IdNumeroExp='$IdNumeroExp' where IdHistorialClinico=".$respH[0];
			mysql_query($SQL);
		$query="update farm_recetas set IdArea='$IdArea',IdFarmacia='$IdFarmacia' where IdReceta='$IdReceta'";
		mysql_query($query);
		$query2="select Area from mnt_areafarmacia where IdArea='$IdArea'";
		$resp=mysql_fetch_array(mysql_query($query2));
		
		$query3="select Farmacia from mnt_farmacia where IdFarmacia=".$IdFarmacia;
		$resp2=mysql_fetch_array(mysql_query($query3));


	}//actualizaicon de area
	
	function ActualizarMedico($IdHistorialClinico,$IdMedico){
		$query="update sec_historial_clinico set IdEmpleado='$IdMedico' where IdHistorialClinico='$IdHistorialClinico'";
		mysql_query($query);
	}
	
	function ActualizarSubServicio($IdHistorialClinico,$IdSubServicio){
				
		$query="update sec_historial_clinico set IdSubServicio='$IdSubServicio' where IdHistorialClinico='$IdHistorialClinico'";
		mysql_query($query);	
		
	}
	
	function VerificaRecetas($IdReceta){
		$query="select IdMedicinaRecetada from farm_medicinarecetada where IdReceta=".$IdReceta;
		$resp=mysql_fetch_array(mysql_query($query));
		return($resp[0]);		
	}//verifica recetas

	
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

	function ValorDivisor($IdMedicina){
	   $SQL="select DivisorMedicina from farm_divisores where IdMedicina=".$IdMedicina;
	   $resp=mysql_query($SQL);
	   return($resp);
    	}

function ObtenerExistencia($IdMedicina,$TipoFarmacia){
	$SQL="SELECT sum(Existencia) as Existencia
 	FROM farm_entregamedicamento 
	where IdMedicina=".$IdMedicina."
	";
	$resp=mysql_fetch_array(mysql_query($SQL));
	if($resp[0]!='' and $resp[0]!=NULL){
		if($row=mysql_fetch_array($this->ValorDivisor($IdMedicina)) and $TipoFarmacia==1){
		$respuesta=number_format($resp[0]*$row[0],0,'.','');
			
		}else{
		$respuesta=$resp[0];
		}
	}else{
		$respuesta=0;
	}
	return($respuesta);
}

function AreaOrigen($IdArea){
	$SQL="select IdArea,Area from mnt_areafarmacia where Habilitado='S' and IdArea not in(12,7,".$IdArea.")";
	$resp=mysql_query($SQL);
	$combo="<select id='IdAreaOrigen' name='IdAreaOrigen'>
		<option value='0'>[Opcional ...]</option>";
	while($row=mysql_fetch_array($resp)){
	   $combo.="<option value='".$row["IdArea"]."'>".$row["Area"]."</option>";
	}
	$combo.="</select>";
	return($combo);
   }

   function ActualizarAreaOrigen($IdArea,$IdReceta){

		$query="update farm_recetas set IdAreaOrigen='$IdArea' where IdReceta='$IdReceta'";
		mysql_query($query);

   }


	function RecetasIngresadasConteo($IdPersonal){
		$query="select count(IdMedicinaRecetada)
				from farm_usuarios
				inner join farm_recetas
				on farm_recetas.IdPersonalIntro=farm_usuarios.IdPersonal
							
				inner join farm_medicinarecetada
				on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
				inner join sec_historial_clinico
				on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
				
				where FechaHoraReg is not null
				and date(FechaHoraReg)=curdate()
				and IdPersonalIntro='$IdPersonal'
group by farm_recetas.IdPersonalIntro
order by farm_recetas.IdPersonalIntro";
		$resp=mysql_fetch_array(mysql_query($query));
		return($resp[0]);
	}//Informacion

function ObtenerCorrelativoAnual($IdReceta,$Fecha){
	
	$datos=array('A'=>'01','B'=>'02','C'=>'03','D'=>'04','E'=>'05','F'=>'06','G'=>'07','H'=>'08','I'=>'09','J'=>'10','K'=>'11','L'=>'12',);

	$date=explode('-',$Fecha);
	$mes=$date[1];
	$Letra= array_search($mes,$datos);	
		

	   $SQL="select Correlativo
		from farm_recetas
		where CorrelativoAnual like '%$Letra%'
		and year(Fecha)=year('$Fecha')
		order by Correlativo desc";
	   $resp=mysql_fetch_array(mysql_query($SQL));
		
           $tail="select right('".$date[0]."',2) as tail";
            $tt=mysql_fetch_array(mysql_query($tail));
           
		$correlativo=$resp[0]+1;
		
		$correlativoAnual=$correlativo."".$Letra."".$tt[0];
		
		$SQL_u="update farm_recetas set Correlativo='$correlativo', CorrelativoAnual='$correlativoAnual' where IdReceta=".$IdReceta;
			mysql_query($SQL_u);
		return($correlativoAnual);
	}

}//Clase RecetasProceso


?>