<?php
require "zip.class.php"; // Get the zipfile class

ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');

$zipfilepath = '../grabber/babo.zip';
$zipextractpath = 'ext/';
$zipfile = new zipfile; // Create an object
$zipfile->read_zip($zipfilepath); // Read the zip file

// Now, $zipfile->files is an array containing information about the files
// Here is an example of it's use

foreach($zipfile->files as $filea)
{
	// Sadece Listememe
	echo "The contents of {$filea['name']}:\n<br />";
	// Extract çıkartma işlemi
	# echo "{$filea['data']}\n\n";
	file_put_contents($zipextractpath . $filea['name'], $filea['data']);
	echo "Extract Done..<br />";
	flush();
}
?>
