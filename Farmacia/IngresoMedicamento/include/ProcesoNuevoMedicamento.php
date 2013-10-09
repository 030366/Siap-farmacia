<?php

session_start();
include('../../Clases/class.php');
include('ClaseNuevoMedicamento.php');
$Bandera = $_GET["Bandera"];
$new = new NuevoMedicamento;

$IdModalidad=$_SESSION["IdModalidad"];

/* Bandera que determina si es introduccion de nuevo medicamento o asignacion de especialidades */
conexion::conectar();
switch ($Bandera) {
    case 1:
//SI YA ESTA EL MEDICMANETO DENTRO DE LA BASE DE DATOS
        $IdMedicina = $_GET["IdMedicina"];
        $IdHospital = $_GET["IdHospital"];

//INTRODUCCION DE MEDICAMENTO
        $codigo = $_GET["Codigo"];
        $nombre = $_GET["Nombre"];
        $grupo = $_GET["Grupo"];
        $UnidadMedida = $_GET["UnidadMedida"];
        $concentracion = $_GET["Concentracion"];
        $presentacion = $_GET["Presentacion"];
        $precio = 0;

        $tmp = explode('/', $nombre);
        $tope = sizeof($tmp);
        if ($tope != 0) {
            $Nombre1 = '';

            for ($i = 0; $i < $tope; $i++) {
                if (($tmp[$i] != NULL && $tmp[$i] != '') and ($i < $tope - 1)) {
                    $con = '+';
                } else {
                    $con = '';
                }
                $Nombre1.=$tmp[$i] . '' . $con;
            }//for
        } else {//if !=NULL
            $Nombre1 = $nombre;
        }

        $tmp22 = explode('~', $concentracion);
        $tope22 = sizeof($tmp22);
        if ($tope22 == 1) {
            $tmp2 = explode('/', $concentracion);
            $tope2 = sizeof($tmp2);
        } else {
            $tope2 = 0;
            $concentracion = $tmp22[1];
        }


        if ($tope2 != 0) {
            $concentracion1 = '';

            for ($i = 0; $i < $tope2; $i++) {
                if (($tmp2[$i] != NULL && $tmp2[$i] != '') and ($i < $tope2 - 1)) {
                    $con2 = '+';
                } else {
                    $con2 = '';
                }
                $concentracion1.=$tmp2[$i] . '' . $con2;
            }//for
        } else {//if !=NULL
            $concentracion1 = $concentracion;
        }


        if ($IdMedicina != '') {
            $resp = $new->InfoMedicina($IdMedicina);
            if ($row = mysql_fetch_array($resp)) {

                $CodigoB = $row["Codigo"];
                $PresentacionB = $row["Presentacion"];
                $ConcentracionB = $row["Concentracion"];
                $IdUnidadMedidaB = $row["IdUnidadMedida"];

                if ($CodigoB != $codigo) {
                    $resp = $new->AgregarMedicamento($codigo, $Nombre1, $concentracion1, $presentacion, $precio, $grupo, $UnidadMedida, $IdHospital, $_SESSION["IdPersonal"],$IdModalidad);

                    if ($resp != false) {
                        $IdMedicina = $resp;
                        $resp = $new->GetInformacion($IdMedicina);
                        $row = mysql_fetch_array($resp);

                        $tbl = "<input type='hidden' id='IdMedicina2' name='IdMedicina2' value='" . $IdMedicina . "'>";
                        $respMed = $new->GetInformacion($IdMedicina);
                        $row = mysql_fetch_array($respMed);
                        $tbl.="<table width='80%' border=1>";

                        $tbl.="<tr><td colspan=2 align='center'><strong>DETALLE DE MEDICAMENTO INTRODUCIDO</strong></td></tr>
				<tr><td><strong>Codigo</strong> </td><td><strong>" . $row["Codigo"] . "</strong></td></tr>
				<tr><td><strong>Nombre</strong> </td><td><div id='Nombre1'><div id='Nombre' onclick='CambiarInfo(this.id,$IdMedicina);'>" . $row["Nombre"] . "</div></div></td></tr>
			   	<tr><td><strong>Unidad Medida</strong> </td><td><div id='IdUnidadMedida1'><div id='IdUnidadMedida' onclick='CambiarInfo(this.id,$IdMedicina);'>" . $row["Descripcion"] . "</div></div></td></tr>
				<tr><td><strong>Grupo Terapeutico </strong></td><td><div id='IdTerapeutico1'><div id='IdTerapeutico' onclick='CambiarInfo(this.id,$IdMedicina);'>" . $row["GrupoTerapeutico"] . "</div></div></td></tr>
				<tr><td><strong>Concentracion </strong></td><td><div id='Concentracion1'><div id='Concentracion' onclick='CambiarInfo(this.id,$IdMedicina);'>" . $row["Concentracion"] . "</div></div></td></tr>
				<tr><td><strong>Presentacion </strong></td><td><div id='Presentacion1'><div id='Presentacion' onclick='CambiarInfo(this.id,$IdMedicina);'>" . $row["Presentacion"] . "</div></div></td></tr>
				<tr><td align='right' colspan=2><input type='button' id='New' value='Ingresar Nuevo' onclick='window.location=window.location;'></td></tr>
			</table>";
                        echo $tbl;
                    } else {
                        echo " Ya existe el Codido: " . $codigo . ", esta siendo utilizado por otro medicamento<br>
		<input type='button' id='aceptar' name='aceptar' value='Aceptar' onclick='habilitar()'>";
                    }
                } else {

                    $resp = $new->ActualizarEstadoMedicamento($IdMedicina, $codigo, $Nombre1, $concentracion1, $presentacion, $precio, $grupo, $UnidadMedida, $IdHospital, $_SESSION["IdPersonal"],$IdModalidad);
                    $tbl = "<input type='hidden' id='IdMedicina2' name='IdMedicina2' value='" . $IdMedicina . "'>";
                    $respMed = $new->GetInformacion($IdMedicina);
                    $row = mysql_fetch_array($respMed);
                    $tbl.="<table width='80%' border=1>";

                    $tbl.="<tr><td colspan=2 align='center'><strong>DETALLE DE MEDICAMENTO INTRODUCIDO</strong></td></tr>
				<tr><td><strong>Codigo</strong> </td><td><strong>" . $row["Codigo"] . "</strong></td></tr>
				<tr><td><strong>Nombre</strong> </td><td><div id='Nombre1'><div id='Nombre' onclick='CambiarInfo(this.id,$IdMedicina);'>" . $row["Nombre"] . "</div></div></td></tr>
			   	<tr><td><strong>Unidad Medida</strong> </td><td><div id='IdUnidadMedida1'><div id='IdUnidadMedida' onclick='CambiarInfo(this.id,$IdMedicina);'>" . $row["Descripcion"] . "</div></div></td></tr>
				<tr><td><strong>Grupo Terapeutico </strong></td><td><div id='IdTerapeutico1'><div id='IdTerapeutico' onclick='CambiarInfo(this.id,$IdMedicina);'>" . $row["GrupoTerapeutico"] . "</div></div></td></tr>
				<tr><td><strong>Concentracion </strong></td><td><div id='Concentracion1'><div id='Concentracion' onclick='CambiarInfo(this.id,$IdMedicina);'>" . $row["Concentracion"] . "</div></div></td></tr>
				<tr><td><strong>Presentacion </strong></td><td><div id='Presentacion1'><div id='Presentacion' onclick='CambiarInfo(this.id,$IdMedicina);'>" . $row["Presentacion"] . "</div></div></td></tr>
				<tr><td align='right' colspan=2><input type='button' id='New' value='Ingresar Nuevo' onclick='window.location=window.location;'></td></tr>
			</table>";
                    echo $tbl;
                }
            }
        } else {

            $resp = $new->AgregarMedicamento($codigo, $Nombre1, $concentracion1, $presentacion, $precio, $grupo, $UnidadMedida, $IdHospital, $_SESSION["IdPersonal"],$IdModalidad);
            if ($resp != false) {
                $IdMedicina = $resp;
                $tbl = "<input type='hidden' id='IdMedicina2' name='IdMedicina2' value='" . $IdMedicina . "'>";
                $respMed = $new->GetInformacion($IdMedicina);
                $row = mysql_fetch_array($respMed);
                $tbl.="<table width='80%' border=1>";

                $tbl.="<tr><td colspan=2 align='center'><strong>DETALLE DE MEDICAMENTO INTRODUCIDO</strong></td></tr>
				<tr><td><strong>Codigo</strong> </td><td><strong>" . $row["Codigo"] . "</strong></td></tr>
				<tr><td><strong>Nombre</strong> </td><td><div id='Nombre1'><div id='Nombre' onclick='CambiarInfo(this.id,$IdMedicina);'>" . $row["Nombre"] . "</div></div></td></tr>
			   	<tr><td><strong>Unidad Medida</strong> </td><td><div id='IdUnidadMedida1'><div id='IdUnidadMedida' onclick='CambiarInfo(this.id,$IdMedicina);'>" . $row["Descripcion"] . "</div></div></td></tr>
				<tr><td><strong>Grupo Terapeutico </strong></td><td><div id='IdTerapeutico1'><div id='IdTerapeutico' onclick='CambiarInfo(this.id,$IdMedicina);'>" . $row["GrupoTerapeutico"] . "</div></div></td></tr>
				<tr><td><strong>Concentracion </strong></td><td><div id='Concentracion1'><div id='Concentracion' onclick='CambiarInfo(this.id,$IdMedicina);'>" . $row["Concentracion"] . "</div></div></td></tr>
				<tr><td><strong>Presentacion </strong></td><td><div id='Presentacion1'><div id='Presentacion' onclick='CambiarInfo(this.id,$IdMedicina);'>" . $row["Presentacion"] . "</div></div></td></tr>
				<tr><td align='right' colspan=2><input type='button' id='New' value='Ingresar Nuevo' onclick='window.location=window.location;'></td></tr>
			</table>";
                echo $tbl;
            } else {
                echo " Ya existe el Codido: <h2><strong>" . $codigo . "</strong></h2>, esta siendo utilizado por otro medicamento<br>
	<input type='button' id='aceptar' name='aceptar' value='Aceptar' onclick='habilitar()'>";
            }
        }
        break;

    case 2:

//ASIGNACION DE ESPECIALIDADES
        $IdMedicina = $_GET["IdMedicina"];
        $Especialidad = $_GET["Especialidad"];
        echo "<input type='hidden' id='IdMedicina2' name='IdMedicina2' value='" . $IdMedicina . "'>";
        $Nombre = $new->GetName($IdMedicina);
        if ($Especialidad == 0) {
            echo $Nombre . " - CON - TODAS LAS ESPECIALIDADES";
        } else {
            $NombreEspecialidad = $new->GetEspecialidad($Especialidad);
            echo $Nombre . " - CON - " . $NombreEspecialidad;
        }
        break;

    case 3:
        $IdMedicina = $_GET["IdMedicina"];
        $IdArea = $_GET["IdArea"];
        $queryInsert = "insert into mnt_areamedicina (IdArea,IdMedicina) values('$IdArea','$IdMedicina')";
        mysql_query($queryInsert);
        $querySelect = "select IdAreaMedicina 
			from mnt_areamedicina
			order by IdAreaMedicina desc
			limit 1";
        $resp = mysql_fetch_array(mysql_query($querySelect));
        echo 'Area Asignada<br><input type="hidden" id="IdAreaMedicina" name="IdAreaMedicina" value="' . $resp[0] . '">';
        break;

    case 4:
        $IdMedicina = $_GET["IdMedicina"];
        $IdArea = $_GET["IdArea"];
        $IdAreaMedicina = $_GET["IdAreaMedicina"];

        $queryUpdate = "update mnt_areamedicina set Dispensada='$IdArea' where IdAreaMedicina='$IdAreaMedicina'";
        mysql_query($queryUpdate);
        echo "OK";
        break;

    case 5:
//combo
        $IdMedicina = $_GET["IdMedicina"];
        $IdUnidadMedida = $_GET["IdUnidadMedida"];
        $Descripcion = $_GET["Descripcion"];

        $comboUnidadMedida = '<select id="UnidadMedida" name="UnidadMedida">
	  <option value="' . $IdUnidadMedida . '">' . $Descripcion . '</option>';

        $resp2 = mysql_query("select * from farm_unidadmedidas where (IdUnidadMedida=1 or IdUnidadMedida=2 or IdUnidadMedida=7 or IdUnidadMedida = 17) and IdUnidadMedida <> " . $IdUnidadMedida);

        while ($row2 = mysql_fetch_array($resp2)) {
            $comboUnidadMedida.='<option value="' . $row2[0] . '">' . $row2[1] . '</option>';
        }
        $comboUnidadMedida.='</select>';

        echo $comboUnidadMedida;

        break;
    case 6:
        //monstrar opciones de cambios
        $IdMedicina = $_GET["IdMedicina"];
        switch ($_GET["SubBandera"]) {
            case 'Nombre':
                $resp = $new->GetInformacionMedicina($_GET["SubBandera"], $IdMedicina);
                $Nombre = mysql_fetch_array($resp);
                echo "<input type='text' id='NombreNuevo' name='NombreNuevo' size='70' value='" . $Nombre[0] . "' onblur='MakeChange(this.value," . $IdMedicina . ",\"" . $_GET["SubBandera"] . "\");'>";
                break;
            case 'IdUnidadMedida':
                $resp = $new->GetInformacionMedicina($_GET["SubBandera"], $_GET["IdMedicina"]);
                $campo = "fcp." . $_GET["SubBandera"];
                $resp = $new->GetInformacionMedicina($campo, $_GET["IdMedicina"]);
                $IdUnidadMedida = mysql_fetch_array($resp);
                //construccion de combo
                echo $new->ComboMedida($IdUnidadMedida[0], $IdMedicina, $_GET["SubBandera"]);
                break;
            case 'IdTerapeutico':
                $campo = "fcp." . $_GET["SubBandera"];
                $resp = $new->GetInformacionMedicina($campo, $_GET["IdMedicina"]);
                $IdTerapeutico = mysql_fetch_array($resp);
                //construccion de combo
                echo $new->ComboTerapeutico($IdTerapeutico[0], $IdMedicina, $_GET["SubBandera"]);
                break;
            case 'Concentracion':
                $resp = $new->GetInformacionMedicina($_GET["SubBandera"], $IdMedicina);
                $Nombre = mysql_fetch_array($resp);
                echo "<input type='text' id='ConcentracionNuevo' name='ConcentracionNuevo' value='" . $Nombre[0] . "' onblur='MakeChange(this.value," . $IdMedicina . ",\"" . $_GET["SubBandera"] . "\");'>";
                break;
            case 'Presentacion':
                $resp = $new->GetInformacionMedicina($_GET["SubBandera"], $IdMedicina);
                $Nombre = mysql_fetch_array($resp);
                echo "<input type='text' id='PresentacionNuevo' name='PresentacionNuevo' value='" . $Nombre[0] . "' onblur='MakeChange(this.value," . $IdMedicina . ",\"" . $_GET["SubBandera"] . "\");'>";
                break;
            case 'Nombre1':
                $NuevaInfo = $_GET["NuevaInfo"];

                $tmp = explode('/', $NuevaInfo);
                $tope = sizeof($tmp);
                if ($tope != 0) {
                    $NuevaInfo2 = '';

                    for ($i = 0; $i < $tope; $i++) {
                        if (($tmp[$i] != NULL && $tmp[$i] != '') and ($i < $tope - 1)) {
                            $con = '+';
                        } else {
                            $con = '';
                        }
                        $NuevaInfo2.=$tmp[$i] . '' . $con;
                    }//for
                } else {//if !=NULL
                    $NuevaInfo2 = $NuevaInfo;
                }

                $new->ActualizarInformacion($IdMedicina, $_GET["campo"], $NuevaInfo2);
                $resp = $new->GetInformacionMedicina($_GET["campo"], $IdMedicina);
                $row = mysql_fetch_array($resp);
                echo "<div id='Nombre' onclick='CambiarInfo(this.id,$IdMedicina);'>" . $row["Nombre"] . "</div>";
                break;
            case 'IdUnidadMedida1':
                $NuevaInfo = $_GET["NuevaInfo"];
                $new->ActualizarInformacion($IdMedicina, $_GET["campo"], $NuevaInfo);
                $resp = $new->GetInformacionMedicina("Descripcion", $IdMedicina);
                $row = mysql_fetch_array($resp);
                echo "<div id='IdUnidadMedida' onclick='CambiarInfo(this.id,$IdMedicina);'>" . $row["Descripcion"] . "</div>";
                break;
            case 'IdTerapeutico1':
                $NuevaInfo = $_GET["NuevaInfo"];
                $new->ActualizarInformacion($IdMedicina, $_GET["campo"], $NuevaInfo);
                $resp = $new->GetInformacionMedicina("GrupoTerapeutico", $IdMedicina);
                $row = mysql_fetch_array($resp);
                echo "<div id='IdTerapeutico' onclick='CambiarInfo(this.id,$IdMedicina);'>" . $row["GrupoTerapeutico"] . "</div>";
                break;
            case 'Concentracion1':
                $NuevaInfo = $_GET["NuevaInfo"];


                $tmp22 = explode('~', $NuevaInfo);
                $tope22 = sizeof($tmp22);
                if ($tope22 == 1) {
                    $tmp = explode('/', $NuevaInfo);
                    $tope = sizeof($tmp);
                } else {
                    $tope = 0;
                    $NuevaInfo = $tmp22[1];
                }



                if ($tope != 0) {
                    $NuevaInfo2 = '';

                    for ($i = 0; $i < $tope; $i++) {
                        if (($tmp[$i] != NULL && $tmp[$i] != '') and ($i < $tope - 1)) {
                            $con = '+';
                        } else {
                            $con = '';
                        }
                        $NuevaInfo2.=$tmp[$i] . '' . $con;
                    }//for
                } else {//if !=NULL
                    $NuevaInfo2 = $NuevaInfo;
                }
                $new->ActualizarInformacion($IdMedicina, $_GET["campo"], $NuevaInfo2);
                $resp = $new->GetInformacionMedicina($_GET["campo"], $IdMedicina);
                $row = mysql_fetch_array($resp);
                echo "<div id='Concentracion' onclick='CambiarInfo(this.id,$IdMedicina);'>" . $row["Concentracion"] . "</div>";
                break;
            case 'Presentacion1':
                $NuevaInfo = $_GET["NuevaInfo"];
                $new->ActualizarInformacion($IdMedicina, $_GET["campo"], $NuevaInfo);
                $resp = $new->GetInformacionMedicina($_GET["campo"], $IdMedicina);
                $row = mysql_fetch_array($resp);
                echo "<div id='Presentacion' onclick='CambiarInfo(this.id,$IdMedicina);'>" . $row["Presentacion"] . "</div>";
                break;
        }


        break;
}//switch
conexion::desconectar();
?>