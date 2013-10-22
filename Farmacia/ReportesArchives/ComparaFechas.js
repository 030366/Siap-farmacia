
function comparacion(form) {
    var aux1;
    var aux2;
    var fechaFin = form.fechaFin.value;
    var fechaInicio = form.fechaInicio.value;

    aux1 = fechaFin.split("-");
    aux2 = fechaInicio.split("-");

    fechalimite = aux1[2] + aux1[1] + aux1[0];
    fecha = aux2[2] + aux2[1] + aux2[0];
    if (fechalimite < fecha) {
        alert("La fecha limite es menor que la fecha inicial");
        return(false);
    }
}