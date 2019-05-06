<?php

session_start();

require_once(__DIR__ . "/../controllers/ProjectController.php");
require_once(__DIR__ . "/../controllers/TaskController.php");

$fetchProjectController = new ProjectController();
$fetchController = new TaskController();

// if was send requestType from AJAX
if (isset($_POST["requestType"]) && isset($_SESSION["user"])) {

    // array for errors
    $errors = array();

    $requestType =  $fetchProjectController->checkInput($_POST["requestType"]);

    // select projects
    switch ($requestType) {
        case "single":

            if (isset($_POST["id"])) {

                // assign and test values (only int)
                $id = $fetchProjectController->sanitizeNumber($_POST["id"]);

                $singleProject = $fetchProjectController->selectSingleProject($id);

                if (!empty($singleProject)) {

                    $responseArray["id"] =$fetchProjectController->sanitizeNumber( $singleProject[0]["id"]);
                    $responseArray["projectClient"] =$fetchProjectController->checkOutput( $singleProject[0]["project_client"]);
                    $responseArray["projectName"] =$fetchProjectController->checkOutputLight( $singleProject[0]["project_name"]);
                    $responseArray["projectDescription"] =$fetchProjectController->checkOutputLight( $singleProject[0]["project_description"]);
                    $responseArray["projectStatus"] =$fetchProjectController->checkOutput( $singleProject[0]["project_status"]);
                    $responseArray["projectCategory"] =$fetchProjectController->checkOutput( $singleProject[0]["project_category"]);
                    $responseArray["projectCreated"] =$fetchProjectController->checkOutput( $singleProject[0]["create_time"]);
                    $responseArray["projectStart"] =$fetchProjectController->checkOutput( $singleProject[0]["project_start"]);
                    $responseArray["projectEnd"] =$fetchProjectController->checkOutput( $singleProject[0]["project_end"]);
                    $responseArray["projectAuthor"] =$fetchProjectController->checkOutput( $singleProject[0]["project_author"]);
                    $responseArray["projectDirectory"] =$fetchProjectController->checkOutput( $singleProject[0]["project_directory"]);
                    $responseArray["projectCreatedFormated"] =$projectCreatedFormated = (new DateTime($fetchProjectController->checkOutput($singleProject[0]["create_time"])))->format('d / m / Y');
                    $responseArray["projectStartFormated"] =$projectStartFormated = (new DateTime($fetchProjectController->checkOutput($singleProject[0]["project_start"])))->format('d / m / Y');
                    $responseArray["projectEndFormated"] =$projectEndFormated = (new DateTime($fetchProjectController->checkOutput($singleProject[0]["project_end"])))->format('d / m / Y');
                    if($singleProject[0]["project_end"] < (new DateTime())->format('Y-m-d H:i:s'))
                    {
                        $responseArray["overdueClass"]="overDue";
                    }
                    else
                    {
                        $responseArray["overdueClass"]="NoOverdue";
                    }

                    echo json_encode($responseArray, JSON_UNESCAPED_UNICODE);
                }

            }

            break;

        case "all":

            $countAllProjects = $fetchProjectController->formatToZero($fetchProjectController->countAllProjects($_SESSION["user"]["id"]));
            $countMyProjects = $fetchProjectController->formatToZero($fetchProjectController->countMyProjects($fetchProjectController->checkOutput($_SESSION["user"]["id"])));
            $countFinishedProjects = $fetchProjectController->formatToZero($fetchProjectController->countSelectedProjects("Dokončený", $fetchProjectController->checkOutput($_SESSION["user"]["id"])));
            $countInProgressProjects = $fetchProjectController->formatToZero($fetchProjectController->countSelectedProjects("Prebieha", $fetchProjectController->checkOutput($_SESSION["user"]["id"])));

            $pctFinished = $fetchProjectController->formatToPercentage($countFinishedProjects, $countAllProjects);
            $pctActive = $fetchProjectController->formatToPercentage($countInProgressProjects, $countAllProjects);
            $pctMy = $fetchProjectController->formatToPercentage($countMyProjects, $countAllProjects);


            //must be the same name as in projectBoardList.php
            $allProjects = $fetchProjectController->selectAllProjects($fetchProjectController->checkOutput($_SESSION["user"]["id"]));

            //update all -> cards & table
            //html -> #mainContentView
            require_once(__DIR__ . "/../views/projectBoard.php");
            break;

        case "my":


            //must be the same name as in projectBoard.php
            $allProjects = $fetchProjectController->selectMyProjects($fetchProjectController->checkOutput($_SESSION["user"]["id"]));
//            $allProjects = $fetchProjectController->selectMyProjects($fetchProjectController->checkOutput($_SESSION["user"]["id"]));
            //html -> #projectContentBox
            require_once(__DIR__ . "/../views/projectBoardList.php");
            break;

        case "active":


            //must be the same name as in projectBoardList.php
            $allProjects = $fetchProjectController->selectCustomProjects("Prebieha", $fetchProjectController->checkOutput($_SESSION["user"]["id"]));
//            $allProjects = $fetchProjectController->selectCustomProjects("Prebieha", $fetchProjectController->checkOutput($_SESSION["user"]["id"]));
            //html -> #projectContentBox
            require_once(__DIR__ . "/../views/projectBoardList.php");
            break;

        case "done":

            //must be the same name as in projectBoardList.php
            $allProjects = $fetchProjectController->selectCustomProjects("Dokončený", $fetchProjectController->checkOutput($_SESSION["user"]["id"]));
//            $allProjects = $fetchProjectController->selectCustomProjects("Dokončený", $fetchProjectController->checkOutput($_SESSION["user"]["id"]));
            //html -> #projectContentBox
            require_once(__DIR__ . "/../views/projectBoardList.php");
            break;

        default:
            echo "V priebehu vykonávania akcie došlo k neočakávanej chybe.";
            die();

    }

} else {
    //ak sa spustí script bez parametrov
    $fetchProjectController->redirect("../../../public_html/");
    die();
}