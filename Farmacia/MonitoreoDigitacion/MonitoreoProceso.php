<?php

session_start();

if (!isset($_SESSION["nivel"])) {
    echo "ERROR_SESSION";
} else {


    include('MonitoreoClase.php');
    conexion::conectar();
    $mon = new Monitoreo;
    $Bandera = $_GET["Bandera"];
    switch ($Bandera) {
        case 1:

            $tabla = '<table width="100%" border="1" class="borders">
		<tr style="background:#3333CC; color:#CCCCCC;">
			<td colspan="2" align="center"><strong>Monitoreo de Digitaci&oacute;n</strong></td>
		</tr>
	 	 <tr style="background:#0099FF; color:#FFFFFF;">
			<td width="75%" align="center"><strong>Digitador</strong></td>
			<td align="center"><strong>Recetas Digitadas</strong></td>
		</tr>';
            $respPersonal = $mon->ObtenerInformacion();
            while ($rowPersonal = mysql_fetch_array($respPersonal)) {
                $Nombre = $rowPersonal[0];
                $NumeroRecetas = $rowPersonal[1];


                $tabla.='<tr style="background:#CCCCCC;"><td>' . htmlentities($Nombre) . '</td>';
                $tabla.='<td align="right">' . $NumeroRecetas . '</td>';

                $tabla.=' </tr>';
            }//while
            $tabla.='</table>';

            echo $tabla;
            break;
        case 2:
            //Personas conectadas al sistema
            $tabla = '<table width="100%" border="1" class="borders">
		<tr style="background:#3333CC; color:#CCCCCC;">
			<td colspan="2" align="center"><strong><h5>Usuarios Conectados</h5></strong></td>
		</tr>
	 	 <tr style="background:#0099FF; color:#FFFFFF;">
			<td width="75%" align="center"><strong><h5>Usuario</h5></strong></td>
			<td align="center"><strong><h5>Estado</h5></strong></td>
		</tr>';
            $respPersonal = $mon->ObtenerInformacionEnLinea($_SESSION["IdPersonal"]);
            if ($rowPersonal = mysql_fetch_array($respPersonal)) {
                $sonido = "NO";
                do {
                    $IdPersonal = $rowPersonal["IdPersonal"];
                    $Nombre = $rowPersonal["Nombre"];
                    $Estado = $rowPersonal["Estado"];

                    $chat = $mon->Chat($_SESSION["IdPersonal"], $IdPersonal);
                    $Activo = "";
                    if ($rowChat = mysql_fetch_array($chat)) {
                        $Activo = "<strong>tiene " . $rowChat["Numero"] . " mensaje(s) NUEVO(S) o< </strong>";
                        if ($_SESSION["nivel"] == 1) {
                            $sonido = "OK";
                        } else {
                            $sonido = "NO";
                        }
                    }

                    $tabla.='<tr style="background:#CCCCCC;"><td><span id="' . $IdPersonal . '" onclick="AbrirChat(this.id);";><h5>' . htmlentities($Nombre) . '</h5><span style="font-size:xx-small;">' . $Activo . '</span></span></td>';
                    $tabla.='<td align="center"><strong><h5>' . $Estado . '</h5></strong></td>';

                    $tabla.=' </tr>';
                } while ($rowPersonal = mysql_fetch_array($respPersonal));
            } else {//while
                $tabla.='<tr style="background:#CCCCCC;"><td colspan=2><h5>No hay usuarios conectados!</h5></td>';
                $tabla.=' </tr>';
                $sonido = "NO";
            }
            $tabla.='</table>';

            echo $tabla . "~" . $sonido;
            break;
        case 3:
            //Personas conectadas al sistema
            $tabla = '<table width="70%" border="1" class="borders">
		<tr style="background:#3333CC; color:#CCCCCC;">
			<td colspan="2" align="center"><strong><h5>Chat(s)</h5></strong></td>
		</tr>
	 	 ';
            $respPersonal = $mon->ObtenerInformacionEnLinea($_SESSION["IdPersonal"]);

            if ($rowPersonal = mysql_fetch_array($respPersonal)) {
                $sonido = "NO";
                do {
                    $IdPersonal = $rowPersonal["IdPersonal"];
                    $Nombre = $rowPersonal["Nombre"];
                    $Estado = $rowPersonal["Estado"];

                    $chat = $mon->Chat($_SESSION["IdPersonal"], $IdPersonal);
                    $Activo = "";

                    if ($rowChat = mysql_fetch_array($chat)) {
                        $Activo = "<strong>tiene " . $rowChat["Numero"] . " mensaje(s) NUEVO(S) o< </strong>";
                        $sonido = "OK";
                    }
                    $tabla.='<tr style="background:#CCCCCC;"><td><span id="' . $IdPersonal . '" onclick="AbrirChat(this.id);"; style="font-size:9px;">' . htmlentities($Nombre) . '<span style="font-size:xx-small;">' . $Activo . '</span></span></td>';
                    //$tabla.='<td align="center"><strong><h5>'.$Estado.'</h5></strong></td>';

                    $tabla.=' </tr>';
                } while ($rowPersonal = mysql_fetch_array($respPersonal));
            } else {//while
                $tabla.='<tr style="background:#CCCCCC;"><td colspan=2><h5>No hay usuarios conectados!</h5></td>';
                $tabla.=' </tr>';
                $sonido = "NO";
            }
            $tabla.='</table>';
            echo $tabla . "~" . $sonido;
            break;
    }//switch
    conexion::desconectar();
}//session
?>