<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type='text/javascript' src='jquery.js'></script>
<style>
span#numero {
font-weight:bold;
}

a#hashtag {
text-decoration:none;
color:green;
}

a#geo {
color:orange;
font-size:14px;
}

span#item_bio {
font-weight:bold;
}

img#avatar {
width:50px;
heigth:50px;
}

span#fecha {
color:grey;
font-size:12px;
display:none;
}

span#fecha-favs {
color:grey;
font-size:12px;
display:none;
}

a#followers {
color:orange;
}

a#followings {
color:orange;
}

#indice {
font-size:23px;
text-decoration:none;
}

div#contenedor {
	background-color:#C0DEED;
}

div#imagen-principal {
	background-image:url(bg.png);
	background-repeat:no-repeat;
	text-align:center;
}

div#imagen-principal span#titulo {
	font-size:30px;
}

table#opciones {
	cursor:default;
	-moz-user-select: none; 
	-o-user-select: none; 
	-webkit-user-select: none; 
	-ie-user-select: none; 
	user-select: none;
}

img.opciones {
	width:30px;
	display:none;
}

span.opciones:hover {
	text-decoration:underline;
}

div#contenedor div#opcion0 {
	background-color:#C0DEED;
	display:block;

}

div#contenedor div.opciones {
	margin:10px;
}

img#loading {
	width:150px;
	height:150px;
}

span#loading { font-size:40px; }

img#flechas {
	width:20px;
	heigh:20px;
	display:none;
}

td#flecha {
	text-align:center;
	height:90px;
}


/***********/

table#cabecera-replies {
	margin-left:40px;
	border:2px solid black;
}

table#cabecera-replies td#linea {
	/*border-right:1px solid black;*/
}

<?php



	$categorias["tweets"] = "Tweets";
	$categorias["replies"] = "Replies";
	$categorias["tts"] = "TT";
	$categorias["statistics"] = "Statistics";


for($a=1;$a<=count($categorias);$a++){
	echo "div#contenedor div#opcion".$a." { display:none; }
	";
}

?>

</style>

