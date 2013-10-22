<?php
require('../Clases/class.php');
$IdReceta=$_GET["IdReceta"];
$IdMedicina=$_GET["IdMedicina"];
$Bandera=$_GET["Bandera"];
$IdArea=$_SESSION["IdArea"];
if($IdArea==1){$IdArea=2;}
conexion::conectar();
if($Bandera=='SI'){$IdEstado='';}else{$IdEstado='I';}

if($IdEstado==''){
$queryUpdate="update farm_medicinarecetada set IdEstado='', CantidadLote1='0',Lote1='0' where IdReceta='$IdReceta' and IdMedicina='$IdMedicina'";
mysql_query($queryUpdate);
/*
$querySelect="select farm_medicinaexistenciaxarea.Existencia,farm_lotes.IdLote as Lote, farm_lotes.FechaVencimiento,
			farm_lotes.Lote as CodigoLote
			from farm_medicinaexistenciaxarea
			inner join farm_lotes
			on farm_lotes.IdLote=farm_medicinaexistenciaxarea.IdLote
			where farm_medicinaexistenciaxarea.IdMedicina='$IdMedicina'
			and farm_medicinaexistenciaxarea.Existencia <> '0'
			and farm_medicinaexistenciaxarea.IdArea='$IdArea'
			order by farm_lotes.FechaVencimiento asc
			LIMIT 1";
$resp=mysql_fetch_array(mysql_query($querySelect));
if($resp["Lote"]!=NULL){$Lote=$resp["Lote"];$LoteAviso=$resp["CodigoLote"];}else{$Lote=0;$LoteAviso='';}

if($resp["Existencia"]!=NULL){$Existencia=$resp["Existencia"];}else{$Existencia=0;}

$querySelectCantidad="select farm_medicinarecetada.Cantidad
					from farm_medicinarecetada
					where farm_medicinarecetada.IdReceta='$IdReceta' and farm_medicinarecetada.IdMedicina='$IdMedicina'";
$resp2=mysql_fetch_array(mysql_query($querySelectCantidad));
$Cantidad=$resp2["Cantidad"];
// VERIFICACION EN DADO CASO LA EXISTENCIA DE ESE LOTE SEA CERO 
$Resta=$Existencia-$Cantidad;//Verificacion si hay o no existencias para la dosiss


if($Resta >= 0){
// En dado caso el lote supla correctamente la cantidad 
if($IdEstado=='I'){$IdEstado='S';echo "NO ".$LoteAviso." ".$Existencia;}
$queryUpdate="update farm_medicinarecetada set IdEstado='$IdEstado', CantidadLote1='$Cantidad', Lote1='$Lote' where IdReceta='$IdReceta' and IdMedicina='$IdMedicina'";
mysql_query($queryUpdate);

}elseif($Resta < 0){
// En dado caso el lote suple un parte del tratamiento
$querySelect="select farm_medicinaexistenciaxarea.Existencia,farm_lotes.IdLote as Lote, farm_lotes.FechaVencimiento,
			farm_lotes.Lote as CodigoLote
			from farm_medicinaexistenciaxarea
			inner join farm_lotes
			on farm_lotes.IdLote=farm_medicinaexistenciaxarea.IdLote
			where farm_medicinaexistenciaxarea.IdMedicina='$IdMedicina'
			and farm_medicinaexistencia.Existencia <> '0'
			and farm_medicinaexistenciaxarea.IdArea='$IdArea'
			order by farm_lotes.FechaVencimiento desc
			LIMIT 1";
$resp=mysql_fetch_array(mysql_query($querySelect));
if($resp["Lote"]!=NULL){$Lote2=$resp["Lote"];$LoteAviso=$resp["CodigoLote"];}else{$Lote2=0;} // segundo lote en farmacia

			if($Lote2 != $Lote){
		$Existencia2=$resp["Existencia"];
			if($IdEstado=='I'){echo "NO ".$LoteAviso." ".$Existencia2;$IdEstado='';}
                $Cantidad_=$Cantidad+$Resta;
				$Faltante=$Resta * -1;
				$queryUpdate="update farm_medicinarecetada set IdEstado='$IdEstado', CantidadLote1='$Cantidad_',Lote1='$Lote' where IdReceta='$IdReceta' and IdMedicina='$IdMedicina'";
			
				$queryUpdate2="update farm_medicinarecetada set IdEstado='$IdEstado', CantidadLote2='$Faltante',Lote2='$Lote2' where IdReceta='$IdReceta' and IdMedicina='$IdMedicina'";
			mysql_query($queryUpdate);
			mysql_query($queryUpdate2);
			
			}else{//Diferencia de lotes
			// Si no existe otro lote en dado caso farmacia no fue abastecida 
			if($IdEstado==''){echo "SI ".$Existencia." ".$Lote;}
				$queryUpdate="update farm_medicinarecetada set IdEstado='I', CantidadLote1='$Cantidad',Lote1='$Lote' where IdReceta='$IdReceta' and IdMedicina='$IdMedicina'";
			mysql_query($queryUpdate);
			}//else

}//ElseIf

*/
}else{
/*ESTE CODIGO ES TEMPORAL ES SOLO PARA PONER BANDERA = I EN MEDICAMENTO SIN EXISTENCIA*/

$queryUpdate="update farm_medicinarecetada set IdEstado='I', CantidadLote1='0',Lote1='0' where IdReceta='$IdReceta' and IdMedicina='$IdMedicina'";
mysql_query($queryUpdate);


}//ELSE


conexion::desconectar();
?>