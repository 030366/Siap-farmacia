<?php session_start();
if(!isset($_SESSION["nivel"])){?>
<script language="javascript">
window.location='../signIn.php';
</script>
<?php
}else{
$IdArea=$_SESSION["IdArea"];
require('../Clases/class.php');  
$query=new queries;
	conexion::conectar();
$entregada=$_REQUEST["Entregada"];
$p=$_REQUEST["p"];//IdReceta
$IdReceta=$p;
$page=$_REQUEST["page"];//paginacion
/* SI LA RECETA HA SIDO ENTREGADA == 1 */
$Ancla='L';
$resp=$query->datosRecetaListas($p,$Ancla,$IdArea);//obtencion de detalle de la receta
		$row=mysql_fetch_array($resp);
		//$IdReceta=$row["IdReceta"];
	if($entregada==1){
/*	do{
		/*$NombreMedicina=$row["medicina"];
		$Concentracion=$row["CONCENTRACION"];
		$Presentacion=$row["FORMAFARMACEUTICA"].", ".$row["PRESENTACION"];
		$diagnostico=$row["Diagnostico"];
		$dosis=$row["Dosis"];  arterico/
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
		$respLotes=$query->ObtenerLotes($idmedicina,$IdReceta,$IdArea,1,0,0,'','');
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
	*/
	
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
conexion::desconectar();
?>
<!-- AL FINAL DE LA ACTUALIZACION DE RECETAS Y EXITENCIAS REDIRECCION A BUSQUEDA -->
<script language="javascript">
window.location='buscador_recetas.php?page=<?php echo"$page";?>';
</script>
<?php }//Fin de Nivel?>