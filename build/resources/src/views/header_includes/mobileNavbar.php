<ul class="list-unstyled pt-4" data-simplebar>

    <?php
    switch ($active){

        case "dashboard":
            ?>
            <li class="sideMenuItem">
                <div>
                    <a href="dashboard" class="active">Nástenka</a>
                </div>
            </li>
            <li class="sideMenuItem">
                <div>
                    <a href="project-board">Projekty</a>
                </div>
            </li>
            <li class="sideMenuItem">
                <div>
                    <a href="#" data-toggle="modal" data-target="#profileModal" class="profileButton">Môj Profil</a>
                </div>
            </li>
            <li class="sideMenuItem">
<!--                <div>-->
<!--                    <a id="mobileTimersLink" href="javascript:void(0)" role="button" data-toggle="modal"-->
<!--                       data-target="#timerModal"><i class="fas fa-stopwatch"></i>Running Timers</a>-->
<!--                </div>-->
            </li>
            <li class="sideMenuItem">
                <div>
                    <a href="javascript: void(0)" class="buttonLogout">Odhlásiť sa</a>
                </div>
            </li>
            <?php
            break;

        case "projectboard":
            ?>
            <li class="sideMenuItem">
                <div>
                    <a href="dashboard">Nástenka</a>
                </div>
            </li>
            <li class="sideMenuItem">
                <div>
                    <a href="project-board" class="active">Projekty</a>
                </div>
            </li>
            <li class="sideMenuItem">
                <div>
                    <a href="#" data-toggle="modal" data-target="#profileModal" class="profileButton">Môj Profil</a>
                </div>
            </li>
            <li class="sideMenuItem">
<!--                <div>-->
<!--                    <a id="mobileTimersLink" href="javascript:void(0)" role="button" data-toggle="modal"-->
<!--                       data-target="#timerModal"><i class="fas fa-stopwatch"></i>Running Timers</a>-->
<!--                </div>-->
            </li>
            <li class="sideMenuItem">
                <div>
                    <a href="javascript: void(0)" class="buttonLogout">Odhlásiť sa</a>
                </div>
            </li>
            <?php
            break;

        case "profile":
            ?>
            <li class="sideMenuItem">
                <div>
                    <a href="dashboard">Nástenka</a>
                </div>
            </li>
            <li class="sideMenuItem">
                <div>
                    <a href="project-board">Projekty</a>
                </div>
            </li>
            <li class="sideMenuItem">
                <div>
                    <a href="#" data-toggle="modal" data-target="#profileModal" class="profileButton active">Môj Profil</a>
                </div>
            </li>
            <li class="sideMenuItem">
<!--                <div>-->
<!--                    <a id="mobileTimersLink" href="javascript:void(0)" role="button" data-toggle="modal"-->
<!--                       data-target="#timerModal"><i class="fas fa-stopwatch"></i>Running Timers</a>-->
<!--                </div>-->
            </li>
            <li class="sideMenuItem">
                <div>
                    <a href="javascript: void(0)" class="buttonLogout">Odhlásiť sa</a>
                </div>
            </li>
            <?php
            break;


        default:
            ?>
            <li class="sideMenuItem">
                <div>
                    <a href="dashboard">Nástenka</a>
                </div>
            </li>
            <li class="sideMenuItem">
                <div>
                    <a href="project-board">Projekty</a>
                </div>
            </li>
            <li class="sideMenuItem">
                <div>
                    <a href="#" data-toggle="modal" data-target="#profileModal" class="profileButton">Môj Profil</a>
                </div>
            </li>
            <li class="sideMenuItem">
<!--                <div>-->
<!--                    <a id="mobileTimersLink" href="javascript:void(0)" role="button" data-toggle="modal"-->
<!--                       data-target="#timerModal"><i class="fas fa-stopwatch"></i>Running Timers</a>-->
<!--                </div>-->
            </li>
            <li class="sideMenuItem">
                <div>
                    <a href="javascript: void(0)" class="buttonLogout">Odhlásiť sa</a>
                </div>
            </li>
        <?php

    }
    ?>
</ul>