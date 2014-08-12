<?

/**
	Nos permite crear la cabecera de cada usuario de las replies.
*/
function cabecera_usuario_replies($user) {
	echo "entro";
	$resultado = guardar_pagina("http://api.twitter.com/1/users/show.json?include_entities=true&screen_name=".$user);
	
	$objeto = json_decode($resultado);

	echo '<table id="cabecera-replies" cellspacing="2">
	<tr>
	<td id="linea"><a href="https://twitter.com/' . $user . '" target="_blank"><img src="' . $objeto->{"profile_image_url_https"} . '" id="avatar"></a></td>
	<td id="linea">' . $user . '</td>
	<td id="linea">' . $objeto->{"name"} . '</td>
	<td>' . $objeto->{"created_at"} .'</td>
	</tr>
	</table>';
}

/**
	Esta función nos comprueba las opciones, para que nadie introduzca basura.
*/
function comprobar_opciones($opciones){
	$opciones_validas = array("bio", "tweets", "geo", "followers", "followings", "favs", "listas-1", "listas-2");
	foreach($opciones as $clave => $valor) {
		if(in_array($valor, $opciones_validas)==false)
			unset($opciones[$clave]);
	}
	return $opciones;
}

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
	
	$datos_pedidos = array("Name" => "name", "Url" => "uri", "Members" => "member_count", "Suscribers" => "subscriber_count", "Created at" => "created_at");
	
	if(count($objeto)==0){
		echo "He/She doesn't follow/is followed by any list.<br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
		return;
	}
	else
	{
		echo "<table border=1>
		<tr><td>Owner</td><td>Name</td><td>Members</td><td>Suscribers</td><td>Created at</td>";
		for($a=0;$a<count($objeto);$a++)
			echo '<tr>
			<td><a href="https://twitter.com/' . $objeto[$a]->{'user'}->{'screen_name'} . '" target="_blank"><img title="' . $objeto[$a]->{'user'}->{'name'} . '" src="' . $objeto[$a]->{'user'}->{'profile_image_url_https'} . '"></a></td><td><a href="https://twitter.com' . $objeto[$a]->{$datos_pedidos["Url"]}  . '" target="_blank">' . $objeto[$a]->{$datos_pedidos["Name"]}  . '</a></td><td>' . $objeto[$a]->{$datos_pedidos["Members"]}  . '</td><td>' . $objeto[$a]->{$datos_pedidos["Suscribers"]}  . '</td><td>' . $objeto[$a]->{$datos_pedidos["Created at"]}  . '</td>
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
	
	$datos_pedidos = array("Created at" => "created_at", "Author" => "name", "Content" => "text", "Id" => "id_str");
	
	$objeto = json_decode($resultado);
	
	//CUERPO DE LOS TWEETS
	//200

	echo '<div id="tweets-favs">';
	if(count($objeto)>0){
		for($a=0;$a<count($objeto);$a++)
			echo '<p id="tweet_individual"><span id="numero"><a href="https://twitter.com/' . $user . '/status/' . $objeto[$a]->{$datos_pedidos["Id"]} . '" target="_blank">' . ($a+1) . '</a></span>: [<span id="fecha-favs">' . $objeto[$a]->{$datos_pedidos["Created at"]} . '</span>] [<a href="http://twitter.com/' . $objeto[$a]->{"user"}->{"screen_name"} . '" target="_blank">' . $objeto[$a]->{"user"}->{"screen_name"} . '</a>] ' . parsear_tweet($objeto[$a]->{$datos_pedidos["Content"]}) . '</p>
			';
	}
	else
		echo "There's no favs.<br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
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
		echo "There's no $tipo";
}


/**
	Esta función nos permite obtener los avatares de los usuarios cuyos
	ids están pasados como parámetro.
*/
function obtenemos_avatares($cadena){
	$resultado = guardar_pagina("http://api.twitter.com/1/users/lookup.json?user_id=" . $cadena);
	$objeto = json_decode($resultado);
	
	$datos_pedidos = array("Avatar" => "profile_image_url_https", "Name" => "name", "Nick" => "screen_name");

	for($a=0;$a<count($objeto);$a++){
		echo '<a href="http://twitter.com/' . $objeto[$a]->{$datos_pedidos["Nick"]} . '" target="_blank"><img id="avatar" title="' . $objeto[$a]->{$datos_pedidos["Name"]} . '" src="' . $objeto[$a]->{$datos_pedidos["Avatar"]} . '"></a>
		';
	}
}


