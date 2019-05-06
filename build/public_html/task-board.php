<?php
session_start();

// if not logged in redirect
if(!$_SESSION["user"]){
    header("Location: login");
    die();
}

if(empty($_REQUEST["project"]))
{
    header("Location: dashboard");
    die();
}else{
    $idProject = $_REQUEST["project"];
    $title = "TaskMag - TaskBoard";
    $active = "";
}

require_once (__DIR__."/../resources/src/views/headerTemplate.php");
?>
        <div id="mainContent" class="container-fluid customWidth">
            <div id="expanded">
                <div id="headlineBox" class="row justify-content-between d-none d-md-flex">
                    <div class="d-flex">
                        <h1 class="title m-0"><span class="projectNameAjax"></span></h1>
                        <span class="mr-2"><a id="projectInfo" data-id="<?php echo $idProject; ?>" class="action" href="#" role="button" data-toggle="modal" data-target="#projectInfoModal"><i class="fas fa-info-circle"></i></a></span>
                    </div>

                    <div>
                        <ul class="list-unstyled list-group list-group-horizontal">
                            <li class="ml-2">
                                <a id="newTaskD" class="btn d-none d-md-block" href="#" role="button" data-toggle="modal" data-target="#newTaskModal">Vytvoriť úlohu</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
<!--                    <nav aria-label="breadcrumb" class="d-none d-md-block">-->
<!--                        <ol class="breadcrumb">-->
<!--                            <li class="breadcrumb-item"><a href="project-board.php">Projekty</a></li>-->
<!--                            <li class="breadcrumb-item active" aria-current="page">NázovProjektu</li>-->
<!--                        </ol>-->
<!--                    </nav>-->
                    <ul class="list-unstyled list-group list-group-horizontal mt-3 justify-content-between align-items-center">
                        <li id="hideMainSidebar" class="p-1 d-none d-md-block">

                                <span id="expandIcon" title="maximalizovať"><i class="fas fa-expand"></i></span>
                                <span id="compressIcon" title="minimalizovať" class="d-none"><i class="fas fa-compress"></i></span>

                        </li>
                        <li class="d-md-none">
                            <h5 style="cursor: pointer;" class="m-0" data-id="<?php echo $idProject; ?>" data-toggle="modal" data-target="#projectInfoModal"><span class="projectNameAjax shortTitle"></span></h5>
                        </li>
                        <li id="expandedProjectName" class="d-none">
                            <h5 style="cursor: pointer;" class="m-0" data-id="<?php echo $idProject; ?>" data-toggle="modal" data-target="#projectInfoModal"><span class="projectNameAjax"></span></h5>
                        </li>
                        <li>
                            <ul class="list-unstyled list-group list-group-horizontal justify-content-between align-items-center">
                                <?php

                                if(!empty($_SESSION["timer"]["project"]) && !empty($_SESSION["timer"]["start"]) && !empty($_SESSION["timer"]["id"]) && $_SESSION["timer"]["project"] == $idProject)
                                {
                                    ?>
                                    <li id="timerTime">
                                        <a id="stopTimer" href="#" role="button" data-start="<?php echo $_SESSION["timer"]["start"];?>" data-toggle="modal"
                                           data-target="#stopTimerModal" title="Zastaviť časovač">
                                <span id="clockTimer" class="text-wrap badge badge-danger">
                                    <span>Beží</span>
                                </span>
                                        </a>
                                    </li>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <li id="timerButton">
                                        <a id="startTimer" href="javascript:void(0)" role="button">
                                        <span class="text-wrap badge badge-primary p-1 d-flex"><i class="far fa-clock"></i><span class="d-none d-md-block ml-1">Spustiť</span><span class="ml-1">Časovač</span>
                                        </a>
                                    </li>
                                    <?php
                                }
                                ?>

                                <li id="expandCreateTaskButton" class="d-none ml-2">
                                    <a id="newTaskD" href="javascript: void(0)" role="button" data-toggle="modal" data-target="#newTaskModal">
                                        <span class="text-wrap badge badge-primary p-1 d-flex">Vytvoriť úlohu</span>
                                    </a>
                                </li>
                            </ul>
                        </li>



<!--                        <li class="ml-3">-->
<!--                            <a href="#" role="button" ><img src="uploads/users/profilePic.jpg" class="rounded-circle profile" alt="" width="30" height="30" data-toggle="tooltip" data-placement="bottom" title="Andrea"></a>-->
<!--                        </li>-->


                    </ul>
                </div>
            </div>
            <hr class="mt-2 mb-3">
            <!-- COLUMNS -->

            <div id="listsContentAjax" class="lists d-flex">


            </div>
        </div>
    </section>

    <button id="newProjectM" class="btn text-center relative d-md-none" data-toggle="modal" data-target="#newTaskModal"><span class="">Vytvoriť<br>úlohu</span></button>



    <?php

    /**
     *
     * MODALS
     *
     */

    ?>







