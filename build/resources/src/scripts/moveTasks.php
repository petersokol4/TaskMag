<?php

session_start();

require_once(__DIR__ . "/../controllers/ColumnController.php");

$cController = new ColumnController();

if (isset($_POST["requestType"]) && isset($_POST["columnId"]) && isset($_POST["newColumnId"])) {

    //old column id - from
    //new column id - where

    $c_Id = $cController->sanitizeNumber($_POST["columnId"]);
    $c_newId = $cController->sanitizeNumber($_POST["newColumnId"]);
    $requestType =  $cController->checkInput($_POST["requestType"]);

    if($c_Id === $c_newId)
    {
        $messages ="Nemožno presunúť úlohy do rovnakého stĺpca.";
        echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
        die();
    }
    else
    {
        switch ($requestType) {
            case "move":

                //change column id

                if($taskCount = $cController->changeColumn($c_Id, $c_newId))
                {
                    $messages ="Bolo presunutých $taskCount úloh.";
                    echo json_encode(['code' => 200, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                    die();

                }
                else {

                    //todo zas to nefunguje
                    $messages ="Vyskytol sa problém pri presune úloh. Možno sa v stĺpci nenachádzajú žiadne úlohy.";
                    echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                    die();
                }

                break;




            //TODO filtrovanie podľa priority - nie je číselná
            //TODO filtrovanie podľa priradeného člena

            case "anotherProject":
                //all tasks with column id -> change project id | create new column or add to first | change column id  | change assign member if they are not in new project | comment | attachements | ...
                break;

            default:
                echo "V priebehu vykonávania akcie došlo k neočakávanej chybe.";
                die();


        }
    }

} else {
    //ak sa spustí script bez parametrov
    echo "V priebehu vykonávania akcie došlo k neočakávanej chybe.";
    $cController->redirect("../../../public_html/");
    die();
}