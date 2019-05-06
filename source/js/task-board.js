<!---->
<!--<script type="text/javascript">-->
<!--    jQuery(document).ready(function() {-->
<!--        $("time.timeago").timeago();-->
<!--    });-->


<!--GENERAL-->


$(document).ajaxSend(function(event, request, settings) {
    $('#ajax_loader').show();
});

$(document).ajaxComplete(function(event, request, settings) {
    $('#ajax_loader').hide();
});


//tooltips - z popper.js
$(document).ready(function () {

    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    autosize(document.querySelectorAll('textarea'));

    $("#taskPriority").slider();
    $("#taskPriority").on("change", function(slideEvt) {
        $("#currentPriority").text(slideEvt.value["newValue"]);
    });


});



<!--COLUMNS AND TASKS-->

$(document).ready(function () {

    var projectId = $("#projectInfo").attr("data-id"); // echoed from PHP

    loadTasks(projectId);


    var columnForm = $("#_column");

    //UPDATE | INSERT

    //form validation (jQuery Validation plugin)
    columnForm.validate({
        rules: {                         //rules

            //input names and their rules
            columnName: {
                required: true,
                minlength: 2,
                maxlength: 100
            },
            color: {
                required: true
            },
            columnLimit:{
                min: 1,
                digits: true,
                number: true
            }
        },
        messages: {                      //messages showed when inputs are invalid
            columnName: {
                required: "Prosím, zadajte názov stĺpca",
                minlength: "Minimálna dĺžka je 2 znaky",
                maxlength: "Maximálna dĺžka je 100 znakov"
            },
            color: {
                required: "Vyberte farbu"
            },
            columnLimit:{
                min: "Limit musí byť aspoň 1",
                digits: "Prosím, zadajte celé číslo",
                number: "Prosím, zadajte celé číslo"
            }
        },

        submitHandler: function () {

            //if the form is valid works
            if (columnForm.valid()) {
                //ajax

                $.ajax({
                    url: '../resources/src/scripts/insertColumn.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: columnForm.serialize(),
                    success: function (data) {
                        // This is a callback that runs if the submission was a success.

                        if(data.code === 200){
                            $("#addColumnModal").modal('hide');
                        }

                        loadTasks(projectId);

                        //alert(data.msg);

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

    $(document).on('click', '.updateColumn', function () {

        var idColumnUpdate = $(this).attr("data-id"); // Extract info from data-* attributes

        $.ajax({
            url: '../resources/src/scripts/fetchColumns.php',
            type: 'POST',
            dataType: 'JSON',
            data: {"id": idColumnUpdate, "requestType": "single", "projectId": projectId},
            success: function (data) {
                // This is a callback that runs if the submission was a success.


                $("#addColumnModalTitle").text('Úprava stĺpca');  //zmeni to všetky modaly?

                $("input#c_Id").val(data.id);

                $("#createColumnButton").text("Upraviť");

                $('#columnName').val(data.columnName);
                $('#_column').find(':radio[name=color][value="'+data.color+'"]').prop('checked', true);

                if(data.columnLimit != 0)
                {
                    $('#columnLimit').val(data.columnLimit);
                }

                //SHOW
                $('#addColumnModal').modal("show");

            },
            error: function () {
                //alert('error');
            }

        });
    });

    $('#addColumnModal').on('hidden.bs.modal', function () {

        //reset COMMENT form
        $("#_column")[0].reset();
        $('#_column').validate().resetForm();
        $("em.invalid-feedback").remove();
        $(".form-control").removeClass("is-invalid");
        $("input#c_Id").val("");
        $("#addColumnModalTitle").text('Pridanie stĺpca');
        $("#createColumnButton").text("Pridať");

    });

    //move tasks to new column

    $(document).on('click', '.moveColumnTasks', function () {

        var idColumnMove = $(this).attr("data-id"); // Extract info from data-* attributes


        $.ajax({
            url: '../resources/src/scripts/fetchColumns.php',
            type: 'POST',
            dataType: 'html',
            data: {"requestType": "allForm", "projectId": projectId},
            success: function (data) {
                // This is a callback that runs if the submission was a success.

                $("#defaultModalTitle").text('Presun úloh');
                $('#defaultModalContentAjax').html(data);
                $("input#columnId").val(idColumnMove);
                $('#defaultModal').modal("show");

                var columnMoveForm = $("#_columnMove");


                columnMoveForm.validate({
                    rules: {                         //rules

                        //input names and their rules
                        newColumnId: {
                            required: true
                        }
                    },
                    messages: {                      //messages showed when inputs are invalid
                        newColumnId: {
                            required: "Prosím, vyberte stĺpec."
                        }
                    },

                    submitHandler: function () {

                        //if the form is valid works
                        if (columnMoveForm.valid()) {
                            //ajax

                            $.ajax({
                                url: '../resources/src/scripts/moveTasks.php',
                                type: 'POST',
                                dataType: 'JSON',
                                data: columnMoveForm.serialize() +"&requestType=move",
                                success: function (data) {
                                    // This is a callback that runs if the submission was a success.

                                    if(data.code === 200){

                                        loadTasks(projectId);
                                        $("#defaultModal").modal('hide');
                                        alert(data.msg);
                                    }
                                    else if(data.code === 404)
                                    {
                                        alert(data.msg);
                                    }
                                },

                                error: function () {
                                    // This is a callback that runs if the submission was not successful.

                                }
                            });

                            return false;
                        }
                    }
                });
            },
            error: function () {
                //alert('error');
            }
        });
    });

    //delete column

    $(document).on('click', '.deleteColumn', function () {

        var idColumnDelete = $(this).attr("data-id"); // Extract info from data-* attributes

        var countTasks = $(this).closest(".listHeader").find("h6.taskCountAjax").text();


        $.ajax({
            url: '../resources/src/scripts/deleteColumn.php',
            type: 'POST',
            dataType: 'html',
            data: {"requestType": "show", "countTasks": countTasks, "columnId": idColumnDelete},
            success: function (data) {
                // This is a callback that runs if the submission was a success.

                $("#defaultModalTitle").text('Vymazanie stĺpca');
                $('#defaultModalContentAjax').html(data);
                $('#defaultModal').modal("show");

                $(document).off("click", "#deleteColumnButton").on('click', '#deleteColumnButton', function (e) {   //https://stackoverflow.com/questions/43819886/ajax-sends-request-more-than-once
                    $.ajax({
                        url: '../resources/src/scripts/deleteColumn.php',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {"requestType": "delete", "columnId": idColumnDelete, "projectId": projectId},
                        success: function (data) {
                            // This is a callback that runs if the submission was a success.

                            if(data.code === 200){

                                loadTasks(projectId);
                                $("#defaultModal").modal('hide');
                            }
                            else if(data.code === 404)
                            {
                                alert(data.msg);
                                $("#defaultModal").modal('hide');
                            }
                        },

                        error: function () {
                            // This is a callback that runs if the submission was not successful.

                        }
                    });
                    e.preventDefault();
                });
            },
            error: function () {
                //alert('error');
            }
        });
    });

});

function loadTasks (id) {

    $.ajax({
        url: '../resources/src/scripts/fetchColumns.php',
        type: 'POST',
        dataType: 'html',
        data: {"projectId": id, "requestType": "all"},
        success: function (response) {
            // This is a callback that runs if the submission was a success.

            $("#listsContentAjax").html(response);

            //initiaion
            $('ul[id^="sort"]').sortable(
                {
                    //resolve troubles with z-index
                    appendTo: ".lists", helper: "clone",

                    //connected columns with class sortable
                    connectWith: ".sortable",

                    //animation
                    start: function (e, ui) {
                        $(ui.placeholder).hide(200);
                    },
                    change: function (e, ui) {
                        $(ui.placeholder).hide().show(200);
                    }
                    ,
                    //send changed position using AJAX
                    receive: function (e, ui) {
                        var columnId = $(ui.item).parent(".sortable").attr("data-columnid");
                        var taskId = $(ui.item).attr("data-taskid");
                        $.ajax({
                            url: '../resources/src/scripts/updateTaskColumn.php',
                            type: 'POST',
                            dataType: 'JSON',
                            data: {"taskId": taskId, "columnId": columnId},
                            success: function (data) {

                                // change count of tasks
                                $('[data-columnid="'+data.c_OldId+'"] > header > h6.taskCountAjax').text(data.c_OldCount);
                                $('[data-columnid="'+data.c_NewId+'"] > header > h6.taskCountAjax').text(data.c_NewCount);


                                //WIP funkcionality
                                var c_OldLimit = $('[data-columnid="'+data.c_OldId+'"]').attr("data-limit");
                                var c_NewLimit = $('[data-columnid="'+data.c_NewId+'"]').attr("data-limit");

                                if((data.c_OldCount <= c_OldLimit) && (c_OldLimit != 0))
                                {
                                    $('.list[data-columnid="'+data.c_OldId+'"]').removeClass("overLoad");
                                }
                                else if ((data.c_NewCount > c_NewLimit) && (c_NewLimit != 0))
                                {
                                    $('.list[data-columnid="'+data.c_NewId+'"]').addClass("overLoad");
                                }

                            }
                        });
                    }

                }).disableSelection();


            $('.listName').each(function () {
                var content = $(this).text();
                var len = content.length;
                if (len > 18) {
                    var contentShort = content.substr(0, 15) + "...";
                    $(this).text(contentShort);
                }
            });

        },
        error: function () {
            //if project doesn't exist redirect
            window.location.replace("project-board");
        }

    });




}






<!--SHOW TIMER-->

$(document).ready(function () {

    var dseconds = 0;
    var dminutes = 0;
    var dhours = 0;

    var seconds = 0;
    var minutes = 0;
    var hours = 0;


    var clock = $("#clockTimer");

    if ($("#stopTimer").data('start')) {

        var start = $("#stopTimer").attr('data-start');
        var date = Math.round(Date.now()/1000);
        var actual = date - start;

        //change actual to sec min hours
        //set ini values


        seconds = actual % 60;
        minutes = Math.floor(actual / 60) % 60;
        hours = Math.floor(actual / 60 / 60);




        setInterval(function() {

            seconds++;
            if (seconds >= 60) {
                seconds = 0;
                minutes++;
                if (minutes >= 60) {
                    minutes = 0;
                    hours++;
                }
            }

            if (seconds < 10){
                dseconds = "0"+seconds.toString();
            }else
            {
                dseconds = seconds;
            }
            if (minutes < 10){
                dminutes = "0"+minutes.toString();
            }else
            {
                dminutes = minutes;
            }
            if (hours < 10){
                dhours = "0"+hours.toString();
            }else
            {
                dhours = hours;
            }

            clock.html(dhours+" : "+dminutes+" : "+dseconds);

        }, 1000);

    }
});

<!--PROJECT-->


//PROJECT

$(document).ready(function () {
    var id = $("#projectInfo").attr("data-id"); // echoed from PHP

    /** STATIC INFO **/

    loadProjectInfo(id);


    /** DYNAMIC INFO **/

    //Project info - GRAPHS

    $('#projectInfoModal').on('shown.bs.modal', function (event) {

        //TASK GRAPH
        $.ajax({
            url: '../resources/src/scripts/fetchTasks.php',
            type: 'POST',
            dataType: 'JSON',
            data: {"requestType": "graph", "id" : id},
            success: function (graphData) {
                // This is a callback that runs if the submission was a success.

                var ctx = $("#statChart");

                var done = graphData.done;
                var inProgress = graphData.open;

                var data = {

                    datasets: [{
                        data: [done, inProgress],
                        backgroundColor: [
                            '#3ea7db',
                            '#babcbe'
                        ]
                    }],
                    labels: [
                        "Dokončené úlohy",
                        "Nedokončené úlohy"
                    ]

                };

                var statChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: data,
                    options: {
                        legend: {
                            labels: {
                                fontColor: 'black',
                                defaultFontFamily: "'Roboto'"

                            }
                        },
                        emptyOverlay:{
                            message: "Zatiaľ neboli vytvorené žiadne úlohy",
                            fillStyle: 'rgba(255,255,255,1.0)',
                            fontStrokeWidth: 0
                        }
                    }
                });

            },
            error: function () {
                //alert('error');
            }

        });

        //CYCLE TIME
        $.ajax({
            url: '../resources/src/scripts/fetchTasks.php',
            type: 'POST',
            dataType: 'JSON',
            data: {"requestType": "cycleTime", "id" : id},
            success: function (cycletime) {
                // This is a callback that runs if the submission was a success.

                if(cycletime.code != '404')
                {
                    if(cycletime.averageCycleTime == 1)
                    {
                        $("#cycleTimeAjax").text(cycletime.averageCycleTime+ ' deň');
                    }
                    else if(1 < cycletime.averageCycleTime < 5 )
                    {
                        $("#cycleTimeAjax").text(cycletime.averageCycleTime+ ' dni');
                    }
                    else
                    {
                        $("#cycleTimeAjax").text(cycletime.averageCycleTime+ ' dní');
                    }
                }
                else
                {
                    $("#cycleTimeAjax").html('<span style="font-size: 12px;"";">' + cycletime.msg +'</span>');
                }
            },
            error: function () {
                //alert('error');
            }

        });

        //USERS STATISTICS

        $.ajax({
            url: '../resources/src/scripts/fetchUsers.php',
            type: 'POST',
            dataType: 'JSON',
            data: {"requestType": "statistics", "projectId" : id},
            success: function (data) {

                // This is a callback that runs if the submission was a success.
                $("#assignedUsersAjax").text(data.assigned);
                $("#assignedProgressbarAjax").css('width', data.assigned + '%');

                $("#acceptedUsersAjax").text(data.accepted);
                $("#acceptedProgressbarAjax").css('width', data.accepted + '%');

            },
            error: function () {
                //alert('error');
            }

        });

    });

});


//SELECT PROJECT INFO AND ShOW
function loadProjectInfo (id) {

    $.ajax({
        url: '../resources/src/scripts/fetchProjects.php',
        type: 'POST',
        dataType: 'JSON',
        data: {"id": id, "requestType": "single"},
        success: function (data) {
            // This is a callback that runs if the submission was a success.

            //assign project data to modal
            $('.projectNameAjax').text(data.projectName);
            $('#projectStatusAjax').text(data.projectStatus);
            $('#projectClientAjax').text(data.projectClient);
            $('#projectDescriptionAjax').text(data.projectDescription);
            $('#projectStatus').text(data.projectStatus);
            $('#projectCategoryAjax').text(data.projectCategory);
            $('#projectCreatedAjax').text(data.projectCreatedFormated);
            $('#projectStartAjax').text(data.projectStartFormated);
            $('#projectEndAjax').text(data.projectEndFormated);

            if(data.overdueClass === 'overDue'){
                $("#overdueProjectAjax").addClass("overDue");
            }else if(data.overdueClass === 'noOverdue'){
                $("#overdueProjectAjax").removeClass("overDue");
            }

            //assign project info to hidden inputs
            $("input#attachDir").val(data.projectDirectory);
            $("input#dirZIP").val(data.projectDirectory);
            $("input#projectZIP").val(data.projectName);
            $("input#i_pN").val(data.projectName);

            //shorten project title

            $('.shortTitle').each(function () {
                var content = $(this).text();
                var len = content.length;
                if (len > 22) {
                    var contentShort = content.substr(0, 19) + "...";
                    $(this).text(contentShort);
                }
            });

        },
        error: function () {
            //if project doesn't exist redirect
            window.location.replace("project-board");
        }

    });
}


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
                minlength: 2,
                maxlength: 50
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

            $('#profileModal').on('shown.bs.modal', function () {
                var ta = document.querySelector('#userAbout');
                autosize.update(ta);
            });

        },
        error: function () {
            //alert('error');
        }
    });
}



<!--OPEN TASK MODAL - INFO | COMMENTS | ?SUBTSKS ?USERS-->

$(document).ready(function () {

    $('#taskModal').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget); // Button that triggered the modal
        var taskId = button.attr('data-taskid'); // Extract info from data-* attributes
        if(taskId == null)
        {
            //if modal was open without click on button
            taskId = $("#showAttachButton").attr("data-id");
        }

        loadTaskMembers(taskId);

        $.ajax({
            url: '../resources/src/scripts/fetchTasks.php',
            type: 'POST',
            dataType: 'JSON',
            data: {"id": taskId, "requestType": "single"},
            success: function (data) {
                // This is a callback that runs if the submission was a success. TODO 200 | 404

                //not completed and not started
                if((data.taskStatus == 0) && (data.taskStarted == 0))
                {
                    $("#taskCompletedAjax").text("");
                    $("#startButton").removeClass("d-none").addClass("d-block");
                    $("#completeButton").removeClass("d-block").addClass("d-none");
                    $("#completeTitle").removeClass("d-block").addClass("d-none");
                }
                else if((data.taskStatus == 0) && (data.taskStarted != 0)) //not completed but started
                {
                    $("#taskCompletedAjax").text("");
                    $("#completeButton").removeClass("d-none").addClass("d-block");
                    $("#startButton").removeClass("d-block").addClass("d-none");
                    $("#completeTitle").removeClass("d-block").addClass("d-none");
                }
                else if(data.taskStatus == 1) //completed
                {
                    $("#taskCompletedAjax").text(data.taskCompletedFormatted);
                    $("#completeButton").removeClass("d-block").addClass("d-none");
                    $("#completeTitle").removeClass("d-none").addClass("d-block");
                }

                //works
                $('#updateTask').attr("data-id", taskId);
                $('#deleteTask').attr("data-id", taskId);
                $('#completeButton').attr("data-id", taskId);
                $('#startButton').attr("data-id", taskId);
                $('#showAttachButton').attr("data-id", taskId);
                $('input#taskId').val(taskId);
                $('input#cTaskId').val(taskId);


                $('#taskNameAjax').text(data.taskName);
                $('#taskDescriptionAjax').text(data.taskDescription);
                $('#taskPriorityAjax').text(data.taskPriority);
                $('#taskDueDateAjax').text(data.taskDueDateFormatted);
                $('#taskCreatedAjax').text(data.taskCreatedFormatted);

                if(data.overdueClass == 'overDue'){
                    $("#overdueTaskAjax").addClass("overDue");
                }




            },

            complete: function ()
            {
                $('#taskModal').modal("show");
            },

            error: function () {
                alert('error');
            }

        });



        // ADD MEMBERS TO TASK
        //task_id | user_id


        $(document).on('click', '#assignMemberTaskButton', function () {

            var projectId = $("#projectInfo").attr("data-id"); // echoed from PHP

            $.ajax({
                url: '../resources/src/scripts/fetchUsers.php',
                type: 'POST',
                dataType: 'html',
                data: {"requestType": "noAssignedTask", "projectId": projectId, "taskId": taskId},
                success: function (data) {
                    // This is a callback that runs if the submission was a success.

                    $("#defaultModalTitle").text('Priradenie člena');
                    $('#defaultModalContentAjax').html(data);

                    $('#members').selectpicker();

                    var assignMemberTaskForm = $("#_taskMember");
                    assignMemberTaskForm.find("input#taskId").val(taskId);
                    $('#defaultModal').modal("show");




                    assignMemberTaskForm.validate({
                        rules: {                         //rules

                            //input names and their rules
                            'members[]': {
                                required: true
                            }
                        },
                        messages: {                      //messages showed when inputs are invalid

                            'members[]': "Prosím, vyberte aspoň jedného člena."

                        },

                        submitHandler: function () {

                            //if the form is valid works
                            if (assignMemberTaskForm.valid()) {
                                //ajax

                                $.ajax({
                                    url: '../resources/src/scripts/assignMembers.php',
                                    type: 'POST',
                                    dataType: 'JSON',
                                    data: assignMemberTaskForm.serialize() +"&requestType=task",
                                    success: function (data) {
                                        // This is a callback that runs if the submission was a success.

                                        if(data.code === 200){

                                            //loadTasks(projectId);
                                            $("#defaultModal").modal('hide');
                                            loadTaskMembers(taskId);
                                            //alert(data.msg);
                                        }
                                        else if(data.code === 404)
                                        {
                                            alert(data.msg);
                                        }
                                    },

                                    error: function () {
                                        // This is a callback that runs if the submission was not successful.

                                    }
                                });

                                return false;
                            }
                        }
                    });
                },
                error: function () {
                    //alert('error');
                }
            });
        });

        //UNASSIGN MEMBER FROM TASK

        $(document).on('click', '.unassignUserTaskAjax', function () {

            var idUnassignMember = $(this).attr("data-id"); // Extract info from data-* attributes
            var projectId = $("#projectInfo").attr("data-id"); // echoed from PHP

            $.ajax({
                url: '../resources/src/scripts/unassignUser.php',
                type: 'POST',
                dataType: 'html',
                data: {"requestType": "showTask"},
                success: function (data) {
                    // This is a callback that runs if the submission was a success.

                    $("#defaultModalTitle").text('Odobratie užívateľa z úlohy');
                    $('#defaultModalContentAjax').html(data);
                    $('#defaultModal').modal("show");

                    $(document).off("click", "#unassignTaskMemberButton").on('click', '#unassignTaskMemberButton', function (e) {   //https://stackoverflow.com/questions/43819886/ajax-sends-request-more-than-once
                        $.ajax({
                            url: '../resources/src/scripts/unassignUser.php',
                            type: 'POST',
                            dataType: 'JSON',
                            data: {"requestType": "unassignTask", "memberId": idUnassignMember, "taskId": taskId, "projectId": projectId},
                            success: function (data) {
                                // This is a callback that runs if the submission was a success.

                                if(data.code === 200){

                                    loadTaskMembers(taskId);

                                    $("#defaultModal").modal('hide');
                                    //alert(data.msg);
                                }
                                else if(data.code === 404)
                                {
                                    alert(data.msg);
                                }
                            },

                            error: function () {
                                // This is a callback that runs if the submission was not successful.

                            }
                        });
                        e.preventDefault();
                    });
                },
                error: function () {
                    //alert('error');
                }
            });
        });







        /* COMMENTS */

        var commentForm = $("#_commentForm");

        loadComments(taskId);


        // EDIT COMMENTS
        $(document).on('click', '.editComment', function () {
            var commentId = $(this).attr("data-id");
            var button = $(this).addClass("d-none");

            var oldText;

            var editableText = $('<textarea class="form-control" id="'+commentId+'" rows="1"></textarea>');

            //create textarea and load text from DB to textarea

            $.ajax({
                url: '../resources/src/scripts/fetchComments.php',
                type: 'POST',
                dataType: 'JSON',
                data: {"commentId": commentId, "requestType": "single"},
                success: function (data) {

                    editableText.text(data.commentContent);
                    oldText=data.commentContent;

                    $('div#'+commentId).html(editableText);

                    editableText.focus().height( $(editableText)[0].scrollHeight );
                    autosize($('textarea'));

                    //alternative contenteditable="true"
                    //todo -> submit on focusout
                },
                error: function () {
                    alert("error");
                }

            });

            //update comment on focusout from textarea and change the textarea to paragraph


            //validation
            editableText.keyup(function(event) {

                // // Avoid revalidate the field when pressing one of the following keys - DOESN'T WORK
                //
                // // Clear       => 12
                // // Enter       => 13
                // // Shift       => 16
                // // Ctrl        => 17
                // // Alt         => 18
                // // Pause/Break => 19
                // // Caps lock   => 20
                // // PgUp        => 33
                // // PgDown      => 34
                // // End         => 35
                // // Home        => 36
                // // Left arrow  => 37
                // // Up arrow    => 38
                // // Right arrow => 39
                // // Down arrow  => 40
                // // PrintScreen => 44
                // // Insert      => 45
                // // ContextMenu => 93
                // // F1 => 112
                // // F2 => 113
                // // F3 => 114
                // // F4 => 115
                // // F5 => 116
                // // F6 => 117
                // // F7 => 118
                // // F8 => 119
                // // F9 => 120
                // // F10 => 121
                // // F11 => 122
                // // F12 => 123
                // // Num lock    => 144
                // // Scroll lock => 145
                // // AltGr key   => 225
                //
                // var disabledKeys = [12, 13, 16, 17, 18, 19, 20, 33, 34, 35, 36, 37, 38, 39, 40, 44, 45, 93, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123, 144, 145, 225];
                //
                // if($.inArray(event.keyCode, disabledKeys) !== -1)
                // {
                var dInput = this.value;
                autosize($('textarea'));

                if(dInput.length < 5 || dInput.length > 800)
                {

                    //if doesn't exist
                    $("#editComentAjax-error").remove();
                    editableText.parent('.putError').after( '<em id="editComentAjax-error" class="invalid-feedback d-block">Komentár musí obsahovať minimálne 5 a maximálne 800 znakov.</em>');
                    editableText.addClass('is-invalid').removeClass('is-valid');
                    return false;

                }
                else
                {
                    $("#editComentAjax-error").remove();
                    editableText.removeClass('is-invalid').addClass('is-valid');
                }
                // }
                // else
                // {
                //
                // }
            });

            $(editableText).on('focusout', function (e){
                var newText = editableText.val();
                var staticText = $('<p style="white-space: pre-line; padding: .375rem .75rem;" class="m-0" id="'+commentId+'"></p>');





                if(editableText.val() === '' || newText === oldText)
                //if value is not empty or the same
                {
                    $("#editComentAjax-error").remove();
                    $('div#'+commentId).html(oldText);
                    button.removeClass("d-none");
                }
                else
                {

                    if(newText.length >= 5 && newText.length <= 800)
                    {
                        $("#editComentAjax-error").remove();
                        editableText.removeClass('is-invalid');
                        $.ajax({
                            type:'POST',
                            dataType: "JSON",
                            url:'../resources/src/scripts/updateComment.php',
                            data: {"idComment": commentId, "cContent": newText},
                            success:function(data){

                                if(data.code == 200){

                                    staticText.text(newText);
                                    $('div#'+commentId).html(staticText);
                                    button.removeClass("d-none");
                                    return false;
                                }
                                else if(data.code == 404){

                                    alert(data.msg);
                                    $("#editComentAjax-error").remove();
                                    $('div#'+commentId).html(oldText);
                                    button.removeClass("d-none");
                                    loadComments(taskId);
                                }
                            },
                            error: function(){
                                alert("error");
                            }
                        });

                    }
                }


                return false;
            });
        });


        // INSERT COMMENT

        //form validation (jQuery Validation plugin)
        commentForm.validate({
            rules: {                         //rules

                //input names and their rules
                cContent: {
                    required: true,
                    minlength: 5,
                    maxlength: 800
                }
            },
            messages: {                      //messages showed when inputs are invalid

                cContent: {
                    required: "Prosím, zadajte komentár.",
                    minlength: "Komentár musí obsahovať minimálne 5 znakov.",
                    maxlength: "Komentár musí obsahovať maximálne 800 znakov."
                }
            },

            submitHandler: function () {

                //if the form is valid works
                if (commentForm.valid()) {

                    //ajax

                    $.ajax({
                        url: '../resources/src/scripts/createComment.php',
                        type: 'POST',
                        dataType: 'JSON',
                        data: commentForm.serialize(),
                        success: function (data) {
                            // This is a callback that runs if the submission was a success.

                        },

                        error: function () {
                            // This is a callback that runs if the submission was not successful.
                        },
                        complete: function () {



                            $("textarea#cContent").val("");

                            //TODO css na transition?
                            //validate enters?????
                            var ta = document.querySelector('#cContent');
                            autosize.update(ta);

                            taskId = $("#showAttachButton").attr("data-id");

                            loadComments(taskId);

                        }
                    });

                    return false;
                }
            }
        });

        //DELETE COMMENT

        $('#deleteCommentModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var deleteId = button.attr('data-id'); // Extract info from data-* attributes

            $("input#idComment").val(deleteId);

            //ak kliknem na tačidlo vymazať
            //TODO - nie cez click ale cez submit?
            $(document).off("click", "#deleteCommentButton").on('click', '#deleteCommentButton', function (e) {
                e.preventDefault();
                $.ajax({
                    url: '../resources/src/scripts/deleteComment.php',
                    method: 'POST',
                    dataType: 'JSON',
                    data: $("#_deleteComment").serialize(),
                    success: function (data) {
                        // This is a callback that runs if the submission was a success.

                        //show some message
                        //showMessages(data.code, data.msg);

                        if(data.code == 200){
                            //alert(data.msg);
                            //hide modal
                            $('#deleteCommentModal').modal('hide');

                            //refresh LOAD DATA
                            loadComments(taskId);
                        }
                        else if(data.code == 404){
                            //alert(data.msg);
                        }



                    },
                    error: function(){
                    }
                });
            });
        });




    });

    /* FUNCTIONS */

    function loadComments(taskId)
    {
        $.ajax({
            url: '../resources/src/scripts/fetchComments.php',
            type: 'POST',
            dataType: 'text',
            data: {"taskId": taskId, "requestType": "all"},
            success: function (response) {
                $("#commentContentAjax").html(response);
                $('[data-toggle="tooltip"]').tooltip();
            },
            error: function () {
                alert("error");
            }

        });


    }

    function loadTaskMembers(taskId)
    {
        $.ajax({
            url: '../resources/src/scripts/fetchUsers.php',
            type: 'POST',
            dataType: 'html',
            data: {"taskId": taskId, "requestType": "task"},
            success: function (response) {
                $("#taskMemberContentAjax").html(response);
                $('[data-toggle="tooltip"]').tooltip();
            },
            error: function () {
                alert("error");
            }

        });


    }

});

