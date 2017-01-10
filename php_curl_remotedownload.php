<?php
#Kaynak : http://denizyildirim.net/2013/05/php-curl-ile-yarim-downloadlari-tamamlamak/
#Kaynak2: http://mfyz.com/php-ile-curl-kutuphanesinin-kullanimi
/**
    Download remote file in php using curl
    Files larger that php memory will result in corrupted data
*/
session_start(); //to ensure you are using same session
ob_start(); // start output buffer
set_time_limit(40);
ini_set('max_execution_time', 40);
ini_set('memory_limit', '512M');
echo "<pre>";
echo "Loading ...<br />";
# ob_start();
# ob_flush();
# flush();
function progress($resource, $download_size, $downloaded, $upload_size, $uploaded)
{
    if ($download_size > 0)
	{
        #$progress = round($downloaded / $download_size * 100);
		$progress = $downloaded / $download_size  * 100;
		echo "$download_size\n";
	}
    /*
	$progress = array('progress' => $progress);
    $path = "./";
    $destination = $path."11.json";
    $file = fopen($destination, "w+");
	$json_encode = json_encode($progress, JSON_UNESCAPED_UNICODE);
    fwrite($file, $json_encode);
    fclose($file);
	*/
}
	/**
	* Get Remote File Size
	*
	* @param sting $url as remote file URL
	* @return int as file size in byte
	*/
function remote_file_size($url)
{
	# Get all header information
	$data = get_headers($url, true);
	# Look up validity
	if (isset($data['Content-Length']))
    # Return file size
    return (int) $data['Content-Length'];
}

$urlfile = "http://www.mega-debrit.com/index.php/MEGA/mega_eab4ba20d6/www.mega-debrit.com_MT6572__alps___tangxun6572_we_l__5.1__ALPS.L1.MP6.V2.8_TANGXUN6572.WE.L.rar";
$fileName = "leyla.rar";
echo "Local File Name: " .$fileName . "<br />";
echo "Remote URL     : " .$urlfile . "<br />";
$ch = curl_init(); // oturum baslat
// http://www.denizyildirim.com/buyukdosya.zip sekilinde olacak
curl_setopt($ch, CURLOPT_URL, $urlfile);
if (file_exists($fileName)) 
{ // Eğer daha önce indirilmişse
	$from = filesize($fileName); // nekadarı indiğini öğren
	/* Eğer inen dosya remote dosyadan eşit yada büyükse bu işlemi yap */
	$remotefrom = remote_file_size($urlfile);
	echo "RemoteSize: " . $remotefrom . "<br />";
	if ($from == $remotefrom)
	{
		echo "Dosya zaten inmiş. İşlem yapılmadı!";
		curl_close($ch); // Curl işlemini bitir
		exit;
	}
	elseif ($from > $remotefrom)
	{
		echo "Local file ile Remote file size değerleri birbirinden farklı!<br />";
		echo "Dosya silindi! <br />";
		echo "Sayfayı yenile! <br />";
		unlink($fileName);
		curl_close($ch); // Curl işlemini bitir
		exit;
	}	
	curl_setopt($ch, CURLOPT_RANGE, $from . "-"); // ne kadarı indiyse o noktadan downloada başla
}
#$fp = fopen ($fileName, 'a+'); // İndirdiğimiz dosyamızın kaldığı yerden tamamlayacak şekilde aç
// Servera hangi browser kullandığımızı gönderelim
#curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.93 Safari/537.36');
chmod($fileName, 0755); // kayıtlı dosyaya yazma hakkımız yoksa o hakkı verelim
#curl_setopt($ch, CURLOPT_TIMEOUT, 1950); // download işlemi için ne kadar uğraşsın
curl_setopt($ch, CURLOPT_TIMEOUT, 20); // download işlemi için ne kadar uğraşsın (25 saniye)
curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, 'progress'); // Progress-bar fonksiyonumuz
curl_setopt($ch, CURLOPT_NOPROGRESS, false); // üstteki fonksiyonun çalışması için
#curl_setopt($ch, CURLOPT_FILE, $fp); // Curl işleminin dosya download olduğunu belirtiyoruz
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // yönlendirme varsa takip et
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 	// New
curl_setopt($ch, CURLOPT_HEADER, 0);			// New
curl_setopt($ch, CURLOPT_BUFFERSIZE, 65536);	// New

$data = curl_exec($ch); // Curl işlemine başla
$dlhizi = curl_getInfo($ch,"CURLINFO_SPEED_DOWNLOAD");	// New
curl_close($ch); // Curl işlemini bitir
// baglantiyi kapa
    $islem = fopen($fileName, "a");
    fwrite($islem, $data);
    fclose($islem);
// Açtığımız dosyayı kapat.
chmod($fileName, 0644); // Dosya yazma iznini kapat.

#echo "Tamamlanma Yüzdesi: %" .$icerik ."<br />";
echo "Download Hızı: " .$dlhizi ."<br />";
/*
	if ((int)$icerik < 100) 
		{		
		session_unset();
		session_destroy(); //destroy the session
		$delay=1; //Where 0 is an example of time Delay you can use 5 for 5 seconds for example !
		#header("Refresh: $delay;"); 
		exit();
		}
		*/
echo "Done";
#ob_flush();
#flush();
?>
