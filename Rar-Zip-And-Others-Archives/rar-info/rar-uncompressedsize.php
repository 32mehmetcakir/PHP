<?php
require_once dirname(__FILE__).'/rarinfo.php';
   
$rarfilename = '../grabber/babo.rar';
$raruncompressedsize = 0;	#Rar dosyasının içindeki dosyaların toplanacağı toplam uncompressed size değeri
$rarfilecount = 0; 			#Rar dosyasının içindeki toplam dosya sayısı
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
	
// RAR arşivinin özetini çıkartma: rar version gibi
$rarsummery = $rar->getSummary();
$rarsummery['file_size'] .= '<b><font color="DarkOrange"> Bytes</font></b>';
echo "====================================================| ";
echo "<b> RAR ARŞİV ÖZETİ: </b>";
echo " |====================================================";
echo "<pre>\n";
print_r($rarsummery);
echo "</pre>";
 
    // Process the file list
    $files = $rar->getFileList();
    foreach ($files as $file) {  
		// Uncompressedsize toplama işlemi
		$rarfilecount++;
		$raruncompressedsize += $file['size'];
    }
$rar->close(); // Close rar handle
echo "<br /><hr></hr>";
echo "<font color='blue'>RAR arşivi içindeki toplam dosya+klasör sayısı &nbsp;: </font><b><font color='purple'>" . $rarfilecount . "</font></b><br />";
echo "<font color='blue'>RAR arşiv dosyasının uncompressed size değeri : </font><b><font color='Darkpink'>" . $raruncompressedsize . " Bytes</font></b><br />";
echo "<hr></hr>";
echo "<b><font color='green'>Done..</font></b>";
?>
