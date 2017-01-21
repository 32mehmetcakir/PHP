 <?php
 $rarfilename = '../MT6572__alps___tangxun6572_we_l__5.1__ALPS.L1.MP6.V2.8_TANGXUN6572.WE.L.rar';
 $rarpassword = '';
 
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
 
    // Process the file list
    $files = $rar->getFileList();
    foreach ($files as $file) {
      if ($file['pass'] == true) {
        echo "<br />File is passworded: {$file['name']}\n";
      }
      if ($file['compressed'] == false)
        echo "<br /><font color='red'>Listing uncompressed(Directory or 0 Byte) file:</font><b> {$file['name']}\n</b>";
      else
      echo "<br />Listing compressed file: {$file['name']}\n";      
    }
 echo "<br />--------------------------------------------------------------------------------------<br />";
 echo "<b><font color='green'>Done..</font></b>";
 ?>
