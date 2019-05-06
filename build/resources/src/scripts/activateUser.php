<?php
/**
 * Copyright (c) 2019. TaskMag project managing web app. Created by Peter Sokol for Diploma Thesis.
 */

session_start();

require_once(__DIR__ . "/../controllers/UserController.php");

$activateUserController = new UserController();

// if was send request from AJAX
if (isset($_POST['trust'])) {      //name button

    // errors array
    $messages = array();


    // check if inputs are empty and valid (check length) -> add error message to array
    if (empty($_POST["idKey"]) || empty($_POST["userToken"])) {
        array_push($messages, "Overenie emailu bolo neúspešné.");
    }


    // if any errors -> insert to db
    if (count($messages) == 0) {

        // assign and test values
        $id = base64_decode($_POST["idKey"]);
        $userToken = $_POST["userToken"];

        //if activation unsuccessful
        if($activateUserController->emailActivation($id, $userToken)){

            array_push($messages, "Aktivácia účtu bola úspešná.");
            echo json_encode(['code' => 200, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
            die();

        }else{


            //generating code unsuccessful
            array_push($messages, "Aktivácia účtu bola neúspešná. Účet je buď aktivovaný alebo vypršala platnosť overovacieho emailu. Vygenerujte si a pošlite ďalší overovací email.");
            echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
            die();
        }

    } else {
        //if errors -> send array (code, array of errors)  || still on success

        echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE );
        die();
    }

} else {

    //unauthorized access -> redirect
    echo ("error");
    $activateUserController->redirect("../../../public_html/index.php");
    die();
}