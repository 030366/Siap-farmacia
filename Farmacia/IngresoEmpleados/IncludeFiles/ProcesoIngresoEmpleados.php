<?php session_start();
include('ClaseIngresoEmpleados.php');
conexion::conectar();
$ingreso=new IngresoEmpleados;
$Bandera=$_GET["Bandera"];
switch($Bandera){
	case 1:
		/*GENERACION DE IDEMPLEADO*/
		$IdTipoEmpleado=$_GET["IdTipoEmpleado"];
		$IdEmpleado=$ingreso->ObtenerIdEmpleado($IdTipoEmpleado,$_SESSION["IdEstablecimiento"]);
		echo $IdEmpleado;		
		
	break;
	case 2:
		/*Informacion*/
		$IdTipoEmpleado=$_GET["IdTipoEmpleado"];
			
		$CodigoFarmacia=strtoupper($_GET["CodigoFarmacia"]);
		$NombreEmpleado=strtoupper($_GET["NombreEmpleado"]);
			$Existe=$ingreso->VerificaCodigoFarmacia($CodigoFarmacia);
		if($Existe==true){
			$respuesta="NO~";
		}else{
			$IdEmpleado=$ingreso->ObtenerIdEmpleado($IdTipoEmpleado,$_SESSION["IdEstablecimiento"]);
			$DatosIdEmpleado=explode('/',$IdEmpleado);
			$ingreso->GuardarEmpleado($DatosIdEmpleado[0],$_SESSION["IdEstablecimiento"],$IdTipoEmpleado,$NombreEmpleado,$DatosIdEmpleado[1],$CodigoFarmacia, $_SESSION["IdPersonal"]);
				
				$respDatos1=$ingreso->ObtenerDatos($DatosIdEmpleado[0],$_SESSION["IdEstablecimiento"]);
				$respDatos=mysql_fetch_array($respDatos1);
				
				$tbl="<table>
					<tr><td colspan='2' align='center'>DATOS INGRESADOS</td></tr>
					<tr><td>Codigo Farmacia [JVPM]:</td><td><strong>".$respDatos["CodigoFarmacia"]."</strong></td></tr>
					<tr><td align='right'>Nombre:</td><td><strong>".$respDatos["NombreEmpleado"]."</strong></td></tr>
					<tr><td>";
					
					
				$tbl.="</table>";
			
				$respuesta='SI~'.$tbl;

			
		}
		
		
		echo $respuesta;
		/**************/
	break;
	
	case 3:
	$NombreEmpleado=$_GET["NombreEmpleado"];
	   $resp=$ingreso->Empleados($NombreEmpleado,$_SESSION["IdEstablecimiento"]);
	   $tbl="<table width='65%'>
		<tr><td align='center'><strong>Codigo Farmacia [JVMP]</strong></td><td align='center'><strong>Medico</strong></td>";
		
		while($row=mysql_fetch_array($resp)){
			$Codigo="NO ASIGNADO";
			if($row["CodigoFarmacia"]!='' and $row["CodigoFarmacia"]!=NULL){$Codigo=$row["CodigoFarmacia"];}
		   $tbl.="<tr><td><strong>".$Codigo."</strong></td><td>".htmlentities($row["NombreEmpleado"])."</td>";
		}
		
		
	   $tbl.="</table>";
	
	  echo $tbl;
	break;
	
	default:
		
	break;
	
	
	
}//switch
conexion::desconectar();
?>