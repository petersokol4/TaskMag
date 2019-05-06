<?php
/**
 * Copyright (c) 2019. TaskMag project managing web app. Created by Peter Sokol for Diploma Thesis.
 */
session_start();

set_time_limit(0);

require_once(__DIR__ . "/../controllers/AttachmentController.php");

$attachController = new AttachmentController();

// Download folder, i.e. folder where you keep all files for download.
// MUST end with slash (i.e. "/" )
define('ATTACH_DIR','../../../public_html/uploads/projects/');

$messages= array();

if($_FILES["attach"]["name"] != '' && !empty($_POST["taskId"] && !empty($_POST["projectId"]) && !empty($_POST["attachDir"]) && !empty($_SESSION["user"]["id"])))
{
    $taskId = $attachController->sanitizeNumber($_POST["taskId"]);
    $projectId = $attachController->sanitizeNumber($_POST["projectId"]);
    $userId = $attachController->sanitizeNumber($_SESSION["user"]["id"]);
    $attachDir = $attachController->sanitizeNumber($_POST["attachDir"]);

    $data = explode(".", $_FILES["attach"]["name"]);
    $originalName=$_FILES["attach"]["name"];
    $extension = strtolower(end($data));
    $allowed_extension = ATTACHEMENTS_EXTENSIONS;
    if(in_array($extension, $allowed_extension))
    {
        $newFileName = $attachController->generateName().'.'.$extension;
        $path = ATTACH_DIR.$attachDir."/".$newFileName;



        if(move_uploaded_file($_FILES["attach"]["tmp_name"], $path))
        {

            //update db and delete old file
            if($attachController -> uploadAttach($newFileName, $originalName, $taskId, $projectId, $attachDir, $userId))
            {

                echo json_encode( ['code' => 200, 'msg' =>'Príloha bola nahraná.', 'attach_new' =>$newFileName, 'attach_orig' =>$originalName]);

            }
            else
            {

                if(file_exists($path)){
                    unlink($path);
                }
                echo json_encode( ['code' => 404, 'msg' =>"Nastala chyba pri nahrávaní prílohy do DB. Príloha nebola nahraná. Kontaktujte podporu."]);
            }


        }
        else
        {
            //výpis chýb
            /*
             * UPLOAD_ERR_INI_SIZE = Value: 1; The uploaded file exceeds the upload_max_filesize directive in php.ini.

                UPLOAD_ERR_FORM_SIZE = Value: 2; The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.

                UPLOAD_ERR_PARTIAL = Value: 3; The uploaded file was only partially uploaded.

                UPLOAD_ERR_NO_FILE = Value: 4; No file was uploaded.

                UPLOAD_ERR_NO_TMP_DIR = Value: 6; Missing a temporary folder. Introduced in PHP 5.0.3.

                UPLOAD_ERR_CANT_WRITE = Value: 7; Failed to write file to disk. Introduced in PHP 5.1.0.

                UPLOAD_ERR_EXTENSION = Value: 8; A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop; examining the list of loaded extensions with phpinfo() may help.
             *
             */
            echo json_encode( ['code' => 404, 'msg' =>"Príloha nebola uploadnutá kvôli chybe: #".$_FILES["attach"]["error"] .". Kontaktujte podporu."]);
        }


    }
    else{
        echo json_encode( ['code' => 404, 'msg' =>"Nedovolený typ súboru. Ak potrbujete uploadovať tento súbor, uploadujte ho v archíve ZIP."]);
    }
}
else{
    echo json_encode( ['code' => 404, 'msg' =>"Prosím, zvoľte súbor."]);
}