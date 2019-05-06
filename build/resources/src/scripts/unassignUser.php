<?php

session_start();

require_once(__DIR__ . "/../controllers/TaskController.php");
require_once(__DIR__ . "/../controllers/ProjectController.php");

$tController = new TaskController();
$pController = new ProjectController();

// if was send id from AJAX
if (isset($_POST["requestType"])) {

    $requestType =  $tController->checkInput($_POST["requestType"]);

    switch ($requestType) {
        case "showTask":

            require_once(__DIR__ . "/../views/task_board/modals/Tasks/task_unassignMember.php");
            die();

            break;

        case "showProject":

            require_once(__DIR__ . "/../views/task_board/modals/Projects/project_unassignMember.php");
            die();

            break;

        case "unassignTask":
            // check if inputs are empty -> add error message to array
            if (empty($_POST["memberId"]) || empty($_SESSION["user"]["id"]) || empty($_POST["taskId"])) {

                $messages ="Vyskytla sa chyba pri odoberaní užívateľa z úlohy.";
                echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                die();
            }
            else
            {
                // assign and sanitize
                $taskId = $tController->sanitizeNumber($_POST["taskId"]);
                $userId = $tController->sanitizeNumber($_SESSION["user"]["id"]);
                $memberId = $tController->sanitizeNumber($_POST["memberId"]);
                $projectId = $tController->sanitizeNumber($_POST["projectId"]);

                if(!$tController->selectProjectAuthor($projectId, $userId))
                {

                    $messages ="Nemáte potrebné oprávnenie.";
                    echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                    die();


                }else {


                    if ($tController->unassignFromTask($memberId, $taskId)) {

                        //send back to AJAX
                        $messages ="Užívateľ bol úspešne odobraný z úlohy.";
                        echo json_encode(['code' => 200, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                        die();

                    } else {

                        $messages ="Vyskytla sa chyba pri odoberaní užívateľa z úlohy.";
                        echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);

                        die();
                    }
                }
            }

            break;

        case "unassignProject":
            // check if inputs are empty -> add error message to array
            if (empty($_POST["memberId"]) || empty($_SESSION["user"]["id"])) {

                $messages ="Vyskytla sa chyba pri odoberaní užívateľa z projektu.";
                echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                die();
            }
            else
            {
                // assign and sanitize

                $userId = $tController->sanitizeNumber($_SESSION["user"]["id"]);
                $memberId = $tController->sanitizeNumber($_POST["memberId"]);
                $projectId = $tController->sanitizeNumber($_POST["projectId"]);

                if(!$tController->selectProjectAuthor($projectId, $userId))
                {

                    $messages ="Nemáte potrebné oprávnenie.";
                    echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                    die();


                }
                else if($memberId = $userId)
                {
                    $messages ="Nemôžete vymazať autora projektu.";
                    echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                    die();
                }
                else
                {


                    if ($pController->unassignFromProject($memberId, $projectId)) {

                        //send back to AJAX
                        $messages ="Užívateľ bol úspešne odobraný z projektu.";
                        echo json_encode(['code' => 200, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                        die();

                    } else {

                        $messages ="Vyskytla sa chyba pri odoberaní užívateľa z projektu.";
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
    $tController->redirect("../../../public_html/");
    die();
}