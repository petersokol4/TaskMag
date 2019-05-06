<?php

session_start();

require_once(__DIR__ . "/../controllers/ColumnController.php");
require_once(__DIR__ . "/../controllers/TaskController.php");

$cController = new ColumnController();
$tController = new TaskController();

// if was send id from AJAX
if (isset($_POST["requestType"]) && isset($_POST["columnId"])) {

    $requestType =  $cController->checkInput($_POST["requestType"]);

    switch ($requestType) {
        case "show":

            if (isset($_POST["countTasks"])) {

                $columnId = $cController->sanitizeNumber($_POST["columnId"]);
                $countTasks = $cController->sanitizeNumber($_POST["countTasks"]);
                require_once(__DIR__ . "/../views/task_board/modals/column/column_deleteColumn.php");
                die();
            }


            break;

        case "delete":
            // check if inputs are empty -> add error message to array
            if (empty($_POST["columnId"]) || empty($_SESSION["user"]["id"]) || empty($_POST["projectId"])) {

                $messages ="Vyskytla sa chyba pri mazaní stĺpca.";
                echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                die();
            }
            else
            {
                // assign and sanitize
                $c_Id = $cController->sanitizeNumber($_POST["columnId"]);
                $userId = $cController->sanitizeNumber($_SESSION["user"]["id"]);
                $projectId = $cController->sanitizeNumber($_POST["projectId"]);

                if(!$cController->selectProjectAuthor($projectId, $userId))
                {

                    $messages ="Nemáte potrebné povolenie na vymazanie.";
                    echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                    die();


                }else {


                    if ($countTasks = $cController->deleteColumn($c_Id)) {

                        $tController->deleteColumnTasks($c_Id);

                        //send back to AJAX
                        $messages ="Stĺpec bol úspešne vymazaný.";
                        echo json_encode(['code' => 200, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                        die();

                    } else {

                        $messages ="Vyskytol sa problém pri mazaní stĺpca. Možno nemáte oprávnenie vymazať stĺpec.";
                        echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);

                        die();
                    }
                }
            }

            break;

        default:
            echo "V priebehu vykonávania akcie došlo k neočakávanej chybe.";
            die();
    }


} else {

    $messages ="Vyskytol sa problém pri mazaní úlohy.";
    echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
    //ak sa spustí script bez parametrov
    $cController->redirect("../../../public_html/");
    die();
}