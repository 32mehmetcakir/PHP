<?php
#Kaynak:	http://selcuk.in/yandex-disk-webdav-ile-dosya-yukleme.html
#Kaynak2:	http://php.net/manual/tr/function.curl-getinfo.php
#Kaynak3:	http://stackoverflow.com/questions/11797680/curl-getting-http-code
#Kaynak4:	https://tr.wikipedia.org/wiki/HTTP_durum_kodları
#Kaynak5:	http://www.r10.net/php/1206285-php-ile-iki-sayi-arasi-kontrolu.html

/*
Fonksiyondaki $file değişkeni  yüklenecek dosyanın konumu olarak değişti.			#Fonksiyondaki $file değişkeni HTML FORM dan gelen $_FİLES global dizi değişkeni olmalıdır.
Örnek olarak yandexDiskUpload('user', 'password', 'babo.zip', 'fileNameNew', '');	#Örnek olarak yandexDiskUpload('user', 'password', $_FILES['file'], 'fileNameNew', '');
*/
function yandexDiskUpload($user, $password, $file, $fileName = NULL, $folder = NULL)
{
        $yandexWebDavUrl = $folder ? "https://webdav.yandex.com.tr/$folder/" : "https://webdav.yandex.com.tr/";
        $fileHandler = fopen($file, 'r');
        $fileDetails = explode('.', strtolower($file));
        $findExtension = end($fileDetails);
        if (isset($fileName)) $fileName = $fileName.'.'.$findExtension; else $fileName = uniqid().'.'.$findExtension;

        $ch = curl_init($yandexWebDavUrl . $fileName);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_USERPWD, "$user:$password");
        curl_setopt($ch, CURLOPT_PUT, TRUE);
        curl_setopt($ch, CURLOPT_INFILE, $fileHandler);
        $result = curl_exec($ch);

        fclose($fileHandler);
		
		// Benim kodlarım ÇBP
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);	#Kaynak3
		// Tanıtıcıyı kapatalım
		curl_close($ch);										#Kaynak2
		if (!in_array($http_status, range(200, 202))) {			#Kaynak4 + #Kaynak5
			return false;
		}
		// Benim kodlarım ÇBP SON
        return $result ? $yandexWebDavUrl . $fileName : false;      
}

// Yandex WEBDAV a gönderme yapıyoruz..
$sonuc = yandexDiskUpload('leech@mehmetcakir.eu.org', 'password', 'babo.zip', null, 'Downloads');

if ($sonuc) {
	$slaclardanparcala = explode('/', $sonuc);
echo "<pre>";
echo "Dosya şuraya yüklenmiştir. --> <b>" . $sonuc . "\t (<font color='red'>Direct link'tir, dosya indirilebilir.</font>)</b><br />";
echo "<p>Aynı zamanda Yandex Diskten de erişilebilir. Yüklendiği klasör ise: <b><font color='green'>" 
		. $slaclardanparcala[count($slaclardanparcala)-2] . "/"
		. end($slaclardanparcala) . "</font></b></p></pre>";
}
else
	echo "<p><b>Yükleme başarısız oldu!!!\t (<font color='blue'>Hata ayrıntı kodu yukarıdadır ^</font>)</b></p>";
?>
