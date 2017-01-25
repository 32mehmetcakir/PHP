<?php
function remote_file_exists($url){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if($httpCode == 200 ){return true;}
    return false;
}

function remote_file_size($url)
{
	# Get all header information
	$data = get_headers($url, true);
	# Look up validity
	if (isset($data['Content-Length']))
    # Return file size
    return (int) $data['Content-Length'];
}
?>