/**
	Esta función nos permite obtener los 200 últimos tweets del user.
*/
function obtener_tweets($user, $geo=false, $deep_scan=false, &$replies=array(), &$trendings=array(), &$fechas=array()) {
	
	
	if(!$deep_scan) { //Con deep_scan habilitamos o no para que busquemos más allá de los 200 tweets
		$resultado = guardar_pagina("http://api.twitter.com/1/statuses/user_timeline.json?include_entities=false&include_rts=true&screen_name=" . $user . "&count=200");
		$datos_pedidos = array("Created at" => "created_at", "Content" => "text", "Id" => "id_str");
		$objeto = json_decode($resultado);

		//CUERPO DE LOS TWEETS
		//200
		echo '<div id="tweets">';
		if(!$geo)
		{
			for($a=0;$a<count($objeto);$a++) {
				
				//Parte para las replies y los tt.
				$patron = "/@[a-zA-Z0-9_]*/";
				preg_match_all($patron, $objeto[$a]->{$datos_pedidos["Content"]}, $salida);
				for($ts=0;$ts<count($salida[0]);$ts++) {
					$user = substr($salida[0][$ts], 1, strlen($salida[0][$ts])-1);
					$replies[$user][$a]['id'] = $objeto[$a]->{$datos_pedidos["Id"]};
					$replies[$user][$a]['created_at'] = $objeto[$a]->{$datos_pedidos["Created at"]};
					$replies[$user][$a]['content'] = $objeto[$a]->{$datos_pedidos["Content"]};	
				}
				$patron = "/#[a-zA-Z0-9_]*/";
				preg_match_all($patron, $objeto[$a]->{$datos_pedidos["Content"]}, $salida);
				for($ts=0;$ts<count($salida[0]);$ts++) {
					$tt = substr($salida[0][$ts], 1, strlen($salida[0][$ts])-1);
					$trendings[$tt][$a]['id'] = $objeto[$a]->{$datos_pedidos["Id"]};
					$trendings[$tt][$a]['created_at'] = $objeto[$a]->{$datos_pedidos["Created at"]};
					$trendings[$tt][$a]['content'] = $objeto[$a]->{$datos_pedidos["Content"]};	
				}
				//Añadimos la fecha del post a una variable
				$fechas[] = $objeto[$a]->{$datos_pedidos["Created at"]};
				echo '<p id="tweet_individual"><span id="numero"><a href="https://twitter.com/' . $user . '/status/' . $objeto[$a]->{$datos_pedidos["Id"]} . '" target="_blank">' . ($a+1) . '</a></span>: [<span id="fecha">' . $objeto[$a]->{$datos_pedidos["Created at"]} . '</span>] ' . parsear_tweet($objeto[$a]->{$datos_pedidos["Content"]}) . '</p>
				';
			}
		}
		else //Detección Geo activada
		{
			for($a=0;$a<count($objeto);$a++){
				
				//Parte para las replies y los tt.
				$patron = "/@[a-zA-Z0-9_]*/";
				preg_match_all($patron, $objeto[$a]->{$datos_pedidos["Content"]}, $salida);
				for($ts=0;$ts<count($salida[0]);$ts++) {
					$user = substr($salida[0][$ts], 1, strlen($salida[0][$ts])-1);
					$replies[$user][$a]['id'] = $objeto[$a]->{$datos_pedidos["Id"]};
					$replies[$user][$a]['created_at'] = $objeto[$a]->{$datos_pedidos["Created at"]};
					$replies[$user][$a]['content'] = $objeto[$a]->{$datos_pedidos["Content"]};	
				}
				$patron = "/#[a-zA-Z0-9_]*/";
				preg_match_all($patron, $objeto[$a]->{$datos_pedidos["Content"]}, $salida);
				for($ts=0;$ts<count($salida[0]);$ts++) {
					$tt = substr($salida[0][$ts], 1, strlen($salida[0][$ts])-1);
					$trendings[$tt][$a]['id'] = $objeto[$a]->{$datos_pedidos["Id"]};
					$trendings[$tt][$a]['created_at'] = $objeto[$a]->{$datos_pedidos["Created at"]};
					$trendings[$tt][$a]['content'] = $objeto[$a]->{$datos_pedidos["Content"]};	
				}
				//Añadimos la fecha del post a una variable
				$fechas[] = $objeto[$a]->{$datos_pedidos["Created at"]};
				
				if(isset($objeto[$a]->{'place'})) {
					//Obtenemos el ID del sitio parseando la cadena
					preg_match_all("|id\/(.*)\.json$|", $objeto[$a]->{'place'}->{'url'}, $matriz);
					$valor = "(<a id='geo' href='https://twitter.com/places/" . $matriz[1][0] . "' target='_blank'>" . $objeto[$a]->{'place'}->{'name'} . ", " . $objeto[$a]->{'place'}->{'country'} . "</a>)";
				}
				else
					$valor = "";
					
				echo '<p id="tweet_individual"><span id="numero"><a href="https://twitter.com/' . $user . '/status/' . $objeto[$a]->{$datos_pedidos["Id"]} . '" target="_blank">' . ($a+1) . '</a> ' . $valor . '</span>: [<span id="fecha">' . $objeto[$a]->{$datos_pedidos["Created at"]} . '</span>] ' . parsear_tweet($objeto[$a]->{$datos_pedidos["Content"]}) . '</p>
				';
			}
		}
		echo '</div>';
	}
	else
	{
		
		echo '<div id="tweets">';
		for($pagina=1;$pagina<5;$pagina++) {
			
			$resultado = guardar_pagina("http://api.twitter.com/1/statuses/user_timeline.json?include_entities=false&include_rts=true&screen_name=" . $user . "&count=200&page=".$pagina);

			$datos_pedidos = array("Created at" => "created_at", "Content" => "text", "Id" => "id_str");
			$objeto = json_decode($resultado);
			
			//Ya no hay mas tweets guardados.
			if($objeto[0]=="") {
				$contador=0;
				//Hacemos tres intentos
				while($contador<3 && $objeto[0]=="") {
					$resultado = guardar_pagina("http://api.twitter.com/1/statuses/user_timeline.json?include_entities=false&include_rts=true&screen_name=" . $user . "&count=200&page=".$pagina);
					$objeto = json_decode($resultado);
					$contador++;
				}
			}

						
			//CUERPO DE LOS TWEETS
			//200
		
			if(!$geo)
			{
				//Entro aqui al principio de cada una de las 4 veces.
				for($a=0;$a<count($objeto);$a++) {
					//Parte para las replies y los tt.
					$patron = "/@[a-zA-Z0-9_]*/";
					preg_match_all($patron, $objeto[$a]->{$datos_pedidos["Content"]}, $salida);
					for($ts=0;$ts<count($salida[0]);$ts++) {
						$user_ = substr($salida[0][$ts], 1, strlen($salida[0][$ts])-1);
						$replies[$user_][$a]['id'] = $objeto[$a]->{$datos_pedidos["Id"]};
						$replies[$user_][$a]['created_at'] = $objeto[$a]->{$datos_pedidos["Created at"]};
						$replies[$user_][$a]['content'] = $objeto[$a]->{$datos_pedidos["Content"]};	
					}
					$patron = "/#[a-zA-Z0-9_]*/";
					preg_match_all($patron, $objeto[$a]->{$datos_pedidos["Content"]}, $salida);
					for($ts=0;$ts<count($salida[0]);$ts++) {
						$tt = substr($salida[0][$ts], 1, strlen($salida[0][$ts])-1);
						$trendings[$tt][$a]['id'] = $objeto[$a]->{$datos_pedidos["Id"]};
						$trendings[$tt][$a]['created_at'] = $objeto[$a]->{$datos_pedidos["Created at"]};
						$trendings[$tt][$a]['content'] = $objeto[$a]->{$datos_pedidos["Content"]};	
					}
	
					//Añadimos la fecha del post a una variable
					$fechas[] = $objeto[$a]->{$datos_pedidos["Created at"]};
					echo '<p id="tweet_individual"><span id="numero"><a href="https://twitter.com/' . $user . '/status/' . $objeto[$a]->{$datos_pedidos["Id"]} . '" target="_blank">' . (($a+1)+(($pagina-1)*200)) . '</a></span>: [<span id="fecha">' . $objeto[$a]->{$datos_pedidos["Created at"]} . '</span>] ' . parsear_tweet($objeto[$a]->{$datos_pedidos["Content"]}) . '</p>
					';
				}
			}
			else //Detección Geo activada
			{
				for($a=0;$a<count($objeto);$a++){
					//Parte para las replies y los tt.
					$patron = "/@[a-zA-Z0-9_]*/";
					preg_match_all($patron, $objeto[$a]->{$datos_pedidos["Content"]}, $salida);
					for($ts=0;$ts<count($salida[0]);$ts++) {
						$user_ = substr($salida[0][$ts], 1, strlen($salida[0][$ts])-1);
						$replies[$user_][$a]['id'] = $objeto[$a]->{$datos_pedidos["Id"]};
						$replies[$user_][$a]['created_at'] = $objeto[$a]->{$datos_pedidos["Created at"]};
						$replies[$user_][$a]['content'] = $objeto[$a]->{$datos_pedidos["Content"]};	
					}
					$patron = "/#[a-zA-Z0-9_]*/";
					preg_match_all($patron, $objeto[$a]->{$datos_pedidos["Content"]}, $salida);
					for($ts=0;$ts<count($salida[0]);$ts++) {
						$tt = substr($salida[0][$ts], 1, strlen($salida[0][$ts])-1);
						$trendings[$tt][$a]['id'] = $objeto[$a]->{$datos_pedidos["Id"]};
						$trendings[$tt][$a]['created_at'] = $objeto[$a]->{$datos_pedidos["Created at"]};
						$trendings[$tt][$a]['content'] = $objeto[$a]->{$datos_pedidos["Content"]};	
					}
					//Añadimos la fecha del post a una variable
					$fechas[] = $objeto[$a]->{$datos_pedidos["Created at"]};
						if(isset($objeto[$a]->{'place'})) {
							//Obtenemos el ID del sitio parseando la cadena
							preg_match_all("|id\/(.*)\.json$|", $objeto[$a]->{'place'}->{'url'}, $matriz);
							$valor = "(<a id='geo' href='https://twitter.com/places/" . $matriz[1][0] . "' target='_blank'>" . $objeto[$a]->{'place'}->{'name'} . ", " . $objeto[$a]->{'place'}->{'country'} . "</a>)";
						}
						else
							$valor = "";
						
					echo '<p id="tweet_individual"><span id="numero"><a href="https://twitter.com/' . $user . '/status/' . $objeto[$a]->{$datos_pedidos["Id"]} . '" target="_blank">' . (($a+1)+(($pagina-1)*200)) . '</a> ' . $valor . '</span>: [<span id="fecha">' . $objeto[$a]->{$datos_pedidos["Created at"]} . '</span>] ' . parsear_tweet($objeto[$a]->{$datos_pedidos["Content"]}) . '</p>
					';
				}
			}
		} //Del for
		echo '</div>';		
	}
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
	
	$datos_pedidos = array("Id" => "id_str", "Description" => "description", "Web" => "url", "Name" => "name",
						"Followings" => "friends_count", "Followers" => "followers_count", "Tweets" => "statuses_count",
						"Creation date" => "created_at", "Favorites" => "favourites_count", "Lists" => "listed_count",
						"Location" => "location", "Time zone" => "time_zone");

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
		die("</style><h2>Error. Rate limit exceeded. Clients may not make more than 150 requests per hour. Just wait.</h2>");
	
	return $retrievedhtml;
}

?>