<div class="modal fade animated bounceInDown" id="showAttachmentModal" tabindex="-1" role="dialog"
     aria-labelledby="Zoznam príloh" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Zoznam príloh</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="_zipAttach" method="post" accept-charset="UTF-8" action="../resources/src/scripts/zipAttachments.php">
                <input type="hidden" name="dirZIP" id="dirZIP" value="">
                <input type="hidden" name="projectZIP" id="projectZIP" value="">
                <div id="attachmentListContent">

                <?php // content from AJAX ?>

                </div>
            </form>
        </div>
    </div>
</div>




<div class="modal fade animated bounceInDown" id="deleteAttachModal" tabindex="-1" role="dialog"
     aria-labelledby="Vymazať prílohu" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Vymazať?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <div class="modal-body">
                    Ste si istý, že chcete vymazať túto prílohu? Táto zmena je nevratná.
                </div>
                <div class="modal-footer">
                    <button class="btn" type="button" data-dismiss="modal">Zavrieť</button>
                    <button id="deleteAttachButton" class="btn deleteLink"><i class="far fa-trash-alt"></i> Vymazať</button>
                </div>
        </div>
    </div>
</div>




<div class="modal fade animated bounceInDown" id="deleteCommentModal" tabindex="-1" role="dialog"
     aria-labelledby="Vymazať komentár" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Vymazať?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form role="form" method="post" id="_deleteComment">
                <div class="modal-body">
                    Ste si istý, že chcete vymazať túto úlohu? Táto zmena je nevratná.
                    <input type="hidden" id="idComment" name="idComment" value="">
                    <input type="hidden" id="" name="projectId" value="<?php echo $idProject; ?>">
                </div>
                <div class="modal-footer">
                    <button class="btn" type="button" data-dismiss="modal">Zavrieť</button>
                    <button id="deleteCommentButton" class="btn deleteLink" type="submit"><i class="far fa-trash-alt"></i> Vymazať</button>
                </div>
            </form>
        </div>
    </div>
</div>















    <?php
    /**
     *
     * TASK MODAL
     *
     */
    ?>

    <div class="modal fade animated bounceInDown" id="taskModal" tabindex="-1" role="dialog"
         aria-labelledby="Task" aria-hidden="true" style="overflow: auto !important;">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="taskNameAjax">Názov tasku</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-8 order-2 order-lg-1 p-3" id="taskMainContent">
                                <section class="mt-2">
                                    <div class="row">
                                        <div class="col d-flex flex-wrap justify-content-between">
                                            <h6 class="mb-3">Popis</h6>
                                            <?php // TODO dont show if userId (me) != assigned or superAdmin?>
<!--                                            <a class="action" href="#" role="button" data-toggle="tooltip"-->
<!--                                               data-placement="bottom" title="Upraviť"><i class="far fa-edit"></i></a>-->
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col" style="overflow-wrap: break-word;">
                                            <p id="taskDescriptionAjax" style="white-space: pre-line;">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto,
                                                dignissimos sed! Animi consequuntur dolore doloribus eaque eius enim
                                                ipsa molestias soluta. Blanditiis eligendi iure molestias quam quia, quo
                                                reiciendis. Facilis.</p>
                                        </div>
                                    </div>
                                </section>
                                <hr class="mt-4 mb-4">
<!--                                <section id="subtaskSection">-->
<!--                                    <div class="row">-->
<!--                                        <div class="col d-flex flex-wrap justify-content-between">-->
<!--                                            <h6 class="mb-3">Podúlohy</h6>-->
<!--                                            --><?php ////TODO dont show if userId (me) != assigned or superAdmin?>
<!--                                            <a class="action" href="#" role="button" data-toggle="tooltip"-->
<!--                                               data-placement="bottom" title="Pridať"><i class="fas fa-plus-square"></i></a>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="row">-->
<!--                                        <div class="col">-->
<!--                                            <div class="progress mb-3" style="height: 4px;" data-toggle="tooltip"-->
<!--                                                 data-placement="bottom" title="40%">-->
<!--                                                <div class="progress-bar bg-warning" role="progressbar"-->
<!--                                                     style="width: 40%" aria-valuenow="40" aria-valuemin="0"-->
<!--                                                     aria-valuemax="40"></div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="row">-->
<!--                                        <div class="col">-->
<!--                                            <ul class="list-unstyled m-0">-->
<!--                                                <li class="subTask p-3 my-2 d-flex justify-content-between">-->
<!--                                                    <div class="custom-control custom-checkbox mr-2">-->
<!--                                                        --><?php //// TODO checked?>
<!--                                                        <input type="checkbox" class="custom-control-input"-->
<!--                                                               id="customCheck1" checked>-->
<!--                                                        <label class="custom-control-label" for="customCheck1"></label>-->
<!--                                                    </div>-->
<!--                                                    <div class="mr-2 w-100">-->
<!--                                                        <textarea class="m-0 form-control">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iure neque quaerat ratione reiciendis repellat sequi.</textarea>-->
<!--                                                    </div>-->
<!--                                                    <div class="mr-2">-->
<!--                                                        <i class="far fa-edit"></i>-->
<!--                                                    </div>-->
<!--                                                    <div>-->
<!--                                                        <i class="far fa-trash-alt"></i>-->
<!--                                                    </div>-->
<!--                                                </li>-->
<!--                                                <li class="subTask p-3 my-2 d-flex justify-content-between">-->
<!--                                                    <div class="custom-control custom-checkbox mr-2">-->
<!--                                                        --><?php //// TODO checked?>
<!--                                                        <input type="checkbox" class="custom-control-input"-->
<!--                                                               id="customCheck2">-->
<!--                                                        <label class="custom-control-label" for="customCheck2"></label>-->
<!--                                                    </div>-->
<!--                                                    <div class="mr-2 w-100">-->
<!--                                                        <div class="m-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iure neque quaerat ratione reiciendis repellat sequi.</div>-->
<!--                                                    </div>-->
<!--                                                    <div class="mr-2">-->
<!--                                                        <i class="far fa-edit"></i>-->
<!--                                                    </div>-->
<!--                                                    <div>-->
<!--                                                        <i class="far fa-trash-alt"></i>-->
<!--                                                    </div>-->
<!--                                                </li>-->
<!--                                            </ul>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </section>-->
<!--                                <hr class="mt-4 mb-4">-->
                                <section>
                                    <div class="row">
                                        <div class="col d-flex flex-wrap justify-content-between">
                                            <h6 class="mb-3">Prílohy</h6>
                                            <div>
                                                <?php // TODO dont show if attachments == null ?>
