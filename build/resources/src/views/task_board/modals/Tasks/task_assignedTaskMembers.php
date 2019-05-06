<?php if(!empty($members)): ?>
    <?php foreach ($members as $member): ?>
        <li>
            <div class="dropleft">
                <a class="p-1" href="#" role="button" id="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="uploads/users/<?php echo $uController->checkOutput($member["user_avatar"]); ?>" class="rounded-circle profile" alt="" width="36" height="36" data-toggle="tooltip" data-placement="bottom" title="<?php echo $uController->checkOutput($member["user_name"]); ?>">

                </a>
                <div class="dropdown-menu" aria-labelledby="">
                    <a class="dropdown-item unassignUserTaskAjax" href="javascript: void(0)" data-id="<?php echo $uController->checkOutput($member["id_users"]); ?>"> Odobrať</a>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
<?php else: ?>
    <div class="my-3">
        <p>Nepriradená úloha.</p>
    </div>
<?php endif; ?>
