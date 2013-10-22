<?php session_start();
include('Clases.php');
conexion::conectar();
$mantto=new Mantenimientos;
$Bandera=$_GET["Bandera"];
switch($Bandera){
	case 1: 
		// OBTENECION DE AREAS DE FARMACIA
		$numero=1;
		$resp=$mantto->AreasFarmacia($_GET["IdFarmacia"],$_SESSION["IdEstablecimiento"],$_SESSION["IdModalidad"]);
                $disabled="";
		//if($_GET["IdFarmacia"]==4){$disabled="disabled='true'";}else{$disabled="";}
		$tabla="<table width='95%'>
			<tr><td colspan='3'>&nbsp;</td></tr>
			<tr><td colspan='3'><hr></td></tr>
			<tr><td align='right'>Ingresar Nueva Area:</td><td colspan=2><input type='text' id='NuevaArea' name='NuevaArea' maxlength='30' ".$disabled."> <input type='button' id='agregar' name='agregar' value='Agregar Area' onclick='Agregar()' ".$disabled."></td></tr>
			<tr><td colspan='3'><hr></td></tr>
			";
		$tabla.="<tr><td width='20%'>No.</td><td><strong>Area</strong></td><td><strong>Estado</strong></td>";
		   while($row=mysql_fetch_array($resp)){
			if($row["Habilitado"]=='N' or $row["Habilitado"]==null){
			   $habilitado="<input type='checkbox' id='".$row["IdArea"]."' value='".$row["IdArea"]."' onclick='CambiaEstado(this.value,1,".$_GET["IdFarmacia"].");'  ".$disabled.">";
			}else{
			   $habilitado="<input type='checkbox' id='".$row["IdArea"]."' value='".$row["IdArea"]."' onclick='CambiaEstado(this.value,2,".$_GET["IdFarmacia"].");' checked=true ".$disabled.">";
			}

			$tabla.="<tr><td>".$numero."</td><td><span id='spanAreaExt".$row["IdArea"]."'><span id='spanArea".$row["IdArea"]."' onclick='CambioNombreArea(".$row["IdArea"].")'>".$row["Area"]."</span></span></td><td>".$habilitado."</td>";
			
			$numero++;
		   }
		$tabla.="</table>";
		echo $tabla;
	break;
	case 2:

	    $mantto->CambioEstado($_GET["IdArea"],$_GET["Estado"],$_SESSION["IdEstablecimiento"],$_SESSION["IdModalidad"]);
		
	break;
	case 3:
		//INGRESO NUEVA AREA DE FARMACIA
		$IdFarmacia=$_GET["IdFarmacia"];
		$NombreArea=$_GET["NombreArea"];
		$IdEstablecimiento=$_SESSION["IdEstablecimiento"];
                $IdModalidad=$_SESSION["IdModalidad"];
		$Estado='S';
		
		   $test=$mantto->verificar($NombreArea);
		if($row=mysql_fetch_array($test)){
		   echo "N";
		}else{
		   $mantto->IngresarArea($IdFarmacia,$NombreArea,$Estado,$IdEstablecimiento);
		}	
		
	break;
	case 4:
		$IdFarmacia=$_GET["IdFarmacia"];
		$Estado=$_GET["Estado"];
		
		$mantto->CambioEstadoFarmacia($IdFarmacia,$Estado,$_SESSION["IdEstablecimiento"],$_SESSION["IdModalidad"]);
		
		$SQL="select distinct mf.IdFarmacia,mf.Farmacia 
                    from mnt_farmacia mf
                    inner join mnt_farmaciaxestablecimiento mfe
                    on mfe.IdFarmacia=mf.IdFarmacia
                    where mfe.HabilitadoFarmacia ='S'
                    and IdEstablecimiento=".$_SESSION["IdEstablecimiento"]."
                    and IdModalidad=".$_SESSION["IdModalidad"];
		$resp=mysql_query($SQL);
  

	$combo="<select id='IdFarmacia' name='IdFarmacia' onchange='CargarAreas(this.value);'>
		<option value='0'>[SELECCIONE]</option>";
	    while($row=mysql_fetch_array($resp)){
		$combo.="<option value='".$row["IdFarmacia"]."'>".$row["Farmacia"]."</otion>";
	    }
	$combo.="</select>";
		echo $combo;
	break;

//***************CAMBIO DE NOMBRE DE FARMACIAS******************
	case 5:
		$IdFarmacia=$_GET["IdFarmacia"];
		$resp=mysql_fetch_array($mantto->Farmacia($IdFarmacia));
		$text="<input type='text' id='Nombre".$resp[0]."' name='Nombre".$resp[0]."' value='".$resp[1]."' onblur='CambiarNombreFinal(this.id,this.value,".$IdFarmacia.");'>";
		
		echo $text;
	break;
	case 6:
		$IdFarmacia=$_GET["IdFarmacia"];
		$NombreNuevo=$_GET["NombreNuevo"];
			$mantto->ActualizaNombreFarmacia($IdFarmacia,$NombreNuevo);
		$row=mysql_fetch_array($mantto->Farmacia($IdFarmacia));
		
		$text="<span id='span".$row["IdFarmacia"]."' onclick='CambioNombre(".$row["IdFarmacia"].")'>".$row["Farmacia"]."</span>";
		echo $text;
	break;
//**************************************************************
//*************CAMBIO DE NOMBRES DE AREAS DE FARMACIA***************
	case 7:
		$IdArea=$_GET["IdArea"];
		$resp=mysql_fetch_array($mantto->FarmaciaArea($IdArea));
		$text="<input type='text' id='Nombre".$resp[0]."' name='Nombre".$resp[0]."' value='".$resp[1]."' onblur='CambiarNombreFinalArea(this.id,this.value,".$IdArea.");'>";
		
		echo $text;
	break;
	case 8:
		$IdArea=$_GET["IdArea"];
		$NombreNuevo=$_GET["NombreNuevo"];
			$mantto->ActualizaNombreFarmaciaArea($IdArea,$NombreNuevo);
		$row=mysql_fetch_array($mantto->FarmaciaArea($IdArea));
		
		$text="<span id='spanArea".$row["IdArea"]."' onclick='CambioNombreArea(".$row["IdArea"].")'>".$row["Area"]."</span>";
		echo $text;
	break;

//**********************************************************

	case 12:
            //FREE
		
	break;
	
	default:
		
	break;
	
	
	
}//switch
conexion::desconectar();
?>