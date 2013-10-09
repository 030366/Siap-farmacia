<?php
require('../../Clases/class.php');

class TransferenciaProceso{
/*INTRODUCCION DE NUEVA TRANSFERENCIA*/

	function ObtenerExistencia($Lote,$Bandera,$IdAreaOrigen,$IdEstablecimiento,$IdModalidad){
	/*Se usa para obtener su existencia y para obtener el codigo de lote[despliegue del detalle]*/
		$querySelect="select IdExistencia,farm_medicinaexistenciaxarea.Existencia,farm_lotes.Lote
					from farm_medicinaexistenciaxarea
					inner join farm_lotes
					on farm_lotes.IdLote=farm_medicinaexistenciaxarea.IdLote
					where farm_lotes.IdLote='$Lote'
					and IdArea=".$IdAreaOrigen." 
                                        and Existencia <> 0
                                        and farm_medicinaexistenciaxarea.IdEstablecimiento=".$IdEstablecimiento." 
                                        and farm_medicinaexistenciaxarea.IdModalidad=$IdModalidad";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		if($Bandera==1){return($resp);}else{return($resp);}
	}//ObtenerExistencia
        
        
        function ObtenerExistencia2($Lote,$Bandera,$IdAreaOrigen,$IdEstablecimiento,$IdModalidad){
	/*Se usa para obtener su existencia y para obtener el codigo de lote[despliegue del detalle]*/
		$querySelect="select IdExistencia,farm_medicinaexistenciaxarea.Existencia,farm_lotes.Lote
					from farm_medicinaexistenciaxarea
					inner join farm_lotes
					on farm_lotes.IdLote=farm_medicinaexistenciaxarea.IdLote
					where farm_lotes.IdLote='$Lote'
					and IdArea=".$IdAreaOrigen." 
                                        and farm_medicinaexistenciaxarea.IdEstablecimiento=".$IdEstablecimiento." 
                                        and farm_medicinaexistenciaxarea.IdModalidad=$IdModalidad";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp);
	}//ObtenerExistencia
        

