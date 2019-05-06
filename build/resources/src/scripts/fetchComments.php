<?php

session_start();

require_once(__DIR__ . "/../controllers/CommentController.php");
require_once(__DIR__ . "/../controllers/UserController.php");

$fetchCommentController = new CommentController();
$userController = new UserController();



// if was send requestType from AJAX

if (isset($_POST["requestType"])) {

    // array for errors
    $messages = array();

    $requestType =  $fetchCommentController->checkInput($_POST["requestType"]);


    // select projects
    switch ($requestType) {
        case "single":
            if (isset($_POST["commentId"])) {

                // assign and test values (only int)
                $commentId = $fetchCommentController->sanitizeNumber($_POST["commentId"]);

                $comment = $fetchCommentController->selectComment($commentId);

                if (!empty($comment)) {
                    $responseArray["id"] =$fetchCommentController->sanitizeNumber( $comment[0]["id_comments"]);
                    $responseArray["commentContent"] =$fetchCommentController->checkOutput( $comment[0]["comment_content"]);

                    echo json_encode($responseArray, JSON_UNESCAPED_UNICODE);
                }

            }

            break;

        case "all":

            if(isset($_POST["taskId"]))
            {
                //$countAllProjects = $fetchCommentController->formatToZero($fetchCommentController->countAllProjects());

                $taskId =  $fetchCommentController->sanitizeNumber($_POST["taskId"]);

                //must be the same name as in commentList.php
                $allComments = $fetchCommentController->selectAllComments($taskId);
                require_once(__DIR__ . "/../views/commentList.php");
            }

            break;

        default:
            echo "V priebehu vykonávania akcie došlo k neočakávanej chybe.";
            die();

    }

} else {
    //ak sa spustí script bez parametrov
    echo "V priebehu vykonávania akcie došlo k neočakávanej chybe.";
    $fetchCommentController->redirect("../../../public_html/");
    die();
}