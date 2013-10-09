<?php
require('../../Clases/class.php');

class TransferenciaProceso{
/*INTRODUCCION DE NUEVA TRANSFERENCIA*/

	function ObtenerExistencia($Lote,$Bandera,$IdAreaOrigen,$TipoFarmacia,$IdEstablecimiento,$IdModalidad){
	/*Se usa para obtener su existencia y para obtener el codigo de lote[despliegue del detalle]*/
	    if($IdAreaOrigen!=12 and $TipoFarmacia==2){
		$querySelect="select farm_medicinaexistenciaxarea.Existencia,farm_lotes.Lote
					from farm_medicinaexistenciaxarea
					inner join farm_lotes
					on farm_lotes.IdLote=farm_medicinaexistenciaxarea.IdLote
					where farm_lotes.IdLote='$Lote'
					and IdArea=".$IdAreaOrigen."
                                        and farm_medicinaexistenciaxarea.IdEstablecimiento=".$IdEstablecimiento." 
                                        and farm_medicinaexistenciaxarea.IdModalidad=$IdModalidad";
	    }
	   if($IdAreaOrigen==12 or $TipoFarmacia==1){
		$querySelect="select farm_entregamedicamento.Existencia,farm_lotes.Lote
					from farm_entregamedicamento
					inner join farm_lotes
					on farm_lotes.IdLote=farm_entregamedicamento.IdLote
					where farm_lotes.IdLote='$Lote'
					and farm_entregamedicamento.IdEstablecimiento=".$IdEstablecimiento." 
                                        and farm_entregamedicamento.IdModalidad=$IdModalidad";
		
	    }

		$resp=mysql_fetch_array(mysql_query($querySelect));
		if($Bandera==1){return($resp[0]);}else{return($resp);}
	}//ObtenerExistencia

