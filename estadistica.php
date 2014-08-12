<?

//ZONA DE CONTROL ---------------------------------------------------->
//Vamos a recoger el valor de las variables que se pasan por GET
//$valores = array(12, $_POST['var'], 2, 5, 4, 9, 4);
if($_GET['tipo']=="dias") {
$total=7; //Dias de la semana
	for($a=0;$a<$total;$a++) {
		if($_GET[chr(97+$a)]!="" && isset($_GET[chr(97+$a)]) && is_numeric($_GET[chr(97+$a)]))
			$valores[]=$_GET[chr(97+$a)];	
		else {
			echo "Error. Wtf are you doing?<br>";
			return;
		}
	}
	/*if(count($valores)!=$total){
		echo "Error. Wtf are you doing?<br>";
		return;
	}*/
		
} elseif($_GET['tipo']=="horas") {
$total=24; //Dias de la semana
	for($a=0;$a<$total;$a++) {
		if($_GET[chr(97+$a)]!="" && isset($_GET[chr(97+$a)]) && is_numeric($_GET[chr(97+$a)]))
			$valores[]=$_GET[chr(97+$a)];	
		else {
			echo "Error. Wtf are you doing?<br>";
			return;
		}
	}
} else {
	echo "Error. Wtf are you doing?<br>";
	return;
}

//CARACTERISTICAS --------------------------------------------------->
$ancho = 540;
$alto = 300;

$margen['superior'] = 50;
$margen['inferior'] = 50;
$margen['izquierdo'] = 50;
$margen['derecho'] = 50;

$diferencia_barra_numero = 20;

//Este va a ser el margen que se dejará desde el inicio del eje hasta el mayor valor
$limite_superior = round(($alto-$margen['superior']-$margen['inferior'])/10);
$altura_barra_maxima = $alto-$margen['superior']-$margen['inferior']-$limite_superior;
$altura_barra_minima = 0;

$anchura_eje_horizontal = $ancho-$margen['izquierdo']-$margen['derecho'];
$anchura_espaciado = round(($anchura_eje_horizontal/3)/(count($valores)+1));

$anchura_barra = round((($anchura_eje_horizontal/3)*2)/(count($valores)));

//CARACTERISTICAS ------->



$valores2 = array_values($valores);

//$espaciado = round(($ancho-100)/count($valores));


//Empezamos a crear la imagen
$imagen = imagecreate($ancho, $alto);

$background = imagecolorallocate($imagen, 192, 222, 237);
$color_ejes = imagecolorallocate($imagen, 0, 0, 0);

//$coloresnuevos = array();

//for($i=0; $i<count($valores2);$i++)
//	$coloresnuevos[$valores2[$i]]= imagecolorallocate($imagen, 43, 169, 231);

//Ejes!!!!!!!!!!!!
//bool imageline ( resource $image , int $x1 , int $y1 , int $x2 , int $y2 , int $color )
//Eje horizontal
imageline($imagen, $margen['izquierdo'], $alto-$margen['inferior']+1, $ancho-$margen['derecho'], $alto-$margen['inferior']+1, $color_ejes);
//Eje vertical
imageline($imagen, $margen['izquierdo'], $margen['superior'], $margen['izquierdo'], $alto-$margen['inferior']+1, $color_ejes);

//Obtenemos los valores pequeños y grandes
sort($valores2);
reset($valores2);
$menor = current($valores2);
end($valores2);
$mayor = current($valores2);

if($_GET['tipo']=="dias")
	$contenido = array("Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun");
elseif($_GET['tipo']=="horas")
	$contenido = array("00", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23");
else
	$contenido = array("error1");

//Rectángulos!!!!!
//$color = imagecolorallocate($imagen, 142, 193, 218);
$color = imagecolorallocate($imagen, 124, 176, 201);

for($posicion=0;$posicion<count($valores);$posicion++) {
	//bool imagefilledrectangle ( resource $image , int $x1 , int $y1 , int $x2 , int $y2 , int $color )
	$x1 = $margen['izquierdo']+$anchura_espaciado*($posicion+1)+($anchura_barra*$posicion);
	$x2 = $margen['izquierdo']+$anchura_espaciado*($posicion+1)+($anchura_barra*$posicion)+$anchura_barra;
	
	$y1 = $alto-$margen['inferior'];
	
	//Hay que calcular la altura que llega a tener (regla de tres)
	$altura_calculada = ($valores[$posicion]*$altura_barra_maxima)/$mayor;
	
	$y2 = $alto-$margen['inferior']-$altura_calculada;
	imagefilledrectangle($imagen, $x1, $y1, $x2, $y2, $color);

	//68
	//Cada numero tiene 6 (8) de tamaño
	switch(strlen($valores[$posicion])){
		case 1:	$ancho_numero=8; break;
		case 2:	$ancho_numero=18; break;
		case 3:	$ancho_numero=28; break;
		case 4:	$ancho_numero=38; break;
		default:
		echo "error2";
	}
	
	switch(strlen($contenido[$posicion])){
		case 1:	$ancho_str=8; break;
		case 2:	$ancho_str=18; break;
		case 3:	$ancho_str=28; break;
		case 4:	$ancho_str=38; break;
		default:
		//echo "error3";
		echo ($contenido[$posicion]);
	}


	$posx_num = ($anchura_barra/2)-($ancho_numero/2);
	$posy_num = $y2-$diferencia_barra_numero;
	
	$color2 = imagecolorallocate($imagen, 0, 0, 0);
	imagestring($imagen, 4, $posx_num+$x1, $posy_num, $valores[$posicion], $color2);

	$espacio_vertical_barra_str = 5;
	$posy_str = $alto-$margen['inferior']+$espacio_vertical_barra_str;
	$posx_str = ($anchura_barra/2)-($ancho_str/2);

	$color3 = imagecolorallocate($imagen, 0, 0, 0);
	imagestring($imagen, 4, $posx_str+$x1, $posy_str, $contenido[$posicion], $color3);
	
}

//Escribimos el titulo, si es de horas o de dias.
if($_GET['tipo']=="horas")
	$title = "Hours Statistics";
elseif($_GET['tipo']=="dias")
	$title = "Days Statistics";
else
	$title = "Title Error";

$color3 = imagecolorallocate($imagen, 0, 0, 0);
imagestring($imagen, 10, 200, 20, $title, $color3);


header("Content-type:image/jpeg");
imagejpeg($imagen);
imagedestroy($imagen);

?>