<!-- PROJECT MEMBERS -->


$(document).ready(function(){
    var projectId = $("#projectInfo").attr("data-id"); // echoed from PHP

    $('#showMembersModal').on('show.bs.modal', function (event) {

        showAllMembers(projectId);

    });


    $('#m_allButton').on('click', function (event) {

        showAllMembers(projectId);

    });

    $('#m_notAssignedButton').on('click', function (event) {

        showNotAssignedMembers(projectId);

    });

    $('#m_invitedButton').on('click', function (event) {

        showInvitedMembers(projectId);

    });


    $(document).on('click', '.unassignUserProjectAjax', function () {

        var idUnassignMember = $(this).attr("data-id"); // Extract info from data-* attributes
        var projectId = $("#projectInfo").attr("data-id"); // echoed from PHP

        $.ajax({
            url: '../resources/src/scripts/unassignUser.php',
            type: 'POST',
            dataType: 'html',
            data: {"requestType": "showProject"},
            success: function (data) {
                // This is a callback that runs if the submission was a success.

                $("#defaultModalTitle").text('Odobratie užívateľa z projektu');
                $('#defaultModalContentAjax').html(data);
                $('#defaultModal').modal("show");

                $(document).off("click", "#unassignProjectMemberButton").on('click', '#unassignProjectMemberButton', function (e) {   //https://stackoverflow.com/questions/43819886/ajax-sends-request-more-than-once
                    $.ajax({
                        url: '../resources/src/scripts/unassignUser.php',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {"requestType": "unassignProject", "memberId": idUnassignMember, "projectId": projectId},
                        success: function (data) {
                            // This is a callback that runs if the submission was a success.

                            if(data.code === 200){

                                showAllMembers(projectId);

                                $("#defaultModal").modal('hide');
                                //alert(data.msg);
                            }
                            else if(data.code === 404)
                            {
                                alert(data.msg);
                            }
                        },

                        error: function () {
                            // This is a callback that runs if the submission was not successful.

                        }
                    });
                    e.preventDefault();
                });
            },
            error: function () {
                //alert('error');
            }
        });
    });



});

