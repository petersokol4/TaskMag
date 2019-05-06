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
                        alert(data.msg);

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




//toto do app.js GENERAL
flatpickr.localize(flatpickr.l10ns.sk);

$(document).ready(function () {
    $(".datepickerStart").flatpickr({

        altInput: true,
        // enableTime: true,
        // time_24hr: true,
        altFormat: "d. F Y",
        dateFormat: "Y-m-d",
        //disableMobile: "true",
        defaultDate: new Date()

    });

    $(".datepickerEnd").flatpickr({

        altInput: true,
        // enableTime: true,        //throws errors when using with bootstrap modal see more -> https://github.com/ankurk91/vue-flatpickr-component/issues/63
        // time_24hr: true,
        altFormat: "d. F Y",
        dateFormat: "Y-m-d",
        //disableMobile: "true",
        defaultDate: new Date().fp_incr(1)

    });
});




// CRUD AJAX | FILTER

var errorBox = $("#ajaxErrorsAlert");
var errorBoxContent = $("#ajaxErrors");
var errorMessage = "";

$(document).ready(function () {
    //TODO: loader spinner -> znemožniť robiť hocičo kým sa nenačíta celá stránka!!!!!!
    //show content
    loadData();

    //form
    var projectForm = $("#_project");

    //UPDATE | INSERT

    //form validation (jQuery Validation plugin)
    projectForm.validate({
        rules: {                         //rules

            //input names and their rules
            projectClient: {
                required: true,
                minlength: 2
            },
            projectName: {
                required: true,
                minlength: 2
            },
            projectDescription: {
                required: true,
                minlength: 10,
                maxlength: 400
            },
            projectStatus: {
                required: true

            },
            projectCategory: {
                required: true

            },
            projectStart: {
                required: true

            },
            projectEnd: {
                required: true
            }
        },
        messages: {                      //messages showed when inputs are invalid
            projectClient: {
                required: "Prosím, zadajte meno klienta.",
                minlength: "Názov musí obsahovať minimálne 2 znaky."
            },
            projectName: {
                required: "Prosím, zadajte názov projektu.",
                minlength: "Názov musí obsahovať minimálne 2 znaky."
            },
            projectDescription: {
                required: "Prosím, zadajte popis projektu.",
                minlength: "Popis musí obsahovať minimálne 10 znakov.",
                maxlength: "Popis musí obsahovať maximálne 400 znakov."
            },
            projectStatus: {
                required: "Prosím, zvoľte status projektu."

            },
            projectCategory: {
                required: "Prosím, zvoľte kategóriu projektu."

            },
            projectStart: {
                required: "Prosím, zvoľte začiatok projektu."

            },
            projectEnd: {
                required: "Prosím, zvoľte koniec projektu."
            }
        },

        submitHandler: function () {

            //if the form is valid
            if (projectForm.valid()) {
                //ajax

                $.ajax({
                    url: '../resources/src/scripts/insertProject.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: projectForm.serialize(),
                    success: function (data) {
                        // This is a callback that runs if the submission was a success.

                        //show some message
                        showMessages(data.code, data.msg);

                        //hide modal
                        $("#newProjectModal").modal('hide');

                        //reload filters + data
                        loadData();

                        return false;
                    },

                    error: function () {
                        // This is a callback that runs if the submission was not successful.

                    }
                });

                return false;
            }
        }
    });


    //SELECT AND ShOW
    $(document).on('click', '.btn-edit', function () {

        var id = $(this).data("id"); // Extract info from data-* attributes


        $.ajax({
            url: '../resources/src/scripts/fetchProjects.php',
            type: 'POST',
            dataType: 'JSON',
            data: {"id": id, "requestType": "single"},
            success: function (data) {
                // This is a callback that runs if the submission was a success.


                $("#newProjectModalTitle").text('Úprava projektu');  //zmeni to všetky modaly?

                //add value to hidden input with id="id"
                $("#id").val(data.id);

                $("#createProjectButton").text("Upraviť");
                $('#projectClient').val(data.projectClient);
                $('#projectName').val(data.projectName);
                $('#projectDescription').val(data.projectDescription);
                $('#projectStatus').val(data.projectStatus);
                $('#projectCategory').val(data.projectCategory);
                $('#projectStart').val(data.projectStart);
                $('#projectEnd').val(data.projectEnd);
                $('.datepickerEnd').val(data.projectEnd);

                //show corect date

                $(".datepickerStart").flatpickr({

                    altInput: true,
                    // enableTime: true,        //throws errors when using with bootstrap modal see more -> https://github.com/ankurk91/vue-flatpickr-component/issues/63
                    // time_24hr: true,
                    altFormat: "d. F Y",
                    dateFormat: "Y-m-d",
                    // disableMobile: "true",
                    defaultDate: data.projectStart

                });

                $(".datepickerEnd").flatpickr({

                    altInput: true,
                    // enableTime: true,        //throws errors when using with bootstrap modal see more -> https://github.com/ankurk91/vue-flatpickr-component/issues/63
                    // time_24hr: true,
                    altFormat: "d. F Y",
                    dateFormat: "Y-m-d",
                    //disableMobile: "true",
                    defaultDate: data.projectEnd
                });

                //SHOW
                $('#newProjectModal').modal("show");
                $('#newProjectModal').on('shown.bs.modal', function () {
                    var ta = document.querySelector('#projectDescription');
                    autosize.update(ta);
                });

            },
            error: function () {
                //alert('error');
            }

        });
    });


    //keď kliknem na button delete -> zobrazí sa modal a ulozi sa id projektu z atributu data-id
    $('#deleteProjectModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var deleteId = button.data('id'); // Extract info from data-* attributes

        //ak kliknem na tačidlo vymazať
        $("button#delete").click(function () {
            $.ajax({
                url: '../resources/src/scripts/deleteProject.php',
                type: 'POST',
                dataType: 'JSON',
                data: {"id": deleteId},
                success: function (data) {
                    // This is a callback that runs if the submission was a success.

                    //show some message
                    showMessages(data.code, data.msg);

                    //refresh filters + table
                    loadData();

                    //hide modal
                    $('#deleteProjectModal').modal('hide');

                }
            });
        });
    });


    //filters
    $(document).on('click', '#btnActiveProjects', function () {

        loadActiveData();

    });

    $(document).on('click', '#btnMyProjects', function () {

        loadMyData();

    });

    $(document).on('click', '#btnDoneProjects', function () {

        loadDoneData();

    });

    $(document).on('click', '#btnAllProjects', function () {

        loadData();

    });


    //functions

    function loadData() {
        $.ajax({
            url: '../resources/src/scripts/fetchProjects.php',
            type: 'POST',
            data: {"requestType": "all"},
            success: function (response) {
                $("#mainContentView").html(response);
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    }

    function loadMyData() {
        $.ajax({
            url: '../resources/src/scripts/fetchProjects.php',
            type: 'POST',
            data: {"requestType": "my"},
            success: function (response) {
                $("#projectContentBox").html(response);
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    }

    function loadActiveData() {
        $.ajax({
            url: '../resources/src/scripts/fetchProjects.php',
            type: 'POST',
            data: {"requestType": "active"},
            success: function (response) {
                $("#projectContentBox").html(response);
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    }

    function loadDoneData() {
        $.ajax({
            url: '../resources/src/scripts/fetchProjects.php',
            type: 'POST',
            data: {"requestType": "done"},
            success: function (response) {
                $("#projectContentBox").html(response);
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    }

    function showMessages(code, msg) {
        // if success
        if (code == 200) {
            errorMessage = '<h6>Hurá! Všetko prebehlo OK.</h6>';
            $.each(msg, function (key, value) {
                errorMessage += ('<p>' + value + '</p>');
            });
            errorBox.addClass("alert alert-success");
            errorBoxContent.html(errorMessage);
            errorBox.alert();
            //auto-fade alert
            errorBox.fadeTo(5000, 5000).slideUp(5000, function () {
            });
            //if some errors
        } else if (code == 404) {
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
        }
    }

});




$(document).ready(function () {
    //on close modal
    $('#newProjectModal').on('hidden.bs.modal', function () {
        //RESET FORM
        $('#_project')[0].reset();
        //erase value of hidden input || must be here if modal was only close (not send)
        $("#id").val("");

        $('#_project').validate().resetForm();
        //delete error classes
        $("em.invalid-feedback").remove();
        $(".form-control").removeClass("is-invalid");

        $(".datepickerStart").flatpickr({

            altInput: true,
            // enableTime: true,        //throws errors when using with bootstrap modal see more -> https://github.com/ankurk91/vue-flatpickr-component/issues/63
            // time_24hr: true,
            altFormat: "d. F Y",
            dateFormat: "Y-m-d",
            //disableMobile: "true",
            defaultDate: "today"
        });

        $(".datepickerEnd").flatpickr({

            altInput: true,
            // enableTime: true,        //throws errors when using with bootstrap modal see more -> https://github.com/ankurk91/vue-flatpickr-component/issues/63
            // time_24hr: true,
            altFormat: "d. F Y",
            dateFormat: "Y-m-d",
            //disableMobile: "true",
            defaultDate: new Date().fp_incr(1)

        });

        $("#newProjectModalTitle").text('Vytvorenie nového projektu');  //zmeni to všetky modaly?
        $("#createProjectButton").text("Vytvoriť");
    });
});



<!--GENERAL -->


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

    //hide notification badge after click
    $(".notificationLink").click(function () {
        $(".badge.up").css({"display": "none"});
    });

    $("#hideMainSidebar").click(function () {
        $("#mainSidebar").toggleClass('hidden');
        $("#mainContent").toggleClass('marged customWidth');
    });

    $('.modal').on('shown.bs.modal', function () {
        $("#mobileSideMenu").css({"left": "-100vw", "box-shadow": "none"});
        $("#mobileSideMenuBg").css({"visibility": "hidden", "background-color": "rgba(0,0,0,0"});
    });

});



//tooltips - z popper.js
$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();
    autosize(document.querySelectorAll('textarea'));

});
