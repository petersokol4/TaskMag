<?php

session_start();

if(!isset($_REQUEST['idKey']) || !isset($_REQUEST['passwordToken'])){
    header("Location: registration");
    die();
}


if(isset($_SESSION['user'])){
    header("Location: dashboard");
    die();
}

$title = "TaskMag - Vytvorenie nového hesla";
require_once (__DIR__."/../resources/src/views/header_includes/head.php");
?>

<body>
<div <div id="mainWrap">
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-lg-8 text-center d-none d-md-block my-md-auto ">
                <p class="jumbo">Teraz je rad na Vás!</p>
                <div class="svg-image mx-auto d-block">
                    <img src="assets/img/resetPass.svg">

                </div>

            </div>
            <div class="col-md-5 col-lg-4 text-center main-wrap my-auto rightColumn ">

                <div class="go-back-btn"><a href="index.php" title="Naspat na hlavnu stranku"></a></div>
                <div class="logo">
                    <a href="index.php"><img src="assets/img/icons/logo.svg"></a>
                </div>
                <p class="title thin">Resetovanie hesla</p>
                <!-- errors -->
                <div class="serverSideErrors">
                    <section class="errors">
                        <div id="ajaxErrorsAlert" class=" fade show" role="alert">
                            <div id="ajaxErrors"></div>
                        </div>
                    </section>
                </div>
                <!-- end of errors -->
                <section class="text-left">

                <p>Vyrvorte si Vaše nové heslo. Kreativite sa medze nekladú, aj keď bezpečnosť je dôležitejšia. Tvoje nové heslo by malo obsahovať 8 až 20 znakov a minimálne jedno malé a jedno veľké písmeno a nezabudni na jednu číslicu.</p>

                <form role="form" method="post" id="_resetForm">
                    <div class="form-group putError">
                        <label for="upass" class="text-muted form-text" >Nové heslo</label>
                        <div class="input-icon">
                            <img class="lock" src="assets/img/icons/Lock.svg">
                        </div>
                        <div class="input-icon-back">
                            <svg class="toggle-password" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 128 81.7" style="enable-background:new 0 0 128 81.7;" xml:space="preserve">
                                        <title>Ukázať / skryť heslo</title>
                                <g id="pass-visibility">
                                    <path class="st0" d="M64,4.1c18.5,0,34.4,6.7,47.2,19.8c4.4,4.6,8.3,9.8,11.4,15.4c1.1,2,1,4.4-0.3,6.3
            c-3.3,5.1-7.3,9.7-11.7,13.9c-13,12.1-28.4,18.2-45.9,18.2S31.4,71.5,18,59.3c-4.6-4.1-8.7-8.8-12.2-13.8C4.5,43.6,4.4,41,5.5,39
            c3.1-5.5,6.9-10.6,11.3-15.1C29.6,10.7,45.4,4.1,64,4.1 M64,0.1C24.1,0,5.7,29.7,0.7,39.5c-1,1.9-0.9,4.2,0.3,6
            c5.8,9.1,26.3,36.2,63.7,36.2s57-27.2,62.5-36.2c1.1-1.8,1.2-4,0.2-5.9C122.4,29.8,103.9,0,64,0.1L64,0.1z"/>
                                    <path class="st0" d="M63.3,67.6c-15,0-27.1-12.4-27.1-27.7s12.2-27.7,27.1-27.7s27.1,12.4,27.1,27.7S78.3,67.6,63.3,67.6z
             M63.3,14.1c-13.9,0-25.1,11.5-25.1,25.7s11.3,25.7,25.1,25.7S88.4,54,88.4,39.8S77.2,14.1,63.3,14.1z"/>
                                    <path class="st0" d="M48,46.2c-0.5,0-0.9-0.3-1-0.8c-0.1-0.4-2.4-10,2.8-16.6c3.1-4,8.2-6,15.1-6c0.6,0,1,0.4,1,1s-0.4,1-1,1
            c-6.3,0-10.8,1.8-13.5,5.2c-4.6,5.9-2.5,14.9-2.5,15c0.1,0.5-0.2,1.1-0.7,1.2C48.1,46.2,48.1,46.2,48,46.2z"/>
                                </g>
                            </svg>
                        </div>
                        <input class="input-line input-pass" type="password" id="upass" name="upass">
                        <span></span>
                    </div>

                    <div class="form-group putError mb-5">
                        <label for="cpass" class="text-muted form-text" >Potvrďte nové heslo</label>
                        <div class="input-icon">
                            <img class="lock" src="assets/img/icons/Lock.svg">
                        </div>
                        <div class="input-icon-back">
                            <svg class="toggle-password-c" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 128 81.7" style="enable-background:new 0 0 128 81.7;" xml:space="preserve">
                                        <title>Ukázať / skryť heslo</title>
                                <g id="pass-visibility">
                                    <path class="st0" d="M64,4.1c18.5,0,34.4,6.7,47.2,19.8c4.4,4.6,8.3,9.8,11.4,15.4c1.1,2,1,4.4-0.3,6.3
            c-3.3,5.1-7.3,9.7-11.7,13.9c-13,12.1-28.4,18.2-45.9,18.2S31.4,71.5,18,59.3c-4.6-4.1-8.7-8.8-12.2-13.8C4.5,43.6,4.4,41,5.5,39
            c3.1-5.5,6.9-10.6,11.3-15.1C29.6,10.7,45.4,4.1,64,4.1 M64,0.1C24.1,0,5.7,29.7,0.7,39.5c-1,1.9-0.9,4.2,0.3,6
            c5.8,9.1,26.3,36.2,63.7,36.2s57-27.2,62.5-36.2c1.1-1.8,1.2-4,0.2-5.9C122.4,29.8,103.9,0,64,0.1L64,0.1z"/>
                                    <path class="st0" d="M63.3,67.6c-15,0-27.1-12.4-27.1-27.7s12.2-27.7,27.1-27.7s27.1,12.4,27.1,27.7S78.3,67.6,63.3,67.6z
             M63.3,14.1c-13.9,0-25.1,11.5-25.1,25.7s11.3,25.7,25.1,25.7S88.4,54,88.4,39.8S77.2,14.1,63.3,14.1z"/>
                                    <path class="st0" d="M48,46.2c-0.5,0-0.9-0.3-1-0.8c-0.1-0.4-2.4-10,2.8-16.6c3.1-4,8.2-6,15.1-6c0.6,0,1,0.4,1,1s-0.4,1-1,1
            c-6.3,0-10.8,1.8-13.5,5.2c-4.6,5.9-2.5,14.9-2.5,15c0.1,0.5-0.2,1.1-0.7,1.2C48.1,46.2,48.1,46.2,48,46.2z"/>
                                </g>
                            </svg>
                        </div>
                        <input class="input-line input-pass" type="password" id="cpass" name="cpass">
                        <span></span>
                    </div>
                    <input type="hidden" name="idKey" id="idKey" value="<?=$_REQUEST["idKey"]?>">
                    <input type="hidden" name="passwordToken" id="passwordToken" value="<?=$_REQUEST["passwordToken"]?>">
                    <button class="btn btn-block mb-5" type="submit" name="resetForm">resetovať heslo</button>
                </form>
                </section>
            </div>
        </div>
    </div>
