<?php
defined('BASEPATH') OR exit('');
?>

<?php echo isset($range) && !empty($range) ? $range : ""?>
<div class="panel panel-primary">
    <div class="panel-heading">DENIED LOANS <i class="fa fa-remove"></i></div>
    <?php if($denLoans):?>
    <div class="table table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>USER</th>
                    <th>LOAN</th>
                    <th>COLLATERAL</th>
                    <th>DURATION</th>
                    <th>DATE REQUESTED</th>
                    <th>DATE DENIED</th>
                    <th>STATUS</th>
                    <th>EDIT</th>
                    <th>ACTIONS</th>
                    <!-- <th>DATE CREATED</th> -->
                    <!-- <th>DELETE</th> -->
                </tr>
            </thead>
            <tbody>
                <?php foreach($denLoans as $get):?>
                    <tr>
                        <th><?=$sn?>.</th>
                        <td class="user"><?=$get->email?></td>
                        <td class="hidden user_id"><?=$get->user_id?></td>
                        <td class="hidden loan_unit_id"><?=$get->loan_unit_id?></td>
                        <td class="hidden loan_amount"><?=$get->loan_amount?></td>
                        <td class="hidden collateral_unit_id"><?=$get->collateral_unit_id?></td>
                        <td class="hidden collateral_amount"><?=$get->collateral_amount?></td>
                        <td class="hidden status_id"><?=$get->status_number?></td>
                        <td class="loan_amount"><?= html_entity_decode($loan_unit_icons[$get->loan_unit_id])?><?=number_format($get->loan_amount) ?></td>
                        <td class="collateral_amount"><?=$get->collateral_amount?><?=html_entity_decode($collateral_unit_icons[$get->collateral_unit_id])?></td>
                        <td class="duration"><?=$get->loan_duration ?> months</td>
                        <td class="requested_on"><?=$get->requested_on ?></td>
                        <td class="denied_on"><?=$get->approved_on ?></td>
                        <!-- <td><?=date('jS M, Y h:i:sa', $get->requested_on)?></td> -->
                        <td class="status"><?=$get->status ?></td>
                        <td class="text-center editLoan" id="edit-<?=$get->id?>">
                            <i class="fa fa-pencil pointer"></i>
                        </td>
                        <td class="actions">
                            <a class="approveLoan" id="approve-<?=$get->id?>" title="Approve"><i class="fa fa-check"></i></a>

                            <a class="revertLoan" id="revert-<?=$get->id?>" title="Revert to Pending"><i class="fa fa-refresh"></i></a>
                        </td>
                        <!-- <td class="text-center text-danger deleteBank" id="del-<?=$get->id?>">
                            <?php if($get->deleted === "1"): ?>
                            <a class="pointer">Undo Delete</a>
                            <?php else: ?>
                            <i class="fa fa-trash pointer"></i>
                            <?php endif; ?>
                        </td> -->
                    </tr>
                    <?php $sn++;?>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
    <?php else:?>
    No Denied Loans
    <?php endif; ?>
</div>
<!-- Pagination -->
<div class="row text-center">
    <?php echo isset($links) ? $links : ""?>
</div>
<!-- Pagination ends -->