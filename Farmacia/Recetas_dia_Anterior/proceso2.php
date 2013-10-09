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

    /** VALORES PARA AJAX* */
//**********************************************************************
    if ($Lista == 1) {
        /* Si la receta estaba en proceso Bandera=P en la tabla se guarda la informacion
          y la bandera de la receta pasa a Lista (L) */
        $resp = $query2->MedicinaReceta($IdReceta);

        while ($row = mysql_fetch_array($resp)) {
            $IdMedicina = $row["IdMedicina"];
            $IdReceta = $row["IdReceta"];
            $satisfecha = $row["IdEstado"];

            if ($satisfecha != "I") {
               
                /* Updates */
                queries::InsertarDatosReceta($IdMedicina, $IdReceta, $IdHistorialClinico); //Poner Fechas
            }//IF Satisfecho
            else {
                //Insatisfechas IF Bandera==NO
                queries::InsertarDatosReceta2($IdMedicina, $IdReceta, $IdHistorialClinico);
            }
        }//fin de while



        mysql_query("update farm_recetas set IdEstado='L' where IdReceta='$IdReceta'");

        conexion::desconectar();
    }//IF Listo
    else {
        ?>

        <?php

    }
} else {
    echo "ERROR_SESSION";
}
?>