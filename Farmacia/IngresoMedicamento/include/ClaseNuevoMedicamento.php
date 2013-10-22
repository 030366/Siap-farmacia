<?php
class NuevoMedicamento{
function ComboGrupoTerapeutico(){
	$querySelect="select * from mnt_grupoterapeutico";
	$resp=mysql_query($querySelect);
	return($resp);
}//ComboGrupoTerapeutico

function ComboEspecialidades(){
	$querySelect="select IdSubEspecialidad,NombreSubEspecialidad from mnt_subespecialidad order by NombreSubEspecialidad";
	$resp=mysql_query($querySelect);
	return($resp);
}//ComboEspecialidades

function ComboAreas(){
	$querySelect="select IdArea,Area from mnt_areafarmacia";
	$resp=mysql_query($querySelect);
	return($resp);
}//ComboAreas

function Last(){
$querySelect="select IdMedicina from farm_catalogoproductos order by IdMedicina desc limit 1";
$row=mysql_fetch_array(mysql_query($querySelect));
$IdMedicina=$row["IdMedicina"];
return($IdMedicina);
}

function InfoMedicina($IdMedicina){
$SQL="select * from farm_catalogoproductos where IdMedicina=".$IdMedicina;
$resp=mysql_query($SQL);
return($resp);
}


/* 
  FUNCIONES PARA LA INTRODUCCION DE MEDICAMENTO A LA DB ENLACE CON ESPECIALIDADES
*/
function VerificaMedicamento($Codigo){
	$querySelect="select * from farm_catalogoproductos where Codigo = '$Codigo'";
	$resp=mysql_query($querySelect);
	if($row=mysql_fetch_array($resp)){  return(true);}else{return(false);}
}//Fin VerificaMedicamento


function ActualizarEstadoMedicamento($IdMedicina,$codigo,$Nombre1,$concentracion,$presentacion,$precio,$IdGrupoTerapeutico,$UnidadMedida,$IdHospital,$IdUsuarioReg,$IdModalidad){

	$query="update farm_catalogoproductos set  Concentracion='$concentracion',Presentacion='$presentacion',IdUnidadMedida='$UnidadMedida', IdTerapeutico='$IdGrupoTerapeutico', IdHospital='$IdHospital', IdEstado='H' where IdMedicina='$IdMedicina'";
	mysql_query($query);
	
	
	$SQL="insert into farm_catalogoproductosxestablecimiento (IdMedicina,IdEstablecimiento,IdUsuarioReg,FechaHoraReg,IdModalidad) 
                                                           values('$IdMedicina','$IdHospital','$IdUsuarioReg',now(),$IdModalidad)";
	mysql_query($SQL);
	
}//Actualizar Estado de Medicmaneto


function AgregarMedicamento($Codigo,$Nombre,$Concentracion,$Presentacion,$PrecioActual,$IdTerapeutico,$UnidadMedida,$IdHospital,$IdUsuarioReg,$IdModalidad){
	$B=$this->VerificaMedicamento($Codigo);
	if($B==false){
	$queryInsert="insert into farm_catalogoproductos (Codigo, IdUnidadMedida,Nombre,Concentracion,Presentacion,PrecioActual,PerteneceListadoOficial,IdTerapeutico,IdHospital) 
                                                   values('$Codigo','$UnidadMedida','$Nombre','$Concentracion','$Presentacion','$PrecioActual',0,'$IdTerapeutico','$IdHospital')";
		mysql_query($queryInsert);
	
	$LatID=mysql_insert_id();
	
	if($LatID!=0){
	$SQL="insert into farm_catalogoproductosxestablecimiento (IdMedicina,IdEstablecimiento,IdUsuarioReg,FechaHoraReg,IdModalidad) 
                                                           values('$LatID','$IdHospital','$IdUsuarioReg',now(),$IdModalidad)";
		mysql_query($SQL);
	return($LatID);
	}else{return($queryInsert);}
	
	
	}else{
		return(false);
	}
}//AgregarMedicamento

/*ASIGNACION DE MEDICAMENTO A ESPECIALIDADES*/

function AsignarMedicamento($IdMedicamento,$IdSubEspecialidad){
	if($IdSubEspecialidad==0){
		/*PARA TODAS LAS ESPECIALIDADES*/
		$querySelect="select IdSubEspecialidad from mnt_subespecialidad";
		$resp=mysql_query($querySelect);
		while($row=mysql_fetch_array($resp)){
			$IdSubEspecialidad=$row["IdSubEspecialidad"];
			$queryInsert="insert into mnt_medicinaespecialidad (IdMedicina,IdSubEspecialidad) values('$IdMedicina','$IdSubEspecialidad')";
			mysql_query($queryInsert);//Insertamos un nuevo registro en tabla
			
		}//fin de while
	}else{
		/*UNA SOLA ESPECIALIDAD O VARIAS PERO SELECCIONADAS*/
		$queryInsert="insert into mnt_medicinaespecialidad (IdMedicina,IdSubEspecialidad) values('$IdMedicina','$IdSubEspecialidad')";
		mysql_query($queryInsert);
	}

}

function GetName($IdMedicina){
$querySelect="select Nombre from farm_catalogoproductos where IdMedicina = '$IdMedicina'";
$resp=mysql_fetch_array(mysql_query($querySelect));
$nombre=$resp[0];
return($nombre);
}//GetName

function GetEspecialidad($IdSubEspecialidad){
$querySelect="select NombreSubEspecialidad from mnt_subespecialidad where IdSubEspecialidad = '$IdSubEspecialidad'";
$resp=mysql_fetch_array(mysql_query($querySelect));
$nombre=$resp[0];
return($nombre);
}//GetEspecialidad


function Correlativo(){
	$SQL="select (count(IdMedicina)+1) as Correlativo, LPAD((count(IdMedicina)+1), 5, '0') as CodigoCorrelativo
		from farm_catalogoproductos 
		where left(codigo,3)='099'";
	$resp=mysql_fetch_array(mysql_query($SQL));
	return($resp[1]);
}

function GetInformacion($IdMedicina){
$SQL="SELECT Codigo,Nombre,Descripcion,GrupoTerapeutico,Concentracion,Presentacion
		from farm_catalogoproductos fcp
		inner join mnt_grupoterapeutico mgt
		on mgt.IdTerapeutico=fcp.IdTerapeutico
		inner join farm_unidadmedidas fum
		on fum.IdUnidadMedida = fcp.IdUnidadMedida
		
		where IdMedicina=".$IdMedicina;
	$resp=mysql_query($SQL);
	return($resp);

}

function GetInformacionMedicina($campo,$IdMedicina){
	$SQL="SELECT $campo
		from farm_catalogoproductos fcp
		inner join mnt_grupoterapeutico mgt
		on mgt.IdTerapeutico=fcp.IdTerapeutico
		inner join farm_unidadmedidas fum
		on fum.IdUnidadMedida = fcp.IdUnidadMedida
		
		where IdMedicina=".$IdMedicina;
	$resp=mysql_query($SQL);
	
	return($resp);
}


function ComboTerapeutico($IdTerapeutico,$IdMedicina,$opcion){
	$SQL="select IdTerapeutico, GrupoTerapeutico from mnt_grupoterapeutico where IdTerapeutico <>".$IdTerapeutico;
	$resp=mysql_query($SQL);
	   $combo="<select id='IdTerapeuticoNuevo' name='IdTerapeuticoNuevo' onchange='MakeChange(this.value,".$IdMedicina.",\"".$opcion."\");'>
		<option value='0'>SELECCIONE</option>";
	while($row=mysql_fetch_array($resp)){
		$combo.="<option value='".$row["IdTerapeutico"]."'>".$row["IdTerapeutico"]." - ".$row["GrupoTerapeutico"]."</option>";
	}
	   $combo.="</select>";

	return($combo);
}


function ComboMedida($IdUnidadMedida,$IdMedicina,$opcion){
	$SQL="select IdUnidadMedida, Descripcion from farm_unidadmedidas where IdUnidadMedida <>".$IdUnidadMedida." and (IdUnidadMedida=1 or IdUnidadMedida=2 or IdUnidadMedida=7 or IdUnidadMedida = 17 )";
	$resp=mysql_query($SQL);
	   $combo="<select id='IdUnidadMedidaNuevo' name='IdUnidadMedidaNuevo' onchange='MakeChange(this.value,".$IdMedicina.",\"".$opcion."\");'>
		<option value='0'>SELECCIONE</option>";
	while($row=mysql_fetch_array($resp)){
		$combo.="<option value='".$row["IdUnidadMedida"]."'>".$row["Descripcion"]."</option>";
	}
	   $combo.="</select>";

	return($combo);
}



function ActualizarInformacion($IdMedicina,$campo,$NuevaInfo){
	$SQL="update farm_catalogoproductos set $campo='$NuevaInfo' where IdMedicina=".$IdMedicina;
	mysql_query($SQL);

}




}//Fin de Clase

?>