<?php session_start();
//Validacion de Session
include('../../Clases/class.php');
include('./Clases.php');

$especialidad=new Especialidades;
$subespecialidad = new SubEspecialidades;
$servicio= new Servicios;
$subservicio=new SubServicios;
$laboratorio = new Laboratorio;
$farmacia=new Farmacia;
$especiales= new Especiales;

conexion::conectar();


if(!isset($_SESSION["nivel"])){
    echo "ERROR_SESSION";
}else{


switch($_GET["Bandera"]){
  case 9:
	//Case para Adminiatracion de Catalogo de Farmacia
	switch($_GET["SubOpcion"]){
	   case 91:
		//Fiultracion por GrupoTerapeutico
		$resp=$farmacia->ObtenerGrupoTerapeutico();
	     if($row=mysql_fetch_array($resp)){
		$out="<table>
		
		<tr><td align='center'><select id='IdGrupoTerapeutico' name='IdGrupoTerapeutico' onchange='CargarCatFarmacia();'>
			<option value='0'> << Grupo Terapeutico >> </option>";
			do{
			$out.="<option value='".$row["IdTerapeutico"]."'>".$row["IdTerapeutico"]." - ".$row["GrupoTerapeutico"]."</option>";
			}while($row=mysql_fetch_array($resp));
		$out.="</select></td></tr>
		<tr><td align='center'>Busqueda => Codigo/Nombre: <input type='text' id='Nombre' name='Nombre' size='40' onkeyup='CargarCatFarmacia2();'></td></tr>
		<tr><th><u><strong>CATALOGO DE MEDICAMENTOS POR GRUPO TERAPEUTICO</strong></u></th></tr>
		
		<tr><td style='border:solid;'  valign='top' width='995px' height='325' align='center'>
			<div id='Farmacos' style='overflow:scroll; width:995px; height:360; '></div>
		</td></tr>
		
		<tr><td style='font-size:10px'>*Para habilitar y/o deshabilitar medicamentos dar clic en el 	cheque de la opcion deseada.- </td></tr>
		</table>";
			
	     }else{
		$out= "NO_GRUPO";
	     }
		echo $out;
	   break;
	   case 92:
		//Mostrar Catalogo por Grupo Terapeutico
		$IdGrupoTerapeutico=$_GET["IdGrupoTerapeutico"];
		$Nombre=$_GET["Nombre"];
		$out="";
		$resp=$farmacia->CatalogoxGrupo($IdGrupoTerapeutico,$Nombre);
		if($resp!=false){
		if($row=mysql_fetch_array($resp)){
	//la consulta genera informacion
	   	    $out.="		
		    <table width='100%' >
		    <tr class='FONDO2'><th style='border-left:solid; border-right:solid; border-bottom:solid;'>CODIGO</th><th style=' border-right:solid;border-bottom:solid;'>MEDICAMENTO</th><th style=' border-right:solid;border-bottom:solid;'>CONCENTRACION</th><th style=' border-right:solid;border-bottom:solid;'>PRESENTACION</th><th style=' border-right:solid;border-bottom:solid;'>HABILITAR/DESHABILITAR</th><th style=' border-right:solid;border-bottom:solid;'>ESTUPEFACIENTE</th>
		<th style=' border-right:solid;border-bottom:solid;'>DIVISOR</th></tr>";
	   	do{
		    $confirmacion=$farmacia->MedicamentoHabilitado($row["IdMedicina"],$_SESSION["IdEstablecimiento"],$_SESSION["IdModalidad"]);
		    
		    if($confirmacion!=NULL and $confirmacion!=''){
		       $checkbox="<input type='checkbox' id='".$row["IdMedicina"]."' value='".$row["IdMedicina"]."' checked='true' alt='Deshabilitar' onclick='DeshabilitarMedicamento(this.value,".$_SESSION["IdEstablecimiento"].");'>";
		    }else{
		       $checkbox="<input type='checkbox' id='".$row["IdMedicina"]."' value='".$row["IdMedicina"]."' alt='Habilitar' onclick='HabilitarMedicamento(this.value,".$_SESSION["IdEstablecimiento"].");'>";
		    }
			//verificacion si es Estupefaciente o no
		    $verifica2=$farmacia->Estupefaciente($row["IdMedicina"],$_SESSION["IdEstablecimiento"],$_SESSION["IdModalidad"]);
		    if($verifica2!=NULL and $verifica2!=''){
			$checkbox_estupe="<input type='checkbox' id='".$row["IdMedicina"]."' value='".$row["IdMedicina"]."' checked='true' alt='Deshabilitar' onclick='Estupefaciente(this.value,".$_SESSION["IdEstablecimiento"].",\"N\");'>";
		    }else{
			$checkbox_estupe="<input type='checkbox' id='".$row["IdMedicina"]."' value='".$row["IdMedicina"]."' alt='Deshabilitar' onclick='Estupefaciente(this.value,".$_SESSION["IdEstablecimiento"].",\"S\");'>";
		    }

			$verDivisor=$farmacia->Divisor($row["IdMedicina"]);
			if($respDivisor=mysql_fetch_array($verDivisor)){
			  $valorDivisor=$farmacia->ValorDivisor($row["IdMedicina"],$_SESSION["IdEstablecimiento"],$_SESSION["IdModalidad"]);
			  if($valorRow=mysql_fetch_array($valorDivisor)){
			   $divisor="<input type='text' id='Divisor".$row["IdMedicina"]."' name='Divisor".$row["IdMedicina"]."' value='".$valorRow[0]."' size='5' onblur='NoCero(this.id); AsginarDivisor(this.id,this.value)' onKeyPress='return acceptNum(event);' style='border:solid;'>";
			  }else{
			   $divisor="<input type='text' id='Divisor".$row["IdMedicina"]."' name='Divisor".$row["IdMedicina"]."' size='5' onblur='NoCero(this.id); AsginarDivisor(this.id,this.value)' onKeyPress='return acceptNum(event);' style='border-color:red;'>";
			  }
			}else{
			   $divisor="--";
			}

		
		   $out.="<tr class='FONDO'><td valign='top' style='border-left:solid; border-right:solid;border-bottom:solid;'>".$row["Codigo"]."</td><td valign='top' style=' border-right:solid;border-bottom:solid;'>".htmlentities($row["Nombre"])."</td><td valign='top' style=' border-right:solid;border-bottom:solid;'>".$row["Concentracion"]." &nbsp;</td> <td valign='top' style=' border-right:solid;border-bottom:solid;'>".htmlentities($row["FormaFarmaceutica"])."<br>".htmlentities($row["Presentacion"])."</td><td valign='middle' align='center' style=' border-right:solid;border-bottom:solid;'>".$checkbox."</td><td valign='middle' align='center' style=' border-right:solid;border-bottom:solid;'>".$checkbox_estupe."</td>
	<td valign='middle' align='center' style='border-right:solid;border-bottom:solid;'>".$divisor."</td>
			
			</tr>";
		
		
	   	}while($row=mysql_fetch_array($resp)); 
	   	    $out.="</table>";
		}
		
		}else{
			//Si no hay informacion o la consulta genera errores...
		   $out='NO_FARMA';
		}
	
		echo $out;
	   break;
	   case 93:
		//Habilitar Medicamentos
		$IdMedicina=$_GET["IdMedicina"];
		$IdEstablecimiento=$_GET["IdEstablecimiento"];
                $IdModalidad=$_SESSION["IdModalidad"];
                
		$verifica=$farmacia->MedicamentoHabilitado($IdMedicina,$IdEstablecimiento,$IdModalidad,1);
		if($verifica!=NULL and $verifica!=''){
		   $farmacia->HabilitarMedicina($IdMedicina,$IdEstablecimiento,$_SESSION["IdUser"],$IdModalidad);
		}else{
		   $farmacia->LevantamientoMedicina($IdMedicina,$IdEstablecimiento,$_SESSION["IdUser"],$IdModalidad);
		}
	   break;
	   case 94:
		//Deshabilitar Medicamentos
		$IdMedicina=$_GET["IdMedicina"];
		$IdEstablecimiento=$_GET["IdEstablecimiento"];
                $IdModalidad=$_SESSION["IdModalidad"];
                
		$farmacia->DeshabilitarMedicina($IdMedicina,$IdEstablecimiento,$_SESSION["IdUser"],$IdModalidad);
		
	   break;
	   case 95:
		//Si es estupefaciente o no
		$IdMedicina=$_GET["IdMedicina"];
		$IdEstablecimiento=$_GET["IdEstablecimiento"];
		$Estado=$_GET["Estado"];
                $IdModalidad=$_SESSION["IdModalidad"];
                
		$farmacia->EstadoEstupefaciente($IdMedicina,$IdEstablecimiento,$Estado,$_SESSION["IdUser"],$IdModalidad);
	   break;
	}
	
  break;
  case 10:
	$IdMedicina=$_GET["IdMedicina"];
	$Divisor=$_GET["Divisor"];
	$farmacia->IngresaDivisor($IdMedicina,$Divisor,$_SESSION["IdEstablecimiento"],$_SESSION["IdModalidad"]);
  break;

//*******************************************************************************************************
}
conexion::desconectar();


}
?>
