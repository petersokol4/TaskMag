<?php

    if(!empty($columns)):
    foreach ($columns as $column):

    $tasks =  $tController->selectColumnTasks($cController->sanitizeNumber($column["id_columns"]), $p_Id);
    $tasksCount = count($tasks);
    $columnLimit = $cController->sanitizeNumber($column["column_limit"]);
?>

<div class="list <?php echo (( $columnLimit < $tasksCount) && ( $columnLimit != 0) ?  'overLoad' : '') ?>" data-columnid="<?php echo $cController->sanitizeNumber($column["id_columns"]); ?>" data-limit="<?php echo $columnLimit; ?>">
    <header class="listHeader d-flex align-items-center justify-content-between bg-<?php echo $cController->checkOutput($column["column_color"]); ?>">
        <div class="listIcon p-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-tasks"></i>
        </div>
        <div class="dropdown-menu">
            <a class="dropdown-item updateColumn" href="javascript:void(0)" data-id="<?php echo $cController->sanitizeNumber($column["id_columns"]); ?>" >Upraviť</a>
            <a class="dropdown-item moveColumnTasks" href="javascript: void(0)" data-id="<?php echo $cController->sanitizeNumber($column["id_columns"]); ?>">Presunúť úlohy</a>
<!--            <a class="dropdown-item" href="#">Zoradiť</a>-->
            <div class="dropdown-divider"></div>
            <a class="dropdown-item deleteColumn" href="javascript: void(0)" data-id="<?php echo $cController->sanitizeNumber($column["id_columns"]); ?>">Vymazať</a>
        </div>
        <h6 class="mb-0 text-break listName"><?php echo $cController->checkOutput($column["column_title"]); ?></h6>
        <h6 class="mb-0 p-3 taskCountAjax"><?php echo $cController->sanitizeNumber($tasksCount); ?></h6>
    </header>
    <div class="listBox" data-simplebar>
        <ul id="sort1" class="list-unstyled sortable" data-columnid="<?php echo $cController->sanitizeNumber($column["id_columns"]); ?>">

              <?php
                if (! empty($tasks)) {
                    foreach ($tasks as $task)
                    {


              ?>


                <li data-toggle="modal" data-target="#taskModal" style="list-style: none; background-color: #ffffff; padding: 15px; margin-bottom: 15px; border-radius: 5px; box-shadow: 0 2px 2px rgba(0, 0, 0, 0.2);" data-taskid="<?php echo $tController->sanitizeNumber($task["id_tasks"]); ?>" class="taskButtonAjax">
                    <div class="d-flex justify-content-between flex-warp">
                        <div class="taskListText">
                            <p><?php echo $tController->checkOutput($task["task_name"]); ?></p>
                        </div>

                        <?php
                        if($task["task_status"] == 1)
                        {
                            ?>

                            <div class="statusDone taskListStatus ml-2" style="color: #4eba6f;">
                                <span class=""><i class="far fa-check-circle"></i></span>
                            </div>

                            <?php
                        }

                        else if($task["task_due_date"] < (new \DateTime())->format('Y-m-d H:i:s'))
                        {
                            ?>

                            <div class="statusOverDue taskListStatus ml-2" style="color: #e54141;">
                                <span class=""><i class="fas fa-exclamation-circle"></i></span>
                            </div>

                            <?php
                        }
                        ?>


                    </div>
                    <!--                                <div class="d-flex justify-content-between align-items-center flex-warp">-->
                    <!--                                    <div class="d-flex align-items-center">-->
                    <!--                                        <img src="uploads/users/profilePic.jpg" class="rounded-circle profile" alt=""-->
                    <!--                                             width="20" height="20" data-toggle="tooltip" data-placement="bottom"-->
                    <!--                                             title="Andrea">-->
                    <!--                                        <img src="uploads/users/profilePic.jpg" class="rounded-circle profile" alt=""-->
                    <!--                                             width="20" height="20" data-toggle="tooltip" data-placement="bottom"-->
                    <!--                                             title="Andrea">-->
                    <!--                                        <img src="uploads/users/profilePic.jpg" class="rounded-circle profile" alt=""-->
                    <!--                                             width="20" height="20" data-toggle="tooltip" data-placement="bottom"-->
                    <!--                                             title="Andrea">-->
                    <!--                                        <img src="uploads/users/profilePic.jpg" class="rounded-circle profile" alt=""-->
                    <!--                                             width="20" height="20" data-toggle="tooltip" data-placement="bottom"-->
                    <!--                                             title="Andrea">-->
                    <!--                                    </div>-->
                    <!--                                    <div class="d-flex justify-content-between flex-warp"-->
                    <!--                                         style="color: #BABCBE; font-size: 14px;">-->
                    <!--                                        <div class="taskListStat mr-1">-->
                    <!--                                            <i class="fas fa-clipboard-check"></i><span class="ml-1">0/0</span>-->
                    <!--                                        </div>-->
                    <!--                                        <div class="taskListStat mr-1">-->
                    <!--                                            <i class="fas fa-paperclip"></i><span class="ml-1">4</span>-->
                    <!--                                        </div>-->
                    <!--                                        <div class="taskListStat mr-1">-->
                    <!--                                            <i class="fas fa-comments"></i><span class="ml-1">2</span>-->
                    <!--                                        </div>-->
                    <!--                                    </div>-->
                    <!--                                </div>-->
                </li>
            <?php
                }
            }
            ?>
        </ul>
    </div>
</div>

<?php endforeach; ?>

<?php endif; ?>
<div class="ml-2" style="font-size: 20px">
    <a class="p-3" href="#" role="button" title="Pridať stĺpec" data-toggle="modal" data-target="#addColumnModal"><i class="far fa-plus-square"></i></a>
</div>