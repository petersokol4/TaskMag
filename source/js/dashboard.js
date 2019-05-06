<!--GENERALLLLLL-->

<!--CHECK TIMER BEFORE LOGOUT-->


$(".buttonLogout").on('click', function(){

    $.ajax({
        url: '../resources/src/scripts/insertTimer.php',
        type: 'POST',
        dataType: 'JSON',
        data: {"requestType" : "check"},       //workaround -> more info insertProject.php
        success: function (data) {

            // This is a callback that runs if the submission was a success.

            switch (data.code)
            {
                case 200:
                    //timer is not running

                    //logout
                    window.location = '../resources/src/scripts/logoutUser.php';
                    break;

                case 404:
                    //some timer is running

                    $("#backToProject").attr("data-projectid", data.projectId);
                    $("#timerCheckModal").modal('show');
                    break;

                default:
                    //logout
                    window.location = '../resources/src/scripts/logoutUser.php';
            }
        },

        error: function () {
            // This is a callback that runs if the submission was not successful.
            alert(error);
        }
    });

});

$("#backToProject").on('click', function () {
    var idProject = $(this).attr('data-projectid');
    window.location = 'task-board?project='+idProject;
});


<!-- PROFILE -->


// PROFILE

$(document).ready(function () {

    /** STATIC INFO **/

    loadMyProfile();

    /** DYNAMIC INFO **/


    var profileForm = $("#_profile");
    profileForm.validate({  //form validation (jQuery Validation plugin)
        rules: {                         //rules

            //input names and their rules
            userName: {
                required: true,
                minlength: 2
            },
            userAbout: {
                minlength: 10,
                maxlength: 400
            }
        },
        messages: {                      //messages showed when inputs are invalid
            userName: {
                required: "Prosím, zadajte meno.",
                minlength: "Meno musí obsahovať minimálne 2 znaky."
            },
            userAbout: {

                minlength: "Popis musí obsahovať minimálne 10 znakov.",
                maxlength: "Popis musí obsahovať maximálne 400 znakov."
            }
        },

        submitHandler: function () {

            //if the form is valid
            if (profileForm.valid()) {
                //ajax

                $.ajax({
                    url: '../resources/src/scripts/updateProfile.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: profileForm.serialize(),       //workaround -> more info insertProject.php
                    success: function (data) {
                        // This is a callback that runs if the submission was a success.

                        loadMyProfile();
                        //alert(data.msg);
                        $("#profileModal").modal('hide');

                        return false;
                    },

                    error: function () {
                        // This is a callback that runs if the submission was not successful.

                    }
                });

                return false;
            }
        }
    }); //update profile

    var avatarForm = $("#_avatarForm");
    avatarForm.on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: '../resources/src/scripts/uploadAvatar.php',
            method: 'POST',
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                // This is a callback that runs if the submission was a success.

                if(data.code == '200'){
                    //update images

                    loadMyProfile();
                    $("#changeAvatarModal").modal('hide');
                    //TODO vynulovať file input
                }
                else{
                    alert(data.msg);
                }

                return false;
            },

            error: function () {
                // This is a callback that runs if the submission was not successful.
                alert("error");
            }
        });
    }); //upload and change avatar photo

    //close modal changeAvatarModal -> show profile modal
    $('#changeAvatarModal').on('hidden.bs.modal', function () {
        $("#profileModal").modal('show');
    });

    $('#changeAvatarModal').on('show.bs.modal', function () {
        $("#profileModal").modal('hide');
    });
});

function loadMyProfile() {
    $.ajax({
        url: '../resources/src/scripts/fetchProfile.php',
        type: 'POST',
        dataType: 'JSON',
        data: {"requestType": "my"},
        success: function (data) {
            // This is a callback that runs if the submission was a success.

            //insert to modal
            $('input#userName').val(data.userName);
            $('textarea#userAbout').val(data.userAbout);
            $('.userEmail').text(data.userEmail);

            //insert to sidebar and navbar & modal (img)
            $(".uNameAjax").text(data.userName);
            $("img.uAvatarAjax").attr("src","uploads/users/"+data.userAvatar);

        },
        error: function () {
            //alert('error');
        }
    });
}





//custom file input
$(document).ready(function () {
    bsCustomFileInput.init()
});



<!--END OF GENERALLLLLLL-->





//    JAVASCRIPT
var canvas = document.getElementById("canvas");
var ctx = canvas.getContext("2d");


function degToRad(degree) {
    var factor = Math.PI / 180;
    return degree * factor;
}

