<?php
require('../../Clases/class.php');

class PacientesProceso{
function IntroducirDatosPaciente($PrimerNombre,$SegundoNombre,$TercerNombre,$PrimerApellido,$SegundoApellido,$Sexo,$FechaNacimiento,$NombreMadre){

$PrimerNombre=strtoupper($PrimerNombre);
$SegundoNombre=strtoupper($SegundoNombre);
$TercerNombre=strtoupper($TercerNombre);
$PrimerApellido=strtoupper($PrimerApellido);
$SegundoApellido=strtoupper($SegundoApellido);
$NombreMadre=strtoupper($NombreMadre);


$queryInsert="insert into mnt_datospaciente (PrimerApellido,SegundoApellido,PrimerNombre,SegundoNombre,TercerNombre,Sexo,FechaNacimiento,NombreMadre) values('$PrimerApellido','$SegundoApellido','$PrimerNombre','$SegundoNombre','$TercerNombre','$Sexo','$FechaNacimiento','$NombreMadre')";

$querySelect="select mnt_datospaciente.IdPaciente
			from mnt_datospaciente
			order by mnt_datospaciente.IdPaciente desc limit 1";

mysql_query($queryInsert);
$IdPaciente=mysql_fetch_array(mysql_query($querySelect));

if(mysql_affected_rows()>0){
$Respuesta[0]=true;
$Respuesta[1]=$IdPaciente[0];
return($Respuesta);
}else{
$Respuesta[0]=false;
$Respuesta[1]='';
return($Respuesta);}
}//DatosPacientes	

function VerificaExpediente($NumeroExpediente){
	$querySelect="select * from mnt_expediente where IdNumeroExp='$NumeroExpediente'";
	if($resp=mysql_fetch_array(mysql_query($querySelect))){
		return(true);
	}else{
		return(false);
	}
}//Verifica Expediente

function IntroducirExpediente($NumeroExpediente,$IdPaciente){
	$queryInsert="insert into mnt_expediente (IdNumeroExp,IdPaciente) value('$NumeroExpediente','$IdPaciente')";
	mysql_query($queryInsert);
}//introducir expediente
	
}//Clase PacientesProceso


?>