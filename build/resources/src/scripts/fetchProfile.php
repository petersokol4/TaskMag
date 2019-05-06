<?php

session_start();

require_once(__DIR__ . "/../controllers/UserController.php");

$fetchController = new UserController();

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

                $profile = $fetchController->selectProfile($id);

                if (!empty($profile)) {
                    $responseArray["id"] =$fetchController->sanitizeNumber( $profile[0]["id_users"]);
                    $responseArray["userName"] =$fetchController->checkOutput( $profile[0]["user_name"]);
                    $responseArray["userEmail"] =$fetchController->checkOutput( $profile[0]["user_email"]);
                    $responseArray["userAbout"] =$fetchController->checkOutputLight( $profile[0]["user_about"]);

                    echo json_encode($responseArray, JSON_UNESCAPED_UNICODE);
                }

            }

            break;

        case "my":
            if (isset($_SESSION["user"]["id"])) {

                // assign and test values (only int)
                $id = $fetchController->sanitizeNumber($_SESSION["user"]["id"]);

                $profile = $fetchController->selectProfile($id);

                if (!empty($profile)) {
                    $responseArray["id"] =$fetchController->sanitizeNumber( $profile[0]["id_users"]);
                    $responseArray["userName"] =$fetchController->checkOutput( $profile[0]["user_name"]);
                    $responseArray["userEmail"] =$fetchController->checkOutput( $profile[0]["user_email"]);
                    $responseArray["userAbout"] =$fetchController->checkOutputLight( $profile[0]["user_about"]);
                    $responseArray["userAvatar"] =$fetchController->checkOutputLight( $profile[0]["user_avatar"]);

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
    $fetchController->redirect("../../../public_html/");
    die();
}