<!--                                                <a class="action" href="#" role="button" data-toggle="tooltip"-->
<!--                                                   data-placement="bottom" title="Stiahnuť všetko"><i-->
<!--                                                            class="fas fa-arrow-alt-circle-down"></i></a>-->
                                                <a class="action" href="#" role="button" data-toggle="modal"
                                                   data-target="#addAttachmentModal" title="Pridať"><i
                                                            class="fas fa-plus-square"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col text-center">
<!--                                            <ul class="list-unstyled m-0 d-flex flex-wrap justify-content-start">-->
<!--                                                <li><a href="#" role="button"><i class="far fa-file-pdf"></i> PDF</a>-->
<!--                                                </li>-->
<!--                                                <li><a href="#" role="button"><i class="far fa-file-image"></i>-->
<!--                                                        IMAGE</a></li>-->
<!--                                                <li><a href="#" role="button"><i class="fas fa-file-alt"></i>-->
<!--                                                        DOCUMENT</a></li>-->
<!--                                                <li><a href="#" role="button"><i class="fas fa-file-archive"></i>-->
<!--                                                        ZIP</a></li>-->
<!--                                            </ul>-->
                                            <button data-id="" role="button" class="btn btn-primary" id="showAttachButton" data-toggle="modal" data-target="#showAttachmentModal">Zobraziť prílohy<span id="attachCountAjax" class="ml-2"></span></button>
                                        </div>
                                    </div>
                                </section>
                                <hr class="mt-4 mb-4">
                                <section>
                                    <div class="row">
                                        <div class="col">
                                            <h6 class="mb-3">Komentáre</h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <form role="form" method="post" action="" name="" id="_commentForm">

                                                <input type="hidden" name="cTaskId" id="cTaskId" value="">
                                                <input type="hidden" name="cProjectId" id="cProjectId" value="<?php echo $idProject; ?>">
                                                <div class="form-group putError">
                                                    <textarea class="form-control" id="cContent" name="cContent" placeholder="Napíšte komentár" rows="2"></textarea>
                                                </div>
                                                <div class="d-flex flex-wrap justify-content-end">
                                                    <button type="submit" class="btn btn-primary" name="comment" id="commentButton"><i
                                                                class="fas fa-paper-plane"></i> Komentovať
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <hr class="mt-4 mb-4">
                                    <div class="row">
                                        <div class="col">
                                            <ul class="list-unstyled m-0">
                                                <li id="commentContentAjax">
                                                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </section>
                            </div>
                            <div class="col-lg-4 order-1 order-lg-2 p-3" id="taskSidebar">
                                <div class="row mt-2">
                                    <div class="col taskComplete">
                                        <?php // TODO if status == done show first else show second?>
                                        <div class="m-0 d-flex flex-wrap justify-content-center">
                                            <button id="completeButton" data-id="" role="button" class="btn bg-danger d-none"><i class="far fa-check-circle"></i> Dokončiť</button>
                                            <button id="startButton" data-id="" role="button" class="btn bg-success d-none"><i class="fas fa-play-circle"></i> Začať</button>
                                        </div>
                                        <div class="m-0 text-center">
                                            <h5 id="completeTitle"><span class="completedIcon"><i class="far fa-check-circle"></i></span>Dokončené</h5>
                                            <p>
