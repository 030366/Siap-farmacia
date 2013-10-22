<?php session_start();
include('CierreClase.php');
$Bandera=$_GET["Bandera"];
$proceso=new Proceso;
conexion::conectar();

//var general
$IdModalidad=$_SESSION["IdModalidad"];


switch($Bandera){
	case 1:
		$resp=$proceso->CargarCierres($_SESSION["IdEstablecimiento"],$IdModalidad);
		$tabla="<table>
			<tr><td colspan='2' align='center'><h3>Cierres Mensuales Activos</h3></td></tr>
			<tr><td align='center'><strong>Mes Correspondiente</strong></td><td align='center'><strong>Desactivar Cierre</strong></td></tr>";
		
		if($row=mysql_fetch_array($resp)){
		    do{
		    
		    $tabla.="<tr><td>".$row["MesCierre"]."</td><td align='right'><input type='checkbox' id='".$row["IdCierre"]."' name='Cierres' value='".$row["IdCierre"]."'></td></tr>";
		    }while($row=mysql_fetch_array($resp));
			
		    
		}else{
		    $tabla.="<tr><td colspan='2'>NO HAY PERIODOS DE CIERRE MENSUALES <br> SI SE DESEA ELIMINAR UN CIERRE ANUAL, CONTACTAR AL ADMINISTRADOR</td></tr>";
		    
		}
		
		
		echo $tabla;
		
	break;
	case 2:
		//Periodo de Cierre
		$IDs=explode(',',$_GET["IDs"]);//vector que contiene los IdCierre a Eliminar
		$Tope=sizeof($IDs);
		$IdCierre='';
		
		for($i=0;$i<$Tope;$i++){
			$Ok=$proceso->EliminarCierre($IDs[$i],$_SESSION["IdEstablecimiento"],$IdModalidad);
		}
		
		if($Ok==true){
		  echo "SI";
		}else{
		  echo "NO";
		}
		
	break;	
	
}//switch
conexion::desconectar();
?>