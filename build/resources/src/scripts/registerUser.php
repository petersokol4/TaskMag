<?php
/**
 * Copyright (c) 2019. TaskMag project managing web app. Created by Peter Sokol for Diploma Thesis.
 */

session_start();

require_once(__DIR__ . "/../controllers/UserController.php");

$registerUserController = new UserController();

// if was send request from AJAX
if (isset($_POST["register"])) {       //button name

    // errors array
    $messages = array();


    // check if inputs are empty and valid (check length) -> add error message to array
    if (empty($_POST["conditions"])) {
        array_push($messages, "Musíte súhlasiť s podmienkami.");
    }
    if (empty($_POST["userName"])) {
        array_push($messages, "Prosím, zadajte vaše meno.");
    }else{
        if(!$registerUserController->checkInputLength($_POST["userName"],2,50)){array_push($messages, "Názov klienta musí obsahovať minimálne 2 a maximálne 50 znakov");}
    }
    if (empty($_POST["userEmail"])) {
        array_push($messages, "Prosím, zadajte váš email.");
    }else{
        if(!$registerUserController->validateEmail($_POST["userEmail"])){
            array_push($messages, "Zadajte správny tvar emailu.");
        }
        else{
            //check if email is already used
            if($registerUserController->checkEmailExists($_POST["userEmail"])){
                array_push($messages, "Emailová adresa je už použitá.");
            }
        }
    }
    if (empty($_POST["upass"])) {
        array_push($messages, "Prosím, zadajte vaše heslo");
    }else{
        if(!$registerUserController->checkPassword($_POST["upass"])){array_push($messages, "Heslo musí obsahovať 8 - 20 zankov a minimálne jedno veľké písmeno, jedno malé písmeno a jednu číslicu.");}
    }


    // if any errors -> insert to db
    if (count($messages) == 0) {

        // assign and test values
        $userName = $registerUserController->checkInput($_POST["userName"]);
        $userEmail = $registerUserController->checkInput($_POST["userEmail"]);
        $upass = $_POST["upass"];

        //check if there is an error during generating a token
        if($userToken = $registerUserController->generateToken(16)){
            if($idKey = $registerUserController->registration($userName, $userEmail, $upass, $userToken)){
                //email

                $domain = DOMAIN_NAME_EMAIL; //TODO: ZMENIŤ LOCALHOST na doménu
                $message = "     
                    <h2>Ahoj $userName,</h2>
                    <p>vitaj v TaskMag!<br/>
                    Ďakujeme, že ste si zvolili práve nás.</p>
                    <p>Prosím, aktivujte svoj účet kliknutím na nasledujúci odkaz alebo ho skopírujte a vložte do vášho prehliadača:<br/>
                    <br /><br />
                  
                    <a href='$domain/TaskMag/build/public_html/activate?idKey=$idKey&userToken=$userToken'>Kliknite sem pre aktivovanie účtu</a>
                    <br /><br />
                    S pozdravom,
                    TaskMag Team
                    <br>
                    <br>
                    <p>Ak ste sa na stránke TaskMag nezaregistrovali, prosím ignorujte tento email.</p>
                    
                ";

                $subject = "Potvrdenie registrácie";

                //send email
                if($registerUserController->sendEmail($userEmail,$message,$subject, VERIFICATION_MAIL_FROM_EMAIL)){

                    array_push($messages, "Ďakujeme! Prosím, skontrolujte si email a pokračujte podľa inštrukcií, ktoré v ňom nájdete.");
                    echo json_encode(['code' => 200, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                    die();

                }else{
                    //sending email unsuccessful
                    array_push($messages, "Vyskytol sa problém pri odosielaní emailu.");
                    echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                    die();
                }

            }else{
                // registation unsuccessful
                array_push($messages, "Vyskytol sa problém pri registrácii.");
                echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                die();
            }

        }else{
            //generating code unsuccessful
            array_push($messages, "Vyskytol sa problém a registrácia nemôže byť dokončená.");
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
    $registerUserController->redirect("../../../public_html/");
    die();
}