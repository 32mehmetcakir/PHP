<?php
require "zip.class.php"; // Get the zipfile class
$zipfile = new zipfile; // Create an object
$zipfile->read_zip("myzip.zip"); // Read the zip file

// Now, $zipfile->files is an array containing information about the files
// Here is an example of it's use

foreach($zipfile->files as $filea)
{
	echo "The contents of {$filea['name']}:\n{$file['data']}\n\n";
}
?>