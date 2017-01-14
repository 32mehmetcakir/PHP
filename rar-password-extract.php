<?php

$rarfilename = 'DikeySupurge.rar';
$rarpassword = 'anam';

/* example by Erik Jenssen aka erix */
$rarfilepath = dirname(__FILE__).'/'; // "/home/foo/bar/";
$rar_file = rar_open($rarfilepath.$rarfilename, $rarpassword);
if ($rar_file === FALSE)
{
    die("Failed opening file (Dosya açma başarısız!!!)");
	rar_close($rar_file);
	exit;
}
	echo "<p><b>Dosyalar Çıkartılıyor...</b></p><pre>";
$list = rar_list($rar_file);
foreach($list as $file) {
    $entry = rar_entry_get($rar_file, $file->getName());
    if ($entry->extract(".")) // extract to the current dir
	echo "Açılan dosya: <font color='red'>". $file->getName(). "</font> -> <font color='green'><b>OK</b></font>\n";
	else
	echo "Açılan dosya: <font color='blue'>". $file->getName(). "</font> -> <font color='red'><b>FAİL!</b></font>\n";
	flush();
}
echo "-----------------\n";
echo "<b>İşlem tamam :)</b></pre>";
rar_close($rar_file);
?>
