<?php
    $zip = new ZipArchive();
    $zip_status = $zip->open("MT6572__Samsung__SM-J500HDS__Samsung_J500HDS__4.4.2__ALPS.KK1.MP7.V1.zip");
    if ($zip_status === true)
    {
        if ($zip->setPassword("keytak72"))
        {
            if (!$zip->extractTo(__DIR__))
                echo "Extraction failed (wrong password?)";
        }
        $zip->close();
        echo "Başarılı bir şekilde açıldı.";
    }
    else
    {
        die("Failed opening archive: ". @$zip->getStatusString() . " (code: ". $zip_status .")");
    }
?>
