<?php session_start();
//Validacion de Session
include('../../Clases/class.php');
include('./Clases.php');

$farmacia=new Farmacia;


conexion::conectar();


if(!isset($_SESSION["nivel"])){
    echo "ERROR_SESSION";
}else{

    //variable general
    $IdModalidad=$_SESSION["IdModalidad"];
    
    //******************//

switch($_GET["Bandera"]){
  case 1:
	//Inicio de Combos
	$resp=$farmacia->Farmacias($_SESSION["IdEstablecimiento"],$_SESSION["IdModalidad"]);
	     if($row=mysql_fetch_array($resp)){
		$out="<select id='IdFarmacia' name='IdFarmacia' onchange='CargarAreas(this.value);'>
			<option value='0'> [SELECCIONE] </option>";
			do{
			$out.="<option value='".$row["IdFarmacia"]."'>".$row["Farmacia"]."</option>";
			}while($row=mysql_fetch_array($resp));
		$out.="</select>-";
	     }else{
		$out="<select id='IdFarmacia' name='IdFarmacia'>
			<option value='0'> [SELECCIONE] </option>
			</select>-";
	     }

	$resp=$farmacia->ObtenerGrupoTerapeutico();
	     if($row=mysql_fetch_array($resp)){
		$out.="<select id='IdGrupoTerapeutico' name='IdGrupoTerapeutico' onchange='CargarCatFarmacia(this.value);' disabled='true'>
			<option value='0'> << Grupo Terapeutico >> </option>";
			do{
			$out.="<option value='".$row["IdTerapeutico"]."'>".$row["IdTerapeutico"].' = '.$row["GrupoTerapeutico"]."</option>";
			}while($row=mysql_fetch_array($resp));
		$out.="</select>";
	     }
	     echo $out;

  break;
  case 2:
	//Carga de combo Area de Farmacia
	$IdFarmacia=$_GET["IdFarmacia"];
	$resp=$farmacia->Areas($IdFarmacia,$_SESSION["IdEstablecimiento"],$_SESSION["IdModalidad"]);
	$out="<select id='IdArea' name='IdArea' onchange='HabilitarCombo();'>
		<option value='0'>[SELECCIONE]</option>";
	if($row=mysql_fetch_array($resp)){
	   do{
	   $out.="<option value='".$row["IdArea"]."'>".$row["Area"]."</option>";
	  }while($row=mysql_fetch_array($resp));
	}
	$out.="</select>";
	echo $out;

  break;
  case 9:
	//Case para Adminiatracion de Catalogo de Farmacia
	switch($_GET["SubOpcion"]){
	   case 92:
		//Mostrar Catalogo por Grupo Terapeutico
		$IdGrupoTerapeutico=$_GET["IdGrupoTerapeutico"];
		$out="";
		$resp=$farmacia->CatalogoxGrupo($IdGrupoTerapeutico,$_SESSION["IdEstablecimiento"],$_SESSION["IdModalidad"]);
		if($resp!=false){
		if($row=mysql_fetch_array($resp)){
	//la consulta genera informacion
			
	   	    $out.="		
		    <table width='100%'>
		    <tr class='FONDO2'><th style='border-left:solid; border-right:solid; border-bottom:solid;'>CODIGO</th><th style=' border-right:solid;border-bottom:solid;'>MEDICAMENTO</th><th style=' border-right:solid;border-bottom:solid;'>CONCENTRACION</th><th style=' border-right:solid;border-bottom:solid;'>PRESENTACION</th><th style=' border-right:solid;border-bottom:solid;'>HABILITAR<br>[<input type='checkbox' id='all' name='all' onclick='SeleccionaTodo();'> TODO]</th><th style=' border-right:solid;border-bottom:solid;'>AREA DISPENSADA</th></tr>";
	   	do{
                    $IdMedicina=$row["IdMedicina"];
		    $confirmacion=$farmacia->MedicamentoHabilitado($row["IdMedicina"],$_GET["IdArea"],$_SESSION["IdEstablecimiento"],$_SESSION["IdModalidad"]);
		    
		    if($confirmacion!=NULL and $confirmacion!=''){
		       $checkbox="<input type='checkbox' name='checkeo' id='".$row["IdMedicina"]."' value='".$row["IdMedicina"]."' checked='true' alt='Deshabilitar' onclick='DeshabilitarMedicamento(this.value);'>";
		    }else{
		       $checkbox="<input type='checkbox' name='checkeo' id='".$row["IdMedicina"]."' value='".$row["IdMedicina"]."' alt='Habilitar' onclick='HabilitarMedicamento(this.value);'>";
		    }

			//verificacion si es Dispensada en otra area
		    $Dispensada=$farmacia->DispensadaEn($IdMedicina,$_GET["IdArea"],$_SESSION["IdModalidad"]);
                    
		    if($Dispensada[0]!='' and $Dispensada[0]!=NULL){
			$select="<select id='Despacha".$IdMedicina."' onchange='AreaDespacha(".$IdMedicina.",\"C\");'>
				<option value='0'>".$Dispensada[1]."</option>";
				$select.=$farmacia->AreasDispensar($IdModalidad,$Dispensada[0]);
			$select.="</select> <input type='button' id='".$IdMedicina."' value='Eliminar' onclick='AreaDespacha(this.id,\"E\");'><input type='hidden' id='Old".$IdMedicina."' value='".$Dispensada[0]."'>";
		    }else{
			$select="<select id='Despacha".$IdMedicina."'>
				<option value='0'>[AREA DE ENTREGA (Opcional)]</option>";
				$select.=$farmacia->AreasDispensar($IdModalidad);
			$select.="</select> <input type='hidden' id='Old".$IdMedicina."' value='".$Dispensada[0]."'> <input type='button' id='Despacha".$IdMedicina."' value='Asignar Area' onclick='AreaDespacha(".$IdMedicina.",\"H\");'>";
		    }


		    		
		    $out.="<tr class='FONDO'><td valign='top' style='border-left:solid; border-right:solid; border-bottom:solid;'>".$row["Codigo"]."</td><td valign='top' style=' border-right:solid; border-bottom:solid;'>".htmlentities($row["Nombre"])."</td><td valign='top' style=' border-right:solid; border-bottom:solid;'>".$row["Concentracion"]."</td> <td valign='top' style=' border-right:solid; border-bottom:solid;'>".htmlentities($row["FormaFarmaceutica"].' - '.$row["Presentacion"])."</td><td valign='top' align='center' style=' border-right:solid; border-bottom:solid;'>".$checkbox."</td><td style=' border-right:solid;border-bottom:solid;'>".$select."</td></tr>";
		
		
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
		$IdArea=$_GET["IdArea"];
		$verifica=$farmacia->AgregarMedicamento($IdMedicina,$IdArea,$_SESSION["IdEstablecimiento"],$IdModalidad);
		echo $verifica;
	   break;
	   case 94:
		//Deshabilitar Medicamentos
		$IdMedicina=$_GET["IdMedicina"];
		$IdArea=$_GET["IdArea"];
		$farmacia->EliminarMedicina($IdMedicina,$IdArea,$_SESSION["IdEstablecimiento"],$IdModalidad);
		
	   break;
	   case 95:
		//AgregarArea despacho
		$IdMedicina=$_GET["IdMedicina"];
		$IdArea=$_GET["IdArea"];
		$IdAreaDispensada=$_GET["IdAreaDispensada"];
		$Accion=$_GET["Accion"];
		$IdAreaOld=$_GET["IdAreaOld"];
                
		$farmacia->AreaDespacho($IdMedicina,$IdArea,$IdAreaDispensada,$IdAreaOld,$Accion,$_SESSION["IdEstablecimiento"],$IdModalidad);
                
               
	   break;
	}
	
  break;


//*******************************************************************************************************
}
conexion::desconectar();


}
?>