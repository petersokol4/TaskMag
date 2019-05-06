<?php
/**
 * Copyright (c) 2019. TaskMag project managing web app. Created by Peter Sokol for Diploma Thesis.
 */

$status = $_SERVER['REDIRECT_STATUS'];
$codes = array(
    403 => array('403 Forbidden', 'The server has refused to fulfill your request.'),
    404 => array('404 Not Found', 'The document/file requested was not found on this server.'),
    405 => array('405 Method Not Allowed', 'The method specified in the Request-Line is not allowed for the specified resource.'),
    408 => array('408 Request Timeout', 'Your browser failed to send a request in the time allowed by the server.'),
    500 => array('500 Internal Server Error', 'The request was unsuccessful due to an unexpected condition encountered by the server.'),
    502 => array('502 Bad Gateway', 'The server received an invalid response from the upstream server while trying to fulfill the request.'),
    504 => array('504 Gateway Timeout', 'The upstream server failed to send a request in the time allowed by the server.'),
);

$title = $codes[$status][0];
$message = $codes[$status][1];
if ($title == false || strlen($status) != 3) {
    $message = 'Please supply a valid status code.';
}

$path = '/TaskMag/build/public_html/';

?>

<!DOCTYPE html>
<html class="no-js" lang="sk">
<head>
    <title>TaskMag - Niečo sa stalo...</title>
    <base href="<?php echo $path;?>" />
    <meta charset="UTF-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700,900&amp;subset=latin-ext" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css" />
</head>
<body>

<div id="mainWrap m-0" style="min-height: 100vh;">
    <div class="container ">
        <div class="row" style="min-height: 100vh;">
            <div class="col-md-7 col-lg-8 text-center d-none d-md-block my-md-auto">
                <p class="jumbo">Ooops!</p>
                <div class="svg-image mx-auto d-block">
                    <img src="<?php echo $path; ?>assets/img/bulb.svg" class="leftImg">
                </div>

            </div>
            <div class="col-md-5 col-lg-4 text-center main-wrap my-auto rightColumn ">
                <div class="go-back-btn"><a href="login" title="Naspäť na prihlásenie"></a></div>
                <div class="logo">
                    <a href="index"><img src="assets/img/icons/logo.svg"></a>
                </div>
                <p class="title thin"><?php echo $title; ?></p>

                <section class="text-left">

                    <p><?php echo $message; ?></p>

                        <a href="index" class="btn btn-block mt-5 mb-5">Naspäť do bezpečia</a>

                </section>
            </div>
        </div>
    </div>
</div>


