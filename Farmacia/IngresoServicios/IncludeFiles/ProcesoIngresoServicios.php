<?php session_start();
include('ClaseIngresoServicios.php');
conexion::conectar();
$ingreso=new IngresoEmpleados;
$Bandera=$_GET["Bandera"];
switch($Bandera){
	case 1:
		/*GENERACION DE IDEMPLEADO*/
		$CodigoServicio=$_GET["CodigoServicio"];
		$NombreServicio=$_GET["NombreServicio"];
		$IdServicio=$_GET["IdServicio"];
		$Existe=$ingreso->VerificarServicio($CodigoServicio);
		if($Existe==true){
			echo "NO";
		}else{
		
		$ingreso->IngresarServicio($CodigoServicio,$NombreServicio,$_SESSION["IdPersonal"],$_SESSION["IdEstablecimiento"],$IdServicio);
			echo "Servicio Ingresado Satisfactoriamente !";
		}
		
	break;
	
	case 2:
		//ultimo codigo ingresado
		$SQL="select max(CodigoFarmacia) from mnt_subservicio";
		$resp=mysql_fetch_array(mysql_query($SQL));
		echo $resp[0];
	break;
	
	case 3:
		//combo
		$SQL="select IdServicio,NombreServicio from mnt_servicio";
		$resp=mysql_query($SQL);
		$combo="<select id='IdServicio'>
			<option value='0'>[Seleccione...]</option>";
		while($row=mysql_fetch_array($resp)){
		   $combo.="<option value='".$row["IdServicio"]."'>".$row["NombreServicio"]."</option>";
		}
		$combo.="</select>";

		echo $combo;
	break;
	default:
		
	break;
	
	
	
}//switch
conexion::desconectar();
?>