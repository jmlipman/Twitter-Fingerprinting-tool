<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type='text/javascript' src='jquery.js'></script>
<link rel="stylesheet" href="utils/analisis.css" type="text/css"/>
<script type='text/javascript' src='utils/analisis.js'></script>

<?php
	//Seteamos las categorias.
	$categorias["tweets"] = "Tweets";
	$categorias["replies"] = "Replies";
	$categorias["hashtags"] = "Hashtags";
	$categorias["statistics"] = "Statistics";

echo "<style>";
for($a=1;$a<=count($categorias);$a++){
	echo "div#contenedor div#opcion".$a." { display:none; }
	";
}
echo "</style>";
?>


</head>


<body>
<div id="contenedor">
	<div id="imagen-principal"><span id="titulo"><br /><br />Twitter Fingerprinting Tool</span>
    <!--</div>
    <div id="pos-imagen-principal">-->
    	<table align="center" id="opciones" cellpadding="2" cellspacing="10">
        	<tr>

<?php
	//Printeamos las flechas y las opciones.
	$a=1;
	foreach($categorias as $valor)
		echo '				<td id="flecha"><img src="images/flecha.png" id="flechas"></td>
	';
		echo "</tr><tr>";
	foreach($categorias as $valor){
		echo '				<td class="opciones" id="opcion'.$a.'"><img src="images/twitter.gif" class="opciones" id="opcion'.$a.'" /><span class="opciones" id="opcion'.$a.'">'.$valor.'</span></td>
';
	$a++;
	}
?>

            </tr>
        </table>
    </div>
    
	<div class="opciones" id="opcion0"><br /><br /><br /><p align="center"><img id="loading" src="images/loading.gif"><span id="loading">LOADING... WAIT.</span></p><br /><br /><br /></div>

<?php
	$j=1;
	foreach($categorias as $clave => $valor){
		echo '		<div class="opciones" id="opcion'.$j.'">';
		
		if($clave=="tweets" && !$protegido) {
			//GEOLOCALIZACION y DEEP SCAN
			$geoloc=false;
			$deep=true;
			echo '<br><div class="dates" id="botonfecha">Show/Hide dates</div><br>';
			obtener_tweets($user,$geoloc,$deep, $replies, $trendings, $fechas);
			
		} elseif($clave=="replies" && !$protegido) {
			echo '<br><div class="dates" id="botonfecha-replies">Show/Hide dates</div><br><br>';
			
			//Creamos el indice
			crear_indice($replies,6);
				
			foreach($replies as $clave1 => $valor1) {
				$a=1;
				//Nombre de usuario
				//Crear una cabecera para cada usuario: avatar, nick, nombre, fecha creación.
				//cabecera_usuario_replies($clave1);
				echo "<h3 id='reply_nick'><ul><li><a name='$clave1'>$clave1</a></li></ul></h3>
				";

				foreach($valor1 as $clave2 => $valor2)
					echo '<p class="sangrar" id="tweet_individual"><span id="numero"><a href="https://twitter.com/' . $clave1 . '/status/' . $valor2["id"] . '" target="_blank">' . ($a++) . '</a></span>: <span id="fecha">[' . $valor2["created_at"] . ']</span> ' . parsear_tweet($valor2["content"]) . '</p>
					';
				$a=0;
			}
			
		} elseif($clave=="hashtags" && !$protegido) {
			echo '<br><div class="dates" id="botonfecha-hashtags">Show/Hide dates</div><br><br>';
			
			//Creamos el indice
			crear_indice($trendings,6);

			foreach($trendings as $clave1 => $valor1) {
				$a=1;
				//Nombre de usuario
				echo "<h3 id='hashtag'><ul><li><a name='$clave1'>$clave1</a></li></ul></h3>
				";

				//Printeamos los tweets.
				foreach($valor1 as $clave2 => $valor2)
					echo '<p class="sangrar" id="tweet_individual"><span id="numero"><a href="https://twitter.com/' . $clave1 . '/status/' . $valor2["id"] . '" target="_blank">' . ($a++) . '</a></span>: <span id="fecha">[' . $valor2["created_at"] . ']</span> ' . parsear_tweet($valor2["content"]) . '</p>
					';
				$a=0;
			}
		} elseif($clave=="statistics" && !$protegido) {
			//Seteamos los dias, ya que sino por defecto, se setearon con numeros en la clave.
			$total_dias['Mon']=0;
			$total_dias['Tue']=0;
			$total_dias['Wed']=0;
			$total_dias['Thu']=0;
			$total_dias['Fri']=0;
			$total_dias['Sat']=0;
			$total_dias['Sun']=0;
			
			//Obtenemos en dos matrices, las horas y los dias, que posteriormente contaremos para sacar las gráficas.
			foreach($fechas as $valor) {
				$dia = explode(" ", $valor);
				$total_dias[$dia[0]]++;
				$hora = explode(":", $dia[3]);
				$total_horas[intval($hora[0], 10)]++;
			}
			//Obtenemos la URL de las horas
			//Primero ordenamos los resultados (por la clave).
			ksort($total_horas);
			
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

			//Las dos imágenes de las estadísticas
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