function renderTime() {
    var now = new Date();
    var todayStr = now.toLocaleDateString('sk-SK', {weekday: 'long'}).toUpperCase();
    var todayStrThree = todayStr.substring(0, 3);
    var monthStr = now.toLocaleDateString('sk-SK', {month: 'short'}).toUpperCase();

    var time = now.toLocaleTimeString();

    var hrs = now.getHours().toString();

    var min = now.getMinutes().toString();


    var sec = now.getSeconds();
    var mil = now.getMilliseconds();

    var day = now.getDate().toString();
    var month = (now.getMonth() + 1).toString();
    var yearStr = now.getFullYear().toString();

    var smoothsec = sec + (mil / 1000);

    //Background

    ctx.fillStyle = 'rgba(0, 0, 0, 0.5)';
    ctx.fillRect(0, 0, 1000, 1000);   //čo to je?

    ctx.beginPath();
    ctx.strokeStyle = '#3ea7db';
    ctx.lineWidth = 8;
    ctx.arc(500, 500, 450, degToRad(270), degToRad((smoothsec * 6) - 90));
    ctx.stroke();
    //Date
    // ctx.font = "300 30px Roboto";
    // ctx.fillStyle = '#ffffff';
    // ctx.fillText(today, 175, 250);
    //Time
    // ctx.font = "300 160px Roboto ";
    // ctx.fillStyle = '#ffffff';
    // ctx.fillText(time, 55, 300);

    //Time
    if (min.length < 2) {
        min = 0 + min;
    }
    if (hrs.length < 2) {
        hrs = 0 + hrs;
    }

    ctx.font = "300 280px Roboto";
    ctx.fillStyle = '#ffffff';
    ctx.fillText(hrs, 140, 500);

    ctx.font = "300 280px Roboto";
    ctx.fillStyle = '#ffffff';
    ctx.fillText(min, 560, 500);

    //today name
    ctx.font = "400 100px Roboto";
    ctx.fillStyle = '#ffffff';
    ctx.fillText(todayStrThree, 240, 700);

    //date
    if (day.length < 2) {
        day = 0 + day;
    }
    ctx.font = "500 100px Roboto";
    ctx.fillStyle = '#ffffff';
    ctx.fillText(day, 560, 700);

    ctx.font = "700 44px Roboto";
    ctx.fillStyle = '#ffffff';
    ctx.fillText(monthStr, 690, 656);

    ctx.font = "300 42px Roboto";
    ctx.fillStyle = '#ffffff';
    ctx.fillText(yearStr, 690, 704);

    //divider
    ctx.beginPath();
    ctx.moveTo(500, 240);
    ctx.lineTo(500, 760);
    ctx.strokeStyle = '#d3d3d3';
    ctx.lineWidth = 6;
    ctx.stroke();


}

setInterval(renderTime, 40);






var d = new Date();    // defaults to the current time in the current timezone
var welcome = document.getElementById("welcome");

if(d.getHours() < 5)
{
    welcome.textContent = "Dobrý neskorý večer";
}
else if (d.getHours() < 10) {

    welcome.textContent = "Dobré ráno";
}
else if (d.getHours() <= 17 )
{
    welcome.textContent = "Dobrý deň";
}
else if (17 < d.getHours())
{
    welcome.textContent = "Dobrý večer";
}
else
{
    welcome.textContent = "Dobrý deň";
}



<!--JQUERY-->



//    mobile menu functionality
$(document).ready(function () {
    $("#openSideMenu").click(function () {
        $("#mobileSideMenuBg").css({"visibility": "visible", "background-color": "rgba(0,0,0,0.8"});
        $("#mobileSideMenu").css({"left": "0vw", "box-shadow": "6px 3px 6px rgba(0,0,0,0.16)"});
    });

    $("#closeSideMenu, #mobileSideMenuBg, #mobileTimersLink").click(function () {
        $("#mobileSideMenu").css({"left": "-100vw", "box-shadow": "none"});
        $("#mobileSideMenuBg").css({"visibility": "hidden", "background-color": "rgba(0,0,0,0"});
    });

    $('.modal').on('shown.bs.modal', function () {
        $("#mobileSideMenu").css({"left": "-100vw", "box-shadow": "none"});
        $("#mobileSideMenuBg").css({"visibility": "hidden", "background-color": "rgba(0,0,0,0"});
    });
});




//hide notification badge after click
$(".notificationLink").click(function () {
    $(".badge.up").css({"display": "none"});
});




//hide main sidebar

//
// $("#hideMainSidebar").click(function () {
//     $("#mainSidebar").toggleClass('hidden');
//     $("#mainContent").toggleClass('marged customWidth');
//
// });






//hide main Navbar if no mouse move
var timeout = null;
$(document).on('mousemove', function(event) {
    if (timeout !== null) {
        $(".mainNavbarDashboard").removeClass('hideMainNavDashboard');
        $("#mainContent").removeClass("marged");
        $("#mainSidebar").removeClass('hidden');
        $('body').css('cursor', 'default');
        clearTimeout(timeout);
    }


    timeout = setTimeout(function() {

        $(".mainNavbarDashboard").addClass('hideMainNavDashboard');
        $("#mainContent").addClass("marged");
        $("#mainSidebar").addClass('hidden');
        $('body').css('cursor', 'none');


    }, 30000);

});


