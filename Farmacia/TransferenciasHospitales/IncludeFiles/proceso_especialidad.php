<?php session_start();
require('../../Clases/class.php');
conexion::conectar();

switch($_GET["Bandera"]){
   case 1:
	//Carga de comobo Destino
	$valor = $_GET["ValorOrigen"];
	if($valor!=0){
	$SQL="select * from mnt_areafarmacia where IdArea <> 7 and IdArea <> 12 and Habilitado ='S' and IdArea <> ".$valor;
	$resp=mysql_query($SQL);
	$comboDestino="<select id='IdAreaDestino'>
		<option value='0'>[Seleccion Area Destino]</option>";
	while($row=mysql_fetch_array($resp)){
	   $comboDestino.="<option value='".$row["IdArea"]."'>".$row["Area"]."</option>";
	}
	$comboDestino.="</select>";
	

	}else{
		$comboDestino="<select id='IdAreaDestino'>
		<option>[Seleccion Area Destino]</option>";
		$comboDestino.="</select>";
	}
	echo $comboDestino;
   break;


}
conexion::desconectar();
?>