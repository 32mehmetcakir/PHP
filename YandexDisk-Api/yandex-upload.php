<?php
#Kaynak1:	https://toster.ru/q/197453
#Kaynak2:	http://stackoverflow.com/questions/11797680/curl-getting-http-code
#Kaynak3:	http://stackoverflow.com/questions/15110355/how-to-safely-get-full-url-of-parent-directory-of-current-php-page

# Bilgi:	http yöntemi ile yandex'e dosya gönderme
# Tanımlamalar:
$yandex_tokenfile = "yandex.token";													# Yandex token değişken dosyası
$filename = 'eXtplorer_2.1.9.zip';													# Değiştirebilirsin
$DownloadURL = 'https://doughty-surprise.000webhostapp.com/eXtplorer_2.1.9.zip';	# Değiştirebilirsin
$yandexpath = 'Downloads/' . $filename;												# Öyle kalsın!

define("TOKEN", file_get_contents($yandex_tokenfile, FILE_TEXT));			# Token değeri dosyadan çekiliyor.
$url = "https://cloud-api.yandex.net:443/v1/disk/resources/upload?path=" . $yandexpath . "&url=" . $DownloadURL . "&disable_redirects=true";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url); 
$header = array(
    'Accept: application/json',
    'Authorization: OAuth '. TOKEN ,
);
curl_setopt($ch, CURLOPT_HEADER, true);	// Ben ekledim.
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_POST, 1); 
$result = curl_exec($ch);
$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);	//Kaynak2
curl_close($ch);
#echo $result;

echo "<pre>";
// http status code
echo "<b> HTTP STATUS KODU: </b><hr>";
echo $http_status;
echo "<hr /><br />";
// Eğer çıkan kod 401 ise token değeri hatalıdır. Yeniden alınması lazım: (HTTP/1.1 401 UNAUTHORIZED)
if ($http_status == 401) {
	echo "<p><b>Token değeri hatalı veya süresi dolmuş olabilir! </b></p><br />";
	// yandex-get-token.php dosyasına yönlendirme yapılıyor... 
	echo "<font color='green'>Bu adresten yeniden alabilirsin --> </font><b><a href='";
	$yandexgettokenredirectaddress = 'http://' . $_SERVER['HTTP_HOST'] . '/grabber/yandex-get-token.php';	#Kaynak3
	echo $yandexgettokenredirectaddress;
	echo "'>$yandexgettokenredirectaddress</a><br /><br /></b>";
}

// Curl çıktısı + Son satır JSON çıktısıdır.
echo "<b> JSON ÇIKTISI (En sondaki satır):</b><hr><br />";
print_r($result);
echo "</pre>";
echo "<hr />";
?>
