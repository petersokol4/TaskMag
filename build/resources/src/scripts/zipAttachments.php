<?php
/**
 * Copyright (c) 2019. TaskMag project managing web app. Created by Peter Sokol for Diploma Thesis.
 */

session_start();

//set local time for this script for basename function
setlocale(LC_ALL,"sk_SK.utf8");

set_time_limit(0);

require_once(__DIR__ . "/../controllers/AttachmentController.php");

$zipController = new AttachmentController();

// Allow direct file download (hotlinking)?
// Empty - allow hotlinking
// If set to nonempty value (Example: example.com) will only allow downloads when referrer contains this text
define('ALLOWED_REFERRER', '');

// Download folder, i.e. folder where you keep all files for download.
// MUST end with slash (i.e. "/" )
define('ATTACH_DIR','../../../public_html/uploads/projects/');

// log zipping?  true/false
define('LOG_ZIPPING',true);

// log file name
define('LOG_FILE','../../zip.log');

// array for errors
$messages = array();

// if was send id from AJAX
if (isset($_POST["createZIP"]) && isset($_POST["dirZIP"]) &&isset($_POST["projectZIP"])) {

    // If hotlinking not allowed then make hackers think there are some server problems
    if (ALLOWED_REFERRER !== '' && (!isset($_SERVER['HTTP_REFERER']) || strpos(strtoupper($_SERVER['HTTP_REFERER']),strtoupper(ALLOWED_REFERRER)) === false))
    {
        die("Systémová chyba. Prosím, kontaktujte podporu.");
    }

    //TODO cez ajax poslať ešte DIR
    $dir=$zipController->sanitizeNumber($_POST["dirZIP"]);
    $project=$zipController->checkOutput($_POST["projectZIP"]);

    $fileFolder = ATTACH_DIR.$dir."/";

    if(extension_loaded('zip'))
    {
        if(isset($_POST["attach"]) && count($_POST["attach"])>0)
        {
            $zip = new ZipArchive();
            $zipName = "TaskMag-".$project."-attachements-".(new DateTime())->setTimezone(new DateTimeZone('Europe/Prague'))->format('d-m-Y').".zip";
            $path = $fileFolder.$zipName;
            if($zip->open($path, ZIPARCHIVE::CREATE)!==TRUE)
            {
                echo "Vytváranie ZIP archývu zlyhalo.";
                die();
            }
            else
            {
                foreach ($_POST["attach"] as $file)
                {
                    if(file_exists($fileFolder.$file)) {
                        //adding files into ZIP archive
                        $zip->addFile($fileFolder.$file,$file);
                    }
                }

                $zip->close();

                if(file_exists($path))
                {

                    $fileSize = filesize($path);

                    //push to download the zip
                    header("Pragma: public");
                    header("Expires: 0");
                    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                    header("Cache-Control: public");
                    header("Content-Description: File Transfer");
                    header("Content-Type: application/zip");
                    header("Content-Disposition: attachment; filename=$zipName");
                    header("Content-Transfer-Encoding: binary");
                    header("Content-Length: " . $fileSize);


                    // download
                    //alternatívna funkcia:
                    //readfile($path);
                    $file = fopen($path,"rb");
                    if ($file) {
                        while(!feof($file)) {
                            set_time_limit(0);
                            print(fread($file, 1024*8));
                            flush();
                            if (connection_status()!=0) {
                                fclose($file);
                                die();
                            }
                        }
                        fclose($file);
                    }

                    //delete ZIP
                    unlink($path);

                    // log downloads
                    if (!LOG_ZIPPING) die();

                    //open or create file if doesn't exist
                    $f = fopen(LOG_FILE, 'a+');
                    if ($f) {
                        fputs($f, date("d.m.Y H:i:s")."  ".$_SERVER['REMOTE_ADDR']."  ".$zipName."\r\n");
                        fclose($f);
                    }

                    die();
                }

            }
        }
        else
        {
            echo "Neboli zvolené žiadne súbory.";
            die();
        }
    }
    else
    {
        echo "Systémová chyba. Možno nemáte rozšírenie ZIP. Kontaktujte podporu.";
        die();
    }

} else {

    $zipController->redirect("../../../public_html/");
    die();
}
