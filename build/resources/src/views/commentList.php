<?php if(!empty($allComments)): ?>

    <?php foreach ($allComments as $comment): ?>

        <?php
            $user = $userController->selectProfile($userController->sanitizeNumber($comment['created_by']));
        ?>

        <li class="media">

            <div class="media-body my-0" style="overflow-x: auto;">

                <div class="d-flex justify-content-between mb-2 align-items-center parentSelector">
                    <div>
                        <img src="uploads/users/<?php echo $user[0]["user_avatar"];?>"
                             class="rounded-circle mr-2 align-self-start" alt="" width="30" height="30" data-toggle="tooltip" data-placement="bottom"
                             title="<?php echo $user[0]["user_name"];?>">
                        <small class="need_to_be_rendered mt-0 mb-1"><?php echo (new DateTime($fetchCommentController->checkOutput($comment["created_time"])))->format("d / m / Y, H:i");?></small>
                    </div>
                    <?php if(($comment['created_by'] == $_SESSION["user"]["id"]) || ($comment['created_by'] == $_SESSION["user"]["user_type"])): ?>

                    <div>
                        <button data-id="<?php echo $fetchCommentController->sanitizeNumber($comment['id_comments'])?>" type="button" class="action btnReset editComment" role="button" data-toggle="tooltip" data-placement="bottom" title="Upraviť"><i class="far fa-edit"></i></button>
                        <button data-id="<?php echo $fetchCommentController->sanitizeNumber($comment['id_comments'])?>" type="button" class="action btnReset" role="button" title="Vymazať" data-toggle="modal" data-target="#deleteCommentModal"><i class="far fa-trash-alt"></i></button>
                    </div>

                    <?php endif; ?>
                </div>
                <div class="commentBox p-3">
                    <div class="editCommentBox flex-wrap putError" style="overflow-wrap: break-word;" id="<?php echo $fetchCommentController->sanitizeNumber($comment['id_comments'])?>">
                        <p style="white-space: pre-line; padding: .375rem .75rem;" class="m-0" id="<?php echo $fetchCommentController->sanitizeNumber($comment['id_comments'])?>"><?php echo $fetchCommentController->checkOutput($comment["comment_content"]); ?></p>
                    </div>
                </div>
            </div>
        </li>
        <hr class="mt-4 mb-4">
    <?php endforeach; ?>
<?php else: ?>
    <li id="anyCommentText" class="text-center d-lg-flex align-items-lg-center justify-content-lg-center py-5">
        <h6>Neboli nájdené žiadne komentáre.</h6>
    </li>
<?php  endif; ?>