<?php
session_start();

//todo session

if(isset($_SESSION['user'])){
    header("Location: dashboard");
    die();
}

//$errors=array();
//if(isset($_SESSION['errors'])){
//
//    $errors =  $_SESSION['errors'];
//    unset($_SESSION["errors"]);
//}

$title = "TaskMag - Registrácia";
require_once (__DIR__."/../resources/src/views/header_includes/head.php");
?>

<body>
<div id="mainWrap">
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-lg-8 text-center d-none d-md-block">
                <p class="jumbo">Vitajte!</p>
                <div class="svg-image mx-auto d-block">
                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         viewBox="0 0 787.7 954.8" style="enable-background:new 0 0 787.7 954.8;" xml:space="preserve">
                        <style type="text/css">
                            .st0{fill:#3EA7DB;stroke:#3EA7DB;stroke-width:2;stroke-miterlimit:10;}
                        </style>
                        <g id="rocket">
                            <g id="stars">
                                <path class="st0" d="M738.8,412.6c-0.3,0-0.5-0.1-0.7-0.3c-0.4-0.4-0.4-1,0-1.4c0,0,0,0,0,0l13.5-13.5c0.4-0.4,1-0.5,1.4-0.1
            c0.4,0.4,0.5,1,0.1,1.4c0,0-0.1,0.1-0.1,0.1l-13.5,13.5C739.3,412.5,739.1,412.6,738.8,412.6z"/>
                                <path class="st0" d="M752.3,412.6c-0.3,0-0.5-0.1-0.7-0.3l-13.5-13.5c-0.4-0.4-0.4-1,0-1.4c0.4-0.4,1-0.4,1.4,0c0,0,0,0,0,0
            l13.5,13.5c0.4,0.4,0.4,1,0,1.4c0,0,0,0,0,0C752.9,412.5,752.6,412.6,752.3,412.6z"/>
                                <path class="st0" d="M456.6,283.5c-0.6,0-1-0.4-1-1c0-0.3,0.1-0.5,0.3-0.7l13.5-13.5c0.4-0.4,1-0.5,1.4-0.1s0.5,1,0.1,1.4
            c0,0-0.1,0.1-0.1,0.1l-13.5,13.5C457.1,283.4,456.8,283.5,456.6,283.5z"/>
                                <path class="st0" d="M470.1,283.5c-0.3,0-0.5-0.1-0.7-0.3l-13.5-13.5c-0.4-0.4-0.4-1,0-1.4c0.4-0.4,1-0.4,1.4,0c0,0,0,0,0,0
            l13.5,13.5c0.4,0.4,0.4,1,0,1.4C470.6,283.4,470.3,283.5,470.1,283.5z"/>
                                <path class="st0" d="M22.5,456.1c-0.3,0-0.5-0.1-0.7-0.3c-0.4-0.4-0.4-1,0-1.4l13.5-13.5c0.4-0.4,1-0.5,1.4-0.1
            c0.4,0.4,0.5,1,0.1,1.4c0,0-0.1,0.1-0.1,0.1l-13.5,13.5C23,456,22.7,456.1,22.5,456.1z"/>
                                <path class="st0" d="M36,456.1c-0.3,0-0.5-0.1-0.7-0.3l-13.5-13.5c-0.4-0.4-0.4-1,0-1.4c0.4-0.4,1-0.4,1.4,0c0,0,0,0,0,0
            l13.5,13.5c0.4,0.4,0.4,1,0,1.4C36.5,456,36.3,456.1,36,456.1z"/>
                                <path class="st0" d="M178.4,140.4c-0.6,0-1-0.4-1-1c0-0.3,0.1-0.5,0.3-0.7l13.5-13.5c0.4-0.4,1-0.4,1.4,0c0,0,0,0,0,0
            c0.4,0.4,0.4,1,0,1.4l-13.5,13.5C178.9,140.3,178.6,140.4,178.4,140.4z"/>
                                <path class="st0" d="M191.9,140.4c-0.3,0-0.5-0.1-0.7-0.3l-13.5-13.5c-0.4-0.4-0.3-1.1,0.1-1.4c0.4-0.3,0.9-0.3,1.3,0l13.5,13.5
            c0.4,0.4,0.4,1,0,1.4c0,0,0,0,0,0C192.4,140.3,192.2,140.4,191.9,140.4z"/>
                                <path class="st0" d="M513.4,184.1c-0.6,0-1-0.4-1-1c0-0.3,0.1-0.5,0.3-0.7l13.5-13.5c0.4-0.4,1-0.4,1.4,0c0.4,0.4,0.4,1,0,1.4
            l-13.5,13.5C514,184,513.7,184.1,513.4,184.1z"/>
                                <path class="st0" d="M527,184.1c-0.3,0-0.5-0.1-0.7-0.3l-13.5-13.5c-0.4-0.4-0.4-1,0-1.4c0.4-0.4,1-0.4,1.4,0l13.5,13.5
            c0.4,0.4,0.4,1,0,1.4C527.5,184,527.3,184.2,527,184.1L527,184.1z"/>
                                <path class="st0" d="M309.5,209.4c-0.3,0-0.5-0.1-0.7-0.3c-0.4-0.4-0.4-1,0-1.4c0,0,0,0,0,0l13.5-13.5c0.4-0.4,1-0.5,1.4-0.1
            s0.5,1,0.1,1.4c0,0-0.1,0.1-0.1,0.1l-13.5,13.5C310,209.3,309.7,209.4,309.5,209.4z"/>
                                <path class="st0" d="M323,209.4c-0.3,0-0.5-0.1-0.7-0.3l-13.5-13.5c-0.4-0.4-0.4-1,0-1.4c0.4-0.4,1-0.4,1.4,0c0,0,0,0,0,0
            l13.5,13.5c0.4,0.4,0.4,1,0,1.4c0,0,0,0,0,0C323.5,209.3,323.3,209.4,323,209.4z"/>
                                <path class="st0" d="M476.8,15.5c-0.3,0-0.5-0.1-0.7-0.3c-0.4-0.4-0.4-1,0-1.4l13.5-13.5c0.4-0.4,1-0.4,1.4,0
            c0.4,0.4,0.4,1,0,1.4l0,0l-13.5,13.5C477.3,15.4,477,15.5,476.8,15.5z"/>
                                <path class="st0" d="M490.3,15.5c-0.3,0-0.5-0.1-0.7-0.3L476.1,1.7c-0.4-0.4-0.4-1,0-1.4s1-0.4,1.4,0c0,0,0,0,0,0L491,13.8
            c0.4,0.4,0.4,1,0,1.4C490.8,15.4,490.6,15.5,490.3,15.5z"/>
                                <path class="st0" d="M221.6,32.6c-0.3,0-0.5-0.1-0.7-0.3c-0.4-0.4-0.4-1,0-1.4l13.5-13.5c0.4-0.4,1-0.4,1.4,0
            c0.4,0.4,0.4,1,0,1.4l0,0l-13.5,13.5C222.1,32.5,221.8,32.6,221.6,32.6z"/>
                                <path class="st0" d="M235.1,32.6c-0.3,0-0.5-0.1-0.7-0.3l-13.5-13.5c-0.4-0.4-0.4-1,0-1.4c0,0,0,0,0,0c0.4-0.4,1-0.4,1.4,0
            l13.5,13.5c0.4,0.4,0.4,1,0,1.4C235.6,32.5,235.3,32.6,235.1,32.6L235.1,32.6z"/>
                                <path class="st0" d="M1,22.3c-0.3,0-0.5-0.1-0.7-0.3c-0.4-0.4-0.4-1,0-1.4L13.8,7.1c0.4-0.4,1-0.4,1.4,0c0.4,0.4,0.4,1,0,1.4
            c0,0,0,0,0,0L1.7,22C1.5,22.2,1.3,22.3,1,22.3z"/>
                                <path class="st0" d="M14.5,22.3c-0.3,0-0.5-0.1-0.7-0.3L0.3,8.5c-0.4-0.4-0.4-1,0-1.4s1-0.4,1.4,0l13.5,13.5
            c0.4,0.4,0.4,1,0,1.4C15,22.2,14.8,22.3,14.5,22.3z"/>
                                <path class="st0" d="M158.1,318.1c-0.3,0-0.5-0.1-0.7-0.3c-0.4-0.4-0.4-1,0-1.4l13.5-13.5c0.4-0.4,1-0.4,1.4,0
            c0.4,0.4,0.4,1,0,1.4c0,0,0,0,0,0l-13.5,13.5C158.6,318,158.4,318.1,158.1,318.1z"/>
                                <path class="st0" d="M171.6,318.1c-0.3,0-0.5-0.1-0.7-0.3l-13.5-13.5c-0.4-0.4-0.4-1,0-1.4c0.4-0.4,1-0.4,1.4,0l13.5,13.5
            c0.4,0.4,0.4,1,0,1.4C172.1,318,171.9,318.1,171.6,318.1z"/>
                                <path class="st0" d="M36,216.2c-0.6,0-1-0.5-1-1c0-0.3,0.1-0.5,0.3-0.7l13.5-13.5c0.4-0.4,1-0.4,1.4,0c0.4,0.4,0.4,1,0,1.4
            l-13.5,13.5C36.5,216.1,36.3,216.2,36,216.2z"/>
                                <path class="st0" d="M49.5,216.2c-0.3,0-0.5-0.1-0.7-0.3l-13.5-13.5c-0.4-0.4-0.4-1,0-1.4c0,0,0,0,0,0c0.4-0.4,1-0.4,1.4,0
            l13.5,13.5c0.4,0.4,0.4,1,0,1.4C50,216.1,49.8,216.2,49.5,216.2z"/>
                                <path class="st0" d="M652.5,260.8c-0.6,0-1-0.4-1-1c0-0.3,0.1-0.5,0.3-0.7l13.5-13.5c0.4-0.4,1.1-0.3,1.4,0.1
            c0.3,0.4,0.3,0.9,0,1.3l-13.5,13.5C653,260.7,652.7,260.8,652.5,260.8z"/>
                                <path class="st0" d="M666,260.8c-0.3,0-0.5-0.1-0.7-0.3l-13.5-13.5c-0.4-0.4-0.4-1,0-1.4c0.4-0.4,1-0.4,1.4,0c0,0,0,0,0,0
            l13.5,13.5c0.4,0.4,0.4,1,0,1.4C666.5,260.7,666.3,260.8,666,260.8L666,260.8z"/>
                                <path class="st0" d="M773.1,22.3c-0.3,0-0.5-0.1-0.7-0.3c-0.4-0.4-0.4-1,0-1.4L786,7.1c0.4-0.4,1-0.4,1.4,0c0.4,0.4,0.4,1,0,1.4
            L773.8,22C773.7,22.2,773.4,22.3,773.1,22.3z"/>
                                <path class="st0" d="M786.7,22.3c-0.2,0-0.5-0.1-0.7-0.3L772.4,8.5c-0.4-0.4-0.4-1,0-1.4c0,0,0,0,0,0c0.4-0.4,1-0.4,1.4,0
            l13.5,13.5c0.4,0.4,0.4,1,0,1.4C787.2,22.2,786.9,22.3,786.7,22.3z"/>
                                <path class="st0" d="M626.2,75c-0.3,0-0.5-0.1-0.7-0.3c-0.4-0.4-0.4-1,0-1.4L639,59.8c0.4-0.4,1-0.4,1.4,0c0.4,0.4,0.4,1,0,1.4
            l0,0l-13.5,13.5C626.7,74.9,626.4,75,626.2,75z"/>
                                <path class="st0" d="M639.7,75c-0.3,0-0.5-0.1-0.7-0.3l-13.5-13.5c-0.4-0.4-0.4-1,0-1.4c0,0,0,0,0,0c0.4-0.4,1-0.4,1.4,0
            l13.5,13.5c0.4,0.4,0.4,1,0,1.4C640.2,74.9,640,75,639.7,75z"/>
                                <path class="st0" d="M772,157.1c-0.3,0-0.5-0.1-0.7-0.3c-0.4-0.4-0.4-1,0-1.4c0,0,0,0,0,0l13.5-13.5c0.4-0.4,1-0.4,1.4,0
            c0,0,0,0,0,0c0.4,0.4,0.4,1,0,1.4l-13.5,13.5C772.5,157,772.3,157.1,772,157.1z"/>
                                <path class="st0" d="M785.5,157.1c-0.3,0-0.5-0.1-0.7-0.3l-13.5-13.5c-0.4-0.4-0.4-1,0-1.4c0.4-0.4,1-0.4,1.4,0c0,0,0,0,0,0
            l13.5,13.5c0.4,0.4,0.4,1,0,1.4c0,0,0,0,0,0C786.1,157,785.8,157.1,785.5,157.1z"/>
                                <path class="st0" d="M371.5,94.7c-0.3,0-0.5-0.1-0.7-0.3c-0.4-0.4-0.4-1,0-1.4l13.5-13.5c0.4-0.4,1-0.4,1.4,0s0.4,1,0,1.4l0,0
            l-13.5,13.5C372,94.5,371.7,94.7,371.5,94.7z"/>
                                <path class="st0" d="M385,94.7c-0.3,0-0.5-0.1-0.7-0.3l-13.5-13.5c-0.4-0.4-0.4-1,0-1.4s1-0.4,1.4,0l0,0L385.7,93
            c0.4,0.4,0.4,1,0,1.4C385.5,94.6,385.3,94.7,385,94.7z"/>
                            </g>
                            <g id="stands">
                                <path class="st0" d="M738.8,920H52.1c-0.6,0-1-0.4-1-1s0.4-1,1-1h686.7c0.6,0,1,0.4,1,1S739.4,920,738.8,920z"/>
                                <path class="st0" d="M647.5,954.8H143.4c-0.6,0-1-0.4-1-1s0.4-1,1-1h504.1c0.6,0,1,0.4,1,1S648.1,954.8,647.5,954.8z"/>
                            </g>
                            <g id="rocket_compo">
                                <g id="rocket-2">
                                    <path class="st0" d="M395,391.8c63.7,62.7,75.4,161.1,28.2,237c-17.9-8.4-38.6-8.5-56.6-0.4C319.6,552.6,331.4,454.3,395,391.8
                 M395,389c-65.9,63.5-78,164.6-29.2,241.9c18.4-8.9,39.9-8.7,58.2,0.4C473.1,553.9,461,452.6,395,389L395,389z"/>
                                    <path class="st0" d="M333.6,578.3c5.2,19.4,13.3,37.8,24.1,54.7c-20.9,12.7-37.7,35.8-47.1,64.6
                C301.5,635.2,325.6,591,333.6,578.3 M334.4,573.5c-1.8,2.4-39.1,54.3-24.4,132.4l0.4-0.6c8.9-32.5,27.1-58.5,50.1-71.7
                C348.4,615.2,339.6,594.9,334.4,573.5L334.4,573.5z"/>
                                    <path class="st0" d="M456.5,578.3c8,12.7,32.1,56.8,22.9,119.3c-9.4-28.8-26.2-52-47.1-64.6
                C443.2,616.1,451.4,597.7,456.5,578.3 M455.7,573.5c-5.2,21.4-14,41.8-26.1,60.2c23,13.1,41.2,39.2,50.1,71.7l0.4,0.6
                C494.8,627.8,457.5,575.9,455.7,573.5z"/>
                                    <path class="st0" d="M420.5,639h-51.9c-0.6,0-1-0.4-1-1s0.4-1,1-1h51.9c0.6,0,1,0.4,1,1S421,639,420.5,639z"/>
                                    <path class="st0" d="M413.6,648.8h-38.1c-0.6,0-1-0.4-1-1s0.4-1,1-1h38.1c0.6,0,1,0.4,1,1S414.1,648.8,413.6,648.8L413.6,648.8
                z"/>
                                    <g id="window">
                                        <path class="st0" d="M395,545.6c-15.9,0-28.8-12.9-28.8-28.8s12.9-28.8,28.8-28.8s28.8,12.9,28.8,28.8
                    C423.7,532.8,410.9,545.6,395,545.6z M395,489.1c-15.3,0-27.8,12.4-27.8,27.8c0,15.3,12.4,27.8,27.8,27.8s27.8-12.4,27.8-27.8
                    l0,0C422.7,501.6,410.3,489.2,395,489.1z"/>
                                        <path class="st0" d="M395,552.4c-19.6,0-35.5-15.9-35.5-35.5c0-19.6,15.9-35.5,35.5-35.5s35.5,15.9,35.5,35.5
                    C430.5,536.5,414.6,552.4,395,552.4z M395,482.4c-19.1,0-34.5,15.4-34.5,34.5s15.4,34.5,34.5,34.5s34.5-15.4,34.5-34.5
                    C429.5,497.8,414,482.4,395,482.4z"/>
                                        <path class="st0" d="M385,509.4c-3,0-5.5-2.5-5.5-5.5s2.5-5.5,5.5-5.5s5.5,2.5,5.5,5.5S388,509.4,385,509.4L385,509.4z
                     M385,499.4c-2.5,0-4.5,2-4.5,4.5s2,4.5,4.5,4.5s4.5-2,4.5-4.5C389.5,501.4,387.5,499.4,385,499.4z"/>
                                    </g>
                                </g>
                                <g id="fire">
                                    <path class="st0" d="M395,728.5c-0.6,0-1-0.4-1-1v-69c0-0.6,0.4-1,1-1s1,0.4,1,1v69C396,728.1,395.6,728.5,395,728.5z"/>
                                    <path class="st0" d="M387.8,698.3c-0.6,0-1-0.4-1-1v-28.8c0-0.6,0.4-1,1-1s1,0.4,1,1v28.8C388.8,697.9,388.3,698.3,387.8,698.3
                z"/>
                                    <path class="st0" d="M402.2,707c-0.6,0-1-0.4-1-1v-37.4c0-0.6,0.4-1,1-1s1,0.4,1,1V706C403.2,706.6,402.7,707,402.2,707z"/>
                                </g>
                                <g id="right_clouds">
                                    <path class="st0" d="M52.1,857.4c-0.6,0-1-0.5-1-1c0-0.3,0.1-0.5,0.3-0.7c10.1-10.5,26-14.2,40.7-9.7c1-7.7,4.5-14.9,9.9-20.5
                c14.8-15.4,40.6-16.9,57.5-3.3c2.9,2.4,5.5,5.2,7.6,8.3c6.4-4.3,14-6.3,21.7-5.7c4.3-23.7,27-41.4,53.2-41.4
                c1.6,0,3.2,0.1,4.8,0.2c3-16.8,19-29.4,37.6-29.4c0.9,0,1.9,0,2.9,0.1c4-13,16.8-22,31.5-22c18.7,0,22.8,10,25.7,21.1
                c2.2,8.1,9.9,13.3,18.2,12.4c6.8-0.9,10.9-6,10.9-13.7c0-17.5,0-50,0-50h2c0,0,0,32.5,0,50c0,10.4-6.5,14.9-12.6,15.7
                c-8,1-17.9-3.9-20.4-13.9c-2.7-10.7-6.5-19.6-23.7-19.6c-14.1,0-26.4,8.8-29.9,21.3c-0.1,0.5-0.6,0.8-1,0.7
                c-1.2-0.1-2.4-0.2-3.6-0.2c-18,0-33.4,12.3-35.8,28.6c-0.1,0.5-0.6,0.9-1.1,0.8c-1.9-0.2-3.8-0.3-5.6-0.3
                c-25.6,0-47.7,17.5-51.4,40.6c-0.1,0.5-0.6,0.9-1.1,0.8c-7.8-0.8-15.7,1.3-22.1,5.8c-0.2,0.2-0.5,0.2-0.8,0.2
                c-0.3-0.1-0.5-0.2-0.7-0.5c-2.1-3.3-4.7-6.2-7.8-8.7c-16.1-13-40.6-11.6-54.8,3.2c-5.4,5.5-8.8,12.8-9.5,20.5
                c0,0.3-0.2,0.6-0.5,0.8c-0.3,0.2-0.6,0.2-0.9,0.1c-14.2-4.8-30.2-1.3-39.8,8.8C52.7,857.3,52.4,857.4,52.1,857.4z"/>
                                </g>
                                <g id="left_clouds">
                                    <path class="st0" d="M738.8,857.4c-0.3,0-0.5-0.1-0.7-0.3c-9.7-10.1-25.9-13.7-40.3-8.8c-0.3,0.1-0.6,0.1-0.9-0.1
                c-0.3-0.2-0.4-0.4-0.5-0.8c-0.7-7.7-4.1-14.9-9.5-20.5c-14.2-14.7-38.7-16.1-54.8-3.2c-3,2.5-5.7,5.4-7.8,8.7
                c-0.1,0.2-0.4,0.4-0.7,0.5c-0.3,0.1-0.5,0-0.8-0.2c-6.4-4.6-14.3-6.6-22.1-5.8c-0.5,0-1-0.3-1.1-0.8
                c-3.7-23.2-25.8-40.6-51.4-40.6c-1.8,0-3.7,0.1-5.6,0.3c-0.5,0.1-1-0.3-1.1-0.8c-2.4-16.3-17.8-28.6-35.7-28.6
                c-1.2,0-2.3,0-3.6,0.2c-0.5,0-0.9-0.3-1-0.7c-3.4-12.5-15.7-21.3-29.9-21.3c-17.3,0-21,8.9-23.8,19.6
                c-2.6,10-12.4,14.9-20.4,13.9c-6.1-0.8-12.6-5.3-12.6-15.7c0-17.5,0-50,0-50h2c0,0,0,32.5,0,50c0,7.7,4.1,12.8,10.9,13.7
                c8.3,0.9,16-4.3,18.2-12.4c2.9-11.1,7-21.1,25.7-21.1c14.7,0,27.6,9,31.5,22c1-0.1,2-0.1,2.9-0.1c18.6,0,34.6,12.5,37.6,29.4
                c1.7-0.1,3.3-0.2,4.8-0.2c26.2,0,48.9,17.7,53.2,41.4c7.7-0.6,15.3,1.4,21.7,5.7c2.1-3.1,4.7-5.9,7.7-8.3
                c16.8-13.6,42.6-12.1,57.5,3.3c5.4,5.6,8.9,12.7,9.9,20.5c14.8-4.5,31.2-0.7,41.2,9.7c0.4,0.4,0.4,1,0,1.4
                C739.3,857.3,739.1,857.4,738.8,857.4L738.8,857.4z"/>
                                </g>
                                <g id="moon">
                                    <path class="st0" d="M738.8,857.4c-0.1,0-0.3,0-0.4-0.1c-0.5-0.2-0.7-0.8-0.5-1.3c19.6-46,29.7-95.6,29.6-145.6
                c0-205.1-166.9-372-372-372s-372,166.9-372,372c-0.1,50,10,99.6,29.6,145.6c0.2,0.5,0,1.1-0.5,1.3c-0.5,0.2-1.1,0-1.3-0.5
                C-29.4,666.6,59.4,447,249.7,366.4s409.8,8.2,490.4,198.4c39.3,93.4,39.2,198.7-0.3,292C739.6,857.2,739.2,857.4,738.8,857.4z"
                                    />
                                </g>
                            </g>
                            <g id="aditional_clouds">
                                <path class="st0" d="M522.6,857.4L522.6,857.4c-0.6,0-1-0.5-1-1c0-0.2,0.8-19.4-10.4-31.2c-5.9-6.2-14-9.3-24.2-9.3l0,0
            c-0.8,0-1.7,0.2-2.5,0.4l-0.6,0.2c-0.5,0.1-1-0.2-1.2-0.7c-5.7-16.5-20.5-24.5-45.3-24.5l0,0c-22.6,0-34.2,10.2-39.9,18.8
            c-0.3,0.5-0.9,0.6-1.4,0.3c-0.5-0.3-0.6-0.9-0.3-1.4c6-9,18.1-19.6,41.6-19.6l0,0c25.4,0,40.7,8.2,46.9,25
            c0.9-0.2,1.8-0.4,2.7-0.4l0,0c10.8,0,19.4,3.4,25.7,9.9c11.8,12.4,10.9,31.8,10.9,32.7C523.5,857,523.1,857.4,522.6,857.4z"/>
                                <path class="st0" d="M101,857.4c-0.6,0-1-0.4-1-1c0-18.3,14.9-33.2,33.2-33.2c0.6,0,1,0.4,1,1s-0.4,1-1,1
            c-17.2,0-31.2,14-31.2,31.2C102,856.9,101.6,857.4,101,857.4z"/>
                                <path class="st0" d="M264,805.2c-0.3,0-0.6-0.1-0.8-0.4c-10.6-13.6-30.2-16.1-43.8-5.5c-0.4,0.3-1.1,0.3-1.4-0.2c0,0,0,0,0,0
            c-0.3-0.4-0.3-1.1,0.2-1.4c14.5-11.2,35.4-8.6,46.6,5.8c0.3,0.4,0.3,1.1-0.2,1.4C264.4,805.1,264.2,805.2,264,805.2L264,805.2z"
                                />
                                <path class="st0" d="M522.6,805.2c-0.2,0-0.4-0.1-0.6-0.2c-0.4-0.3-0.5-1-0.2-1.4c0,0,0,0,0,0c11.3-14.5,32.1-17.1,46.6-5.8
            c0.5,0.3,0.6,0.9,0.3,1.4s-0.9,0.6-1.4,0.3c0,0-0.1-0.1-0.1-0.1c-13.6-10.6-33.2-8.1-43.8,5.5
            C523.2,805.1,522.9,805.2,522.6,805.2z"/>
                                <path class="st0" d="M624.2,857.4c-0.6,0-1-0.4-1-1c0-18.3,14.9-33.2,33.2-33.2c0.6,0,1,0.4,1,1s-0.4,1-1,1
            c-17.2,0-31.2,14-31.2,31.2C625.2,856.9,624.8,857.4,624.2,857.4z"/>
                                <path class="st0" d="M264.1,857.4c-0.6,0-1-0.4-1-1c0-4.8,0-19.6,9.7-29.1c6.5-6.3,16.1-9.4,28.6-9.1h0.9
            c1.6-20.1,22.8-35.7,48.6-35.7s47,15.5,48.6,35.6c5.7-1.6,11.7-2.4,17.6-2.4c27.8,0.9,35.2,25.2,35.2,39.5c0,0.6-0.4,1-1,1
            s-1-0.4-1-1c0-13.5-7-36.6-33.3-37.5c-4.9-0.1-13.9,1-18.1,2.7c-0.3,0.1-0.6,0.1-0.9-0.1c-0.3-0.2-0.4-0.5-0.5-0.8
            c-0.8-19.7-21.3-35-46.7-35c-25.2,0-45.7,15.3-46.7,34.8c0,0.3-0.1,0.5-0.3,0.7c-0.2,0.2-0.5,0.3-0.7,0.3l-1.8-0.1
            c-12-0.3-21.1,2.6-27.2,8.6c-9.1,8.9-9.1,23-9.1,27.7C265.1,856.9,264.7,857.4,264.1,857.4C264.1,857.4,264.1,857.4,264.1,857.4
            z"/>
                                <path class="st0" d="M313.1,820.9c-0.6,0-1-0.4-1-1c0-8.2,3.6-16,9.9-21.3c5.9-5.1,13.9-7.3,21.6-5.9c0.6,0.1,0.9,0.6,0.8,1.2
            c-0.1,0.6-0.6,0.9-1.2,0.8l0,0c-7.1-1.3-14.4,0.7-19.9,5.5c-5.8,5-9.1,12.2-9.1,19.8C314.2,820.5,313.7,821,313.1,820.9
            L313.1,820.9z"/>
                                <path class="st0" d="M507.5,837.3c-0.5,0-1-0.4-1-0.9c-0.3-4.3-2.4-8.2-5.9-10.7c-3.7-2.6-8.3-3.5-12.6-2.5
            c-0.5,0.2-1.1-0.2-1.2-0.7s0.2-1.1,0.7-1.2c0,0,0,0,0.1,0c4.9-1.2,10.2-0.2,14.3,2.8c3.9,2.8,6.4,7.3,6.7,12.1
            C508.5,836.8,508.1,837.3,507.5,837.3C507.6,837.3,507.6,837.3,507.5,837.3L507.5,837.3z"/>
                            </g>
                        </g>
                    </svg>
                </div>
                <p class="title thin primary">Zaregistrujte sa<br>a naštartujte Váš nový projekt hneď.</p>
            </div>
            <div class="col-md-5 col-lg-4 text-center main-wrap rightColumn">
                <div class="go-back-btn"><a href="index.php" title="Naspat na hlavnu stranku"></a></div>
                <div class="logo">
                    <a href="index.php"><img src="assets/img/icons/logo.svg"></a>
                </div>
                <p class="title thin">Registrovať sa</p>


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
                    <form accept-charset="utf-8" id="_regForm" method="post">
                        <div class="form-group putError">
                            <label for="userName" class="text-muted form-text" >Meno</label>
                            <div class="input-icon">
                                <img class="user" src="assets/img/icons/user.svg">
                            </div>
                            <input class="input-line input-default" type="text" id="userName" name="userName" value="">
                            <span></span>
                        </div>
                        <div class="form-group putError">
                            <label for="userEmail" class="text-muted form-text" >Email</label>
                            <div class="input-icon">
                                <img src="assets/img/icons/Email.svg">
                            </div>
                            <input class="input-line input-default" type="email" id="userEmail" name="userEmail" value="">
                            <span></span>
                        </div>
                        <div class="form-group putError">
                            <label for="upass" class="form-text text-muted " >Heslo</label>
                            <div class="input-icon">
                                <img class="lock" src="assets/img/icons/Lock.svg">
                            </div>
                            <div class="input-icon-back">
                                <svg class="toggle-password" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 128 81.7" style="enable-background:new 0 0 128 81.7;" xml:space="preserve">
                                    <title>Ukázať / skryť heslo</title>
                                    <g id="pass-visibility">
                                        <path class="0st" d="M64,4.1c18.5,0,34.4,6.7,47.2,19.8c4.4,4.6,8.3,9.8,11.4,15.4c1.1,2,1,4.4-0.3,6.3
            c-3.3,5.1-7.3,9.7-11.7,13.9c-13,12.1-28.4,18.2-45.9,18.2S31.4,71.5,18,59.3c-4.6-4.1-8.7-8.8-12.2-13.8C4.5,43.6,4.4,41,5.5,39
            c3.1-5.5,6.9-10.6,11.3-15.1C29.6,10.7,45.4,4.1,64,4.1 M64,0.1C24.1,0,5.7,29.7,0.7,39.5c-1,1.9-0.9,4.2,0.3,6
            c5.8,9.1,26.3,36.2,63.7,36.2s57-27.2,62.5-36.2c1.1-1.8,1.2-4,0.2-5.9C122.4,29.8,103.9,0,64,0.1L64,0.1z"/>
                                        <path class="0st" d="M63.3,67.6c-15,0-27.1-12.4-27.1-27.7s12.2-27.7,27.1-27.7s27.1,12.4,27.1,27.7S78.3,67.6,63.3,67.6z
             M63.3,14.1c-13.9,0-25.1,11.5-25.1,25.7s11.3,25.7,25.1,25.7S88.4,54,88.4,39.8S77.2,14.1,63.3,14.1z"/>
                                        <path class="0st" d="M48,46.2c-0.5,0-0.9-0.3-1-0.8c-0.1-0.4-2.4-10,2.8-16.6c3.1-4,8.2-6,15.1-6c0.6,0,1,0.4,1,1s-0.4,1-1,1
            c-6.3,0-10.8,1.8-13.5,5.2c-4.6,5.9-2.5,14.9-2.5,15c0.1,0.5-0.2,1.1-0.7,1.2C48.1,46.2,48.1,46.2,48,46.2z"/>
                                    </g>
                                </svg>
                            </div>
                            <input class="input-line input-pass" type="password" id="upass" name="upass">
                            <span></span>
                        </div>

                        <div class="custom-control custom-checkbox putError">
                            <input type="checkbox" class="custom-control-input" id="conditions" name="conditions">
                            <label class="custom-control-label" for="conditions">Súhlasím so <a href="#" role="button" data-toggle="modal" data-target="#gdpr-modal">spracovaním osobných údajov.</a> </label>
                        </div>
                        <div id="passwordHelpBlock">
                            <p><small class="form-text text-muted "><span>Už máte účet? </span><a href="login.php">Prihláste sa.</a></small></p>
                        </div>
                        <button class="btn btn-block mb-4" type="submit" id="registerButton" name="register">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            <span>registrovať sa</span>
                        </button>
                    </form>
                    <!-- Modal for Conditions -->
                    <div class="modal fade animated bounceInDown" id="gdpr-modal" tabindex="-1" role="dialog" aria-labelledby="zasady ochrany osobnych udajov" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="gdprModalTitle">Zásady ochrany osobných údajov</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras ut tellus nec urna volutpat imperdiet auctor et dui. Pellentesque faucibus velit in libero iaculis semper. Nullam ut est ut lectus mattis sagittis. In euismod gravida tellus, id tempor lorem viverra nec.
                                    </p>
                                    <p>
                                        Morbi semper velit sit amet lorem rutrum, id aliquam sapien faucibus. Curabitur id massa et nibh lacinia facilisis. Curabitur eu nibh metus. Suspendisse cursus molestie erat, nec lobortis ligula pretium nec.
                                    </p>
                                    <p>
                                        Maecenas at porttitor libero. Maecenas eu ante metus. Maecenas bibendum tincidunt magna ac dapibus.
                                    </p>
                                    <p>
                                        In ultrices, nunc nec interdum cursus, nulla leo ullamcorper neque, vel maximus odio ligula quis lorem. Suspendisse lobortis libero at lacus luctus elementum. Integer mollis enim non venenatis molestie. Cras ac neque id felis aliquam convallis ut in mi.
                                    </p>
                                    <p>
                                        Etiam sagittis nisi nibh, ac consectetur erat viverra hendrerit. Etiam sapien eros, molestie vitae odio quis, eleifend facilisis ligula. Nulla et varius metus. Morbi eleifend finibus interdum...
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn" type="button" data-dismiss="modal">Zavrieť</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<script src="./assets/js/vendors.min.js"></script>
<script src="./assets/js/app.min.js"></script>

<script>
    // AJAX REGISTRATION

    var errorBox = $("#ajaxErrorsAlert");
    var errorBoxContent = $("#ajaxErrors");
    var errorMessage = "";

    $(document).ready(function () {


        //form
        var registerForm = $("#_regForm");

            //form validation (jQuery Validation plugin)
            registerForm.validate({
                rules: {                         //rules

                    //input names and their rules
                    userName:{
                        required: true,
                        minlength: 2
                    },
                    userEmail:{
                        required: true,
                        email: true
                    },
                    upass:{
                        required: true,
                        rangelength: [8, 20],
                        strongPassword: true
                    },
                    conditions:{
                        required: true
                    }
                },
                messages:{                      //messages
                    userName:{
                        required: "Prosím, zadajte vaše meno",
                        minlength: "Meno musí obsahovať minimálne 2 znaky"
                    },
                    userEmail:{
                        required: "Prosím, zadajte váš email",
                        email: "Zadajte správny tvar emailu napr. meno@priezvisko.sk"
                    },
                    upass:{
                        required: "Prosím, zadajte heslo",
                        rangelength: "Heslo musí obsahovať minimálne 8 a maximálne 20 znakov"
                    },
                    conditions:{
                        required: "Pre zaregistrovanie musíte súhlasiť s našimi podmienkami"
                    }
                },



                submitHandler: function () {



                    //if the form is valid
                    if (registerForm.valid()){

                        $(".spinner-border").removeClass("d-none");
                        //ajax

                        $.ajax({
                            url: '../resources/src/scripts/registerUser.php',
                            type: 'POST',
                            dataType: 'JSON',
                            data: registerForm.serialize(),
                            success: function (data) {
                                // This is a callback that runs if the submission was a success.

                                $(".spinner-border").addClass("d-none");
                                //show some message
                                showMessages(data.code, data.msg);

                                registerForm[0].reset();
                                //
                                // registerForm.validate().resetForm();
                                // //delete error classes
                                // $("em.invalid-feedback").remove();
                                // $(".form-control").removeClass("is-invalid");

                                return false;
                            },

                            error: function () {
                                $(".spinner-border").addClass("d-none");
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
