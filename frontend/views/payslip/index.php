<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/26/2020
 * Time: 10:59 PM
 */



/* @var $this yii\web\View */

$this->title = 'HRMIS - Payslip Report';
$this->params['breadcrumbs'][] = ['label' => 'Payroll Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Payslip', 'url' => ['index']];

if(Yii::$app->session->hasFlash('success')){
    print ' <div class="alert alert-success alert-dismissable">
                     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-check"></i> Success!</h5>
';
    echo Yii::$app->session->getFlash('success');
    print '</div>';
}else if(Yii::$app->session->hasFlash('error')){
    print ' <div class="alert alert-danger alert-dismissable">
                         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-check"></i> Error!</h5>
                        ';
    echo Yii::$app->session->getFlash('error');
    print '</div>';
}

?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">My Payslip Report</h3>

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                        <?php
// echo '<pre>';
// print_r($pperiods);
// exit;


?>
                            <form method="post" action="<?= Yii::$app->recruitment->absoluteUrl().'payslip/index'?>">
                                <?= \yii\helpers\Html::dropDownList('payperiods','',$pperiods,['prompt' =>'select PayPeriod','class' => 'form-control','required' => true]) ?>

                                <div class="form-group" style="margin-top: 10px">
                                <?= \yii\helpers\Html::submitButton('Generate Payslip',['class' => 'btn btn-primary']); ?>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!--<iframe src="data:application/pdf;base64,<?/*= $content; */?>" height="950px" width="100%"></iframe>-->
                    <?php
                    if($report){ ?>

                        <iframe src="data:application/pdf;base64,<?= $content; ?>" height="950px" width="100%"></iframe>
                   <?php } ?>



                </div>
            </div>
        </div>
    </div>

<?php
$script  = <<<JS
    $('select[name="payperiods"]').select2();
JS;

$this->registerJs($script, yii\web\View::POS_READY);










