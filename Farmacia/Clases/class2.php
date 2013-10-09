<?php
/*CONEXION A LA DB*/
class conexion{
	function conectar()
	{
		mysql_connect("localhost","farma","farm4");
		mysql_select_db("siap");
	}
	
	function desconectar()
	{
		mysql_close();
	}
}//fin de la clase conexion
	/********************/
class encabezado{
		function top($NombreDeFarmacia,$tipoUsuario,$nick,$nombre){
		if($NombreDeFarmacia==1){
			$NombreDeFarmacia="Central";
		}elseif($NombreDeFarmacia==2){
			$NombreDeFarmacia="Consulta Externa";
		}elseif($NombreDeFarmacia==3){$NombreDeFarmacia="Emergencia"; }
			else{
				$NombreDeFarmacia="Control Global";
		}
		echo"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Nombre de Usuario:</strong>&nbsp;&nbsp; $nombre </br>
		<strong>Farmacia:</strong>&nbsp;&nbsp; $NombreDeFarmacia";
		}//funcion top
}//fin de clase

class queries{

/*********************INICIO DE SESION************************/
function InicioSesion($usuario,$contra){
	$contra=md5($contra);
	$querySelect="select Correlativo, NombreEmpleado,Nivel,mnt_empleados.IdEmpleado
					from mnt_empleados
					inner join mnt_usuarios
					on mnt_empleados.IdEmpleado=mnt_usuarios.IdEmpleado
					where login='$usuario'
					and password='$contra'
					and modulo='ALM'";
	$resp=mysql_query($querySelect);
	if($row=mysql_fetch_array($resp)){
		$_SESSION["Nick"]=$usuario;
		$_SESSION["NombreEmpleado"]=$row["NombreEmpleado"];
		$_SESSION["Nivel"]=$row["Nivel"];
		$_SESSION["IdUsuario"]=$row["Correlativo"];
		$_SESSION["IdEmpleado"]=$row["IdEmpleado"];
			$querySelect="select EstadoCuenta from alm_estadocuenta where IdEmpleado='".$row["IdEmpleado"]."'";
			$resp=mysql_fetch_array(mysql_query($querySelect));
			if($resp[0]==NULL or $resp[0]!='I'){
				$_SESSION["EstadoCuenta"]='H';
				$respuesta='S';
			}else{
				$_SESSION["EstadoCuenta"]=$resp[0];
				$respuesta='N';
			}
		
				
			
	}else{
			$respuesta='N';
		
	}
	
	return($respuesta);
		
}//Inicio Sesion

function NombreEmpleado($IdEmpleado){
$querySelect="select mnt_empleados.NombreEmpleado
			from mnt_empleados
			where mnt_empleados.IdEmpleado='$IdEmpleado'";
$resp=mysql_fetch_array(mysql_query($querySelect));
return($resp[0]);
}//NombreEmpleado


function LotesDeEntrega($IdMedicina){
	$querySelect="select Cantidad, Lote, DATE_FORMAT(FechaVencimiento,'%d/%m/%Y') as FechaVencimiento
			from alm_entregamedicamento
			inner join farm_lotes
			on farm_lotes.IdLote=alm_entregamedicamento.IdLote
			where alm_entregamedicamento.IdMedicina=$IdMedicina
			and IdEstado=1";
	$resp=mysql_query($querySelect);
	return($resp);
}



function ObtenerLotesPorExistencia($IdMedicina){
	$querySelect="select farm_entregamedicamento.Existencia,farm_lotes.IdLote, Lote, DATE_FORMAT(FechaVencimiento,'%d-%m-%Y') as FechaVencimiento
				from farm_lotes
				inner join farm_entregamedicamento
				on farm_entregamedicamento.IdLote=farm_lotes.IdLote
				where farm_entregamedicamento.IdMedicina='$IdMedicina'
				and Existencia <> '0'
				order by FechaVencimiento";
	$resp=mysql_query($querySelect);
	$datos="<select id='IdLote' name='IdLote' onclick='javascript:AlmExistencia(this.value);'>
	<option value='0'>[Seleccione...]</option>";	
	while($row=mysql_fetch_array($resp)){
		$datos.="<option value='".$row["IdLote"]."'>".$row["Lote"]." - ".$row["FechaVencimiento"]."</option>";
	}//while
	$datos.="</select>";
return($datos);

}//Lotes por Existencia

function EmpleadoNumeroCorrelativo($IdEmpleado){
	$querySelect="select Correlativo
	from mnt_empleados
	where IdEmpleado='$IdEmpleado'";
	$resp=mysql_fetch_array(mysql_query($querySelect));
	return($resp[0]);
}



function ObtenerExistenciaPorLote($IdLote){
	$querySelect="select Existencia from farm_entregamedicamento where IdLote=".$IdLote;
	$resp=mysql_fetch_array(mysql_query($querySelect));
	return($resp["Existencia"]);
}

/*	TRANSFERENCIA DE MEDICAMENTO DE ALMACEN A FARMACIA	*/
function RealizarTransferencia($IdMedicina,$Cantidad,$IdLote,$IdUsuarioReg){
	
	$querySelect="select Existencia from alm_existencias where IdLote=".$IdLote;
		$resp=mysql_fetch_array(mysql_query($querySelect));
		$Existencia_new=$resp[0]-$Cantidad;
		$Existencia_old=$resp[0];
		
		$cargaBal = $Existencia_new;
		$siguiente=0;
if($Existencia_new < 0){
/*		SI LA CANTIDAD A SER TRANSFERIDA ES MENOR QUE LA EXISTENCIA ACTUAL		*/
		$Existencia_new = 0;
		
	$queryInsert="insert into alm_entregamedicamento (IdMedicina,Cantidad,IdLote,Fecha,IdEstado,IdUsuarioReg) values('$IdMedicina','".$Existencia_old."','$IdLote',curdate(),'1','$IdUsuarioReg')";
	$resp=mysql_query($queryInsert) or die('N');

			
	/* BALANCEO DEINAMICO DE LOTES PARA CUBRIR DEMANDA */
	while($cargaBal < 0 ){
		$cargaBal=$cargaBal*-1;
	
		$querySelect="select Existencia,alm_existencias.IdLote, FechaVencimiento
					from alm_existencias
					inner join farm_lotes
					on farm_lotes.IdLote=alm_existencias.IdLote
					
					where IdMedicina='$IdMedicina'
					and alm_existencias.IdLote != '$IdLote'
					order by FechaVencimiento limit $siguiente,1";
		$CargaLotes=mysql_fetch_array(mysql_query($querySelect));	
		 $cargaBal=$CargaLotes["Existencia"]-$cargaBal;
		 
		 	if($cargaBal < 0){
				$Cantidad=$CargaLotes["Existencia"];
			}else{
				$Cantidad=($cargaBal-$CargaLotes["Existencia"])*-1;
			}
			
		 
$queryInsert="insert into alm_entregamedicamento (IdMedicina,Cantidad,IdLote,Fecha,IdEstado,IdUsuarioReg) values('".$IdMedicina."','".$Cantidad."','".$CargaLotes["IdLote"]."',curdate(),'1','$IdUsuarioReg')";
	$resp=mysql_query($queryInsert);		 
		 
		 $siguiente ++;
	}//fin de while  negativo			
			
}else{
	/*		SI LA RESTA DE LA EXISTENCIA MENOS LA CANTIDAD PEDIDA ES MAYOR QUE CERO		*/

			$querySelectTrans="select IdMedicina, Cantidad, IdLote from alm_entregamedicamento where IdLote='$IdLote' and IdUsuarioReg='$IdUsuarioReg' and IdEstado=1";
			$respTrans=mysql_query($querySelectTrans);
			
			if($testTrans=mysql_fetch_array($respTrans)){
			//si hay datos previos de ese lote se hace una actualizacion sumando las nueva cantidad a transferir
					
					$Cantidad_new=$testTrans["Cantidad"]+$Cantidad;
					$queryUpdateTrans="update alm_entregamedicamento set Cantidad='$Cantidad_new' where IdLote=".$IdLote;
					$resp=mysql_query($queryUpdateTrans);

			}else{	// si hay no hay datos de ese lote se introduce un registro nuevo

	$queryInsert="insert into alm_entregamedicamento (IdMedicina,Cantidad,IdLote,Fecha,IdEstado,IdUsuarioReg) values('$IdMedicina','$Cantidad','$IdLote',curdate(),'1','$IdUsuarioReg')";
	$resp=mysql_query($queryInsert) or die('N');
			}

}
		
		
	$queryUpdate="update alm_existencias set Existencia='$Existencia_new' where IdLote=".$IdLote;
		$resp=mysql_query($queryUpdate);		
	
	return($resp);
		
}//Fn de Funcion


function RealizarTransferenciaDoble($IdMedicina,$Cantidad,$IdLote,$IdUsuarioReg){
	
			$querySelect="select Existencia from alm_existencias where IdLote=".$IdLote;
			$total=mysql_fetch_array(mysql_query($querySelect));
			
			$querySelectTrans="select IdMedicina, Cantidad, IdLote from alm_entregamedicamento where IdLote='$IdLote' and IdUsuarioReg='$IdUsuarioReg' and IdEstado=1";
			$respTrans=mysql_query($querySelectTrans);
			
			if($testTrans=mysql_fetch_array($respTrans)){
			//si hay datos previos de ese lote se hace una actualizacion sumando las nueva cantidad a transferir
					
					$Cantidad_new=$total[0]+$testTrans["Cantidad"];
					$queryUpdateTrans="update alm_entregamedicamento set Cantidad='$Cantidad_new' where IdLote=".$IdLote;
					$resp=mysql_query($queryUpdateTrans);

			}else{	// si hay no hay datos de ese lote se introduce un registro nuevo
			
			
					$queryInsert="insert into alm_entregamedicamento (IdMedicina,Cantidad,IdLote,Fecha,IdEstado,IdUsuarioReg) values('$IdMedicina','".$total[0]."','$IdLote',curdate(),'1','$IdUsuarioReg')";
					$resp=mysql_query($queryInsert);
			}//else
	
	
	
	
			$queryUpdate="update alm_existencias set Existencia='0' where IdLote=".$IdLote;
				mysql_query($queryUpdate);
	
	
	
		$cargaBal=$total[0]-$Cantidad;
		$siguiente=0;
	
	/* BALANCEO DEINAMICO DE LOTES PARA CUBRIR DEMANDA */
	while($cargaBal < 0 ){
		$cargaBal=$cargaBal*-1;
	
		$querySelect="select Existencia,alm_existencias.IdLote, FechaVencimiento
					from alm_existencias
					inner join farm_lotes
					on farm_lotes.IdLote=alm_existencias.IdLote
					
					where IdMedicina='$IdMedicina'
					and alm_existencias.IdLote != '$IdLote'
					order by FechaVencimiento limit $siguiente,1";
			
		if($test=mysql_fetch_array(mysql_query($querySelect))){
			
				$CargaLotes=mysql_fetch_array(mysql_query($querySelect));	
				 $cargaBal=$CargaLotes["Existencia"]-$cargaBal;
				 
					if($cargaBal < 0){
						$Cantidad=$CargaLotes["Existencia"];
							$Existencia_new=0;
					}else{
						$Cantidad=($cargaBal-$CargaLotes["Existencia"])*-1;
							$Existencia_new=$CargaLotes["Existencia"]-$Cantidad;
							$IdLote_last=$CargaLotes["IdLote"];
					}
					
				 
		$queryInsert="insert into alm_entregamedicamento (IdMedicina,Cantidad,IdLote,Fecha,IdEstado,IdUsuarioReg) values('".$IdMedicina."','".$Cantidad."','".$CargaLotes["IdLote"]."',curdate(),'1','$IdUsuarioReg')";
			$resp=mysql_query($queryInsert);		
			
			
			$queryUpdate="update alm_existencias set Existencia='$Existencia_new' where IdLote=".$CargaLotes["IdLote"];
					mysql_query($queryUpdate);
			
				 
				 $siguiente ++;
		 
		 
		 }else{//fin de IF datos
		 	$IdLote_last=0;
		 }
	}//fin de while  negativo
		 
		 	
	return($IdLote_last);
		
}//Transferencia Doble



function DesplegarTransferencias($IdUsuarioReg,$Externo){
	if($Externo==0){
	$querySelect2="select alm_entregamedicamento.IdMedicina,alm_entregamedicamento.IdEntrega,farm_catalogoproductos.Nombre, farm_catalogoproductos.Concentracion,
				alm_entregamedicamento.Cantidad,farm_lotes.Lote,farm_lotes.IdLote,FechaVencimiento
				from farm_catalogoproductos
				inner join alm_entregamedicamento
				on alm_entregamedicamento.IdMedicina=farm_catalogoproductos.IdMedicina
				inner join farm_lotes
				on farm_lotes.IdLote=alm_entregamedicamento.IdLote
				where alm_entregamedicamento.IdUsuarioReg=$IdUsuarioReg
				and alm_entregamedicamento.IdEstado=1
				and Fecha=curdate()";	
	}else{
	$querySelect2="select alm_entregamedicamento.IdMedicina,farm_catalogoproductos.Nombre, farm_catalogoproductos.Concentracion,
				sum(alm_entregamedicamento.Cantidad)as Cantidad,farm_lotes.IdLote
				from farm_catalogoproductos
				inner join alm_entregamedicamento
				on alm_entregamedicamento.IdMedicina=farm_catalogoproductos.IdMedicina
				inner join farm_lotes
				on farm_lotes.IdLote=alm_entregamedicamento.IdLote
				where alm_entregamedicamento.IdUsuarioReg=$IdUsuarioReg
				and alm_entregamedicamento.IdEstado=1
				and Fecha=curdate()
				group by alm_entregamedicamento.IdMedicina
				order by farm_catalogoproductos.Nombre";
	
	
	}
				
	$resp2=mysql_query($querySelect2);
	$resp3=mysql_query($querySelect2);
	
	$datos="<table align='center' width='100%'>";
	if($Externo==1){
		$datos.="
			<tr><td align='right' colspan='6'>Fecha de Reporte:&nbsp;&nbsp;&nbsp;<strong>".date('d/m/Y')."</strong></td></tr>
			<tr><td colspan='6' align='center'><strong>HOSPITAL NACIONAL ROSALES</strong></td></tr>
			<tr><td colspan='6' align='center'><strong>ALMACEN DE MEDICAMENTOS</strong></td></tr>
			<tr><td colspan='6' align='center'><strong>LISTADO DE MEDICAMENTOS A ENTREGAR</strong><br><br></td></tr>";
		
	}
	
	
	$datos.="<tr class='FONDO2'><td align='center'><strong>Movimiento No.</strong></td><td align='center'><strong>Medicamento</strong></td><td align='center'><strong>Unidad de Medida</strong></td><td align='center'><strong>Cantidad</strong></td><td align='center'><strong>Lote</strong></td>";
	if($Externo==1){
		$datos.="<tr><td colspan='6'><hr></td></tr>";
	}
	
	if($Externo!=1){
	$datos.="<td align='center'><strong>Eliminar</strong></td>";
	}else{
	$datos.="<td align='center'><strong>&nbsp;</strong></td>";
	}
	$datos.="</tr>";
	
	
	if($row2=mysql_fetch_array($resp3)){
		$NoMovimiento=0;
	
	while($row=mysql_fetch_array($resp2)){
			/*OBTENCION DE MEDIDA PARA EL MEDICAMENTO*/
			$querySelect1="select Descripcion,UnidadesContenidas
			from farm_unidadmedidas
			inner join farm_catalogoproductos
			on farm_catalogoproductos.IdUnidadMedida=farm_unidadmedidas.IdUnidadMedida
			where IdMedicina=".$row["IdMedicina"];
			$unidades=mysql_fetch_array(mysql_query($querySelect1));
			/*********************************************/
			if($row2["IdMedicina"]==$row["IdMedicina"]){
				$NoMovimiento++;
			}else{
				$NoMovimiento=1;
			}
			
						$row2=mysql_fetch_array($resp3);
			
		$datos.="<tr class='FONDO'><td align='center'>".$NoMovimiento."</td><td align='center'>".$row["Nombre"]." - ".$row["Concentracion"]."</td><td align='center'>".$unidades["Descripcion"]."</td><td align='center'>".$row["Cantidad"]/$unidades["UnidadesContenidas"]."</td><td align='center'>";
		if($Externo==1){
			$respLotes=queries::LotesDeEntrega($row["IdMedicina"]);
			while($rowLotes=mysql_fetch_array($respLotes)){
				$datos.="Cantidad: ".$rowLotes["Cantidad"]."<br>";
				$datos.="Lote: ".$rowLotes["Lote"]."<br>";
				$datos.="Fecha de Vencimiento: ".$rowLotes["FechaVencimiento"]."<br><br>";		
			}
		}else{
		$datos.="".$row["Lote"]."<br>".$row["FechaVencimiento"]."";
		}
		
		$datos.="</td>";
		
		if($Externo!=1){
		$datos.="<td align='center'>		
		<a onclick=\"javascript:EliminarTransferencia(".$row["IdEntrega"].");\" style=\"cursor:default;\"><img src=\"../../images/papelera.gif\" /></a></td></tr>";
		}//If Externo
		else{
			
			$datos.="<td align='center'><strong>&nbsp;</strong></td>";
		}
		if($Externo==1){
			$datos.="<tr><td colspan='6'><hr></td></tr>";
		}
		
	}//while resp2

}else{

$datos.="<tr class='FONDO'><td colspan='6' align='center'><h2>NO EXISTE(N) TRANSFERENCIA(S) A MOSTRAR</h2></td></tr>";
}
	$datos.="</table>";
	
	return($datos);	
	
}//Desplegar Tranferencias



function EliminarTransferencia($IdEntrega){

/*		AUNMENTO DE EXISTENCIAS EN LOS LOTES EN QUE SERAN ELIMINADAS LAS TRANSFERENCIAS			*/
	$querySelect="select Cantidad, IdLote from alm_entregamedicamento where IdEntrega=".$IdEntrega;
				$resp=mysql_fetch_array(mysql_query($querySelect));
		
	$querySelect="select Existencia from alm_existencias where IdLote=".$resp["IdLote"];
				$resp2=mysql_fetch_array(mysql_query($querySelect));
		
		$Existencia_new=$resp2["Existencia"]+$resp["Cantidad"];
		
	$queryUpdate="update alm_existencias set Existencia='$Existencia_new' where IdLote=".$resp["IdLote"];
				mysql_query($queryUpdate);

/************************************************************************************************/		

	$queryDelete="delete from alm_entregamedicamento where IdEntrega=".$IdEntrega;
	mysql_query($queryDelete);	
}//Elimnar Tranferencia




function FinalizarTransferencias($IdUsuarioReg){
	$querySelect="select IdEntrega, IdMedicina, Cantidad, IdLote
				from alm_entregamedicamento
				where IdUsuarioReg='$IdUsuarioReg'
				and IdEstado=1
				and Fecha=curdate()";
	$resp=mysql_query($querySelect);
	
	while($row=mysql_fetch_array($resp)){
		
		$querySelect2="select IdMedicina, Existencia, IdLote from farm_entregamedicamento where IdLote=".$row["IdLote"];
				$resp2=mysql_query($querySelect2);
				
		if($test=mysql_fetch_array($resp2)){
		$Existencia_new=$test["Existencia"]+$row["Cantidad"];
		
		$queryUpdateExistencia="update farm_entregamedicamento set Existencia='$Existencia_new' where IdLote=".$row["IdLote"];
				mysql_query($queryUpdateExistencia);
		
		}else{
		
			
		/*concretar la transferencia de medicamento a Farmacia*/
		$queryInsert="insert into farm_entregamedicamento (IdMedicina,Existencia,IdLote) values('".$row["IdMedicina"]."','".$row["Cantidad"]."','".$row["IdLote"]."')";
				mysql_query($queryInsert);

		}
		
		
		$queryUpdate="update alm_entregamedicamento set IdEstado=2 where IdEntrega=".$row["IdEntrega"];
		
		mysql_query($queryUpdate);
		
	}
	
	
}//FinalizarTransferencias



/*			FIN DE TRANSFERENCIAS			*/


/*	INTRODUCCION DE EXISTENCIAS A ALMACEN	*/


function CodigoLote($IdLote){
	$querySelect="select Lote from farm_lotes where IdLote='$IdLote'";
	$resp=mysql_fetch_array(mysql_query($querySelect));
	return($resp[0]);
}


function ObtenerUnidadMedida($IdMedicina){
	$querySelect="select farm_unidadmedidas.UnidadesContenidas
				from farm_unidadmedidas
				inner join farm_catalogoproductos
				on farm_catalogoproductos.IdUnidadMedida=farm_unidadmedidas.IdUnidadMedida
				where farm_catalogoproductos.IdMedicina='$IdMedicina'";
	$resp=mysql_fetch_array(mysql_query($querySelect));
	return($resp[0]);
}//ObtenerUnidadMedida



/***************	AUMENTO DE EXISTENCIAS EN ALMACEN	******************/

function ConfirmaExistencia($IdMedicina,$Lote,$IdEstablecimiento,$IdModalidad){
if($Lote!='Lote.'){
$querySelect="select farm_entregamedicamento.Existencia,farm_lotes.IdLote
			from farm_entregamedicamento
			inner join farm_lotes
			on farm_lotes.IdLote=farm_entregamedicamento.IdLote 
			where farm_entregamedicamento.IdMedicina='$IdMedicina' 
			and farm_lotes.Lote='$Lote'
                        and farm_entregamedicamento.IdEstablecimiento=$IdEstablecimiento
                        and farm_entregamedicamento.IdModalidad=$IdModalidad
			and Existencia <> 0 
			and left(curdate(),7) <= left(FechaVencimiento,7)";
}else{
//$querySelect="select * from farm_medicinaexistenciaxarea where IdMedicina='$IdMedicina' and IdArea='$IdArea'";
}
$resp=mysql_query($querySelect);
return($resp);
}//confirma existencia


function AumentaExistencias($IdMedicina,$cantidad,$ventto,$Lote,$Precio,$IdEstablecimiento,$IdModalidad){
/* AQUI $Lote ES UNA CADENA QUE IDENTIFICA EL CODIGO DEL LOTE */
	$respuesta=queries::ConfirmaExistencia($IdMedicina,$Lote,$IdEstablecimiento,$IdModalidad);
	if($row=mysql_fetch_array($respuesta)){
		$Multiplicador=queries::ObtenerUnidadMedida($IdMedicina);
		$cantidad=$cantidad*$Multiplicador;
		queries::ActualizarExistencias($IdMedicina,$cantidad,$Lote,$IdEstablecimiento,$IdModalidad);
	}else{
		$Multiplicador=queries::ObtenerUnidadMedida($IdMedicina);
		$cantidad=$cantidad*$Multiplicador;
		queries::IntroducirExistencias($IdMedicina,$cantidad,$ventto,$Lote,$Precio,$IdEstablecimiento,$IdModalidad);
	}
}//AumentaExistencias


function ActualizarExistencias($IdMedicina,$cantidad,$Lote,$IdEstablecimiento,$IdModalidad){
$resp=queries::ConfirmaExistencia($IdMedicina,$Lote,$IdEstablecimiento,$IdModalidad);
$row=mysql_fetch_array($resp);
$IdLote=$row["IdLote"];
$cantidad_old=$row["Existencia"];
$cantidad_new=$cantidad_old+$cantidad;
if($Lote!='Lote.'){
$queryUpdate="update farm_entregamedicamento set Existencia='$cantidad_new' 
              where IdMedicina='$IdMedicina' and IdLote='$IdLote' 
              and IdEstablecimiento=".$IdEstablecimiento." 
              and IdModalidad=$IdModalidad";

		$IdEntrega=mysql_fetch_array(mysql_query("select IdEntrega 
                                                        from farm_entregamedicamento 
                                                        where IdMedicina='$IdMedicina' and IdLote='$IdLote' 
                                                        and IdEstablecimiento=".$IdEstablecimiento." 
                                                        and IdModalidad=$IdModalidad"));
		$IdEntrega=$IdEntrega[0];

$queryBitacora="insert into farm_bitacoraentregamedicamento (IdMedicina,Existencia,IdEntregaOrigen,IdLote,FechaHoraIngreso,IdEstablecimiento,IdModalidad) 
                values('$IdMedicina','$cantidad','$IdEntrega','$IdLote',now(),$IdEstablecimiento,$IdModalidad)";
}else{
//$queryUpdate="update farm_entregamedicamento set Existencia='$cantidad_new' where IdMedicina='$IdMedicina' and Existencia <> '0'";
}
mysql_query($queryUpdate);
mysql_query($queryBitacora);

}//Actualizacion Existencias

//*****Introduccion de Existencias de almacen
function IntroducirExistencias($IdMedicina,$cantidad,$fecha,$Lote,$Precio,$IdEstablecimiento,$IdModalidad){
	if($Lote!='Lote.' and $fecha!='Fecha Ventto.'){
	$queryInsert="insert into farm_lotes(Lote,PrecioLote,FechaVencimiento,IdEstablecimiento,IdModalidad) 
                        values('$Lote','$Precio','$fecha',$IdEstablecimiento,$IdModalidad)";
	mysql_query($queryInsert);	
	$IdLote=mysql_insert_id();

	$queryInsert="insert into farm_entregamedicamento(IdMedicina,Existencia,IdLote,IdEstablecimiento,IdModalidad) 
                                values('$IdMedicina','$cantidad','$IdLote',$IdEstablecimiento,$IdModalidad)";
		mysql_query($queryInsert);

				$IdEntrega=mysql_insert_id();

	$queryBitacora="insert into farm_bitacoraentregamedicamento (IdMedicina,Existencia,IdEntregaOrigen,IdLote,FechaHoraIngreso,IdEstablecimiento,IdModalidad) 
                        values('$IdMedicina','$cantidad','$IdEntrega','$IdLote',now(),$IdEstablecimiento,$IdModalidad)";
    		mysql_query($queryBitacora);
	}

}//Introducir Existencia





function ObtenerPrecioLote($Lote){
	$querySelect="select farm_lotes.PrecioLote
					from farm_lotes
					where farm_lotes.IdLote='$Lote'";
	$resp=mysql_fetch_array(mysql_query($querySelect));
	return($resp[0]);
}//ObtenerPrecioLote

/**********	FIN CONTROL DE EXISTENCIAS	*********************/



/**********	DATOS MEDICINA	***************/
function ObtenerDatosMedicina($IdMedicina){
$queryMedicina="select IdMedicina, Nombre, Concentracion, Descripcion, UnidadesContenidas,GrupoTerapeutico
				from farm_catalogoproductos 
				inner join mnt_grupoterapeutico
				on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico
				inner join farm_unidadmedidas
				on farm_unidadmedidas.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida
				where farm_catalogoproductos.IdMedicina='$IdMedicina'";

$info=mysql_query($queryMedicina);

return($info);
}//fin DatosMedicina

/*************************************************************************/


/*******************	REPORTES DE ALMACEN		*************************/

function ComboEmpleados(){
conexion::conectar();
	$querySelect="select NombreEmpleado, IdEmpleado
				from mnt_empleados
				where IdTipoEmpleado='ALM'";
	$resp=mysql_query($querySelect);	
	$combo='<select id="IdEmpleado" name="IdEmpleado">
              <option value="0">[Seleccione...]</option>';
	while($row=mysql_fetch_array($resp)){
		$combo.="<option value='".$row["IdEmpleado"]."'>".$row["NombreEmpleado"]."</option>";
	}
	$combo.="</select>";
	return($combo);
conexion::desconectar();
}//Combo Empleados

function ComboGrupoTerapeutico(){
	conexion::conectar();
	$resp=queries::GrupoTerapeutico();
	
	$combo="<select id='IdTerapeutico' name='IdTerapeutico' onchange='javascript:Combo();'>
	<option value='0'>[Seleccione ...]</option>";
	while($row=mysql_fetch_array($resp)){
		$combo.="<option value='".$row["IdTerapeutico"]."~".$row["GrupoTerapeutico"]."'>".$row["GrupoTerapeutico"]."</option>";
	}//while
	$combo.="</select>";
	conexion::desconectar();
	return($combo);
}// Combo Grupo Terapeutico

function ComboMedicina($IdTerapeutico){
	$querySelect="select IdMedicina, Nombre, Concentracion
				from farm_catalogoproductos 
				where IdTerapeutico=".$IdTerapeutico."
				and IdEstado = 'H'
				order by Nombre";
	$resp=mysql_query($querySelect);
	$combo="<select id='IdMedicina' name='IdMedicina'>
			<option value='0'>[Seleccione ...]</option>";
	while($row=mysql_fetch_array($resp)){
		$combo.="<option value='".$row["IdMedicina"]."'>".$row["Nombre"]." - ".$row["Concentracion"]."</option>";
	}//while
	$combo.="</select>";
	return($combo);
}//Combo Medicina

function GrupoTerapeutico(){
	$querySelect="select IdTerapeutico, GrupoTerapeutico
				from mnt_grupoterapeutico
				where GrupoTerapeutico <> '--'
				order by GrupoTerapeutico";
	$resp=mysql_query($querySelect);
	return($resp);
}//Grupo Terapeutico

function NombreGrupoTerapeutico($IdTerapeutico){
	$querySelect="select GrupoTerapeutico
				from mnt_grupoterapeutico
				where IdTerapeutico=".$IdTerapeutico;
	$resp=mysql_fetch_array(mysql_query($querySelect));
	return($resp[0]);
}//NombreGrupoTerapeutico

function LotesPorMedicina($IdMedicina){
	$querySelect="select Existencia, Lote, FechaVencimiento
				from farm_Lotes
				inner join farm_entregamedicamento
				on farm_entregamedicamento.IdLote = farm_lotes.IdLote
				where farm_entregamedicamento.IdMedicina='$IdMedicina'
				and farm_entregamedicamento.Existencia <> '0'
				order by FechaVencimiento";
	$resp=mysql_query($querySelect);
	return($resp);
}//Lotes por Medicina


function ReporteExistenciaGral($Externo){
	$IdMedicina='';
	
	$resp=queries::GrupoTerapeutico();
	$data='<table align="center" border="0" width="100%">';
	if($Externo==1){
	$data.="<tr><td colspan='5' align='center' class='FONDO'><strong>HOSPITAL NACIONAL ROSALES<br>ALMACEN DE MEDICAMENTOS<br>REPORTE GENERAL DE EXISTENCIAS</strong></td></tr>
	<tr><td colspan='5' align='center'>Fecha de Reporte: <strong>".date('d/m/Y')."</strong><br><br></td>";
	}

	while($row=mysql_fetch_array($resp)){
		$resp2=queries::ReporteExistencia($row["IdTerapeutico"],0,1,0);
		if($row2=mysql_fetch_array($resp2)){
		
	$data.='<tr class="FONDO2"><td align="center" colspan="5"><strong>'.$row["GrupoTerapeutico"].'</strong></td></tr>
	<tr><td align="center" class="FONDO"><strong>Medicamento</strong></td><td align="center" class="FONDO"><strong>Concentracion</strong></td><td align="center" class="FONDO"><strong>Unidad de Medida</strong></td><td align="center" class="FONDO"><strong>Existencia</strong></td><td align="center" class="FONDO"><strong>Detalle de Lote</strong></td></tr>';
	if($Externo==1){
		$data.="<tr><td colspan='5'><hr></td></tr>";
	}
		
			$resp2=queries::ReporteExistencia($row["IdTerapeutico"],0,1,0);
			
			while($row2=mysql_fetch_array($resp2)){
						
				$resp3=queries::LotesPorMedicina($row2["IdMedicina"]);
				
				
				$data.='<tr><td class="FONDO" align="center">'.$row2["Nombre"].'</td><td class="FONDO" align="center">'.$row2["Concentracion"].'</td><td class="FONDO" align="center">'.$row2["Descripcion"].'</td><td class="FONDO" align="center">'.$row2["ExistenciaTotal"]/$row2["UnidadesContenidas"].'</td><td class="FONDO" align="center">';
				
				while($row3=mysql_fetch_array($resp3)){
					$data.="Existencia: ".$row3["Existencia"]/$row2["UnidadesContenidas"]."  ".$row2["Descripcion"]."<br>";
					$data.="Lote: ".$row3["Lote"]."<br>";
					$data.="Fecha Vencimiento: ".$row3["FechaVencimiento"]."<br><br>";
				}//while
				
				$data.='</td></tr>';
				
				
			}//fin de while Medicina
		$data.='<tr><td colspan="5" class="FONDO"><hr></td></tr>';

		}//IF datos en ReporteExistencia


	}//fin de while Grupo Terapeutico
	
	return($data);
}//fin ReporteExistenciaGral



function ReporteExistencia($IdTerapeutico,$IdMedicina,$Bandera,$Externo){
	$var1='';$var2='';$var3='';
	if($IdTerapeutico != 0 and $IdMedicina == 0){
		$var1="and farm_catalogoproductos.IdTerapeutico='$IdTerapeutico'";
	}
	
	if($IdTerapeutico != 0 and $IdMedicina != 0){
		$var2="and farm_catalogoproductos.IdTerapeutico='$IdTerapeutico' and farm_catalogoproductos.IdMedicina='$IdMedicina'";
	}
	
	$querySelect="select farm_catalogoproductos.IdMedicina, GrupoTerapeutico, Nombre, Concentracion, sum(Existencia)as ExistenciaTotal, UnidadesContenidas,Descripcion
				from mnt_grupoterapeutico
				inner join farm_catalogoproductos
				on farm_catalogoproductos.IdTerapeutico=mnt_grupoterapeutico.IdTerapeutico
				inner join farm_entregamedicamento
				on farm_entregamedicamento.IdMedicina=farm_catalogoproductos.IdMedicina
				inner join farm_lotes
				on farm_lotes.IdLote=farm_entregamedicamento.IdLote
				inner join farm_unidadmedidas
				on farm_unidadmedidas.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida
				where farm_catalogoproductos.IdEstado = 'H'
				and mnt_grupoterapeutico.GrupoTerapeutico <> '--'
				and mnt_grupoterapeutico.IdTerapeutico=1
				and farm_entregamedicamento.Existencia <> '0'
				$var1
				$var2
				group by farm_catalogoproductos.Nombre";
	$resp=mysql_query($querySelect);
	if($Bandera==1){
		return($resp);
	}else{
		/*	CONTRUCCION DE REPORTE ESPECIFICO	*/
		$data='<table align="center" width="100%" border="0">';
		
			if($Externo==1){
	$data.="<tr><td colspan='5' align='center' class='FONDO'><strong>HOSPITAL NACIONAL ROSALES<br>ALMACEN DE MEDICAMENTOS<br>REPORTE DE EXISTENCIAS</strong></td></tr>
	<tr><td colspan='5' align='center'>Fecha de Reporte: <strong>".date('d/m/Y')."</strong><br><br></td>";
	}

		
		if($row=mysql_fetch_array($resp)){
			
			$row=mysql_fetch_array(mysql_query($querySelect));
			
			$data.='<tr class="FONDO2"><td colspan="5" align="center"><strong>'.$row["GrupoTerapeutico"].'</strong></td></tr>
			<tr><td align="center" class="FONDO"><strong>Medicamento</strong></td><td align="center" class="FONDO"><strong>Concentracion</strong></td><td align="center" class="FONDO"><strong>Unidad de Medida</strong></td><td align="center" class="FONDO"><strong>Existencia</strong></td><td align="center" class="FONDO"><strong>Detalle de Lote</strong></td></tr>';	
			
			if($Externo==1){
				$data.="<tr><td colspan='5'><hr></td></tr>";
			}
			
				$resp=mysql_query($querySelect);
			while($row=mysql_fetch_array($resp)){
				$data.='<tr><td align="center" class="FONDO">'.$row["Nombre"].'</td><td align="center" class="FONDO">'.$row["Concentracion"].'</td><td align="center" class="FONDO">'.$row["Descripcion"].'</td><td align="center" class="FONDO">'.$row["ExistenciaTotal"]/$row["UnidadesContenidas"].'</td><td align="center" class="FONDO">';
				
				$resp3=queries::LotesPorMedicina($row["IdMedicina"]);
				
				while($row3=mysql_fetch_array($resp3)){
					$data.="Existencia: ".$row3["Existencia"]/$row["UnidadesContenidas"]." ".$row["Descripcion"]."<br>";
					$data.="Lote: ".$row3["Lote"]."<br>";
					$data.="Fecha Vencimiento: ".$row3["FechaVencimiento"]."<br><br>";
				}//while
				
				$data.='</td></tr>';
			}//Fin de while
		$data.='<tr><td colspan="5" class="FONDO"><hr></td></tr>';
		}//IF $resp
		else{
			$data.="<tr><td class='FONDO2' align='center'><strong>NO HAY DATOS A MOSTRAR</strong></td></tr>";
		}
		$data.='</table>';
		return($data);
	}
}//Respuesta Reporte


/*	REPORTE DE ENTREGAS*/
function ReporteEntregasGral($IdTerapeutico,$IdMedicina,$IdEmpleado,$FechaInicio,$FechaFin,$Externo){
	$var1='';$var2='';$var3='';$var4='';
	
	if($IdTerapeutico!='0'){
		$var1="and farm_catalogoproductos.IdTerapeutico='$IdTerapeutico'";
	}
	if($IdMedicina!='0'){
		$var2="and alm_entregamedicamento.IdMedicina='$IdMedicina'";
	}
	if($IdEmpleado!='0'){
		$EmpleadoCorrelativo=queries::EmpleadoNumeroCorrelativo($IdEmpleado);
		$var3="and alm_entregamedicamento.IdUsuarioReg='$EmpleadoCorrelativo'";
	}
	
	
	/*		FECHAS DE ENTREGA		*/
	$querySelect="select distinct Fecha, DATE_FORMAT(Fecha,'%d/%m/%Y') as Entrega
				from farm_catalogoproductos
				inner join alm_entregamedicamento
				on farm_catalogoproductos.IdMedicina=alm_entregamedicamento.IdMedicina
				inner join farm_lotes
				on farm_lotes.IdLote=alm_entregamedicamento.IdLote
				
				where alm_entregamedicamento.IdEstado=2
				and Fecha between '$FechaInicio' and '$FechaFin'
				$var1
				$var2
				$var3
				order by Fecha";
	
	$resp=mysql_query($querySelect);
	
	
	
	$data="<table width='100%'>";
	if($row=mysql_fetch_array($resp)){
		$resp=mysql_query($querySelect);
	
	if($Externo==1){
		$Fecha1=explode('-',$FechaInicio);
		$Fecha2=explode('-',$FechaFin);
		
		$Fecha1=$Fecha1[2].'/'.$Fecha1[1].'/'.$Fecha1[0];
		$Fecha2=$Fecha2[2].'/'.$Fecha2[1].'/'.$Fecha2[0];
	
		$data.="<tr><td class='FONDO' align='right' colspan='5'>Fecha de Reporte:&nbsp;&nbsp;&nbsp;<strong>".date('d/m/Y')."</strong></td></tr>
				<tr><td class='FONDO' align='center' colspan='5'><strong>HOSPITAL NACIONAL ROSALES<br>ALMACEN DE MEDICAMENTOS<br>REPORTE DE MEDICAMENTOS ENTREGADOS A FARMACIA</strong</td></tr>
				<tr><td class='FONDO' align='center' colspan='5'>Periodo:&nbsp;&nbsp;&nbsp;<strong>".$Fecha1."&nbsp;&nbsp;&nbsp;a&nbsp;&nbsp;&nbsp; ".$Fecha2." </strong></td></tr>";
	}
	
	if($IdEmpleado != '0'){
		$data.="<tr><td class='FONDO' colspan='5' align='center'>Entrega Registrada por:&nbsp;&nbsp;&nbsp;<strong>".queries::NombreEmpleado($IdEmpleado)."</strong></td></tr>";
	}
	
	if($IdTerapeutico!='0'){
		$data.="<tr><td class='FONDO' colspan='5' align='center'>Grupo Terapeutico:&nbsp;&nbsp;&nbsp;<strong>".queries::NombreGrupoTerapeutico($IdTerapeutico)."</strong></td></tr>";
	}
	
	
	while($rowFechas=mysql_fetch_array($resp)){
		$data.="<tr><td class='FONDO' colspan='5' align='center'><br><strong>FECHA DE ENTREGA:&nbsp;&nbsp;".$rowFechas["Entrega"]."</strong></td></tr>";
		
			/*		DATOS DE MEDICAMENTOS ENTREGADO		*/
	$querySelectEntregas="select alm_entregamedicamento.IdMedicina,Nombre,Concentracion,sum(Cantidad)as Cantidad,Fecha
				from farm_catalogoproductos
				inner join alm_entregamedicamento
				on farm_catalogoproductos.IdMedicina=alm_entregamedicamento.IdMedicina
				inner join farm_lotes
				on farm_lotes.IdLote=alm_entregamedicamento.IdLote
				
				where alm_entregamedicamento.IdEstado=2
				and Fecha = '".$rowFechas["Fecha"]."'
				$var1
				$var2
				$var3
				group by alm_entregamedicamento.IdMedicina,Fecha
				order by Nombre";
		$respMedicina=mysql_query($querySelectEntregas);
			$resptmp=mysql_query($querySelectEntregas);
				$tmp=mysql_fetch_array($resptmp);
				

				
				
		$data.="<tr><td class='FONDO' align='center'><strong>Medicamento</strong></td><td class='FONDO' align='center'><strong>Concentracion</strong></td><td class='FONDO' align='center'><strong>Cantidad Entregada</strong></td><td class='FONDO' align='center'><strong>Lote</strong></td></tr>";
		while($rowMedicina=mysql_fetch_array($respMedicina)){
			$tmp=mysql_fetch_array($resptmp);
			
			/*		OBTENCION DE INFORMACION DE LOTES ENTREGADOS 	*/
						$queryLotes="select Cantidad, Lote, FechaVencimiento
						from alm_entregamedicamento
						inner join farm_lotes
						on farm_lotes.IdLote=alm_entregamedicamento.IdLote
						where IdMedicina=".$rowMedicina["IdMedicina"]."
						and alm_entregamedicamento.Fecha='".$rowFechas["Fecha"]."'";
						
						$respLotes=mysql_query($queryLotes);
						
			/********************************************************/
			
			
			$data.="<tr><td class='FONDO' align='center'>".$rowMedicina["Nombre"]."</td><td class='FONDO' align='center'>".$rowMedicina["Concentracion"]."</td><td class='FONDO' align='center'>".$rowMedicina["Cantidad"]."</td><td class='FONDO' align='center'>";
			/*	DESPLEGAR INFORMACION DE LOTES	 */
			while($rowLotes=mysql_fetch_array($respLotes)){
				$data.="Cantidad Entregada:&nbsp;".$rowLotes["Cantidad"]."<br>";
				$data.="Codigo Lote:&nbsp;".$rowLotes["Lote"]."<br>";
				$data.="Fecha de Vencimiento:&nbsp;".$rowLotes["FechaVencimiento"]."<br><br>";
				
			
			}
			/*************************************/
			
			$data.="</td></tr>";
			if(($rowMedicina["IdMedicina"]!=$tmp["IdMedicina"]) and ($tmp["IdMedicina"]!='' and $tmp["IdMedicina"]!=NULL) and $Externo==1){
				$data.="<tr><td colspan='6'><hr></td></tr>";
			}
			
			
		}//Medicamentos Entregados
		if($Externo==1){
			$data.="<tr><td colspan='5'><hr style='border:double;color:#333333;'></td><tr>";
		}
	}//Fechas de Entrega
	
	}else{
		$data.="<tr><td align='center'>NO HAY REGISTROS</td></tr>";
		
		
	}
	
	
	
	
	$data.="</table>";
	return($data);
}//ReporteEntregasGral


/********************************************************************/

/*********************	ADMINISTRACION DE CUENTAS DE USUARIO  *************************/
function InformacionDeUsuario($pagina,$Limite){
	$Paginacion="LIMIT ".($pagina*$Limite).",$Limite";

	$querySelect="select mnt_empleados.IdEmpleado, NombreEmpleado,
				CASE mnt_usuarios.nivel WHEN '1' THEN 'ADMINISTRADOR'
							WHEN '2' THEN 'USUARIO'
				END as nivel, CASE EstadoCuenta WHEN 'H' THEN 'HABILITADA'
							WHEN 'I' THEN 'DESHABILITADA'
				END as EstadoCuentaName,EstadoCuenta
				from mnt_empleados
				inner join mnt_usuarios
				on mnt_usuarios.IdEmpleado=mnt_empleados.IdEmpleado
				inner join alm_estadocuenta
				on alm_estadocuenta.IdEmpleado=mnt_empleados.IdEmpleado
				
				$Paginacion";
	$resp=mysql_query($querySelect);
	return($resp);
}//Informacion General de Usuario

function NumeroDeUsuario(){
	$querySelect="select count(mnt_empleados.IdEmpleado)as Total
				from mnt_empleados
				inner join mnt_usuarios
				on mnt_usuarios.IdEmpleado=mnt_empleados.IdEmpleado
				inner join alm_estadocuenta
				on alm_estadocuenta.IdEmpleado=mnt_empleados.IdEmpleado";
	$resp=mysql_fetch_array(mysql_query($querySelect));
	return($resp[0]);	
}//NumeroDeUsuario
function CuentasDeUsuario($pagina,$Limite){
	$resp=queries::InformacionDeUsuario($pagina,$Limite);
	$Total=queries::NumeroDeUsuario();
	
	$datos='<table width="100%">
	    <tr>
      <td align="center" class="FONDO"><strong>Tipo de Cuenta</strong></td>
      <td align="center" class="FONDO"><strong>Nombre de Empleado </strong></td>
      <td align="center" class="FONDO"><strong>Estado de Cuenta </strong></td>
      <td align="center" class="FONDO"><strong>Habilitar/Deshabilitar</strong></td>
    </tr>';
	while($row=mysql_fetch_array($resp)){
		$datos.=" <tr><td align='center' class='FONDO'>".$row["nivel"]."</td>
				  <td align='center' class='FONDO'>".$row["NombreEmpleado"]."</td>
				  <td align='center' class='FONDO'>".$row["EstadoCuentaName"]."</td>";
			if($row["EstadoCuenta"]=='H'){
				$datos.="<td align='center' class='FONDO'><input type='button' id='habilitar' name='habilitar' value='Deshabilitar' onclick='Habilitar(\"".$row["IdEmpleado"]."\",\"".$row["EstadoCuenta"]."\");'></td>";
			}else{
				$datos.="<td align='center' class='FONDO'><input type='button' id='habilitar' name='habilitar' value='Habilitar' onclick='Habilitar(\"".$row["IdEmpleado"]."\",\"".$row['EstadoCuenta']."\");'></td><tr>";	
			}
	}
	
	$datos.='~';
		$paginacion='';
	if($pagina==0){
		$paginacion.='<input id="siguiente" type="button" disabled="disabled" value=" << Anterior ">';
	}else{
		$Page=$pagina-1;
		$paginacion.='<input id="siguiente" type="button" onclick="RecargaDatos2('.$Page.');" value=" << Anterior ">';
	}
		
	for($i=0;$i<($Total/$Limite);$i++){
		if($i==$pagina){
			$page=$i+1;
		$paginacion.='&nbsp;&nbsp;&nbsp; '.$page.' &nbsp;&nbsp;&nbsp; ';
		}else{
			$page=$i+1;
		$paginacion.='&nbsp;&nbsp;&nbsp;<a onclick="RecargaDatos2('.$i.');" style="color:#FFFFFF"><u>'.$page.'</u></a> ';
		}
	}
	
		$tope=round(($Total/$Limite),0);
	if(($pagina+1)==$tope or $tope==$pagina){
		$paginacion.='<input id="siguiente" type="button" disabled="disabled" value="Siguiente >> ">';
	}else{
		$Page=$pagina+1;
		$paginacion.='<input id="siguiente" type="button" onclick="RecargaDatos2('.$Page.');" value="Siguiente >> ">';
	}	
	
	$datos.=$paginacion;
	echo $datos;
}// Cuentas de Usuario


function CambiosEstado($IdEmpleado,$EstadoActual){
	if($EstadoActual=='H'){
		$queryUpdate="update alm_estadocuenta set EstadoCuenta='I' where IdEmpleado='$IdEmpleado'";
	}else{
		$queryUpdate="update alm_estadocuenta set EstadoCuenta='H' where IdEmpleado='$IdEmpleado'";
	}
	mysql_query($queryUpdate);
	
}//








/**************************************************************************************/








 }//fin class queries
 
 
class meses{
function NombreMes($mes){
		switch($mes){
			case 'January': $mes="Enero";
			break;
			case 'February': $mes="Febrero";
			break;
			case 'March': $mes="Marzo";
			break;
			case 'April': $mes="Abril";
			break;
			case 'May': $mes="Mayo";
			break;
			case 'June': $mes="Junio";
			break;
			case 'July': $mes="Julio";
			break;
			case 'August': $mes="Agosto";
			break;
			case 'September': $mes="Septiembte";
			break;
			case 'October': $mes="Octubre";
			break;
			case 'November': $mes="Noviembre";
			break;
			case 'December': $mes="Diciembre";
			break;
			default: $mes=" ____________ ";
			break;
		}//switch
return ($mes);
}//NombreMes

} //meses

?>
