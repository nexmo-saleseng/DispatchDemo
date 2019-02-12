<?php 
$request= file_get_contents('php://input');
$decoded_request = json_decode($request, true);
$logfile = "";
if(isset($decoded_request["dispatch_uuid"])){
	$logfile = "logs/".$decoded_request["dispatch_uuid"];
}
else {
	$logfile = "logs/".$decoded_request["_links"]["dispatch"]["dispatch_uuid"];
}

$fp = fopen($logfile, 'a');
    fwrite($fp, $request."\n");
fclose($fp);

$fp = fopen("logs/raw.txt", 'a');
fwrite($fp, $request."\n");
fclose($fp);

?>
