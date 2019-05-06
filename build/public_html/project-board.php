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

$title = "TaskMag - ProjectBoard";
$active = "projectboard";
require_once (__DIR__."/../resources/src/views/headerTemplate.php");
?>
    </section>

    <section id="mainContent" class="container-fluid customWidth">
        <div id="headlineBox" class="row">
            <div>
                <h1 class="title m-0">Projekty</h1>
                <nav aria-label="breadcrumb" class="d-none d-md-block">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard">Nástenka</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Projekty</li>
                    </ol>
                </nav>
            </div>
            <ul class="list-unstyled list-group list-group-horizontal ml-auto">
                <li class="d-none d-md-block ml-2">
                    <a id="newProjectD" class="btn" href="#" role="button" data-toggle="modal"
                       data-target="#newProjectModal">Vytvoriť projekt</a>
                </li>
            </ul>
        </div>
        <hr>
        <!-- errors -->
        <div class="serverSideErrors">
            <section class="errors">
                <div id="ajaxErrorsAlert" class=" fade show" role="alert">
                    <div id="ajaxErrors"></div>
                </div>
            </section>
        </div>
        <!-- end of errors -->
        <div id="mainContentView"></div>
    </section>

    <button id="newProjectM" class="btn text-center relative d-md-none" data-toggle="modal" data-target="#newProjectModal"><span class="">Vytvoriť<br>projekt</span></button>


    <!-- MODALS -->

    <?php

    /**
     *
     * CREATE NEW PROJECT MODAL
     *
     */

    ?>

    <div class="modal fade animated bounceInDown" id="newProjectModal" tabindex="-1" role="dialog"
         aria-labelledby="Vytvoriť nový projekt" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newProjectModalTitle">Vytvorenie nového projektu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form accept-charset="utf-8" id="_project" method="post">
                        <!--                                    <div style="display:none">-->
                        <!--                                        <input type="hidden" name="fcs_csrf_token" value="a37d2b04de493957dccc697900e70e6a">-->
                        <!--                                    </div>-->
                        <div class="form-group putError">
                            <label for="projectName"><span
                                        class="text-uppercase formLabel required">Názov Projektu</span></label>
                            <input type="text" class="form-control" id="projectName" name="projectName"
                                   aria-describedby="názov-projektu" value="">
                        </div>
                        <div class="form-group putError">
                            <label for="projectClient"><span
                                        class="text-uppercase formLabel required">Klient</span></label>
                            <input type="text" class="form-control" id="projectClient" name="projectClient"
                                   aria-describedby="klient-projektu" value="">
                        </div>
                        <div class="form-group putError">
                            <label for="projectDescription"><span class="text-uppercase formLabel required">Popis Projektu</span></label>
                            <small id="descriptionHelp" class="form-text text-muted text-right">max. 400 znakov</small>
                            <textarea class="form-control" id="projectDescription" name="projectDescription"
                                      rows="3"></textarea>
                        </div>
                        <div class="form-group putError">
                            <label for="projectStatus"><span
                                        class="text-uppercase formLabel required">Status</span></label>
                            <select id="projectStatus" name="projectStatus" class="form-control"
                                    aria-describedby="status-projektu">
                                <option disabled selected value> -- Zvoľte status projektu --</option>
                                <option value="Nezačal">Nezačal</option>
                                <option value="Prebieha">Prebieha</option>
                                <option value="Pozastavený">Pozastavený</option>
                                <option value="Zrušený">Zrušený</option>
                                <option value="Dokončený">Dokončený</option>
                            </select>
                        </div>
                        <div class="form-group putError">
                            <label for="projectCategory"><span
                                        class="text-uppercase formLabel required">Kategória</span></label>
                            <input type="text" id="projectCategory" name="projectCategory" class="form-control"
                                    aria-describedby="kategória-projektu">
<!--                                <option disabled selected value> -- Zvoľte kategóriu projektu --</option>-->
<!--                                <option value="Dizajn">Dizajn</option>-->
<!--                                <option value="Web Aplikácia">Web Aplikácia</option>-->
<!--                                <option value="Web Stránka">Web Stránka</option>-->
<!--                                <option value="Wordpress Plugin">Wordpress Plugin</option>-->
<!--                                <option value="Wordpress Téma">Wordpress Téma</option>-->
<!--                                <option value="Mobilná aplikácia">Mobilná aplikácia</option>-->
                            </input>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 putError">
                                <label for="projectStart"><span class="text-uppercase formLabel required">Začiatok Projektu</span></label>
                                <input class="form-control datepickerStart" type="date" value="" id="projectStart"
                                       name="projectStart" aria-describedby="dátum-začiatku-projektu">
                            </div>
                            <div class="form-group col-md-6 putError">
                                <label for="projectEnd"><span
                                            class="text-uppercase formLabel required">Koniec Projektu</span></label>
                                <input class="form-control datepickerEnd" type="date" value="" id="projectEnd"
                                       name="projectEnd" aria-describedby="dátum-konca-projektu">
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="id" id="id" class="form-control" value="">
                        </div>

<!--                        <div id="addedFieldRow" class="form-row">-->
<!--                            <div class="col-12">-->
<!--                                <label for="projectTags"><span class="text-uppercase formLabel">Tagy</span></label>-->
<!--                            </div>-->
<!---->
<!--                            <div class="input-group mb-3 col-md-4 after-add-more">-->
<!---->
<!--                                <input type="text" name="tags[]" id="projectTags" class="form-control"-->
<!--                                       aria-describedby="prvý-tag-projektu">-->
<!--                                <div class="input-group-append">-->
<!--                                    <span class="input-group-text add-more" title="Pridať ďalšie pole.">+</span>-->
<!--                                </div>-->
<!---->
<!--                            </div>-->
<!---->
<!--                        </div>-->
<!---->
<!--                        --><?php
//                        /**
//                         *  fields to by copied
//                         */
//                        ?>
<!--                        <div class="copy d-none">-->
<!--                            <div class="input-group col-md-4 mb-3 addedField">-->
<!---->
<!--                                <input type="text" name="tags[]" class="form-control"-->
<!--                                       aria-describedby="ďalší-tag-projektu">-->
<!--                                <div class="input-group-append">-->
<!--                                    <span class="input-group-text remove" title="Odstrániť pole.">&times;</span>-->
<!--                                </div>-->
<!---->
<!--                            </div>-->
<!--                        </div>-->


                        <div class="modal-footer">
                            <button class="btn btn-default" data-dismiss="modal">Zavrieť</button>
                            <button class="btn" id="createProjectButton" name="insert" type="submit">Vytvoriť Projekt</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <?php

    /**
     *
     * DELETE MODAL
     *
     */

    ?>

    <div class="modal fade animated bounceInDown" id="deleteProjectModal" tabindex="-1" role="dialog"
         aria-labelledby="Vymazať projekt" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Vymazať projekt?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Ste si istý, že chcete vymazať tento projekt? Táto zmena je nevratná.
                </div>
                <div class="modal-footer">
                    <button class="btn" type="button" data-dismiss="modal">Zavrieť</button>
                    <button id="delete" class="btn" type="button"><i class="far fa-trash-alt"></i> Vymazať</button>
                </div>
            </div>
        </div>
    </div>



<!--    PROFILE -->

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





</main>


<script src="assets/js/vendors.min.js"></script>
<script src="assets/js/app.min.js"></script>
<script src="assets/js/project-board.min.js"></script>

</body>
</html>




