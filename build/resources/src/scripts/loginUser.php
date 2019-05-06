<?php
/**
 * Copyright (c) 2019. TaskMag project managing web app. Created by Peter Sokol for Diploma Thesis.
 */

session_start();

require_once(__DIR__ . "/../controllers/UserController.php");

$loginController = new UserController();

// či sa odoslal formulár - ak sa stlačilo tlačidlo odoslať (name login)
if(isset($_POST["login"])){

    // messages array
    $messages = array();

    if (empty($_POST["email"])) {
        array_push($messages, "Prosím, zadajte váš email.");
    }else{
        if(!$loginController->validateEmail($_POST["email"])){
            array_push($messages, "Zadajte správny tvar emailu.");
        }
    }
    if (empty($_POST["upass"])) { array_push($messages, "Prosím, zadajte heslo"); }

    if (count($messages) == 0) {

        // priradenie hodnôt z formuláru
        $email = $loginController->test_input($_POST["email"]);
        $upass = $loginController->test_input($_POST["upass"]);

        $ip=$_SERVER['REMOTE_ADDR'];

        if($loginController->login($email,$upass, $ip))
        {
            array_push($messages, "Prihlásenie bolo úspešné.");
            echo json_encode(['code' => 200, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
            die();
        }else{
            //generating code unsuccessful
            array_push($messages, "Email alebo heslo sa nezhodujú.");
            echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
            die();
        }
    } else{

        echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE );
        die();
    }
}else{
    echo ("error");
    $loginController->redirect("../../../public_html/login.php");
    die();
}