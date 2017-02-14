<?php
#Kaynak1:	https://tech.yandex.com/direct/doc/dg-v4/examples/auth-token-sample-docpage/#get-token
#Kaynak2:	https://tech.yandex.com/direct/doc/dg/examples/auth-token-sample-docpage/
#Kaynak3:	http://php.net/manual/tr/function.file-put-contents.php
#Kaynak4:	http://stackoverflow.com/questions/768431/how-to-make-a-redirect-in-php
#Kaynak5:	http://stackoverflow.com/questions/15110355/how-to-safely-get-full-url-of-parent-directory-of-current-php-page

// Token File Name
$yandex_tokenfile = "yandex.token";	# Yandex token değişken dosyası

// App ID
$client_id = '422172bb6af140c689dab754547c168e'; 
// App password
$client_secret = '162616c1e7714ceabf9083a3eec496ee';

// If the script was called with the "code" parameter in the URL,
// the request to get a token is executed
if (isset($_GET['code']))
  {
    // Forming parameters (the body) of the POST request, specifying the authorization code
    $query = array(
      'grant_type' => 'authorization_code',
      'code' => $_GET['code'],
      'client_id' => $client_id,
      'client_secret' => $client_secret
    );
    $query = http_build_query($query);

    // Forming headers of the POST request
    $header = "Content-type: application/x-www-form-urlencoded";

    // Executing the POST request and outputting results
    $opts = array('http' =>
      array(
      'method'  => 'POST',
      'header'  => $header,
      'content' => $query
      ) 
    );
    $context = stream_context_create($opts);
    $result = file_get_contents('https://oauth.yandex.com/token', false, $context);
    $result = json_decode($result);

    // Save the token for using in requests to the Direct API
#    echo $result->access_token;
	
	// İçeriği dosyaya yazalım	-> Kaynak3
	file_put_contents($yandex_tokenfile, $result->access_token, FILE_TEXT);	#Kaynak3
	chmod($yandex_tokenfile, 0600); // Dosya yazma iznini kapat. (Sadece sahibi yazar ve okur, diğerleri erişemez grubu da dahil)
	
	// yandex-upload.php dosyasına gitmesi için mesaj verelim ve link ekleyelim
	echo "<br /><p><b>Token değeri başarılı bir şekilde alındı ve ilgili dosyaya yazıldı.</b></p><br />";
	echo "<font color='green'>Bu adresten yeniden yandex-upload.php sayfasına gidebilirsin --> </font><b><a href='";
	$yandexuploadredirectaddress = 'http://' . $_SERVER['HTTP_HOST'] . '/grabber/yandex-upload.php';		#Kaynak5
	echo $yandexuploadredirectaddress;																		
	echo "'>$yandexuploadredirectaddress</a><br /><br /></b>";
  }
  else
  {
	  // İlk kez authorization olup code değişkenini isteyeceğiz. --> ?code=9008997
	  header("Location: https://oauth.yandex.com/authorize?response_type=code&client_id=" . $client_id);	#Kaynak4
	  die();																								#Kaynak4
  }
?>
