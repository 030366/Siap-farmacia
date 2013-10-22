<?php session_start();
if(!isset($_SESSION["nivel"])){?>
<script language="javascript">
window.location='../signIn.php';
</script>
<?php
}else{
require('../Clases/class.php');
$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
$IdArea=$_SESSION["IdArea"];

require('include/conexion.php');
require('include/funciones.php');
require('include/pagination.class.php');
require('include/RecetasClass.php');
$FechaD=new ClassFechaAtras;
$Classquery=new Classquery;
//****obtencion de fechas validas de recetas (3 dias habiles)

$selectNombreFecha="select dayname(curdate()) as NombreFechaActual";
$NombreDiaActual = mysql_query($selectNombreFecha, $link);
$rowNombre=mysql_fetch_array($NombreDiaActual);
$NombreFecha=$rowNombre["NombreFechaActual"];
$FechaAtras=$FechaD->ObtenerFechaAtras($NombreFecha,$link);
$FechaAdelante=$FechaD->Adelante($NombreFecha,$link);
//***


$items = 10;
$page = 1;

if(isset($_GET['page']) and is_numeric($_GET['page']) and $page = $_GET['page'])
		$limit = " LIMIT ".(($page-1)*$items).",$items";
	else
		$limit = " LIMIT $items";

if(isset($_GET['q']) and !eregi('^ *$',$_GET['q'])){
		$q = sql_quote($_GET['q']); //para ejecutar consulta
		$busqueda = htmlentities($q); //para mostrar en pantalla
		$Bandera=1;
			
//and month(farm_recetas.Fecha)=month(CURDATE())  Esta sentencia va si las recetas de un mes no pueden dar en otro mes
//a pesar que la vida de una receta sean 3 dias...Ej. 29/02/2008 --->  01/03/2008
$sqlStr=$Classquery->ObtenerQuery($Bandera,$IdArea,$FechaAtras,$FechaAdelante,$q);
$sqlStrAux=$Classquery->ObtenerQueryTotal($Bandera,$IdArea,$FechaAtras,$FechaAdelante,$q);
}else{
$Bandera=0;
$sqlStr=$Classquery->ObtenerQuery($Bandera,$IdArea,$FechaAtras,$FechaAdelante,"");
$sqlStrAux=$Classquery->ObtenerQueryTotal($Bandera,$IdArea,$FechaAtras,$FechaAdelante,"");
}
$aux = Mysql_Fetch_Assoc(mysql_query($sqlStrAux,$link));
$query = mysql_query($sqlStr.$limit, $link);
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<title>...:::BUSQUEDA DE RECETAS:::...</title>
<link rel="stylesheet" href="pagination.css" media="screen">
<link rel="stylesheet" href="style.css" media="screen">
<script src="include/buscador.js" type="text/javascript" language="javascript"></script>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	left:60px;
	top:224px;
	width:826px;
	height:33px;
	z-index:1;
}

#resultados{
	position:absolute;
	left:44px;
	top:286px;
	width:854px;
	height:156px;
	z-index:2;
}
#Layer2 {
	position:absolute;
	left:400px;
	top:564px;
	width:58px;
	height:31px;
	z-index:3;
}
#Layer6 {	position:absolute;
	left:25px;
	top:105px;
	width:955px;
	height:30px;
	z-index:2;
}
.style1 {color:#0000CC; font-size:11px; font-family:Arial, Helvetica, sans-serif}
.style4 {font-size: 24px}
#Layer4 {position:absolute;
	left:-199px;
	top:-39px;
	width:55px;
	height:31px;
	z-index:7;
}
#Layer7 {position:absolute;
	left:303px;
	top:39px;
	width:596px;
	height:23px;
	z-index:5;
}
#Layer3 {position:absolute;
	left:-1px;
	top:173px;
	width:836px;
	height:34px;
	z-index:6;
}
-->
</style>
<script language="javascript">
function inicio(){
document.form.q.focus();
}//inicio

</script>
</head>