function showAllMembers(projectId)
{
    $.ajax({
        url: '../resources/src/scripts/fetchUsers.php',
        type: 'POST',
        dataType: 'html',
        data: {"requestType": 'all', "projectId": projectId},
        success: function (response) {

            $("#membersContent").html(response);

        },
        error: function () {
            //alert('error');
        }
    });
}

function showNotAssignedMembers(projectId)
{
    $.ajax({
        url: '../resources/src/scripts/fetchUsers.php',
        type: 'POST',
        dataType: 'html',
        data: {"requestType": 'notassigned', "projectId": projectId},
        success: function (response) {

            $("#membersContent").html(response);

        },
        error: function () {
            //alert('error');
        }
    });
}

function showInvitedMembers(projectId)
{
    $.ajax({
        url: '../resources/src/scripts/fetchUsers.php',
        type: 'POST',
        dataType: 'html',
        data: {"requestType": 'invited', "projectId": projectId},
        success: function (response) {

            $("#membersContent").html(response);

        },
        error: function () {
            //alert('error');
        }
    });
}


<!--MEMBER STATISTICS -->


$(document).ready(function () {

    var projectId = $("#projectInfo").attr("data-id"); // echoed from PHP
    var name="člen";

    $('#memberInfoModal').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget);
        var userId = button.attr('data-id'); // Extract info from data-* attributes

        $.ajax({
            url: '../resources/src/scripts/fetchUsers.php',
            type: 'POST',
            dataType: 'json',
            data: {"requestType": 'single', "projectId": projectId, "userId": userId},
            success: function (data) {

                $("#memberNameAjax").html(data.userName);
                name = data.userName;
                $("#memberAvatarAjax").attr('src', 'uploads/users/'+data.userAvatar);
                $("#memberEmailAjax").html(data.userEmail);
                $("#memberAboutAjax").html(data.userAbout);
                $("#memberRegisteredAjax").html(data.userCreated);
                $("#memberAssignedAjax").html(data.userAssigned);

                $.ajax({
                    url: '../resources/src/scripts/fetchTasks.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {"requestType": "memberGraph", "projectId": projectId, "userId": userId},
                    success: function (graphData) {
                        // This is a callback that runs if the submission was a success.

                        var ctx = $("#memberChart");

                        var done = graphData.done;
                        var inProgress = graphData.open;

                        var data = {

                            datasets: [{
                                data: [done, inProgress],
                                backgroundColor: [
                                    '#3ea7db',
                                    '#babcbe'
                                ]
                            }],
                            labels: [
                                "Dokončené úlohy - " +name,
                                "Nedokončené úlohy - " +name
                            ]

                        };

                        var statChart = new Chart(ctx, {
                            type: 'pie',
                            data: data,
                            options: {
                                legend: {
                                    labels: {
                                        fontColor: 'black',
                                        defaultFontFamily: "'Roboto'"

                                    },
                                    position: 'top'
                                },
                                emptyOverlay:{
                                    message: name+" zatiaľ nemá priradené žiadne úlohy",
                                    fillStyle: 'rgba(255,255,255,1.0)',
                                    fontStrokeWidth: 0
                                }
                            }
                        });


                        var ctx2 = $("#doneChart");

                        var doneAll = graphData.doneAll;

                        var data2 = {

                            datasets: [{
                                data: [done, doneAll],
                                backgroundColor: [
                                    '#3ea7db',
                                    '#babcbe'
                                ]
                            }],
                            labels: [
                                "Dokončené úlohy - " +name,
                                "Všetky dokončené úlohy v projekte"
                            ]

                        };

                        var statChart2 = new Chart(ctx2, {
                            type: 'pie',
                            data: data2,
                            options: {
                                legend: {
                                    labels: {
                                        fontColor: 'black',
                                        defaultFontFamily: "'Roboto'"

                                    },
                                    position: 'top'
                                },
                                emptyOverlay:{
                                    message: "Zatiaľ neboli dokončené žiadne úlohy.",
                                    fillStyle: 'rgba(255,255,255,1.0)',
                                    fontStrokeWidth: 0
                                }
                            }
                        });





                    },
                    error: function () {
                        //alert('error');
                    }

                });

                $.ajax({
                    url: '../resources/src/scripts/fetchTimers.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {"requestType": "workTimeGraph", "projectId": projectId, "userId": userId},
                    success: function (graphData) {
                        // This is a callback that runs if the submission was a success.

                        var ctx3 = $("#timeChart");

                        var timeUser = graphData.timeUser;
                        var timeAll = graphData.timeAll;

                        var data3 = {

                            datasets: [{
                                data: [timeUser, timeAll],
                                backgroundColor: [
                                    '#3ea7db',
                                    '#babcbe'
                                ]
                            }],
                            labels: [
                                "Pracovný čas - "+name+" [ "+ graphData.timeUserFormatted +" ]",
                                "Pracovný čas - všetci [ "+ graphData.timeAllFormatted +" ]"
                            ]

                        };

                        var statChart = new Chart(ctx3, {
                            type: 'pie',
                            data: data3,
                            options: {
                                legend: {
                                    labels: {
                                        fontColor: 'black',
                                        defaultFontFamily: "'Roboto'"

                                    },
                                    position: 'top'
                                },
                                emptyOverlay:{
                                    message: "Zatiaľ neboli vytvorené žiadne časovače.",
                                    fillStyle: 'rgba(255,255,255,1.0)',
                                    fontStrokeWidth: 0
                                }
                            }
                        });

                    },
                    error: function () {
                        //alert('error');
                    }

                });

            },
            error: function () {
                //alert('error');
            }
        });



        //task controller -> select graph data projectId + userId
        //task controller -> select graph data projectId + userId + taskStatus = 1  |||||  projecId + taskStatus = 1
        //timer controller -> select work time projectId + created_by + timer_finished = 1 ACCUMULATE INSIDE FOREACH
        //timer controller -> select project work time projectId + timer_finished = 1 ACCUMULATE INSIDE FOREACH
        //timer controller -> select timesheet projectId userId timerfinished = 1 -> require tbl


        loadMemberTimers(projectId, userId);

    });

    function loadMemberTimers(projectId, userId) {
        $.ajax({
            url: '../resources/src/scripts/fetchTimers.php',
            type: 'POST',
            dataType: 'html',
            data: {"requestType": "my", "projectId": projectId, "userId": userId},
            success: function (response) {
                $("#memberTimesheetContent").html(response);
            }
        });
    }
});

