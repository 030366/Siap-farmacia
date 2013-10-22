<?php 
$Bandera=$_GET["Bandera"];
require('ExistenciaVirtualClase.php');
conexion::conectar();
$proceso=new ExistenciaVirtualProceso;

switch($Bandera){

case 1:
/* INICIALIZACION DE LA TABLA DE EXISTENCIAS VIRTUALES */
	$IdArea=$_GET["IdArea"];
	$Totales=array();
	$i=0;

$respTotales=$proceso->ObtenerExistenciaVirtual($IdArea);//Comprobar si ya hay datos en existenciavirtual
if($r=mysql_fetch_array($respTotales)){	
$respTotales=$proceso->ObtenerExistenciaVirtual($IdArea);
}else{
$respTotales=$proceso->ObtenerExistenciaTotal($IdArea);//PrimeraVez
}

$tbl='<table border="1">
<tr><td colspan="5" align="center">PROCESO DE OBTENCION DE EXISTENCIAS</td></tr>
<tr><td>IdMedicina</td><td>Total Existencia</td><td>Total Repetitiva</td><td>Total a Mostrar</td><td>Query</td></tr>';

while($row=mysql_fetch_array($respTotales)){
$IdMedicina=$row["IdMedicina"];
$SumaTotal=$row["Existencia"];
$Repetitiva=$proceso->ObtenerRepetitivas($IdArea,$IdMedicina);//MedicamentoComprometido por recetas repetitivas
if($Repetitiva==NULL){$Repetitiva=0;}
$TotalMostrar=$SumaTotal-$Repetitiva;
if($TotalMostrar<0){$TotalMostrar=0;}

$tbl.='<tr><td>'.$IdMedicina.'</td><td>'.$SumaTotal.'</td><td>'.$Repetitiva.'</td><td>'.$TotalMostrar;
$respuesta=$proceso->ExistenDatos($IdArea,$IdMedicina);
if($respuesta==NULL){
$query="insert into farm_existenciavirtual (IdMedicina,IdArea,Existencia) values('$IdMedicina','$IdArea','$TotalMostrar')";
$tbl.="</td><td>".$query."</td></tr>";
}else{
$query="update farm_existenciavirtual set Existencia='$TotalMostrar' where IdMedicina='$IdMedicina' and IdArea='$IdArea'";
$tbl.="</td><td>".$query."</td></tr>";
}
mysql_query($query);

}//while 

$tbl.='</table>';

echo $tbl;
break;

case 2:
/*AUMENTAR EXISTENCIAS CON LAS MEDICINAS NO RECLAMADAS*/
$IdArea=$_GET["IdArea"];
$i=0;
$query2=array();
$tbl='<table border="1">
<tr><td>ExistenciaVirtual</td><td>Medicmaneto no reclamado</td><td>Nuevo Total</td><td>Query</td></tr>';

/*OBTENER EXISTENCIA VIRTUAL ACTUAL*/
$respuesta=$proceso->ObtenerExistenciaVirtual($IdArea);

while($row=mysql_fetch_array($respuesta)){
	$ExistenciaVirtual=$row["Existencia"];
	$IdMedicina=$row["IdMedicina"];
 $MedicinaNoReclamada=$proceso->ObtencionMedicamentoNoReclamado($IdArea,$IdMedicina);
 if($MedicinaNoReclamada[0]!=NULL){
 $NuevaExistencia=$ExistenciaVirtual+$MedicinaNoReclamada[0];
 
 $query="update farm_existenciavirtual set Existencia='$NuevaExistencia' where IdMedicina='$IdMedicina' and IdArea='$IdArea'";
 
 $query2[$i]="update farm_recetas set IdEstado='Z' where IdReceta='".$MedicinaNoReclamada[2]."'";
 mysql_query($query);//aumenta la existencia con las no recalamadas
 $i++;
 }else{
 $NuevaExistencia=$ExistenciaVirtual+0;
 $query="";
 }
 
	$tbl.='<tr><td>'.$ExistenciaVirtual.'</td><td>'.$MedicinaNoReclamada[0].'</td><td>'.$NuevaExistencia.'</td><td>'.$query.'</td></tr>';
	
}//fin de while

$tbl.='</table>';

$cambios=count($query2);
for($i=0;$i<$cambios;$i++){
	echo $query2[$i];
	mysql_query($query2[$i]);//actualiza la bandera de la no entregada con Z para no ser tomada en cuenta a la proxima vez
}

echo $tbl;
break;

case 3:
/*LIBRE*/

break;

case 4:
/*LIBRE*/

break;

case 5:
/* LIBRE */

break;

case 6:
/*LIBRE*/

break;

default:
/*LIBRE*/

break;

}//Fin de switch
conexion::desconectar();

?>