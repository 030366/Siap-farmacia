<?php session_start();
if (!isset($_SESSION["IdPersonal"]) or $_SESSION["IdPersonal"] != 1) { ?>
    <script language="javascript">
        window.location="../Principal/index.php?Permiso=1";
    </script>
    <?php
} else {
    include("../Clases/class.php");
    conexion::conectar();

    function farmacias() {
        $query = "select * from mnt_farmacia where HabilitadoFarmacia='S'";
        $resp = mysql_query($query);
        $combo = "<select id='IdFarmacia' name='IdFarmacia'>
                <option value='0'>[GENERAL]</option>";
        while ($row = mysql_fetch_array($resp)) {
            $combo.="
                <option value='" . $row["IdFarmacia"] . "'>" . $row["Farmacia"] . "</option>";
        }
        $combo.="</select>";

        return $combo;
    }
    ?>
    
    
    <html>
        <head>
            <script language="javascript" src="IncludeFiles/Ajax.js"></script>
            <script language="javascript"  src="../ReportesArchives/calendar.js"> </script>
        </head>

        <body>
            <table align="center">
                <tr><td colspan="2">Errores con Cantidades Despachada </td></tr>
                <tr><td>Farmacia: </td><td><?php echo farmacias(); ?></td></tr>
                <tr><td>Fecha de Inicio: </td><td><input type="text" id="fechaInicial" name="fechaInicial" readonly="true" onclick="scwShow (this, event);"/></td></tr>
                <tr><td colspan="2" align="right"><input type="button" id="go" name="go" value="GO!" onclick="valida()"/></td></tr>
            </table>
            <br/><br/>
            <div id="Progreso" align="center"></div>
            <br/>
            <div id="ErroresDespacho" align="center"></div>
        </body>
    </html>
    <?php
    conexion::desconectar();
}
?>
