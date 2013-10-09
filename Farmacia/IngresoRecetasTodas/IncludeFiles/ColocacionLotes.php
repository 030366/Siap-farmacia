<?php  session_start();
require('../../Clases/class.php');
require('DiasClase.php');
$query=new queries;
$query2=new Repetitivas;
conexion::conectar();
/**VALORES POR POST**/
$Lista=1;
$IdReceta=$_GET["IdReceta"];
$IdArea=$_GET["IdArea"];
$Fecha=$_GET["Fecha"];
$IdPersonal=$_SESSION["IdPersonal"];

	//mysql_query("update farm_recetas set IdEstado='TT' where IdReceta='$IdReceta' and IdPersonalIntro='$IdPersonal'");
/** VALORES PARA AJAX**/

//**********************************************************************
if($Lista==1){
/*Si la receta estaba en proceso Bandera=P en la tabla se guarda la informacion
  y la bandera de la receta pasa a Lista (L)  */
		$resp=$query2->MedicinaReceta($IdReceta);

		while($row=mysql_fetch_array($resp)){
		$IdMedicina=$row["IdMedicina"];
		$IdReceta=$row["IdReceta"];
		$satisfecha=$row["IdEstado"];

			if($satisfecha != "I"){
				//Satisfechas
				if($IdArea==1){$IdArea=2;}
				$querySelect="select farm_medicinaexistenciaxarea.Existencia,farm_lotes.IdLote as Lote,
							farm_lotes.FechaVencimiento
							from farm_medicinaexistenciaxarea
							inner join farm_lotes
							on farm_lotes.IdLote=farm_medicinaexistenciaxarea.IdLote
							where farm_medicinaexistenciaxarea.IdMedicina='$IdMedicina'
							and farm_medicinaexistenciaxarea.Existencia <> '0'
							and farm_medicinaexistenciaxarea.IdArea='$IdArea'
							order by farm_lotes.FechaVencimiento asc
							LIMIT 1";
				$resp1=mysql_fetch_array(mysql_query($querySelect));
				$Lote=$resp1["Lote"];				//Lote
				$Existencia=$resp1["Existencia"]; 	// Existencia en Lote

				$querySelectCantidad="select farm_medicinarecetada.Cantidad
									from farm_medicinarecetada
									where farm_medicinarecetada.IdReceta='$IdReceta' and farm_medicinarecetada.IdMedicina='$IdMedicina'";
				$resp2=mysql_fetch_array(mysql_query($querySelectCantidad));
				$Cantidad=$resp2["Cantidad"];//Cantidad medicada

				$Resta=$Existencia-$Cantidad;//Verificacion si hay o no existencias para la dosiss

				/* UPDATES */
if($Resta >= 0){
/* En dado caso el lote supla correctamente la cantidad */
$queryUpdate="update farm_medicinarecetada set CantidadLote1='$Cantidad',Lote1='$Lote' where IdReceta='$IdReceta' and IdMedicina='$IdMedicina'";
$queryUpdate2="update farm_medicinarecetada set CantidadLote2='0',Lote2='0' where IdReceta='$IdReceta' and IdMedicina='$IdMedicina'";
mysql_query($queryUpdate);mysql_query($queryUpdate2);
}elseif($Resta < 0){
/* En dado caso el lote suple un parte del tratamiento */
$querySelect="select farm_medicinaexistenciaxarea.Existencia,farm_lotes.IdLote as Lote, farm_lotes.FechaVencimiento
			from farm_medicinaexistenciaxarea
			inner join farm_lotes
			on farm_lotes.IdLote=farm_medicinaexistenciaxarea.IdLote
			where farm_medicinaexistenciaxarea.IdMedicina='$IdMedicina'
			and farm_medicinaexistenciaxarea.Existencia <> '0'
			and farm_medicinaexistenciaxarea.IdArea='$IdArea'
			order by farm_lotes.FechaVencimiento desc
			LIMIT 1";
if($resp3=mysql_fetch_array(mysql_query($querySelect))){
$Lote2=$resp3["Lote"]; // segundo lote en farmacia

			if($Lote2 != $Lote){
                $Cantidad_=$Cantidad+$Resta;
				$Faltante=$Resta * -1;
				$queryUpdate="update farm_medicinarecetada set CantidadLote1='$Cantidad_',Lote1='$Lote' where IdReceta='$IdReceta' and IdMedicina='$IdMedicina'";
			
				$queryUpdate2="update farm_medicinarecetada set CantidadLote2='$Faltante',Lote2='$Lote2' where IdReceta='$IdReceta' and IdMedicina='$IdMedicina'";
			mysql_query($queryUpdate);
			mysql_query($queryUpdate2);
			}else{//Diferencia de lotes
			/* Si no existe otro lote en dado casa farmacia no fue abastecida */
				$queryUpdate="update farm_medicinarecetada set CantidadLote1='$Cantidad',Lote1='$Lote' where IdReceta='$IdReceta' and IdMedicina='$IdMedicina'";
			mysql_query($queryUpdate);
			}//else

}else{
/*SI NO EXITE LOTE ALGUNO*/
$queryUpdate="update farm_medicinarecetada set CantidadLote1='$Cantidad',Lote1='0' where IdReceta='$IdReceta' and IdMedicina='$IdMedicina'";
mysql_query($queryUpdate);
}


}//ElseIf
		
				/* Updates */
				queries::InsertarDatosReceta($IdMedicina,$IdReceta,$IdHistorialClinico);//Poner Fechas
				queries::ActualizaFechaEntregaMedicina($IdReceta,$Fecha);

			}//IF Satisfecho
			else{
				//Insatisfechas IF Bandera==NO
				queries::InsertarDatosReceta2($IdMedicina,$IdReceta,$IdHistorialClinico);
				queries::ActualizaFechaEntregaMedicina($IdReceta,$Fecha);
			}
		}//fin de while





conexion::desconectar();

	}//IF Listo

	else{ ?>

	<?php	
	}		
?>