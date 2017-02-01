<?php
require_once dirname(__FILE__).'/zipinfo.php';
   
$zipfilename = '../grabber/babo.zip';
    // Load the ZIP file or data
    $zip = new ZipInfo;
    $zip->open($zipfilename); // or $zip->setData($data);
    if ($zip->error) {
      echo "Error: {$zip->error}\n";
      exit;
    }
 
    // Check encryption
    if ($zip->isEncrypted) {
      echo "Archive is password encrypted\n";
     # exit;
    }
	
// Zip arşivinin özetini çıkartma: zip version gibi
$zipsummery = $zip->getSummary();
$zipsummery['file_size'] .= '<b><font color="DarkOrange"> Bytes</font></b>';
echo "====================================================| ";
echo "<b> ZIP ARŞİV ÖZETİ: </b>";
echo " |====================================================";
echo "<pre>\n";
print_r($zipsummery);
echo "</pre>";
echo "==========================================================================================================================";
 
    // Process the file list
    $files = $zip->getFileList();
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
$zip->close(); // Close zip handle
echo "<br />--------------------------------------------------------------------------------------<br />";
echo "<b><font color='green'>Done..</font></b>";
?>
