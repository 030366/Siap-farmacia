// JavaScript autocomplete widget, version 1.4.5, built on 03/09/2008 at 10:48.
// For details, see: http://www.createwebapp.com/
(function(){

var ua=navigator.userAgent.toLowerCase();var webkit=/webkit/.test(ua);var webkit4=/webkit\/4/.test(ua);var webkit5=/webkit\/4/.test(ua);var gecko=!webkit&&/gecko/.test(ua);var msie=/msie/.test(ua);var msie6=/msie 6/.test(ua);var loaded=0;var sw=0;var sn;var ATB="autocomplete_text_busy";var PX="px";var ON="on";var OFF="off";


var Z=function(s){
	s=cwa.b(s.toString()).replace(/[\Wvar]/gi,"");
	var x=0;
	for(var i=0;i<s.length;i++)x=(x+s.charCodeAt(i)%10+i%10)%1986;return x;
};



var cwa={

	h:function(o){
		var s=0;for(i=0;i<o.length;i++){s+=o.charCodeAt(i);};
		var base="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
		var h=base.substr(s&63,1);
		while(s>63){s>>=6;h=base.substr(s&63,1)+h;};return h;
	},

	y:function(o){return o.owner&&o.key&&!o.key.indexOf(cwa.h(o.owner));},

	w:function(){return"<span id='owner'>Motor de busquesda MSPAS</span>";},

	b:function(t){return t.substring(t.indexOf('{')+1,t.lastIndexOf('}'));},

     focus:function(t){
	t.focus();
	var l=t.value.length;
	if(msie){
	var r=t.createTextRange();
	r.moveStart('character',l);
	r.moveEnd('character',l);
	r.select();
	}else{
	t.setSelectionRange(l,l);
	};
     }
};

(function(){
	var timer;
	
     function fireContentLoadedEvent(){
	if(loaded)return;
	if(timer)window.clearInterval(timer);loaded=1;
     };
	
     if(document.addEventListener){
	if(webkit){timer=window.setInterval(function(){if(/loaded|complete/.test(document.readyState))fireContentLoadedEvent();},0);Event.observe(window,"load",fireContentLoadedEvent);}else{document.addEventListener("DOMContentLoaded",fireContentLoadedEvent,false);}

      }else{
	document.write("<script id=__onDOMContentLoaded defer src=//:><\/script>");
	$("__onDOMContentLoaded").onreadystatechange=function(){
		if(this.readyState=="complete"){this.onreadystatechange=null;fireContentLoadedEvent();}
	};

      }

})();


var ac=function(){this.initialize.apply(this,arguments);};

Object.extend(ac,{

u:function(e){while(e=e.parentNode){if(e.style){if(e.style.overflow=='hidden')e.style.overflow='visible';if(e.style.tableLayout=='fixed')e.style.tableLayout='auto';}}},

removeWatermark:function(n,k){ac.owner=n+' Autocomplete';ac.key=k;},

findPopup:function(v){var e=Event.element(v);e=e?e:v;while(e&&e.parentNode&&!$(e).hasClassName("autocomplete_list"))e=e.parentNode;if(e==null)return null;return e.parentNode&&e.id?e:null;},


I:function(e){var v;if(e.nodeType==1)v=e.getAttribute("onselect");return(v!=null)&&(v!=undefined);},

F:function(v,p){
var e=Event.element(v);
while(e.parentNode&&(e!=p)&&(!ac.I(e)))e=e.parentNode;
return(e.parentNode&&(e!=p))?e:null;},


process:function(e,o){if(!$(e).hasClassName('usual'))o.request(e.getAttribute('href'));},

C:function(v){var e=Event.element(v);ac.inst.each(function(i){if(i.text!=e&&i.L.L2!=e)setTimeout(i.hide.bind(i),0);});},

L:function(){
if(msie)sn=self.name;
if(webkit4){$(Autocomplete.inst).each(

function(i){var l=$(document.createElement("div"));
document.body.appendChild(l);
l.style.position="absolute";
l.className="autocomplete_text";
l.style.width="15px";
Position.clone(i.text,$(l),{setLeft:1,setTop:1,setWidth:0,setHeight:1,offsetTop:0,offsetLeft:i.text.getWidth()-l.getWidth()-1});
i.S=l;
}

);};

var x="autocomplete_x1";if(loaded){var e=document.createElement("div");e.id=x;var es=e.style;es.position="absolute";es.left=es.top="-999px";es.overflow="scroll";es.width="400px";e.className=ATB;e.innerHTML="<div style='width:80px'></div>";document.body.appendChild(e);}else{document.write("<div id='"+x+"' style='position:absolute;left:-999px;top:-999px;overflow:scroll;width:40px' class='"+ATB+"''><div style='width:80px'></div></div>");};sw=$(x).offsetWidth-$(x).clientWidth;},


inst:new Array(),name:'',key:'',
getStyle:function(e){
if(!webkit&&document.defaultView&&document.defaultView.getComputedStyle)return document.defaultView.getComputedStyle(e,null);else return e.currentStyle||e.style;
},

getInt:function(s){var i=parseInt(s);return isNaN(i)?0:i;}


}


);




ac.prototype={
$c:0,init:0,T:0,i:-1,d:1,last_value:"",custom_uri:"",bw:1,

initialize:function(t,f,options){

this.S=this.text=$(t)?$(t):document.getElementsByName(t)[0];

if((this.text==null)||(f==null)||(typeof f!='function'))return;this.text.setAttribute("autocomplete",OFF);

this.setOptions(options);
this.getURL=f;
var x=this.text.getAttribute("autocomplete_id");
if(x!=null)return;

var sid="no_"+ac.inst.length;this.text.setAttribute('autocomplete_id',sid);
this.onchange=this.text.onchange;
this.text.onchange=function(){};

var ml=function(n){
var x="autocomplete_list";
if(loaded){
var e=document.createElement("ol");
e.id=n+"_"+x;
var es=e.style;
es.position="absolute";
es.left=es.top="-9999px";
e.className=x;
document.body.appendChild(e);

}else{
document.write("<ol id='"+n+"_"+x+"' style='position:absolute;left:-9999px;top:-9999px' class='"+x+"'></ol>");
};

return $(n+"_"+x);

};


this.L=ml(sid+"a");
this.L2=ml(sid+"b");
if(msie6){

if(loaded){var e=document.createElement("iframe");e.id=sid+"_iframe";var es=e.style;es.position="absolute";es.filter="progid:DXImageTransform.Microsoft.Alpha(opacity = 0)";e.src="javascript:false;";document.body.appendChild(e);

}else{
document.write("<iframe id='"+sid+"_iframe' style='position:absolute;filter:progid:DXImageTransform.Microsoft.Alpha(opacity = 0)' src='javascript:false;'></iframe>");

};

this.F=$(sid+"_iframe");this.F.style.display="none";
};

ac.inst.push(this);if(ac.L){ac.L();ac.L=undefined;};if(!cwa.y(ac)){new Insertion.After(this.text,cwa.w());ac.u(this.text);};this.r();

},


V:function(){return this.L.style.display!="none";},

setOptions:function(options){
this.options={width:"auto",frequency:0.25,minChars:1,delimChars:', ',size:10,select_first:1,align:"auto"};
Object.extend(this.options,options||{});
},

r:function(){
this._k=this.k.bindAsEventListener(this);
this.$r=this.request.bind(this);
var t=this.text;
$(t).addClassName("autocomplete_text");
if(/mac/.test(ua)){
t._ac=this;
t.onkeypress=function(e){return!this._ac.$s;};

};
var O=Event.observe;
if(webkit&&Event._observeAndCache)
Event._observeAndCache(t,"keypress",this._k,false);
else if(msie)O(t,"keydown",this._k);
else O(t,"keypress",this._k);
O(t,'dblclick',this.$r);
O(t,'keyup',function(){clearTimeout(this.$u)}.bind(this));
O(t,'focus',this.$f.bind(this));
O(t,'blur',this.blur.bind(this));
if(ac.inst.length==1){O(document,'click',ac.C);};
var e=t;
while(e=e.parentNode)
if(e.style&&(e.style.overflow=='scroll'||e.style.overflow=='auto')){
this.scrollable=this.scrollable?this.scrollable:e;
O(e,'scroll',this.onScroll.bind(this));
}

},

onScroll:function(){
var s=this.scrollable;
if(s){
var p=this.t();
var o=Position.cumulativeOffset(s);
if(p[1]>=o[1]&&p[1]<o[1]+s.offsetHeight&&p[0]>=o[0]&&p[0]<o[0]+s.offsetWidth&&this.V())this.s();
else this.hide();
}
},

t:function(){var p=Position.page(this.text);
return[p[0]+(msie?this.text.scrollLeft:0)+(document.documentElement.scrollLeft||document.body.scrollLeft),
p[1]+(document.documentElement.scrollTop||document.body.scrollTop)];

},

iolv:function(){
var d=this.options.delimChars,v=encodeURIComponent(this.text.value),i,j,k=0;
for(i=v.length-1;i>=0;i--){for(j=0;j<d.length;j++)if(v.charAt(i)==d.charAt(j)){k=i+1;break;};if(k)break;};return k;

},


page:function(n){
var e=$A(document.getElementsByClassName(n)).find(function(e){return ac.findPopup(e)==this.L;}.bind(this));

if(e&&e.tagName&&e.tagName.toUpperCase()=='A')ac.process(e,this);
else{
var s=this.options.size;
var i=this.i;
var l=this.items.length;
if(n=="page_up"){
if(i>=s)this.focus(i-s);else this.focus(0);
};

if(n=="page_down"){if(i+s<l)this.focus(i+s);else this.focus(l-1)};

}

},

$f:function(){

if(this.status!=ON){
this.status=ON;if(!this.V()&&this.text.value=='')this.request();
}

},

blur:function(){

if(!this.V()){
this.status=OFF;
setTimeout(function(){if(this.status==OFF)this.stop();}.bind(this),10);
}

},

stop:function(){this.c();this.stopIndicator();this.hide();},

c:function(){
if((this.latest)&&(this.latest.transport.readyState!=4))this.latest.transport.abort();

},

k:function(e){

this.status=ON;
this.$s=false;
var c=e.keyCode;
var t=e.type;
if(c==9||c==13){if(this.V()||!this.$c){if((c==13)&&(this.$c)&&(this.i>-1)){Event.stop(e);this.$s=true;};
if(this.V())this.z();};return;};

if(c==38||c==40||c==63232||c==63233){if(this.$c){(c==38)||(c==63232)?this.U():this.D();Event.stop(e);};};

if(c==33||c==34||c==63276||c==63277){if(this.$c)(c==33)||(c==63276)?this.page('page_up'):this.page('page_down');};
if(c==27){this.stop();if(webkit){this.text.blur();this.text.focus();}};
if(c==38||c==40||c==33||c==34||c==27||c==63232||c==63233||c==63276||c==63277){Event.stop(e);return;};

switch(c){case 9:case 37:case 39:case 35:case 36:case 45:case 16:case 17:case 18:break;default:this.custom_uri="";clearTimeout(this.T);this.c();setTimeout(function(){this.T=setTimeout(this.$r,this.options.frequency*1000);}.bind(this),10);}

},

z:function(){var m=this.G();this.stop();
var x=Z(cwa.y)+Z(cwa.w)+Z(cwa.h)+Z(ac.u)+Z(ac.prototype.initialize);

if((m==undefined)||(m==null))return;

var s=m.getAttribute('o'+'nsel'+'ect').replace("this.request(","this.request(1");
if(m&&!((x+5)%4)){try{eval(s);}catch(e){this.onError(e)};cwa.focus(this.text);if(this.onchange){setTimeout(function(){this.onchange.bind(this.text)();}.bind(this),10);}};

},


G:function(){return this.items?this.items[this.i]:null;},

focus:function(i){if((this.i==i)||(!this.$c))return;$(this.L).show();Element.removeClassName(this.G(),'current_item');this.i=i;var m=this.G();if(!m)return;$(m).addClassName('current_item');var size=this.options.size;var u=this.L;var h=this.H(this.L);var mt=m.offsetTop;
var btw=parseInt(Element.getStyle(u,'border-top-width'));
var bbw=parseInt(Element.getStyle(u,'border-bottom-width'));
if(webkit4)mt-=btw;
if((gecko)&&(mt<u.scrollTop))mt+=btw;
if(msie){
mt-=parseInt($(m).getStyle("padding-top"))+this.bw;
if(document.compatMode=="BackCompat")h-=btw+bbw;
};

if(mt<u.scrollTop)u.scrollTop=mt+(mt==0?0:this.bw);
if(mt+m.offsetHeight-u.scrollTop>h)u.scrollTop=mt+m.offsetHeight-h-this.bw;
try{var z=m.getAttribute('onfocus');
if(msie)z=cwa.b(z.toString());eval(z);

}catch(e){}



},




U:function(){if(this.i>-1)this.focus(this.i-1);},

D:function(){if(this.i<this.items.length-1)this.focus(this.i+1);},


beforeRequest:function(){},

bR:function(){
if(!this.init){this.init=true;
this.L.onscroll=function(){cwa.focus(this.text);}.bind(this);};

this.last_value=this.value.substr(this.iolv());
var l=this.last_value?this.last_value.length:this.text.value.length;
return l>=this.options.minChars;
},


request:function(u){
var z=typeof u!="string";
this.value=encodeURIComponent(this.text.value);
if(u==1){
u=this.url;this.status=ON;
}else{
	if(z){	
	u=this.getURL();
	if(u==undefined){this.stop();return;}
	};

};

if(this.status==ON&&this.bR()){this.onLoad();this.url=u;

this.latest=new Ajax.Updater(
	this.L2,
	u+this.custom_uri,
	{method:'get',onComplete:this.onComplete.bind(this),onFailure:this.onFailure.bind(this)}

);


	}else this.stop();

},



onError:function(){},

onFailure:function(){},

onLoad:function(){this.$c=0;this.i=-1;this.startIndicator();},

onComplete:function(){setTimeout(this.d.bind(this,arguments[0]),10);},

d:function(){

var l=this.latest;
var tx=l.transport;
if((this.status==ON)&&(tx==arguments[0]||tx==arguments[0].transport)){

if(this.latest.url!=this.url+this.custom_uri)return;

this.$c=true;
if(!l.success)l.success=l.responseIsSuccess;

try{

if((typeof tx.status!="unknown")&&l.success()){
}else{
this.L2.innerHTML="<li onselect=';'>Request failed: "+tx.status+' '+(tx.statusText?tx.statusText:'')+'</li>';};

this.L2.style.width=this.L2.style.height="auto";var ls=this.L2.childNodes;var i=0;

for(var j=0;j<ls.length;j++){
var x=ls[j];
if(ac.I(x)){
x.className="item";
if(msie&&(++i<=this.options.size)&&!x.getElementsByTagName("span").length)x.innerHTML="<span style='padding:0'></span>"+x.innerHTML;
}
};

this.$c=true;this.s(this.options.select_first);

}catch(e){};

};


},




offset:function(e){
var o=0;
if(gecko||webkit||(msie&&(document.compatMode!='BackCompat'))){
var bl='border-left-width';
var br='border-right-width';
var pl='padding-left';
var pr='padding-right';
var f=new Function('e','p','return Autocomplete.getInt(Element.getStyle(e, p));');
o=f(e,bl)+f(e,br)+f(e,pl)+f(e,pr);
};
return o;
},


H:function(L){
var s=this.options.size;
var A=$A(L.getElementsByTagName("li"));
var l=A.size();
var m=A[(l>s?s:l)-1];
var h=m.offsetTop+m.offsetHeight;
var btw=parseInt(Element.getStyle(L,'border-top-width'));
var bbw=parseInt(Element.getStyle(L,'border-bottom-width'));
if(msie){
if(document.compatMode=="BackCompat")h+=btw+bbw;
h-=parseInt($(m).getStyle("padding-top"))+this.bw;
};

if(webkit4)h-=btw;return h-this.bw;

},

s:function(ft){
this.status=ON;
var x=Z(cwa.y)+Z(cwa.w)+Z(cwa.h)+Z(ac.u)+Z(this.initialize);
// alert(x);
if(x!=4099)return;
var p=this.t();
var th=this.text.offsetHeight;
if(this.status==ON){
var pt=p[1]+th;
if(this.status!=ON)return;
var w="auto";
{var i=600;
if(!!window.opera)this.L2.style.width=i+PX;
var oh=this.L2.offsetHeight;
if(webkit){w=this.L2.offsetWidth;
}else{
var l=this.text.offsetWidth,h=i;
do{
i=Math.ceil((l+h)/2);
this.L2.style.width=i+PX;
if(this.L2.offsetHeight>oh)l=i+1;else h=i;
}while(h-l>=20);


w=h;this.L2.style.width=h+PX;

};

};

if(this.L2.offsetWidth<this.text.offsetWidth)w=this.text.offsetWidth-this.offset(this.L2);
var h="auto";
this.items=new Array();
if(this.L.innerHTML!=this.L2.innerHTML){
this.L.innerHTML=this.L2.innerHTML;this.i=-1;
var ls=this.L.childNodes;
for(var j=0;j<ls.length;j++){
var x=ls[j];
if(x.className=="item"){
var i=this.items.length;
x.onmouseover=function(i){this.focus(i)}.bind(this,i);
x.onclick=function(i){this.i=i;this.z();}.bind(this,i);
this.items.push(x);
};

};

Element.addClassName(this.items[0],"first_item");

};


if(this.items.length>this.options.size){
this.L.style.overflow='auto';
w=parseInt(w)+sw;
h=this.H(this.L2)+PX;
};

if(this.items.length){
var l=p[0];
var d=this.text.offsetWidth-w;
var a=this.options.align;
if((a=='auto')&&(document.body.offsetWidth-l-w>14))d=0;
if(a=='left')d=0;
if(a=='center')d/=2;
Element.setStyle(this.L,{top:pt+PX,left:l+d+PX,width:w+PX,height:h});

this.L.style.display="";
if(ft)setTimeout(this.D.bind(this),0);
if(this.F){
self.name=sn;
var es=this.F.style;
es.top=pt+PX;
es.left=p[0]+PX;
es.width=w;
es.height=this.L.getHeight();
es.display="";
};

};

this.stopIndicator();
if(msie){
setTimeout(
function(){
for(var j=0;j<this.items.length;j++){
var x=this.items[j];
if(!x.getElementsByTagName("span").length)x.innerHTML="<span style='padding:0'></span>"+x.innerHTML;
}
}.bind(this),0
);
}

}


},



hide:function(){
if(this.V()){this.L.style.display="none";if(this.F)this.F.style.display="none";}
},

startIndicator:function(){$(this.S).addClassName(ATB);},
stopIndicator:function(){$(this.S).removeClassName(ATB);}


};



window.AutoComplete=window.Autocomplete=ac;
try{var a="autocomplete.js";var b="license.js";
var p=$A(document.getElementsByTagName("script")).find(function(s){return s.src.indexOf(a)>-1;}).src.replace(a,b);new Ajax.Request(p,{method:"get",asynchronous:0});

}catch(e){};

})();