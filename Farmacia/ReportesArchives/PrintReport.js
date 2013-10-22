function ImprimirReporte() {
document.getElementById("imprimir").style.visibility='hidden';
document.getElementById("cerrar").style.visibility='hidden';
window.print();
document.getElementById("imprimir").style.visibility='visible';
document.getElementById("cerrar").style.visibility='visible';
}//impresion