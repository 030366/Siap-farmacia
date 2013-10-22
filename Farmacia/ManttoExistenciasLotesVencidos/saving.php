<?php session_start();
require('../Clases/class2.php');
$IdPersonal=$_SESSION["IdPersonal"];


class MedicamentoVencido{
function AumentaExistencias($IdArea,$IdMedicina,$cantidad,$ventto,$Lote,$Precio){

/* AQUI $Lote ES UNA CADENA QUE IDENTIFICA EL CODIGO DEL LOTE */
$respuesta=MedicamentoVencido::ConfirmaExistencia($IdMedicina,$IdArea,$Lote);
	if($row=mysql_fetch_array($respuesta)){
		$Multiplicador=queries::ObtenerUnidadMedida($IdMedicina);
		$cantidad=$cantidad*$Multiplicador;
		MedicamentoVencido::ActualizarExistencias($IdArea,$IdMedicina,$cantidad,$Lote);
	}else{
		$Multiplicador=queries::ObtenerUnidadMedida($IdMedicina);
		$cantidad=$cantidad*$Multiplicador;
		MedicamentoVencido::IntroducirExistencias($IdArea,$IdMedicina,$cantidad,$ventto,$Lote,$Precio);
	}
}//AumentaExistencias

function ObtenerUnidadMedida($IdMedicina){
	$querySelect="select farm_unidadmedidas.UnidadesContenidas
				from farm_unidadmedidas
				inner join farm_catalogoproductos
				on farm_catalogoproductos.IdUnidadMedida=farm_unidadmedidas.IdUnidadMedida
				where farm_catalogoproductos.IdMedicina='$IdMedicina'";
	$resp=mysql_fetch_array(mysql_query($querySelect));
	return($resp[0]);
}//ObtenerUnidadMedida

function ConfirmaExistencia($IdMedicina,$IdArea,$Lote){
if($Lote!='0'){

$querySelect="select farm_medicinavencida.Existencia,farm_lotes.IdLote
			from farm_medicinavencida
			inner join farm_lotes
			on farm_lotes.IdLote=farm_medicinavencida.IdLote 
			where farm_medicinavencida.IdMedicina='$IdMedicina' 
			and farm_lotes.Lote='$Lote'
			and IdArea='$IdArea'
			and Existencia <> 0 
			and  left(FechaVencimiento,7) < left(curdate(),7)";
}else{
//$querySelect="select * from farm_medicinavencida where IdMedicina='$IdMedicina' and IdArea='$IdArea'";
}
$resp=mysql_query($querySelect);
return($resp);
}//confirma existencia

//****Actualizacion de existencias
function ActualizarExistencias($IdArea,$IdMedicina,$cantidad,$Lote){
$resp=MedicamentoVencido::ConfirmaExistencia($IdMedicina,$IdArea,$Lote);
$row=mysql_fetch_array($resp);
$IdLote=$row["IdLote"];
$cantidad_old=$row["Existencia"];
$cantidad_new=$cantidad_old+$cantidad;

$queryUpdate="update farm_medicinavencida set Existencia='$cantidad_new' where IdMedicina='$IdMedicina' and IdLote='$IdLote' and IdArea='$IdArea'";

mysql_query($queryUpdate);

}//Actualizacion Existencias

//*****Introduccion de Existencias por area
function IntroducirExistencias($IdArea,$IdMedicina,$cantidad,$fecha,$Lote,$Precio){
    if($fecha=='Fecha Ventto.'){
        $Query="select FechaVencimiento from farm_lotes where IdLote=".$Lote;
        $resp=mysql_query($Query);
        if($row=mysql_fetch_array($resp)){
            $fecha=$row[0];
        }
        
    }    
    
	if($Lote!='0' and $fecha!='Fecha Ventto.'){
		$SelectExistencia="select Existencia
						from farm_medicinavencida
						inner join farm_lotes
						on farm_lotes.IdLote=farm_medicinavencida.IdLote
						where farm_lotes.IdLote='$Lote'";
		$rowExistencia=mysql_fetch_array(mysql_query($SelectExistencia));
		
		if($rowExistencia[0]!=NULL and $rowExistencia[0]!=''){
			/*	ACTUALIZACION DE EXISTENCIAS	*/
				$NuevaExistencia=$cantidad+$rowExistencia[0];
			
			
			$UpdateExistencia1="update farm_medicinavencida set Existencia='$NuevaExistencia' where IdArea=".$IdArea." and IdLote=".$Lote;
				mysql_query($UpdateExistencia1);
			
		}else{

			$IdLote=MedicamentoVencido::AgregarLote($Lote,$Precio,$fecha);
									
				$queryInsertExistencia1="insert into farm_medicinavencida(IdMedicina,Existencia,IdLote,IdArea,Fecha,FechaHoraIngreso,IdPersonal) values('$IdMedicina','$cantidad','$IdLote','$IdArea',curdate(),now(),".$_SESSION["IdPersonal"].")";
				mysql_query($queryInsertExistencia1);
			
		}
		
	}//Lote != NULL

}//Introducir Existencia

function AgregarLote($Lote,$Precio,$FechaVencimiento){
   $SQL="insert into farm_lotes (Lote,PrecioLote,FechaVencimiento) values('$Lote','$Precio','$FechaVencimiento')";
   mysql_query($SQL);
   $IdLote=mysql_insert_id();
	return($IdLote);
}

function ObtenerArea($IdArea){
   $SQL="select Area
	from mnt_areafarmacia
	where IdArea=".$IdArea;
	$resp=mysql_fetch_array(mysql_query($SQL));
	return($resp[0]);
}

