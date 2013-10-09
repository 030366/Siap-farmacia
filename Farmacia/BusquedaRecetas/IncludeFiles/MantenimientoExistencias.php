<?php session_start();
if(!isset($_SESSION["nivel"])){?>
<script language="javascript">
window.location='../signIn.php';
</script>
<?php
}else{
require('../../../Clases/class.php');  
$query=new queries;
	conexion::conectar();
$entregada=1;
$IdArea=$_GET["IdArea"];
$IdReceta=$_REQUEST["IdReceta"];//IdReceta
$Fecha=$_GET["Fecha"];

/* SI LA RECETA HA SIDO ENTREGADA == 1 */
$Ancla='TT';
$resp=$query->datosRecetaListasTotal($IdReceta,$Ancla,$IdArea);//obtencion de detalle de la receta
		$row=mysql_fetch_array($resp);
		//$IdReceta=$row["IdReceta"];
	if($entregada==1){
	do{
		/*$NombreMedicina=$row["medicina"];
		$Concentracion=$row["CONCENTRACION"];
		$Presentacion=$row["FORMAFARMACEUTICA"].", ".$row["PRESENTACION"];
		$diagnostico=$row["Diagnostico"];
		$dosis=$row["Dosis"];*/
		$IdArea=$row["IdArea"];
		$idmedicina=$row["IdMedicina"];


			//verificamos si es satisfecha o no		
			$respuesta=$query->verificaSatisfecha($idmedicina,$IdReceta);
			//*************
			if($datos=mysql_fetch_array($respuesta)){
				$Entregada="SI";
			}else{
				$Entregada="NO";
			}//if

//Aqui tengo q ver que lotes mando para hacer la descarga de existencia en ese o esos lotes
		$respLotes=$query->ObtenerLotes($idmedicina,$IdReceta,$IdArea,8,0,0,'','');
		$rowLote=mysql_fetch_array($respLotes);
			$cantidad1=$rowLote["CantidadLote1"];
			$Lote1=$rowLote["Lote1"];
			$cantidad2=$rowLote["CantidadLote2"];
			$Lote2=$rowLote["Lote2"];
		if($Lote1!=NULL){
		$query->MedicinaExistencias($idmedicina,$cantidad1,$Entregada,$IdArea,$Lote1);
		}
		if($Lote2!=NULL){
		$query->MedicinaExistencias($idmedicina,$cantidad2,$Entregada,$IdArea,$Lote2);
		}       
	//Lotes *************************************	
	}while($row=mysql_fetch_array($resp));//fin de while
$Bandera=3;//E
$query->ActualizarEstadoRecetas($IdReceta, $Bandera,$IdArea);//Estado de Receta a Entregada (E)	

}//if entregada == 1

elseif($entregada==2){
//No Entregada     CODIGO NO UTILIZADO
$Bandera=4;//N-> NO ENTREGADA
$query->ActualizarEstadoRecetas($IdReceta, $Bandera, $IdArea);

}elseif($entregada==3){
$Bandera=6;
$query->ActualizarEstadoRecetas($IdReceta, $Bandera, $IdArea);//Estado de Receta a Entregada (E)	
}elseif($entregada==9){
$Bandera=9;
$query->ActualizarEstadoRecetas($IdReceta, $Bandera, $IdArea);//Estado de Receta a Entregada (E)	
}

$query->ActualizaFechaEntregaMedicina($IdReceta,$Fecha);


conexion::desconectar();
 }//Fin de Nivel?>