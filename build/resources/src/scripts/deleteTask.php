<?php

session_start();

require_once(__DIR__ . "/../controllers/TaskController.php");

$deleteTaskController = new TaskController();



// if was send id from AJAX
if (isset($_POST["idDelete"])) {

    // array for errors
    $messages = "";

    // check if inputs are empty -> add error message to array
    if (empty($_POST["idDelete"]) || empty($_SESSION["user"]["id"]) || empty($_POST["projectId"])) {
        $messages ="Vyskytla sa chyba pri mazaní úlohy.";
        echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
        die();
    }
    else
    {

        // assign and sanitize
        $id = $deleteTaskController->sanitizeNumber($_POST["idDelete"]);
        $userId = $deleteTaskController->sanitizeNumber($_SESSION["user"]["id"]);
        $projectId = $deleteTaskController->sanitizeNumber($_POST["projectId"]);

        if(!$projectAuthor = $deleteTaskController->selectProjectAuthor($projectId, $userId))
        {

            $messages ="Nemáte potrebné povolenie na vymazanie.";
            echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
            die();


        }
        else
        {

            if ($deleteTaskController->deleteTask($id, $userId)) {

                //send back to AJAX
                $messages = "Úloha bol úspešne vymazaná.";
                echo json_encode(['code' => 200, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                die();

            } else {

                $messages = "Vyskytol sa problém pri mazaní úlohy. Možno nemáte oprávnenie vymazať úlohu.";
                echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                die();
            }

        }
    }

} else {

    $messages ="Vyskytol sa problém pri mazaní úlohy 1.";
    echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
    //ak sa spustí script bez parametrov
    $deleteTaskController->redirect("../../../public_html/");
    die();
}