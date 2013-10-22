<?php session_start();
$IdPersonal=$_SESSION["IdPersonal"];
$IdArea=$_SESSION["IdArea"];
require('PacienteProcesoClase.php');
conexion::conectar();
$proceso=new PacientesProceso;
$Bandera=$_GET["Bandera"];
switch($Bandera){

case 1:
/* Introduccion de Datos Generales Paciente */
	$PrimerNombre= $_GET["Nombre1"];$SegundoNombre=$_GET["Nombre2"];$TercerNombre=$_GET["TercerNombre"];
	$PrimerApellido=$_GET["Apellido1"];$SegundoApellido=$_GET["Apellido2"];
	$Sexo=$_GET["Sexo"];$FechaNacimiento=$_GET["FechaNacimiento"];
	$NombreMadre=$_GET["NombreMadre"];
$Respuesta=$proceso->IntroducirDatosPaciente($PrimerNombre,$SegundoNombre,$TercerNombre,$PrimerApellido,$SegundoApellido,$Sexo,$FechaNacimiento,$NombreMadre);

if($Respuesta[0]==true){
$echo="SI-".$Respuesta[1]."-";
$echo=$echo."<table>
<tr><td><strong>INTRODUCCION DE NUMERO DE EXPEDIENTE</strong></td></tr>
<tr><td>Numero de Expediente: </td><td><input type=\"text\" id=\"NumeroExpediente\" name=\"NumeroExpediente\" /></td></tr>
<tr><td><input type='button' id='agregar' name='agregar' value='Crear Expediente' onClick='javascript:valida2();'>
</table>";
echo $echo;
}else{
echo "...... El Registro no fue creado........";

}



break;

case 2:
/* CREACION DE EXPEDIENTE */
	$NumeroExpediente=$_GET["NumeroExpediente"];
	$IdPaciente=$_GET["IdPaciente"];
	$resp=$proceso->VerificaExpediente($NumeroExpediente);
	if($resp==false){
		$proceso->IntroducirExpediente($NumeroExpediente,$IdPaciente);
		echo "OK1";
	}else{
		echo "EXISTE";
	}
	
break;

default:
/*************************/
break;

}//Fin de switch
conexion::desconectar();

?>
