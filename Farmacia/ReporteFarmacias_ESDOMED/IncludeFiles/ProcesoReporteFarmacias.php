<?php
$path='../';
include('ClasesReporteFarmacias.php');
conexion::conectar();
$Bandera=$_GET["Bandera"];
$reporte=new ReporteFarmacias;
switch($Bandera){
	case 1:
		/*Pegar Combo Grupos Terapeuticos*/
		$Valor=$_GET["ValorSeleccionado"];
		if($Valor==0){
			$Combo='<select name="IdTerapeutico" id="IdTerapeutico" disabled="disabled"><option value="0">[Seleccione ...]</option></select>';
		}else{
			/*	CARGA DE COMBO	*/
			$resp=$reporte->GruposTerapeuticos(0);
			
			$Combo='<select name="IdTerapeutico" id="IdTerapeutico" onChange="CargarCombo(this.id,this.value)">';
			$Combo.='<option value="0">[Seleccione ...]</option>';
			while($row=mysql_fetch_array($resp)){	
				$Combo.='<option value="'.$row[0].'">'.$row[1].'</option>';			
			}
			$Combo.='</select>';
			
		}
		
		echo $Combo;
	break;
	case 2:
		/*Pegar Combo de Medicinas*/
		$Valor=$_GET["ValorSeleccionado"];
		if($Valor==0){
			$Combo='<select name="IdMedicina" id="IdMedicina" disabled="disabled"><option value="0">[Seleccione ...]</option></select>';
		}else{
			/*	CARGA DE COMBO	*/
			$IdTerapeutico=$_GET["ValorSeleccionado"];
			$resp=$reporte->MedicamentosPorGrupo($IdTerapeutico);
			
			$Combo='<select name="IdMedicina" id="IdMedicina">';
			$Combo.='<option value="0">[Seleccione ...]</option>';
			while($row=mysql_fetch_array($resp)){	
				$Combo.='<option value="'.$row[0].'">'.$row[1].' - '.$row[2].' - '.$row[3].'</option>';			
			}
			$Combo.='</select>';
			
		}
		
		echo $Combo;
	break;
	
}
conexion::desconectar();
?>