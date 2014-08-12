<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type='text/javascript' src='jquery.js'></script>
<link rel="stylesheet" href="utils/fingerprinting.css" type="text/css"/>
<script type='text/javascript' src='utils/fingerprinting.js'></script>

<?php
$opciones = $_POST['opcion'];

if(!isset($opciones) || $opciones=="") {
	echo "Error. Missing data.";
	return;	
}

$opciones = comprobar_opciones($opciones);



if(in_array("bio", $opciones))
	$categorias["bio"] = "Biography";
if(in_array("tweets", $opciones) && !$protegido)
	$categorias["tweets"] = "Tweets";
if(in_array("followers", $opciones) && !$protegido)
	$categorias["followers"] = "Followers";
if(in_array("followings", $opciones) && !$protegido)
	$categorias["followings"] = "Followings";
if(in_array("favs", $opciones) && !$protegido)
	$categorias["favs"] = "Favorites";
if(in_array("listas-1", $opciones) && !$protegido)
	$categorias["listas-1"] = "Lists (follow)";
if(in_array("listas-2", $opciones) && !$protegido)
	$categorias["listas-2"] = "Lists ('s followed by)";
	
echo "<style>";
for($a=1;$a<=count($categorias);$a++){
	echo "div#contenedor div#opcion".$a." { display:none; }
	";
}
echo "</style>";
?>


<script language="javascript">
	
	<?php echo "var num_categorias=".count($categorias).";
	"; ?>
$(this.document).ready(function() {
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
	$a=1;
	foreach($categorias as $clave => $valor){
		echo '		<div class="opciones" id="opcion'.$a.'">';
		
		if($clave=="bio")
			obtener_bio($user);
		elseif($clave=="tweets" && !$protegido){
				if($_POST['opcion_geo']=="geo")
					$valor = true;
				else
					$valor = false;
			echo '<br><div class="dates" id="botonfecha">Show/Hide dates</div><br>';
			obtener_tweets($user,$valor,false);
		}
		elseif($clave=="followers" && !$protegido)
			obtener_followers_followings($user, "followers");
		elseif($clave=="followings" && !$protegido)
			obtener_followers_followings($user, "followings");
		elseif($clave=="favs" && !$protegido){
			echo '<br><div class="dates" id="botonfecha-favs">Show/Hide dates</div><br>';
			obtener_favoritos($user);
		}
		elseif($clave=="listas-1" && !$protegido)
			obtener_listas($user, "sigue");
		elseif($clave=="listas-2" && !$protegido)
			obtener_listas($user, "le siguen");
		else
			echo "Error";
			
		echo '</div>
';
	$a++;
	}
?>
    
</div>
</body>
</html>