<!--TIMERS-->


/* TIMERS */

$(document).ready(function () {

    var projectId = $("#projectInfo").attr("data-id"); // echoed from PHP

    var start = $("#startTimer");
    var stop = $("#stopTimer");
    var submit = $("#stopTimerFormButton");

    var timerStopForm = $("#t_Form");



    // START TIMER

    $(document).on('click', '#startTimer', function () {

        $.ajax({
            url: '../resources/src/scripts/insertTimer.php',
            method: "POST",
            dataType: "json",
            data: {"requestType": "start", "projectId" : projectId},
            success: function (data) {

                switch (data.code)
                {
                    case 200:

                        //reload
                        window.location = window.location;
                        break;

                    case 404:
                        alert(data.msg);

                        break;

                    default:
                        $("#taskActionsAjax").html("Chyba časovača");
                        console.log("error");
                }

            },
            error: function() {

            }

        });

    });

    $(document).on('click','#timeSheetButton', function (event) {
        loadTimers(projectId);
        $("#timersModal").modal('show');
    });

    //CHECK BEFORE LOGOUT

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


    //STOP TIMER
    timerStopForm.validate({  //form validation (jQuery Validation plugin)
        rules: {                         //rules
            timerNote: {
                minlength: 5,
                maxlength: 400
            }
        },
        messages: {                      //messages showed when inputs are invalid
            timerNote: {

                minlength: "Popis musí obsahovať minimálne 5 znakov.",
                maxlength: "Popis musí obsahovať maximálne 400 znakov."
            }
        },

        submitHandler: function () {

            //if the form is valid
            if (timerStopForm.valid()) {
                //ajax

                $.ajax({
                    url: '../resources/src/scripts/insertTimer.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: timerStopForm.serialize()+ '&requestType=stop',       //workaround -> more info insertProject.php
                    success: function (data) {

                        // This is a callback that runs if the submission was a success.

                        //reload
                        window.location = window.location;


                    },

                    error: function () {
                        // This is a callback that runs if the submission was not successful.
                        alert(error);
                    }
                });

                return false;
            }
        }
    });
});

