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
	$("#botonfecha-replies").click(function() {
		if(j%2==0)
		$("span#fecha").css("display","none");
		else
		$("span#fecha").css("display","inline");
		j++;
	});
	
	var k=1;
	$("#botonfecha-hashtags").click(function() {
		if(k%2==0)
		$("span#fecha").css("display","none");
		else
		$("span#fecha").css("display","inline");
		k++;
	});

	//Por haber 4 categorias.
	var num_categorias=4;
	
	$(".opciones").click(function() {
		var categoria_seleccionada = $(this).attr('id');
		for(var a=0;a<=num_categorias;a++) {
			$("table#opciones td#opcion"+a).css("border-top","");
			$("table#opciones td#opcion"+a).css("border-bottom","");
			$("table#opciones img#opcion"+a).css("display","none");
			$("div#contenedor div#opcion"+a).css("display","none");
			$("div#contenedor span#opcion"+a).css("font-weight","100");
			$("div#contenedor span#opcion"+a).css("font-size","16");
		}
		$("div#contenedor div#"+categoria_seleccionada).css("display","block")
		$("table#opciones td#"+categoria_seleccionada).css("border-top","1px solid black");
		$("table#opciones td#"+categoria_seleccionada).css("border-bottom","1px solid black");
		$("table#opciones img#"+categoria_seleccionada).css("display","inline");
		$("div#contenedor span#"+categoria_seleccionada).css("font-weight","bold");
		$("div#contenedor span#"+categoria_seleccionada).css("font-size","25");
		
	});

 });