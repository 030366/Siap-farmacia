<?php include('../Titulo/Titulo.php');

if (!isset($_SESSION["nivel"])) { ?>
    <script language="javascript">
        window.location='../signIn.php';
    </script>
    <?php
} else {
    $nivel = $_SESSION["nivel"];
    if ($_SESSION["Reportes"] != 1) {
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
        $IdFarmacia = $_SESSION["IdFarmacia2"];
        require('../Clases/class.php');
        $conexion = new conexion;

//******Generacion del combo principal

        function ComboEmisor() { //creacioon de combo para las Regiones
            $conexion = new conexion;
            $conexion->conectar();
            $consulta = mysql_query("select distinct farm_usuarios.IdPersonal,Nombre from farm_usuarios
                                     inner join farm_ajustes
                                     on farm_ajustes.IdPersonal = farm_usuarios.IdPersonal
                                     ");
            $conexion->desconectar();
            // Voy imprimiendo el primer select compuesto por los paises
            echo "<select name='IdPersonal' id='IdPersonal'>";
            echo "<option value='0'>[Todos los usuarios...]</option>";
            while ($registro = mysql_fetch_row($consulta)) {
                if ($registro[1] != "--") {
                    echo "<option value='" . $registro[0] . "'>" . $registro[1] . "</option>";
                }
            }
            echo "</select>";
        }

        function generaSelect2() { //creacioon de combo para las Regiones
            // Voy imprimiendo el primer select compuesto por los paises
            echo "<select name='IdAreaDestino' id='IdAreaDestino'>";
            echo "<option value='0'>[Seleccione Area Destino...]</option>";
            echo "</select>";
        }
        ?>
        <html>
            <head>
                <link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
                <title>Reporte Ajustes</title>
                <script language="javascript" src="IncludeFiles/IntroTransferencias.js"></script>
                <script language="javascript"  src="../ReportesArchives/calendar.js"> </script>
                <script language="javascript"  src="../ReportesArchives/validaFechas.js"> </script>
                <script type="text/javascript" src="IncludeFiles/FiltroEspecialidad.js"></script>
                <script language="JavaScript" src="../noCeros.js"></script>
                <script language="JavaScript" src="../trim.js"></script>

                <!-- AUTOCOMPLETAR -->
                <script type="text/javascript" src="scripts/prototype.js"></script>
                <script type="text/javascript" src="scripts/autocomplete.js"></script>
                <link rel="stylesheet" type="text/css" href="styles/autocomplete.css" />
                <!--  -->

        <?php head(); ?>

            </head>
            <body>
        <?php Menu(); ?>
                <br>

                <form action="" method="post" name="formulario">

                    <table width="70%" border="0">
                        <tr class="MYTABLE">
                            <td colspan="2" align="center"><strong>AJUSTES DE MEDICAMENTOS </strong></td>
                        </tr>
                        <tr><td colspan="2" class="FONDO"><br></td></tr>
                        <tr>
                            <td class="FONDO"><strong>Usuario emisor de ajustes:</strong></td>
                            <td class="FONDO"><?php echo ComboEmisor(); ?></td>
                        </tr>
                        <tr>
                            <td class="FONDO"><strong>Fecha Inicial: </strong></td>
                            <td class="FONDO"><input type="text" name="FechaInicial" id="FechaInicial" readonly="true" value="<?php echo date('Y-m-d'); ?>" onClick="scwShow (this, event);"/></td>
                        </tr>
                        <tr>
                            <td class="FONDO"><strong>Fecha Final: </strong></td>
                            <td class="FONDO"><input type="text" name="FechaFinal" id="FechaFinal" readonly="true" value="<?php echo date('Y-m-d'); ?>" onClick="scwShow (this, event);"/></td>
                        </tr>
                        <tr>
                            <td class="FONDO" colspan="2" align="right"><input type="button" name="generar" id="generar" value="Generar Reporte Ajustes" onclick="Valida();"/></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center"><div id="ReporteAjustes"></div></td>
                        </tr>
                    </table>
                </form>

                <script>
                    new Autocomplete('NombreMedicina', function() { 
        		
                        return 'respuesta.php?q=' + this.value + '&IdArea='+$('IdArea').value; 
                    });
                </script>

            </body>
        </html>
        <?php
    }//Fin de IF nivel == 1
}//Fin de IF isset de Nivel
?>