<?php

session_start();

require_once(__DIR__ . "/../controllers/CommentController.php");

$deleteCommentController = new CommentController();

// if was send id from AJAX
if (isset($_POST["idComment"])) {

    // array for errors
    $messages = "";

    // check if inputs are empty -> add error message to array
    if (empty($_POST["idComment"]) || empty($_SESSION["user"]["id"]) || empty($_POST["projectId"])) {
        $messages ="Vyskytla sa chyba pri mazaní úlohy.";
        error_log("Asd2");
        echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
        die();
    }
    else{

        // assign and sanitize
        $id = $deleteCommentController->sanitizeNumber($_POST["idComment"]);
        $userId = $deleteCommentController->sanitizeNumber($_SESSION["user"]["id"]);
        $projectId = $deleteCommentController->sanitizeNumber($_POST["projectId"]);



        if ($deleteCommentController->deleteComment($id, $userId))
        {

            $responseArray["code"] = 200;
            $responseArray["msg"] = "Úloha bol úspešne vymazaná.";
            echo json_encode($responseArray, JSON_UNESCAPED_UNICODE);
            die();

        }
        else
        {

            $responseArray["code"] = 404;
            $responseArray["msg"] = "Vyskytol sa problém pri mazaní úlohy. Možno nemáte oprávnenie vymazať túto úlohu.";
            echo json_encode($responseArray, JSON_UNESCAPED_UNICODE);
            die();

        }

    }


} else {

    $messages ="Vyskytol sa problém pri mazaní úlohy 1.";
    echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
    //ak sa spustí script bez parametrov
    $deleteCommentController->redirect("../../../public_html/");
    die();
}