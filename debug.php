<?php
echo "<pre>";
$filename = $_GET['wfname'];
$maxcount = $_GET['count'];
if($maxcount == "")
	$maxcount=100;

if($filename != ""){
	foreach(file("logs/".$filename) as $line) {
		$json_string = json_encode(json_decode($line,true), JSON_PRETTY_PRINT);
		echo $json_string."\n\n";
	}
}
else {
	$fp = fopen('logs/raw.txt', 'r');
	$pos = -2; // Skip final new line character (Set to -1 if not present)

	$currentLine = '';
	$linecount=0;
	while (-1 !== fseek($fp, $pos, SEEK_END)) {
    		$char = fgetc($fp);
    		if (PHP_EOL == $char) {
			$json_string = json_encode(json_decode($currentLine,true), JSON_PRETTY_PRINT);
			echo $json_string."\n\n";
            		$currentLine = '';
			$linecount++;
			if($linecount > $maxcount)
				break;
    		}else {
            		$currentLine = $char . $currentLine;
    		}
    		$pos--;
	}

}



echo "</pre>";
?>
