<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 6:09 PM
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;


$this->title = 'Shift - '.$model->No;
$this->params['breadcrumbs'][] = ['label' => 'Overtime List', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Overtime Card', 'url' => ['view','No'=> $model->No]];





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
        <div class="col-md-12">
            <div class="card-info">
                <div class="card-header">
                    <h3>Shift Document </h3>
                </div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">

                <div class="row">
    <div class="col-md-4">

        <?= ($model->Status== 'Open')?Html::a('<i class="fas fa-paper-plane"></i> Send For Approval ',['send-for-approval'],['class' => 'btn btn-success submitforapproval',
            'data' => [
                'confirm' => 'Are you sure you want to send this document for approval?',
                'params'=>[
                    'No'=> $model->No,
                    'employeeNo' => Yii::$app->user->identity->{'Employee No_'},
                ],
                'method' => 'get',
        ],
            'title' => 'Submit Document for Approval'

        ]):'' ?>


        <?= ($model->Status == 'Pending_Approval' && !Yii::$app->request->get('Approval'))?Html::a('<i class="fas fa-times"></i> Cancel Approval Req.',['cancel-request'],['class' => 'btn btn-warning submitforapproval',
            'data' => [
            'confirm' => 'Are you sure you want to cancel document approval request?',
            'params'=>[
                'No'=> $model->No,
            ],
            'method' => 'get',
        ],
            'title' => 'Cancel Document Approval Request'

        ]):'' ?>
    </div>
</div>


                </div>
                <div class="card-body">


                    <?php $form = ActiveForm::begin(); ?>


                    <div class="row">
                        <div class=" row col-md-12">
                            <div class="col-md-6">

                                <?= $form->field($model, 'No')->textInput(['readonly' => true]) ?>
                                <?= $form->field($model, 'Key')->hiddenInput()->label(false) ?>
                                <?= $form->field($model, 'Status')->hiddenInput()->label(false) ?>
                                <?= $form->field($model, 'Expected_Sart_Time')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                <?= $form->field($model, 'Expected_End_Time')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                <?php if($model->Status == 'Approved'): ?>
                                   <?= $form->field($model, 'Shift_Employee_No')->widget(Select2::classname(), [
                                        'data' => $Employees,
                                        'language' => 'de',
                                        'options' => ['placeholder' => 'Select a state ...'],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                            ],
                                        ]);
                                    ?>

                                    <!-- <?= $form->field($model, 'Shift_Employee_No')->dropDownList($Employees, ['prompt' => 'Select Employee..']) ?> -->
                                <?php endif; ?>

                            </div>
                            <div class="col-md-6">

                                <?= $form->field($model, 'Department')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                <?= $form->field($model, 'Expected_Hours')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                <?= $form->field($model, 'Expected_Date')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                <?php if($model->Status == 'Approved'): ?>
                                    <?= $form->field($model, 'Type')->dropDownList(['Locum'=> 'Locum', 'Working_Hours_Extension'=>'Working_Hours_Extension'], ['prompt' => 'Select Department..']) ?>
                                <?php endif; ?>


                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <?= Html::submitButton('Create Overtime', ['class' => 'btn btn-success']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>



                </div>
            </div><!--end details card-->

          
    </div>


<?php

$script = <<<JS

    $(function(){
      
        
     /*Deleting Records*/
     
     $('.delete, .delete-objective').on('click',function(e){
         e.preventDefault();
           var secondThought = confirm("Are you sure you want to delete this record ?");
           if(!secondThought){//if user says no, kill code execution
                return;
           }
           
         var url = $(this).attr('href');
         $.get(url).done(function(msg){
            $('#modal').modal('show')
                    .find('.modal-body')
                    .html(msg.note);
         },'json');
     });
      
    
    /*Evaluate KRA*/
        $('.evalkra').on('click', function(e){
             e.preventDefault();
            var url = $(this).attr('href');
            console.log('clicking...');
            $('.modal').modal('show')
                            .find('.modal-body')
                            .load(url); 

        });
        
        
      //Add  plan Line
    
     $('.add-line, .update-objective').on('click',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        console.log('clicking...');
        $('#modal').modal('show')
                        .find('.modal-body')
                        .load(url); 

     });
     
     
     //Update a training plan
    
     $('.update-trainingplan').on('click',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        console.log('clicking...');
        $('#modal').modal('show')
                        .find('.modal-body')
                        .load(url); 

     });
     
     
     //Update/ Evalute Employeeappraisal behaviour -- evalbehaviour
     
      $('.evalbehaviour').on('click',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        console.log('clicking...');
        $('#modal').modal('show')
                        .find('.modal-body')
                        .load(url); 

     });
      
      /*Add learning assessment competence-----> add-learning-assessment */
      
      
      $('.add-learning-assessment').on('click',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        console.log('clicking...');
        $('#modal').modal('show')
                        .find('.modal-body')
                        .load(url); 

     });
      
      
     
      
      
      
    
    /*Handle modal dismissal event  */
    $('#modal').on('hidden.bs.modal',function(){
        var reld = location.reload(true);
        setTimeout(reld,1000);
    }); 
        
    /*Parent-Children accordion*/ 
    
    $('tr.parent').find('span').text('+');
    $('tr.parent').find('span').css({"color":"red", "font-weight":"bolder"});    
    $('tr.parent').nextUntil('tr.parent').slideUp(1, function(){});    
    $('tr.parent').click(function(){
            $(this).find('span').text(function(_, value){return value=='-'?'+':'-'}); //to disregard an argument -event- on a function use an underscore in the parameter               
            $(this).nextUntil('tr.parent').slideToggle(100, function(){});
     });
    
    /*Divs parenting*/
    
     $('p.parent').find('span').text('+');
    $('p.parent').find('span').css({"color":"red", "font-weight":"bolder"});    
    $('p.parent').nextUntil('p.parent').slideUp(1, function(){});    
    $('p.parent').click(function(){
            $(this).find('span').text(function(_, value){return value=='-'?'+':'-'}); //to disregard an argument -event- on a function use an underscore in the parameter               
            $(this).nextUntil('p.parent').slideToggle(100, function(){});
     });
    
        //Add Career Development Plan
        
        $('.add-cdp').on('click',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
           
            
            console.log('clicking...');
            $('#modal').modal('show')
                            .find('.modal-body')
                            .load(url); 
            
         });//End Adding career development plan
         
         /*Add Career development Strength*/
         
         
        $('.add-cds').on('click',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            
            $('#modal').modal('show')
                            .find('.modal-body')
                            .load(url); 
            
         });
         
         /*End Add Career development Strength*/
         
         
         /* Add further development Areas */
         
            $('.add-fda').on('click',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
                       
            console.log('clicking...');
            $('.modal').modal('show')
                            .find('.modal-body')
                            .load(url); 
            
         });
         
         /* End Add further development Areas */
         
         /*Add Weakness Development Plan*/
             $('.add-wdp').on('click',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
                       
            console.log('clicking...');
            $('#modal').modal('show')
                            .find('.modal-body')
                            .load(url); 
            
         });
         /*End Add Weakness Development Plan*/

         //Change Action taken

         $('select#probation-action_taken').on('change',(e) => {

            const key = $('input[id=Key]').val();
            const Employee_No = $('input[id=Employee_No]').val();
            const Appraisal_No = $('input[id=Appraisal_No]').val();
            const Action_Taken = $('#probation-action_taken option:selected').val();
           
              

            /* var data = {
                "Action_Taken": Action_Taken,
                "Appraisal_No": Appraisal_No,
                "Employee_No": Employee_No,
                "Key": key

             } 
            */
            $.get('./takeaction', {"Key":key,"Appraisal_No":Appraisal_No, "Action_Taken": Action_Taken,"Employee_No": Employee_No}).done(function(msg){
                 $('.modal').modal('show')
                    .find('.modal-body')
                    .html(msg.note);
                });


            });
    
        
    });//end jquery

    

        
