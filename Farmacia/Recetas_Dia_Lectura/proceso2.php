<?php

session_start();

if (isset($_SESSION["nivel"])) {

    $IdArea = $_SESSION["IdArea"];
    require('../Clases/class.php');
    require('IncludeFiles/DiasClase.php');
    $query = new queries;
    $query2 = new Repetitivas;
    conexion::conectar();
    /*     * VALORES POR POST* */
    $Lista = 1;
    $IdReceta = $_GET["IdReceta"];

    mysql_query("update farm_recetas set IdEstado='L' where IdReceta='$IdReceta'");
    /** VALORES PARA AJAX* */
//**********************************************************************
    if ($Lista == 1) {
        //Se obtiene el detalle de la receta para determinar el consumo de medicamentos
        $resp = $query2->MedicinaReceta($IdReceta);

        while ($row = mysql_fetch_array($resp)) {
            $IdMedicina = $row["IdMedicina"];
            $IdReceta = $row["IdReceta"];
            $satisfecha = $row["IdEstado"];
            $IdHistorialClinico = $row["IdHistorialClinico"];

            if ($satisfecha != "I") {
                //Satisfechas

                /* Updates */
                queries::InsertarDatosReceta($IdMedicina, $IdReceta, $IdHistorialClinico);
            } else {
                //Insatisfechas
                queries::InsertarDatosReceta2($IdMedicina, $IdReceta, $IdHistorialClinico);
            }
        }//fin de while


        conexion::desconectar();
    }//IF Listo
} else {
    echo "ERROR_SESSION";
}
?>