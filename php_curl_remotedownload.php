<?php
# Kaynak : http://denizyildirim.net/2013/05/php-curl-ile-yarim-downloadlari-tamamlamak/
# Kaynak2: http://mfyz.com/php-ile-curl-kutuphanesinin-kullanimi

session_start(); //to ensure you are using same session
ob_start(); // start output buffer
# set_time_limit(40);
ini_set('max_execution_time', 40);
ini_set('memory_limit', '512M');

// Timeout
$startTime = time();
$timeout = 29;   //timeout in seconds

echo "<pre>";
echo "<b>Loading ...<br /></b>";
# ob_flush();
# flush();

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

$urlfile = "http://dl.generatorlinkpremium.com/?id=y3sA3C0ffLbpG5n9ragYYLGhl&h=1";
$fileName = "leyla.rar";
echo "Local File Name	: " .$fileName . "<br />";
echo "Remote URL	: " .$urlfile . "<br />";
$ch = curl_init(); // oturum baslat
// http://www.denizyildirim.com/buyukdosya.zip sekilinde olacak
curl_setopt($ch, CURLOPT_URL, $urlfile);
if (file_exists($fileName)) 
{ // Eğer daha önce indirilmişse
	$from = filesize($fileName); // nekadarı indiğini öğren
	/* Eğer inen dosya remote dosyadan eşit yada büyükse bu işlemi yap */
	$remotefrom = remote_file_size($urlfile);
	echo "RemoteSize	: " . $remotefrom . " Byte<br />";
	if (is_int($remotefrom) && $from == $remotefrom)
	{
		echo "<b>Dosya zaten inmiş. ==> İşlem yapılmadı! ==> Remote Size == Local Size birbirine eşit :)</b>";
		curl_close($ch); // Curl işlemini bitir
		exit;
	}
	elseif (is_int($remotefrom) && $from > $remotefrom)
	{
		echo "Local file ile Remote file size değerleri birbirinden farklı! :((<br />";
		echo "Dosya silindi! <br />";
		echo "Sayfayı yenile! <br />";
		unlink($fileName);
		curl_close($ch); // Curl işlemini bitir
		exit;
	}	
	curl_setopt($ch, CURLOPT_RANGE, $from . "-"); // ne kadarı indiyse o noktadan downloada başla
}
$fp = fopen ($fileName, 'a'); // İndirdiğimiz dosyamızın kaldığı yerden tamamlayacak şekilde aç (Default değeri 'a+' dır.)
// Servera hangi browser kullandığımızı gönderelim
# curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.93 Safari/537.36');
@chmod($fileName, 0755); // kayıtlı dosyaya yazma hakkımız yoksa o hakkı verelim
curl_setopt($ch, CURLOPT_TIMEOUT, 18); // download işlemi için ne kadar uğraşsın (Default: 1950 saniye)
## curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, 'progress'); // Progress-bar fonksiyonumuz
curl_setopt($ch, CURLOPT_NOPROGRESS, true); // üstteki fonksiyonun çalışması için (Default: false)
curl_setopt($ch, CURLOPT_FILE, $fp); // Curl işleminin dosya download olduğunu belirtiyoruz
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // yönlendirme varsa takip et
## curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Yorum satırı olarak kalmalı 0 kb sorunu buradan kaynaklanıyor.

curl_exec($ch); // Curl işlemine başla
$curl_dump = curl_getinfo($ch); // İstatistik değerlerini dizi şeklinde al
$dlhizi = $curl_dump['speed_download']; // curl_getinfo($ch, CURLINFO_SPEED_DOWNLOAD);	// New byte/sn cinsinden
curl_close($ch); // Curl işlemini bitir
fclose($fp); // Açtığımız dosyayı kapat.
// 2 saniye bekle
usleep(2000000);
chmod($fileName, 0644); // Dosya yazma iznini kapat.

echo "Local Size	: ". filesize($fileName). " Byte\n";
$tamamlanma_yuzdesi = filesize($fileName) / $remotefrom * 100;
echo "Tamamlanma Yüzdesi: %" .$tamamlanma_yuzdesi ."<br />";
echo "Download Hızı	: " .(int)$dlhizi / 1024 . " KB/sn<br />";
echo "<p><b>ÖZET:</b></p>";
print_r($curl_dump); // Detaylı curl özeti

// Tamamlanma yüzdesi eksikse sayfa refresh edilecek
	if ($tamamlanma_yuzdesi < 100) 
		{
		// Timeout buraya gelsin			
		$delay=abs(($startTime + $timeout) - time()); //Where 0 is an example of time Delay you can use 5 for 5 seconds for example !
		echo "\n <b>$delay saniye sonra sayfa otomatik olarak REFRESH edilecektir...</b>\n";
		echo "<center><b>Lütfen beklemeye devam ediniz!!!</b></center>\n";
		usleep(1000000 * $delay);
		$delay = 1;
		// Timeout buraya gelsin SON
		header("Refresh: $delay;");
		}
// refresh son..	
echo "<b>Done</b></pre>";
ob_end_flush(); // Çıktı tamponunu temizler (siler) ve tamponu kapatır
session_unset();
session_destroy(); //destroy the session
?>
