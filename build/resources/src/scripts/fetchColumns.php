<?php

session_start();

require_once(__DIR__ . "/../controllers/ColumnController.php");
require_once(__DIR__ . "/../controllers/TaskController.php");

$cController = new ColumnController();
$tController = new TaskController();


if (isset($_POST["requestType"]) && isset($_POST["projectId"])) {

    $p_Id = $cController->sanitizeNumber($_POST["projectId"]);
    $requestType =  $cController->checkInput($_POST["requestType"]);

    switch ($requestType) {
        case "single":

            if(isset($_POST["id"])) {

                $id = $cController->sanitizeNumber($_POST["id"]);
                $singleColumn = $cController->selectSingleColumn($id);

                if (!empty($singleColumn)) {

                    $responseArray["id"] =$cController->sanitizeNumber( $singleColumn[0]["id_columns"]);
                    $responseArray["columnLimit"] =$cController->sanitizeNumber( $singleColumn[0]["column_limit"]);
                    $responseArray["columnName"] =$cController->checkOutput( $singleColumn[0]["column_title"]);
                    $responseArray["color"] =$cController->checkOutputLight( $singleColumn[0]["column_color"]);

                    echo json_encode($responseArray, JSON_UNESCAPED_UNICODE);
                }

            }

            break;

        case "all":

            $columns = $cController->selectAllColumns($p_Id);


            require_once(__DIR__ . "/../views/columnList.php");
            die();

            break;

        case "allForm":

            //todo 200 | 404
            $columns = $cController->selectAllColumns($p_Id);

            require_once(__DIR__ . "/../views/task_board/modals/column/column_moveTasks.php");
            die();

            break;

        default:
            echo "V priebehu vykonávania akcie došlo k neočakávanej chybe.";
            die();


    }


} else {
    //ak sa spustí script bez parametrov
    echo "V priebehu vykonávania akcie došlo k neočakávanej chybe.";
    $cController->redirect("../../../public_html/");
    die();
}