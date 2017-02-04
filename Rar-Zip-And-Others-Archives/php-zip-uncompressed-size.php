<?php
#Kaynak: https://gist.github.com/Caffe1neAdd1ct/f2ece3705053b3e2ea4c

$ZipFileName = 'phprft/downloads/babo.zip';		#Zip dosyası adresi
$ZipPassword = null;	#Zip dosyasının parolası varsa, yoksa null olarak kalacak!

$zip = new ZipArchive();
if ($zip->open($ZipFileName, $ZipPassword) === true)
    var_dump(getTotalUncompressedSize($zip));
$zip->close();

function getTotalUncompressedSize(ZipArchive $zip)
{   
    $totalSize = 0;
    
    for ($i = 0; $i < $zip->numFiles; $i++) {
        $fileStats = $zip->statIndex($i);
        $totalSize += $fileStats['size'];
    }
    
    return $totalSize;
}
?>