function loadTimers(projectId) {
    $.ajax({
        url: '../resources/src/scripts/fetchTimers.php',
        type: 'POST',
        dataType: 'text',
        data: {"requestType": "all", "projectId": projectId},
        success: function (response) {
            $("#timerListContent").html(response);
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
}



<!--INVITATIONS-->

$(document).ready(function () {



    var iForm = $("#i_Form");

    //form validation (jQuery Validation plugin)
    iForm.validate({
        rules: {                         //rules

            //input names and their rules
            memberEmail: {
                required: true,
                email: true
            },
            iMessage:{
                minlength: 5,
                maxlength: 400
            }
        },
        messages: {                      //messages showed when inputs are invalid
            memberEmail: {
                required: "Prosím, zadajte váš email",
                email: "Zadajte správny tvar emailu napr. meno@priezvisko.sk"
            },
            iMessage:{
                minlength: "Minimálna dĺžka správy musí byť 5 znakov",
                maxlength: "Maximálna dĺžka správy musí byť 400 znakov"
            }
        },

        submitHandler: function () {

            //if the form is valid works
            if (iForm.valid()) {
                //ajax
                $(".memberSpinner").removeClass("d-none");

                $.ajax({
                    url: '../resources/src/scripts/sendInvitation.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: iForm.serialize(),
                    success: function (data) {
                        // This is a callback that runs if the submission was a success.

                        $(".memberSpinner").addClass("d-none");

                        switch (data.code)
                        {
                            case 200:

                                //reload
                                $("#i_Form")[0].reset();
                                $("#i_Form").validate().resetForm();
                                $("em.invalid-feedback").remove();
                                $(".form-control").removeClass("is-invalid");
                                $("#addMembersModal").modal('hide');
                                break;

                            case 404:

                                alert(data.msg);
                                break;

                            default:
                                alert(data.msg);
                                $("#i_Form")[0].reset();
                                $("#i_Form").validate().resetForm();
                                $("em.invalid-feedback").remove();
                                $(".form-control").removeClass("is-invalid");
                                $("#addMembersModal").modal('hide');
                        }
                    },

                    error: function () {
                        // This is a callback that runs if the submission was not successful.
                        $(".memberSpinner").addClass("d-none");
                    }
                });

                return false;
            }
        }
    });
});



<!--ZIP-->

$(document).ready(function () {

    //ZIP files - just control inputs (BACKEND LOGIC)

    var zipForm = $("#_zipAttach");

    //form validation (jQuery Validation plugin)
    zipForm.validate({
        rules: {                         //rules

            //input names and their rules
            'attach[]': {
                required: true,
                minlength: 1
            }
        },
        messages: {                      //messages showed when inputs are invalid

            'attach[]': "Prosím, vyberte aspoň jeden súbor."

        },
        errorPlacement: function (error, element) {
            error.insertAfter("#errZIP");
        }
    });

});


<!--ATTACHEMENTS-->

$(document).ready(function () {

    $('#showAttachButton').on('click', function (event) {

        var button = $(this); // Button that triggered the modal
        var taskId = button.attr('data-id'); // Extract info from data-* attributes
        //$('#deleteAttachTriggerAjax').attr('data-taskid', taskId);

        //todo ako funkciu
        loadAttachData(taskId);
    });

    $('#deleteAttachModal').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget); // Button that triggered the modal
        var deleteId = button.attr('data-id'); // Extract info from data-* attributes
        var name = button.attr('data-name'); // Extract info from data-* attributes
        var dir = button.attr('data-dir'); // Extract info from data-* attributes
        var taskId = button.attr("data-taskid");

        //ak kliknem na tačidlo vymazať
        $(document).off("click", "button#deleteAttachButton").on('click', 'button#deleteAttachButton', function (e) {
            e.preventDefault();
            $.ajax({
                url: '../resources/src/scripts/deleteAttachment.php',
                type: 'POST',
                dataType: 'JSON',
                data: {"id": deleteId, "name": name, "dir": dir},
                success: function (data) {
                    // This is a callback that runs if the submission was a success.

                    //todo show some message
                    //showMessages(data.code, data.msg);

                    loadAttachData(taskId);

                    //hide modal
                    $('#deleteAttachModal').modal('hide');

                },
                error: function () {
                    //alert('error');
                }
            });
        });
    });
});

