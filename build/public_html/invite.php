<?php

/**
 * Copyright (c) 2019. TaskMag project managing web app. Created by Peter Sokol for Diploma Thesis.
 */


session_start();

//if no values in GET parameters
if(!isset($_REQUEST['idKey']) || !isset($_REQUEST['invitation'])){
    header("Location: registration");
    die();
}

$title = "TaskMag - Pozvanie";
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
                <p class="title thin">Priradenie k projektu</p>

                <!-- errors -->
                <div class="serverSideErrors">
                    <section class="errors">
                        <div id="ajaxErrorsAlert" class=" fade show" role="alert">
                            <div id="ajaxErrors"></div>
                        </div>
                    </section>
                </div>
                <!-- end of errors -->

                <div class="">
                    <p id="msg">Prebieha spracovanie požiadavky...</p>
                </div>

                <div class="text-left mt-5">
                    <form role="form" method="post" id="inviteForm" accept-charset="UTF-8" action="../resources/src/scripts/completeInvitation.php">
                        <input type="hidden" name="invitation" id="invitaion" value="<?php echo $_REQUEST["invitation"]; ?>">
                        <input type="hidden" name="idKey" id="idKey" value="<?php echo $_REQUEST["idKey"]; ?>">
                    </form>
                    <a href="index.php" class="btn btn-block mb-5" id="redirectButton">Domovská stránka</a>
                </div>

            </div>

        </div>
    </div>
    <script src="assets/js/vendors.min.js"></script>
    <script src="assets/js/app.min.js"></script>

    <script>

        // var errorBox = $("#ajaxErrorsAlert");
        // var errorBoxContent = $("#ajaxErrors");
        // var errorMessage = "";

        $(document).ready(function () {

            //form
            var inviteForm = $("#inviteForm");

                var url = inviteForm.attr('action');
                var method = inviteForm.attr('method');

                $.ajax({
                    url: url,
                    type: method,
                    dataType: 'json',
                    data: inviteForm.serialize() + "&trust=yes",      //no jQuery validation must in php check isset
                    success: function (data) {
                        // This is a callback that runs if the submission was a success.

                        //show some message

                        switch (data.code)
                        {
                            case 200:
                                //login

                                //logout
                                $("#redirectButton").text("PRIHLÁSIŤ SA");
                                $("#redirectButton").attr('href', 'login');
                                $("#msg").html(data.msg);
                                break;

                            case 404:
                                //register

                                $("#redirectButton").text("REGISTROVAŤ SA");
                                $("#redirectButton").attr('href', 'registration');
                                $("#msg").html(data.msg);
                                break;

                            default:
                                $("#redirectButton").text("DOMOVSKÁ STRÁNKA");
                                $("#redirectButton").attr('href', 'index.php');
                                $("#msg").html(data.msg);
                        }
                    },

                    error: function () {
                        // This is a callback that runs if the submission was not successful.
                        alert("error");
                    }
                });




        });
    </script>

</body>