	function ObtenerSiguienteLote($IdMedicina,$Lote,$IdAreaOrigen,$TipoFarmacia,$IdEstablecimiento,$IdModalidad){
	if($IdAreaOrigen!=12 and $TipoFarmacia==2){
		$querySelect="select farm_lotes.IdLote, Existencia
					from farm_lotes
					inner join farm_medicinaexistenciaxarea
					on farm_medicinaexistenciaxarea.IdLote=farm_lotes.IdLote
					where farm_lotes.IdLote <> '$Lote'
					and farm_medicinaexistenciaxarea.IdMedicina='$IdMedicina'
					and IdArea=".$IdAreaOrigen."
                                        and farm_medicinaexistenciaxarea.IdEstablecimiento=$IdEstablecimiento
                                        and farm_medicinaexistenciaxarea.IdModalidad=$IdModalidad
					and Existencia <> 0
					and left(FechaVencimiento,7) < left(curdate(),7)
					order by FechaVencimiento asc";
	}
	if($IdAreaOrigen==12 or $TipoFarmacia==1){
		$querySelect="select farm_lotes.IdLote, Existencia
					from farm_lotes
					inner join farm_entregamedicamento
					on farm_entregamedicamento.IdLote=farm_lotes.IdLote
					where farm_lotes.IdLote <> '$Lote'
					and farm_entregamedicamento.IdMedicina='$IdMedicina'
					and IdArea=".$IdAreaOrigen."
                                        and farm_entregamedicamento.IdEstablecimiento=$IdEstablecimiento
                                        and farm_entregamedicamento.IdModalidad=$IdModalidad
					and Existencia <> 0
					and left(FechaVencimiento,7) < left(curdate(),7)
					order by FechaVencimiento asc";	
	}
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp);	
	}
	
	function IntroducirDescargos($Cantidad,$IdMedicina,$IdAreaOrigen,$Justificacion,$FechaDescargo,$IdPersonal,$Lote,$TipoFarmacia,$IdEstablecimiento,$IdModalidad){
	/*CONTROL DE EXISTENCIA EN DADO CASO EL LOTE SELECCIONADO NO SUPLA LA CANTIDAD ENTERA SE DESCUENTA DEL SIGUIENTE LOTE*/
$Bandera=0;
while($Bandera==0){
		$Existencia=TransferenciaProceso::ObtenerExistencia($Lote,1,$IdAreaOrigen,$TipoFarmacia,$IdEstablecimiento,$IdModalidad);

	if($Existencia < $Cantidad){
		//Si se necesita mas de un lote para suplir la transferencia
		$Cantidad2=$Cantidad-$Existencia;//restante a suplir
		$Cantidad1=$Existencia;
		
		//Primera transferencia del lote agotado...
		$queryInsert="insert into farm_medicinavencida(IdMedicina,Existencia,IdLote,IdArea,Justificacion,Fecha,FechaHoraIngreso,IdPersonal,IdEstablecimiento,IdModalidad) 
                                                        values('$IdMedicina','$Cantidad1','$Lote','$IdAreaOrigen','$Justificacion','$FechaDescargo',now(),'$IdPersonal',$IdEstablecimiento,$IdModalidad)";
			mysql_query($queryInsert);

		//*******************************************************************************

		if($IdAreaOrigen!=12 and $TipoFarmacia==2){
		    $SQL="update farm_medicinaexistenciaxarea set Existencia = '0' 
                          where IdMedicina='$IdMedicina' and IdLote='$Lote' 
                          and IdArea='$IdAreaOrigen' and IdEstablecimiento=".$IdEstablecimiento." 
                          and IdModalidad=$IdModalidad";
			mysql_query($SQL);
		}
		if($IdAreaOrigen==12 or $TipoFarmacia==1){
		    $SQL="update farm_entregamedicamento set Existencia = '0' 
                          where IdMedicina='$IdMedicina' and IdLote='$Lote' 
                          and IdEstablecimiento=".$IdEstablecimiento." 
                          and IdModalidad=$IdModalidad";
			mysql_query($SQL);
		}

		$respLote2=TransferenciaProceso::ObtenerSiguienteLote($IdMedicina,$Lote,$IdAreaOrigen,$TipoFarmacia,$IdEstablecimiento,$IdModalidad);
		$Lote=$respLote2[0];
		$Cantidad=$Cantidad2;
			if($Lote==NULL or $Lote==''){$Bandera=1;$falta=$Cantidad;}

	}else{
		$Cantidad1=$Cantidad;
		$queryInsert="insert into farm_medicinavencida(IdMedicina,Existencia,IdLote,IdArea,Justificacion,Fecha,FechaHoraIngreso,IdPersonal,IdEstablecimiento,IdModalidad) 
                                                        values('$IdMedicina','$Cantidad1','$Lote','$IdAreaOrigen','$Justificacion','$FechaDescargo',now(),'$IdPersonal',$IdEstablecimiento,$IdModalidad)";
		mysql_query($queryInsert);
			

		$Existencia_new=$Existencia-$Cantidad;//Existencia remanente despues de transferencia

		if($IdAreaOrigen!=12 and $TipoFarmacia==2){
		    $SQL="update farm_medicinaexistenciaxarea set Existencia = '$Existencia_new' 
                          where IdMedicina='$IdMedicina' and IdLote='$Lote' 
                          and IdArea='$IdAreaOrigen' and IdEstablecimiento=".$IdEstablecimiento." 
                          and IdModalidad=$IdModalidad";
			mysql_query($SQL);
		}
		if($IdAreaOrigen==12 or $TipoFarmacia==1){
		    $SQL="update farm_entregamedicamento set Existencia = '$Existencia_new' 
                          where IdMedicina='$IdMedicina' and IdLote='$Lote' 
                          and IdEstablecimiento=".$IdEstablecimiento." 
                          and IdModalidad=$IdModalidad";
			mysql_query($SQL);
		}


	      	$Bandera=1;
		$falta=0;
	}

	return($falta);
}

	/**********************************************/

		
}//Introducir Transferencia



	function ObtenerDescargos($IdPersonal,$Fecha,$IdEstablecimiento,$IdModalidad){
	/*OBTENCION DE INFORMES INTRODUCIDOS POR EL USUARIO SIN SER FINALIZADOS*/
		$querySelect="select farm_medicinavencida.IdMedicina, farm_medicinavencida.Existencia,farm_catalogoproductos.Nombre,farm_catalogoproductos.Concentracion, Presentacion,Descripcion, 
					mnt_areafarmacia.Area,farm_medicinavencida.Justificacion,
					farm_medicinavencida.IdEntrega,farm_lotes.Lote, date_format(Fecha,'%d-%m-%Y') as Fecha
					from farm_medicinavencida
					inner join farm_catalogoproductos
					on farm_catalogoproductos.IdMedicina=farm_medicinavencida.IdMedicina
					inner join mnt_areafarmacia
					on mnt_areafarmacia.IdArea=farm_medicinavencida.IdArea
					inner join farm_lotes
					on farm_lotes.IdLote=farm_medicinavencida.IdLote
					inner join farm_unidadmedidas fum
					on fum.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida
					where farm_medicinavencida.IdPersonal='$IdPersonal'
                                        and farm_medicinavencida.IdEstablecimiento=$IdEstablecimiento
                                        and farm_medicinavencida.IdModalidad=$IdModalidad
					and date(farm_medicinavencida.FechaHoraIngreso) = curdate()
					order by IdEntrega asc";
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
	
	function EliminarDescargo($IdEntrega,$TipoFarmacia,$IdEstablecimiento,$IdModalidad){

		$SQL="select * from farm_medicinavencida where IdEntrega=".$IdEntrega;
		$row=mysql_fetch_array(mysql_query($SQL));

		$IdMedicina=$row["IdMedicina"];
		$Cantidad=$row["Existencia"];
		$IdLote=$row["IdLote"];
		$IdAreaOrigen=$row["IdArea"];
		

		if($IdAreaOrigen!=12 and $TipoFarmacia==2){
		$SQL2="select * 
			from farm_medicinaexistenciaxarea fmexa
			inner join farm_lotes fl
			on fl.IdLote = fmexa.IdLote
			
			where IdMedicina='$IdMedicina'
			and fl.IdLote='$IdLote'
			and IdArea=".$IdAreaOrigen."
                        and fmexa.IdEstablecimiento=".$IdEstablecimiento." 
                        and fmexa.IdModalidad=$IdModalidad";

		}
		if($IdAreaOrigen==12 or $TipoFarmacia==1){
		$SQL2="select * 
			from farm_entregamedicamento fmexa
			inner join farm_lotes fl
			on fl.IdLote = fmexa.IdLote
			
			where IdMedicina='$IdMedicina'
			and fl.IdLote='$IdLote'
			and fmexa.IdEstablecimiento=".$IdEstablecimiento." 
                        and fmexa.IdModalidad=$IdModalidad";
		}

		$resp=mysql_fetch_array(mysql_query($SQL2));
		
		$ExistenciaActual=$resp["Existencia"];
		  $Existencia_new=$ExistenciaActual+$Cantidad;

		if($IdAreaOrigen!=12 and $TipoFarmacia==2){
		
		$SQL3="update farm_medicinaexistenciaxarea set Existencia='$Existencia_new' 
                       where IdExistencia=".$resp["IdExistencia"]." and IdEstablecimiento=".$IdEstablecimiento." 
                       and IdModalidad=$IdModalidad";

		}
		if($IdAreaOrigen==12 or $TipoFarmacia==1){
		
		$SQL3="update farm_entregamedicamento set Existencia='$Existencia_new' 
                       where IdEntrega=".$resp["IdEntrega"]." and IdEstablecimiento=".$IdEstablecimiento." 
                       and IdModalidad=$IdModalidad";

		}
 		mysql_query($SQL3);

		$querySelect="delete from farm_medicinavencida where IdEntrega='$IdEntrega'";
		mysql_query($querySelect);
return($Cantidad."~".$Existencia_new);
	}//ObtenerIdRecetaRepetitivaEliminar


/*FINALIZA TODAS LAS TRANSFERENCIAS*/	
	function FinalizaTransferencia($IdPersonal){
		$queryUpdate="update farm_transferencias set IdEstado='D' 
                              where IdPersonal='$IdPersonal' and IdEstado='X'";
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


	function ObtenerLotesMedicamento($IdMedicina,$Motivo,$IdArea,$TipoFarmacia,$IdEstablecimiento,$IdModalidad){
	   if($Motivo==1){$comp="and left(farm_lotes.FechaVencimiento,7) < left(curdate(),7)";}else{$comp="and left(farm_lotes.FechaVencimiento,7) >= left(curdate(),7)";}

		if($IdArea!=12 and $TipoFarmacia==2){

		$querySelect="select farm_lotes.IdLote,farm_lotes.Lote, farm_lotes.FechaVencimiento
					from farm_lotes
					inner join farm_medicinaexistenciaxarea
					on farm_medicinaexistenciaxarea.IdLote=farm_lotes.IdLote
					where farm_medicinaexistenciaxarea.IdMedicina='$IdMedicina'
					and farm_medicinaexistenciaxarea.Existencia <> 0
                                        and farm_medicinaexistenciaxarea.IdEstablecimiento=$IdEstablecimiento
                                        and farm_medicinaexistenciaxarea.IdModalidad=$IdModalidad
					".$comp."
					group by farm_lotes.IdLote
					order by farm_lotes.FechaVencimiento";
		}
		if($IdArea==12 or $TipoFarmacia==1){
		$querySelect="select farm_lotes.IdLote,farm_lotes.Lote, farm_lotes.FechaVencimiento
					from farm_lotes
					inner join farm_entregamedicamento
					on farm_entregamedicamento.IdLote=farm_lotes.IdLote
					where farm_entregamedicamento.IdMedicina='$IdMedicina'
					and farm_entregamedicamento.Existencia <> 0
                                        and farm_entregamedicamento.IdEstablecimiento=$IdEstablecimiento
                                        and farm_entregamedicamento.IdModalidad=$IdModalidad
					".$comp."
					group by farm_lotes.IdLote
					order by farm_lotes.FechaVencimiento";

		}


		$resp=mysql_query($querySelect);
		return($resp);
	}//ObtenerLotesMedicamento


	function ObtenerDetalleLote($IdEntrega){
		$querySelect="select Existencia, Lote, fl.IdLote
				from farm_medicinavencida ft
				inner join farm_lotes fl
				on fl.IdLote = ft.IdLote
					where IdEntrega='$IdEntrega'";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp);
	}//ObtenerDetalleLote

	function ValorDivisor($IdMedicina,$IdEstablecimiento,$IdModalidad){
	   $SQL="select DivisorMedicina from farm_divisores 
                 where IdMedicina=".$IdMedicina." 
                 and IdEstablecimiento=".$IdEstablecimiento." 
                 and IdModalidad=$IdModalidad";
	   $resp=mysql_query($SQL);
	   return($resp);
    	}
}//Clase RecetasProceso


?>