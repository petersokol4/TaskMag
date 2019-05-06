<?php
/**
 * Copyright (c) 2019. TaskMag project managing web app. Created by Peter Sokol for Diploma Thesis.
 */

session_start();

require_once(__DIR__ . "/../controllers/TaskController.php");

$completeController = new TaskController();

// if was send request from AJAX
if (isset($_POST["id"])) {       //name of button

    // errors array
    $messages = array();

    if (!empty($_POST["id"])) {

        // assign and test values
        $taskId = $completeController->sanitizeNumber($_POST["id"]);

        //insert

        if ($taskStarted=$completeController->startTask($taskId)) {
            $taskStartedFormated= (new DateTime($taskStarted))->format('d / m / Y');
            array_push($messages, "Úloha začala.");
            echo json_encode(['code' => 200, 'msg' => $messages, 'time' =>$taskStartedFormated], JSON_UNESCAPED_UNICODE);
            die();

        } else {

            //TODO ajax to aj tak nesupti try catch?
            array_push($messages, "Vyskytol sa problém pri úprave stavu úlohy.");
            echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
            die();
        }

    } else {
        array_push($messages, "Vyskytol sa neočakávaný problém. Kontaktujte podporu.");
        echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
    }

} else {

    //ak sa spustí script bez parametrov
    array_push($messages, "Vyskytol sa neočakávaný problém. Kontaktujte podporu.");
    echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
    $completeController->redirect("../../../public_html/");
    die();
}