	function ObtenerSiguienteLote($IdMedicina,$Lote,$IdAreaOrigen,$IdEstablecimiento,$IdModalidad){
		$querySelect="select farm_lotes.IdLote, Existencia,IdExistencia
					from farm_lotes
					inner join farm_medicinaexistenciaxarea
					on farm_medicinaexistenciaxarea.IdLote=farm_lotes.IdLote
					where farm_lotes.IdLote <> '$Lote'
					and farm_medicinaexistenciaxarea.IdMedicina='$IdMedicina'
					and IdArea=".$IdAreaOrigen."
                                            and Existencia <> 0
                                        and farm_medicinaexistenciaxarea.IdEstablecimiento=".$IdEstablecimiento."
					and farm_medicinaexistenciaxarea.IdModalidad=$IdModalidad
                                        order by FechaVencimiento asc";

		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp);	
	}
	
	function IntroducirTransferencia($Cantidad,$IdMedicina,$IdAreaOrigen,$IdAreaDestino,$Justificacion,$FechaTransferencia,$IdPersonal,$Lote,$Divisor,$UnidadesContenidas,$IdEstablecimiento,$IdModalidad){
	/*CONTROL DE EXISTENCIA EN DADO CASO EL LOTE SELECCIONADO NO SUPLA LA CANTIDAD ENTERA SE DESCUENTA DEL SIGUIENTE LOTE*/
$Bandera=true;
while($Bandera){
		$Existencia=TransferenciaProceso::ObtenerExistencia($Lote,1,$IdAreaOrigen,$IdEstablecimiento,$IdModalidad);
                    $Existencia2=TransferenciaProceso::ObtenerExistencia($Lote,1,$IdAreaOrigen,$IdEstablecimiento,$IdModalidad);
		$Existencia=$Existencia["Existencia"]*$Divisor;
		$Cantidad=$Cantidad*$UnidadesContenidas;
		
		$falta=0;
		
	if($Existencia <= $Cantidad){
		//Si se necesita mas de un lote para suplir la transferencia
		$Cantidad2=$Cantidad-$Existencia;//restante a suplir
		$Cantidad1=($Existencia/$Divisor);
                        
		//Primera transferencia del lote agotado...
		$queryInsert="insert into farm_transferencias(Cantidad,IdMedicina,IdLote,IdAreaOrigen,IdAreaDestino,Justificacion,FechaTransferencia,IdPersonal,IdEstado,IdEstablecimiento,IdModalidad) 
                                                       values('$Cantidad1','$IdMedicina','$Lote','$IdAreaOrigen','$IdAreaDestino','$Justificacion','$FechaTransferencia','$IdPersonal','X',$IdEstablecimiento,$IdModalidad)";
			mysql_query($queryInsert);

			$IdTransferenciasN=mysql_insert_id();

		//SIL A AREA ES DIFERENTE DE CERO ES DECIR SI ES UNA TRANFERENCIA ENTRE FARMACIAS
		if($IdAreaDestino!=0 and $Cantidad1!=0){
		   $ver=TransferenciaProceso::ObtenerExistencia2($Lote,1,$IdAreaDestino,$IdEstablecimiento,$IdModalidad);
                        $IdExistenciaDestino=$ver["IdExistencia"];
		   if($IdExistenciaDestino==NULL or $IdExistenciaDestino==""){
		   //NO EXISTE INFORMACION DE ESTE LOTE NI DEL MEDICAMENTO EN CUESTION	
                        
			$SQL="insert into farm_medicinaexistenciaxarea (IdMedicina,IdArea,Existencia,IdLote,IdEstablecimiento,IdModalidad) 
                                                                 values('$IdMedicina','$IdAreaDestino','$Cantidad1','$Lote',$IdEstablecimiento,$IdModalidad)";
			mysql_query($SQL);
			$SQL2="insert into farm_bitacoramedicinaexistenciaxarea (IdMedicina,IdArea,Existencia,IdLote,FechaHoraIngreso,IdPersonal,IdTransferencia,IdEstablecimiento,IdModalidad)
                                                                          values('$IdMedicina','$IdAreaDestino','$Cantidad1','$Lote',now(),'$IdPersonal','$IdTransferenciasN',$IdEstablecimiento,$IdModalidad)";
			mysql_query($SQL2);
		   }
                   
                   
                   $ExistenciaDestino=number_format($ver["Existencia"],0,'.','');
		   if(($ExistenciaDestino==0 or $ExistenciaDestino!=0) and $ExistenciaDestino!=NULL and $ExistenciaDestino!=""){
		   //SI EXISTE INFORMACION DEL LOTE PERO EL MEDICAMENTO ESTA COMPLETAMENTE AGOTADO O APUNTO DE...
                                              
			$Cantidad_nueva=$Cantidad1+$ExistenciaDestino;
                        
			$SQL="update farm_medicinaexistenciaxarea set Existencia='$Cantidad_nueva' 
                              where IdMedicina='$IdMedicina' and IdArea='$IdAreaDestino' 
                              and IdLote='$Lote' and IdExistencia=".$ver["IdExistencia"]."
                              ";
                        mysql_query($SQL);

			$SQL2="insert into farm_bitacoramedicinaexistenciaxarea (IdMedicina,IdArea,Existencia,IdLote,FechaHoraIngreso,IdPersonal,IdTransferencia,IdEstablecimiento,IdModalidad) 
                                                                          values('$IdMedicina','$IdAreaDestino','$Cantidad1','$Lote',now(),'$IdPersonal','$IdTransferenciasN',$IdEstablecimiento,$IdModalidad)";
			mysql_query($SQL2);
		   }

		}
		//*******************************************************************************


		$SQL="update farm_medicinaexistenciaxarea set Existencia = '0' 
                      where IdMedicina='$IdMedicina' and IdLote='$Lote' and IdArea='$IdAreaOrigen' and IdExistencia=".$Existencia2["IdExistencia"];
			mysql_query($SQL);

		$respLote2=TransferenciaProceso::ObtenerSiguienteLote($IdMedicina,$Lote,$IdAreaOrigen,$IdEstablecimiento,$IdModalidad);
		$Lote=$respLote2[0];
		$Cantidad=($Cantidad2/$UnidadesContenidas);
			if($Lote==NULL or $Lote==''){$Bandera=1;$falta=$Cantidad;}

                        
		if($Cantidad==0){$Bandera=1;}
                
                

	}else{
            
                
            
		$Cantidad1=($Cantidad/$Divisor);
		$queryInsert="insert into farm_transferencias(Cantidad,IdMedicina,IdLote,IdAreaOrigen,IdAreaDestino,Justificacion,FechaTransferencia,IdPersonal,IdEstado,IdEstablecimiento,IdModalidad) 
                                                       values('$Cantidad1','$IdMedicina','$Lote','$IdAreaOrigen','$IdAreaDestino','$Justificacion','$FechaTransferencia','$IdPersonal','X',$IdEstablecimiento,$IdModalidad)";
		mysql_query($queryInsert);
			

			$IdTransferenciasN=mysql_insert_id();

		//SIL A AREA ES DIFERENTE DE CERO ES DECIR SI ES UNA TRANFERENCIA ENTRE FARMACIAS
		if($IdAreaDestino!=0){
		   $ver=TransferenciaProceso::ObtenerExistencia2($Lote,1,$IdAreaDestino,$IdEstablecimiento,$IdModalidad);
		   $IdExistenciaDestino=$ver["IdExistencia"];
		   if($IdExistenciaDestino==NULL or $IdExistenciaDestino==""){
		   //NO EXISTE INFORMACION DE ESTE LOTE NI DEL MEDICAMENTO EN CUESTION	
			$SQL="insert into farm_medicinaexistenciaxarea (IdMedicina,IdArea,Existencia,IdLote,IdEstablecimiento,IdModalidad) 
                                                                 values('$IdMedicina','$IdAreaDestino','$Cantidad1','$Lote',$IdEstablecimiento,$IdModalidad)";
			mysql_query($SQL);
			$SQL2="insert into farm_bitacoramedicinaexistenciaxarea (IdMedicina,IdArea,Existencia,IdLote,FechaHoraIngreso,IdPersonal,IdTransferencia,IdEstablecimiento,IdModalidad) 
                                                                          values('$IdMedicina','$IdAreaDestino','$Cantidad1','$Lote',now(),'$IdPersonal','$IdTransferenciasN',$IdEstablecimiento,$IdModalidad)";
			mysql_query($SQL2);
		   }
                   
		   $ExistenciaDestino=number_format($ver["Existencia"],0,'.','');
		   if(($ExistenciaDestino==0 or $ExistenciaDestino!=0) and $ExistenciaDestino!=NULL and $ExistenciaDestino!=""){
		   //SI EXISTE INFORMACION DEL LOTE PERO EL MEDICAMENTO ESTA COMPLETAMENTE AGOTADO O APUNTO DE...
			$Cantidad_nueva=$Cantidad1+$ExistenciaDestino;
			$SQL="update farm_medicinaexistenciaxarea set Existencia='$Cantidad_nueva' 
                              where IdMedicina='$IdMedicina' and IdArea='$IdAreaDestino' and IdLote='$Lote' and IdExistencia=".$ver["IdExistencia"];
			mysql_query($SQL);
			$SQL2="insert into farm_bitacoramedicinaexistenciaxarea (IdMedicina,IdArea,Existencia,IdLote,FechaHoraIngreso,IdPersonal,IdTransferencia,IdEstablecimiento,IdModalidad) 
                                                                          values('$IdMedicina','$IdAreaDestino','$Cantidad1','$Lote',now(),'$IdPersonal','$IdTransferenciasN',$IdEstablecimiento,$IdModalidad)";
			mysql_query($SQL2);
		   }

		}
		//*******************************************************************************

		$Existencia_new=($Existencia2["Existencia"]*$Divisor)-($Cantidad1*$Divisor);//Existencia remanente despues de transferencia
                        $Existencia_new=$Existencia_new/$Divisor;
		$SQL="update farm_medicinaexistenciaxarea set Existencia = '$Existencia_new' 
                      where IdMedicina='$IdMedicina' and IdLote='$Lote' and IdArea='$IdAreaOrigen' and IdExistencia=".$Existencia2["IdExistencia"];
			mysql_query($SQL);

	      	$Bandera=false;
		$falta=0;
                
                
	}

}
return($falta);
	/**********************************************/

		
	}//Introducir Transferencia



	function ObtenerTransferencias($IdPersonal,$Fecha){
	/*OBTENCION DE INFORMES INTRODUCIDOS POR EL USUARIO SIN SER FINALIZADOS*/
		$querySelect="select farm_transferencias.Cantidad,farm_catalogoproductos.Nombre,farm_catalogoproductos.Concentracion, Presentacion,Descripcion, 
					mnt_areafarmacia.Area,farm_transferencias.Justificacion,farm_transferencias.IdAreaDestino,
					farm_transferencias.IdTransferencia,farm_lotes.Lote,farm_catalogoproductos.IdMedicina
					from farm_transferencias
					inner join farm_catalogoproductos
					on farm_catalogoproductos.IdMedicina=farm_transferencias.IdMedicina
					inner join mnt_areafarmacia
					on mnt_areafarmacia.IdArea=farm_transferencias.IdAreaOrigen
					inner join farm_lotes
					on farm_lotes.IdLote=farm_transferencias.IdLote
					inner join farm_unidadmedidas fum
					on fum.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida
					where farm_transferencias.IdPersonal='$IdPersonal'
					and farm_transferencias.FechaTransferencia = '$Fecha'
					and farm_transferencias.IdEstado='X'";
		$resp=mysql_query($querySelect);
		return($resp);	
	}//Obtener transferencias



	function NombreArea($IdArea){
		$querySelect="select mnt_areafarmacia.Area
					from mnt_areafarmacia
					where mnt_areafarmacia.IdArea='$IdArea'";
		if($resp=mysql_fetch_array(mysql_query($querySelect))){
			return($resp[0]);
		}else{
			return("Otras Areas");
		}
	}

	
