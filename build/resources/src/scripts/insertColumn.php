<?php

session_start();

require_once(__DIR__ . "/../controllers/ColumnController.php");

$cController = new ColumnController();

// if was send request from AJAX
if (isset($_POST["column"]) && isset($_POST["columnName"]) && isset($_POST["color"]) && isset($_POST["p_Id"])) {       //name of button

    // errors array
    $messages = array();

    $columnName = $cController->checkInput($_POST["columnName"]);
    $columnColor = $cController->checkInput($_POST["color"]);
    $projectId = $cController->sanitizeNumber($_POST["p_Id"]);

    if(isset($_POST["columnLimit"]) && !empty($_POST["columnLimit"]))
    {
        $columnLimit = $cController->sanitizeNumber($_POST["columnLimit"]);
    }else
    {
        $columnLimit=0;
    }


    if (empty($_POST["c_Id"])) {
        //insert

        if ($cController->createColumn($columnName, $columnColor, $projectId, $columnLimit)) {
            array_push($messages, "stlpec bol vytvoreny.");
            echo json_encode(['code' => 200, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
            die();

        } else {

            array_push($messages, "Vyskytol sa problém pri vytváraní stlpca.");
            echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
            die();
        }

    } else {
        //update
        $c_Id = $cController->sanitizeNumber($_POST["c_Id"]);

        if ($cController->editColumn($c_Id, $columnName, $columnColor, $columnLimit))
        {
            array_push($messages, "stlpec bol úspešne upravený.");
            echo json_encode(['code' => 200, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
            die();

        } else {

            array_push($messages, "Vyskytol sa problém pri úprave stlpca.");
            echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
            die();
        }
    }

} else {

    //ak sa spustí script bez parametrov
    $cController->redirect("../../../public_html/");
    die("Error. Kontaktujte podporu.");
}