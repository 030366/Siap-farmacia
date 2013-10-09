function mayor(fecha1,fecha2){
    var Ok =true;
    f1=fecha1.split('-');
    f2=fecha2.split('-');
    fecha_1=new Date(f1[0],f1[1]-1,f1[2]);
    fecha_2=new Date(f2[0],f2[1]-1,f2[2]);
       
       //alert(fecha_1+" != "+fecha_2);
       
    
        if (fecha_1 <= fecha_2){
            Ok=true;
        }else{
            
            Ok = false;
        }
    
    
    return Ok;
}
 