function loadAttachData(taskId)
{
    $.ajax({
        url: '../resources/src/scripts/fetchAttachments.php',
        type: 'POST',
        dataType: 'text',
        data: {"taskId": taskId, "requestType": "all"},
        success: function (response) {
            $("#attachmentListContent").html(response);
        },
        error: function () {
            alert("error");
        }

    });
}




//upload attachment
$(document).ready(function () {
    var attachForm = $("#_attachForm");

    attachForm.on('submit', function (e) {
        e.preventDefault();
        $(".uploadSpinner").removeClass("d-none");

        $.ajax({
            url: '../resources/src/scripts/uploadAttachment.php',
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
                    // $newPath = 'uploads/users/'+data.msg;
                    // $("img.avatarImg").attr("src",$newPath);
                    // $("input#oldAvatar").val(data.msg);

                    $("#addAttachmentModal").modal('hide');
                    // //TODO vynulovať file input

                    $(".uploadSpinner").addClass("d-none");
                    alert(data.msg);
                }
                else{
                    alert(data.msg);
                    $(".uploadSpinner").addClass("d-none");
                }

                return false;
            },

            error: function () {
                // This is a callback that runs if the submission was not successful.
                alert("error");
            }
        });
    });
});

<!--TASK MODAL-->



$(document).ready(function () {

    //reset overdue color class
    $('#taskModal').on('hidden.bs.modal', function () {

        //TODO overdue neviem či tam dám :D
        $("#overdueTaskAjax").removeClass("overDue");

        //reset COMMENT form
        $("#_commentForm")[0].reset();
        $('#_commentForm').validate().resetForm();
        $("em.invalid-feedback").remove();
        $(".form-control").removeClass("is-invalid");
        $("#startButton").removeClass("d-block").addClass("d-none");
        $("#completeButton").removeClass("d-block").addClass("d-none");

    });

    $(document).on('click', '#completeButton', function () {

        //works
        var id = $('#completeButton').attr("data-id"); // Extract info from data-* attributes


        $.ajax({
            url: '../resources/src/scripts/completeTask.php',
            type: 'POST',
            dataType: 'JSON',
            data: {"id": id},
            success: function (data) {
                // This is a callback that runs if the submission was a success.


                if(data.code == 200){

                    $('.taskButtonAjax[data-taskid="'+id+'"] > div.d-flex > div.statusDone').removeClass("d-none");
                    $("#completeButton").removeClass("d-block").addClass("d-none");
                    $("#completeTitle").removeClass("d-none").addClass("d-block");
                    $("#taskCompletedAjax").text(data.time);

                    var p_Id = $("#projectInfo").attr("data-id"); // echoed from PHP

                    loadTasks(p_Id);

                }
                else if(data.code == 404)
                {
                    alert(data.msg);
                }



            },
            error: function () {
                //alert('error');
            }

        });
    });

    $(document).on('click', '#startButton', function () {

        var id = $('#startButton').attr("data-id"); // Extract info from data-* attributes

        $.ajax({
            url: '../resources/src/scripts/startTask.php',
            type: 'POST',
            dataType: 'JSON',
            data: {"id": id},
            success: function (data) {
                // This is a callback that runs if the submission was a success.

                if(data.code == 200){


                    $("#completeButton").removeClass("d-none").addClass("d-block");
                    $("#startButton").removeClass("d-block").addClass("d-none");
                    //$("#taskCompletedAjax").text(data.time);

                    var p_Id = $("#projectInfo").attr("data-id"); // echoed from PHP

                    loadTasks(p_Id);

                }
                else if(data.code == 404)
                {
                    alert(data.msg);
                }



            },
            error: function () {
                //alert('error');
            }

        });
    });

});








