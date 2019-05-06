<?php

session_start();

require_once(__DIR__ . "/../controllers/UserController.php");

$updateController = new UserController();

// if was send request from AJAX
if (isset($_POST["updateProfile"]) && isset($_SESSION["user"]["id"])) {       //name of button

    // errors array
    $errors = array();

    //check inputs length
    // check if inputs are empty -> add error message to array
    if (empty($_POST["userName"])) {
        array_push($errors, "Prosím zadajte klienta");
    }else{
        if(!$updateController->checkInputLength($_POST["userName"],2,50)){array_push($errors, "Meno musí obsahovať minimálne 2 a maximálne 50 znakov");}
    }


    if (!empty($_POST["userAbout"])) {
        if(!$updateController->checkInputLength($_POST["userAbout"],11,400)){array_push($errors, "Popis musí obsahovať minimálne 10 a maximálne 400 znakov");}
    }

    //TODO this is only update MY profile
    // if any errors -> insert to db
    if (count($errors) == 0) {

        // assign and test values
        $userName = $updateController->checkInput($_POST["userName"]);
        $userAbout = $updateController->checkInput($_POST["userAbout"]);

        //update
        $id = $updateController->sanitizeNumber($_SESSION["user"]["id"]);

        if ($updateController->editProfile($id, $userName, $userAbout)) {

            session_regenerate_id(true);
            $_SESSION["user"]["name"] = $userName;
            array_push($errors, "Profil bol úspešne upravený.");
            echo json_encode(['code' => 200, 'msg' => $errors], JSON_UNESCAPED_UNICODE);
            die();

        } else {

            array_push($errors, "Vyskytol sa problém pri úprave profilu.");
            echo json_encode(['code' => 404, 'msg' => $errors], JSON_UNESCAPED_UNICODE);
            die();
        }


    } else {
        //if errors -> send array (code, array of errors)  || still on success

        echo json_encode(['code' => 404, 'msg' => $errors], JSON_UNESCAPED_UNICODE);
        die();
    }

} else {

    //ak sa spustí script bez parametrov
    $updateController->redirect("../../../public_html/");
    die();
}