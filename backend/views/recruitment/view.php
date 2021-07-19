<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 6:09 PM
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
// print '<pre>';
// print_r($model); exit;


?>

<?php
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
                                    <h5><i class="icon fas fa-check"></i> Success!</h5>
                                 ';
    echo Yii::$app->session->getFlash('success');
    print '</div>';
}
?>
<div class="row">
    <div class="col-md-12">
        


      
            <!---end responsibilities------->

        <div class="card card-blue">
            <!-------Add requirements------------>
            <div class="card-header">
                <h3 class="card-title">Job Requirements</h3>
            </div>


            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" >
                            <?php

                            if(!empty($model->Hr_job_requirements->Hr_job_requirements) && sizeof($model->Hr_job_requirements->Hr_job_requirements)){
                                foreach($model->Hr_job_requirements->Hr_job_requirements as $req){
                                    if(!empty($req->Requirement)){
                                        print '<tr>
                                            <td class="parent"><span>+</span>'.$req->Requirement.'</td>';
                                            echo Yii::$app->recruitment->Requirementspecs($req->Line_No);

                                        print'</tr>';
                                    }

                                }
                            }else{
                                print '<tr>
                                            <td>No requirements set yet.</td>
                                        </tr>';
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>

            <!---end requirements------->


        </div>
    </div>
</div>


<!-- Default box -->
<div class="card">
        <div class="card-header">
          <h3 class="card-title">Job Details</h3>

        
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
              <div class="row">
                <div class="col-12 col-sm-4">
                  <div class="info-box bg-light">
                    <div class="info-box-content">
                      <span class="info-box-text text-center text-muted">No of Posts</span>
                      <span class="info-box-number text-center text-muted mb-0"><?=$model->No_Posts?></span>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-sm-4">
                  <div class="info-box bg-light">
                    <div class="info-box-content">
                      <span class="info-box-text text-center text-muted">Reason for Advertisement</span>
                      <span class="info-box-number text-center text-muted mb-0"><?=$model->Reasons_For_Requisition ?></span>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-sm-4">
                  <div class="info-box bg-light">
                    <div class="info-box-content">
                      <span class="info-box-text text-center text-muted">Job Title</span>
                      <span class="info-box-number text-center text-muted mb-0"><?=$model->Job_Description ?> <span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <h4>Responsibilities</h4>
                  
                    <div class="post clearfix">
                 
                      <!-- /.user-block -->
                      <p>
                        
                            <?php
                                if(!empty($model->Hr_Job_Responsibilities->Hr_Job_Responsibilities) && sizeof($model->Hr_Job_Responsibilities->Hr_Job_Responsibilities)){
                                   echo '<ol>';
                                    foreach($model->Hr_Job_Responsibilities->Hr_Job_Responsibilities as $resp){

                                        if(!empty($resp->Responsibility_Description)){
                                            print '<li>'.$resp->Responsibility_Description.'</li>'; 
                                        // echo (Yii::$app->recruitment->Responsibilityspecs($resp->Line_No));
                                        }

                                    }
                                }
                                echo ' </ol>';
                                
                            ?>
                       

                      </p>
                      <p>
                        <!-- <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 2</a> -->
                      </p>
                    </div>

                    <div class="post">
                      <div class="user-block">
                        <img class="img-circle img-bordered-sm" src="../../dist/img/user1-128x128.jpg" alt="user image">
                        <span class="username">
                          <a href="#">Jonathan Burke Jr.</a>
                        </span>
                        <span class="description">Shared publicly - 5 days ago</span>
                      </div>
                      <!-- /.user-block -->
                      <p>
                        Lorem ipsum represents a long-held tradition for designers,
                        typographers and the like. Some people hate it and argue for
                        its demise, but others ignore.
                      </p>

                      <p>
                        <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 1 v1</a>
                      </p>
                    </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
              <h3 class="text-primary"><i class="fas fa-paint-brush"></i> AdminLTE v3</h3>
              <p class="text-muted">Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terr.</p>
              <br>
              <div class="text-muted">
                <p class="text-sm">Client Company
                  <b class="d-block">Deveint Inc</b>
                </p>
                <p class="text-sm">Project Leader
                  <b class="d-block">Tony Chicken</b>
                </p>
              </div>

              <h5 class="mt-5 text-muted">Project files</h5>
              <ul class="list-unstyled">
                <li>
                  <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-file-word"></i> Functional-requirements.docx</a>
                </li>
                <li>
                  <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-file-pdf"></i> UAT.pdf</a>
                </li>
                <li>
                  <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-envelope"></i> Email-from-flatbal.mln</a>
                </li>
                <li>
                  <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-image "></i> Logo.png</a>
                </li>
                <li>
                  <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-file-word"></i> Contract-10_12_2014.docx</a>
                </li>
              </ul>
              <div class="text-center mt-5 mb-3">
                <a href="#" class="btn btn-sm btn-primary">Add files</a>
                <a href="#" class="btn btn-sm btn-warning">Report contact</a>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->



<?php

$script = <<<JS
    /*Parent-Children accordion*/ 
    
    $('td.parent').find('span').text('+');
    $('td.parent').find('span').css({"color":"red", "font-weight":"bolder", "margin-right": "10px"});    
    $('td.parent').nextUntil('td.parent').slideUp(1, function(){});    
    $('td.parent').click(function(){
            $(this).find('span').text(function(_, value){return value=='-'?'+':'-'}); //to disregard an argument -event- on a function use an underscore in the parameter               
            $(this).nextUntil('td.parent').slideToggle(100, function(){});
     });
JS;

$this->registerJs($script);

