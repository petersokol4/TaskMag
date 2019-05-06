<?php require_once (__DIR__ . "/header_includes/head.php"); ?>

<body id="<?php echo ($active == "dashboard" ?  "dashboardBg" : "defaultBody") ?>">


<header>
    <nav class="">
        <div class="container-fluid">
            <?php /* MAIN NAVBAR */ ?>
            <div class="mainNavbar mainNavbarDashboard d-none d-md-block fixed-top">
                <div class="row navbarBox">
                    <div class="navbarLogo p-2">Task<strong>Mag</strong></div>
                    <ul class="list-unstyled list-group list-group-horizontal ml-md-auto">
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link p-2 notificationLink" href="javascript:void(0)" role="button"-->
<!--                               data-toggle="modal" data-target="#notificationModal"><span><i-->
<!--                                        class="fas fa-bell"></i></span><span-->
<!--                                    class="badge badge-warning up">10</span></a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link p-2" href="javascript:void(0)" role="button" data-toggle="modal"-->
<!--                               data-target="#timerModal"><i class="fas fa-stopwatch"></i></a>-->
<!--                        </li>-->
                        <li class="nav-item dropdown">
                            <a href="javascript:void(0)" class="nav-item nav-link p-2 dropdown-toggle" id="profileDropdown"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span><img src="" class="rounded-circle profile avatarImg uAvatarAjax"
                                        alt="" width="30" height="30"></span><span class="ml-2" id="uNameAjax"></span></a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profile Dropdown">
<!--                                <a class="dropdown-item" href="javascript:void(0)">Môj profil</a>-->
<!--                                <a class="dropdown-item" href="javascript:void(0)">Nastavenia</a>-->
<!--                                <div class="dropdown-divider"></div>-->
                                <a class="dropdown-item buttonLogout" href="javascript:void(0)">Odhlásiť sa</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <?php /* MOBILE NAVBAR */ ?>
            <div class="mobileNavbar d-md-none fixed-top">
                <div class="row navbarBox">
                    <div class="col-3 text-left nav-item p-2">
                        <a href="javascript:void(0)" class="" id="openSideMenu"><img src="" class="rounded-circle profile avatarImg uAvatarAjax" alt="" width="30"
                                                                    height="30"></a>
                    </div>
                    <div class="col-6 text-center nav-item p-2">
                        <span class="navbarLogo">Task<strong>Mag</strong></span>
                    </div>
<!--                    <div class="col-3 text-right nav-item p-2">-->
<!--                        <a class="notificationLink" href="javascript:void(0)" role="button" data-toggle="modal"-->
<!--                           data-target="#notificationModal"><span><i class="fas fa-bell"></i></span><span-->
<!--                                class="badge badge-warning up">10</span></a>-->
<!--                    </div>-->
                </div>
            </div>
            <?php /* MOBILE SIDEMENU */ ?>
            <div id="mobileSideMenuBg" class="d-md-none"></div>
            <div id="mobileSideMenu" class="d-md-none">
                <div class="container-fluid">
                    <div class="row navbarBox">
                        <div class="col-6">
                            <div class="p-2">
                                <a href="javascript:void(0)" class="" id="closeSideMenu"><img src="" class="rounded-circle profile avatarImg uAvatarAjax" alt="" width="30" height="30"></a>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2">
                                <span class="navbarLogo">Task<strong>Mag</strong></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="" style="min-height: 100vh;">
                    <div class="">
                        <?php require_once (__DIR__ . "/header_includes/mobileNavbar.php"); ?>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
<main>
    <section>
        <aside id="mainSidebar" class="d-none d-md-block">
            <div class="sidebarBox">
                <div class="userProfileBox">
                    <div class=" d-flex justify-content-center">
                        <img src="" class="mb-4 mt-5 ml-5 mr-5 rounded-circle avatarImg uAvatarAjax" alt=""
                             width="100" height="100">
                    </div>
                    <div class="sidebarEmail text-center pb-2">
                        <span class="userEmail"></span>
                    </div>
                    <hr>
                </div>
                <nav id="sidebarNav">
                    <?php require_once (__DIR__ . "/header_includes/desktopSidebar.php"); ?>
                </nav>
            </div>
        </aside>