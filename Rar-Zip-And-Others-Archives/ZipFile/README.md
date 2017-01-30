Açılamayan (php nin dahili zip classları ve, diğer yazılmış classlarda dahil, unzipper, zip-password-extract.php, zip-info gibi.)
zip arşivlerini açıyor.
* 3. öncelikli kullandığım zip sınıfı olarak güncellendi.
* Sadece create, add, read, extract gibi işlemler yapılıyor.
* Okuma ve listeleme konusunda çok iyi test edildi.
* Şu kodlar muhakkak example1.php, example2.php gibi kullanılacak php dosyalarının en tepesine eklenmelidir.
** ini_set('max_execution_time', 0);
** ini_set('memory_limit', '1024M');
* example2.php den cbp-zip-list-extract.php dosyası türetildi.
