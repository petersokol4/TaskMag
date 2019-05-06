<?php

session_start();

require_once(__DIR__ . "/../controllers/ProjectController.php");

$ProjectController = new ProjectController();

// if was send request from AJAX
if (isset($_POST["insert"])) {       //name of button

    // errors array
    $messages = array();


    //check inputs length
    // check if inputs are empty -> add error message to array
    if (empty($_POST["projectClient"])) {
        array_push($messages, "Prosím zadajte klienta");
    }else{
        if(!$ProjectController->checkInputLength($_POST["projectClient"],2,50)){array_push($messages, "Názov klienta musí obsahovať minimálne 2 a maximálne 50 znakov");}
    }
    if (empty($_POST["projectName"])) {
        array_push($messages, "Prosím zadajte názov projektu");
    }else{
        if(!$ProjectController->checkInputLength($_POST["projectName"],2,50)){array_push($messages, "Názov projektu musí obsahovať minimálne 2 a maximálne 50 znakov");}
    }
    if (empty($_POST["projectDescription"])) {
        array_push($messages, "Prosím zadajte popis projektu");
    }else{
        if(!$ProjectController->checkInputLength($_POST["projectDescription"],11,400)){array_push($messages, "Popis projektu musí obsahovať minimálne 10 a maximálne 400 znakov");}
    }
    if (empty($_POST["projectStatus"])) {
        array_push($messages, "Prosím zvoľte ststus projektu");
    }
    if (empty($_POST["projectCategory"])) {
        array_push($messages, "Prosím zvoľte kategóriu projektu");
    }
    if (empty($_POST["projectStart"])) {
        array_push($messages, "Prosím zvoľte začiatok projektu");
    }else{
        if(!$ProjectController->checkInputLength($_POST["projectStart"],10,10)){array_push($messages, "Zadajte správny tvar dátumu");}
    }
    if (empty($_POST["projectEnd"])) {
        array_push($messages, "Prosím zvoľte koniec projektu");
    }else{
        if(!$ProjectController->checkInputLength($_POST["projectEnd"],10,10)){array_push($messages, "Zadajte správny tvar dátumu");}
    }

    if (!empty($_POST["projectStart"]) && !empty($_POST["projectEnd"])) {
        // check if end of project is > than start
        if ($_POST["projectEnd"] < $_POST["projectStart"]) {
            array_push($messages, "Koniec projektu nemôže byť skôr ako jeho začiatok.");
        }

    }


    // if any errors -> insert to db
    if (count($messages) == 0) {

        // assign and test values
        $projectClient = $ProjectController->checkInput($_POST["projectClient"]);
        $projectName = $ProjectController->checkInput($_POST["projectName"]);
        $projectDescription = $ProjectController->checkInput($_POST["projectDescription"]);
        $projectStatus = $ProjectController->checkInput($_POST["projectStatus"]);
        $projectCategory = $ProjectController->checkInput($_POST["projectCategory"]);
        $projectStart = $ProjectController->checkInput($_POST["projectStart"]);
        $projectEnd = $ProjectController->checkInput($_POST["projectEnd"]);


        if (empty($_POST["id"])) {
            //insert

            if ($ProjectController->createProject($projectClient, $projectName, $projectDescription, $projectStatus, $projectCategory, $projectStart, $projectEnd, $ProjectController->sanitizeNumber($_SESSION["user"]["id"]))) {
                array_push($messages, "Projekt bol úspešne vytvorený.");
                echo json_encode(['code' => 200, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                die();

            } else {

                //TODO ajax to aj tak nesupti try catch?
                array_push($messages, "Vyskytol sa problém pri vytváraní projektu.");
                echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                die();
            }

        } else {
            //update
            $id = $ProjectController->sanitizeNumber($_POST["id"]);

            if ($ProjectController->editProject($id, $projectClient, $projectName, $projectDescription, $projectStatus, $projectCategory, $projectStart, $projectEnd)) {
                array_push($messages, "Projekt bol úspešne upravený.");
                echo json_encode(['code' => 200, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                die();

            } else {
                //TODO ajax to aj tak nesupti try catch?
                array_push($messages, "Vyskytol sa problém pri úprave projektu.");
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
    $ProjectController->redirect("../../../public_html/");
    die();
}