$(document).ready(function () {
    //close modal changeAvatarModal -> show profile modal
    $('#addMembersModal').on('hidden.bs.modal', function () {
        $("#projectInfoModal").modal('show');
    });

    $('#addMembersModal').on('show.bs.modal', function () {
        $("#projectInfoModal").modal('hide');
    });

    //close modal changeAvatarModal -> show profile modal
    $('#addAttachmentModal').on('hidden.bs.modal', function () {
        $("#taskModal").modal('show');
    });

    $('#addAttachmentModal').on('show.bs.modal', function () {
        $("#taskModal").modal('hide');
    });


    /* ATTACHEMENTS */

    $('#showAttachmentModal').on('hidden.bs.modal', function () {
        $("#taskModal").modal('show');
    });

    $('#showAttachmentModal').on('show.bs.modal', function () {
        $("#taskModal").modal('hide');
    });

    /*TIMERS*/

    $('#timersModal').on('hidden.bs.modal', function () {
        $("#projectInfoModal").modal('show');
    });

    $('#timersModal').on('show.bs.modal', function () {
        $("#projectInfoModal").modal('hide');
    });

    /*MEMBERS*/

    $('#memberInfoModal').on('hidden.bs.modal', function () {
        $("#showMembersModal").modal('show');
    });

    $('#showMembersModal').on('show.bs.modal', function () {
        $("#projectInfoModal").modal('hide');
    });

    $('#memberInfoModal').on('show.bs.modal', function () {
        $("#showMembersModal").modal('hide');
    });



});




//reset form after close modal
$(document).ready(function () {
    //on close modal
    $('#newTaskModal').on('hidden.bs.modal', function () {
        //RESET FORM
        $('#_task')[0].reset();
        //erase value of hidden input || must be here if modal was only close (not send)
        $("#id").val("");

        $('#_task').validate().resetForm();
        //delete error classes
        $("em.invalid-feedback").remove();
        $(".form-control").removeClass("is-invalid");

        $(".datepickerStart").flatpickr({

            altInput: true,
            // enableTime: true,        //throws errors when using with bootstrap modal see more -> https://github.com/ankurk91/vue-flatpickr-component/issues/63
            // time_24hr: true,
            altFormat: "d. F Y",
            dateFormat: "Y-m-d",
            disableMobile: "true", //must be
            defaultDate: "today"
        });

        $("#taskPriority").slider('setValue', 1);
        $("#currentPriority").text(1);

        $("#newTaskModalTitle").text('Nová úloha');  //zmeni to všetky modaly?
        $("#createTaskButton").text("Vytvoriť");
    });
});