<body onLoad="inicio()">
	
	<form name="form" action="buscador_terapeutico.php" onSubmit="return buscar()">
      <div id="Layer1">
	 <table width="751">
	 <tr>
	   <td width="380" align="center"><strong>Busqueda de Recetas No Entregadas </strong></td>
	   </tr>
	 <tr>
	   <td><strong>No. EXPEDIENTE:</strong>
	     <input type="text" id="q" name="q" value="<?php if(isset($q)) echo $busqueda;?>" onKeyUp="return buscar()" style="border-bottom-color:#000099; border-top-color:#000099; border-left-color:#000099; border-right-color:#000099" size="50">
	     
	     &nbsp;&nbsp;<input type="button" value="Buscar" id="boton" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099">
	     <span id="loading"></span>	</td></tr>
	</table>
	  </div>
    </form>
    
    <div id="resultados" align="center">
	<p><?php
		if($aux['total'] and isset($busqueda)){
				//echo "{$aux['total']} Resultado".($aux['total']>1?'s':'')." que coinciden con tu b&uacute;squeda \"<strong>$busqueda</strong>\".";
echo "Resultados que coinciden con tu b&uacute;squeda \"<strong>$busqueda</strong>\".";
			}elseif($aux['total'] and !isset($q)){
				//echo "Total de registros: {$aux['total']}";
			}elseif(!$aux['total'] and isset($q)){
				echo"No hay registros que coincidan con tu b&uacute;squeda \"<strong>$busqueda</strong>\"";
			}
	?><br>

	<?php 
		if($aux['total']>0){
			$p = new pagination;
			$p->Items($aux['total']);
			$p->limit($items);
			if(isset($q))
					$p->target("buscador_recetas.php?q=".urlencode($q));
				else
					$p->target("buscador_recetas.php");
			$p->currentPage($page);
			$p->show();
			echo "\t<table class=\"registros\">\n";
			$r=0;
			while($row = mysql_fetch_assoc($query)){
		if(isset($page)){
	$Id=$row["IdReceta"]; 
	
echo "<tr class=\"titulos\"><td>TIPO RECETA</td><td>No. EXPEDIENTE</td><td>NOMBRE PACIENTE</td><td>FECHA DE PREPARACION</td></tr>\n";			$r=0;
	
	if($row["IdEstado"]=='RL' || $row["IdEstado"]=='RN'){$Rep=" REPETITIVA";}else{$Rep="DEL DIA";}
echo "\t\t<tr class=\"row$r\"><td align=\"center\"><a href=\"#\">".$Rep."</a></td><td align=\"center\"><a href=\"#\">".htmlentities($row['IdNumeroExp'])."</a></td><td align=\"center\"><a href=\"#\">".strtoupper (htmlentities($row['NOMBRE']))."</a></td><td align=\"center\"><a href=\"#\">".$row["FechaPreparada"]."</a></td><td align=\"center\"></tr>";

echo '<tr><td align="center" colspan="4">
<table width="827" boder="1">
<tr><td align="center" colspan="6"><strong>RECETA PREPARADA Y NO ENTREGADA</</td></tr>
<tr class="MYTABLE"><td align="center">CANTIDAD</td><td align="center">MEDICAMENTO</td><td align="center">CONCENTRACION</td><td align="center">PRESENTACION</td><td align="center">DOSIS</td><td align="center">SATISFECHO</td></tr>';
$DetalleReceta=ClassQuery::MedicinaReceta($Id,$IdArea);
$DetalleReceta=mysql_query($DetalleReceta,$link);
while($rowDetalle=mysql_fetch_array($DetalleReceta)){
$Cantidad=$rowDetalle["Cantidad"];$Nombre=$rowDetalle["Nombre"];$Concentracion=$rowDetalle["Concentracion"];$Presentacion=$rowDetalle["FormaFarmaceutica"];
$Dosis=$rowDetalle["Dosis"];$IdEstado=$rowDetalle["IdEstado"];
if($IdEstado=='' || $IdEstado=='S'){$Satisfecho="SI";$Colore="";}else{$Satisfecho="NO";$Colore='style="background-color:#FF6633"';}

echo '<tr '.$Colore.' class="FONDO2"><td align="center">'.$Cantidad.'</td><td align="center">'.$Nombre.'</td><td align="center">'.$Concentracion.'</td><td align="center">'.$Presentacion.'</td><td>'.$Dosis.'</td><td align="center">'.$Satisfecho.'</td></tr>';


}//while
echo '</table>';
echo '</td></tr>';


	}//if
          if($r%2==0)++$r;else--$r;
        }
			echo "\t</table>\n";
			$p->show();
		}
	?>
</div>
<div class="style1" id="Layer6" >
  <?php
$NombreDeFarmacia=$_SESSION["IdFarmacia2"];
encabezado::top($NombreDeFarmacia,$tipoUsuario,$nick,$nombre);

?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </div>
<div id="Layer7">
  <div id="Layer4"><img src="../images/paisanito.jpg" alt="" width="195" height="94" /></div>
  <span class="style4">Ministerio de Salud P&uacute;blica y Asistencia Social </span></div>
<div id="Layer3">
  <script webstyle4>document.write('<scr'+'ipt src="../xaramenu.js">'+'</scr'+'ipt>');document.write('<scr'+'ipt src="../MenuImages/menuconsultaexterna.js">'+'</scr'+'ipt>');/*img src="MenuImages/MenuConsultaExterna.gif" moduleid="MenuConExt (Project)\MenuConsultaExterna_off.xws"*/</script>
</div>
</body>
</html>
<?php 

}//Fin de IF isset de Nivel ?>
