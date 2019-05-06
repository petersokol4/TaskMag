<?php

session_start();

require_once(__DIR__ . "/../controllers/InvitationController.php");

$iController = new InvitationController();

// if was send request from AJAX
if (isset($_POST["invitation"]) && isset($_POST["i_pN"]) && isset($_POST["i_pId"]) && $_SESSION["user"]["name"]) {       //name of button

    // errors array
    $messages = array();


    //check inputs length
    // check if inputs are empty -> add error message to array
    if (empty($_POST["memberEmail"])) {
        array_push($messages, "Prosím zadajte klienta");
    }else{
        if(!$iController->validateEmail($_POST["memberEmail"])){
            array_push($messages, "Prosím, zadajte email.");
        }
    }

    if (!empty($_POST["iMessage"])) {
        if(!$iController->checkInputLength($_POST["iMessage"],5,400)){array_push($messages, "Správa musí obsahovať minimálne 10 a maximálne 400 znakov");}
    }

    // if any errors -> insert to db
    if (count($messages) == 0) {

        $email = $iController->checkInput($_POST["memberEmail"]);
        $projectId = $iController->sanitizeNumber($_POST["i_pId"]);

        $projectName = $iController->checkInput($_POST["i_pN"]);
        $customMessage = $iController->checkInput($_POST["iMessage"]);
        $iName = $iController->checkOutput($_SESSION["user"]["name"]);
        $iEmail = $iController->checkOutput($_SESSION["user"]["email"]);

        //check if user (email) is already a member

        if(!$iController->checkIsMember($email, $projectId))
        {
            // assign and test values
            if($iToken = $iController->generateInvitationToken(32))
            {
                if($idKey = $iController->setInvitation($email,$projectId, $iToken)){

                    $receiverName = explode('@', $email);
                    $domain = DOMAIN_NAME_EMAIL;

                    //TODO: ZMENIŤ LOCALHOST na doménu
                    $message = "     
                        <h2>Ahoj $receiverName[0],</h2>
                        <div>
                            <p>užívateľ aplikácie TaskMag $iName ($iEmail) ťa pozýva spolupracovať na projekte '$projectName'.</p>
                            <p>$customMessage</p>
                            <br>
                            <p>Kliknutím na nasledujúci odkaz alebo jeho skopírovaním a vložením do prehliadača sa pridáte do projektu:<br/>
                        
                            <a href='$domain/TaskMag/build/public_html/invite?idKey=$idKey&invitation=$iToken'>Otvoriť projekt</a>
                            <br /><br />
                            S pozdravom,
                            TaskMag Team
                        </div>  
                    ";

                    $subject = "Pozvanie do spolupráce na projekte $projectName";

                    //send email
                    if($iController->sendEmail($email,$message,$subject, INVATION_MAIL_FROM_EMAIL)){

                        array_push($messages, "Ďakujeme! Prosím, skontrolujte si email a pokračujte podľa inštrukcií, ktoré v ňom nájdete.");
                        echo json_encode(['code' => 200, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                        die();

                    }else{
                        //sending email unsuccessful

                        $i_Id = base64_decode($idKey);
                        $iController->deleteInvitation($i_Id);
                        array_push($messages, "Vyskytol sa problém pri odosielaní emailu.");
                        echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                        die();
                    }
                }
                else
                {
                    array_push($messages, "Pozvánka pre email $email už bola odoslaná.");
                    echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                    die();
                }

            }
            else
            {
                array_push($messages, "Vyskytla sa chyba.");
                echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
                die();
            }
        }
        else
        {
            array_push($messages, "Člen, ktorého sa snažíte pozvať je už členom projektu $projectName.");
            echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
            die();
        }


    } else {
        //if errors -> send array (code, array of errors)  || still on success
        echo json_encode(['code' => 404, 'msg' => $messages], JSON_UNESCAPED_UNICODE);
        die();
    }

} else {

    //ak sa spustí script bez parametrov
    $iController->redirect("../../../public_html/");
    die();
}
