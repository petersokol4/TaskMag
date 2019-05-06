<?php
/**
 * Copyright (c) 2019. TaskMag project managing web app. Created by Peter Sokol for Diploma Thesis.
 */

session_start();

require_once(__DIR__ . "/../controllers/InvitationController.php");

$iController = new InvitationController();

// if was send request from AJAX
if (isset($_POST['trust']) && $_POST['trust'] == 'yes') {      //name button

    // errors array
    $messages = array();

    // check if inputs are empty and valid (check length) -> add error message to array
    if (empty($_POST["idKey"]) || empty($_POST["invitation"])) {
        array_push($messages, "Došlo k chybe pri spracovaní požiadavky.");
    }


    // if any errors -> insert to db
    if (count($messages) == 0) {

        // assign and test values
        $i_Id = base64_decode($_POST["idKey"]);
        $i_Token = $_POST["invitation"];

        if($i_Creds = $iController->checkInvitationCode($i_Id, $i_Token))
        {

            //p_Id
            //email
            $p_Id = $iController->sanitizeNumber($i_Creds[0]);
            $email = $iController->sanitizeEmail($i_Creds[1]);

            if($u_Id = $iController->checkRegistration($email))
            {
                if($iController->assignUserToProject($u_Id, $p_Id)){

                    if($iController->deleteInvitation($i_Id))
                    {
                        array_push($messages, "Priradenie k projektu bolo úspešné. Pokračujte prihlásením.");
                        echo json_encode(['code' => 200, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                        die();
                    }
                    else
                    {
                        array_push($messages, "Vyskytla sa interná chyba.");
                        echo json_encode(['code' => 500, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                        die();
                    }

                }else{


                    array_push($messages, "Priradenie k projektu bolo neúspešné. Prosím, kontaktujte podporu.");
                    echo json_encode(['code' => 500, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                    die();
                }
            }
            else
            {
                //not registerd
                array_push($messages, "Najskôr sa musíte registrovať do TaskMag aplikácie. Po úspešnej registrácii znovu kliknite na odkaz v pozvánke.");
                echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                die();

            }
        }
        else
        {
            //no valid invitation
            array_push($messages, "Platnosť pozvánky už vypršala.");
            echo json_encode(['code' => 500, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
            die();

        }

    } else {
        //if errors -> send array (code, array of errors)  || still on success

        echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE );
        die();
    }

} else {

    //unauthorized access -> redirect
    $iController->redirect("../../../public_html/index");
    die();
}