<!--                                                <span>email@email.com</span><br>-->
                                                <span id="taskCompletedAjax"></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-4">
                                <div class="row">
                                    <div id="taskActionsAjax" class="col taskActions">
                                        <ul class="list-unstyled m-0 d-flex flex-wrap justify-content-between">


                                            <li class="">
                                                <a href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                                                   aria-expanded="false"><span class="mr-2"><i class="fas fa-sliders-h"></i></span>MOŽNOSTI</a>
                                                <div class="dropdown-menu">
                                                    <a id="updateTask" class="dropdown-item" href="#" role="button" data-toggle="modal"
                                                       data-target="#newTaskModal" data-dismiss="modal" data-id="">Upraviť</a>
                                                    <a id="deleteTask" class="dropdown-item" href="#" role="button" data-toggle="modal"
                                                       data-target="#deleteTaskModal" data-id="">Vymazať</a>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <hr class="mt-4 mb-4">
                                <div class="row">
                                    <div class="col taskInfo">
                                        <ul class="list-unstyled m-0">
                                            <li>
                                                <p><span class="taskIconInfo"><i class="fas fa-rocket" data-toggle="tooltip" data-placement="bottom" title="Projekt"></i></span><span class="projectNameAjax"></span></p>
                                            </li>
                                            <li>
                                                <p><span class="taskIconInfo"><i class="fas fa-bolt" data-toggle="tooltip" data-placement="bottom" title="Priorita"></i></span><span id="taskPriorityAjax"></span>
                                                </p>
                                            </li>
                                            <li>
                                                <p>
                                                    <span class="taskIconInfo" data-toggle="tooltip"
                                                          data-placement="bottom" title="Vytvorené"><i
                                                                class="far fa-calendar-plus"></i></span><span id="taskCreatedAjax">28/10/2018, 08:35</span>
                                                </p>
                                            </li>
                                            <li>
                                                <?php // TODO if dueTime < currentTime add class overDue?>
                                                <p id="overdueTaskAjax" class="">
                                                    <span class="taskIconInfo" data-toggle="tooltip"
                                                          data-placement="bottom" title="Plánované ukončenie"><i
                                                                class="far fa-calendar-check"></i></span><span id="taskDueDateAjax">28/11/2018, 12:00</span>
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <hr class="mt-4 mb-4">
                                <div class="row">
                                    <div class="col d-flex flex-wrap justify-content-between">
                                        <h6 class="mb-3">Priradení členovia</h6>
                                        <div>
                                            <a href="#" role="button" data-toggle="modal" data-target="#memberModal" title="Pridať" id="assignMemberTaskButton"><i class="fas fa-plus-square"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col taskAssigned">

                                        <ul id="taskMemberContentAjax" class="list-unstyled m-0 d-flex flex-wrap justify-content-start">

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php
    /**
     *
     * ABOUT PROJECT MODAL
     *
     */
    ?>

    <div class="modal fade animated bounceInDown" id="projectInfoModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLongTitle" aria-hidden="true" style="overflow: auto !important;">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header align-items-center">
<!--                    <a href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span-->
<!--                                class="mb-0 mr-3 subtitle"><i class="fas fa-ellipsis-v"></i></span></a>-->
<!--                    <div class="dropdown-menu">-->
<!--                        <a class="dropdown-item" href="#" role="button" data-toggle="modal" data-target="#newTaskModal"-->
<!--                           data-dismiss="modal">Upraviť</a>-->
<!--                        --><?php //// TODO modal či chce naozaj vymazať?>
<!--                        <a class="dropdown-item" href="#" role="button" data-toggle="modal"-->
<!--                           data-target="#deleteTaskModal">Vymazať</a>-->
<!--                    </div>-->
                    <h5 class="modal-title" id="projectTitle"><span class="projectNameAjax"></span></h5>
                    <button type="button" class="close align-self-start" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-8 order-2 order-lg-1 p-3" id="taskMainContent">
                                <section class="mt-2">
                                    <div class="row">
                                        <div class="col d-flex flex-wrap justify-content-between">
                                            <h6 class="mb-3">Popis</h6>
                                            <?php // TODO dont show if userId (me) != assigned or superAdmin?>
<!--                                            <a class="action" href="#" role="button" data-toggle="tooltip"-->
<!--                                               data-placement="bottom" title="Upraviť"><i class="far fa-edit"></i></a>-->
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <p id="projectDescriptionAjax">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto,
                                                dignissimos sed! Animi consequuntur dolore doloribus eaque eius enim
                                                ipsa molestias soluta. Blanditiis eligendi iure molestias quam quia, quo
                                                reiciendis. Facilis.</p>
                                        </div>
                                    </div>
                                </section>
                                <hr class="my-4">
                                <div>
                                    <div class="row">
                                        <div class="col">
                                            <h6 class="mb-3">Štatistika</h6>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col chartBox">
                                            <div class="wrapper py-3">
                                                <canvas id="statChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 my-4">
                                        <div class="boxBase p-3" style="box-shadow: 0 1px 15px 1px rgba(90,90,90,0.2); border: 1px solid #f9f9f9; border-radius: 5px; ">
                                            <div class="box-header"><small>Cycle Time</small></div>
                                            <div class="box-body subtitle my-2"><span id="cycleTimeAjax"></span></div>
                                            <div class="box-icon" style="position: absolute; font-size: 42px; color:#babcbe; top: 30px; right: 40px;"><i class="fas fa-history"></i></div>
