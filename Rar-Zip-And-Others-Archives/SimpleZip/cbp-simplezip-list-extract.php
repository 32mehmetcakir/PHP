<?php
#Kaynak: http://www.7tech.co.in/php/how-to-unzip-a-zip-file-in-php-unzip/
require('simplezipClass.php');

ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');

$path_of_the_zip_file_on_server = '../grabber/babo.zip';	# Zip File adress
$Zipextractdir = 'ext/'; 									# Extract directory

$obj = new SimpleUnzip();
$obj->ReadFile($path_of_the_zip_file_on_server);
 
if ($obj->Count() == 0) { //check if zip is empty
	echo "The Zip file is empty";
} 
else {
	// Create Extract Directory
	@mkdir($Zipextractdir);
	
	// Extract Directory header echo
	echo "<b>" . $path_of_the_zip_file_on_server . " </b>ZİP arşiv dosyası içereği <b><font color='red'>" . $Zipextractdir . "</font></b> klasörüne çıkartılıyor...<br />";
	echo "<hr></hr>";
	
	for($i=0;$i<$obj->Count();$i++)
	{ 
		$data=$obj->GetData($i); // gives you the data of the current file in zip
		$name=$obj->GetName($i); // gives you the name of the current file in zip
		file_put_contents($Zipextractdir . $name, $data); //saving the file on server
		echo $name . ' <b>...Extract Done.</b><br />';
		flush();
	}
	echo "<hr></hr>";
	echo "<p><font color='green'>Toplam <b>" . $obj->Count() . " </b>dosya extract edildi.</font><p>";
}
?>
