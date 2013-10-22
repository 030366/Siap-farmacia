<?php
class variables{
function CambioNivel($Nivel){
switch($Nivel){
case 2:
		$Nivel="Co-Administrador";
break;

case 3:
		$Nivel="T&eacute;cnico Farmacia";
break;

}//swithc
return($Nivel);
}//Nivel

function CambioFarmacia($Farmacia){
switch($Farmacia){
case 1:
		$Farmacia="Central";
break;
case 2:
		$Farmacia="Consulta Externa";
break;

case 3:
		$Farmacia="Emergencias";
break;
}
return($Farmacia);
}//Farmacia

function CambioArea($IdArea){

switch($IdArea){
case 1:
		$IdArea="Altas";
break;
case 2:
		$IdArea="Consulta Externa";
break;

case 3:
		$IdArea="Emergencias";
break;

case 4:
		$IdArea="Especialidades";
break;

case 5:
		$IdArea="Recetarios";
break;

case 6:
		$IdArea="Unidosis";
break;
}
return($IdArea);
}//Area
}//Clase
?>