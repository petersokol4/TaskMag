<?php
/**
 * Copyright (c) 2019. TaskMag project managing web app. Created by Peter Sokol for Diploma Thesis.
 */

session_start();

require_once(__DIR__ . "/../controllers/UserController.php");

$forgotController = new UserController();

// if was send request from AJAX
if (isset($_POST["forgot"])) {       //button name

    // errors array
    $messages = array();


    // check if inputs are empty and valid (check length) -> add error message to array
    if (empty($_POST["email"])) {
        array_push($messages, "Prosím, zadajte váš email.");
    }else{
        if(!$forgotController->validateEmail($_POST["email"])){
            array_push($messages, "Zadajte správny tvar emailu.");
        }
    }

    if (count($messages) == 0) {

        // assign and test values
        $userEmail = $forgotController->checkInput($_POST["email"]);


        //check if there is an error during generating a token
        if($passwordToken = $forgotController->generateToken(16)){
            if($idKey = $forgotController->forgotPassword($userEmail, $passwordToken)){

                //email

                $receiverName = explode('@', $userEmail);
                $domain = DOMAIN_NAME_EMAIL;//TODO: ZMENIŤ LOCALHOST na doménu

                $message = "     
                    <h2>Ahoj $receiverName[0],</h2>
                    <p>Zaznamenali sme požiadavku na resetovanie Vášho hesla. Toto môžete urobiť kliknutím na nasledujúci odkaz alebo ho skopírujte a vložte do vášho prehliadača:<br/>
                    <br /><br />
                  
                    <a href='$domain/TaskMag/build/public_html/reset-password?idKey=$idKey&passwordToken=$passwordToken'>Kliknite sem pre resetovanie hesla</a>
                    <br /><br />
                    S pozdravom,
                    TaskMag Team
                    <br>
                    <br>
                    <p>Ak ste požiadavku o zmenu hesla neposlali vy, prosím ignorujte tento email.</p>
                    
                ";

                $subject = "Resetovanie hesla";

                //send email
                if($forgotController->sendEmail($userEmail,$message,$subject, VERIFICATION_MAIL_FROM_EMAIL)){

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
                array_push($messages, "Vyskytol sa problém pri resetovaní hesla. Možno ešte nemáte aktivovaný účet. Skontrolujte si emailovú schránku, na ktorú sme vám odoslali aktivačný email po registrácii.");
                echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                die();
            }

        }else{
            //generating code unsuccessful
            array_push($messages, "Vyskytol sa problém a resetovanie hesla nemôže byť dokončené.");
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
    $forgotController->redirect("../../../public_html/");
    die();
}