<?php

session_start();

/*GENERAL SETTINGS*/

//set local time for this script for basename function
setlocale(LC_ALL,"sk_SK.utf8");

// Allow direct file download (hotlinking)?
// Empty - allow hotlinking
// If set to nonempty value (Example: example.com) will only allow downloads when referrer contains this text
define('ALLOWED_REFERRER', '');

// Download folder, i.e. folder where you keep all files for download.
// MUST end with slash (i.e. "/" )
define('ATTACH_DIR','../../../public_html/uploads/projects/');

// log downloads?  true/false
define('LOG_DOWNLOADS',true);

// log file name
define('LOG_FILE','../../downloads.log');

// Make sure program execution doesn't time out
// Set maximum script execution time in seconds (0 means no limit)
set_time_limit(0);


require_once(__DIR__ . "/../controllers/TaskController.php");

$downloadAttachController = new TaskController();


if (isset($_REQUEST["id"])) {

    // check if inputs are empty -> add error message to array
    if (empty($_REQUEST["id"]) || empty($_REQUEST["name"]) || empty($_REQUEST["dir"])) {
        die("Vyskytla sa chyba pri sťahovaní prílohy.");
    }

    // If hotlinking not allowed then make hackers think there are some server problems
    if (ALLOWED_REFERRER !== '' && (!isset($_SERVER['HTTP_REFERER']) || strpos(strtoupper($_SERVER['HTTP_REFERER']),strtoupper(ALLOWED_REFERRER)) === false))
    {
        die("Systémová chyba. Prosím, kontaktujte podporu.");
    }

    // assign and sanitize (only numbers)
    $id = $downloadAttachController->sanitizeNumber($_REQUEST["id"]);
    $dir = $downloadAttachController->sanitizeNumber($_REQUEST["dir"]);

    // Get file name.
    // Remove any path info to avoid hacking by adding relative path, etc.
    //returns the filename from a path
    //basename() is locale aware, so for it to see the correct basename with multibyte character paths, the matching locale must be set using the setlocale() function.
    $name = basename($downloadAttachController->checkInput($_REQUEST["name"]));

    $path = ATTACH_DIR.$dir."/".$name;
    $ext = strtolower(pathinfo($path,PATHINFO_EXTENSION));
    // file size in bytes
    $fileSize = filesize($path);

    // Allowed extensions list in format 'extension' => 'mime type'
    // If myme type is set to empty string then script will try to detect mime type itself, which would only work if you have Mimetype or Fileinfo extensions installed on server.
    $allowedExtensions = array (

        //all here -> http://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types

        // texts
        'txt' => 'text/plain',
        //'htm' => 'text/html',
        //'html' => 'text/html',
        //'php' => 'text/html',
        //'css' => 'text/css',
        //'js' => 'application/javascript',
        //'json' => 'application/json',
        //'xml' => 'application/xml',
        //'swf' => 'application/x-shockwave-flash',

        // archives
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        //'exe' => 'application/x-msdownload',
        //'msi' => 'application/x-msdownload',
        //'cab' => 'application/vnd.ms-cab-compressed',

        // ms office
        'doc' => 'application/msword',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',
        'docx' => 'application/msword',
        'xlsx' => 'application/vnd.ms-excel',
        'pptx' => 'application/vnd.ms-powerpoint',
        'rtf' => 'application/rtf',

        //adobe
        'pdf' => 'application/pdf',
        //'psd' => 'image/vnd.adobe.photoshop',
        //'ai' => 'application/postscript',
        //'eps' => 'application/postscript',
        //'ps' => 'application/postscript',

        // open office
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',

        // images
        'png' => 'image/png',
        //'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        //'bmp' => 'image/bmp',
        //'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'svg' => 'image/svg+xml',
        //'svgz' => 'image/svg+xml',

        // audio
        'mp3' => 'audio/mpeg',
        'aac' => 'audio/x-aac',
        'wav' => 'audio/x-wav',

        // video
        'mpeg' => 'video/mpeg',
        'mpg' => 'video/mpeg',
        //'mpe' => 'video/mpeg',
        'mov' => 'video/quicktime',
        'avi' => 'video/x-msvideo',
        //'qt' => 'video/quicktime',
        //'flv' => 'video/x-flv'
    );

    if(file_exists($path)){

        // check if allowed extension
        if (!array_key_exists($ext, $allowedExtensions)) {
            die("Nepovolený typ súboru.");
        }

        // get mime type
        if ($allowedExtensions[$ext] == '') {
            $mimeType = '';
            // mime type is not set, get from server settings
            if (function_exists('mime_content_type')) {
                $mimeType = mime_content_type($path);
            }
            else if (function_exists('finfo_file')) {
                $finfo = finfo_open(FILEINFO_MIME); // return mime type
                $mimeType = finfo_file($finfo, $path);
                finfo_close($finfo);
            }
            //default
            if ($mimeType == '') {
                $mimeType = "application/force-download";
            }
        }
        else {
            // get mime type defined upper
            $mimeType = $allowedExtensions[$ext];
        }


        //DEFINE HEADERS

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Type: $mimeType");
        header("Content-Disposition: attachment; filename=$name");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . $fileSize);

        // download
        //alternatívna funkcia:
            // readfile($path);
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

        // log downloads
        if (!LOG_DOWNLOADS) die();

        //open or create file if doesn't exist
        $f = fopen(LOG_FILE, 'a+');
        if ($f) {
            fputs($f, date("d.m.Y H:i:s")."  ".$_SERVER['REMOTE_ADDR']."  ".$name."\r\n");
            fclose($f);
        }

        die();
    }
    else
    {
        die("Súbor neexistuje.");
    }

} else {

    $downloadAttachController->redirect("../../../public_html/");
    die();
}
