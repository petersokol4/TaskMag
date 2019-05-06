<?php

session_start();

require_once(__DIR__ . "/../controllers/UserController.php");
require_once(__DIR__ . "/../controllers/ProjectController.php");

$uController = new UserController();
$pController = new ProjectController();

// if was send requestType from AJAX
if (isset($_POST["requestType"])) {

    // array for errors
    $errors = array();

    $requestType =  $uController->checkInput($_POST["requestType"]);

    // select projects
    switch ($requestType) {
        case "single":

            if (isset($_POST["userId"]) && isset($_POST["projectId"])) {

                $userId= $uController->sanitizeNumber($_POST["userId"]);

                $singleUser = $uController->selectProfile($userId);
                $assigned = $uController->assignTime($userId);

                if (!empty($singleUser) && !empty($assigned)) {
                    //$responseArray["id"] =$uController->sanitizeNumber( $singleUser[0]["id_users"]);
                    $responseArray["userName"] =$uController->checkOutput( $singleUser[0]["user_name"]);
                    $responseArray["userAvatar"] =$uController->checkOutput( $singleUser[0]["user_avatar"]);
                    $responseArray["userEmail"] =$uController->checkOutput( $singleUser[0]["user_email"]);
                    $responseArray["userAbout"] =$uController->checkOutputLight( $singleUser[0]["user_about"]);
                    $responseArray["userCreated"] =(new DateTime($uController->checkOutput($singleUser[0]["user_created"])))->format('d / m / Y');
                    $responseArray["userAssigned"] = (new DateTime($uController->checkOutput($assigned["assign_time"])))->format('d / m / Y');

                    echo json_encode($responseArray, JSON_UNESCAPED_UNICODE);
                }

                //ďalšie ajaxy
                //task controller -> select graph data projectId + userId
                //task controller -> select graph data projectId + userId + taskStatus = 1  |||||  projecId + taskStatus = 1
                //timer controller -> select work time projectId + created_by + timer_finished = 1 ACCUMULATE INSIDE FOREACH
                //timer controller -> select project work time projectId + timer_finished = 1 ACCUMULATE INSIDE FOREACH
                //timer controller -> select timesheet projectId userId timerfinished = 1 -> require tbl

            }

            break;

        case "all":

            if(isset($_POST["projectId"]))
            {
                $projectId= $uController->sanitizeNumber($_POST["projectId"]);
                $members = $uController->selectAllMembers($projectId);
                $project = $pController->selectSingleProject($projectId);
                require_once(__DIR__ . "/../views/userList.php");


            }
            break;


        case "invited":

            if(isset($_POST["projectId"])) {
                $projectId = $uController->sanitizeNumber($_POST["projectId"]);
                $members = $uController->selectInvitedMemebers($projectId);
                $project = $pController->selectSingleProject($projectId);
                require_once(__DIR__ . "/../views/userList.php");
            }

            break;

        case "notassigned":
            if(isset($_POST["projectId"])) {
                $projectId = $uController->sanitizeNumber($_POST["projectId"]);
                $members = $uController->selectNotAssignedMembers($projectId);
                $project = $pController->selectSingleProject($projectId);
                require_once(__DIR__ . "/../views/userList.php");
            }

            break;

        case "task":
            if (isset($_POST["taskId"])) {

                $taskId = $uController->sanitizeNumber($_POST["taskId"]);
                $members = $uController->selectTaskMembers($taskId);
                require_once(__DIR__ . "/../views/task_board/modals/tasks/task_assignedTaskMembers.php");
            }
            break;

        case "noAssignedTask":
            if (isset($_POST["taskId"]) && isset($_POST["projectId"])) {

                $projectId= $uController->sanitizeNumber($_POST["projectId"]);
                $taskId = $uController->sanitizeNumber($_POST["taskId"]);
                $members = $uController->selectAllMembers($projectId);
                require_once(__DIR__ . "/../views/task_board/modals/tasks/task_assignMember.php");
            }
            break;

        case "statistics":


            //pridelení členovnia - tí čo sú pridelení k tasku / všetci
            //potvrdení členovia - priradení k projektu / priradení k projektu + pozvaní k projektu
            if(isset($_POST["projectId"])) {
                $projectId = $uController->sanitizeNumber($_POST["projectId"]);
                $graphData = $uController->selectGraphData($projectId);

                if (!empty($graphData)) {

                    $noAssigned = $uController->sanitizeNumber($graphData["noAssigned"]);
                    $all = $uController->sanitizeNumber($graphData["all"]);
                    $invited = $uController->sanitizeNumber($graphData["invited"]);

                    $noAssigned = $uController->formatToPercentage($noAssigned, $all);
                    //error_log($noAssigned);
                    $assigned = 100 - $noAssigned;
                    //error_log($assigned);
                    $accepted = $uController->formatToPercentage($all, ($all + $invited));

                    $responseArray["assigned"] = $assigned;
                    $responseArray["accepted"] = $accepted;

                    echo json_encode($responseArray, JSON_UNESCAPED_UNICODE);
                }
            }

            break;

        default:
            echo "V priebehu vykonávania akcie došlo k neočakávanej chybe.";
            die();

    }
    die();

} else {
    //ak sa spustí script bez parametrov
    $fetchProjectController->redirect("../../../public_html/");
    die();
}