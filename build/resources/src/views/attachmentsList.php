<?php if(!empty($allAttachements)): ?>

    <div class="modal-body">
        <table class="table table-bordered" id="errZIP">
            <thead>
            <tr class="">
                <th scope="col"></th>
                <th scope="col" class="">Názov</th>
                <th scope="col" class="">Akcie</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($allAttachements as $attachement): ?>
                <tr class="">
                    <td style="border: none; border-top: 1px solid #dee2e6;" class="d-flex justify-content-center align-items-center"><input type="checkbox" class="form-check" name="attach[]" value="<?php echo $fetchAttachController->checkOutput($attachement['attach_name']) ?> " id=""></td>
                    <td class="">
                        <div class="d-flex justify-content-start align-items-center">
                            <span class="mr-2" data-toggle="tooltip" data-placement="bottom" title="Poznamka"><i class="fas fa-comment-alt"></i></span>
                            <p class="attachName m-0 p-0"><?php echo $fetchAttachController->checkOutput($attachement['attach_name_orig']) ?></p>
                        </div>
                    </td>
                    <td class="Actions">
                        <a href="../resources/src/scripts/download?id=<?php echo $fetchAttachController->sanitizeNumber($attachement['id_attachements'])?>&name=<?php echo $fetchAttachController->checkOutput($attachement['attach_name']) ?>&dir=<?php echo $fetchAttachController->checkOutput($attachement['attach_dir']) ?>" class="rounded-circle edit btn-edit" data-id="" data-name=" " data-dir=""><i class="far fa-save"></i></a>
                        <?php if(($attachement['uploaded_by'] == $_SESSION["user"]["id"]) || ($attachement['uploaded_by'] == $_SESSION["user"]["user_type"]))
                        {
                            ?>
                            <button type="button" title="Vymazať" id="deleteAttachTriggerAjax" class="rounded-circle trash bn-delete btnReset" data-toggle="modal" data-target="#deleteAttachModal" data-id="<?php echo $fetchAttachController->sanitizeNumber($attachement['id_attachements'])?>" data-name="<?php echo $fetchAttachController->checkOutput($attachement['attach_name']) ?> " data-dir="<?php echo $fetchAttachController->checkOutput($attachement['attach_dir']) ?>" data-taskid="<?php echo $taskId?>"><i class="far fa-trash-alt"></i></button>

                            <?php
                        }?>

                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button class="btn btn-default" data-dismiss="modal">Zavrieť</button>
        <button class="btn" type="submit" name="createZIP" id="createZIPButton">
            <span class="spinner-border spinner-border-sm d-none spinnerZIP" role="status" aria-hidden="true"></span>
            <i class="far fa-file-archive"></i>
            <span class="ml-2">Stiahnuť ZIP archív</span>
        </button>
    </div>
<?php else: ?>
    <div id="anyAttachementText" class="text-center d-lg-flex align-items-lg-center justify-content-lg-center py-5">
        <h6>Neboli nájdené žiadne prílohy.</h6>
    </div>
<?php  endif; ?>