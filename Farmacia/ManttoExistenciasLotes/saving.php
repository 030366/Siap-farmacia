<?php
session_start();
require('../Clases/class2.php');
$query = new queries;

function ValorDivisor($IdMedicina, $IdEstablecimiento, $IdModalidad) {
    $SQL = "select DivisorMedicina from farm_divisores 
                 where IdMedicina=" . $IdMedicina . " and IdEstablecimiento=" . $IdEstablecimiento . " 
                     and IdModalidad=$IdModalidad";
    $resp = mysql_query($SQL);
    return($resp);
}
?>
<html>
    <head>
        <title>saving...</title>
    </head>
    <body>
        <?php
        $IdModalidad = $_SESSION["IdModalidad"];

        if (isset($_GET["Bandera"])) {
            $bandera = $_GET["Bandera"];
        } else {
            $bandera = 1;
        }
        if (isset($_GET["IdMedicina"])) {
            $IdMedicina = $_GET["IdMedicina"];
        } else {
            $IdMedicina = 0;
        }

        if ($bandera == 2) {//comprobacion de fechas
            $TestFecha = $_GET["TestFecha"];
            $querySelect = "select left('$TestFecha',7)<left(curdate(),7)";
            $resp = mysql_fetch_array(mysql_query($querySelect));
            if ($resp != 1) {
                echo "SI";
            } else {
                echo "NO";
            }
        } elseif ($bandera != 0) {

            if ($IdMedicina == 0) {

                echo "<center>GUARDANDO EXISTENCIAS....<BR><BR><img src='../images/barra.gif'></center>";

                conexion::conectar();
                /* INTRODUCCION DE EXISTENCIAS POR POST DE FORMULARIO */

                $IdTerapeuticoCombo = $_POST["Terapeutico"];

                $querySelect = "select farm_catalogoproductos.IdMedicina,Codigo ,
                      farm_catalogoproductos.Nombre,farm_catalogoproductos.FormaFarmaceutica,
	farm_catalogoproductos.Concentracion
	from farm_catalogoproductos
	inner join farm_catalogoproductosxestablecimiento cpe
	on cpe.IdMedicina=farm_catalogoproductos.IdMedicina
	
	where cpe.Condicion='H'
	and cpe.IdEstablecimiento=" . $_SESSION["IdEstablecimiento"] . "
        and cpe.IdModalidad=$IdModalidad
	and IdTerapeutico=" . $IdTerapeuticoCombo;
                $resp = mysql_query($querySelect);

                while ($Datos = mysql_fetch_array($resp)) {
                    $IdMedicina = $Datos["IdMedicina"];
                    $Lote_ = "Lote" . $IdMedicina;
                    /**/
                    $mes = "mes" . $IdMedicina;
                    $ano = "ano" . $IdMedicina;
                    if (isset($_POST[$mes])) {
                        $mes = $_POST[$mes];
                    } else {
                        $mes = 0;
                    }
                    if (isset($_POST[$ano])) {
                        $ano = $_POST[$ano];
                    } else {
                        $ano = 0;
                    }

                    if ($mes != 0 and $ano != 0) {
                        $Vencimiento = $ano . "-" . $mes . "-" . "25";
                    } else {
                        $Vencimiento = 'Fecha Ventto.';
                    }
                    /**/
                    $Precio_ = "Precio" . $IdMedicina;
                    /* Obtencion del post de los datos */
                    $NuevaExistencia = $_POST[$IdMedicina];

                    $Lote = $_POST[$Lote_];
                    $Lote = strtoupper($Lote);
                    if (isset($_POST[$Precio_])) {
                        $Precio = $_POST[$Precio_];
                    } else {
                        $Precio = 0;
                    }

                    /* Fin de POST */
                    if ($NuevaExistencia != '0' and $Lote != 'Lote.') {
                        $query->AumentaExistencias($IdMedicina, $NuevaExistencia, $Vencimiento, $Lote, 
                                                   $Precio, $_SESSION["IdEstablecimiento"],$IdModalidad);
                    }
                }
                conexion::desconectar();
                ?>
                <script language="javascript">
                    window.location='existencia.php?IdTerapeutico=<?php echo $IdTerapeuticoCombo; ?>';
                </script>
                <?php
                /* FIN DE DATOS ENVIAMOS POR POST */
            } else {
                /* INGRESO DE EXISTENCIA UTILIZANDO EL AJAX */

                conexion::conectar();
                $vencimiento = $_GET["FechaVentto"]; //Informaci�n de Fecha.-
                $NuevaExistencia = $_GET["Existencia"];
                $Lote = $_GET["Lote"];
                $Lote = strtoupper($Lote);
                $Precio = $_GET["PrecioLote"];
                if ($NuevaExistencia != '0') {
                    $query->AumentaExistencias($IdMedicina, $NuevaExistencia, $vencimiento, $Lote, 
                                               $Precio, $_SESSION["IdEstablecimiento"],$IdModalidad);
                }
                conexion::desconectar();
            }//else 
        } else {
            /* REFRESCAMIENTO DE LA EXISTENCIAS DESPUES DE UTILIZAR AJAX */
            conexion::conectar();
            $data = '';
            $querySelect = " select farm_catalogoproductos.IdMedicina,farm_catalogoproductos.Nombre,farm_catalogoproductos.FormaFarmaceutica,
			farm_catalogoproductos.Concentracion, farm_entregamedicamento.*,farm_lotes.*,
			farm_unidadmedidas.Descripcion,farm_unidadmedidas.UnidadesContenidas as Divisor
			from farm_catalogoproductos
			inner join farm_entregamedicamento
			on farm_entregamedicamento.IdMedicina=farm_catalogoproductos.IdMedicina
			inner join farm_unidadmedidas
			on farm_unidadmedidas.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida
			inner join farm_lotes
			on farm_lotes.IdLote=farm_entregamedicamento.IdLote
			where farm_catalogoproductos.IdMedicina='$IdMedicina' 
			and farm_entregamedicamento.Existencia <> 0
                        and farm_entregamedicamento.IdEstablecimiento=" . $_SESSION["IdEstablecimiento"] . "
                        and farm_entregamedicamento.IdModalidad=$IdModalidad
			and left(FechaVencimiento,7) > left(curdate(),7)
			order by farm_lotes.FechaVencimiento";
            $resp = mysql_query($querySelect);

            while ($Datos = mysql_fetch_array($resp)) {
                $Existencia = $Datos["Existencia"];
                if ($Existencia != '') {

                    $Date = explode('-', $Datos["FechaVencimiento"]);
                    $Fecha = $Date[2] . "-" . $Date[1] . "-" . $Date[0];
                    $Divisor = $Datos['Divisor'];
                    $Script = 'javascript:popUp("ActualizaLotes.php?Lote=' . $Datos["Lote"] . '&IdMedicina=' . $Datos["IdMedicina"] . '")';

                    if ($respDivisor = mysql_fetch_array(ValorDivisor($IdMedicina, $_SESSION["IdEstablecimiento"],$IdModalidad))) {
                        $Divisor = $respDivisor[0];

                        if ($Existencia < 1) {
                            //Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
                            $TransformaEntero = number_format($Existencia * $Divisor, 0, '.', ',');
                            $CantidadTransformada = $TransformaEntero . '/' . $Divisor;
                        } else {
                            //Si la cantidad es mayor a un frasco se realiza este cambio a quebrados
                            $CantidadReal = number_format($Existencia, 2, '.', ',');
                            $CantidadBase = explode('.', $CantidadReal);

                            $Entero = $CantidadBase[0]; //Faccion ENTERA

                            $Decimal = $CantidadBase[1];
                            if ($Decimal == 0) {
                                $Decimal = "";
                                $Quebrado = "";
                            } else {

                                $Quebrado = number_format(($Decimal / 100) * $Divisor, 0, '.', ',');
                                $Quebrado = '[' . $Quebrado . '/' . $Divisor . ']';
                            }


                            $CantidadTransformada = $Entero . ' ' . $Quebrado;
                        }
                        $CantidadIntro = $CantidadTransformada;
                    } else {
                        $CantidadIntro = $Existencia / $Divisor;
                    }


                    $data.= "Existencia: " . $CantidadIntro . "<br>" .
                            "Lote: <a onclick='" . $Script . "'>" . $Datos["Lote"] . "</a><br>" .
                            "Vencimiento: " . $Fecha . "<br><br>";
                }
            }//While


            $data.='~';


            $data.='<select id="mes' . $IdMedicina . '" name="mes' . $IdMedicina . '" disabled=true>
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

            $data.='<select id="ano' . $IdMedicina . '" name="ano' . $IdMedicina . '" disabled=true>
<option value="0">[Seleccione A&ntilde;o]</option>';
            $date = date("Y");

            for ($i = 0; $i <= 12; $i++) {
                $ano = $date + $i;
                $data.='<option value="' . $ano . '">' . $ano . '</option>';
            }//fin de for
            $data.='</select>';

            echo $data;

            conexion::desconectar();
        }
        ?>

    </body>
</html>
