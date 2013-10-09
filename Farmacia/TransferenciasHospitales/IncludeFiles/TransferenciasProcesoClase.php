<?php
require('../../Clases/class.php');

class TransferenciaProceso{
/*INTRODUCCION DE NUEVA TRANSFERENCIA*/

	function ObtenerExistencia($Lote,$Bandera,$IdEstablecimiento){
	/*Se usa para obtener su existencia y para obtener el codigo de lote[despliegue del detalle]*/
		$querySelect="select farm_entregamedicamento.Existencia,farm_lotes.Lote
					from farm_entregamedicamento
					inner join farm_lotes
					on farm_lotes.IdLote=farm_entregamedicamento.IdLote
					where farm_lotes.IdLote='$Lote'
                                        and farm_entregamedicamento.IdEstablecimiento=$IdEstablecimiento
					";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		if($Bandera==1){return($resp[0]);}else{return($resp);}
	}//ObtenerExistencia

	function ObtenerSiguienteLote($IdMedicina,$Lote,$IdEstablecimiento){
		$querySelect="select farm_lotes.IdLote, Existencia
					from farm_lotes
					inner join farm_entregamedicamento
					on farm_entregamedicamento.IdLote=farm_lotes.IdLote
					where farm_lotes.IdLote <> '$Lote'
					and farm_entregamedicamento.IdMedicina='$IdMedicina'
					and farm_entregamedicamento.IdEstablecimiento=$IdEstablecimiento
					order by FechaVencimiento asc";

		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp);	
	}
	
	function IntroducirTransferencia($Cantidad,$IdMedicina,$IdEstablecimientoOrigen,$IdEstablecimientoDestino,$Justificacion,$FechaTransferencia,$IdPersonal,$Lote){
	/*CONTROL DE EXISTENCIA EN DADO CASO EL LOTE SELECCIONADO NO SUPLA LA CANTIDAD ENTERA SE DESCUENTA DEL SIGUIENTE LOTE*/
$Bandera=0;
while($Bandera==0){
		$Existencia=TransferenciaProceso::ObtenerExistencia($Lote,1,$IdEstablecimientoOrigen);

	if($Existencia < $Cantidad){
		//Si se necesita mas de un lote para suplir la transferencia
		$Cantidad2=$Cantidad-$Existencia;//restante a suplir
		$Cantidad1=$Existencia;
		
		//Primera transferencia del lote agotado...
		$queryInsert="insert into farm_transferenciashospitales(Cantidad,IdMedicina,IdLote,IdEstablecimientoOrigen,IdEstablecimientoDestino,Justificacion,FechaTransferencia,IdPersonal,IdEstado) values('$Cantidad1','$IdMedicina','$Lote','$IdEstablecimientoOrigen','$IdEstablecimientoDestino','$Justificacion','$FechaTransferencia','$IdPersonal','X')";
			mysql_query($queryInsert);


		$SQL="update farm_entregamedicamento set Existencia = '0' where IdMedicina='$IdMedicina' and IdLote='$Lote' and IdEstablecimiento=".$IdEstablecimientoOrigen;
			mysql_query($SQL);

		$respLote2=TransferenciaProceso::ObtenerSiguienteLote($IdMedicina,$Lote,$IdEstablecimientoOrigen);
		$Lote=$respLote2[0];
		$Cantidad=$Cantidad2;
			if($Lote==NULL or $Lote==''){$Bandera=1;$falta=$Cantidad;}

	}else{
		$Cantidad1=$Cantidad;
		$queryInsert="insert into farm_transferenciashospitales(Cantidad,IdMedicina,IdLote,IdEstablecimientoOrigen,IdEstablecimientoDestino,Justificacion,FechaTransferencia,IdPersonal,IdEstado) values('$Cantidad1','$IdMedicina','$Lote','$IdEstablecimientoOrigen','$IdEstablecimientoDestino','$Justificacion','$FechaTransferencia','$IdPersonal','X')";
		mysql_query($queryInsert);

		$Existencia_new=$Existencia-$Cantidad;//Existencia remanente despues de transferencia

		$SQL="update farm_entregamedicamento set Existencia = '$Existencia_new' where IdMedicina='$IdMedicina' and IdLote='$Lote' and IdEstablecimiento=".$IdEstablecimientoOrigen;
			mysql_query($SQL);

	      	$Bandera=1;
		$falta=0;
	}

	return($falta);
}

	/**********************************************/

		
	}//Introducir Transferencia



	function ObtenerTransferencias($IdPersonal,$Fecha,$IdEstablecimiento){
	/*OBTENCION DE INFORMES INTRODUCIDOS POR EL USUARIO SIN SER FINALIZADOS*/
		$querySelect="select farm_transferenciashospitales.Cantidad,farm_catalogoproductos.Nombre,farm_catalogoproductos.Concentracion, Presentacion,Descripcion, farm_transferenciashospitales.Justificacion,farm_transferenciashospitales.IdEstablecimientoDestino,
(select concat_ws(' [',Nombre,concat_ws(']',NOMSIBASI,''))as Nombre from mnt_establecimiento where IdEstablecimiento=farm_transferenciashospitales.IdEstablecimientoDestino)as EstablecimientoDestino,
		farm_transferenciashospitales.IdTransferencia,farm_lotes.Lote
		from farm_transferenciashospitales
		inner join farm_catalogoproductos
		on farm_catalogoproductos.IdMedicina=farm_transferenciashospitales.IdMedicina
		inner join farm_lotes
		on farm_lotes.IdLote=farm_transferenciashospitales.IdLote
		inner join farm_unidadmedidas fum
		on fum.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida
		where farm_transferenciashospitales.IdPersonal='$IdPersonal'
		and farm_transferenciashospitales.FechaTransferencia = '$Fecha'
		and farm_transferenciashospitales.IdEstado='X'";
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
	
	function EliminarTransferencia($IdTransferencia){

		$SQL="select * from farm_transferenciashospitales where IdTransferencia=".$IdTransferencia;
		$row=mysql_fetch_array(mysql_query($SQL));

		$IdMedicina=$row["IdMedicina"];
		$Cantidad=$row["Cantidad"];
		$IdLote=$row["IdLote"];
		$IdEstablecimientoOrigen=$row["IdEstablecimientoOrigen"];
		$IdEstablecimientoDestino=$row["IdEstablecimientoDestino"];


		$SQL2="select * 
			from farm_entregamedicamento fmexa
			inner join farm_lotes fl
			on fl.IdLote = fmexa.IdLote
			
			where IdMedicina='$IdMedicina'
			and fl.IdLote='$IdLote'
                        and fmexa.IdEstablecimiento=$IdEstablecimientoOrigen
			";

		$resp=mysql_fetch_array(mysql_query($SQL2));
		
		$ExistenciaActual=$resp["Existencia"];
		  $Existencia_new=$ExistenciaActual+$Cantidad;
		
		$SQL3="update farm_entregamedicamento set Existencia='$Existencia_new' where IdEntrega=".$resp["IdEntrega"];
		   mysql_query($SQL3);


		$querySelect="delete from farm_transferenciashospitales where IdTransferencia='$IdTransferencia'";
		mysql_query($querySelect);
return($Cantidad."~".$Existencia_new);
	}//ObtenerIdRecetaRepetitivaEliminar


/*FINALIZA TODAS LAS TRANSFERENCIAS*/	
	function FinalizaTransferencia($IdPersonal){
		$queryUpdate="update farm_transferenciashospitales set IdEstado='D' where IdPersonal='$IdPersonal' and IdEstado='X'";
		mysql_query($queryUpdate);
	}//Receta Lista


	function ObtenerCantidadMedicina($IdPersonal){
		$querySelect="select farm_transferenciashospitales.Cantidad1,farm_transferenciashospitales.Cantidad2,farm_transferenciashospitales.IdMedicina,
				farm_transferenciashospitales.IdEstablecimientoOrigen as IdArea,farm_transferenciashospitales.IdLote,farm_transferenciashospitales.IdLote2
				from farm_transferenciashospitales
				where farm_transferenciashospitales.FechaTransferencia=curdate()
				and farm_transferenciashospitales.IdEstado='X'
				and farm_transferenciashospitales.IdPersonal='$IdPersonal'";
		$resp=mysql_query($querySelect);
		return($resp);
	}//ObtenerCantidadMedicina


	function ObtenerLotesMedicamento($IdMedicina,$Cantidad,$IdEstablecimientoOrigen){
		$querySelect="select sum(Existencia),farm_lotes.IdLote,farm_lotes.Lote, farm_lotes.FechaVencimiento, UnidadesContenidas
					from farm_lotes
					inner join farm_entregamedicamento
					on farm_entregamedicamento.IdLote=farm_lotes.IdLote
					inner join farm_catalogoproductos
					on farm_catalogoproductos.IdMedicina=farm_entregamedicamento.IdMedicina
					inner join farm_unidadmedidas fu
					on fu.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida
					where farm_entregamedicamento.IdMedicina='$IdMedicina'
					and farm_entregamedicamento.Existencia <> 0
                                        and farm_entregamedicamento.IdEstablecimiento=$IdEstablecimientoOrigen
					and left(farm_lotes.FechaVencimiento,7)>=left(curdate(),7)
					group by farm_lotes.IdLote
					order by farm_lotes.FechaVencimiento";
		$resp=mysql_query($querySelect);
		return($resp);
	}//ObtenerLotesMedicamento


	function ObtenerDetalleLote($IdTransferencia){
		$querySelect="select ft.IdMedicina,Cantidad, Lote, fl.IdLote, UnidadesContenidas
				from farm_transferenciashospitales ft
				inner join farm_lotes fl
				on fl.IdLote = ft.IdLote
				inner join farm_catalogoproductos fcp
				on fcp.IdMedicina=ft.IdMedicina
				inner join farm_unidadmedidas fu
				on fu.IdUnidadMedida=fcp.IdUnidadMedida
					where IdTransferencia='$IdTransferencia'";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp);
	}//ObtenerDetalleLote

	function ValorDivisor($IdMedicina,$IdEstablecimiento){
	   $SQL="select DivisorMedicina from farm_divisores 
                    where IdMedicina=".$IdMedicina." and IdEstablecimiento=".$IdEstablecimiento;
	   $resp=mysql_query($SQL);
	   return($resp);
    	}
}//Clase RecetasProceso


?>