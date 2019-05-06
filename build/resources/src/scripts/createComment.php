<?php

session_start();

require_once(__DIR__ . "/../controllers/CommentController.php");

$commentController = new CommentController();

// if was send request from AJAX
if (isset($_POST["comment"]) && isset($_POST["cTaskId"]) && isset($_POST["cProjectId"]) && isset($_SESSION["user"] ["id"])) {       //name of button

    // errors array
    $messages = array();


    //check inputs length
    // check if inputs are empty -> add error message to array
    if (empty($_POST["cContent"]) || $_POST["cContent"] == "") {
        //array_push($messages, "Prosím zadajte komentár");
        die("Prosím zadajte komentár");
    }else{

        if(!$commentController->checkInputLength($_POST["cContent"],2,800)){
            //array_push($messages, "Komentár musí obsahovať 2 - 800 znakov.");
            die("Komentár musí obsahovať 2 - 800 znakov.");
        }
    }

    // if any errors -> insert to db
//    if (count($messages) == 0) {

        // assign and test values
        $commentContent = $commentController->checkInput($_POST["cContent"]);
        $taskId = $commentController->sanitizeNumber($_POST["cTaskId"]);
        $projectId = $commentController->sanitizeNumber($_POST["cProjectId"]);
        $userId = $commentController->sanitizeNumber($_SESSION["user"] ["id"]);

        if (empty($_POST["idComment"])) {
            //insert

            if ($commentController->addComment($commentContent, $taskId, $projectId, $userId)) {
//                array_push($messages, "Úloha bola úspešne vytvorená.");
//                echo json_encode(['code' => 200, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                die("Komentár pridaný.");

            } else {

                //array_push($messages, "Vyskytol sa problém pri vytváraní úlohy.");
                //echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                die("Vyskytol sa problém pri pridávaní komentáru.");
            }

        }

//    } else {
//        //if errors -> send array (code, array of errors)  || still on success
//        echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
//        die();
//    }

} else {

    //ak sa spustí script bez parametrov
    $commentController->redirect("../../../public_html/");
    die("Error. Kontaktujte podporu.");
}