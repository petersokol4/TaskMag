<?php
session_start();

// if not logged in redirect
if(!$_SESSION["user"]){
    header("Location: login");
    die();
}else{
    //check session output
    $avatar = trim(htmlspecialchars($_SESSION["user"]["avatar"], ENT_QUOTES, 'UTF-8'));
    $email = filter_var(trim(htmlspecialchars($_SESSION["user"]["email"], ENT_QUOTES, 'UTF-8')), FILTER_SANITIZE_EMAIL);
    $name = trim(htmlspecialchars($_SESSION["user"]["name"], ENT_QUOTES, 'UTF-8'));
}

    $title = "TaskMag - DashBoard";
    $active = "dashboard";
    require_once (__DIR__."/../resources/src/views/headerTemplate.php");
?>


        <div id="mainContent" class="container-fluid customWidth">
            <div class="container p-0">
                <div class="row">
                    <div class="col-xl-6  .order-2.order-lg-1">
                        <div class="dateBox">
                            <div class="text-center text-xl-right">
                                <h3 class="d-none d-sm-block">Vitajte späť!</h3>
                                <h2><span id="welcome"></span>, <span class="uNameAjax"><?php echo $name; ?></span>!</h2>
                            </div>

                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="clockBox">
                            <canvas id="canvas" width="1000" height="1000" style="opacity: 0.85"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


<!--PROFILE-->

<?php require_once("../resources/src/views/profileModal.php");?>



<div class="modal fade animated bounceInDown" id="changeAvatarModal" tabindex="-1" role="dialog"
     aria-labelledby="Zmeniť foto" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Zmeniť Fotku</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="" id="_avatarForm" autocomplete="off" enctype="multipart/form-data">
                    <div class="form-group putError">
                        <div class="custom-file mt-2">
                            <input id="avatar" type="file" name="avatar" class="custom-file-input">
                            <label class="custom-file-label" for="avatar">
                                <span class="d-inline-block text-truncate w-75">Zvoľte fotku</span>
                            </label>
                            <input type="hidden" name="id" id="id" value="<?php echo $_SESSION["user"]["id"]?>">
                            <input type="hidden" name="oldAvatar" id="oldAvatar" value="<?php echo $_SESSION["user"]["avatar"]?>">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-default" data-dismiss="modal">Zavrieť</button>
                        <button class="btn" type="submit">Zmeniť fotku</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade animated bounceInDown" id="timerCheckModal" tabindex="-1" role="dialog"
     aria-labelledby="Spustený časovač" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="timersModalTitle">Spustený časovač</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Našiel sa spustený časovač. Chcete sa vrátiť a pozastaviť ho alebo sa chcete odhlásiť?</p>
            </div>
            <div class="modal-footer">
                <button id="backToProject" class="btn btn-default">Zobraziť projekt</button>
                <a href="../resources/src/scripts/logoutUser" class="btn" role="button" >Odhlásiť sa</a>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/vendors.min.js"></script>
<script src="assets/js/app.min.js"></script>
<script src="assets/js/dashboard.min.js"></script>


</body>
</html>