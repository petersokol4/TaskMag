<?php

session_start();

require_once(__DIR__ . "/../controllers/TaskController.php");

$fetchController = new TaskController();

// if was send requestType from AJAX
if (isset($_POST["requestType"])) {

    // array for errors
    $errors = array();

    $requestType =  $fetchController->checkInput($_POST["requestType"]);

    // select projects
    switch ($requestType) {
        case "single":

            if (isset($_POST["id"])) {

                // assign and test values (only int)
                $id = $fetchController->sanitizeNumber($_POST["id"]);

                $singleTask = $fetchController->selectSingleTask($id);

                if (!empty($singleTask)) {

                    $responseArray["id"] =$fetchController->sanitizeNumber( $singleTask[0]["id_tasks"]);
                    $responseArray["taskName"] =$fetchController->checkOutput( $singleTask[0]["task_name"]);
                    $responseArray["taskDescription"] =$fetchController->checkOutputLight( $singleTask[0]["task_description"]);
                    $responseArray["taskCreated"] =$fetchController->checkOutputLight( $singleTask[0]["task_created"]);
                    $responseArray["taskCreatedBy"] =$fetchController->checkOutput( $singleTask[0]["task_created_by"]);
                    $responseArray["taskCompleted"] =$fetchController->checkOutput( $singleTask[0]["task_completed"]);

                    $taskStarted = $fetchController->checkOutput( $singleTask[0]["task_started"]);
                    //if not started
                    if($taskStarted == NULL)
                    {
                        $responseArray["taskStarted"] = 0;
                    }
                    else    //if started
                    {
                        $responseArray["taskStarted"] = (new DateTime($taskStarted))->format('d / m / Y');
                    }

                    $responseArray["taskDueDate"] =$fetchController->checkOutput( $singleTask[0]["task_due_date"]);
                    $responseArray["taskPriority"] =$fetchController->sanitizeNumber( $singleTask[0]["task_priority"]);
                    $responseArray["taskStatus"] =$fetchController->checkOutput( $singleTask[0]["task_status"]);
                    if($singleTask[0]["task_due_date"] < (new DateTime())->format('Y-m-d H:i:s'))
                    {
                        $responseArray["overdueClass"]="overDue";
                    }
                    else
                    {
                        $responseArray["overdueClass"]="NoOverdue";
                    }


                    $responseArray["taskProjectId"] =$fetchController->checkOutput( $singleTask[0]["project_id"]);
                    $responseArray["projectDirectory"] =$fetchController->checkOutput( $singleTask[0]["column_id"]);
                    $responseArray["projectDirectory"] =$fetchController->checkOutput( $singleTask[0]["task_position"]);

                    $responseArray["taskCreatedFormatted"] = (new DateTime($fetchController->checkOutput($singleTask[0]["task_created"])))->format('d / m / Y');
                    $responseArray["taskDueDateFormatted"] = (new DateTime($fetchController->checkOutput($singleTask[0]["task_due_date"])))->format('d / m / Y');
                    $responseArray["taskCompletedFormatted"] = (new DateTime($fetchController->checkOutput($singleTask[0]["task_completed"])))->format('d / m / Y, H:i');

                    echo json_encode($responseArray, JSON_UNESCAPED_UNICODE);
                }

            }

            //TODO else?

            break;

        case "cycleTime":
            if (isset($_POST["id"])) {

                $p_Id = $fetchController->sanitizeNumber($_POST["id"]);

                $result = $fetchController->selectCycleTime($p_Id);

                if (!empty($result)) {

                    if($fetchController->sanitizeNumber( $result["all"]) != 0)
                    {
                        $cycleTimeArray = $result["cycleTime"];
                        $cycleTimeDays = 0;
                        foreach ($cycleTimeArray as $cycleTime)
                        {
                            $datetime1 = new DateTime($cycleTime["task_started"]);

                            $datetime2 = new DateTime($cycleTime["task_completed"]);

                            $diffCycleTime = $datetime1->diff($datetime2);

                            $cycleTimeDays += ($diffCycleTime->d +1);

                        }

                        $averageCycleTime = $cycleTimeDays/$result["all"];

                        $responseArray["averageCycleTime"] = ceil($averageCycleTime);
                        echo json_encode($responseArray, JSON_UNESCAPED_UNICODE);
                    }
                    else
                    {
                        $messages=array();
                        array_push($messages, "Žiadne dokončené úlohy.");
                        echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                    }

                }
            }

            break;

        case "graph":

            //TODO IF else 200 - 404
            if (isset($_POST["id"])) {

                $p_Id = $fetchController->sanitizeNumber($_POST["id"]);

                $graphData = $fetchController->selectGraphData($p_Id);

                if (!empty($graphData)) {

                    $responseArray["open"] =$fetchController->sanitizeNumber( $graphData["open"]);
                    $responseArray["done"] =$fetchController->sanitizeNumber( $graphData["done"]);

                    echo json_encode($responseArray, JSON_UNESCAPED_UNICODE);
                }
            }
            break;

        case "memberGraph":

            if (isset($_POST["projectId"]) && isset($_POST["userId"])) {

                $p_Id = $fetchController->sanitizeNumber($_POST["projectId"]);
                $u_Id = $fetchController->sanitizeNumber($_POST["userId"]);

                $graphData = $fetchController->selectMemberGraphData($p_Id, $u_Id);

                if (!empty($graphData)) {

                    $responseArray["open"] =$fetchController->sanitizeNumber( $graphData["open"]);
                    $responseArray["done"] =$fetchController->sanitizeNumber( $graphData["done"]);
                    $responseArray["doneAll"] =$fetchController->sanitizeNumber( $graphData["doneAll"]);

                    echo json_encode($responseArray, JSON_UNESCAPED_UNICODE);
                }
            }
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