<!--               cards-->
<div class="row cardBox">
    <div class=" col-md-6 col-lg-6 col-xl-3 cardColumn">
        <div class="card p-3">
            <div class="d-flex align-items-center">
                <button id="btnAllProjects" class="btn-circle btn-warning text-white projectCardIcon p-0">
                    <i class="fas fa-tasks"></i>
                </button>
                <div class="ml-3 text-uppercase middle">
                    <h6>všetky projekty</h6>
                    <!--                    <div class="progress" style="height: 4px;">-->
                    <!--                        <div class="progress-bar bg-warning" role="progressbar" style="width: 40%"-->
                    <!--                             aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>-->
                    <!--                    </div>-->
                </div>
                <div class="ml-auto">
                    <h4 class="mb-0"><?php echo $countAllProjects ?></h4>
                </div>
            </div>
        </div>
    </div>
    <div class=" col-md-6 col-lg-6 col-xl-3 cardColumn">
        <div class="card p-3">
            <div class="d-flex align-items-center">
                <button id="btnMyProjects" class="btn-circle btn-secondary text-white projectCardIcon p-0">
                    <i class="far fa-lightbulb"></i>
                </button>
                <div class="ml-3 text-uppercase middle">
                    <h6>moje projekty</h6>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-secondary" role="progressbar" style="width: <?php echo $pctMy ?>%"
                             aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="ml-auto">
                    <h4 class="mb-0"><?php echo $countMyProjects ?></h4>
                </div>
            </div>
        </div>
    </div>
    <div class=" col-md-6 col-lg-6 col-xl-3 cardColumn">
        <div class="card p-3">
            <div class="d-flex align-items-center">
                <button id="btnActiveProjects" class="btn-circle btn-danger text-white projectCardIcon p-0">
                    <i class="fas fa-chart-line"></i>
                </button>
                <div class="ml-3 text-uppercase middle">
                    <h6>aktívne projekty</h6>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo $pctActive ?>%"
                             aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="ml-auto">
                    <h4 class="mb-0"><?php echo $countInProgressProjects ?></h4>
                </div>
            </div>
        </div>
    </div>
    <div class=" col-md-6 col-lg-6 col-xl-3 cardColumn">
        <div class="card p-3">
            <div class="d-flex align-items-center">
                <button id="btnDoneProjects" class="btn-circle btn-success text-white projectCardIcon p-0">
                    <i class="fas fa-clipboard-check"></i>
                </button>
                <div class="ml-3 text-uppercase middle">
                    <h6>hotové projekty</h6>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $pctFinished ?>%"
                             aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="ml-auto">
                    <h4 class="mb-0"><?php echo $countFinishedProjects ?></h4>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="" class="row">
    <div id="panelBox" class="col mb-4">
        <div class="panel">
            <div id="projectContentBox" class="p-4">

                <?php require_once ("projectBoardList.php"); ?>

            </div>
        </div>
    </div>
</div>




