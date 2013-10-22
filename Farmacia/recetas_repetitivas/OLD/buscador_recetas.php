<?php include('../Titulo/Titulo.php');
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
    //fecha de vida de una receta son 3 dias habiles
$query = mysql_query($sqlStr.$limit, $link);
$aux = Mysql_Fetch_Assoc(mysql_query($sqlStrAux,$link));
?>
<html>
<head>
<?php head(); ?>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<title>...:::BUSQUEDA DE RECETAS:::...</title>
<link rel="stylesheet" href="pagination.css" media="screen">
<link rel="stylesheet" href="style.css" media="screen">
<script src="include/buscador.js" type="text/javascript" language="javascript"></script>

<script language="javascript">
function inicio(){
document.form.q.focus();
}//inicio

</script>
</head>

<body onLoad="inicio()">
	<?php Menu(); ?>
<br>
	<form name="form" action="buscador_terapeutico.php" onSubmit="return buscar()">
    
	 <table width="792">
	  <tr>
	   <td width="380" align="center"><strong>Busqueda de Recetas Repetitivas Listas </strong></td>
	   </tr>
	 <tr>
	 <td><strong>No. EXPEDIENTE:</strong> 
	   <input type="text" id="q" name="q" value="<?php if(isset($q)) echo $busqueda;?>" onKeyUp="return buscar()" style="border-bottom-color:#000099; border-top-color:#000099; border-left-color:#000099; border-right-color:#000099" size="50">
		
      &nbsp;&nbsp;<input type="button" value="Buscar" id="boton" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099">
      <span id="loading"></span></td>
	 </tr>
	</table>
	
    </form>
    
<div id="resultados" align="center">
	<?php
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

echo "<tr class=\"titulos\"><td>No. EXPEDIENTE</td><td>NOMBRE PACIENTE</td><td>ENTREGADA</td><td>ERROR DE IMPRESION</td></tr>\n";
//<td>NO ENTREGADA</td>

echo "\t\t<tr class=\"row$r\"><td align=\"center\"><a href=\"#\">".htmlentities($row['IdNumeroExp'])."</a></td><td align=\"left\"><a href=\"#\">".strtoupper (htmlentities($row['NOMBRE']))."</a></td><td align=\"center\"><a href=\"proceso_entrega.php?Entregada=1&p={$row['IdReceta']}&page=$page\" target=\"_self\">"." ENTREGADA "."</a></td><td align='center'><a href=\"proceso_entrega.php?Entregada=9&p={$row['IdReceta']}&page=$page\" target=\"_self\">"." VOLVER A IMPRIMIR "."</a></td></tr>";
//<td align=\"center\"><a href=\"proceso_entrega.php?Entregada=2&p={$row['IdReceta']}&page=$page\" target=\"_self\">"." NO ENTREGADA "."</a></td>

$fecha=explode('-',$row["Fecha"]);
$fecha=$fecha[2].'-'.$fecha[1].'-'.$fecha[0];
echo '<tr><td align="center" colspan="5">
<table width="827" boder="1">
<tr><td align="center" colspan="6"><strong>RECETA A ENTREGAR</</td></tr>
<tr><td align="center" colspan="6"><strong>RECETA NUMERO:  '.$row["NumeroReceta"].',  PREPARADA:  '.$fecha.'</strong></td></tr>
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




echo "<tr><td colspan='4'>&nbsp;</td></tr>";

	}//if

          if($r%2==0)++$r;else--$r;
        }
			echo "\t</table>\n";
			$p->show();
		}
	?>
</div>

</body>
</html>
<?php 

}//Fin de IF isset de Nivel ?>
