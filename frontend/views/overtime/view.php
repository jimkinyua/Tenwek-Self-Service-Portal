<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 6:09 PM
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$ApprovalDetails = Yii::$app->recruitment->getApprovaldetails($model->No);

$ApprovalDetails = Yii::$app->recruitment->getApprovaldetails($model->No);
$this->title = 'Overtime - '.$model->No;
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
    <div class="col-md-4">

        <?= ($model->Status== 'Open')?Html::a('<i class="fas fa-paper-plane"></i> Send Approval Req',['send-for-approval'],['class' => 'btn btn-app submitforapproval',
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

        <?php if($ApprovalDetails): ?>
            <?php if($ApprovalDetails->Sender_No == Yii::$app->user->identity->employee[0]->No): ?>

        <?= ($model->Status == 'Pending_Approval' && !Yii::$app->request->get('Approval'))?Html::a('<i class="fas fa-times"></i> Cancel Approval Req.',['cancel-request'],['class' => 'btn btn-app submitforapproval',
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

    <div class="row">
        <div class="col-md-12">
            <div class="card-info">
                <div class="card-header">
                    <h3>Overtime Document </h3>
                </div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">

                    <h3 class="card-title">Document No. : <?= $model->No?></h3>


                </div>
                <div class="card-body">

                
<div class="row">
    <div class="col-md-12">

        <?= ($model->Status == 'New')?Html::a('<i class="fas fa-paper-plane"></i> Send Approval Req',['send-for-approval','employeeNo' => Yii::$app->user->identity->employee[0]->No],['class' => 'btn btn-success submitforapproval',
            'data' => [
                'confirm' => 'Are you sure you want to send imprest request for approval?',
                'params'=>[
                    'No'=> $_GET['No'],
                    'employeeNo' =>Yii::$app->user->identity->employee[0]->No,
                ],
                'method' => 'get',
        ],
            'title' => 'Submit Leave Approval'

        ]):'' ?>

  


                


                    <?php $form = ActiveForm::begin(); ?>

                    <?php if($model->Status == 'Approved' || $model->Status == 'Pending_Approval'): ?>
                        <div class="row">
                            <div class="row col-md-12">
                                <div class="col-md-6">
                                    <?= $form->field($model, 'No')->textInput(['readonly' => true]) ?>
                                    <?= $form->field($model, 'Key')->hiddenInput()->label(false) ?>
                                    <?=  $form->field($model, 'Employee_No')->textInput(['readonly'=> true, 'disabled'=>true]) ; ?>
                                    <?= $form->field($model, 'Employee_Name')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                </div>
                                <div class="col-md-6">
                                    <?= $form->field($model, 'Global_Dimension_1_Code')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                    <?= $form->field($model, 'Global_Dimension_2_Code')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                    <?= $form->field($model, 'Hours_Worked')->textInput(['readonly'=> true, 'disabled'=>true]) ?> 
                                </div>
                            </div>
                        </div>

                        <?php else: ?>
                            <div class="row">
                                <div class="row col-md-12">
                                    <div class="col-md-6">

                                        <?= $form->field($model, 'No')->textInput(['readonly' => true]) ?>
                                        <?= $form->field($model, 'Key')->hiddenInput()->label(false) ?>
                                        
                                        <?php
                                            if(Yii::$app->user->identity->isSupervisor()){

                                                echo $form->field($model, 'Employee_No')->dropDownList($EmployeesUnderMe,['prompt' => 'Select Employee']);

                                            }else{
                                            echo  $form->field($model, 'Employee_No')->textInput(['readonly'=> true, 'disabled'=>true]) ;                    
                                            }
                                        ?>

                                        <?= $form->field($model, 'Employee_Name')->textInput(['readonly'=> true, 'disabled'=>true]) ?>

                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'Global_Dimension_1_Code')->textInput(['readonly'=> true, 'disabled'=>true]) ?>
                                        
                                        <!-- <?= $form->field($model, 'Global_Dimension_2_Code')->textInput(['readonly'=> true, 'disabled'=>true]) ?> -->
                                        <?= $form->field($model, 'Hours_Worked')->textInput(['readonly'=> true, 'disabled'=>true]) ?> 
                                    </div>
                                </div>
                            </div>

                    <?php endif; ?> 


                <div class="row">
                    <?php if($model->Status == 'Open'): ?>
                        <div class="form-group">
                            <?= Html::submitButton(($model->isNewRecord)?'Save':'Update', ['class' => 'btn btn-success']) ?>
                        </div>
                    <?php endif; ?>

                </div>




                    <?php ActiveForm::end(); ?>



                </div>
            </div><!--end details card-->

            <!--Lines -->

            <div class="card">
                <!-- <div class="card-header">
                    <div class="card-title">
                       <?= ($model->Status == 'Open')?
                        // Html::a('<i class="fa fa-plus-square"></i> Add Line',['overtimeline/create','No'=>$model->No],['class' => 'add-line btn btn-outline-info',
                       
                        Html::button('Add Overtime Hours ',
                        [  'value' => yii\helpers\Url::to(['overtimeline/create',
                        'No' => $model->No,
                        ]),
                        'title' => 'Add Overtime Hours',
                        'class' => 'btn btn-success push-right showModalButton',
                        ]):''
                    ?>
                    </div>
                </div> -->

                <div class="card-body">

                    <?php if(is_array($model->lines)){ //show Lines ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>

                                    <td><b>Date</b></td>
                                    <td><b>Start Time</b></td>
                                    <td><b>End Date</b></td>
                                    <td><b>Hours Worked</b></td>
                                    <td><b>Work Done</b></td>
                                    <td><b>Action</b></td>


                                </tr>
                                </thead>
                                <tbody>
                                <?php
                               foreach($model->lines as $obj):
                                   $deleteLink = ($model->Status == 'Open')?Html::a('<i class="fa fa-trash"></i>',['overtimeline/delete','Key'=> $obj->Key ],['class'=>'delete btn btn-outline-danger btn-xs','title' => 'Delete Overtime Line.']):'';

                                   $updateLink = ($model->Status == 'Open')?

                                   Html::button('<i class="fa fa-edit"></i>',
                                   [  'value' => yii\helpers\Url::to(['overtimeline/update',
                                    'No'=> $obj->Line_No, 'DocNum'=>$model->No
                                   ]),
                                   'title' => 'Edit Overtime Hours',
                                   'class' => 'btn btn-info btn-xs mx-2 showModalButton',
                                   ]):''

                                 
                                    ?>
                                    <tr>

                                        <td data-key="<?= $obj->Key ?>" data-name="Date" data-no="<?= $obj->Line_No ?>"  data-service="OvertimeLine"><?= !empty($obj->Date)?$obj->Date:'Not Set' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Start_Time" data-no="<?= $obj->Line_No ?>"  data-service="OvertimeLine" ><?= !empty($obj->Start_Time)?$obj->Start_Time:'Not Set' ?></td>
                                        <td data-validate="Hours_Worked" data-key="<?= $obj->Key ?>" data-name="End_Time" data-no="<?= $obj->Line_No ?>"  data-service="OvertimeLine" ><?= !empty($obj->End_Time)?$obj->End_Time:'Not Set' ?></td>
                                        <td id="Hours_Worked"><?= !empty($obj->Hours_Worked)?$obj->Hours_Worked:'Not Set' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Work_Done" data-no="<?= $obj->Line_No ?>"  data-service="OvertimeLine" ><?= !empty($obj->Work_Done)?$obj->Work_Done:'Not Set' ?></td>
                                        <td class="text-center"><?= $updateLink.$deleteLink ?></td>

                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                    <?php } ?>
                </div>
            </div>

            <!--End Lines -->

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
      
    $('form#approval-comment').on('submit', function(e){
        e.preventDefault();
        var absolute = $('#ab').val(); 

        var url = absolute + 'approvals/reject-request'; 
        var data = $(this).serialize();
        
        $.post(url, data).done(function(msg){
        // $('#modal').modal('hide');
            var confirm = $('.modal').modal('show')
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
        
        
      //Add  plan Line
    
     $('.add-line, .update-objective').on('click',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        console.log('clicking...');
        $('.OtherModal').modal('show')
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
