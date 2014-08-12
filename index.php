<?
if($_GET || $_POST){
	echo "Error.";
	return;
}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type='text/javascript' src='jquery.js'></script>
<script language="javascript">
 $(this.document).ready(function() {
	$("#geo").click(function() {
		if($("input#tweets").is(':checked')==false && $("input#geo").is(':checked')==true)
			$('input#tweets').attr('checked', true);
	});
	
	$(".select").click(function() {
		var tipo = $(this).attr('id');
		if(tipo=="seleccionar"){
			$('input#bio').attr('checked', true);
			$('input#tweets').attr('checked', true);
			$('input#geo').attr('checked', true);
			$('input#followers').attr('checked', true);
			$('input#followings').attr('checked', true);
			$('input#favs').attr('checked', true);
			$('input#listas-1').attr('checked', true);
			$('input#listas-2').attr('checked', true);
		}
		else
		{
			$('input#bio').attr('checked', false);
			$('input#tweets').attr('checked', false);
			$('input#geo').attr('checked', false);
			$('input#followers').attr('checked', false);
			$('input#followings').attr('checked', false);
			$('input#favs').attr('checked', false);
			$('input#listas-1').attr('checked', false);
			$('input#listas-2').attr('checked', false);	
		}
	});
	
	$("#stalker").submit(function() {
		if($("input#bio").is(':checked')==false && $("input#tweets").is(':checked')==false && $("input#followers").is(':checked')==false
		&& $("input#followings").is(':checked')==false && $("input#favs").is(':checked')==false && $("input#listas-1").is(':checked')==false
		&& $("input#listas-2").is(':checked')==false && $("input#fingerprinting").attr("checked")!="checked" && $("input#analisis").attr("checked")=="checked") {
			alert("Error. Please, select at least one option.");
			return false;
		}
		if($("input#nombre_usuario").val()=="") {
			alert("Error. Please, insert a Twitter account.");
			return false;
		}
	});
	
	$("#fingerprinting").click(function() {
		$('input#bio').attr('disabled', false);
		$('input#tweets').attr('disabled', false);
		$('input#geo').attr('disabled', false);
		$('input#followers').attr('disabled', false);
		$('input#followings').attr('disabled', false);
		$('input#favs').attr('disabled', false);
		$('input#listas-1').attr('disabled', false);
		$('input#listas-2').attr('disabled', false);	
	});
	
	$("#analisis").click(function() {
		$('input#bio').attr('disabled', true);
		$('input#tweets').attr('disabled', true);
		$('input#geo').attr('disabled', true);
		$('input#followers').attr('disabled', true);
		$('input#followings').attr('disabled', true);
		$('input#favs').attr('disabled', true);
		$('input#listas-1').attr('disabled', true);
		$('input#listas-2').attr('disabled', true);	
	});
	
 });
</script>
<style>
.select {
	text-decoration:underline;
	cursor:default;
}
#version {	margin-left:20px; }
#todo {	margin-left:20px; }

#radios {
	margin-left:100px;
}

</style>
<title>Twitter Fingerprinting Tool</title>
</head>
<body>
<h1>Twitter Fingerprinting Tool</h1>
<p>
Author: <b>@jmlipman</b>.<br />
Created: <b>02-02-2012</b>.<br />
Last edition: <b>12-02-2012</b><br>
Current version: <b>1.3.1</b>.<br />
<u>Objective</u>: Get data about a single Twitter account.<br />
<b>Note</b>: If you realize something to be changed (new function, core, even GUI), <u>I would be really pleased to tell me that through Twitter.</u><br />
<b>Note 2</b>: Sometimes it doesn't work due to the 150 petitions/hour per IP Twitter limit.<br>
<b>Note 3</b>: Hours are given in GMT+0.
</p>
<p><b>Designs advices are welcome</b></p>
<p><b>Has the application reached its limit and you want to see the results page? Check the offline version (targeting my account) <a href="example.htm" target="_blank">here</a>.</b></p>

Just tick whatever you want to get returned. Obviously, you also must introduce the username.
<form id="stalker" action="stalker.php" method="post">

<input type="text" id="nombre_usuario" value="jmlipman" name="usuario"/><br>
	   <span class="select" id="seleccionar">Select all</span>     <span class="select" id="deseleccionar">Unselect all</span><br>
<p id="radios"><input type="radio" name="seleccion" value="fingerprinting" id="fingerprinting" title="Allows you to fingerprint one single account" checked><label title="Allows you to fingerprint one single account" for="fingerprinting">Fingerprinting</label>
<input type="radio" name="seleccion" value="analisis" id="analisis" title="Allows you to analyze one single account statistics"><label title="Allows you to analyze one single account statistics" for="analisis">Analysis</label></p>
<ul>
    <input type="checkbox" name="opcion[]" value="bio" id="bio"/><label for="bio">Biography</label><br />
    <input type="checkbox" name="opcion[]" value="tweets" id="tweets"/><label for="tweets">Tweets</label>
    <input type="checkbox" name="opcion_geo" value="geo" id="geo"/><label for="geo">geolocated (if possible)</label><br />
    <input type="checkbox" name="opcion[]" value="followers" id="followers"/><label for="followers">Followers</label><br />
    <input type="checkbox" name="opcion[]" value="followings" id="followings"/><label for="followings">Followings</label><br />
    <input type="checkbox" name="opcion[]" value="favs" id="favs"/><label for="favs">Favorites</label><br />
    <input type="checkbox" name="opcion[]" value="listas-1" id="listas-1"/><label for="listas-1">Lists (follow)</label><br />
    <input type="checkbox" name="opcion[]" value="listas-2" id="listas-2"/><label for="listas-2">Lists (followed by)</label><br />
</ul>
<input type="submit" value="Make request"/>
</form>

<b>Version Timeline:</b>
<div id="version">
	<u>1.3.1:</u> <i>(12-02-2012)</i><br>
    -TT misprint fixed -> Hashtag. (analysis)<br>
    -Square brackets included into date span. (analysis, fingerprinting)<br>
    -New "Show/Hide dates" button. (analysis, fingerprinting)<br>
    -New fonts implemented (analysis, fingerprinting)<br>
    -CSS and JS division. Clearer code. (analysis, fingerprinting)<br>
    -Fixed and commented functions. Clearer code. (analysis, fingerprinting)<br>
    -Index (Hashtags and Replies) added. (analysis)<br>
    <br>
	<u>1.3:</u> <i>(11-02-2012)</i><br>
    -New module added (analysis).<br>
    -Script division, acquiring order.<br>
    -Improved GUI. (fingerprinting)<br>
    -Deep scan implemented (more than 200 tweets, although is limited in 1000).<br>
    <br>
	<u>1.2:</u> <i>(07-02-2012)</i><br>
    -English translation (index.php, stalker.php)<br>
    -Security measurements added. (index.php, stalker.php)<br>
    -New GUI implemented. (stalker.php)<br>
    -"geolocation" check script added. (index.php)<br>
    -"select/unselect all" buttons added (index.php)<br>
    <br>
	<u>1.1:</u> <i>(03-02-2012)</i><br>
    -Labels added. (index.php)<br>
    -Duplicated values deleted. (index.php)<br>
    -Error handly (150 requests/hour per IP) added. (stalker.php)<br>
</div>
<br>
<b>To do:</b>
<div id="todo">
-Improve GUI.<br>
-Don't make difference between @someone and @SoMeOnE.<br>
-Add fingerprinted name under the main banner.<br>
-Add titles.<br>
-Make non-selected new static buttons.<br>
</div>
</body>
</html>