	function ValorDivisor($IdMedicina){
	   $SQL="select DivisorMedicina from farm_divisores where IdMedicina=".$IdMedicina;
	   $resp=mysql_query($SQL);
	   return($resp);
    	}

}//clase

$query22=new MedicamentoVencido;

?>
<html>
<head>
<title>saving...</title>
</head>
<body>
<?php

if(isset($_GET["Bandera"])){$bandera=$_GET["Bandera"];}else{$bandera=1;}
if(isset($_GET["IdMedicina"])){$IdMedicina=$_GET["IdMedicina"];}else{$IdMedicina=0;}

if($bandera==2){//comprobacion de fechas
	$TestFecha=$_GET["TestFecha"];
	$querySelect="select left('$TestFecha',7)<left(curdate(),7)";
	$resp=mysql_fetch_array(mysql_query($querySelect));
	if($resp!=1){
		echo "SI";
	}else{
		echo "NO";
	}
}elseif($bandera!=0){

        if($IdMedicina==0){

	echo "<center>GUARDANDO EXISTENCIAS....<BR><BR><img src='../images/barra.gif'></center>";

        conexion::conectar();
        /* INTRODUCCION DE EXISTENCIAS POR POST DE FORMULARIO */
	
	$IdTerapeuticoCombo=$_POST["Terapeutico"];
	$IdAreaCombo=$_POST["IdArea"];

        $querySelect="select farm_catalogoproductos.IdMedicina,Codigo ,farm_catalogoproductos.Nombre,farm_catalogoproductos.FormaFarmaceutica,
	farm_catalogoproductos.Concentracion
	from farm_catalogoproductos
	inner join farm_catalogoproductosxestablecimiento cpe
	on cpe.IdMedicina=farm_catalogoproductos.IdMedicina
	
	where cpe.Condicion='H'
	and cpe.IdEstablecimiento=".$_SESSION["IdEstablecimiento"]."
	and IdTerapeutico=".$IdTerapeuticoCombo;
        $resp=mysql_query($querySelect);

        while($Datos=mysql_fetch_array($resp)){
	        $IdMedicina=$Datos["IdMedicina"];
	        $Lote_="Lote".$IdMedicina;
	        /**/
	        $mes="mes".$IdMedicina;
	        $ano="ano".$IdMedicina;
	        if(isset($_POST[$mes])){$mes=$_POST[$mes];}else{$mes=0;}
	        if(isset($_POST[$ano])){$ano=$_POST[$ano];}else{$ano=0;}
	        
	        if($mes!=0 and $ano!=0){	
		        $Vencimiento=$ano."-".$mes."-"."25";
	        }else{
		        $Vencimiento='Fecha Ventto.';
	        }
	        /**/
	        $Precio_="Precio".$IdMedicina;
	        /* Obtencion del post de los datos */
	        $NuevaExistencia=$_POST[$IdMedicina];
	        
	        $Lote=$_POST[$Lote_];
	        $Lote=strtoupper($Lote);
	        if(isset($_POST[$Precio_])){$Precio=$_POST[$Precio_];}else{$Precio=0;}

	        /* Fin de POST */
        if($NuevaExistencia!='0' and $Lote!='Lote.'){
$query22->AumentaExistencias($IdAreaCombo,$IdMedicina,$NuevaExistencia,$Vencimiento,$Lote,$Precio);
		}  
        }
        conexion::desconectar();
        ?>
        <script language="javascript">
        	window.location='existencia.php?IdTerapeutico=<?php echo $IdTerapeuticoCombo;?>&IdArea=<?php echo $IdAreaCombo;?>';
        </script>
        <?php 
        /*FIN DE DATOS ENVIAMOS POR POST*/
        }else{
	        /* INGRESO DE EXISTENCIA UTILIZANDO EL AJAX */
	        
	        conexion::conectar();
	        $vencimiento=$_GET["FechaVentto"]; //Información de Fecha.-
	        $NuevaExistencia=$_GET["Existencia"];
	        $Lote=$_GET["Lote"];
		$IdArea=$_GET["IdArea"];
	        $Lote=strtoupper($Lote);
	        $Precio=$_GET["PrecioLote"];
		

		        if($NuevaExistencia!='0'){
 
 $query22->AumentaExistencias($IdArea,$IdMedicina,$NuevaExistencia,$vencimiento,$Lote,$Precio);
			}
	        conexion::desconectar();
	        
        }//else 

}else{
/* REFRESCAMIENTO DE LA EXISTENCIAS DESPUES DE UTILIZAR AJAX */
conexion::conectar();

$data='';
$querySelect=" select farm_catalogoproductos.IdMedicina,farm_catalogoproductos.Nombre,farm_catalogoproductos.FormaFarmaceutica,
			farm_catalogoproductos.Concentracion, farm_medicinavencida.*,farm_lotes.*,
			farm_unidadmedidas.Descripcion,farm_unidadmedidas.UnidadesContenidas as Divisor
			from farm_catalogoproductos
			inner join farm_medicinavencida
			on farm_medicinavencida.IdMedicina=farm_catalogoproductos.IdMedicina
			inner join farm_unidadmedidas
			on farm_unidadmedidas.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida
			inner join farm_lotes
			on farm_lotes.IdLote=farm_medicinavencida.IdLote
			where farm_catalogoproductos.IdMedicina='$IdMedicina' 	
			and farm_medicinavencida.Existencia <> 0
			and left(FechaVencimiento,7) < left(curdate(),7)
			order by farm_lotes.FechaVencimiento";
$resp=mysql_query($querySelect);

while($Datos=mysql_fetch_array($resp)){
$Existencia=$Datos["Existencia"];
	if($Existencia != ''){
	
	$Date=explode('-',$Datos["FechaVencimiento"]);
	$Fecha=$Date[2]."-".$Date[1]."-".$Date[0];
	$Divisor=$Datos['Divisor'];
	$Script='javascript:popUp("ActualizaLotes.php?Lote='.$Datos["Lote"].'&IdMedicina='.$Datos["IdMedicina"].'")';

if($respDivisor=mysql_fetch_array(MedicamentoVencido::ValorDivisor($IdMedicina))){
		$Divisor=$respDivisor[0];
$CantidadReal=$Existencia;
		if($CantidadReal < 1){
			//Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
		   $TransformaEntero=number_format($CantidadReal*$Divisor,0,'.',',');
			$CantidadTransformada=$TransformaEntero.'/'.$Divisor;
		}else{
			//Si la cantidad es mayor a un frasco se realiza este cambio a quebrados
				
		$CantidadBase=explode('.',$CantidadReal);
		
		    $Entero=$CantidadBase[0];//Faccion ENTERA
			if(!isset($CantidadBase[1])){
			   $Decimal=0;
			}else{
			   $Decimal=$CantidadBase[1];
			}
			
		    if($Decimal==0){$Decimal="";$Quebrado="";}else{
			
			$Quebrado=number_format(($Decimal/100)*$Divisor,0,'.',',');
			$Quebrado='['.$Quebrado.'/'.$Divisor.']';
		    }

			
		$CantidadTransformada=$Entero.' '.$Quebrado;
		}
	   $CantidadIntro=$CantidadTransformada;
		
	}else{
	   $CantidadIntro=$Existencia/$Divisor;
		$CantidadIntro=number_format($CantidadIntro,2,'.',',');
	}


		$Area=MedicamentoVencido::ObtenerArea($Datos["IdArea"]);
	$data.= "Area:<strong>".$Area."</strong><br>Existencia: ".$CantidadIntro."<br>".
	"Lote: <a onclick='".$Script."'>".$Datos["Lote"]."</a><br>".
	"Vencimiento: ".$Fecha."<br><br>";
	}

}//While


$data.='~';


$data.='<select id="mes'.$IdMedicina.'" name="mes'.$IdMedicina.'" disabled=true>
	  <option value="0">[Seleccione Mes]</option>
	  <option value="01">ENERO</option>
	  <option value="02">FEBRERO</option>
	  <option value="03">MARZO</option>
	  <option value="04">ABRIL</option>
	  <option value="05">MAYO</option>
	  <option value="06">JUNIO</option>
	  <option value="07">JULIO</option>
	  <option value="08">AGOSTO</option>
	  <option value="09">SEPTIEMBRE</option>
	  <option value="10">OCTUBRE</option>
	  <option value="11">NOVIEMBRE</option>
	  <option value="12">DICIEMBRE</option>
	    </select>';
	


$data.='<select id="ano'.$IdMedicina.'" name="ano'.$IdMedicina.'" disabled=true>
<option value="0">[Seleccione A&ntilde;o]</option>';
$date=date('Y');
  $inicial=$date-5;
for($i=$inicial;$i<=$date;$i++){
	$ano=$i;
	$data.='<option value="'.$ano.'">'.$ano.'</option>';
}//fin de for
$data.='</select>';

	echo $data;

conexion::desconectar();
}
?>

</body>
</html>
