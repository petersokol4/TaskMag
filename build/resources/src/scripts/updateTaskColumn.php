<?php

session_start();

require_once(__DIR__ . "/../controllers/TaskController.php");

$tController = new TaskController();


if (isset($_POST["taskId"]) && isset($_POST["columnId"])) {

    //update
    $taskId = $tController->sanitizeNumber($_POST["taskId"]);
    $columnId = $tController->sanitizeNumber($_POST["columnId"]);

    //old column Id
    $c_OldIdArr = $tController->selectSingleTask($taskId);
    $c_OldId = $c_OldIdArr[0]["column_id"];

    if ($tController->editTaskColumn($taskId, $columnId)) {



        $c_OldCount = $tController->formatToZero($tController->selectColumnTasksCount($c_OldId));
        $c_NewCount = $tController->formatToZero($tController->selectColumnTasksCount($columnId));

        $responseArray["c_OldId"] = $c_OldId;
        $responseArray["c_OldCount"] = $c_OldCount;
        $responseArray["c_NewId"] = $columnId;
        $responseArray["c_NewCount"] = $c_NewCount;

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
    $tController->redirect("../../../public_html/");
    die("Error. Kontaktujte podporu.");
}