/* ELIMINAR */	
	
	function EliminarTransferencia($IdTransferencia,$IdModalidad){

		$SQL="select * from farm_transferencias where IdTransferencia=".$IdTransferencia;
		$row=mysql_fetch_array(mysql_query($SQL));

		$IdMedicina=$row["IdMedicina"];
		$Cantidad=$row["Cantidad"];
		$IdLote=$row["IdLote"];
		$IdAreaOrigen=$row["IdAreaOrigen"];
		$IdAreaDestino=$row["IdAreaDestino"];
		$IdPersonal=$row["IdPersonal"];
                
                $IdEstablecimiento=$row["IdEstablecimiento"];

		if($IdAreaDestino!=0){
			$SQL1="select * from farm_medicinaexistenciaxarea 
                               where IdArea=$IdAreaDestino 
                               and IdMedicina=$IdMedicina and IdLote=$IdLote and Existencia <> 0
                               and IdEstablecimiento=".$IdEstablecimiento." 
                               and IdModalidad=$IdModalidad";
                        
			$respDestino=mysql_fetch_array(mysql_query($SQL1));
			
			$Existencia_Actual_Destino=$respDestino["Existencia"];
				$IdExistenciaDestino=$respDestino["IdExistencia"];
			if($Existencia_Actual_Destino!=0){
			   $Existencia_Nueva_Destino=$Existencia_Actual_Destino-$Cantidad;
				$SQL4="update farm_medicinaexistenciaxarea set Existencia='$Existencia_Nueva_Destino' where IdExistencia=".$IdExistenciaDestino;
				mysql_query($SQL4);
			}
			
			
		}


		$SQL2="select * 
			from farm_medicinaexistenciaxarea fmexa
			inner join farm_lotes fl
			on fl.IdLote = fmexa.IdLote
			
			where IdMedicina='$IdMedicina'
			and fl.IdLote='$IdLote'
			and IdArea=".$IdAreaOrigen."
                        and fmexa.IdEstablecimiento=".$IdEstablecimiento." 
                        and fmexa.IdModalidad=$IdModalidad";

		$resp=mysql_fetch_array(mysql_query($SQL2));
		
                $Divisor=$this->ValorDivisor($IdMedicina,$IdEstablecimiento,$IdModalidad);
                    if($valorDivisor=mysql_fetch_array($Divisor)){
                        $TDivisor=$valorDivisor[0];
                    }else{
                        $TDivisor=1;
                    }
                
                //Transformacion de medicamentos
		$ExistenciaActual=$resp["Existencia"]*$TDivisor;
               
                $Cantidad=$Cantidad*$TDivisor;
                // ******************************************
                
		  $Existencia_new=$ExistenciaActual+$Cantidad;
                  
                        $Existencia_new=$Existencia_new/$TDivisor; //Se regresa a unidad original
		
		$SQL3="update farm_medicinaexistenciaxarea set Existencia='$Existencia_new' 
                       where IdExistencia=".$resp["IdExistencia"];
		   mysql_query($SQL3);


		$querySelect="delete from farm_transferencias 
                              where IdTransferencia='$IdTransferencia'";
		mysql_query($querySelect);

		$SQL33="delete from farm_bitacoramedicinaexistenciaxarea 
                        where IdTransferencia='$IdTransferencia'";
		mysql_query($SQL33);

                return($Cantidad."~".$Existencia_new);
	}//ObtenerIdRecetaRepetitivaEliminar


