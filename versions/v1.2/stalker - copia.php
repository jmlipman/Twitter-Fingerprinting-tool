<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type='text/javascript' src='jquery.js'></script>
<style>
span#numero {
font-weight:bold;
}
/*
span#hashtag {
color:green;
}
*/
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

img {
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

</style>

<script language="javascript">
 $(this.document).ready(function() {
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
</script>

<?php



if($_GET || !$_POST) {
	echo "Error.";
	return;	
}

$user = $_POST['usuario'];
$opciones = $_POST['opcion'];
if(!isset($opciones) || $user=="") {
	echo "Error. Faltan datos.";
	return;	
}

if(!comprobar_usuario($user)){
	echo "El nombre de usuario NO existe.";
	return;
}

$protegido = comprobar_si_protegido($user);
if($protegido)
	echo "<u>El twitter está protegido y por tanto, solo se podrá sacar la Bio.</u>";

//TENGO QUE PROTEGER LA MATRIZ OPCIONES, PARA QUE NO ME METAN MIERDA

//Imprimimos el índice
echo "
<h1><u>Indice</u></h1>
<ul>";

if(in_array("bio", $opciones))
	echo "<li><a href='#bio' id='indice'>Biografia</a></li>";
if(in_array("tweets", $opciones) && !$protegido)
	echo "<li><a href='#tweets' id='indice'>Tweets</a></li>";
if(in_array("followers", $opciones) && !$protegido)
	echo "<li><a href='#followers' id='indice'>Followers</a></li>";
if(in_array("followings", $opciones) && !$protegido)
	echo "<li><a href='#followings' id='indice'>Followings</a></li>";
if(in_array("favs", $opciones) && !$protegido)
	echo "<li><a href='#favs' id='indice'>Favoritos</a></li>";
if(in_array("listas-1", $opciones) && !$protegido)
	echo "<li><a href='#listas-sigue' id='indice'>Listas (sigue)</a></li>";
if(in_array("listas-2", $opciones) && !$protegido)
	echo "<li><a href='#listas-le-siguen' id='indice'>Listas (le siguen)</a></li>";
echo "</ul>
";

if(in_array("bio", $opciones)){
	echo "<h1><a name='bio'>Biografia</a></h1>";
	obtener_bio($user);
}


if(in_array("tweets", $opciones) && !$protegido){
	echo "<h1><a name='tweets'>Tweets</a></h1>";
	echo '<br><input type="button" value="Mostrar/Ocultar fechas" id="botonfecha"><br>';
	echo "<i><b>Nota</b>: a las horas hay que sumarle +1 (hora española).</i>";
	if(in_array("geo", $opciones))
		$valor=true;
	else
		$valor=false;
	obtener_tweets($user,$valor);
}

if(in_array("followers", $opciones) && !$protegido){
	echo "<h1><a name='followers'>Followers</a></h1>";
	obtener_followers_followings($user, "followers");
}

if(in_array("followings", $opciones) && !$protegido){
	echo "<h1><a name='followings'>Followings</a></h1>";
	obtener_followers_followings($user, "followings");
}

if(in_array("favs", $opciones) && !$protegido){
	echo "<h1><a name='favs'>Favoritos</a></h1>";
	echo '<br><input type="button" value="Mostrar/Ocultar fechas" id="botonfecha-favs"><br>';
	echo "<i><b>Nota</b>: a las horas hay que sumarle +1 (hora española).</i>";
	obtener_favoritos($user);
}

if(in_array("listas-1", $opciones) && !$protegido){
	echo "<h1><a name='listas-sigue'>Listas (sigue)</a></h1>";
	obtener_listas($user, "sigue");
}
	
if(in_array("listas-2", $opciones) && !$protegido){
	echo "<h1><a name='listas-le-siguen'>Listas (le siguen)</a></h1>";
	obtener_listas($user, "le siguen");
}




//<---------------------------FUNCIONES --------------------------->

/**
	Esta función se encarga de comprobar si el usuario tiene el twitter protegido.
*/

function comprobar_si_protegido($user){
	$resultado = guardar_pagina("http://api.twitter.com/1/users/show.json?include_entities=true&screen_name=" . $user);	
	$objeto = json_decode($resultado);
	
	if($objeto->{"protected"})
		$protegido = true;
	else
		$protegido = false;
	
	return $protegido;
}


/**
	Esta función nos permite obtener en forma de tablas las listas
	que sigue y que en donde está incluido. Esto se hace mediante el segundo
	parámetro, que puede ser "le siguen" o "sigue". En función de eso saldrá
	una lista o la otra.
*/
function obtener_listas($user, $tipo){
	switch($tipo){
		case "sigue":
			$resultado = guardar_pagina("http://api.twitter.com/1/lists/all.json?screen_name=" . $user);
			$objeto = json_decode($resultado);
			break;
		case "le siguen":
			$resultado = guardar_pagina("http://api.twitter.com/1/lists/memberships.json?screen_name=" . $user);
			$objeto = json_decode($resultado);
			$objeto = $objeto->{'lists'};
			break;
		default:
			echo "Parámetro incorrecto en la función <i>obtener_listas</i>";
			return;
	}
	
	$datos_pedidos = array("Nombre" => "name", "Url" => "uri", "Miembros" => "member_count", "Suscritos" => "subscriber_count", "Fecha Creación" => "created_at");
	
	if(count($objeto)==0){
		echo "No le siguen/sigue ninguna lista";
		return;
	}
	else
	{
		echo "<table border=1>
		<tr><td>Creador</td><td>Nombre</td><td>Miembros</td><td>Suscritos</td><td>Fecha creación</td>";
		for($a=0;$a<count($objeto);$a++)
			echo '<tr>
			<td><a href="https://twitter.com/' . $objeto[$a]->{'user'}->{'screen_name'} . '" target="_blank"><img title="' . $objeto[$a]->{'user'}->{'name'} . '" src="' . $objeto[$a]->{'user'}->{'profile_image_url_https'} . '"></a></td><td><a href="https://twitter.com' . $objeto[$a]->{$datos_pedidos["Url"]}  . '" target="_blank">' . $objeto[$a]->{$datos_pedidos["Nombre"]}  . '</a></td><td>' . $objeto[$a]->{$datos_pedidos["Miembros"]}  . '</td><td>' . $objeto[$a]->{$datos_pedidos["Suscritos"]}  . '</td><td>' . $objeto[$a]->{$datos_pedidos["Fecha Creación"]}  . '</td>
			</tr>';
		echo "</table>";
	}
	
}

/**
	Comprueba si el nombre de usuario dado existe.
*/
function comprobar_usuario($user){
	
	if(preg_match("/[^a-zA-Z0-9_]/", $user)){
		echo "Caracteres permitidos: a-z, A-Z, 0-9 y _<br>";
		return false;
	}
	
	$resultado = guardar_pagina("http://api.twitter.com/1/users/show.json?include_entities=true&screen_name=".$user);
	
	$objeto = json_decode($resultado);

	if($objeto->{"error"}=="Not found")
		return false;
	else
		return true;
}


/**
	Esta función nos permite obtener los últimos favoritos de un usuario.
*/
function obtener_favoritos($user){
	$resultado = guardar_pagina("http://api.twitter.com/1/favorites.json?count=200&screen_name=" . $user);
	
	$datos_pedidos = array("Fecha creación" => "created_at", "Autor" => "name", "Contenido" => "text", "Id" => "id_str");
	
	$objeto = json_decode($resultado);
	
	//CUERPO DE LOS TWEETS
	//200

	echo '<div id="tweets-favs">';
	if(count($objeto)>0){
		for($a=0;$a<count($objeto);$a++)
			echo '<p id="tweet_individual"><span id="numero"><a href="https://twitter.com/' . $user . '/status/' . $objeto[$a]->{$datos_pedidos["Id"]} . '" target="_blank">' . ($a+1) . '</a></span>: [<span id="fecha-favs">' . $objeto[$a]->{$datos_pedidos["Fecha creación"]} . '</span>] [<a href="http://twitter.com/' . $objeto[$a]->{"user"}->{"screen_name"} . '" target="_blank">' . $objeto[$a]->{"user"}->{"screen_name"} . '</a>] ' . parsear_tweet($objeto[$a]->{$datos_pedidos["Contenido"]}) . '</p>
			';
	}
	else
		echo "No tiene ningún favorito";
	echo '</div>';
}

/**
	Esta función nos permite obtener todos los followings o followers dado el user.
	La variable tipo puede ser o "followers" o "followings". Indica qué se quiere recuperar.
*/
function obtener_followers_followings($user, $tipo) {
	
	//Comprobamos que el tipo de lo que queremos devolver es correcto.
	switch($tipo){
		case "followers":
			$resultado = guardar_pagina("http://api.twitter.com/1/followers/ids.json?cursor=-1&screen_name=" . $user);
			break;
		case "followings":
			$resultado = guardar_pagina("http://api.twitter.com/1/friends/ids.json?cursor=-1&screen_name=" . $user);
			break;
		default:
			echo "Error en la función <i>obtener_followers_followings</i>. Tipo erróneo.";
			return;
	}
	
	//var_dump(json_decode($resultado));
	
	$objeto = json_decode($resultado);
	
	$indice=0;
	for($a=0,$contador=1; $a<count($objeto->{"ids"}); $a++,$contador++){
		$ids_totales[$indice] .= "," . $objeto->{"ids"}[$a];
		
		if($contador==100){
			$contador=1;
			$indice++;
		}
	}
	
	if(count($ids_totales)>0) {
		foreach($ids_totales as $valor){
			//Le quitamos el primer caracter, la coma.
			$valor = substr($valor, 1, strlen($valor)-1);
			obtenemos_avatares($valor);
		}
	}
	else
		echo "No tiene $tipo";
}


/**
	Esta función nos permite obtener los avatares de los usuarios cuyos
	ids están pasados como parámetro.
*/
function obtenemos_avatares($cadena){
	$resultado = guardar_pagina("http://api.twitter.com/1/users/lookup.json?user_id=" . $cadena);
	$objeto = json_decode($resultado);
	
	$datos_pedidos = array("Avatar" => "profile_image_url_https", "Nombre" => "name", "Nick" => "screen_name");

	for($a=0;$a<count($objeto);$a++){
		echo '<a href="http://twitter.com/' . $objeto[$a]->{$datos_pedidos["Nick"]} . '" target="_blank"><img title="' . $objeto[$a]->{$datos_pedidos["Nombre"]} . '" src="' . $objeto[$a]->{$datos_pedidos["Avatar"]} . '"></a>
		';
	}
}


/**
	Esta función nos permite obtener los 200 últimos tweets del user.
*/
function obtener_tweets($user, $geo=false) {
	$resultado = guardar_pagina("http://api.twitter.com/1/statuses/user_timeline.json?include_entities=false&include_rts=true&screen_name=" . $user . "&count=400");
	
	$datos_pedidos = array("Fecha creación" => "created_at", "Contenido" => "text", "Id" => "id_str");
	
	$objeto = json_decode($resultado);
	
	
	//CUERPO DE LOS TWEETS
	//200

	echo '<div id="tweets">';
	if($geo==false)
	{
		for($a=0;$a<count($objeto);$a++)
			echo '<p id="tweet_individual"><span id="numero"><a href="https://twitter.com/' . $user . '/status/' . $objeto[$a]->{$datos_pedidos["Id"]} . '" target="_blank">' . ($a+1) . '</a></span>: [<span id="fecha">' . $objeto[$a]->{$datos_pedidos["Fecha creación"]} . '</span>] ' . parsear_tweet($objeto[$a]->{$datos_pedidos["Contenido"]}) . '</p>
			';
	}
	else //Detección Geo activada
	{
		for($a=0;$a<count($objeto);$a++){
			if(isset($objeto[$a]->{'place'})) {
				//Obtenemos el ID del sitio parseando la cadena
				preg_match_all("|id\/(.*)\.json$|", $objeto[$a]->{'place'}->{'url'}, $matriz);
				$valor = "(<a id='geo' href='https://twitter.com/places/" . $matriz[1][0] . "' target='_blank'>" . $objeto[$a]->{'place'}->{'name'} . ", " . $objeto[$a]->{'place'}->{'country'} . "</a>)";
			}
			else
				$valor = "";
				
				//valor = (Name, Country, URL -> http:\/\/api.twitter.com\/1\/geo\/id\/3afc29d1fe76301f)
				//https://twitter.com/#!/places/3afc29d1fe76301f
			echo '<p id="tweet_individual"><span id="numero"><a href="https://twitter.com/' . $user . '/status/' . $objeto[$a]->{$datos_pedidos["Id"]} . '" target="_blank">' . ($a+1) . '</a> ' . $valor . '</span>: [<span id="fecha">' . $objeto[$a]->{$datos_pedidos["Fecha creación"]} . '</span>] ' . parsear_tweet($objeto[$a]->{$datos_pedidos["Contenido"]}) . '</p>
			';
		}
	}
	echo '</div>';
	
}

/** 
	Esta función devolverá una cadena parseada a partir de un tweet.
	Es decir, que colorea los TT de verde y añade enlaces a las menciones.
*/
function parsear_tweet($contenido) {
	
	//PARSEANDO MENCIONES
	$patron = "/@[a-zA-Z0-9_]*/";
	preg_match_all($patron, $contenido, $out);
	for($a=0;$a<count($out[0]);$a++) {
		$user = $out[0][$a];
		$user_limpio = substr($out[0][$a], 1, strlen($out[0][$a])-1);
		$contenido = str_replace($user, '<span id="mencion"><a target="_blank" href="https://twitter.com/' . $user_limpio . '">' . $user . '</a></span>', $contenido);
	}
	
	//PARSEANDO TRENDING TOPICS
	$patron = "/#[a-zA-Z0-9_]*/";
	preg_match_all($patron, $contenido, $out);
	for($a=0;$a<count($out[0]);$a++) {
		$palabra = $out[0][$a];
		$palabra_limpia = substr($out[0][$a], 1, strlen($out[0][$a])-1);
		$contenido = str_replace($palabra, '<span id="hashtag"><a id="hashtag" target="_blank" href="https://twitter.com/search?q=' . $palabra_limpia . '">' . $palabra . '</a></span>', $contenido);
	}
	
	return $contenido;	
}


/** 
	Esta función nos permite obtener la biografia del usuario dado.
	Incrusta directamente el codigo HTML, así que ojo en donde colocamos la función.
*/
function obtener_bio($user){
	
	$resultado = guardar_pagina("http://api.twitter.com/1/users/show.json?include_entities=true&screen_name=".$user);
	
	$datos_pedidos = array("Id" => "id_str", "Descripción" => "description", "Web" => "url", "Nombre" => "name",
						"Followings" => "friends_count", "Followers" => "followers_count", "Tweets" => "statuses_count",
						"Fecha de creación" => "created_at", "Favoritos" => "favourites_count", "Listas" => "listed_count",
						"Ubicación" => "location", "Zona Horaria" => "time_zone");

	$objeto = json_decode($resultado);
	
	//CUERPO DE LA BIOGRAFIA
	echo '<div id="biografia">
	<img src=' . $objeto->{"profile_image_url_https"} . '><br>';
	foreach($datos_pedidos as $clave => $valor)
		echo '<span id="item_bio">' . $clave . "</span>: " . $objeto->{$valor} . "<br>";
	echo "</div>";
}



/**
	Esta función nos permite guardar una página usando cURL.
*/
function guardar_pagina($url) {
	$ch = curl_init($url);
	ob_start();
	curl_exec($ch);
	curl_close($ch);
	$retrievedhtml = ob_get_contents();
	ob_end_clean(); 

	/*$objeto = json_decode($retrievedhtml);
		if($objeto->{"error"} && $objeto->{"error"}=="Rate limit exceeded. Clients may not make more than 150 requests per hour."){
				//echo ;
				die("<p>Error. Se ha excedido el límite de Twitter de 150 peticiones por hora por IP. ¿Solución? Esperar.</p>");
		}
		*/
		
	if(preg_match("/Rate limit exceeded. Clients may not make more than 150 requests per hour/", $retrievedhtml)) 
		die("<p>Error. Se ha excedido el límite de Twitter de 150 peticiones por hora por IP. ¿Solución? Esperar.</p>");
	
	return $retrievedhtml;
}

?>