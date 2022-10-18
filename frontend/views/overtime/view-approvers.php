<?php

?>
  <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <?= \yii\helpers\Html::a('Go Back',['index'],['class' => ' back btn btn-warning push-right']) ?>
                </div>
            </div>
        </div>
    </div>
    
<?php if(is_array($model)): ?>
    <?php foreach($model as $Key => $ApproverDetails): ?>
        <?php if(empty($ApproverDetails->Approver_ID) || empty($ApproverDetails->Status)): ?>
            <?php continue;  ?>
        <?php endif ?>
        <table class="table">

            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Approver ID</th>
                <th scope="col">Status</th>
                <th scope="col">Approval Date</th>
                </tr>
            </thead>

                <tbody>
                    <tr>
                    <th scope="row"><?= $Key+1 ?></th>
                    <td><?= isset($ApproverDetails->Approver_ID)?$ApproverDetails->Approver_ID:'Not Set' ?></td>
                    <td><?= isset($ApproverDetails->Approver_ID)?$ApproverDetails->Status:'Not Set' ?></td>
                    <td><?= isset($ApproverDetails->Approval_Date)?$ApproverDetails->Approval_Date:'Not Set' ?></td>
                    </tr>
                </tbody>
            </table>
    <?php endforeach; ?>
<?php endif; ?>