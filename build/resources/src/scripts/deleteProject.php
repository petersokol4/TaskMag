<?php

session_start();

require_once(__DIR__ . "/../controllers/ProjectController.php");

$deleteProjectController = new ProjectController();

// array for errors
$messages = array();

// if was send id from AJAX
if (isset($_POST["id"])) {



    // check if inputs are empty -> add error message to array
    if (empty($_POST["id"])) {
        array_push($messages, "Vyskytla sa chyba pri mazaní projektu.");
    }

    // if any errors -> insert to db
    if (count($messages) == 0) {

        // assign and sanitize
        $id = $deleteProjectController->sanitizeNumber($_POST["id"]);

        if ($deleteProjectController->deleteProject($id)) {

            //send back to AJAX
            array_push($messages, "Projekt bol úspešne vymazaný.");
            echo json_encode(['code' => 200, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
            die();

        } else {

            array_push($messages, "Vyskytol sa problém pri mazaní projektu.");
            echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
            die();

        }

    } else {

        echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
        die();
    }
} else {

    array_push($messages, "Vyskytol sa neočakávaný problém. Kontaktujte podporu.");
    echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
    //ak sa spustí script bez parametrov
    $deleteProjectController->redirect("../../../public_html/");
    die();
}
