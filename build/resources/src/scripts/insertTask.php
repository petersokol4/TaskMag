<?php

session_start();

require_once(__DIR__ . "/../controllers/TaskController.php");
require_once(__DIR__ . "/../controllers/ColumnController.php");

$TaskController = new TaskController();
$cController = new ColumnController();

// if was send request from AJAX
if (isset($_POST["insert"]) && isset($_SESSION["user"]["id"])) {       //name of button

    // errors array
    $messages = array();


    //check inputs length
    // check if inputs are empty -> add error message to array
    if (empty($_POST["taskName"])) {
        array_push($messages, "Prosím zadajte názov úlohy");
    }else{
        if(!$TaskController->checkInputLength($_POST["taskName"],2,50)){array_push($messages, "Názov klienta musí obsahovať minimálne 2 a maximálne 50 znakov");}
    }
    if (empty($_POST["taskDescription"])) {
        array_push($messages, "Prosím zadajte popis projektu");
    }else{
        if(!$TaskController->checkInputLength($_POST["taskDescription"],10,400)){array_push($messages, "Popis projektu musí obsahovať minimálne 10 a maximálne 400 znakov");}
    }
    if (empty($_POST["taskPriority"])) {
        array_push($messages, "Prosím zvoľte prioritu úlohy");
    }
    if (empty($_POST["taskDueDate"])) {
        array_push($messages, "Prosím zvoľte deadline úlohy");
    }else{
        if(!$TaskController->checkInputLength($_POST["taskDueDate"],10,10)){array_push($messages, "Zadajte správny tvar dátumu");}
    }
    if(empty($_POST["projectId"])){
        array_push($messages, "Vyskytla sa neočakávaná chyba. Kontaktujte podporu.");
    }

    // if any errors -> insert to db
    if (count($messages) == 0) {

        // assign and test values
        $taskName = $TaskController->checkInput($_POST["taskName"]);
        $taskDescription = $TaskController->checkInput($_POST["taskDescription"]);
        $taskPriority = $TaskController->sanitizeNumber($_POST["taskPriority"]);
        $taskDueDate = $TaskController->checkInput($_POST["taskDueDate"]);
        $projectId = $TaskController->sanitizeNumber($_POST["projectId"]);
        $userId = $TaskController->sanitizeNumber($_SESSION["user"]["id"]);

        if (empty($_POST["idTask"])) {
            //insert

            if(!$columnId = $TaskController->selectProjectColumns($projectId))
            {
                //if no columns -> create
                $columnId = $cController->createDefaultColumn($projectId);

                if ($TaskController->createTask($taskName, $taskDescription, $taskPriority, $taskDueDate, $projectId, $userId, $columnId)) {
                    array_push($messages, "Úloha bola úspešne vytvorená.");
                    echo json_encode(['code' => 200, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                    die();

                } else {

                    array_push($messages, "Vyskytol sa problém pri vytváraní úlohy.");
                    echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                    die();
                }
            }
            else
            {
                if ($TaskController->createTask($taskName, $taskDescription, $taskPriority, $taskDueDate, $projectId, $userId, $columnId)) {
                    array_push($messages, "Úloha bola úspešne vytvorená.");
                    echo json_encode(['code' => 200, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                    die();

                } else {

                    array_push($messages, "Vyskytol sa problém pri vytváraní úlohy.");
                    echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                    die();
                }
            }

        } else {
            //update
            $id = $TaskController->sanitizeNumber($_POST["idTask"]);

            if ($TaskController->editTask($id, $taskName, $taskDescription, $taskPriority, $taskDueDate)) {
                array_push($messages, "Úloha bola úspešne upravená.");
                echo json_encode(['code' => 200, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                die();

            } else {

                array_push($messages, "Vyskytol sa problém pri úprave úlohy.");
                echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                die();
            }
        }

    } else {
        //if errors -> send array (code, array of errors)  || still on success
        echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
        die();
    }

} else {

    //ak sa spustí script bez parametrov
    $TaskController->redirect("../../../public_html/");
    die();
}