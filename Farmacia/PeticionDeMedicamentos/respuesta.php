<?php session_start();
include('../Clases/class.php');
conexion::conectar();

$Bandera=$_GET["Bandera"];

if(isset($_GET["Externo"])){
	$Externo=1;?>
	<script language="javascript">
		window.print();
	</script>
<?php }else{
	$Externo=0;
}

switch($Bandera){

	case 1:
		$Busqueda=$_GET['q'];
		$querySelect="select IdMedicina, Descripcion,Nombre,Concentracion,UnidadesContenidas, farm_unidadmedidas.IdUnidadMedida
		from farm_catalogoproductos
		inner join farm_unidadmedidas
		on farm_unidadmedidas.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida
		where Nombre like '%$Busqueda%'
		and farm_catalogoproductos.IdEstado='H'";
			$resp=mysql_query($querySelect);
		while($row=mysql_fetch_array($resp)){
			$Nombre=$row["Nombre"];
			$Concentracion=$row["Concentracion"];
			$IdMedicina=$row["IdMedicina"];
			$Medida=$row["Descripcion"];
			$UnidadesContenidas=$row["UnidadesContenidas"];
			$IdUnidadMedida=$row["IdUnidadMedida"];
			$Nombre=$Nombre." - ".$Concentracion;
		?>
<li onselect="this.text.value = '<?php echo strtoupper(htmlentities($Nombre));?>'; $('IdMedicina').value = '<?php echo $IdMedicina;?>'; $('Medida').innerHTML='<?php echo "Unidad de Medida: ".$Medida;?>'; $('UnidadesContenidas').value=<?php echo $UnidadesContenidas;?>; InformacionAlmacen(<?php echo $IdMedicina;?>);">
 
			<span><?php echo $IdMedicina;?></span>
			<strong><?php echo strtoupper(htmlentities($Nombre));?></strong>
</li>
		<?php
		}

	break;
	
	case 2:
		/*** SI IdLote HA SIDO ENVIDADO***/
		$IdLote=$_GET["IdLote"];
		$querySelect="select Existencia
					from alm_existencias
					inner join farm_lotes
					on farm_lotes.IdLote=alm_existencias.IdLote
					where farm_lotes.IdLote=".$IdLote;
		$resp=mysql_fetch_array(mysql_query($querySelect));
		echo $resp[0];
	break;
	
	case 3:
		$IdMedicina=$_GET["IdMedicina"];
		$Cantidad=$_GET["Cantidad"];
		$Multiplicador=$_GET["Multiplicador"];
		$IdPersonal=$_SESSION["IdPersonal"];
		
		/*	INTRODUCCION DE PETICIONES DE MEDICAMENTOS A ALMACEN	*/
		$IdPedido=queries::IntroducirPeticionMedicamento($IdMedicina,$Cantidad,$IdPersonal);
		
		/*	DESPLEGAR MEDICINA */
		echo queries::DesplegarPeticion($IdPersonal,0)."^".$IdPedido;
		
		
	break;

	case 4:
		$IdPedido=$_GET["IdPedido"];
		$IdMedicina=$_GET["IdMedicina"];
		$Cantidad=$_GET["Cantidad"];
		$Multiplicador=$_GET["Multiplicador"];
		$IdPersonal=$_SESSION["IdPersonal"];
		
		/*	INTRODUCCION DE PETICIONES DE MEDICAMENTOS A ALMACEN	*/
		$IdPedido=queries::IntroducirPeticionMedicamentos($IdPedido,$IdMedicina,$Cantidad);
		
		/*	DESPLEGAR MEDICINA */
		echo queries::DesplegarPeticion($IdPersonal,0)."^".$IdPedido;
		
	break;
	
	case 5:
		queries::FinalizarPedido($_GET["IdPedido"]);
		
	break;
	case 6:
		/*		 IMPRESION DE LISTADO		*/
		echo queries::DesplegarPeticion($_SESSION["IdPersonal"],$Externo);
	
	break;
	case 7:
		/*		OBTENCION DE EXISTENCIAS EN ALMACEN		*/
		$IdMedicina=$_GET["IdMedicina"];
		echo queries::ExistenciaAlmacen($IdMedicina);
		
	break;
	case 8:
		/*	ELIMINAR TRANSFERENCIAS		*/
		$IdDetallePedido=$_GET["IdDetallePedido"];
		queries::EliminarDetalle($IdDetallePedido);
		echo queries::DesplegarPeticion($_SESSION["IdPersonal"],0);
		
	break;
	case 9:
		/*	CANCELAR TODO	*/
		$IdPedido=$_GET["IdPedido"];
		queries::EliminarPedido($IdPedido);		
		
	break;
}//switch
conexion::desconectar();
?>