function NoCero(ct){
//ct = id del textbox
var q=0; 
var c=document.getElementById(ct).value.charAt(q); 

while(c=='0') { ++q; c=document.getElementById(ct).value.charAt(q); } 
document.getElementById(ct).value=document.getElementById(ct).value.substr(q);

}



var nav4 = window.Event ? true : false;
function acceptNum(evt){	
	var key = nav4 ? evt.which : evt.keyCode;	
	//alert(key);
		return ((key < 13) || (key >= 48 && key <= 57));
}


function Mayuscula(texto,id){
    
    var texto=texto.toUpperCase();
    document.getElementById(id).value=texto;
    
    
}

