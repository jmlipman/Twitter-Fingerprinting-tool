// JavaScript Document
function ocultar() {
	$("img#flechas").css("display","none");
}

 $(this.document).ready(function() {
	 $("span#loading").css("display", "none");
	 $("img#flechas").css("display", "block");
	 $("img#loading").attr("src", "images/blank.png");
	 
	var i=1
	var id=setInterval(function() {
	 	$("img#flechas").css("margin-top",i++);
		if(i>64) {
			clearInterval(id);
			setTimeout("ocultar()", 1500);
		}
    }, 20);

	 var i=1;
	$("#botonfecha").click(function() {
		if(i%2==0)
		$("span#fecha").css("display","none");
		else
		$("span#fecha").css("display","inline");
		i++;
	});
	
	var j=1;
	$("#botonfecha-favs").click(function() {
		if(j%2==0)
		$("span#fecha-favs").css("display","none");
		else
		$("span#fecha-favs").css("display","inline");
		j++;
	});
 });