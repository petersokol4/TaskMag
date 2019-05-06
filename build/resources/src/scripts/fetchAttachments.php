<?php

session_start();

require_once(__DIR__ . "/../controllers/AttachmentController.php");

$fetchAttachController = new AttachmentController();

// if was send requestType from AJAX

if (isset($_POST["requestType"]) && isset($_POST["taskId"])) {

    // array for errors
    $messages = array();

    $requestType =  $fetchAttachController->checkInput($_POST["requestType"]);
    $taskId =  $fetchAttachController->sanitizeNumber($_POST["taskId"]);

    // select projects
    switch ($requestType) {
        case "single":

            break;

        case "all":

            //$countAllProjects = $fetchAttachController->formatToZero($fetchAttachController->countAllProjects());

            //TODO IF ELSE
            //must be the same name as in attachmentsList.php
            $allAttachements = $fetchAttachController->selectAllAttachments($taskId);
                require_once(__DIR__ . "/../views/attachmentsList.php");

            break;

        default:
            echo "V priebehu vykonávania akcie došlo k neočakávanej chybe.";
            die();

    }

} else {
    //ak sa spustí script bez parametrov
    echo "V priebehu vykonávania akcie došlo k neočakávanej chybe.";
    $fetchAttachController->redirect("../../../public_html/");
    die();
}