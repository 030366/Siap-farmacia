<?php
require('../../Clases/class.php');

class RecetasProceso{
/*ACTUALIZACION DE HISTORIAL CLINICO*/
	function IntroducirHistorialClinico($Expediente,$IdMedico,$IdSubEspecialidad,$FechaConsulta){
		$IdSubServicio=RecetasProceso::ObtenerIdSubServicio($IdSubEspecialidad);
		$queryInsert="insert into sec_historial_clinico (IdNumeroExp,FechaConsulta,IdEmpleado,IdSubServicio,IdSubEspecialidad,Piloto) values('$Expediente','$FechaConsulta','$IdMedico','$IdSubServicio','$IdSubEspecialidad','V')";
		$querySelect="select IdHistorialClinico 
						from sec_historial_clinico
						where IdNumeroExp='$Expediente'
						order by IdHistorialClinico desc limit 1";
		
		mysql_query($queryInsert);
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp[0]);		
	}//Introducir Historial Clinico

	function ObtenerIdSubServicio($IdSubServicio){
		$querySelect="select mnt_subservicio.IdSubServicio
					from mnt_subservicio
					
					where IdSubServicio='$IdSubServicio'";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp[0]);	
	}//Obtener IdSubServicio


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
	
	
		function IntroducirRecetaNuevaRepetitiva($IdHistorialClinico,$IdMedico,$IdPersonal,$Fecha,$IdArea){
		$Correlativo=RecetasProceso::Correlativo($Fecha);
		if($Correlativo==NULL){$Correlativo=0;}
		$Correlativo++;
		$queryInsert=" insert into farm_recetas(IdHistorialClinico,Fecha,IdEstado,IdArea,NumeroReceta,IdPersonalIntro) values('$IdHistorialClinico','$Fecha','RE','$IdArea','$Correlativo','$IdPersonal')";
		mysql_query($queryInsert);
	}//Intro Receta Nueva

	
