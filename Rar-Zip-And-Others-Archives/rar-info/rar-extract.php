<?php
 $rarfilename = '../gimagex.rar';
 
 require_once dirname(__FILE__).'/rarinfo.php';
    // Load the RAR file or data
    $rar = new RarInfo;
    $rar->open($rarfilename); // or $rar->setData($data);
    if ($rar->error) {
      echo "Error: {$rar->error}\n";
      exit;
    }
 
    // Check encryption
    if ($rar->isEncrypted) {
      echo "Archive is password encrypted\n";
     # exit;
    }
 echo "<pre>";
 $files = $rar->getSummary();
 print_r($files);
 echo "</pre>";
    // Process the file list
	/*
    $files = $rar->getFileList();
    foreach ($files as $file) {
      if ($file['pass'] == true) {
        echo "<br /><font color='blue'>File is passworded:</font><b> {$file['name']}\n</b>";
        continue;
        }
      if ($file['compressed'] == false)
        echo "<br /><font color='red'>Listing uncompressed(Directory or 0 Byte) file:</font><b> {$file['name']}\n</b>";
      else
      echo "<br />Listing compressed file: {$file['name']}\n";      
    }
	*/
 $rar->close(); // Close rar handle
 echo "<br />--------------------------------------------------------------------------------------<br />";
 echo "<b><font color='green'>Done..</font></b>";
 ?>
