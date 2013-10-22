<?php include('../Titulo/Titulo.php');

if (!isset($_SESSION["nivel"])) {
    ?>
    <script language="javascript">
        window.location='../signIn.php';
    </script>
    <?php
} else {

    $nivel = $_SESSION["nivel"];
    if ($_SESSION["Administracion"] != 1) {
        ?>
        <script language="javascript">
            window.location='../Principal/index.php?Permiso=1';
        </script>
        <?php
    } else {
        ?>
        <html>
            <head><TITLE>Dias Desabastecidos</TITLE>
            <?php head(); ?>
            </head>
        <?php Menu(); ?>
            <link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
            <script language="javascript"  src="../ReportesArchives/calendar.js"> </script>
            <script language="javascript" src="IncludeFiles/DiasDes.js"></script>
            <!-- AUTOCOMPLETAR -->
            <script type="text/javascript" src="scripts/prototype.js"></script>
            <script type="text/javascript" src="scripts/autocomplete.js"></script>
            <link rel="stylesheet" type="text/css" href="styles/autocomplete.css" />
            <!--  -->
            <center>
                <table width="37%">
                    <tr class="MYTABLE"><td align="center" colspan="2"><strong>Medicamento Agotado</strong></td></tr>
                    <tr class="FONDO"><td align="center" colspan="2">
                            <input type="text" id="q" name="q" value="" size="50"><input type="hidden" id="IdMedicina">

                        </td></tr>
                    <tr><td class="FONDO" colspan="2">&nbsp;</td></tr>
                    <tr class="MYTABLE"><td align="center" colspan="2"><strong>Periodo Desabastecido</strong></td></tr>
                    <tr><td class="FONDO"><strong>Periodo Inicia:</strong></td><td class="FONDO"><input type="text" id="FechaInicio" readonly="true" onClick="scwShow (this, event);" ></td></tr>
                    <tr><td class="FONDO"><strong>Periodo Finaliza:</strong></td><td class="FONDO"><input type="text" id="FechaFin" readonly="true" onClick="scwShow (this, event);"> <input type="hidden" id="Actual" value="1" onclick="LimpiaFin(this.id);"></td></tr>

                    <tr><td colspan="2" class="FONDO">&nbsp;</td></tr>

                    <tr class="MYTABLE"><td colspan="2" align="right"><input type="button" id="Establecer" value="Establecer Periodo" onclick="Valida();"></td></tr>


                    <tr><td colspan="2" align="center"><div id="Desabastecido"></div></td></tr>


                </table>




            </center>
            <script>
                new Autocomplete('q', function() { 
        			
                    return 'respuesta.php?q=' + this.value; 
                });
            </script>
        </html>
        <?php
    }//niveles
}//Si no hay session
?>