/*	ELIMINAR RECETA E HISTORIA CLINICA	*/	
	
	function EliminarReceta($IdHistorialClinico,$IdPersonal,$IdReceta){
		/****	RECUPERACION DE EXISTENCIAS	 ****/
		$querySelect1="select IdArea from farm_recetas where IdReceta=".$IdReceta;
		$IdArea=mysql_fetch_array(mysql_query($querySelect1));
		if($IdArea[0]==2){
		/****************************************************/
		/*				AREA CONSULTA EXTERNA				*/
		/****************************************************/
			/********************************************************/
			/*							LOTE 1						*/
			/********************************************************/
		
			$querySelect2="select IdMedicinaRecetada,IdEstado from farm_medicinarecetada where IdReceta=".$IdReceta;
			$resp2=mysql_query($querySelect2);
			while($row2=mysql_fetch_array($resp2)){
				$querySelect3="select CantidadLote1, Lote1, CantidadLote2, Lote2
							from farm_medicinarecetada
							where IdMedicinaRecetada=".$row2["IdMedicinaRecetada"];
				$resp3=mysql_fetch_array(mysql_query($querySelect3));
				$CantidadLote1=$resp3["CantidadLote1"];
				$IdLote1=$resp3["Lote1"];
				$CantidadLote2=$resp3["CantidadLote2"];
				$IdLote2=$resp3["Lote2"];
				$IdEstado=$row2["IdEstado"];
				/***************	LOTE 1	*******************/
				if($IdEstado=='S'){
					$querySelect4="select Existencia
								from farm_medicinaexistenciaxarea
								where IdLote=".$IdLote1;
					$respExistencia1=mysql_fetch_array(mysql_query($querySelect4));
					$Existencia_new1=$CantidadLote1+$respExistencia1["Existencia"];
					
					$queryUpdate1="update farm_medicinaexistenciaxarea set Existencia='$Existencia_new1' where IdLote=".$IdLote1;
						mysql_query($queryUpdate1);
					
					/***************	LOTE 2	*******************/
						if($IdLote2!='' and $IdLote2!=NULL and $IdLote2!='0'){
							$querySelect4="select Existencia
										from farm_medicinaexistenciaxarea
										where IdLote=".$IdLote2;
							$respExistencia2=mysql_fetch_array(mysql_query($querySelect4));
							$Existencia_new2=$CantidadLote2+$respExistencia2["Existencia"];
							
							$queryUpdate2="update farm_medicinaexistenciaxarea set Existencia='$Existencia_new2' where IdLote=".$IdLote2;
								mysql_query($queryUpdate2);
						}//IdLote2 != NULL
				}//IDEstado=='S'
			}//While
				
		}else{
		/****************************************************/
		/*					LAS DEMAS AREAS					*/
		/****************************************************/
			$querySelect2="select IdMedicinaRecetada,IdEstado from farm_medicinarecetada where IdReceta=".$IdReceta;
			$resp2=mysql_query($querySelect2);
			while($row2=mysql_fetch_array($resp2)){
				$querySelect3="select CantidadLote1, Lote1, CantidadLote2, Lote2
							from farm_medicinarecetada
							where IdMedicinaRecetada=".$row2["IdMedicinaRecetada"];
				$resp3=mysql_fetch_array(mysql_query($querySelect3));
				$CantidadLote1=$resp3["CantidadLote1"];
				$IdLote1=$resp3["Lote1"];
				$CantidadLote2=$resp3["CantidadLote2"];
				$IdLote2=$resp3["Lote2"];
				$IdEstado=$row2["IdEstado"];
				if($IdEstado=='S'){
					/***************	LOTE 1	*******************/
					$querySelect4="select Existencia
								from farm_entregamedicamento
								where IdLote=".$IdLote1;
					$respExistencia1=mysql_fetch_array(mysql_query($querySelect4));
					$Existencia_new1=$CantidadLote1+$respExistencia1["Existencia"];
					
					$queryUpdate1="update farm_entregamedicamento set Existencia='$Existencia_new1' where IdLote=".$IdLote1;
						mysql_query($queryUpdate1);
					
	
					/***************	LOTE 2	*******************/
					if($IdLote2!='' and $IdLote2!=NULL and $IdLote2!='0'){
						$querySelect4="select Existencia
									from farm_entregamedicamento
									where IdLote=".$IdLote2;
						$respExistencia2=mysql_fetch_array(mysql_query($querySelect4));
						$Existencia_new2=$CantidadLote2+$respExistencia2["Existencia"];
						
						$queryUpdate2="update farm_entregamedicamento set Existencia='$Existencia_new2' where IdLote=".$IdLote2;
							mysql_query($queryUpdate2);
					}//IdLote != NULL
				}//IDEstado==S
			}//While
	
				
		}//ELSE AREA = 2
		
		
		/*****************		ELIMINACION DE RECETA Y DETALLE		***********************/
		$queryDeleteReceta="delete from farm_recetas where farm_recetas.IdReceta='$IdReceta'";
		$querySelect="select * from farm_medicinarecetada where farm_medicinarecetada.IdReceta='$IdReceta'";
		$queryDeleteMedicina="delete from farm_medicinarecetada where farm_medicinarecetada.IdReceta='$IdReceta'";
		$queryDeleteHistorialClinico="delete from sec_historial_clinico where sec_historial_clinico.IdHistorialClinico='$IdHistorialClinico'";
		
		
		$resp=mysql_fetch_array(mysql_query($querySelect));
			
		if($resp!=NULL){
		mysql_query($queryDeleteMedicina);
		}		
		mysql_query($queryDeleteReceta);


		$resp2=RecetasProceso::ObtenerIdRecetaRepetitivaEliminar($IdHistorialClinico,$IdPersonal);
		
		while($row=mysql_fetch_array($resp2)){
			$IdReceta2=$row["IdReceta"];
			$queryDeleteReceta2="delete from farm_recetas where farm_recetas.IdReceta='$IdReceta2'";
			$querySelect2="select * from farm_medicinarecetada where farm_medicinarecetada.IdReceta='$IdReceta2'";
			$queryDeleteMedicina2="delete from farm_medicinarecetada where farm_medicinarecetada.IdReceta='$IdReceta2'";

			$respuesta=mysql_fetch_array(mysql_query($querySelect2));
				if($respuesta!=NULL){
					mysql_query($queryDeleteMedicina2);
				}		
			mysql_query($queryDeleteReceta2);
		}//fin de while
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
		$querySelect="select farm_recetas.IdHistorialClinico, FechaConsulta, farm_medicinarecetada.*,farm_catalogoproductos.Nombre,farm_catalogoproductos.Concentracion,IdArea,FormaFarmaceutica,Presentacion,IdFarmacia
						from farm_medicinarecetada
						inner join farm_catalogoproductos
						on farm_catalogoproductos.IdMedicina=farm_medicinarecetada.IdMedicina
						inner join farm_recetas
						on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
						inner join sec_historial_clinico
						on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
						where (farm_recetas.IdReceta like '$IdReceta' or farm_recetas.CorrelativoAnual = '$IdReceta')
						order by farm_medicinarecetada.IdMedicinaRecetada desc";
		$resp=mysql_query($querySelect);
		return($resp);
	}//Obtener Medicina Introducida
	
	
	function ObtenerDatosGenerales($IdReceta){
		$query="select farm_recetas.IdArea,(select Area from mnt_areafarmacia where IdArea=farm_recetas.IdArea) as Area, NombreEmpleado,NombreSubServicio, NombreServicio as Origen,case farm_recetas.IdFarmacia when 1 then 'Central' when 2 then 'Con. Externa' when 3 then 'Emergencias' when 4 then 'Bodega' end as NombreFarmacia,IdAreaOrigen,(select Area from mnt_areafarmacia where IdArea=farm_recetas.IdAreaOrigen) as AreaOrigen
				from farm_recetas

				inner join sec_historial_clinico
				on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
				inner join mnt_empleados
				on mnt_empleados.IdEmpleado=sec_historial_clinico.IdEmpleado
				inner join mnt_subservicio
				on mnt_subservicio.IdSubServicio=sec_historial_clinico.IdSubServicio
				inner join mnt_servicio
				on mnt_servicio.IdServicio=mnt_subservicio.IdServicio
				where IdReceta=".$IdReceta;
		$resp=mysql_fetch_array(mysql_query($query));
		return($resp);		
	}
	
	
	function CambiarEstado($IdReceta){
		$query="update farm_recetas set IdEstado='XX' where (IdReceta like '".$IdReceta."' or CorrelativoAnual='$IdReceta')";
		mysql_query($query);
	}
	
	
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
		/*****************		RECUPERACION DE EXISTENCIAS DE MEDICAMENTOS		******************/
		/*
		$querySelect1="select CantidadLote1, Lote1, CantidadLote2,Lote2
					from farm_medicinarecetada
					where IdMedicinaRecetada=".$IdMedicinaRecetada;
		$resp=mysql_fetch_array(mysql_query($querySelect1));
		$Cantidad1=$resp[0];	
		$IdLote1=$resp[1];		
		$Cantidad2=$resp[2];	
		$IdLote2=$resp[3];		
		
		$querySelect2="select IdArea, farm_medicinarecetada.IdEstado 
					from farm_recetas 
					inner join farm_medicinarecetada
					on farm_medicinarecetada.IdReceta=farm_recetas.IdReceta
					where IdMedicinaRecetada=".$IdMedicinaRecetada;
		$IdArea=mysql_fetch_array(mysql_query($querySelect2));
		
		//*****	RECUPERACION DE EXISTENCIAS EN LOTE 1 *****
		
		if($IdArea[0]==2){
		//********************************************************
		//*				AREA DE CONSULTA EXTERNA				*
		//********************************************************
			if($IdArea[1]=='S'){
				$querySelect2="select Existencia
							from farm_medicinaexistenciaxarea
							where IdLote=".$IdLote1;
				$resp2=mysql_fetch_array(mysql_query($querySelect2));
				$Existencia=$resp2[0];
				$Existencia_new=$Existencia+$Cantidad1;
				
				$queryUpdate1="update farm_medicinaexistenciaxarea set Existencia='$Existencia_new' where IdLote=".$IdLote1;
				mysql_query($queryUpdate1);
			}
		}else{
		//********************************************************
		//*					TODAS LAS DEMAS AREAS				*
		//********************************************************
			if($IdArea[1]=='S'){
				$querySelect2="select Existencia
							from farm_entregamedicamento
							where IdLote=".$IdLote1;
				$resp2=mysql_fetch_array(mysql_query($querySelect2));
				$Existencia=$resp2[0];
				$Existencia_new=$Existencia+$Cantidad1;
				
				$queryUpdate1="update farm_entregamedicamento set Existencia='$Existencia_new' where IdLote=".$IdLote1;
				mysql_query($queryUpdate1);
			}
		}






		//*****	RECUPERACION DE EXISTENCIAS EN LOTE 2 *****
		if($IdArea[0]==2){
		//****************************************************************
		//*					AREA DE CONSULTA EXTERNA					*
		//****************************************************************
			if($IdLote2!='' and $IdLote2!=NULL and $IdLote2!='0'){
				$querySelect3="select Existencia
							from farm_medicinaexistenciaxarea
							where IdLote=".$IdLote2;
				$resp3=mysql_fetch_array(mysql_query($querySelect3));
				$Existencia2=$resp3[0];
				$Existencia_new2=$Existencia2+$Cantidad2;
				
				$queryUpdate2="update farm_medicinaexistenciaxarea set Existencia='$Existencia_new2' where IdLote=".$IdLote2;
				mysql_query($queryUpdate2);
			}
		}else{
		//********************************************************
		//*					TODAS LAS DEMAS AREAS				*
		//********************************************************
			if($IdLote2!='' and $IdLote2!=NULL and $IdLote2!='0'){
				$querySelect3="select Existencia
							from farm_entregamedicamento
							where IdLote=".$IdLote2;
				$resp3=mysql_fetch_array(mysql_query($querySelect3));
				$Existencia2=$resp3[0];
				$Existencia_new2=$Existencia2+$Cantidad2;
				
				$queryUpdate2="update farm_entregamedicamento set Existencia='$Existencia_new2' where IdLote=".$IdLote2;
				mysql_query($queryUpdate2);
			}
		
		}
		
		*/
		
		/*****************************************************************************************/
		
		$queryDelete="delete from farm_medicinarecetada where IdMedicinaRecetada='$IdMedicinaRecetada'";
		mysql_query($queryDelete);		
		
		
	}//EliminarMedicinaRecetada
	
	

	function UpdateDosis($IdMedicinaRecetada,$Dosis){
		$queryUpdate="update farm_medicinarecetada set Dosis='$Dosis' where IdMedicinaRecetada='$IdMedicinaRecetada'";
		mysql_query($queryUpdate);		
	}//UpdateDosis

	function UpdateCantidad($IdMedicinaRecetada,$Cantidad){
		$querySelect1="select Cantidad,IdEstado from farm_medicinarecetada where IdMedicinaRecetada=".$IdMedicinaRecetada;
		$rowCantidad=mysql_fetch_array(mysql_query($querySelect1));
		$querySelect2="select IdArea
					from farm_recetas
					inner join farm_medicinarecetada
					on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
					where IdMedicinaRecetada=".$IdMedicinaRecetada;
		$IdArea=mysql_fetch_array(mysql_query($querySelect2));
		
		if($IdArea[0]==2){
		$querySelect3="select IdMedicina, CantidadLote1,Lote1, CantidadLote2,Lote2 from farm_medicinarecetada where IdMedicinaRecetada=".$IdMedicinaRecetada;
		$resp1=mysql_fetch_array(mysql_query($querySelect3));
        $IdMedicina=$resp1["IdMedicina"];
		$CantidadLote1=$resp1["CantidadLote1"];
		$IdLote1=$resp1["Lote1"];
		$CantidadLote2=$resp1["CantidadLote2"];
		$IdLote2=$resp1["Lote2"];
		
			if($rowCantidad[1]=='S'){
				if($rowCantidad[0] < $Cantidad){
					/*	SUMA DE CANTIDAD, RESTA EN EXISTENCIAS	*/
					$Diferencia=$Cantidad-$rowCantidad[0]; 	//Diferencia es lo que falta para llegar al nuevo numero de 
															//medicamento

					if($IdLote2!='' and $IdLote2!=NULL and $IdLote2!='0'){
					/*		SE UTILIZO UN LOTE SECUNDARIO		*/
						$querySelect4="select Existencia from farm_medicinaexistenciaxarea where IdLote=".$IdLote2;
						$rowExistencia=mysql_fetch_array(mysql_query($querySelect4));
						
					/*		ACTUALIZACION DE EXISTENCIAS		*/
					if($rowExistencia[0]!=0){
						$Existencia_new=$rowExistencia[0]-$Diferencia;
				$UpdateExistencia="update farm_medicinaexistenciaxarea set Existencia='$Existencia_new' where IdLote=".$IdLote2;
						mysql_query($UpdateExistencia);
												
					/*		ACTUALIZACION DE LA CANTIDAD DE MEDICAMENTOS	*/							
						$NuevaCantidad=$CantidadLote2+$Diferencia;
				$UpdateCantidadLote2="update farm_medicinarecetada set CantidadLote2='$NuevaCantidad' where IdMedicinaRecetada=".$IdMedicinaRecetada;
						mysql_query($UpdateCantidadLote2);
					}
					/***********************************************************************************************/
						
						
					}else{
					/*		SE UTILIZO SOLAMENTE EL LOTE 1		*/
						$querySelect4="select Existencia from farm_medicinaexistenciaxarea where IdLote=".$IdLote1;
						$rowExistencia=mysql_fetch_array(mysql_query($querySelect4));
						
						if($rowExistencia[0]< $Diferencia){
						/*EXISTENCIA MENOR QUE LA DIFERENCIA A AUMENTAR*/
							$queryUpdate="update farm_medicinaexistenciaxarea set Existencia='0' where IdLote=".$IdLote1;
								mysql_query($queryUpdate);
							
							$NuevaCantidad1=$rowExistencia[0]+$CantidadLote1;
							$updateCantidad1="update farm_medicinarecetada set CantidadLote1='$NuevaCantidad1' where IdMedicinaRecetada=".$IdMedicinaRecetada;
								mysql_query($updateCantidad1);
							
							$Restante=$Diferencia-$rowExistencia[0];
							$NuevaCantidad2=$CantidadLote2+$Restante;

							
							if($IdLote2!='' and $IdLote2!='0' and $IdLote2!=NULL){
                                $SelectExistencia2="select Existencia from farm_medicinaexistenciaxarea where IdLote=".$IdLote2;
                                $rowExistencia2=mysql_fetch_array(mysql_query($SelectExistencia2)); 
                                
                            $UpdateCantidad2="update farm_medicinarecetada set CantidadLote2='$NuevaCantidad2' where IdMedicinaRecetada=".$IdMedicinaRecetada;
                                mysql_query($UpdateCantidad2);                                
                                
                            }else{
							    $SelectExistencia2="select Existencia,farm_lotes.IdLote 
                                from farm_medicinaexistenciaxarea 
                                inner join farm_lotes
                                on farm_lotes.IdLote=farm_medicinaexistenciaxarea.IdLote
                                where Existencia <> '0' and IdMedicina='$IdMedicina'
                                order by FechaVencimiento
                                limit 1";
                                $rowExistencia2=mysql_fetch_array(mysql_query($SelectExistencia2)); 
                                $IdLote2=$rowExistencia2["IdLote"];
                                
                            $UpdateCantidad2="update farm_medicinarecetada set CantidadLote2='$NuevaCantidad2',Lote2='$IdLote2' where IdMedicinaRecetada=".$IdMedicinaRecetada;
                                mysql_query($UpdateCantidad2);
                                                                
                            }   
                            
							
							$NuevaExistencia2=$rowExistencia2[0]-$Restante;
							$UpdateExistencia2="update farm_medicinaexistenciaxarea set Existencia='$NuevaExistencia2' where IdLote=".$IdLote2;
								mysql_query($UpdateExistencia2);
													
							
						}else{
							/*EXISTENCIA MAYOR DE LA DIFERENCIA A AUMENTAR*/
							$NuevaExistencia=$rowExistencia[0]-$Diferencia;
							
							$UpdateExistencia1="update farm_medicinaexistenciaxarea set Existencia='$NuevaExistencia' where IdLote=".$IdLote1;
								mysql_query($UpdateExistencia1);
							$NuevaCantidad1=$CantidadLote1+$Diferencia;
							
							$UpdateCantidad1="update farm_medicinarecetada set CantidadLote1='$NuevaCantidad1' where IdMedicinaRecetada=".$IdMedicinaRecetada;
								mysql_query($UpdateCantidad1);
							
							
						}
						
						
												
					}
					
					
				}else{
					/*	RESTA DE CANTIDAD, SUMA DE EXISTENCIA	*/
					$queryCantidad="select Cantidad from farm_medicinarecetada where IdMedicinaRecetada=".$IdMedicinaRecetada;
						$rowCantidad=mysql_fetch_array(mysql_query($queryCantidad));
					$Diferencia=$rowCantidad[0]-$Cantidad; 	//Diferencia es lo que falta para llegar al nuevo numero de 
															//medicamento

					
					
					$selectLotes="select CantidadLote1,Lote1,CantidadLote2,Lote2
								from farm_medicinarecetada
								where IdMedicinaRecetada=".$IdMedicinaRecetada;
					$respLotes=mysql_fetch_array(mysql_query($selectLotes));
					$CantidadLote1=$respLotes["CantidadLote1"];
					$IdLote1=$respLotes["Lote1"];
					$CantidadLote2=$respLotes["CantidadLote2"];
					$IdLote2=$respLotes["Lote2"];
					
					if($IdLote2!='0' and $IdLote2!='' and $IdLote2!=NULL){
						/*	SI SE UTILIZO EL LOTE SECUNDARIO ES EL PRIMERO EN SER DISMINUIDO	*/
						if($CantidadLote2 <= $Diferencia){
							/*SI LA CANTIDAD A ELIMINAR ES MENOR A LA DEL LOTE2*/
							$DiferenciaLotes=$Diferencia-$CantidadLote2;
							
							/*RECUPERACION DE EXISTENCIAS*/
							$SelectExistencia1="select Existencia from farm_medicinaexistenciaxarea where IdLote=".$IdLote1;
							$SelectExistencia2="select Existencia from farm_medicinaexistenciaxarea where IdLote=".$IdLote2;
								$rowExistencia1=mysql_fetch_array(mysql_query($SelectExistencia1));
								$rowExistencia2=mysql_fetch_array(mysql_query($SelectExistencia2));
							
							
							/******************************/
							
							
							$queryUpdateLote2="update farm_medicinarecetada set CantidadLote2='0', Lote2='0' where IdMedicinaRecetada=".$IdMedicinaRecetada;
							mysql_query($queryUpdateLote2);
							
							/*RECUPERACION DE EXISTENCIA*/
							$Existencia2_new=$rowExistencia2[0]+$CantidadLote2;
							$UpdateExistencia2="update farm_medicinaexistenciaxarea set Existencia='$Existencia2_new' where IdLote=".$IdLote2;
							mysql_query($UpdateExistencia2);
							
							
														
							$NuevaCantidadLote1=$CantidadLote1-$DiferenciaLotes;
							/*RECUPERACION DE EXISTENCIAS LOTE 1*/
							$Existencia1_new=$rowExistencia1[0]+$DiferenciaLotes;
							$UpdateExistencia1="update farm_medicinaexistenciaxarea set Existencia='$Existencia1_new' where IdLote=".$IdLote1;
							mysql_query($UpdateExistencia1);
							
							$queryUpdateLote1="update farm_medicinarecetada set CantidadLote1='$NuevaCantidadLote1' where IdMedicinaRecetada=".$IdMedicinaRecetada;
							mysql_query($queryUpdateLote1);
							
						}else{
							/*SI LA CANTIDAD A ELIMINAR ES MAYOR O IGUAL A LA DEL LOTE2*/
							$NuevaCantidadLote2=$CantidadLote2-$Diferencia;
							
							/*RECUPERACION DE EXISTENCIAS*/
							$SelectExistencia2="select Existencia from farm_medicinaexistenciaxarea where IdLote=".$IdLote2;
								$rowExistencia2=mysql_fetch_array(mysql_query($SelectExistencia2));
							$NuevaExistencia2=$rowExistencia2[0]+$Diferencia;
							
							$UpdateExistencia2="Update farm_medicinaexistenciaxarea set Existencia='$NuevaExistencia2' where IdLote=".$IdLote2;
								mysql_query($UpdateExistencia2);
							/********************************************/
							
							$UpdateCantidadLote2="update farm_medicinarecetada set CantidadLote2='$NuevaCantidadLote2' where IdMedicinaRecetada=".$IdMedicinaRecetada;
							mysql_query($UpdateCantidadLote2);
							
						}
						
					}else{
						/*	DISMINUCION DE LOTE 1 	*/
						
							$DiferenciaLote1=$CantidadLote1-$Diferencia;
							
							/*RECUPERACION DE EXISTENCIAS*/
							$SelectExistencia1="select Existencia from farm_medicinaexistenciaxarea where IdLote=".$IdLote1;
								$rowExistencia1=mysql_fetch_array(mysql_query($SelectExistencia1));
							
							/******************************/
							
							
							/*RECUPERACION DE EXISTENCIA*/
							$Existencia1_new=$rowExistencia1[0]+$Diferencia;
							$UpdateExistencia1="update farm_medicinaexistenciaxarea set Existencia='$Existencia1_new' where IdLote=".$IdLote1;
							mysql_query($UpdateExistencia1);
							
							$UpdateCantidadLote1="update farm_medicinarecetada set CantidadLote1='$DiferenciaLote1' where IdMedicinaRecetada=".$IdMedicinaRecetada;
							mysql_query($UpdateCantidadLote1);
						
					}
					
					
				}
			}//IdEstado =='S'
		}else{
			/*		SI EL MEDICAMENTO ES DE OTRA AREA DIFERENTE DE CON. EXT.		*/
			//	rowCantidad[] viene de la query principal al inicio de la funcion
			
$querySelect3="select CantidadLote1,Lote1, CantidadLote2,Lote2 from farm_medicinarecetada where IdMedicinaRecetada=".$IdMedicinaRecetada;
		$resp1=mysql_fetch_array(mysql_query($querySelect3));
		$Cantidad1=$resp1["CantidadLote1"];
		$IdLote1=$resp1["Lote1"];
		$CantidadLote2=$resp1["CantidadLote2"];
		$IdLote2=$resp1["Lote2"];
		
			if($rowCantidad[1]=='S'){
				if($rowCantidad[0] < $Cantidad){
					/*	SUMA DE CANTIDAD, RESTA EN EXISTENCIAS	*/
					$Diferencia=$Cantidad-$rowCantidad[0]; 	//Diferencia es lo que falta para llegar al nuevo numero de 
															//medicamento

					if($IdLote2!='' and $IdLote2!=NULL and $IdLote2!='0'){
					/*		SE UTILIZO UN LOTE SECUNDARIO		*/
						$querySelect4="select Existencia from farm_entregamedicamento where IdLote=".$IdLote2;
						$rowExistencia=mysql_fetch_array(mysql_query($querySelect4));
						
					/*		ACTUALIZACION DE EXISTENCIAS		*/
					if($rowExistencia[0]!=0){
						$Existencia_new=$rowExistencia[0]-$Diferencia;
				$UpdateExistencia="update farm_entregamedicamento set Existencia='$Existencia_new' where IdLote=".$IdLote2;
						mysql_query($UpdateExistencia);
												
					/*		ACTUALIZACION DE LA CANTIDAD DE MEDICAMENTOS	*/							
						$NuevaCantidad=$CantidadLote2+$Diferencia;
				$UpdateCantidadLote2="update farm_medicinarecetada set CantidadLote2='$NuevaCantidad' where IdMedicinaRecetada=".$IdMedicinaRecetada;
						mysql_query($UpdateCantidadLote2);
					}
					/***********************************************************************************************/
						
						
					}else{
					/*		SE UTILIZO SOLAMENTE EL LOTE 1		*/
						$querySelect4="select Existencia from farm_entregamedicamento where IdLote=".$IdLote1;
						$rowExistencia=mysql_fetch_array(mysql_query($querySelect4));
						
						if($rowExistencia[0]< $Diferencia){
						/*EXISTENCIA MENOR QUE LA DIFERENCIA A AUMENTAR*/
							$queryUpdate="update farm_entregamedicamento set Existencia='0' where IdLote=".$IdLote1;
								mysql_query($queryUpdate);
							
							$NuevaCantidad1=$rowExistencia[0]+$CantidadLote1;
							$updateCantidad1="update farm_medicinarecetada set CantidadLote1='$NuevaCantidad1' where IdMedicinaRecetada=".$IdMedicinaRecetada;
								mysql_query($updateCantidad1);
							
							$Restante=$Diferencia-$rowExistencia[0];
							$NuevaCantidad2=$CantidadLote2+$Restante;
					
							
                            if($IdLote2!='' and $IdLote2!='0' and $IdLote2!=NULL){
                                $SelectExistencia2="select Existencia from farm_medicinaexistenciaxarea where IdLote=".$IdLote2;
                                $rowExistencia2=mysql_fetch_array(mysql_query($SelectExistencia2)); 
                                
                                        $UpdateCantidad2="update farm_medicinarecetada set CantidadLote2='$NuevaCantidad2' where IdMedicinaRecetada=".$IdMedicinaRecetada;
                                mysql_query($UpdateCantidad2);
                                
                            }else{
                                $SelectExistencia2="select Existencia,farm_lotes.IdLote 
                                from farm_medicinaexistenciaxarea 
                                inner join farm_lotes
                                on farm_lotes.IdLote=farm_medicinaexistenciaxarea.IdLote
                                where Existencia <> '0' and IdMedicina='$IdMedicina'
                                order by FechaVencimiento
                                limit 1";
                                $rowExistencia2=mysql_fetch_array(mysql_query($SelectExistencia2)); 
                                $IdLote2=$rowExistencia2["IdLote"];
                                
                                        $UpdateCantidad2="update farm_medicinarecetada set CantidadLote2='$NuevaCantidad2',Lote2='$IdLote2' where IdMedicinaRecetada=".$IdMedicinaRecetada;
                                mysql_query($UpdateCantidad2);
                                
                            }   
							
							$NuevaExistencia2=$rowExistencia[0]-$Restante;
							$UpdateExistencia2="update farm_entregamedicamento set Existencia='$NuevaExistencia2' where IdLote=".$IdLote2;
								mysql_query($UpdateExistencia2);
													
							
						}else{
							/*EXISTENCIA MAYOR DE LA DIFERENCIA A AUMENTAR*/
							$NuevaExistencia=$rowExistencia[0]-$Diferencia;
							
							$UpdateExistencia1="update farm_entregamedicamento set Existencia='$NuevaExistencia' where IdLote=".$IdLote1;
								mysql_query($UpdateExistencia1);
							$NuevaCantidad1=$CantidadLote1+$Diferencia;
							
							$UpdateCantidad1="update farm_medicinarecetada set CantidadLote1='$NuevaCantidad1' where IdMedicinaRecetada=".$IdMedicinaRecetada;
								mysql_query($UpdateCantidad1);
							
							
						}
						
						
												
					}
					
					
				}else{
					/*	RESTA DE CANTIDAD, SUMA DE EXISTENCIA	*/
					$queryCantidad="select Cantidad from farm_medicinarecetada where IdMedicinaRecetada=".$IdMedicinaRecetada;
						$rowCantidad=mysql_fetch_array(mysql_query($queryCantidad));
					$Diferencia=$rowCantidad[0]-$Cantidad; 	//Diferencia es lo que falta para llegar al nuevo numero de 
															//medicamento

					
					
					$selectLotes="select CantidadLote1,Lote1,CantidadLote2,Lote2
								from farm_medicinarecetada
								where IdMedicinaRecetada=".$IdMedicinaRecetada;
					$respLotes=mysql_fetch_array(mysql_query($selectLotes));
					$CantidadLote1=$respLotes["CantidadLote1"];
					$IdLote1=$respLotes["Lote1"];
					$CantidadLote2=$respLotes["CantidadLote2"];
					$IdLote2=$respLotes["Lote2"];
					
					if($IdLote2!='0' and $IdLote2!='' and $IdLote2!=NULL){
						/*	SI SE UTILIZO EL LOTE SECUNDARIO ES EL PRIMERO EN SER DISMINUIDO	*/
						if($CantidadLote2 <= $Diferencia){
							/*SI LA CANTIDAD A ELIMINAR ES MENOR A LA DEL LOTE2*/
							$DiferenciaLotes=$Diferencia-$CantidadLote2;
							
							/*RECUPERACION DE EXISTENCIAS*/
							$SelectExistencia1="select Existencia from farm_entregamedicamento where IdLote=".$IdLote1;
							$SelectExistencia2="select Existencia from farm_entregamedicamento where IdLote=".$IdLote2;
								$rowExistencia1=mysql_fetch_array(mysql_query($SelectExistencia1));
								$rowExistencia2=mysql_fetch_array(mysql_query($SelectExistencia2));
							
							
							/******************************/
							
							
							$queryUpdateLote2="update farm_medicinarecetada set CantidadLote2='0', Lote2='0' where IdMedicinaRecetada=".$IdMedicinaRecetada;
							mysql_query($queryUpdateLote2);
							
							/*RECUPERACION DE EXISTENCIA*/
							$Existencia2_new=$rowExistencia2[0]+$CantidadLote2;
							$UpdateExistencia2="update farm_entregamedicamento set Existencia='$Existencia2_new' where IdLote=".$IdLote2;
							mysql_query($UpdateExistencia2);
							
							
														
							$NuevaCantidadLote1=$CantidadLote1-$DiferenciaLotes;
							/*RECUPERACION DE EXISTENCIAS LOTE 1*/
							$Existencia1_new=$rowExistencia1[0]+$DiferenciaLotes;
							$UpdateExistencia1="update farm_entregamedicamento set Existencia='$Existencia1_new' where IdLote=".$IdLote1;
							mysql_query($UpdateExistencia1);
							
							$queryUpdateLote1="update farm_medicinarecetada set CantidadLote1='$NuevaCantidadLote1' where IdMedicinaRecetada=".$IdMedicinaRecetada;
							mysql_query($queryUpdateLote1);
							
						}else{
							/*SI LA CANTIDAD A ELIMINAR ES MAYOR O IGUAL A LA DEL LOTE2*/
							$NuevaCantidadLote2=$CantidadLote-$Diferencia;
							
							/*RECUPERACION DE EXISTENCIAS*/
							$SelectExistencia2="select Existencia from farm_entregamedicamento where IdLote=".$IdLote2;
								$rowExistencia2=mysql_fetch_array(mysql_query($SelectExistencia2));
							$NuevaExistencia2=$rowExistencia2[0]+$Diferencia;
							
							$UpdateExistencia2="Update farm_entregamedicamento set Existencia='$NuevaExistencia2' where IdLote=".$IdLote2;
								mysql_query($UpdateExistencia2);
							/********************************************/
							
							$UpdateCantidadLote2="update farm_medicinarecetada set CantidadLote2='$NuevaCantidadLote2' where IdMedicinaRecetada=".$IdMedicinaRecetada;
							mysql_query($UpdateCantidadLote2);
							
						}
						
					}else{
						/*	DISMINUCION DE LOTE 1 	*/
						
							$DiferenciaLote1=$CantidadLote1-$Diferencia;
							
							/*RECUPERACION DE EXISTENCIAS*/
							$SelectExistencia1="select Existencia from farm_entregamedicamento where IdLote=".$IdLote1;
								$rowExistencia1=mysql_fetch_array(mysql_query($SelectExistencia1));
							
							/******************************/
							
							
							/*RECUPERACION DE EXISTENCIA*/
							$Existencia1_new=$rowExistencia1[0]+$Diferencia;
							$UpdateExistencia1="update farm_entregamedicamento set Existencia='$Existencia1_new' where IdLote=".$IdLote1;
							mysql_query($UpdateExistencia1);
							
							$UpdateCantidadLote1="update farm_medicinarecetada set CantidadLote1='$DiferenciaLote1' where IdMedicinaRecetada=".$IdMedicinaRecetada;
							mysql_query($UpdateCantidadLote1);
						
					}
					
					
				}
			}//IdEstado =='S'			
			
			
		}// else IdArea==2
	
		$queryUpdate="update farm_medicinarecetada set Cantidad='$Cantidad' where IdMedicinaRecetada='$IdMedicinaRecetada'";
		mysql_query($queryUpdate);		
	}//UpdateDosis
	
	function UpdateMedicinaRecetada($IdMedicinaRecetada,$Estado,$IdMedicina){
		if($Estado=='I'){
			/****************************************************/
			/*				RECUPERACION DE EXISTENCIAS			*/
			/****************************************************/
			$querySelect1="select IdArea
						from farm_recetas
						inner join farm_medicinarecetada
						on farm_medicinarecetada.IdReceta=farm_recetas.IdReceta
						where IdMedicinaRecetada=".$IdMedicinaRecetada;
			$IdArea=mysql_fetch_array(mysql_query($querySelect1));
			if($IdArea[0]==2){
				/****************************************************/
				/*				AREA CONSULTA EXTERNA				*/
				/****************************************************/
				$querySelect2="select CantidadLote1, Lote1, CantidadLote2, Lote2
							from farm_medicinarecetada where IdMedicinaRecetada=".$IdMedicinaRecetada;
				$resp2=mysql_fetch_array(mysql_query($querySelect2));
				$CantidadLote1=$resp2["CantidadLote1"];
				$IdLote1=$resp2["Lote1"];
				$CantidadLote2=$resp2["CantidadLote2"];
				$IdLote2=$resp2["Lote2"];
					/*****		LOTE 1		*****/
					$querySelect3="select Existencia from farm_medicinaexistenciaxarea where IdLote=".$IdLote1;
					$resp3=mysql_fetch_array(mysql_query($querySelect3));
					$Existencia_new1=$CantidadLote1+$resp3["Existencia"];
					$queryUpdate1="update farm_medicinaexistenciaxarea set Existencia='$Existencia_new1' where IdLote=".$IdLote1;
						mysql_query($queryUpdate1);
					
					/*****		LOTE 2		*****/
					if($IdLote2!='' and $IdLote2!=NULL and $IdLote2!='0'){
						$querySelect4="select Existencia from farm_medicinaexistenciaxarea where IdLote=".$IdLote2;
						$resp4=mysql_fetch_array(mysql_query($querySelect4));
						$Existencia_new2=$CantidadLote1+$resp4["Existencia"];
					$queryUpdate2="update farm_medicinaexistenciaxarea set Existencia='$Existencia_new2' where IdLote=".$IdLote2;
							mysql_query($queryUpdate2);
					
					}//SI HAY DATOS DEL LOTE 2
					
			}else{
				/****************************************************/
				/*					LAS DEMAS AREAS					*/
				/****************************************************/
				$querySelect2="select CantidadLote1, Lote1, CantidadLote2, Lote2
							from farm_medicinarecetada where IdMedicinaRecetada=".$IdMedicinaRecetada;
				$resp2=mysql_fetch_array(mysql_query($querySelect2));
				$CantidadLote1=$resp2["CantidadLote1"];
				$IdLote1=$resp2["Lote1"];
				$CantidadLote2=$resp2["CantidadLote2"];
				$IdLote2=$resp2["Lote2"];
					/*****		LOTE 1		*****/
					$querySelect3="select Existencia from farm_entregamedicamento where IdLote=".$IdLote1;
					$resp3=mysql_fetch_array(mysql_query($querySelect3));
					$Existencia_new1=$CantidadLote1+$resp3["Existencia"];
					$queryUpdate1="update farm_entregamedicamento set Existencia='$Existencia_new1' where IdLote=".$IdLote1;
						mysql_query($queryUpdate1);
					
					/*****		LOTE 2		*****/
					if($IdLote2!='' and $IdLote2!=NULL and $IdLote2!='0'){
						$querySelect4="select Existencia from farm_entregamedicamento where IdLote=".$IdLote2;
						$resp4=mysql_fetch_array(mysql_query($querySelect4));
						$Existencia_new2=$CantidadLote1+$resp4["Existencia"];
					$queryUpdate2="update farm_entregamedicamento set Existencia='$Existencia_new2' where IdLote=".$IdLote2;
							mysql_query($queryUpdate2);
					
					}//SI HAY DATOS DEL LOTE 2
			}//AREA NO ES C EXT		
			
			/*****************************************************/
			
			$queryUpdate="update farm_medicinarecetada set IdEstado='I', CantidadLote1='0', Lote1='0', CantidadLote2='0', Lote2='0' where IdMedicinaRecetada='$IdMedicinaRecetada'";
			mysql_query($queryUpdate);
			
		}else{
			
					$querySelectArea="select IdArea, farm_medicinarecetada.IdEstado 
									from farm_recetas 
									inner join farm_medicinarecetada
									on farm_medicinarecetada.IdReceta=farm_Recetas.IdReceta
									where IdMedicinaRecetada=".$IdMedicinaRecetada;
		$IdArea=mysql_fetch_array(mysql_query($querySelectArea));
		
		/********	MANTENIMIENTO DE EXISTENCIAS ********/
			if($IdArea[0]==2){
			/********************************************************/
			/*				AREA DE CONSULTA EXTERNA				*/
			/********************************************************/
				$querySelect1="select Existencia, farm_lotes.IdLote
							from farm_medicinaexistenciaxarea
							inner join farm_lotes
							on farm_lotes.IdLote=farm_medicinaexistenciaxarea.IdLote
							where IdMedicina='$IdMedicina'
							and Existencia <> '0'
							order by FechaVencimiento
							limit 1";
				$resp1=mysql_fetch_array(mysql_query($querySelect1));
				$IdLote=$resp1["IdLote"];
				
				$queryCantidad="select Cantidad,IdEstado from farm_medicinarecetada where IdMedicinaRecetada=".$IdMedicinaRecetada;
				$rowCantidad=mysql_fetch_array(mysql_query($queryCantidad));
				$Cantidad=$rowCantidad[0];
				
				
					if($resp1["Existencia"]<$Cantidad){
						$Restante=$Cantidad-$resp["Existencia"];
						$queryUpdate1="update farm_medicinaexistenciaxarea set Existencia='0' where IdLote=".$IdLote;
						mysql_query($queryUpdate);
							
							$CantidadLote1=$resp1["Existencia"];
							$Lote1=$resp1["IdLote"];
						
							$queryUpdate2="update farm_medicinarecetada set CantidadLote1='$CantidadLote1' , Lote1='$Lote1' where IdMedicinaRecetada='$IdMedicinaRecetada'";
							mysql_query($queryUpdate2);
							
							
							/*********	MANIPULACION DEL SEGUNDO LOTE	*********/
							$resp2=mysql_fetch_array(mysql_query($querySelect1));
							
							$Existencia_new=$resp2["Existencia"]-$Restante;
							$Lote2=$resp2["IdLote"];
						$queryUpdate3="update farm_medicinaexistenciaxarea set Existencia='$Existencia_new' where IdLote=".$Lote2;
						$queryUpdate4="update farm_medicinarecetada set CantidadLote2='$Restante', Lote2='$Lote2' 
									where IdMedicinaRecetada='$IdMedicinaRecetada'";
							mysql_query($queryUpdate3);
							mysql_query($queryUpdate4);			
							/***************************************************/
						
					}else{//SI EXISTENCIA < CANTIDAD
					
						/*****************	CANTIDAD < EXISTENCIA *****************/
						$Existencia_new=$resp1["Existencia"]-$Cantidad;
						
						$queryUpdate1="update farm_medicinaexistenciaxarea set Existencia='$Existencia_new' where IdLote=".$IdLote;
						mysql_query($queryUpdate1);
						
						$queryUpdate2="update farm_medicinarecetada set CantidadLote1='$Cantidad', Lote1='$IdLote' where IdMedicinaRecetada='$IdMedicinaRecetada'";
						mysql_query($queryUpdate2);	
						
						/*************************************************/
					}//ELSE EXISTENCIA < CANTIDAD
				
			}else{
				/************************************************************************/
				/*				TODAS LAS AREAS QUE NO SON CONSULTA EXTERNA 			*/				
				/************************************************************************/
				$querySelect="select Existencia,farm_Lotes.IdLote
							from farm_entregamedicamento
							inner join farm_lotes
							on farm_lotes.IdLote=farm_entregamedicamento.IdLote
							where IdLote='$IdMedicina'
							and Existencia <> 0
							order by FechaVencimiento
							limit 1";
				$resp=mysql_fetch_array(mysql_query($querySelect));
					$IdLote=$resp["IdLote"];
					
				$queryCantidad="select Cantidad,IdEstado from farm_medicinarecetada where IdMedicinaRecetada=".$IdMedicinaRecetada;
				$rowCantidad=mysql_fetch_array(mysql_query($queryCantidad));
				$Cantidad=$rowCantidad[0];

				
					if($resp["Existencia"]< $Cantidad){
						$Restante=$Cantidad-$resp["Existencia"];
						$queryUpdate1="update farm_entregamedicamento set Existencia='0' where IdLote=".$resp[1];
						mysql_query($queryUpdate);
							
							$CantidadLote1=$resp["Existencia"];
							$Lote1=$resp["IdLote"];
						
							$queryUpdate2="update farm_medicinarecetada set CantidadLote1='$CantidadLote1' , Lote1='$Lote1' where IdReceta='$IdMedicinaRecetada'";
							mysql_query($queryUpdate2);
							
							
							/*********	MANIPULACION DEL SEGUNDO LOTE	*********/
							$resp2=mysql_fetch_array(mysql_query($querySelect));
							
							$Existencia_new=$resp2["Existencia"]-$Restante;
							$Lote2=$resp2["IdLote"];
							$queryUpdate3="update farm_entregamedicamento set Existencia='$Existencia_new' where IdLote=".$resp2[1];
							$queryUpdate4="update farm_medicinarecetada set CantidadLote2='$Restante', Lote2='$Lote2' where IdMedicinaRecetada='$IdMedicinaRecetada'";
							mysql_query($queryUpdate3);
							mysql_query($queryUpdate4);			
							
							
							/***************************************************/
							
					}else{
	
					/************************************************/
						$Existencia_new=$resp["Existencia"]-$Cantidad;
	
						
						$queryUpdate1="update farm_entregamedicamento set Existencia='$Existencia_new' where IdLote=".$IdLote;
						mysql_query($queryUpdate1);
						
						$queryUpdate2="update farm_medicinarecetada set CantidadLote1='$Cantidad', Lote1='$IdLote' where IdMedicinaRecetada=".$IdMedicinaRecetada;
						mysql_query($queryUpdate2);	
						
						
					}//Cantidad < Existencia
				
		/************************************************/
			}//Else IdArea==2
		
		
		
			$queryUpdate="update farm_medicinarecetada set IdEstado='S' where IdMedicinaRecetada='$IdMedicinaRecetada'";
				mysql_query($queryUpdate);
		}
		
		
	}//UpdateMedicinaRecetada
	
	
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
	function ActualizarArea($IdArea,$IdReceta,$IdFarmacia,$Fecha){
	    //MANEJO DE EXISTENCIAS EN CASO DE CAMBIO DE AREA
		$SQL="select IdMedicinaRecetada,IdArea,Cantidad,IdMedicina
			from farm_medicinarecetada
			inner join farm_recetas
			on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
			where farm_medicinarecetada.IdReceta=".$IdReceta;
		$resp=mysql_query($SQL);
		
		while($row=mysql_fetch_array($resp)){
		   
			//se elimina el medicamento despachado de la area anterior y se aumentan existencias
			$this->AumentarInventario($row["IdMedicinaRecetada"],$row["IdArea"]);
			
			//se le atribuye a la futura area el cargo del medicamento a cambiar de area
			$this->ActualizarInventario($row["IdMedicina"],$row["IdMedicinaRecetada"],$row["Cantidad"],$IdArea,$Fecha);
		   
		}
		

	    //**************************************************

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
		
		$salida="<a href='#' onClick='javascript:Correcciones(\"NombreArea\");'>".strtoupper($resp[0])."</a>~";
		$salida.="<strong>".strtoupper($resp2[0])."</strong><input type='hidden' id='IdAreaActual' value='".$IdArea."'>";

		return($salida);		
	}//actualizaicon de area
	

