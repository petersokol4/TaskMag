<?php

session_start();

require_once(__DIR__ . "/../controllers/TaskController.php");

$timerController = new TaskController();

// if was send requestType from AJAX
if (isset($_POST["requestType"])) {

    $requestType =  $timerController->checkInput($_POST["requestType"]);

    switch ($requestType) {
        case "my":

            if (isset($_POST["projectId"]) && isset($_POST["userId"])) {

                // assign and test values (only int)
                $projectId = $timerController->sanitizeNumber($_POST["projectId"]);
                $userId = $timerController->sanitizeNumber($_POST["userId"]);

                $allTimers = $timerController->selectMyTimers($projectId, $userId);
                require_once(__DIR__ . "/../views/timerList.php");
                exit;
            }

            break;

        case "all":

            if (isset($_POST["projectId"])) {

                // assign and test values (only int)
                $projectId = $timerController->sanitizeNumber($_POST["projectId"]);

                $allTimers = $timerController->selectAllTimers($projectId);
                require_once(__DIR__ . "/../views/timerList.php");
                exit;
            }

            break;

        case "workTimeGraph":

            if (isset($_POST["projectId"]) && isset($_POST["userId"])) {

                $p_Id = $timerController->sanitizeNumber($_POST["projectId"]);
                $u_Id = $timerController->sanitizeNumber($_POST["userId"]);

                $memberTimeArr = $timerController->selectMyTimers($p_Id, $u_Id);
                $allTimeArr = $timerController->selectAllTimers($p_Id);

                $total = 0;

                if(!empty($allTimeArr))
                {
                    foreach ($allTimeArr as $timer){
                        $datetime1 = DateTime::createFromFormat ( "Y-m-d H:i:s", $timerController->checkOutput($timer['timer_start']) );
                        $datetime2 = DateTime::createFromFormat ( "Y-m-d H:i:s", $timerController->checkOutput($timer['timer_stop']) );

                        $dt1 = $datetime1 ->getTimestamp();
                        $dt2 = $datetime2 ->getTimestamp();
                        $td= $dt2 - $dt1;

                        $total += $td;
                    }
                }

                $allTime = $total;
                $allTimeFormatted = $timerController->parseTime($total);

                $total = 0;

                if(!empty($memberTimeArr))
                {
                    foreach ($memberTimeArr as $timer){
                        $datetime1 = DateTime::createFromFormat ( "Y-m-d H:i:s", $timerController->checkOutput($timer['timer_start']) );
                        $datetime2 = DateTime::createFromFormat ( "Y-m-d H:i:s", $timerController->checkOutput($timer['timer_stop']) );

                        $dt1 = $datetime1 ->getTimestamp();
                        $dt2 = $datetime2 ->getTimestamp();
                        $td= $dt2 - $dt1;

                        $total += $td;
                    }
                }

                $memberTime = $total;
                $memberTimeFormatted = $timerController->parseTime($total);

                $responseArray["timeUser"] =$memberTime;
                $responseArray["timeAll"] =$allTime;
                $responseArray["timeUserFormatted"] =$memberTimeFormatted;
                $responseArray["timeAllFormatted"] =$allTimeFormatted;

                echo json_encode($responseArray, JSON_UNESCAPED_UNICODE);

            }
            break;


        default:

            echo "V priebehu vykonávania akcie došlo k neočakávanej chybe.";
            die();

    }

} else {
    //ak sa spustí script bez parametrov
    $timerController->redirect("../../../public_html/");
    die();
}