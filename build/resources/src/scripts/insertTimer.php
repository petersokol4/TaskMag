<?php

session_start();

require_once(__DIR__ . "/../controllers/TaskController.php");

$timerController = new TaskController();

// if was send request from AJAX
if (isset($_POST["requestType"])) {

    switch ($_POST["requestType"]) {
        case "start":

            if(isset($_POST["projectId"]) && isset($_SESSION["user"]["id"]))
            {

                $projectId=$timerController->sanitizeNumber($_POST["projectId"]);
                $userId=$timerController->sanitizeNumber($_SESSION["user"]["id"]);

                if($result = $timerController->startTimer($projectId, $userId))
                {
                    $startTimer = $result[0];
                    $id = $result[1];
                    $startTime = (new DateTime($startTimer["timer_start"]))->getTimestamp();

                    $_SESSION["timer"]["project"] = $projectId;
                    $_SESSION["timer"]["id"] = $timerController->sanitizeNumber($id);
                    $_SESSION["timer"]["start"] = $timerController->sanitizeNumber($startTime);

                    $responseArray["code"] = 200;
                    $responseArray["msg"] = "Časovač spustený.";
                    header('Content-Type: application/json');
                    echo json_encode($responseArray, JSON_UNESCAPED_UNICODE);
                    exit;
                }
                else
                {
                    $responseArray["code"] = 404;
                    $responseArray["msg"] = "Časovač sa nepodarilo spustiť. Možno ho už máte spustený pre iný projekt.";

                    header('Content-Type: application/json');
                    echo json_encode($responseArray, JSON_UNESCAPED_UNICODE);
                    exit;
                }
            }
            else
            {
                $responseArray["code"] = 404;
                $responseArray["msg"] = "Vyskytla sa neočakávaná chyba. Kontaktujte podporu.";

                header('Content-Type: application/json');
                echo json_encode($responseArray, JSON_UNESCAPED_UNICODE);
                exit;
            }

            break;

        case "stop":

            if(isset($_SESSION["timer"]["id"]))
            {
                $timerId = $timerController->sanitizeNumber($_SESSION["timer"]["id"]);
                if(isset($_POST["timerNote"]))
                {
                    if($_POST["timerNote"] != "" && !empty($_POST["timerNote"]))
                    {
                        if(!$timerController->checkInputLength($_POST["timerNote"],2,800))
                        {
                            $responseArray["code"] = 404;
                            $responseArray["msg"] = "Poznámka musí obsahovať 5 - 400 znakov.";
                            echo json_encode($responseArray, JSON_UNESCAPED_UNICODE);
                            exit;

                        }
                        else
                        {
                            $noteTimer = $timerController->checkInput($_POST["timerNote"]);
                        }
                    }
                    else
                    {
                        $noteTimer ="Bez poznámky.";
                    }

                    if($timerController->stopTimer($timerId, $noteTimer))
                    {
                        $responseArray["code"] = 200;
                        $responseArray["msg"] = "Timer ukončený.";
                        header('Content-Type: application/json');
                        echo json_encode($responseArray, JSON_UNESCAPED_UNICODE);
                        exit;
                    }
                    else
                    {
                        $responseArray["code"] = 404;
                        $responseArray["msg"] = "Vyskytol sa problém pri zastavovaní timeru.";
                        header('Content-Type: application/json');
                        echo json_encode($responseArray, JSON_UNESCAPED_UNICODE);
                        exit;
                    }
                }

            }
            else
            {
                die();
            }

            break;

        case "check":

            if(isset($_SESSION["user"]["id"]))
            {

                $userId=$timerController->sanitizeNumber($_SESSION["user"]["id"]);

                if(!$result = $timerController->checkTimer($userId))
                {


                    $responseArray["code"] = 200;
                    $responseArray["msg"] = "Časovač nie je spustený.";
                    header('Content-Type: application/json');
                    echo json_encode($responseArray, JSON_UNESCAPED_UNICODE);
                    exit;
                }
                else
                {
                    $responseArray["code"] = 404;
                    $responseArray["msg"] = "Je spustený časovač.";
                    $responseArray["projectId"] = $result["project_id"];

                    header('Content-Type: application/json');
                    echo json_encode($responseArray, JSON_UNESCAPED_UNICODE);
                    exit;
                }
            }
            else
            {
                $responseArray["code"] = 404;
                $responseArray["msg"] = "Vyskytla sa neočakávaná chyba. Kontaktujte podporu.";

                header('Content-Type: application/json');
                echo json_encode($responseArray, JSON_UNESCAPED_UNICODE);
                exit;
            }

            break;

        default:

            $responseArray["code"] = 404;
            $responseArray["msg"] = "V priebehu vykonávania akcie sa vyskytla neočakávaná chyba. Skúste to neskôr alebo kontaktujte podporu.";

            header('Content-Type: application/json');
            echo json_encode($responseArray, JSON_UNESCAPED_UNICODE);
            die();

    }

} else {

    //ak sa spustí script bez parametrov
    $timerController->redirect("../../../public_html/");
    die();
}