</div>
<script src="./assets/js/vendors.min.js"></script>
<script src="./assets/js/app.min.js"></script>

<script>

    var errorBox = $("#ajaxErrorsAlert");
    var errorBoxContent = $("#ajaxErrors");
    var errorMessage = "";

    $(document).ready(function () {

        //form
        var resetForm = $("#_resetForm");

        resetForm.validate({
            rules:{                         //rules

                upass:{
                    required: true,
                    rangelength: [8, 20],
                    strongPassword: true
                },
                cpass:{
                    required: true,
                    rangelength: [8, 20],
                    strongPassword: true,
                    equalTo: "#upass"
                }
            },
            messages:{                      //messages
                upass:{
                    required: "Prosím, zadajte heslo",
                    rangelength: "Heslo musí obsahovať minimálne 8 a maximálne 20 znakov"
                },
                cpass:{
                    required: "Prosím, zadajte heslo",
                    rangelength: "Heslo musí obsahovať minimálne 8 a maximálne 20 znakov",
                    equalTo: "Heslá sa nezhodujú"
                }
            },

            submitHandler: function () {

                //if the form is valid
                if (resetForm.valid()){

                    //ajax

                    var method = resetForm.attr('method');

                    $.ajax({
                        url: '../resources/src/scripts/resetUser.php',
                        type: method,
                        dataType: 'JSON',
                        data: resetForm.serialize(),      //no jQuery validation must in php check isset
                        success: function (data) {
                            // This is a callback that runs if the submission was a success.

                            //show some message
                            showMessages(data.code, data.msg);



                            return false;
                        },

                        error: function () {
                                alert(error);
                            // This is a callback that runs if the submission was not successful.
                        }
                    });

                    return false;
                }
            }
        });

        function showMessages(code, msg) {
            // if success
            if (code == "200") {

                $.each(msg, function (key, value) {
                    errorMessage += ('<p>' + value + '</p>');
                });
                errorBox.addClass("alert alert-success");
                errorBoxContent.html(errorMessage);
                errorBox.alert();
                //auto-fade alert
                errorBox.fadeTo(5000, 5000).slideUp(5000, function () {
                });
                errorMessage="";
                window.location='dashboard';
                //if some errors
            } else if (code == "404") {
                errorMessage = '<h6>Ooops. Našlo sa pár chýb.</h6>';
                $.each(msg, function (key, value) {
                    errorMessage += ('<p>' + value + '</p>');
                });
                errorBox.addClass("alert alert-danger");
                errorBoxContent.html(errorMessage);
                errorBox.alert();
                //auto-fade alert
                errorBox.fadeTo(5000, 5000).slideUp(5000, function () {
                });
                errorMessage="";
            }
        }


    });
</script>

</body>