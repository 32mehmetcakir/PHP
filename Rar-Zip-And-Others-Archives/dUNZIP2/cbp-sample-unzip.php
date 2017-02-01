<?
require_once dirname(__FILE__)."/dUnzip2.inc.php";

ini_set('max_execution_time', 0);
ini_set('memory_limit', '2024M');

######## Then, load the file again. Now, to unzip it ########
echo "<hr>";

$zipfilepath = '../grabber/babo.zip';	# Zip dosyasının adresi
$zipExtractDir = 'ext';					      # Zip arşivinin çıkartılacağı klasör
$zip = new dUnzip2($zipfilepath);

// Activate debug
$zip->debug = true;

// Unzip all the contents of the zipped file to a new folder called "uncompressed"
#$zip->getList();
@mkdir($zipExtractDir);
$zip->unzipAll($zipExtractDir);

$zip->close();
?>
