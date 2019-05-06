<?php
/**
 * Copyright (c) 2019. TaskMag project managing web app. Created by Peter Sokol for Diploma Thesis.
 */

session_start();

require_once(__DIR__ . "/../controllers/UserController.php");

$logoutController = new UserController();


if(!$logoutController->isLoggedIn())
{
    $logoutController->redirect('../../../public_html/');
    die();
}

if($logoutController->isLoggedIn()!="")
{
    $logoutController->logout();
    $logoutController->redirect('../../../public_html/');
    die();
}
?>