<!--                                            <div class="box-footer">-->
<!--                                                <div class="progress" style="height: 3px">-->
<!--                                                    <div class="progress-bar" id="assignedProgressbarAjax" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0"></div>-->
<!--                                                </div>-->
<!--                                            </div>-->
                                        </div>
                                    </div>
                                    <div class="col-md-6 my-4">
                                        <div class="boxBase p-3" style="box-shadow: 0 1px 15px 1px rgba(90,90,90,0.2); border: 1px solid #f9f9f9; ">
                                            <div class="box-header"><small>Potvrdení členovia</small></div>
                                            <div class="box-body subtitle my-2"><span id="acceptedUsersAjax">0</span> %</div>
                                            <div class="box-icon" style="position: absolute; font-size: 42px; color:#babcbe; top: 30px; right: 40px;"><i class="fas fa-user-secret"></i></div>
                                            <div class="box-footer">
                                                <div class="progress" style="height: 3px">
                                                    <div class="progress-bar" id="acceptedProgressbarAjax" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-4">
                                <section>
                                    <div class="row">
                                        <div class="col d-flex justify-content-between">
                                            <h6 class="mb-3">Členovia</h6>
                                            <?php //TODO dont show if userId (me) != assigned or superAdmin?>
                                            <div>
                                                <a class="action" id="timeSheetButton" href="#" role="button" data-toggle="modal" data-target="#timersModal" title="Výpis časovačov"><i class="fas fa-list"></i></a>
                                                <a class="action" href="#" role="button" data-toggle="modal" data-target="#addMembersModal" title="Pridať člena"><i class="fas fa-plus-square"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col text-center">
                                            <button data-id="" role="button" class="btn btn-primary my-2" id="showMembersButton" data-toggle="modal" data-target="#showMembersModal">Zobraziť členov projektu</button>
                                        </div>
                                    </div>
                                </section>
                            </div>
                            <div class="col-lg-4 order-1 order-lg-2 p-3" id="taskSidebar">
                                <div class="row mt-2">
                                    <div class="col taskComplete">
                                        <?php // TODO meniť status na základe DB?>
                                        <div class="list-unstyled m-0 text-left">
                                            <h5>
<!--                                                <span class="completedIcon"><i class="far fa-check-circle"></i></span>-->
                                                <span id="projectStatusAjax"></span>
                                            </h5>
<!--                                            <p>-->
<!--                                                <span>24/10/2019,</span><span> 08:35</span>-->
<!--                                            </p>-->
                                        </div>
                                    </div>
                                </div>
<!--                                <hr class="my-4">-->
<!--                                <div class="row">-->
<!--                                    <div class="col taskActions">-->
<!--                                        <div class="list-unstyled projectTags">-->
<!--                                            <span class="text-wrap badge badge-dark">wireframe</span>-->
<!--                                            <span class="text-wrap badge badge-dark">ux</span>-->
<!--                                            <span class="text-wrap badge badge-dark">ui</span>-->
<!--                                            <span class="text-wrap badge badge-dark">adobe xd</span>-->
<!--                                            <span class="text-wrap badge badge-dark">adobe xd</span>-->
<!--                                            <span class="text-wrap badge badge-dark">adobe xd</span>-->
<!--                                            <span class="text-wrap badge badge-dark">adobe xd</span>-->
<!--                                            <span class="text-wrap badge badge-dark">adobe xd</span>-->
<!--                                            <span class="text-wrap badge badge-dark">adobe xd</span>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
                                <hr class="mt-4 mb-4">
                                <div class="row">
                                    <div class="col taskInfo">
                                        <ul class="list-unstyled m-0">
                                            <li>
                                                <p>
                                                    <span class="taskIconInfo" data-toggle="tooltip" data-placement="bottom" title="Klient">
                                                        <i class="far fa-handshake"></i>
                                                    </span>
                                                    <span>
                                                        <span id="projectClientAjax"></span>
                                                    </span>
                                                </p>
                                            </li>
                                            <li>
                                                <p><span class="taskIconInfo" data-toggle="tooltip" data-placement="bottom" title="Kategória">
                                                        <i class="far fa-folder-open"></i>
                                                    </span>
                                                    <span>
                                                        <span id="projectCategoryAjax"></span>
                                                    </span>
                                                </p>
                                            </li>
                                            <li>
                                                <p>
                                                    <span  class="taskIconInfo" data-toggle="tooltip"
                                                          data-placement="bottom" title="Dátum vytvorenia"><i
                                                                class="far fa-calendar-plus"></i></span><span><span id="projectCreatedAjax"></span></span>
                                                </p>
                                            </li>
                                            <li>
                                                <p>
                                                    <span class="taskIconInfo" data-toggle="tooltip"
                                                          data-placement="bottom" title="Plánovaný začiatok projektu"><i
                                                                class="fas fa-hourglass-start"></i></span><span><span id="projectStartAjax"></span></span>
                                                </p>
                                            </li>
                                            <li>
                                                <?php // TODO if dueTime < currentTime add class overDue?>
                                                <p id="overdueProjectAjax" class="">
                                                    <span class="taskIconInfo" data-toggle="tooltip" data-placement="bottom" title="Plánované ukončenie projektu">
                                                        <i class="far fa-calendar-check"></i>
                                                    </span>
                                                    <span>
                                                        <span id="projectEndAjax"></span>
                                                    </span>
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





