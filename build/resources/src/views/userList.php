<table class="table table-bordered">
    <thead>
        <tr class="">
            <th scope="col">#</th>
            <th scope="col"></th>
            <th scope="col">Meno</th>
            <th scope="col">Email</th>
<!--            <th scope="col" class="text-center"><i class="fas fa-cogs"></i></th>-->
        </tr>
    </thead>
    <tbody>
    <?php if(!empty($members)): ?>
        <?php $i = 1; ?>
        <?php foreach ($members as $member): ?>
            <tr class="">
                <th><?php echo $i;?></th>
                <td class="d-flex justify-content-center">
                    <img src="uploads/users/<?php echo $uController->checkOutput($member["user_avatar"]);?>" class="rounded-circle profile" alt="" width="30" height="30">
                </td>
                <td><a href="javascript: void(0)" data-toggle="modal" data-target="#memberInfoModal" data-id="<?php echo $uController->sanitizeNumber($member["id_users"]);?>"><?php echo $uController->checkOutput($member["user_name"]);?></a></td>
                <td><?php echo $uController->sanitizeEmail($member["user_email"]);?></td>
<!--                <td class="text-center">-->
<!--                    --><?php //if($project[0]["project_author"] == $_SESSION["user"]["id"] && $member["id_users"] != $_SESSION["user"]["id"] ): ?>
<!--                    <a href="javascript: void(0)" class="p-2 unassignUserProjectAjax" data-id="--><?php //echo $uController->checkOutput($member["id_users"]);?><!--"><i class="fas fa-user-times"></i></a>-->
<!--                    --><?php //endif; ?>
<!--                </td>-->
            </tr>
        <?php $i++; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5" class="text-center">
                <h6 class="my-2">Neboli nájdené žiadne záznamy.</h6>
            </td>
        </tr>
    <?php  endif; ?>
    </tbody>
</table>
