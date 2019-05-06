<?php if(!empty($columns)): ?>
<form role="form" method="post" id="_columnMove" action="">
    <div class="modal-body">
        <div class="form-group putError">
            <label for="newColumnId"><span class="text-uppercase formLabel required">Stĺpec</span></label>
            <select id="newColumnId" name="newColumnId" class="custom-select" aria-describedby="zmena-stĺpca">
                <option disabled selected value=""> -- Zvoľte stĺpec --</option>
                <?php foreach ($columns as $column): ?>
                    <option value="<?php echo $cController->sanitizeNumber($column["id_columns"]); ?>"><?php echo $cController->checkOutput($column["column_title"]); ?></option>
                <?php endforeach; ?>
                <input type="hidden" name="columnId" id="columnId">
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" type="button" data-dismiss="modal">Zavrieť</button>
        <button class="btn" type="submit"><i class="fas fa-box-open"></i> Presunúť</button>
    </div>
</form>


<?php else: ?>
<div class="my-3">
    <p>Žiadne stĺpce sa nenašli</p>
</div>
<?php endif; ?>
