<?php if(!empty($allTimers)): ?>
    <table class="table table-bordered text-left">
        <thead>
            <tr class="">
                <th scope="col">Člen</th>
                <th scope="col">Pozn.</th>
                <th scope="col" class="d-none d-md-table-cell">Začiatok</th>
                <th scope="col" class="d-none d-md-table-cell">Koniec</th>
                <th scope="col" class="text-right">Trvanie</th>
            </tr>
        </thead>
        <tbody>

        <?php $total = 0; ?>
    <?php foreach ($allTimers as $timer): ?>

        <?php

            $datetime1 = DateTime::createFromFormat ( "Y-m-d H:i:s", $timerController->checkOutput($timer['timer_start']) );
            $datetime2 = DateTime::createFromFormat ( "Y-m-d H:i:s", $timerController->checkOutput($timer['timer_stop']) );
//            $interval = $datetime1->diff($datetime2);
//            $elapsed = $interval->format('%h hod. %i min. %s sek.');

            $dt1 = $datetime1 ->getTimestamp();
            $dt2 = $datetime2 ->getTimestamp();
            $td= $dt2 - $dt1;

            $total += $td;
        ?>


        <tr>

            <td class="">
                <?php if(isset($timer["user_avatar"])): ?>
                <div class="d-flex justify-content-start align-items-center">
                    <img src="uploads/users/<?php echo $timerController->checkOutput($timer["user_avatar"]); ?>" class="rounded-circle profile" alt="" width="30" height="30" data-toggle="tooltip" data-placement="bottom" title="<?php echo $timerController->checkOutput($timer["user_name"]); ?>">
                </div>
                <?php endif; ?>
            </td>

            <td class="text-center" style="width: 90px;">
                <span data-toggle="tooltip" data-placement="bottom" title="<?php echo $timerController->checkOutput($timer["timer_description"]); ?>">
                    <?php echo ($timer['timer_description'] == "Bez poznámky." ?  '<i class="far fa-comment"></i>' : '<i class="fas fa-comment"></i>') ?>
                </span></td>
            <td class="d-none d-md-table-cell"><?php echo (\DateTime::createFromFormat("Y-m-d H:i:s",$timerController->checkOutput($timer['timer_start'])))->format('d. F Y, H:i:s'); ?></td>
            <td class="d-none d-md-table-cell"><?php echo (\DateTime::createFromFormat("Y-m-d H:i:s",$timerController->checkOutput($timer['timer_stop'])))->format('d. F Y, H:i:s'); ?></td>
            <td class="text-right"><?php echo $timerController->parseTime($td); ?></td>
        </tr>
    <?php endforeach; ?>
        <tr class="">
            <td class="d-none d-md-table-cell"></td>
            <td class="d-none d-md-table-cell"></td>
            <td></td>
            <th>Celkovo</th>
            <th class="text-right"><?php echo $timerController->parseTime($total); ?></th>
        </tr>

        </tbody>
    </table>

<?php else: ?>
    <div id="anyTimerText" class="text-center d-lg-flex align-items-lg-center justify-content-lg-center py-5">
        <h6>Neboli nájdené žiadne záznamy.</h6>
    </div>
<?php  endif; ?>