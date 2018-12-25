<?php
# Kaynak1:	https://wpguru.co.uk/2014/03/how-to-list-a-directory-in-php-and-only-show-zip-files/
# Kaynak2:	https://stackoverflow.com/questions/8562398/get-max-execution-time-in-php-script
# Kaynak3:	http://php.net/manual/en/ziparchive.extractto.php#99509
#			http://php.net/manual/en/ziparchive.extractto.php	
#			http://php.net/manual/tr/class.ziparchive.php
# Kaynak4:	http://www.bymega.com/php-trim-ltrim-ve-rtrim-fonksiyonlarinin-kullanimi/
# Kaynak5:	https://stackoverflow.com/questions/535020/tracking-the-script-execution-time-in-php
# Kaynak6:	https://stackoverflow.com/questions/7740646/jquery-read-ajax-stream-incrementally
# Kaynak7:	https://www.webcebir.com/61-php-ceil-floor-round-yuvarlama-fonksiyonu-dersi.html
# Kaynak8:	https://stackoverflow.com/questions/1045845/how-to-call-a-javascript-function-from-php

$time1 = microtime(true);	//Kaynak5
$max_time = ini_get("max_execution_time");
$mod = $_GET['mod'];		// 0: test
							// 1: ziplist
							// 2: Get max execution time 
							// 3: Get ZipNumFiles
							// 4: Extract
// Fonksiyonlar
//	1) İlgili dizindeki zip dosyalarının listesini verir
function list_zipfiles($mydirectory) {
	
	// directory we want to scan
	$dircontents = scandir($mydirectory);
	
	// list the contents
	foreach ($dircontents as $file) {
		$extension = pathinfo($file, PATHINFO_EXTENSION);
		if ($extension == 'zip') {
			echo "<option value=$file>$file</option>";
		}
	}
}

if ($mod == 0) {
$yazi = $_GET['yazi'];
echo $yazi . ' MD5 kodu: ' . md5($yazi);
}
elseif ($mod == 1){
	$dizin = $_GET['dizin'];
	list_zipfiles($dizin);
}
elseif ($mod == 2){
	echo $max_time;
}
elseif ($mod == 3){
	$dizin = rtrim($_GET['dizin'], '/');	//Kaynak4
	$zipfile = $_GET['secilizip'];
	$za = new ZipArchive();	//Kaynak3
	$za->open($dizin . '/' . $zipfile);
	echo $za->numFiles;
	echo "<script>$('#lbl_zipnumfiles').text(" . $za->numFiles . ")</script>";
	$za->close();
	// Excecution time değerini ata	//Kaynak5
	$time2 = microtime(true);
	
	echo "<script>$('#executiontime').html(" . ($time2 - $time1) . ")</script>"; // script execution time: //value in seconds
}

elseif ($mod == 4){
	// İşlemler sürerken anında Jquery ile anlık çekiyoruz
	// Kaynak6
	// Flush ve benzeri tanımlamalar yapılıyor..
	header('Content-type: application/octet-stream');

	// Turn off output buffering
	ini_set('output_buffering', 'off');
	// Turn off PHP output compression
	ini_set('zlib.output_compression', false);
	// Implicitly flush the buffer(s)
	ini_set('implicit_flush', true);
	ob_implicit_flush(true);
	// Clear, and turn off output buffering
	while (ob_get_level() > 0) {
    // Get the curent level
    $level = ob_get_level();
    // End the buffering
    ob_end_clean();
    // If the current level has not changed, abort
    if (ob_get_level() == $level) break;
	}
	// Disable apache output buffering/compression
	if (function_exists('apache_setenv')) {
    apache_setenv('no-gzip', '1');
    apache_setenv('dont-vary', '1');
	}
	
	// Artık işlemlere geçelim
	$dizin = rtrim($_GET['dizin'], '/');	//Kaynak4
	$extractdir = rtrim($_GET['extractdir'], '/');	//Kaynak4
	$zipfile = $_GET['secilizip'];
	$tamamlananindex = $_GET['tamamlananindex'];
	
	/* quake2005 at gmail dot com 8 years ago
	If you want to extract one file at a time, you can use this:
	*/
    $zip = new ZipArchive;	//Kaynak3
    if ( $zip->open( $zipfile ) === true)
    {
		$zipnumfiles = $zip->numFiles;
        for ( $i=$tamamlananindex; $i < $zipnumfiles; $i++ )
        {
            $zip->extractTo($extractdir . '/', array($zip->getNameIndex($i)));
                       
        	// here you can run a custom function for the particular extracted file
			
			// Ekrana anlık çıktı verilecekler burdan itibaren başlasın
			echo "<li class='w3-margin-left'>" . $extractdir . '/' . $zip->getNameIndex($i) . "</li>";
			// Tamamlananindex değerini güncelle
			echo "<script>$('#tamamlananislem').text(" . ($i+1). ")</script>";
			// Tamamlanma yüzdesini hesapla
			$tamamlamayuzdesi = ceil((($i+1) / $zipnumfiles) * 100);	//Kaynak7
			// Javascript ile mybar ve move fonksiyonu ile güncelle		//Kaynak8
			echo '<script type="text/javascript">',
     		'move('. $tamamlamayuzdesi .');',
     		'</script>';
			// Excecution time değerini ata	//Kaynak5
			$time2 = microtime(true);
			echo "<script>$('#executiontime').text(" . ($time2 - $time1) . ")</script>"; // script execution time: //value in seconds
			// Excecution time değeri ile maximum execution time - 1 değerine ulaştıysa döngüden çık
			if (($time2 - $time1) >= ($max_time - 1)){
			break;
			}
			
			flush();	// Tamponu temizle..
        }

                $zip->close();	// Zip kapansın..		
    }

}
?>