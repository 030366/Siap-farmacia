<?php session_start();
if(!isset($_SESSION["IdPersonal"])){
  echo "ERROR_SESSION";
}else{
include("../../Clases/class.php");
conexion::conectar();



$SQL="select farm_catalogoproductos.IdMedicina,farm_catalogoproductos.Nombre,farm_catalogoproductos.FormaFarmaceutica,
					farm_catalogoproductos.Concentracion, mnt_areamedicina.IdArea
					from farm_catalogoproductos
					inner join mnt_areamedicina
					on mnt_areamedicina.IdMedicina=farm_catalogoproductos.IdMedicina
					where mnt_areamedicina.IdArea='".$_GET["area"]."'
					and farm_catalogoproductos.IdEstado='H'
					and IdTerapeutico=".$_GET["IdTerapeutico"];

$resp=mysql_query($SQL);

$salida='';
$row=mysql_fetch_array($resp);
$ultimo=mysql_num_rows($resp);
$poss=0;
do{
$poss++;

	if($poss!=$ultimo){$cola='~';}else{$cola='';}
	$salida.=$row["IdMedicina"]."".$cola;

}while($row=mysql_fetch_array($resp));


echo $salida;





conexion::desconectar();
}
?>