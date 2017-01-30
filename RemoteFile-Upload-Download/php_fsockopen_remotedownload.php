<?php
#Kaynak : http://www.ankur.com/blog/106/php/resume-http-downloads-php-curl-fsockopen/
#Kaynak2: http://stackoverflow.com/questions/5153261/unknown-modifier-when-using-preg-match-with-a-regex-expression
#Not: "progress.txt" dosyası ilk kurulumda manuel olarak oluşturulmalıdır!!! (içeriğine ilk kurulumda 0 değeri yazılabilir)
/**
    Download remote file in php using curl
    Files larger that php memory will result in corrupted data
*/
# set_time_limit(40);
session_start(); //to ensure you are using same session
ob_start(); // start output buffer
ini_set('max_execution_time', 300);
ini_set('memory_limit', '512M');
echo "<pre>";
echo "Loading ...<br />";
# ob_start();
# ob_flush();
# flush();

$urlfile = "http://www.mega-debrit.com/index.php/MEGA/mega_eab4ba20d6/www.mega-debrit.com_MT6572__alps___tangxun6572_we_l__5.1__ALPS.L1.MP6.V2.8_TANGXUN6572.WE.L.rar";
$fileName = "leyla.rar";
echo "Local File Name: " .$fileName . "<br />";
echo "Remote URL     : " .$urlfile . "<br />";

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

$remotefrom = 0;
if (file_exists($fileName)) 
{ // Eğer daha önce indirilmişse
	$from = filesize($fileName); // nekadarı indiğini öğren
	/* Eğer inen dosya remote dosyadan eşit yada büyükse bu işlemi yap */
	$remotefrom = remote_file_size($urlfile);
	echo "RemoteSize: " . $remotefrom . "<br />";
	if ($from == $remotefrom)
	{
		echo "Dosya zaten inmiş. İşlem yapılmadı!";
		exit();
	}
	elseif ($from > $remotefrom)
	{
		echo "Local file ile Remote file size değerleri birbirinden farklı!<br />";
		echo "Dosya silindi! <br />";
		echo "Sayfayı yenile! <br />";
		unlink($fileName);
		exit();
	}
}

chmod($fileName, 0755); // kayıtlı dosyaya yazma hakkımız yoksa o hakkı verelim

// Timeout
$startTime = time();
$timeout = 60;   //timeout in seconds

// fsockopen
$partialContent = true;
$finalFileSize = 0;

$urlParts = parse_url($urlfile);
$socketHandler = fsockopen($urlParts["host"], 80, $errno, $errstr, 30);
if (!$socketHandler) {
	exit;
} else {
	$from = 0;

	if (file_exists($fileName)) {
		$from = filesize($fileName);
	}

	$out = "GET " . $urlParts["path"] . " HTTP/1.1\r\n";
	$out .= "Host: " . $urlParts["host"] . "\r\n";
	$out .= "Range: bytes=" . $from . "-\r\n";
	$out .= "Connection: Close\r\n\r\n";

	$headerFound = false;

	if (!$fileHandler = fopen($fileName, "a")) {
		exit;
	}

	fwrite($socketHandler, $out);
	while (!feof($socketHandler)) {
		// Timeout buraya gelsin
		if(time() > $startTime + $timeout) {
		break; }
		// Timeout buraya gelsin SON
		if (filesize($fileName) == $remotefrom)
			break;
		
		if ($headerFound) {
			if ($partialContent) {
				$result = fread($socketHandler, 8192);
				if (fwrite($fileHandler, $result) === false) {
					exit;
				}
			} else {
				fclose($fileHandler);
				fclose($socketHandler);
				exit;
			}
		} else {
			$result = fgets($socketHandler, 8192);
			$result = trim($result);
			if ($result === "") {
				$headerFound = true;
			}

			if (strstr($result, "206 Partial Content")) {
				$partialContent = true;
			}

			if (preg_match("/^Content-Range: bytes (d+)-(d+)\/(d+)$/", $result, $matches)) {
				$finalFileSize = intval($matches[3]);
			}
		}
	}
	fclose($fileHandler);
	fclose($socketHandler);

	clearstatcache();
	
	// Tamamlanan yüzdeyi echo et
	if ($finalFileSize > 0)
		$remotefrom = $finalFileSize;
	$localfilesize = filesize($fileName);
	$tamamlananyuzde = $localfilesize / $remotefrom * 100;
	echo "Tamamlanma Yüzdesi: %" . $tamamlananyuzde . "<br />";

	if ($localfilesize == $finalFileSize) {
		// success
		chmod($fileName, 0644); // Dosya yazma iznini kapat.
		echo "Done";
	} else {
		ob_flush();
		flush();
		session_unset();
		session_destroy(); //destroy the session
		$delay=1; //Where 0 is an example of time Delay you can use 5 for 5 seconds for example !
		header("Refresh: $delay;"); 
		exit;
	}
}
// fsockopen son
?>
