<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 12:13 PM
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\time\TimePicker;

$absoluteUrl = \yii\helpers\Url::home(true);
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="card-body">

        <?php

            $form = ActiveForm::begin([
                    // 'id' => $model->formName()
            ]);



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
                                    <h5><i class="icon fas fa-times"></i> Error!</h5>
                                ';
            echo Yii::$app->session->getFlash('error');
            print '</div>';
        }


            ?>
                <div class="row">
                    <div class="row col-md-12">



                        <div class="col-md-6">

                            <?= $form->field($model, 'No')->textInput(['readonly' => true]) ?>
                            <?= $form->field($model, 'Key')->hiddenInput()->label(false) ?>
                            <?= $form->field($model, 'Expected_Date')->textInput(['type'=> 'date',]) ?>
                            <?= $form->field($model, 'Expected_Sart_Time')->textInput(['type' => 'time', 'value'=> date('h:i:s a', strtotime($model->Expected_Sart_Time))]) ?>

                        </div>

                        <div class="col-md-6">
                            <!-- <?= $form->field($model, 'Expected_Hours')->textInput(['readonly'=> true, 'disabled'=>true]) ?> -->
                            <?= $form->field($model, 'Expected_Hours')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                            <?= $form->field($model, 'Expected_End_Time')->textInput(['type' => 'time', 'value'=> date('h:i:s a', strtotime($model->Expected_End_Time))]) ?>
                            <?= $form->field($model, 'Department')->dropDownList($departments, ['prompt' => 'Select Department..']) ?>


                        </div>

                    </div>

                </div>


                <div class="row">

                    <div class="form-group">
                        <?= Html::submitButton(($model->isNewRecord)?'Save':'Update', ['class' => 'btn btn-success']) ?>
                    </div>


                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>



    </div>
</div>



    <!--My Bs Modal template  --->

    <div class="modal fade bs-example-modal-lg bs-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel" style="position: absolute">Imprest Management</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                </div>

            </div>
        </div>
    </div>
<input type="hidden" name="absolute" value="<?= $absoluteUrl ?>">
<?php
$script = <<<JS
 //Submit Rejection form and get results in json    
       /* $('form').on('submit', function(e){
            e.preventDefault()
            const data = $(this).serialize();
            const url = $(this).attr('action');
            $.post(url,data).done(function(msg){
                    $('.modal').modal('show')
                    .find('.modal-body')
                    .html(msg.note);
        
                },'json');
        });*/


        $('#shiftcard-expected_date').on('change', function(e){
            e.preventDefault();
            const No = $('#shiftcard-no').val();     
            const ShiftStartDate = $('#shiftcard-expected_date').val();
            const url = $('input[name="absolute"]').val()+'shifts/set-start-date';

            if(No.length){
                $.post(url,{'ShiftStartDate': ShiftStartDate,'No': No}).done(function(msg){
                    //populate empty form fields with new data
                        console.log(typeof msg);
                        console.table(msg);
                        if((typeof msg) === 'string') { // A string is an error
                            const parent = document.querySelector('.field-overtimeline-end_time');
                            const helpbBlock = parent.children[2];
                            helpbBlock.innerText = msg;
                            disableSubmit();
                        }else{ // An object represents correct details
                            const parent = document.querySelector('.field-overtimeline-end_time');
                            const helpbBlock = parent.children[2];
                            helpbBlock.innerText = ''; 
                            $('#overtimeline-key').val(msg.Key);
                            $('#overtimeline-hours_worked').val(msg.Hours_Worked);
                            enableSubmit();
                        }
                        $('#overtimeline-key').val(msg.Key);
                        $('#overtimeline-hours_worked').val(msg.Hours_Worked);
                        
                },'json');
            }
            
           
        });


        $('#shiftcard-expected_sart_time').on('change', function(e){
            e.preventDefault();
            const No = $('#shiftcard-no').val();     
            const ShiftStartTime = $('#shiftcard-expected_sart_time').val();
            const url = $('input[name="absolute"]').val()+'shifts/set-start-time';

            if(No.length){
                $.post(url,{'ShiftStartTime': ShiftStartTime,'No': No}).done(function(msg){
                    //populate empty form fields with new data
                        console.log(typeof msg);
                        console.table(msg);
                        if((typeof msg) === 'string') { // A string is an error
                            const parent = document.querySelector('.field-shiftcard-expected_sart_time');
                            const helpbBlock = parent.children[2];
                            helpbBlock.innerText = msg;
                            disableSubmit();
                        }else{ // An object represents correct details
                            const parent = document.querySelector('.field-shiftcard-expected_sart_time');
                            const helpbBlock = parent.children[2];
                            helpbBlock.innerText = ''; 
                            $('#shiftcard-expected_hours').val(msg.Expected_Hours)

                            enableSubmit();
                        }
                        $('#shiftcard-expected_hours').val(msg.Expected_Hours)

                        
                },'json');
            }
            
           
        });


        $('#shiftcard-expected_end_time').on('change', function(e){
            e.preventDefault();
            const No = $('#shiftcard-no').val();     
            const ShiftEndTime = $('#shiftcard-expected_end_time').val();
            const url = $('input[name="absolute"]').val()+'shifts/set-end-time';

            if(No.length){
                $.post(url,{'ShiftEndTime': ShiftEndTime,'No': No}).done(function(msg){
                    //populate empty form fields with new data
                        console.log(typeof msg);
                        console.table(msg);
                        if((typeof msg) === 'string') { // A string is an error
                            const parent = document.querySelector('.field-shiftcard-expected_end_time');
                            const helpbBlock = parent.children[2];
                            helpbBlock.innerText = msg;
                            disableSubmit();
                        }else{ // An object represents correct details
                            const parent = document.querySelector('.field-shiftcard-expected_end_time');
                            const helpbBlock = parent.children[2];
                            helpbBlock.innerText = ''; 
                            $('#shiftcard-expected_hours').val(msg.Expected_Hours)

                            enableSubmit();
                        }
                      
                        $('#shiftcard-expected_hours').val(msg.Expected_Hours)
                        
                },'json');
            }
            
           
        });


   
     
     /*Handle modal dismissal event  */
    $('.modal').on('hidden.bs.modal',function(){
        var reld = location.reload(true);
        setTimeout(reld,1000);
    }); 
     
    function disableSubmit(){
             document.getElementById('submit').setAttribute("disabled", "true");
        }
        
        function enableSubmit(){
            document.getElementById('submit').removeAttribute("disabled");
        
        }
     
JS;

$this->registerJs($script);