<div class="modal fade animated bounceInDown" id="memberInfoModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLongTitle" aria-hidden="true" style="overflow: auto !important;">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header align-items-center">
                <h5 class="modal-title"><span id="memberNameAjax"></span></h5>
                <button type="button" class="close align-self-start" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="conteiner-fluid">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="d-flex justify-content-center my-3">
                                <img id="memberAvatarAjax" src="" class="rounded-circle profile avatarImg" alt="" width="200" height="200">
                            </div>
                        </div>
                        <div class="col-lg-8 mt-3">
                            <ul class="list-unstyled py-3 m-0">
                                <li>
                                    <p>
                                        <span class="taskIconInfo" data-toggle="tooltip" data-placement="bottom" title="Email"><i class="far fa-envelope"></i></span>
                                        <span id="memberEmailAjax"></span>
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        <span  class="taskIconInfo" data-toggle="tooltip" data-placement="bottom" title="Registrácia"><i class="fas fa-briefcase"></i></span>
                                        <span id="memberRegisteredAjax"></span>
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        <span  class="taskIconInfo" data-toggle="tooltip" data-placement="bottom" title="Priradenie k projektu"><i class="fas fa-file-signature"></i></span>
                                        <span id="memberAssignedAjax"></span>
                                    </p>
                                </li>
                            </ul>
                            <h6>Poznámka</h6>
                            <p id="memberAboutAjax">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. At consectetur cumque eveniet perspiciatis possimus sint veritatis! Ab beatae deserunt enim exercitationem, id illum magnam nobis omnis quas quia quis, sed?
                            </p>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="row">
                        <div class="col">
                            <a data-toggle="collapse" href="#collapseStat" role="button" aria-expanded="false" aria-controls="collapseStat"><h6><span class="mr-2"><i class="fas fa-chart-pie"></i></span>Štatistika</h6></a>
                        </div>
                    </div>
                    <div class="collapse" id="collapseStat">
                        <div class="row">
                            <div class="col">
                                <div class="ml-auto mr-auto my-4 chartBox" style="max-width: 500px;">
                                    <div class="wrapper py-3">
                                        <canvas id="memberChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="row">
                            <div class="col">
                                <div class="ml-auto mr-auto my-4 chartBox" style="max-width: 500px;">
                                    <div class="wrapper py-3">
                                        <canvas id="doneChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="row">
                            <div class="col">
                                <div class="ml-auto mr-auto my-4 chartBox" style="max-width: 500px;">
                                    <div class="wrapper py-3">
                                        <canvas id="timeChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">
                    <div class="row">
                        <div class="col">
                            <a data-toggle="collapse" href="#collapseTimmers" role="button" aria-expanded="false" aria-controls="collapseTimmers"><h6 class="mb-3"><span class="mr-2"><i class="fas fa-user-clock"></i></span> Pracovný výkaz</h6></a>
                            <div class="collapse" id="collapseTimmers">
                                <div id="memberTimesheetContent"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





    <?php

    /**
     *
     *  ADD MEMBERS TO PROJECT MODAL
     *
     */

    ?>

    <div class="modal fade animated bounceInDown" id="addMembersModal" tabindex="-1" role="dialog"
         aria-labelledby="Pridať členov do projektu" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="">Pridať Členov</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" accept-charset="utf-8" class="" id="i_Form">
                        <!--                                    <div style="display:none">-->
                        <!--                                        <input type="hidden" name="fcs_csrf_token" value="a37d2b04de493957dccc697900e70e6a">-->
                        <!--                                    </div>-->

                        <div class="form-group putError">
                            <label for="memberEmail"><span
                                        class="text-uppercase formLabel required">Email</span></label>
                            <input type="email" class="form-control" id="memberEmail" name="memberEmail"
                                   aria-describedby="názov-úlohy" value="">
                        </div>
                        <div class="form-group putError">
                            <label for="emailDescription"><span
                                        class="text-uppercase formLabel">Správa (voliteľné)</span></label>
                            <textarea class="form-control" id="iMessage" name="iMessage"
                                      rows="3"></textarea>
                        </div>
                        <input type="hidden" id="i_pId" name="i_pId" value="<?php echo $idProject; ?>">
                        <input type="hidden" id="i_pN" name="i_pN" value="">
                        <div class="modal-footer">
                            <button class="btn btn-default" data-dismiss="modal">Zavrieť</button>
                            <button class="btn" type="submit" name="invitation" id="i_Button">
                                <span class="memberSpinner spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                <span>Poslať pozvánku</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <?php

    /**
     *
     * SEARCH PROJECT MODAL
     *
     */

    ?>


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

    <?php

    /**
     *
     * TIMERS PROJECT MODAL
     *
     */

    ?>


    <div class="modal fade animated bounceInDown" id="timersModal" tabindex="-1" role="dialog"
         aria-labelledby="Zoznam časovačov" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="timersModalTitle">Pracovný výkaz</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="timerListContent" class="table-responsive">


                    </div>
                </div>
            </div>
        </div>
    </div>


