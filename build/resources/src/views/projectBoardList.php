<?php if(!empty($allProjects)): ?>
    <?php $counter = 1; $today = (new \DateTime())->format('Y-m-d H:i:s'); ?>

    <div class="table-responsive">
        <table id="projectTable" class="table table-hover">
            <thead>
            <tr class="projectCategory">
                <th scope="col" class="d-none d-md-table-cell">#</th>
                <th scope="col">Názov</th>
                <th scope="col" class="d-none d-lg-table-cell">Kategória</th>
                <th scope="col" class="d-none d-md-table-cell">Klient</th>
                <th scope="col" class="d-none d-md-table-cell">Uzávierka</th>
                <th scope="col">Status</th>
                <th scope="col" class="d-none d-xl-table-cell">Členovia</th>
                <th scope="col">Akcie</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($allProjects as $project): ?>
                <?php $new_datetime = DateTime::createFromFormat ( "Y-m-d H:i:s", $fetchProjectController->checkOutput($project['project_end']) );

                      $graphData = $fetchController->selectGraphData($project["id"]);

                      $open = $fetchController->sanitizeNumber( $graphData["open"]);
                      $done = $fetchController->sanitizeNumber( $graphData["done"]);

                      $percentage = $fetchProjectController->formatToPercentage($done, ($done + $open));
                  ?>
                <tr class="projectCard">
                    <th scope="row" class="d-none d-md-table-cell"><?php echo $counter?></th>
                    <td class="projectName">
                        <div class="pb-2"><strong><a style="overflow-wrap: break-word;" href="task-board?project=<?php echo $fetchProjectController->sanitizeNumber($project['id'])?>"><?php echo $fetchProjectController->checkOutputLight($project['project_name'])?></a></strong></div>
                        <!--                                        <div class="list-unstyled projectTags d-none d-lg-block">-->
                        <!--                                            <span class="text-wrap badge badge-dark">wireframe</span>-->
                        <!--                                            <span class="text-wrap badge badge-dark">ux</span>-->
                        <!--                                            <span class="text-wrap badge badge-dark">ui</span>-->
                        <!--                                            <span class="text-wrap badge badge-dark">adobe xd</span>-->
                        <!--                                        </div>-->
                    </td>
                    <td class="text-wrap d-none d-lg-table-cell projectCategory"><?php echo $fetchProjectController->checkOutput($project['project_category'])?></td>
                    <td class="d-none d-md-table-cell projectClient"><span
                            class="text-wrap badge badge-primary"><?php echo $fetchProjectController->checkOutput($project['project_client'])?></span></td>
                    <td class="d-none d-md-table-cell projectDeadline"><span
                            class="text-wrap badge badge-<?php echo ($project['project_end'] < $today ?  "danger" : "success") ?>"><?php echo $new_datetime->format('d/m/Y');?></span></td>
                    <td class="project Status">
                        <span class="text-wrap badge badge-<?php echo ($project['project_status'] == "Dokončený" ?  "success" : "primary") ?>"><?php echo $fetchProjectController->checkOutput($project['project_status'])?></span>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: <?php echo $percentage; ?>%;" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100"><span class="ml-1"><?php echo $percentage; ?> %</span></div>
                        </div>
                    </td>
                    <td class="d-none d-xl-table-cell projectMembers">

                        <?php
                            $members = $fetchProjectController->selectProjectUsers($fetchProjectController->sanitizeNumber($project['id']));

                            if(!empty ($members)):
                                foreach ($members as $member):
                        ?>
                        <img src="uploads/users/<?php echo $fetchProjectController->checkOutput($member["user_avatar"]); ?>" class="rounded-circle" alt="" width="24" height="24" data-toggle="tooltip" data-offset="20" data-arrow="disabled" data-placement="bottom" title="<?php echo $fetchProjectController->checkOutput($member["user_name"]); ?>">

                        <?php
                                endforeach;
                            endif;
                        ?>

                    </td>
                    <td class="Actions">
                        <?php
                            if($project["project_author"] == $_SESSION["user"]["id"])
                            {
                        ?>
                                <a href="javascript:void(0)" class="rounded-circle edit btn-edit" data-id="<?php echo $fetchProjectController->sanitizeNumber($project['id'])?>"><i class="far fa-edit"></i></a>
                                <a href="javascript:void(0)" class="rounded-circle trash bn-delete" data-toggle="modal"
                                   data-target="#deleteProjectModal" data-id="<?php echo $fetchProjectController->sanitizeNumber($project['id'])?>"><i class="far fa-trash-alt "></i></a>
                        <?php
                            }
                        ?>

                    </td>
                </tr>
                <?php $counter++; ?>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>

    <div id="anyProjectText" class="text-center d-lg-flex align-items-lg-center justify-content-lg-center">
        <h6>Neboli nájdené žiadne projekty.</h6>
    </div>
<?php  endif; ?>