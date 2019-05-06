<?php

session_start();

require_once(__DIR__ . "/../controllers/CommentController.php");

$commentController = new CommentController();


if (isset($_POST["idComment"]) && isset($_SESSION["user"]["id"])) {
    // errors array
    $messages = array();


    //check inputs length
    // check if inputs are empty -> add error message to array
//    if (empty($_POST["idComment"]) || $_POST["cContent"] == "") {
//        //array_push($messages, "Prosím zadajte komentár");
//        die("Prosím zadajte komentár");
//    }else{
//
//        if(!$commentController->checkInputLength($_POST["cContent"],2,800)){
//            //array_push($messages, "Komentár musí obsahovať 2 - 800 znakov.");
//            die("Komentár musí obsahovať 2 - 800 znakov.");
//        }
//    }

    //update
    $id = $commentController->sanitizeNumber($_POST["idComment"]);
    $commentContent = $commentController->checkInput($_POST["cContent"]);
    $userId = $commentController->sanitizeNumber($_SESSION["user"]["id"]);

    if ($commentController->updateComment($id, $commentContent, $userId)) {

        $responseArray["code"] = 200;
        $responseArray["msg"] = "Úloha bola úspešne upravená.";
        echo json_encode($responseArray, JSON_UNESCAPED_UNICODE);
        die();

    } else {

        $responseArray["code"] = 404;
        $responseArray["msg"] = "Vyskytol sa problém pri úprave komentáru. Možno nemáte potrebné oprávnenie.";
        echo json_encode($responseArray, JSON_UNESCAPED_UNICODE);
        die();
    }

} else {

    //ak sa spustí script bez parametrov
    $commentController->redirect("../../../public_html/");
    die("Error. Kontaktujte podporu.");
}

