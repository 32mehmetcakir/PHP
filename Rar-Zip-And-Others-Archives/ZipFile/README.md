Açılamayan (php nin dahili zip classları ve, diğer yazılmış classlarda dahil, unzipper, zip-password-extract.php, zip-info gibi.)
zip arşivlerini açıyor.
* Sadece create, add, read gibi işlemler yapılıyor.
* Okuma ve listelem konusunda çok iyi test edildi.
* Extract işlemi şuan yapılamıyor!!!
* Şu kodlar muhakkak example1.php, example2.php gibi kullanılacak php dosyalarının en tepesine eklenmelidir.
** ini_set('max_execution_time', 0);
** ini_set('memory_limit', '1024M');