function ActualizarAreaOrigen($IdArea,$IdReceta){
		$query="update farm_recetas set IdAreaOrigen='$IdArea' where IdReceta='$IdReceta'";
		mysql_query($query);

		$query2="select Area from mnt_areafarmacia where IdArea='$IdArea'";
		$resp=mysql_fetch_array(mysql_query($query2));
		
		$salida="<a href='#' onClick='javascript:Correcciones(\"NombreAreaOrigen\");'>".strtoupper($resp[0])."</a>
		";

		return($salida);

}


	function ObtenerIdFarmacia($IdArea){
		$query="select IdFarmacia 
				from mnt_areafarmacia
				where IdArea=".$IdArea;
		$resp=mysql_fetch_array(mysql_query($query));
		return($resp[0]);
	}

	
	function ActualizarMedico($IdHistorialClinico,$IdMedico){
		if($IdMedico!=''){
		$query="update sec_historial_clinico set IdEmpleado='$IdMedico' where IdHistorialClinico='$IdHistorialClinico'";
		mysql_query($query);
		}
		$query2="select NombreEmpleado from mnt_empleados inner join sec_historial_clinico shc on shc.IdEmpleado=mnt_empleados.IdEmpleado where IdHistorialClinico=".$IdHistorialClinico;
		$resp=mysql_fetch_array(mysql_query($query2));
		$salida="<a href='#' onClick='javascript:Correcciones(\"NombreMedico\");'>".$resp[0]."</a>";
		return($salida);
	}
	
	function ActualizarEspecialidad($IdHistorialClinico,$IdSubServicio){
		if($IdSubServicio!=''){	
		$query="update sec_historial_clinico set IdSubServicio='$IdSubServicio' where IdHistorialClinico='$IdHistorialClinico'";
		mysql_query($query);
		}

		$query2="select mnt_subservicio.IdSubServicio,NombreSubServicio, NombreServicio as Ubicacion
                        from mnt_subservicio
                        inner join mnt_servicio
                        on mnt_servicio.IdServicio=mnt_subservicio.Idservicio
                        inner join sec_historial_clinico shc
                        on shc.IdSubServicio=mnt_subservicio.IdSubServicio
                        where IdHistorialClinico='$IdHistorialClinico'
			";
		$resp=mysql_fetch_array(mysql_query($query2));
		if($resp[2]!='' and $resp[2]!=NULL){$Especialidad=$resp[2].' -> '.$resp[1];}else{$Especialidad=$resp[1];}
		$respuesta="<a href='#' onClick='javascript:Correcciones(\"Especialidad\");'>".strtoupper($Especialidad)."</a>";
		return($respuesta);
	}
	
	function ObtenerArea(){
		$query="select IdArea, Area, case IdFarmacia when 1 then '(Farm. Central)' when 2 then '(Con. Ext.)' when 3 then '(Emergencia)' end as Origen
			from mnt_areafarmacia
			where IdArea != 7 and IdArea != 12
			and Habilitado='S'	
			and IdFarmacia <>0
			order by IdFarmacia";

		$resp=mysql_query($query);
		return($resp);
	}
	
	function CambiarFecha($IdReceta,$FechaNueva,$IdHistorialClinico){
		$query="update farm_recetas set Fecha='$FechaNueva' where IdReceta=".$IdReceta;
		$query2="update farm_medicinarecetada set FechaEntrega='$FechaNueva' where IdReceta=".$IdReceta;
		$query3="update sec_historial_clinico set FechaConsulta='$FechaNueva' where IdHistorialClinico=".$IdHistorialClinico;
		mysql_query($query);
		mysql_query($query2);
		mysql_query($query3);
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
				$actualiza="update farm_medicinaexistenciaxarea set Existencia='0' where IdArea='$IdArea' and IdLote='$IdLote'";
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

function ObtenerAreaReceta($IdReceta){
   $SQL="select IdArea from farm_recetas where IdReceta=".$IdReceta;
   $resp=mysql_fetch_array(mysql_query($SQL));
   return($resp[0]);
}


function ObtenerAreaOrigenReceta($IdReceta){
   $SQL="select IdAreaOrigen from farm_recetas where IdReceta=".$IdReceta;
   $resp=mysql_fetch_array(mysql_query($SQL));
   return($resp[0]);
}


function ValorDivisor($IdMedicina){
   $SQL="select DivisorMedicina from farm_divisores where IdMedicina=".$IdMedicina;
   $resp=mysql_query($SQL);
   return($resp);
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