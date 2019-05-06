<?php if(!empty($members)): ?>
    <form role="form" method="post" id="_taskMember" action="">
        <div class="modal-body">
            <div class="form-group putError">
<!--                <label for="members"><span class="text-uppercase formLabel required">Člen</span></label>-->
                <select data-selected-text-format="count" class="selectpicker form-control" data-style="" data-width="100%" data-header="Vyberte člena" multiple data-live-search="true" name="members[]" id="members">
                    <?php foreach ($members as $member): ?>

                                    <option value="<?php echo $uController->sanitizeNumber($member["id_users"]); ?>" title="<?php echo $uController->checkOutput($member["user_name"]); ?>" data-tokens="<?php echo $uController->checkOutput($member["user_name"]); ?>" data-content='<img style="border-radius: 50%;" src="uploads/users/<?php echo $uController->checkOutput($member["user_avatar"]); ?>" width="30" height="30"><span class="ml-2"><?php echo $uController->checkOutput($member["user_name"]); ?></span><small><?php echo $uController->sanitizeEmail($member["user_email"]); ?></small>'><?php echo $uController->checkOutput($member["user_name"]); ?></option>

                    <?php endforeach; ?>
                    <input type="hidden" name="taskId" id="taskId">
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn" type="button" data-dismiss="modal">Zavrieť</button>
            <button class="btn" type="submit"><i class="fas fa-user-plus"></i> Priradiť</button>
        </div>
    </form>


<?php else: ?>
    <div class="my-3">
        <p>Žiadni členovia sa nenašli.</p>
    </div>
<?php endif; ?>