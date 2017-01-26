* Bu script Remote File (URL) dan server'a dosya indirir/copyalar/transfer eder.
* Script in tek zayıf yönü php timeout a takılması yüzünden büyük dosyalarda > 300MB işlem yarıda kalmaktadır!
* Jquery kullandığı için sürekli download ediyor görünmekte ancak timeout süresi bitince aslında serverda dosya transfer işlemi
  sona ermektedir ama biz browserda hala devam ediyor şeklinde algılıyoruz.
Fix:
-> invalid URL hatasını gidermek için, upload.php dosyasının 9. satırına "rar","zip" değerleri eklendi.
