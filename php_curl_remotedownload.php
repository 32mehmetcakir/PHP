<?php
#Kaynak: http://denizyildirim.net/2013/05/php-curl-ile-yarim-downloadlari-tamamlamak/
#$fileName = 'MT6572__alps___tangxun6572_we_l__5.1__ALPS.L1.MP6.V2.8_TANGXUN6572.WE.L.rar';

/**
    Download remote file in php using curl
    Files larger that php memory will result in corrupted data
*/
#$path = '/home/u444339116/public_html/grabber/'.$fileName;

echo "<pre>";
echo "Loading ...<br />";

#ob_start();
#ob_flush();
#flush();

function progress($resource, $download_size, $downloaded, $upload_size, $uploaded)
{
    if ($download_size > 0)
	{
        #$progress = round($downloaded / $download_size * 100);
		$progress = $downloaded / $download_size  * 100;
		
		$dosya = fopen('progress.txt', 'w');
		fwrite($dosya, $progress);
		fclose($dosya);
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

function retrieve_remote_file_size($url){
     $ch = curl_init($url);

     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
     curl_setopt($ch, CURLOPT_HEADER, TRUE);
     curl_setopt($ch, CURLOPT_NOBODY, TRUE);

     $data = curl_exec($ch);
     $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

     curl_close($ch);
     return $size;
}


$urlfile = "http://gfs262n164.userstorage.mega.co.nz/dl/0B5eRndIgLtwWQILGQIYikz0bYBX-T9Bc8qzWOQ-lns4cQWwiwrBcHAkiGqqu7tu4lGNqkMqZ35NEN74_kLvVRyoI0h1QDZdAZeF1FyM_Ab1UvslayO5daEicHGkpw";
$fileName = "MT6572__alps___tangxun6572_we_l__5.1__ALPS.L1.MP6.V2.8_TANGXUN6572.WE.L.rar";
echo "Local File Name: " .$fileName . "<br />";
echo "Remote URL     : " .$urlfile . "<br />";

$ch = curl_init($urlfile);
// http://www.denizyildirim.com/buyukdosya.zip sekilinde olacak
curl_setopt($ch, CURLOPT_URL, $urlfile);
if (file_exists($fileName)) { // Eğer daha önce indirilmişse
$from = filesize($fileName); // nekadarı indiğini öğren

/* Eğer inen dosya remote dosyadan eşit yada büyükse bu işlemi yap */
$remotefrom = retrieve_remote_file_size($urlfile);
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
$fp = fopen ($fileName, 'a+'); // İndirdiğimiz dosyamızın kaldığı yerden tamamlayacak şekilde aç
// Servera hangi browser kullandığımızı gönderelim
#curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
chmod($fileName, 0755); // kayıtlı dosyaya yazma hakkımız yoksa o hakkı verelim
#curl_setopt($ch, CURLOPT_TIMEOUT, 1950); // download işlemi için ne kadar uğraşsın
curl_setopt($ch, CURLOPT_TIMEOUT, 25); // download işlemi için ne kadar uğraşsın (25 saniye)
curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, 'progress'); // Progress-bar fonksiyonumuz
curl_setopt($ch, CURLOPT_NOPROGRESS, false); // üstteki fonksiyonun çalışması için
curl_setopt($ch, CURLOPT_FILE, $fp); // Curl işleminin dosya download olduğunu belirtiyoruz
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // yönlendirme varsa takip et
curl_exec($ch); // Curl işlemine başla
curl_close($ch); // Curl işlemini bitir
fclose($fp); // Açtığımız dosyayı kapat.
chmod($fileName, 0644); // Dosya yazma iznini kapat.


$dosya = fopen('progress.txt', 'r');
$icerik = fread($dosya, filesize('progress.txt'));
echo "Tamamlanma Yüzdesi: %" .$icerik ."<br />";
fclose($dosya);

	if ((int)$icerik < 100) 
		{
		$delay=25; //Where 0 is an example of time Delay you can use 5 for 5 seconds for example !
		header("Refresh: $delay;"); 
		}

echo "Done";
#ob_flush();
#flush();
?>
