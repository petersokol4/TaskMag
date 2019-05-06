<?php

/**
 * Copyright (c) 2019. TaskMag project managing web app. Created by Peter Sokol for Diploma Thesis.
 */


session_start();

//if no values in GET parameters
if(!isset($_REQUEST['idKey']) || !isset($_REQUEST['userToken'])){
    header("Location: registration");
    die();
}

if(isset($_SESSION['user'])){
    header("Location: dashboard");
    die();
}

$title = "TaskMag - Aktivácia";
require_once (__DIR__."/../resources/src/views/header_includes/head.php");
?>

<body>
    <div id="mainWrap m-0" style="min-height: 100vh">
        <div class="container " style="min-height: 100vh">
            <div class="row" style="min-height: 100vh">
                <div class="col-md-7 col-lg-8 text-center d-none d-md-block my-md-auto ">
                    <p class="jumbo">Už len malý krôčik.</p>
                    <div class="svg-image mx-auto d-block">

                        <img src="assets/img/activate.svg">
                    </div>

                </div>
                <div class="col-md-5 col-lg-4 text-center main-wrap my-auto rightColumn ">

                            <div class="go-back-btn"><a href="index.php" title="Naspat na hlavnu stranku"></a></div>
                            <div class="logo">
                                <a href="index.php"><img src="assets/img/icons/logo.svg"></a>
                            </div>
                            <p class="title thin">Potvrďte registráciu</p>

                    <!-- errors -->
                    <div class="serverSideErrors">
                        <section class="errors">
                            <div id="ajaxErrorsAlert" class=" fade show" role="alert">
                                <div id="ajaxErrors"></div>
                            </div>
                        </section>
                    </div>
                    <!-- end of errors -->

                            <section class="">
                                <p>
                                    Ďakujeme za registráciu do TaskMag. Kliknutím na tlačidlo aktivujete svoj účet a môžete začať pracovať na svojich projektoch.
                                </p>
                            </section>

                        <div class="text-left mt-5">
                            <form role="form" method="post" id="activateForm" accept-charset="UTF-8" action="../resources/src/scripts/activateUser.php">
                                <input type="hidden" name="userToken" id="userToken" value="<?php echo $_REQUEST["userToken"]; ?>">
                                <input type="hidden" name="idKey" id="idKey" value="<?php echo $_REQUEST["idKey"]; ?>">

                                <button class="btn btn-block mb-5" type="submit" id="activateButton" name="activate">Potvrdiť registráciu</button>
                            </form>
                        </div>

                    </div>

        </div>
    </div>
    <script src="assets/js/vendors.min.js"></script>
    <script src="assets/js/app.min.js"></script>

    <script>

        var errorBox = $("#ajaxErrorsAlert");
        var errorBoxContent = $("#ajaxErrors");
        var errorMessage = "";

        $(document).ready(function () {

            //form
            var activateForm = $("#activateForm");


            $(document).on('click', "#activateButton", function (event) {
                event.preventDefault();

                var url = activateForm.attr('action');
                var method = activateForm.attr('method');

                $.ajax({
                    url: url,
                    type: method,
                    dataType: 'JSON',
                    data: activateForm.serialize() + "&trust=yes",      //no jQuery validation must in php check isset
                    success: function (data) {
                        // This is a callback that runs if the submission was a success.

                        //show some message
                        showMessages(data.code, data.msg);
                        activateForm[0].reset();
                    },

                    error: function () {
                    // This is a callback that runs if the submission was not successful.
                        alert("error");
                    }
                });

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
                    window.location='login';
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