JS;

$this->registerJs($script);

$style = <<<CSS
    p span {
        margin-right: 50%;
        font-weight: bold;
    }

    tbody {
      display: inline-block;
      overflow-y:auto;
    }
    thead, tbody tr {
      display:table;
      width: 100%;
      table-layout:fixed;
    }


    table td:nth-child(11), td:nth-child(12) {
                text-align: center;
    }
    
    /* Table Media Queries */
    
     @media (max-width: 500px) {
          table td:nth-child(2),td:nth-child(3),td:nth-child(6),td:nth-child(7),td:nth-child(8),td:nth-child(9),td:nth-child(10), td:nth-child(11) {
                display: none;
        }
    }
    
     @media (max-width: 550px) {
          table td:nth-child(2),td:nth-child(6),td:nth-child(7),td:nth-child(8),td:nth-child(9),td:nth-child(10), td:nth-child(11) {
                display: none;
        }
    }
    
    @media (max-width: 650px) {
          table td:nth-child(2),td:nth-child(6),td:nth-child(7),td:nth-child(8),td:nth-child(9),td:nth-child(10), td:nth-child(11) {
                display: none;
        }
    }


    @media (max-width: 1500px) {
          table td:nth-child(2),td:nth-child(7),td:nth-child(8),td:nth-child(9),td:nth-child(10), td:nth-child(11) {
                display: none;
        }
    }
CSS;

$this->registerCss($style);