<script language="javascript">
function ocultar() {
	$("img#flechas").css("display","none");
}

 $(this.document).ready(function() {
	 $("span#loading").css("display", "none");
	 $("img#flechas").css("display", "block");
	 $("img#loading").attr("src", "blank.png");
	 
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
	$("#botonfecha-tts").click(function() {
		if(k%2==0)
		$("span#fecha").css("display","none");
		else
		$("span#fecha").css("display","inline");
		k++;
	});

	
	<?php echo "var num_categorias=".count($categorias).";
	"; ?>
	
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
</script>
</head>


<body>
<div id="contenedor">
	<div id="imagen-principal"><span id="titulo"><br /><br />Twitter Fingerprinting Tool</span>
    <!--</div>
    <div id="pos-imagen-principal">-->
    	<table align="center" id="opciones" cellpadding="2" cellspacing="10">
        	<tr>

<?php
	$a=1;
	foreach($categorias as $valor)
		echo '				<td id="flecha"><img src="flecha.png" id="flechas"></td>
	';
		echo "</tr><tr>";
	foreach($categorias as $valor){
		echo '				<td class="opciones" id="opcion'.$a.'"><img src="twitter.gif" class="opciones" id="opcion'.$a.'" /><span class="opciones" id="opcion'.$a.'">'.$valor.'</span></td>
';
	$a++;
	}
?>

            </tr>
        </table>
    </div>
    
	<div class="opciones" id="opcion0"><br /><br /><br /><p align="center"><img id="loading" src="loading.gif"><span id="loading">LOADING... WAIT.</span></p><br /><br /><br /></div>

<?php
	$j=1;
	foreach($categorias as $clave => $valor){
		echo '		<div class="opciones" id="opcion'.$j.'">';
		
		if($clave=="tweets" && !$protegido) {
			//GEOLOCALIZACION
			$geoloc=false;
			$deep=true;
			echo '<br><input type="button" value="Show/Hide dates" id="botonfecha"><br>';
			obtener_tweets($user,$geoloc,$deep, $replies, $trendings, $fechas);
			
		} elseif($clave=="replies" && !$protegido) {
			echo '<br><input type="button" value="Show/Hide dates" id="botonfecha-replies"><br><br>';
			
			foreach($replies as $clave1 => $valor1) {
				$a=1;
				//Nombre de usuario
				//Crear una cabecera para cada usuario: avatar, nick, nombre, fecha creación.
				//cabecera_usuario_replies($clave1);
				echo "<h2>$clave1</h2>
				";

				foreach($valor1 as $clave2 => $valor2)
					echo '<p id="tweet_individual"><span id="numero"><a href="https://twitter.com/' . $clave1 . '/status/' . $valor2["id"] . '" target="_blank">' . ($a++) . '</a></span>: [<span id="fecha">' . $valor2["created_at"] . '</span>] ' . parsear_tweet($valor2["content"]) . '</p>
					';
				$a=0;
			}
			
		} elseif($clave=="tts" && !$protegido) {
			echo '<br><input type="button" value="Show/Hide dates" id="botonfecha-tts"><br><br>';
			foreach($trendings as $clave1 => $valor1) {
				$a=1;
				//Nombre de usuario
				//Crear una cabecera para cada usuario: avatar, nick, nombre, fecha creación.
				//cabecera_usuario_replies($clave1);
				echo "<h2>$clave1</h2>
				";

				foreach($valor1 as $clave2 => $valor2)
					echo '<p id="tweet_individual"><span id="numero"><a href="https://twitter.com/' . $clave1 . '/status/' . $valor2["id"] . '" target="_blank">' . ($a++) . '</a></span>: [<span id="fecha">' . $valor2["created_at"] . '</span>] ' . parsear_tweet($valor2["content"]) . '</p>
					';
				$a=0;
			}
		} elseif($clave=="statistics" && !$protegido) {
			//echo '<br><input type="button" value="Show/Hide dates" id="botonfecha"><br>';
			//obtener_tweets($user,$geoloc,$deep);
			//Seteamos los dias
			$total_dias['Mon']=0;
			$total_dias['Tue']=0;
			$total_dias['Wed']=0;
			$total_dias['Thu']=0;
			$total_dias['Fri']=0;
			$total_dias['Sat']=0;
			$total_dias['Sun']=0;
			
			foreach($fechas as $valor) {
				$dia = explode(" ", $valor);
				$total_dias[$dia[0]]++;
				$hora = explode(":", $dia[3]);
				$total_horas[intval($hora[0], 10)]++;
			}
			//Obtenemos la URL de las horas
			ksort($total_horas);
			/*for($a=0;$a<7;$a++)
				$total_horas[$a]=0;*/
			
			for($a=0;$a<24;$a++) {
				if($total_horas[$a]=="")
					$total_horas[$a]=0;
				$url_horas .= "&".chr(97+$a)."=".$total_horas[$a];
			}
			
			$a=0;
			foreach($total_dias as $clave => $valor) {
				$url_dias .= "&".chr(97+$a)."=".$valor;
				$a++;
			}
			/*for($a=0;$a<7;$a++) {
				if($total_dias[$a]=="")
					$total_dias[$a]=0;
				$url_dias .= "&".chr(97+$a)."=".$total_dias[$a];
			}*/
			//print_r($total_dias);

			/*foreach($total_horas as $clave => $valor){
				echo "Clave: $clave , Valor: $valor <br>";
				$url_horas .= "&".chr(97+$clave)."=".$valor;
			}*/

			echo '<p align="center"><img src="estadistica.php?tipo=horas'.$url_horas.'"><br>';
			
			echo '<img src="estadistica.php?tipo=dias'.$url_dias.'"><br></p>';
		}	
		else
			echo "Error";		
		echo '</div>
';
	$j++;
	}
?>
    
</div>
</body>
</html>
