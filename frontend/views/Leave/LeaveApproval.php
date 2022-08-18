<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 6:09 PM
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Leave - '.$model->Application_No;
$this->params['breadcrumbs'][] = ['label' => 'Leave List', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Leave Card', 'url' => ['view','No'=> $model->Application_No]];

$Attachmentmodel = new \frontend\models\Leaveattachment();
$ApprovalDetails = Yii::$app->recruitment->getApprovaldetails($model->Application_No);
// echo '<pre>';
// print_r($ApprovalDetails);
// exit;

?>

<div class="row">
    <div class="col-md-12">

        <?php if(!$ApprovalDetails == false): ?>

            <?php if($model->Status == 'Pending_Approval' && $ApprovalDetails->Approver_No == Yii::$app->user->identity->Employee[0]->No):?>
                
                <?= 
                    Html::a('Approve',['approvals/approve-leave', 'app'=> $model->Application_No,
                    'empNo' => Yii::$app->user->identity->employee[0]->No,],['class' => 'btn btn-success ',
                        'data' => [
                            'confirm' => 'Are you sure you want to Approve this request?',
                            'method' => 'post',
                        ],
                        'title' => 'Approve.'
                    ])
                ?>

                <?= 
                    Html::a('Reject Request',['approvals/reject-request', 
                        'app'=> $model->Application_No,
                        'empNo' => Yii::$app->user->identity->employee[0]->No,
                        'rel' => $ApprovalDetails->Document_No,
                        'rev' => $ApprovalDetails->Record_ID_to_Approve,
                        'name' => $ApprovalDetails->Table_ID,
                        'docType' => $ApprovalDetails->Document_Type ],
                    ['class' => 'btn btn-danger reject',
                        'title' => 'Reject.'
                    ])
                ?>


            
            <?php  endif; ?>
        <?php endif; ?>

        
      
        


    </div>
</div>
<br>

    <div class="row">
        <div class="col-md-12">
            <div class="card-info">
                <div class="card-header">
                    <h3>Leave Application Card </h3>
                </div>



            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">




                    <h3 class="card-title">Leave Application : <?= $model->Application_No?></h3>



                    <?php
                    if(Yii::$app->session->hasFlash('success')){
                        print ' <div class="alert alert-success alert-dismissable">
                                 ';
                        echo Yii::$app->session->getFlash('success');
                        print '</div>';
                    }else if(Yii::$app->session->hasFlash('error')){
                        print ' <div class="alert alert-danger alert-dismissable">
                                 ';
                        echo Yii::$app->session->getFlash('error');
                        print '</div>';
                    }
                    ?>
                </div>
                <div class="card-body">


                    <?php $form = ActiveForm::begin(); ?>

                    <div class="row">
                    <div class="row col-md-12">



                            <div class="col-md-6">


                                <?= $form->field($model, 'Employee_No')->textInput(['readonly' => true,'diasbled' => true]); ?>
                                <?= $form->field($model, 'Application_No')->hiddenInput()->label(false); ?>
                                <?= $form->field($model, 'Employee_Name')->textInput(['readonly' => true,'diasbled' => true]) ?>
                                <?= $form->field($model, 'Global_Dimension_2_Code')->textInput(['readonly' => true,'diasbled' => true]) ?>

                                <?= $form->field($model, 'Leave_Code')->dropDownList($leavetypes,['prompt' => 'Select Leave Type', 'readonly'=>true, 'options' =>['id'=>'LeaveCode', ]]) ?>
                                <?= $form->field($model, 'Start_Date')->textInput(['type' => 'date','required' => true, 'readonly'=> true,]) ?>
                                <?= $form->field($model, 'Days_To_Go_on_Leave')->textInput(['type' => 'number','required' =>  true,'min'=> 1, 'readonly'=> true,]) ?>
                                <?= $form->field($model, 'Reliever')->dropDownList($employees,['prompt' => 'Select ..','required'=> true, 'readonly'=> true,]) ?>
                                <?= $form->field($model, 'Comments')->textarea(['rows'=> 2,'maxlength' => 250, 'readonly'=> true,]) ?>
                                <?= $form->field($model,'Leave_Allowance')->checkbox([$model->Leave_Allowance, 'disabled' => true]) ?>


                            </div>

                       
                            <div class="col-md-6">



                                <div class="row">
                                    <div class="col-md-6 col-sm-12">

                                        <?= $form->field($model, 'End_Date')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                        <?= $form->field($model, 'Total_No_Of_Days')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                        <?= $form->field($model, 'Leave_balance')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                        <?= $form->field($model, 'Reliever_Name')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                        <?= $form->field($model, 'Status')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                    </div>
                                    <div class="col-md-6 col-sm-12">


                                        <?= $form->field($model, 'Holidays')->textInput(['readonly'=> true,'disabled'=>true]) ?>
                                        <?= $form->field($model, 'Weekend_Days')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                        <?= $form->field($model, 'Balance_After')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                        <?= $form->field($model, 'Reporting_Date')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                        <?= $form->field($model, 'Application_Date')->textInput(['required' => true, 'disabled'=>true]) ?>
                                        <?= $form->field($model, 'Key')->hiddenInput(['required' => true, 'disabled'=>true])->label(false) ?>
                                    </div>
                                </div>

                            
                            </div>
                            <div class="form-group">
                </div>
                            
                    </div>
                </div>




                    <?php ActiveForm::end(); ?>


            <?php if($Attachmentmodel->getPath($model->Application_No)){   ?>

                <iframe src="data:application/pdf;base64,<?= $Attachmentmodel->readAttachment($model->Application_No); ?>" height="950px" width="100%"></iframe>


            <?php }  ?>

                </div>
            </div><!--end details card-->


            <!--Objectives card -->



    </div>

    <!--My Bs Modal template  --->

    <div class="modal fade bs-example-modal-lg bs-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel" style="position: absolute">Leave Plan</h4>
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


<?php
$absoluteUrl = \yii\helpers\Url::home(true);
if(!$ApprovalDetails === false){
    print '<input type="hidden" id="ab" value="'.$absoluteUrl.'" />';
    print '<input type="hidden" id="documentNo" value="'.$ApprovalDetails->Document_No.'" />';
    print '<input type="hidden" id="Record_ID_to_Approve" value="'.$ApprovalDetails->Record_ID_to_Approve.'" />';
    print '<input type="hidden" id="Table_ID" value="'.$ApprovalDetails->Table_ID.'" />';
    }

$script = <<<JS

    $(function(){

            /*Post Approval comment*/
    
    $('form#approval-comment').on('submit', function(e){
        e.preventDefault();
        var absolute = $('#ab').val(); 

        var url = absolute + 'approvals/reject-request'; 
        var data = $(this).serialize();
        
        
        $.post(url, data).done(function(msg){
          // $('#modal').modal('hide');
            var confirm = $('#modal').modal('show')
                    .find('.modal-body')
                    .html(msg.note);
            
            setTimeout(confirm, 1000);
            
        },'json');
        
       
    });
    
    
    /*Modal initialization*/
    
        $('.reject').on('click',function(e){
            e.preventDefault();
            console.table(this)
            var docno = $('#documentNo').val();
            var Record_ID_to_Approve = $('#Record_ID_to_Approve').val();;
            var Table_ID =$('#Table_ID').val();
            
            $('input[name=documentNo]').val(docno);
            $('input[name=Record_ID_to_Approve]').val(Record_ID_to_Approve);
            $('input[name=Table_ID]').val(Table_ID);
            
    
            $('.ApprovalModal').modal('show');                            
    
         });
    
      
        
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
            $('#modal').modal('show')
                            .find('.modal-body')
                            .load(url); 

        });
        
        
      //Add a training plan
    
     $('.add-objective, .update-objective').on('click',function(e){
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
     // $('tr.parent').nextUntil('tr.parent').slideUp(1, function(){});        
    $('tr.parent').click(function(){
            $(this).find('span').text(function(_, value){return value=='-'?'+':'-'}); //to disregard an argument -event- on a function use an underscore in the parameter               
            $(this).nextUntil('tr.parent').slideToggle(100, function(){});
     });
    
    /*Divs parenting*/
    
     $('p.parent').find('span').text('+');
    $('p.parent').find('span').css({"color":"red", "font-weight":"bolder"});    
    // $('p.parent').nextUntil('p.parent').slideUp(1, function(){});   
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
            $('#modal').modal('show')
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
                 $('#modal').modal('show')
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
