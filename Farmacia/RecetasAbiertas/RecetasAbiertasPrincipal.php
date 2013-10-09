<?php include('../Titulo/Titulo.php');
if (!isset($_SESSION["nivel"])) { ?>
    <script language="javascript">
        window.location='../signIn.php';
    </script>
    <?php
} else {
    if (isset($_SESSION["IdFarmacia2"])) {
        $IdFarmacia = $_SESSION["IdFarmacia2"];
    }
    $nivel = $_SESSION["nivel"];
    if (($_SESSION["Reportes"] != 1)) {
        ?>
        <script language="javascript">
            window.location='../Principal/index.php?Permiso=1';
        </script>
        <?php
    } else {
        $tipoUsuario = $_SESSION["tipo_usuario"];
        $nombre = $_SESSION["nombre"];
        $nivel = $_SESSION["nivel"];
        $nick = $_SESSION["nick"];
        require('../Clases/class.php');

//******Generacion del combo principal

        function generaSelect2() { //creacioon de combo para las Regiones
            // Voy imprimiendo el primer select compuesto por los paises
            echo "<select name='Mes' id='Mes'>";
            for ($i = 1; $i <= 12; $i++) {
                switch ($i) {
                    case 1:
                        $Mes = "ENERO";
                        $MesNum = '01';
                        break;
                    case 2:
                        $Mes = "FEBRERO";
                        $MesNum = '02';
                        break;
                    case 3:
                        $Mes = "MARZO";
                        $MesNum = '03';
                        break;
                    case 4:
                        $Mes = "ABRIL";
                        $MesNum = '04';
                        break;
                    case 5:
                        $Mes = "MAYO";
                        $MesNum = '05';
                        break;
                    case 6:
                        $Mes = "JUNIO";
                        $MesNum = '06';
                        break;
                    case 7:
                        $Mes = "JULIO";
                        $MesNum = '07';
                        break;
                    case 8:
                        $Mes = "AGOSTO";
                        $MesNum = '08';
                        break;
                    case 9:
                        $Mes = "SEPTIEMBRE";
                        $MesNum = '09';
                        break;
                    case 10:
                        $Mes = "OCTUBRE";
                        $MesNum = $i;
                        break;
                    case 11:
                        $Mes = "NOVIEMBRE";
                        $MesNum = $i;
                        break;
                    case 12:
                        $Mes = "DICIEMBRE";
                        $MesNum = $i;
                        break;
                }
                echo "<option value='" . $MesNum . "'>" . $Mes . "</option>";
            }
            echo "</select>";
        }

        function generaSelect3() { //creacioon de combo para las Regiones
            conexion::conectar();
            $consulta = mysql_query("select distinct year(Fecha)as anios from farm_recetas where year(Fecha) not in (select AnoCierre from farm_cierre where AnoCierre is not null)");
            conexion::desconectar();
            // Voy imprimiendo el primer select compuesto por los paises
            echo "<select name='Anio' id='Anio'>";
            while ($registro = mysql_fetch_row($consulta)) {
                echo "<option value='" . $registro[0] . "'>" . $registro[0] . "</option>";
            }
            echo "</select>";
        }
        ?>
        <html>
            <head>
                <?php head(); ?>
                <title>Reporte por Farmacias</title>
                <script language="javascript"  src="../ReportesArchives/calendar.js"> </script>
                <script language="javascript" src="IncludeFiles/ReporteGeneral.js"></script>
                <link rel="stylesheet" type="text/css" href="../default.css" media="screen" />

            </head>

            <body>
                <?php Menu(); ?>
                <br>

                <table width="816" border="0">
                    <tr class="MYTABLE">
                        <td colspan="5" align="center"><strong>RECETAS ABIERTAS</strong></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="FONDO"><br></td>
                    </tr>
                    <tr>
                        <td width="280" class="FONDO" colspan="5" align="center"><strong>Periodo: </strong>
                        <strong>Mes:</strong> <?php generaSelect2(); ?>                        
                        <strong>A&ntilde;o:</strong> <?php generaSelect3(); ?>
                        </td>
                    </tr>
                    <tr>

                    </tr>

                    <tr>
                        <td colspan="5" class="FONDO">&nbsp;</td>
                    </tr>
                    <tr class="MYTABLE">
                        <td colspan="5" align="right">
                            <input type="button" id="generar" name="generar" value="Mostrar Recetas Abiertas" onClick="javascript:Valida();"/>
                        </td>
                    </tr>
                    <!-- <tr class="MYTABLE">
                      <td colspan="5" align="right">	
                <input type="button" id="Imprimir" name="imprimir" value="Imprimir" onClick="javascript:Imprimir();">
                                </td>
                    </tr> -->
                </table>
                <br>
                <div id="Reporte" align="center"></div>
            </body>
        </html>
        <?php
    }//Fin de IF nivel == 1
}//Fin de IF isset de Nivel
?>