<?php
include('../../Clases/class.php');
include('ClaseConsultaRecetas.php');
conexion::conectar();
$IdReceta = $_GET["IdReceta"];
$FechaProgramada = $_GET["Fecha"];
$selectNombreFecha="select dayname('$FechaProgramada') as NombreFecha";
$resp=mysql_query($selectNombreFecha);
$rowtmp=mysql_fetch_array($resp);
$NombreDia=$rowtmp["NombreFecha"];

if($NombreDia=='Saturday'){
$queryChange="select ADDDATE('$FechaProgramada',interval -2 day) as NewDay,dayname(ADDDATE('$FechaProgramada',interval -2 day)) as NombreDia,concat_ws('-',day(ADDDATE('$FechaProgramada',interval -2 day)),month(ADDDATE('$FechaProgramada',interval -2 day)),year(ADDDATE('$FechaProgramada',interval -2 day))) as DiaTope";
$querySelect="select ADDDATE('$FechaProgramada',interval 4 day) as NewDay, dayname(ADDDATE('$FechaProgramada',interval 4 day)) as NombreDia,concat_ws('-',day(ADDDATE('$FechaProgramada',interval 4 day)),month(ADDDATE('$FechaProgramada',interval 4 day)),year(ADDDATE('$FechaProgramada',interval 4 day))) as DiaTope";}

if($NombreDia=='Sunday'){
$queryChange="select ADDDATE('$FechaProgramada',interval -2 day) as NewDay, dayname(ADDDATE('$FechaProgramada',interval -2 day)) as NombreDia,concat_ws('-',day(ADDDATE('$FechaProgramada',interval -2 day)),month(ADDDATE('$FechaProgramada',interval -2 day)),year(ADDDATE('$FechaProgramada',interval -2 day))) as DiaTope";
$querySelect="select ADDDATE('$FechaProgramada',interval 3 day) as NewDay, dayname(ADDDATE('$FechaProgramada',interval 3 day)) as NombreDia, concat_ws('-',day(ADDDATE('$FechaProgramada',interval 3 day)),month(ADDDATE('$FechaProgramada',interval 3 day)),year(ADDDATE('$FechaProgramada',interval 3 day))) as DiaTope";}

$resp=mysql_query($queryChange);
$resp2=mysql_query($querySelect);
$row = mysql_fetch_array($resp);
$row2 = mysql_fetch_array($resp2);
$NuevaFecha=$row["NewDay"];//nueva fecha por la que se cambiara la original de la receta
$NombreDiaNuevo=$row["NombreDia"];
$FechaInicio=$row["DiaTope"];
$NuevaFecha2=$row2["NewDay"];//nueva fecha por la que se cambiara la original de la receta
$NombreDiaNuevo2=$row2["NombreDia"];
$FechaFin=$row2["DiaTope"];

$NombreDiaNuevo=NombreDia::CambiaNombre($NombreDiaNuevo);
$NombreDiaNuevo2=NombreDia::CambiaNombre($NombreDiaNuevo2);

/*$resp2=ConsultaRecetas::ModificaFechaReceta($IdReceta,$NuevaFecha);//Aqui se modifica la fecha de la receta por dias anteriores
$row2=mysql_fetch_array($resp2);*/

echo "FECHA PROXIMA PARA RETIRAR MEDICAMENTO EN DIAS HABILES ES: <strong>".$FechaInicio.$NombreDiaNuevo."</strong> AL <strong>".$FechaFin.$NombreDiaNuevo2."</strong>";


conexion::desconectar();
?>