/*FINALIZA TODAS LAS TRANSFERENCIAS*/	
	function FinalizaTransferencia($IdPersonal){
		$queryUpdate="update farm_transferencias set IdEstado='D' where IdPersonal='$IdPersonal' and IdEstado='X'";
		mysql_query($queryUpdate);
	}//Receta Lista


	function ObtenerCantidadMedicina($IdPersonal){
		$querySelect="select farm_transferencias.Cantidad1,farm_transferencias.Cantidad2,farm_transferencias.IdMedicina,
				farm_transferencias.IdAreaOrigen as IdArea,farm_transferencias.IdLote,farm_transferencias.IdLote2
				from farm_transferencias
				where farm_transferencias.FechaTransferencia=curdate()
				and farm_transferencias.IdEstado='X'
				and farm_transferencias.IdPersonal='$IdPersonal'";
		$resp=mysql_query($querySelect);
		return($resp);
	}//ObtenerCantidadMedicina


	function ObtenerLotesMedicamento($IdMedicina,$Cantidad,$IdAreaOrigen,$IdEstablecimiento,$IdModalidad){
		$querySelect="select sum(Existencia),farm_lotes.IdLote,
                                     if (left(farm_lotes.FechaVencimiento,7) < left(curdate(),7), 
                                        concat_ws(' ',farm_lotes.Lote,' [Lote Vencido]'), 
                                        farm_lotes.Lote) as Lote, 
                                     farm_lotes.FechaVencimiento
					from farm_lotes
					inner join farm_medicinaexistenciaxarea
					on farm_medicinaexistenciaxarea.IdLote=farm_lotes.IdLote
					where farm_medicinaexistenciaxarea.IdMedicina='$IdMedicina'
					and farm_medicinaexistenciaxarea.Existencia <> 0	
					and IdArea=".$IdAreaOrigen."
					and farm_medicinaexistenciaxarea.IdEstablecimiento=".$IdEstablecimiento."
                                        and farm_medicinaexistenciaxarea.IdModalidad=$IdModalidad
					group by farm_lotes.IdLote
					order by farm_lotes.FechaVencimiento";
		$resp=mysql_query($querySelect);
		return($resp);
	}//ObtenerLotesMedicamento


	function ObtenerDetalleLote($IdTransferencia){
		$querySelect="select Cantidad, Lote, fl.IdLote
				from farm_transferencias ft
				inner join farm_lotes fl
				on fl.IdLote = ft.IdLote
					where IdTransferencia='$IdTransferencia'";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp);
	}//ObtenerDetalleLote

	function ValorDivisor($IdMedicina,$IdEstablecimiento,$IdModalidad){
	   $SQL="select DivisorMedicina from farm_divisores 
                 where IdMedicina=".$IdMedicina." 
                 and IdEstablecimiento=$IdEstablecimiento
                 and IdModalidad=$IdModalidad";
	   $resp=mysql_query($SQL);
	   return($resp);
    	}
	
	function UnidadesContenidas($IdMedicina){
	  $SQL="select UnidadesContenidas,Descripcion
		from farm_unidadmedidas fu
		inner join farm_catalogoproductos fcp
		on fcp.IdUnidadMedida = fu.IdUnidadMedida
		where IdMedicina=".$IdMedicina;
	  $resp=mysql_fetch_array(mysql_query($SQL));
	  return($resp[0]);
	}


}//Clase RecetasProceso


?>