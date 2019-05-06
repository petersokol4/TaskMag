<?php if($countTasks > 0): ?>
<div class="modal-body">
    <p>Stĺpec, ktorý sa chystáte vymazať obsahuje úlohy. Chcete ich spolu so stĺpcom vymazať? Táto zmena je nevratná.</p>
</div>
<div class="modal-footer">
    <button class="btn moveColumnTasks" data-id="<?php echo $columnId; ?>" type="button"><i class="fas fa-box-open"></i> Presunúť Úlohy</button>
    <button class="btn" id="deleteColumnButton"><i class="far fa-trash-alt"></i> Vymazať</button>
</div>
<?php else: ?>
<div class="modal-body">
    <p>Naozaj chcete vymazať tento stĺpec? Táto zmena je nevratná.</p>
</div>
<div class="modal-footer">
    <button class="btn" type="button" data-dismiss="modal">Zavrieť</button>
    <button class="btn" id="deleteColumnButton" ><i class="far fa-trash-alt"></i> Vymazať</button>
</div>
<?php endif; ?>
