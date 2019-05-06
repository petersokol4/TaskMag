<?php
/**
 * Copyright (c) 2019. TaskMag project managing web app. Created by Peter Sokol for Diploma Thesis.
 */
session_start();

require_once(__DIR__ . "/../controllers/UserController.php");

$avatarController = new UserController();

$messages= array();

if($_FILES["avatar"]["name"] != '' && !empty($_SESSION["user"]["id"] && !empty($_SESSION["user"]["avatar"])))
{
    $id = $avatarController->sanitizeNumber($_SESSION["user"]["id"]);
    $oldAvatar = $avatarController->checkOutput($_SESSION["user"]["avatar"]);
    $data = explode(".", $_FILES["avatar"]["name"]);
    $extension = strtolower(end($data));
    $allowed_extension = AVATARS_EXTENSIONS;
    if(in_array($extension, $allowed_extension))
    {
        if($_FILES["avatar"]["error"] === 0)
        {
            if($_FILES["avatar"]["size"] < AVATARS_MAX_FILE_SIZE)
            {
                $newFileName = $avatarController->generateName().'.'.$extension;
                $path = "../../../public_html/uploads/users/".$newFileName;
                $pathOld = "../../../public_html/uploads/users/".$oldAvatar;

                if(move_uploaded_file($_FILES["avatar"]["tmp_name"], $path))
                {

                    //update db and delete old file
                    if($avatarController -> changeAvatar($newFileName, $id))
                    {
                        //delete old file if it is not default
                        if($oldAvatar != "64656661756c74.jpg")
                        {
                            if(file_exists($pathOld)){
                                unlink($pathOld);

                            }
                        }

                        $_SESSION["user"]["avatar"] = $newFileName;
                        echo json_encode( ['code' => 200, 'msg' =>"upload ok"]);

                    }
                    else
                    {
                        if(file_exists($path)){
                            unlink($path);

                        }
                        echo json_encode( ['code' => 404, 'msg' =>"Nastala chyba pri zmene obrázka. Kontaktujte podporu."]);
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
                    echo json_encode( ['code' => 404, 'msg' =>"Obrázok nebol uploadnutý kvôli chybe: #".$_FILES["avatar"]["error"] .". Kontaktujte podporu."]);
                }

            }
            else
            {
                echo json_encode( ['code' => 404, 'msg' =>"Bola prekročená maximálna povolená veľkosť."]);
            }
        }
        else
        {
            echo json_encode( ['code' => 404, 'msg' =>"Obrázok nebol uploadnutý kvôli chybe: #".$_FILES["avatar"]["error"] .". Kontaktujte podporu."]);
        }
    }
    else{
        echo json_encode( ['code' => 404, 'msg' =>"Nahrať môžete len obrázky s príponami .jpg, .png alebo .gif"]);
    }
}
else{
    echo json_encode( ['code' => 404, 'msg' =>"Prosím, zvoľte súbor"]);
}