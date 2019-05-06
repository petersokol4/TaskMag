<?php

session_start();

require_once(__DIR__ . "/../controllers/TaskController.php");

$TaskController = new TaskController();

// if was send request from AJAX
if (isset($_POST["requestType"])) {       //name of button

    $requestType =  $TaskController->checkInput($_POST["requestType"]);

    // select projects
    switch ($requestType) {
        case "task":

            if (isset($_POST["members"]) && count($_POST["members"])>0 && isset($_POST["taskId"])) {

                $taskId = $TaskController->sanitizeNumber($_POST["taskId"]);

                foreach ($_POST["members"] as $member)
                {

                    if(!$TaskController->checkAssignMemberTask($TaskController->sanitizeNumber($member), $taskId))
                    {
                        if(!$TaskController->assignTask($taskId, $TaskController->sanitizeNumber($member)))
                        {
                            $messages ="Vyskytla sa chyba pri priraďovaní členov.";
                            echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                            die();
                        }

                    }
                }
                $messages ="Člen (členovia) úspešne pridaný k úlohe.";
                echo json_encode(['code' => 200, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                die();

            }
            else
            {
                $messages ="Vyskytla sa chyba pri priraďovaní členov.";
                echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                die();
            }
            break;

        case "all":


            break;



        default:
            echo "V priebehu vykonávania akcie došlo k neočakávanej chybe.";
            die();

    }

} else {

    //ak sa spustí script bez parametrov
    $TaskController->redirect("../../../public_html/");
    die();
}