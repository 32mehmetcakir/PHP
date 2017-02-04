<?php
require_once dirname(__FILE__).'/zipinfo.php';
   
$zipfilename = '../phprft/downloads/eXtplorer_2.1.9.zip';
$zipuncompressedsize = 0;	#Zip dosyasının içindeki dosyaların toplanacağı toplam uncompressed size değeri
$zipfilecount = 0; 			#Zip dosyasının içindeki toplam dosya sayısı
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
 
    // Process the file list
    $files = $zip->getFileList();
    foreach ($files as $file) {  
		// Uncompressedsize toplama işlemi
		$zipfilecount++;
		$zipuncompressedsize += $file['size'];
    }
$zip->close(); // Close zip handle
echo "<br /><hr></hr>";
echo "<font color='blue'>Zip arşivi içindeki toplam dosya+klasör sayısı &nbsp;: </font><b><font color='purple'>" . $zipfilecount . "</font></b><br />";
echo "<font color='blue'>Zip arşiv dosyasının uncompressed size değeri : </font><b><font color='Darkpink'>" . $zipuncompressedsize . " Bytes</font></b><br />";
echo "<hr></hr>";
echo "<b><font color='green'>Done..</font></b>";
?>
