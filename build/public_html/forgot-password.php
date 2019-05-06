<?php
session_start();

//todo session

if(isset($_SESSION['user'])){
    header("Location: dashboard");
    die();
}

$title = "TaskMag - Zabudnuté heslo";
require_once (__DIR__."/../resources/src/views/header_includes/head.php");
?>

<body>
<div id="mainWrap m-0" style="min-height: 100vh">
    <div class="container " style="min-height: 100vh">
        <div class="row" style="min-height: 100vh">
            <div class="col-md-7 col-lg-8 text-center d-none d-md-block my-md-auto ">
                <p class="jumbo">Toto sa môže stať každému.</p>
                <div class="svg-image mx-auto d-block">
                    <img src="assets/img/forgotPass.svg">

                </div>

            </div>
            <div class="col-md-5 col-lg-4 text-center main-wrap my-auto rightColumn ">
                <div class="go-back-btn"><a href="login.php" title="Naspäť na prihlásenie"></a></div>
                <div class="logo">
                    <a href="index.php"><img src="assets/img/icons/logo.svg"></a>
                </div>
                <p class="title thin">Zabudnuté heslo</p>
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

                    <p>Zabudli ste heslo? Žiadny problém! Len zadajte Vašu emalovú adresu, na ktorú obdržíte link na vytvorenie nového hesla.</p>
                    <form role="form" method="post" id="_forgotForm">
                        <div class="form-group putError mb-5">
                            <label for="email" class="text-muted form-text" >Email</label>
                            <div class="input-icon">
                                <img src="assets/img/icons/Email.svg">
                            </div>
                            <input class="input-line input-default" type="email" id="email" name="email" value="">
                            <span></span>
                        </div>
                            <button class="btn btn-block mb-5" type="submit" name="forgot">
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                <span>pošlite mi email</span>
                            </button>
                    </form>
                </section>
            </div>
        </div>
    </div>
</div>
<script src="./assets/js/vendors.min.js"></script>
<script src="./assets/js/app.min.js"></script>

<script>
    // AJAX LOGIN

    var errorBox = $("#ajaxErrorsAlert");
    var errorBoxContent = $("#ajaxErrors");
    var errorMessage = "";

    $(document).ready(function () {

        //form
        var forgotForm = $("#_forgotForm");

        //form validation (jQuery Validation plugin)
        forgotForm.validate({
            rules: {                         //rules

                //input names and their rules

                email:{
                    required: true,
                    email: true
                }
            },
            messages:{                      //messages

                email:{
                    required: "Prosím, zadajte váš email",
                    email: "Zadajte správny tvar emailu napr. meno@priezvisko.sk"
                }
            },
            submitHandler: function () {

                //if the form is valid
                if (forgotForm.valid()){

                    //ajax
                    $(".spinner-border").removeClass("d-none");
                    var method = forgotForm.attr('method');

                    $.ajax({
                        url: '../resources/src/scripts/forgotUser.php',
                        type: method,
                        dataType: 'JSON',
                        data: forgotForm.serialize(),
                        success: function (data) {
                            // This is a callback that runs if the submission was a success.

                            $(".spinner-border").addClass("d-none");
                            //show some message
                            showMessagesLogin(data.code, data.msg);
                            return false;
                        },

                        error: function () {
                            alert("error");
                            $(".spinner-border").addClass("d-none");
                            // This is a callback that runs if the submission was not successful.
                        }
                    });

                    return false;
                }
            }
        });

        function showMessagesLogin(code, msg) {
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
