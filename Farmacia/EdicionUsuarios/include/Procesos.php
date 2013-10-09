<?php

session_start();
if (isset($_SESSION["nivel"])) {
    include('Clases.php');
    $proceso = new admon;
    conexion::conectar();

    $IdModalidad = $_SESSION["IdModalidad"];
    $IdEstablecimiento=$_SESSION["IdEstablecimiento"];

    switch ($_GET["Bandera"]) {
        case 1:
            //Obtencion de informacion general
            $resp = $proceso->InformacionGral($_GET["IdPersonal"]);
            if ($row = mysql_fetch_array($resp)) {
                if ($row["IdEstadoCuenta"] == 'H') {
                    $checkHabi = "checked='true'";
                } else {
                    $checkHabi = "";
                }

                $out = "<table width='70%'>
		<tr class='MYTABLE'><td colspan='2' align='center'><h2><strong>" . htmlentities($row["Nombre"]) . " <small><i>[" . $row["nick"] . "]</i></small></strong></h2></td></tr>
		<tr class='FONDO2'><td colspan='2' align='center'><strong>Nivel:</strong> <span id='TipoNivel'><u><a onclick='CambioNivel(" . $row["Nivel"] . ");'>" . $row["TipoNivel"] . "</a></u></span></td></tr>
		<tr class='FONDO2'><td colspan='2' align='center'><input type='checkbox' id='EstadoCuenta' " . $checkHabi . " onclick='CambiarEstado();'> <strong>Cuenta Habilitada</td></tr>
		<tr class='FONDO2'><th>Permisos</th><th>Ubicacion</th></tr>";

                //*******
                if ($row["Datos"] == 1) {
                    $datos = 'checked="true"';
                } else {
                    $datos = '';
                }
                if ($row["Reportes"] == 1) {
                    $reportes = 'checked="true"';
                } else {
                    $reportes = '';
                }
                if ($row["Administracion"] == 1) {
                    $administracion = 'checked="true"';
                } else {
                    $administracion = '';
                }

                $out.="<tr class='FONDO2'><td align='center'>
<table>
<tr ><td><strong>Administracion: </strong></td><td><input type=\"checkbox\" id=\"Administracion\" name=\"administracion\" value=\"1\" onclick=\"CambiarPermisos(this.id);\" " . $administracion . "> </td></tr>
<tr><td><strong>Reportes: </strong></td><td><input type='checkbox' id='Reportes' value='1' " . $reportes . " onclick=\"CambiarPermisos(this.id);\"></td></tr>
<tr><td><strong>Datos: </strong></td><td><input type='checkbox' id='Datos' value='1' " . $datos . " onclick='CambiarPermisos(this.id);'></td></tr>
</table>
		</td>
		<td align='center' >
		<div id='ReUbicacion'>
		<table><tr>
			<td>Farmacia: </td><td><div id='ComboFarmacia'>" . $row["Farmacia"] . "</div></td></tr>
			<tr><td>Area: </td><td><div id='ComboArea'>" . $row["Area"] . "</div></td> 
			<tr><td colspan='2'><div id='botones'><input type='button' id='CambiarUbicacion' value='Cambiar  Ubicacion...' onclick='CambiaUbicacion(1);'></div></td></tr>
		</table>
		</div>
		</td>
		</tr>";

                $out.="</table>";
            } else {
                $out = "No hay datos!";
            }
            echo $out;
            break;
        case 2:
            //opciones de nivel
            switch ($_GET["SubOpcion"]) {
                case 21:
                    //Genera Combo de opciones
                    $IdPersonal = $_GET["IdPersonal"];
                    $nivelActual = $_GET["nivelActual"];
                    $out = "<select id='nivel' onchange='CambioNivelFinal(this.value)' onblur='CambioNivelFinal(this.value);'>
	  		<option value='2'>Co-Administrador</option>
	  		<option value='3'>Tecnico de Farmacia</option>
	  		<option value='4'>Digitador</option>";
                    echo $out;
                    break;
                case 22:
                    //Realiza el cambio de nivel
                    $NivelOld = $proceso->NivelUsuario($_GET["IdPersonal"]);
                    if ($NivelOld == $_GET["NivelNuevo"]) {
                        switch ($_GET["NivelNuevo"]) {
                            case '2': $out = "<u><a onclick='CambioNivel(" . $_GET["NivelNuevo"] . ");'>Co-Administrador</a></u>";
                                break;
                            case '3':$out = "<u><a onclick='CambioNivel(" . $_GET["NivelNuevo"] . ");'>Tecnico de Farmacia</a></u>";
                                break;
                            case '4':$out = "<u><a onclick='CambioNivel(" . $_GET["NivelNuevo"] . ");'>Digitador de Farmacia</a></u>";
                                break;
                        }
                    } else {
                        $resp = $proceso->CambiarNivel($_GET["IdPersonal"], $_GET["NivelNuevo"]);

                        $NivelNuevo = $proceso->NivelUsuario($_GET["IdPersonal"]);
                        switch ($_GET["NivelNuevo"]) {
                            case '2': $out = "<u><a onclick='CambioNivel(" . $_GET["NivelNuevo"] . ");'>Co-Administrador</a></u>";
                                break;
                            case '3':$out = "<u><a onclick='CambioNivel(" . $_GET["NivelNuevo"] . ");'>Tecnico de Farmacia</a></u>";
                                break;
                            case '4':$out = "<u><a onclick='CambioNivel(" . $_GET["NivelNuevo"] . ");'>Digitador de Farmacia</a></u>";
                                break;
                        }
                    }
                    echo $out;
                    break;
            }
            break;
        case 3:
            //Cambio de permisos de acceso
            $IdPersonal = $_GET["IdPersonal"];
            $acceso = $_GET["acceso"];
            $campo = $_GET["Id"];
            $proceso->CambioPermisos($IdPersonal, $acceso, $campo);

            break;
        case 4:
            //Cambio de Ubicacion
            switch ($_GET["SubOpcion"]) {
                case 41:
                    //despliegue de opciones
                    $resp = $proceso->Farmacias($IdModalidad);
                    $out = "<table width='100%'>
			<tr><td width='30%'>Farmacia: </td><td><select id='farmacia' onchange='CargarAreas(this.value)'>
			<option value='0'>[SELECCIONE]</option>";
                    while ($row = mysql_fetch_array($resp)) {
                        $out.="<option value='" . $row["IdFarmacia"] . "'>" . $row["Farmacia"] . "</<option>";
                    }
                    $out.="</select></td></tr>
		<tr><td>Area: </td><td><div id='ComboAreas'><select id='area' disabled='true'><option>[SELECCIONE]</option></select></div></td></tr>
		<tr><td colspan='2'align='right'>
			<input type='button' id='Guardar' value='Cambiar' onclick='CambiaUbicacion(2)'> 
			<input type='button' id='Cancelar' value='Cancelar' onclick='CambiaUbicacion(3)'>
		</td></tr></table>";
                    echo $out;
                    break;
                case 42:
                    //Guarda Cambios
                    $IdFarmacia = $_GET["IdFarmacia"];
                    $IdArea = $_GET["IdArea"];
                    $IdPersonal = $_GET["IdPersonal"];
                    $proceso->CambiarArea($IdFarmacia, $IdArea, $IdPersonal);
                    $resp = $proceso->InformacionGral($IdPersonal);
                    $row = mysql_fetch_array($resp);

                    $out = "<table><tr>
			<td>Farmacia: </td><td><div id='ComboFarmacia'>" . $row["Farmacia"] . "</div></td></tr>
			<tr><td>Area: </td><td><div id='ComboArea'>" . $row["Area"] . "</div></td> 
			<tr><td colspan='2'><div id='botones'><input type='button' id='CambiarUbicacion' value='Cambiar  Ubicacion...' onclick='CambiaUbicacion(1);'></div></td></tr>
		</table>";
                    echo $out;
                    break;
                case 43:
                    //Despliega Areas
                    $IdFarmacia = $_GET["IdFarmacia"];
                    $IdPersonal = $_GET["IdPersonal"];
                    $resp = $proceso->AreasFarmacia($IdFarmacia, $IdPersonal, $IdModalidad,$IdEstablecimiento);
                    $out = "<select id='area'>
			<option value='0'>[SELECCIONE]</option>";
                    while ($row = mysql_fetch_array($resp)) {
                        $out.="<option value='" . $row["IdArea"] . "'>" . $row["Area"] . "</option>";
                    }
                    $out.="</select>";
                    echo $out;
                    break;
                case 44:
                    //Cancelacion
                    $IdPersonal = $_GET["IdPersonal"];
                    $resp = $proceso->InformacionGral($IdPersonal);
                    $row = mysql_fetch_array($resp);

                    $out = "<table><tr>
			<td>Farmacia: </td><td><div id='ComboFarmacia'>" . $row["Farmacia"] . "</div></td></tr>
			<tr><td>Area: </td><td><div id='ComboArea'>" . $row["Area"] . "</div></td> 
			<tr><td colspan='2'><div id='botones'><input type='button' id='CambiarUbicacion' value='Cambiar  Ubicacion...' onclick='CambiaUbicacion(1);'></div></td></tr>
		</table>";
                    echo $out;
                    break;
            }
            break;
        case 5:
            //Deshabilitar o Habilitar Cuentas de usuarios
            $proceso->DeshabilitarCuenta($_GET["IdPersonal"], $_GET["NuevoEstado"]);

            break;
        case 6:

            break;
    }
    conexion::desconectar();
} else {
    echo "ERROR_SESSION";
}
?>