$(document).ready(function () {
    var taskForm = $("#_task");

    //UPDATE | INSERT

    //form validation (jQuery Validation plugin)
    taskForm.validate({
        rules: {                         //rules

            //input names and their rules
            taskName: {
                required: true,
                minlength: 2,
                maxlength: 50
            },
            taskDescription: {
                required: true,
                minlength: 10,
                maxlength: 400
            },
            taskPriority: {
                required: true

            },
            taskDueDate: {
                required: true

            }
        },
        messages: {                      //messages showed when inputs are invalid
            taskName: {
                required: "Prosím, zadajte meno klienta.",
                minlength: "Názov musí obsahovať minimálne 2 znaky.",
                maxlenght: "Názov musí obsahovať minimálne 50 znakov."
            },
            taskDescription: {
                required: "Prosím, zadajte popis projektu.",
                minlength: "Popis musí obsahovať minimálne 10 znakov.",
                maxlength: "Popis musí obsahovať maximálne 400 znakov."
            },
            taskPriority: {
                required: "Prosím, zvoľte prioritu úlohy."

            },
            taskDueDate: {
                required: "Prosím, zvoľte plánované ukončenie úlohy."

            }
        },

        submitHandler: function () {

            //if the form is valid works
            if (taskForm.valid()) {
                //ajax

                $.ajax({
                    url: '../resources/src/scripts/insertTask.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: taskForm.serialize(),
                    success: function (data) {
                        // This is a callback that runs if the submission was a success.

                        //show some message
                        //showMessages(data.code, data.msg);

                        //hide modal

                        if(data.code == 200){
                            $("#newTaskModal").modal('hide');
                        }

                        var id = $("#projectInfo").attr("data-id"); // echoed from PHP

                        loadTasks(id);

                        //alert(data.msg);

                    },

                    error: function () {
                        // This is a callback that runs if the submission was not successful.

                    }
                });

            }
        }
    });


    //on hidden clear input
    $('#newTaskModal').on('hidden.bs.modal', function () {

        //reset COMMENT form
        $("#_task")[0].reset();
        $('#_task').validate().resetForm();
        $('input#idTask').val("");
        $("em.invalid-feedback").remove();
        $(".form-control").removeClass("is-invalid");

        $(".datepickerStart").flatpickr({

            altInput: true,
            // enableTime: true,        //throws errors when using with bootstrap modal see more -> https://github.com/ankurk91/vue-flatpickr-component/issues/63
            // time_24hr: true,
            altFormat: "d. F Y",
            dateFormat: "Y-m-d",
            disableMobile: "true", //must be
            defaultDate: "today"
        });



    });



    //TODO SELECT AND SHOW ON MODAL OPEN

    //SELECT AND ShOW works
    $(document).on('click', '#updateTask', function () {

        var id = $(this).attr("data-id"); // Extract info from data-* attributes


        $.ajax({
            url: '../resources/src/scripts/fetchTasks.php',
            type: 'POST',
            dataType: 'JSON',
            data: {"id": id, "requestType": "single"},
            success: function (data) {
                // This is a callback that runs if the submission was a success.


                $("#newTaskModalTitle").text('Úprava úlohy');  //zmeni to všetky modaly?

                //add value to hidden input with id="id"
                $("#idTask").val(data.id);

                $("#createTaskButton").text("Upraviť");

                $('#taskName').val(data.taskName);
                $('#taskDescription').val(data.taskDescription);

                //$('#taskPriority').val(data.taskPriority);
                $("#taskPriority").slider('setValue', data.taskPriority);
                $("#currentPriority").text(data.taskPriority);
                //$('#taskPriority').attr('data-value', data.taskPriority);
                //$('#taskPriority').attr('data-slider-value', data.taskPriority);

                $('#taskDueDate').val(data.taskDueDate);

                //TODO: vyznačiť členov- ako?

                //show correct date

                $(".datepickerStart").flatpickr({

                    altInput: true,
                    // enableTime: true,        //throws errors when using with bootstrap modal see more -> https://github.com/ankurk91/vue-flatpickr-component/issues/63
                    // time_24hr: true,
                    altFormat: "d. F Y",
                    dateFormat: "Y-m-d",
                    disableMobile: "true", //must be
                    defaultDate: data.taskDueDate

                });

                //SHOW
                $('#newTaskModal').modal("show");

                $('#newTaskModal').on('shown.bs.modal', function () {
                    var ta = document.querySelector('#taskDescription');
                    autosize.update(ta);
                });
            },
            error: function () {
                //alert('error');
            }

        });
    });


    //DELETE TASK

    //keď kliknem na button delete -> zobrazí sa modal a ulozi sa id projektu z atributu data-id

    $('#deleteTaskModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var deleteId = button.attr('data-id'); // Extract info from data-* attributes
        var projectId = $("#projectInfo").attr("data-id"); // echoed from PHP

        //todo off on
        $("input#idDelete").val(deleteId);

        //ak kliknem na tačidlo vymazať
        $(document).off("click", "#deleteTaskButton").on('click', '#deleteTaskButton', function (e) {
            e.preventDefault();

            $.ajax({
                url: '../resources/src/scripts/deleteTask.php',
                method: 'POST',
                dataType: 'JSON',
                data: $("#_deleteTask").serialize(),
                success: function (data) {
                    // This is a callback that runs if the submission was a success.

                    //show some message
                    //showMessages(data.code, data.msg);

                    if(data.code == 200){
                        //alert(data.msg);
                        //hide modal
                        $('#deleteTaskModal').modal('hide');
                        $('#taskModal').modal('hide');

                        loadTasks(projectId);
                    }
                    else if(data.code == 404){
                        alert(data.msg);
                    }



                },
                error: function(){
                }
            });
        });
    });
});



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
    // $(".notificationLink").click(function () {
    //     $(".badge.up").css({"display": "none"});
    // });

    $("#hideMainSidebar").click(function () {
        $("#mainSidebar").toggleClass('hidden');
        $("#expanded").toggleClass('d-none');
        $("#expandedProjectName").toggleClass('d-none');
        $("#expandIcon").toggleClass('d-none');
        $("#compressIcon").toggleClass('d-none');
        $("#mainContent").toggleClass('marged customWidth');
        $("#listsContentAjax").toggleClass('expandedHeight');
        $("#expandCreateTaskButton").toggleClass('d-none');

    });


    //on show bootstrap modal - hide mobile side menu
    $('.modal').on('shown.bs.modal', function () {
        $("#mobileSideMenu").css({"left": "-100vw", "box-shadow": "none"});
        $("#mobileSideMenuBg").css({"visibility": "hidden", "background-color": "rgba(0,0,0,0"});
    });

});




//toto do app.js GENERAL
flatpickr.localize(flatpickr.l10ns.sk);

$(document).ready(function () {
    $(".datepickerStart").flatpickr({

        altInput: true,
        // enableTime: true,
        // time_24hr: true,
        altFormat: "d. F Y",
        dateFormat: "Y-m-d",
        disableMobile: "true", //must be
        defaultDate: new Date()

    });
});



//custom file input
$(document).ready(function () {
    bsCustomFileInput.init()
});


<!--END OF GENERALLLLLLL-->