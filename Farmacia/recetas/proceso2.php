<?php

session_start();

if (isset($_SESSION["nivel"])) {

    $IdArea = $_SESSION["IdArea"];

//TIPO DE FARMACIA ES UNA BANDERA DE DECISION 
//EN DONDE 1 ES QUE NO POSEEN BODEGA ES DECIR LAS DESCARGAS SON DE EXISTENCIAS
//PROPIAMENTE DE "BODEGA"[farm_entregamedicamento] 
//Y 2 LAS EXISTENCIAS SE DESCARGAN DE CADA AREA [farm_medicinaexistenciaxarea]
    $TipoFarmacia = $_SESSION["TipoFarmacia"];

    require('../Clases/class.php');
    require('IncludeFiles/DiasClase.php');
    $query = new queries;
    $query2 = new Repetitivas;
    $proceso = new Lotes;
    conexion::conectar();
    /*     * VALORES POR POST* */
    $Lista = 1;
    $IdReceta = $_GET["IdReceta"];



//MANEJO DE LA EXISTENCIAS 
    //Se obtiene el detalle de la receta para determinar el consumo de medicamentos
    $resp = $query2->MedicinaReceta($IdReceta);

    while ($row = mysql_fetch_array($resp)) {

        $IdReceta = $row["IdReceta"];
        $satisfecha = $row["IdEstado"];
        $IdHistorialClinico = $row["IdHistorialClinico"];
        //********** Datos para manejo de lotes ***********
        $IdMedicina = $row["IdMedicina"];
        $IdMedicinaRecetada = $row["IdMedicinaRecetada"];
        $Cantidad = $row["Cantidad"];
        $IdArea = $row["IdArea"];
        //*************************************************
        //Se convierten las cantidades en dado caso sea un medicamento hibrido
        //Principalmente los antivirales que son en presentacion de frascos
        //y sond espachadas en pastillas

        $respDivisor = $proceso->ValorDivisor($IdMedicina);

        if ($rowDivisor = mysql_fetch_array($respDivisor)) {
            $Divisor = $rowDivisor[0];
            $Cantidad = $Cantidad / $Divisor;
            $proceso->ActualizarCantidad($IdMedicinaRecetada, $Cantidad);
        }

        //------------------------------------------------------------------------------------

        if ($satisfecha != "I") {
            //Satisfechas
            //Se realiza la disminucion de existencias del inventario y se deja constancia
            //en farm_medicinadespachada de los movimientos realizados....
            if ($TipoFarmacia == 2) {
                $proceso->ActualizarInventario($IdMedicina, $IdMedicinaRecetada, $Cantidad, $IdArea);
            } else {
                $proceso->ActualizarInventarioBodega($IdMedicina, $IdMedicinaRecetada, $Cantidad);
            }
        }
    }//fin de while
//FIN DE MANEJO DE EXISTENCIAS
//FINALMENTE SE CAMBIA EL ESTADO DE LA RECETA A ENTREGADA....

    $TipoReceta = $query2->TipoReceta($IdReceta);
    if ($TipoReceta == 'L') {
        mysql_query("update farm_recetas set IdEstado='E',IdPersonalDespacho='" . $_SESSION["IdPersonal"] . "' where IdReceta='$IdReceta'");
    }
    if ($TipoReceta == 'RL') {
        mysql_query("update farm_recetas set IdEstado='ER', IdPersonalDespacho='" . $_SESSION["IdPersonal"] . "' where IdReceta='$IdReceta'");
    }


    conexion::desconectar();
} else {
    echo "ERROR_SESSION";
}
?>