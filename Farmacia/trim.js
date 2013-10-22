function trim(str,Obj){
	str = str.replace(/^(\s|\&nbsp;)*|(\s|\&nbsp;)*$/g,"");
	if(str==''){document.getElementById(Obj).value=str;}
	return(str);
}//trim