<?php

session_start();

require_once(__DIR__ . "/../controllers/AttachmentController.php");

$deleteAttachController = new AttachmentController();

// array for errors
$messages = array();

// if was send id from AJAX
if (isset($_POST["id"])) {



    // check if inputs are empty -> add error message to array
    if (empty($_POST["id"]) || empty($_POST["name"]) || empty($_POST["dir"])) {
        array_push($messages, "Vyskytla sa chyba pri mazaní prílohy.");
    }

    // if any errors -> insert to db
    if (count($messages) == 0) {

        // assign and sanitize
        $id = $deleteAttachController->sanitizeNumber($_POST["id"]);
        $dir = $deleteAttachController->sanitizeNumber($_POST["dir"]);
        $name = $deleteAttachController->checkInput($_POST["name"]);
        $path = "../../../public_html/uploads/projects/".$dir."/".$name;

        if ($deleteAttachController->deleteAttachment($id)) {

            if(file_exists($path)){
                unlink($path);
                //send back to AJAX
                array_push($messages, "Príloha bola úspešne vymazaná.");
                echo json_encode(['code' => 200, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                die();
            }
            else
            {
                //todo zase sa to cachuje
                array_push($messages, "Vyskytol sa problém pri mazaní prílohy.1");
                echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                die();
            }



        } else {

            array_push($messages, "Vyskytol sa problém pri mazaní prílohy.");
            echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
            die();

        }

    } else {

        echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
        die();
    }
} else {

    array_push($messages, "Vyskytol sa neočakávaný problém. Kontaktujte podporu.");
    echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
    //ak sa spustí script bez parametrov
    $deleteAttachController->redirect("../../../public_html/");
    die();
}