<div class="modal fade animated bounceInDown" id="showMembersModal" tabindex="-1" role="dialog"
     aria-labelledby="Zoznam členov" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Členovia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="btn-group-sm d-flex justify-content-end my-3" role="group" aria-label="Basic example">
                    <button type="button" id="m_allButton" class="btn btn-secondary ml-2">Všetci</button>
<!--                    <button type="button" id="m_notAssignedButton" class="btn btn-secondary ml-2">Nepriradení</button>-->
                    <button type="button" id="m_invitedButton" class="btn btn-secondary ml-2">Pozvaní</button>
                </div>
                <div id="membersContent" class="table-responsive">


                </div>
            </div>
        </div>
    </div>
</div>




    <?php

    /**
     *
     * stop timer modal
     *
     */

    ?>


    <div class="modal fade animated bounceInDown" id="stopTimerModal" tabindex="-1" role="dialog"
         aria-labelledby="Uložiť časovač" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="">Časovače</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form" method="post" action="" id="t_Form" autocomplete="off">
                        <div class="form-group putError">
                            <textarea class="form-control" id="timerNote" name="timerNote" rows="3" placeholder="Napíšte poznámku"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-default" data-dismiss="modal">Zavrieť</button>
                            <button id="stopTimerFormButton" class="btn" type="submit">Uložiť</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <?php

    /**
     *
     * add atachement modal
     *
     */

    ?>


    <div class="modal fade animated bounceInDown" id="addAttachmentModal" tabindex="-1" role="dialog"
         aria-labelledby="Pridať prílohu" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="">Pridať Prílohu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form" method="post" action="" id="_attachForm" enctype="multipart/form-data">
                        <div class="form-group putError">
                            <div class="custom-file mt-2">
                                <input id="attach" name="attach" type="file" class="custom-file-input">
                                <label class="custom-file-label" for="attachment">
                                    <span class="d-inline-block text-truncate w-75">Zvoľte prílohu</span>
                                </label>
                            </div>
                        </div>
                        <input type="hidden" name="taskId" id="taskId" value="">
                        <input type="hidden" name="projectId" id="projectId" value="<?php echo $idProject; ?>">
                        <input type="hidden" name="attachDir" id="attachDir" value="">
                        <div class="modal-footer">
                            <button class="btn btn-default" data-dismiss="modal">Zavrieť</button>
                            <button class="btn" type="submit" id="">
                                <span class="uploadSpinner spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                <span>Nahrať</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <?php

    /**
     *
     * add column modal
     *
     */

    ?>


    <div class="modal fade animated bounceInDown" id="addColumnModal" tabindex="-1" role="dialog"
         aria-labelledby="Pridať stĺpec" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addColumnModalTitle">Pridať Stĺpec</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form role="form" method="post" action="" id="_column" autocomplete="off">
                    <div class="modal-body">
                        <div class="form-group putError">
                            <label for="columnName"><span class="text-uppercase formLabel required">Názov Stĺpca</span></label>
                            <input type="text" class="form-control" id="columnName" name="columnName"
                                   aria-describedby="názov stĺpca" value="">
                        </div>
                        <div class="form-group putError">
                            <label for="columnLimit"><span class="text-uppercase formLabel">Limit (Work In Progress)</span></label>
                            <input type="number" min="1" class="form-control" id="columnLimit" name="columnLimit" aria-describedby="limit stĺpca" value="">
                        </div>
                        <div class="putError">
                            <label for="columnColor"><span class="text-uppercase formLabel required">Farba Stĺpca</span></label>
                            <div class="d-flex justify-content-between">
                                <label class="colorBox">
                                    <input type="radio" value="purple" name="color" checked="checked">
                                    <span class="checkmark bg-purple"></span>
                                </label>
                                <label class="colorBox">
                                    <input type="radio" value="blue" name="color">
                                    <span class="checkmark bg-blue"></span>
                                </label>
                                <label class="colorBox">
                                    <input type="radio" value="teal" name="color">
                                    <span class="checkmark bg-teal"></span>
                                </label>
                                <label class="colorBox">
                                    <input type="radio" value="cyan" name="color">
                                    <span class="checkmark bg-cyan"></span>
                                </label>
                                <label class="colorBox">
                                    <input type="radio" value="dark-green" name="color">
                                    <span class="checkmark bg-dark-green"></span>
                                </label>
                                <label class="colorBox">
                                    <input type="radio" value="light-green" name="color">
                                    <span class="checkmark bg-light-green"></span>
                                </label>
                                <label class="colorBox">
                                    <input type="radio" value="yellow" name="color">
                                    <span class="checkmark bg-yellow"></span>
                                </label>
                                <label class="colorBox">
                                    <input type="radio" value="orange" name="color">
                                    <span class="checkmark bg-orange"></span>
                                </label>
                                <label class="colorBox">
                                    <input type="radio" value="dark-orange" name="color">
                                    <span class="checkmark bg-dark-orange"></span>
                                </label>
                                <label class="colorBox">
                                    <input type="radio" value="deep-red" name="color">
                                    <span class="checkmark bg-deep-red"></span>
                                </label>
                            </div>
                            <input type="hidden" name="c_Id" id="c_Id" value="">
                            <input type="hidden" name="p_Id" id="p_Id" value="<?php echo $idProject; ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" data-dismiss="modal">Zavrieť</button>
                        <button class="btn" type="submit" name="column" id="createColumnButton">Pridať</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="defaultModalTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="defaultModalContentAjax">

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

