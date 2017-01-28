1. Öncelikli açılamayan zip dosylarını başarılı bir şekilde listesini çıkaran ve açmaya çalışan php scriptir.
* Php dahili classlarının, unzipper, zip-info gibi extra classların bile açamadığı zip arşivlerini açar.
* Ayrıntılı listeleme yapar.
* 200MB üzeri dosyalarda çıkartma yaparken sorun çıkmaktadır ! Sorun: gzinflate(): data error
* sample.php dosyasının en üst satırına aşağıdaki kodları eklemdim.
ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');
