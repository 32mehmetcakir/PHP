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

function filemtime_remote($uri)
{
    $uri = parse_url($uri);
    $handle = @fsockopen($uri['host'],80);
    if(!$handle)
        return 0;

    fputs($handle,"GET $uri[path] HTTP/1.1\r\nHost: $uri[host]\r\n\r\n");
    $result = 0;
    while(!feof($handle))
    {
        $line = fgets($handle,1024);
        if(!trim($line))
            break;

        $col = strpos($line,':');
        if($col !== false)
        {
            $header = trim(substr($line,0,$col));
            $value = trim(substr($line,$col+1));
            if(strtolower($header) == 'last-modified')
            {
                $result = strtotime($value);
                break;
            }
        }
    }
    fclose($handle);
    return $result;
}

?>
