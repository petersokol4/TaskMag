<?php
/**
 * Copyright (c) 2019. TaskMag project managing web app. Created by Peter Sokol for Diploma Thesis.
 */

session_start();

require_once(__DIR__ . "/../controllers/UserController.php");

$resetUserController = new UserController();

// if was send request from AJAX
if (isset($_POST['resetForm'])) {      //name button

    // messages array
    $messages = array();

    // check if inputs are empty and valid (check length) -> add error message to array
    if (empty($_POST["idKey"]) || empty($_POST["passwordToken"])) {
        array_push($messages, "Resetovanie hesla bolo neúspešné.");
    }

    if (empty($_POST["upass"]) || $_POST["upass"]==NULL ) {
        array_push($messages, "Prosím, zadajte heslo");
    }

    if (empty($_POST["cpass"]) || $_POST["cpass"]==NULL ) {
        array_push($messages, "Prosím, zadajte heslo");
    }

    if($_POST["upass"]!== $_POST["cpass"]) {array_push($messages, "Heslá, ktoré ste zadali sa nezhodujú.");}


    // if any errors -> insert to db
    if (count($messages) == 0) {

        // assign and test values
        $id = base64_decode($_POST["idKey"]);
        $passwordToken = $_POST["passwordToken"];
        $newPass = $_POST["cpass"];

        //if activation unsuccessful
        if($resetUserController->resetPassword($id, $passwordToken, $newPass)){

            array_push($messages, "Resetovanie hesla prebehlo úspešne.");
            echo json_encode(['code' => 200, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
            die();

        }else{

            //generating code unsuccessful
            array_push($messages, "Resetovanie hesla bolo neúspešné. Pravdepodobne vypršala platnosť overovacieho kódu. Kontaktujte podporu.");
            echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
            die();
        }

    } else {
        //if errors -> send array (code, array of errors)  || still on success

        echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE );
        die();
    }

} else {

    //unauthorized access -> redirect ON ERROR
    echo ("error");
    $resetUserController->redirect("../../../public_html/");
    die();
}