<div class="modal fade animated bounceInDown" id="deleteTaskModal" tabindex="-1" role="dialog"
     aria-labelledby="Vymazať projekt" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Vymazať?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form role="form" method="post" id="_deleteTask">
                <div class="modal-body">
                    Ste si istý, že chcete vymazať túto úlohu? Táto zmena je nevratná.
                    <input type="hidden" id="projectId" name="projectId" value="<?php echo $idProject; ?>">
                    <input type="hidden" id="idDelete" name="idDelete" value="">
                </div>
                <div class="modal-footer">
                    <button class="btn" type="button" data-dismiss="modal">Zavrieť</button>
                    <button id="deleteTaskButton" class="btn deleteLink" type="submit"><i class="far fa-trash-alt"></i> Vymazať</button>
                </div>
            </form>
        </div>
    </div>
</div>













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




<?php

/**
 *
 * CREATE NEW TASK MODAL
 *
 */

?>

<div class="modal fade animated bounceInDown" id="newTaskModal" tabindex="-1" role="dialog"
     aria-labelledby="Vytvoriť nový projekt" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newTaskModalTitle">Nová úloha</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" accept-charset="utf-8" class="" id="_task">
                    <!--                                    <div style="display:none">-->
                    <!--                                        <input type="hidden" name="fcs_csrf_token" value="a37d2b04de493957dccc697900e70e6a">-->
                    <!--                                    </div>-->

                    <div class="form-group putError">
                        <label for="taskName"><span
                                    class="text-uppercase formLabel required">Názov Úlohy</span></label>
                        <input type="text" class="form-control" id="taskName" name="taskName"
                               aria-describedby="názov-úlohy" value="">
                    </div>
                    <div class="form-group putError">
                        <label for="taskDescription"><span
                                    class="text-uppercase formLabel required">Popis Úlohy</span></label>
                        <small id="descriptionHelp" class="form-text text-muted text-right">max. 400 znakov</small>
                        <textarea class="form-control" id="taskDescription" name="taskDescription"
                                  rows="3"></textarea>
                    </div>
                    <div class="form-group putError">

                        <div class="d-flex justify-content-between">
                            <label for="taskPriority"><span class="text-uppercase formLabel required">Priorita</span></label>
                            <span id="taskPriorityCurrent">Priorita: <span id="currentPriority">1</span></span>
                        </div>
                        <input
                                type="text"
                                id="taskPriority"
                                name="taskPriority"
                                data-provide="slider"
                                data-slider-min="1"
                                data-slider-max="10"
                                data-slider-step="1"
                                data-slider-value="1"
                                data-slider-tooltip="show"
                        >
                    </div>
                    <div class="form-group putError">
                        <label for="taskDueDate"><span class="text-uppercase formLabel required">Plánované Ukončenie</span></label>
                        <input class="form-control datepickerStart" type="text" value="" id="taskDueDate" name="taskDueDate" aria-describedby="plánované-ukončenie-úlohy">
                    </div>
<!--                    <div class="form-group putError">-->
<!--                        <label for="taskMembers"><span class="text-uppercase formLabel">Priradení členovia</span></label>-->
<!--                        <select id="taskMembers" name="taskMembers[]" class="form-control" aria-describedby="členovia-projektu" multiple>-->
<!--                            <option value="uid1">email@email.com</option>-->
<!--                            <option value="uid2">email2@email.com</option>-->
<!--                            <option value="uid3">email3@email.com</option>-->
<!--                        </select>-->
<!--                    </div>-->
                    <input type="hidden" id="projectId" name="projectId" value="<?php echo $idProject;?>">
                    <input type="hidden" id="idTask" name="idTask">

                    <div class="modal-footer">
                        <button class="btn btn-default" data-dismiss="modal">Zavrieť</button>
                        <button id="createTaskButton" class="btn" name="insert" type="submit">Vytvoriť Úlohu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</main>

<div id='ajax_loader' style="position: fixed; left: 50%; top: 50%; display: none; z-index: 5000;">
    <img src="assets/img/icons/ajax-loader.gif">
</div>


<script src="assets/js/vendors.min.js"></script>
<script src="assets/js/app.min.js"></script>
<script src="assets/js/task-board.min.js"></script>


</body>
</html>