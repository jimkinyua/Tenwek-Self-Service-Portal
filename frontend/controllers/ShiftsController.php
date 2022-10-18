<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:21 PM
 */

namespace frontend\controllers;
use frontend\models\Overtime;
use frontend\models\Purchaserequisition;
use frontend\models\SalaryIncrement;
use frontend\models\ShiftCard;
use frontend\models\TrackApprovals;
use Yii;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use kartik\mpdf\Pdf;

class ShiftsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout','signup','index','list','create','update','delete','view'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout','index','list','create','update','delete','view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
            'contentNegotiator' =>[
                'class' => ContentNegotiator::class,
                'only' => ['list', 'approved-list'],
                'formatParam' => '_format',
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    //'application/xml' => Response::FORMAT_XML,
                ],
            ]
        ];
    }

    public function actionIndex(){

        return $this->render('index');

    }

    public function actionApproved(){

        return $this->render('approved');

    }


    public function actionCreate(){
       // Yii::$app->recruitment->printrr($this->getPayrollscales());
       $model = new ShiftCard(); 
       $service = Yii::$app->params['ServiceName']['ShiftCard'];

        /*Do initial request */
        if(!isset(Yii::$app->request->post()['ShiftCard'])){
            $model->Employee_No = Yii::$app->user->identity->{'Employee No_'};
            $model->Expected_Date = date('Y-m-d', strtotime($model->Expected_Date));
            $request = Yii::$app->navhelper->postData($service, $model);
            if(!is_string($request) )
            {
                Yii::$app->navhelper->loadmodel($request,$model);
            }else{

                Yii::$app->session->setFlash('error',$request);
                 return $this->render('create',[
                    'model' => $model,
                    'programs' => $this->getPrograms(),
                    'departments' => $this->getDepartments(),
                     'ApprovedHRJobs' => $this->getApprovedHRJobs(),
                     'OvertimePeriods' => $this->getOvertimePeriods(),
                ]);
            }
        }

        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['ShiftCard'],$model) ){
            $filter = [
                'No' => $model->No,
            ];
            $refresh = Yii::$app->navhelper->getData($service,$filter);
            $model->Key = $refresh[0]->Key;
            $model->Expected_Date = date('Y-m-d', strtotime($model->Expected_Date));
            $result = Yii::$app->navhelper->updateData($service,$model);
            if(!is_string($result)){

                Yii::$app->session->setFlash('success','Request Created Successfully.' );
                return $this->redirect(['view','No' => $result->No]);

            }else{
                Yii::$app->session->setFlash('error','Error Creating Request '.$result );
                return $this->redirect(['index']);

            }

        }


        //Yii::$app->recruitment->printrr($model);

        return $this->render('create',[
            'model' => $model,
            'programs' => $this->getPrograms(),
            'departments' => $this->getDepartments(),
            'ApprovedHRJobs' => $this->getApprovedHRJobs(),
            'OvertimePeriods' => $this->getOvertimePeriods(),
        ]);
    }




    public function actionUpdate($No){
        $model = new ShiftCard(); 
        $service = Yii::$app->params['ServiceName']['ShiftCard'];
        $model->isNewRecord = false;

        $filter = [
            'No' => $No,
        ];
        $refresh = Yii::$app->navhelper->getData($service,$filter);

        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['ShiftCard'],$model) ){
            $model->Key = $refresh[0]->Key;
            $result = Yii::$app->navhelper->updateData($service,$model);

            if(!is_string($result)){
                Yii::$app->session->setFlash('success','Document Updated Successfully.' );
                return $this->redirect(['view','No' => $result->No]);

            }else{
                Yii::$app->session->setFlash('error','Error Updating Document'.$result );
                return $this->render('update',[
                    'model' => Yii::$app->navhelper->loadmodel($refresh[0],$model),
                    'departments' => $this->getDepartments(),
                    'programs' => $this->getPrograms(),
                    'ApprovedHRJobs' => $this->getApprovedHRJobs(),
                    'OvertimePeriods' => $this->getOvertimePeriods(),
                ]);

            }

        }


        return $this->render('update',[
            'model' =>Yii::$app->navhelper->loadmodel($refresh[0],$model) ,
            // 'programs' => $this->getPrograms(),
            'departments' => $this->getDepartments(),
            'ApprovedHRJobs' => $this->getApprovedHRJobs(),
            'programs' => $this->getPrograms(),
            'ApprovedHRJobs' => $this->getApprovedHRJobs(),
            'OvertimePeriods' => $this->getOvertimePeriods(),

        ]);
    }

    public function actionDelete(){
        $service = Yii::$app->params['ServiceName']['SalaryIncrementCard'];
        $result = Yii::$app->navhelper->deleteData($service,Yii::$app->request->get('Key'));
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(!is_string($result)){

            return ['note' => '<div class="alert alert-success">Record Purged Successfully</div>'];
        }else{
            return ['note' => '<div class="alert alert-danger">Error Purging Record: '.$result.'</div>' ];
        }
    }

    public function actionView($No){
        $model = new ShiftCard();
        $service = Yii::$app->params['ServiceName']['ShiftCard'];

        $filter = [
            'No' => $No
        ];

        $result = Yii::$app->navhelper->getData($service, $filter);

        //load nav result to model
        $model = Yii::$app->navhelper->loadmodel($result[0],$model) ;

        //Yii::$app->recruitment->printrr($model);

        return $this->render('view',[
            'model' => $model,
            'ApprovedHRJobs' => $this->getApprovedHRJobs(),
        ]);
    }

    public function actionAddEmployee($No){
        $model = new ShiftCard();
        $service = Yii::$app->params['ServiceName']['ShiftCard'];

        $filter = [
            'No' => $No
        ];

        $refresh = Yii::$app->navhelper->getData($service, $filter);

        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['ShiftCard'],$model) ){
            $model->Key = $refresh[0]->Key;
            $result = Yii::$app->navhelper->updateData($service,$model);

            if(!is_string($result)){

                $service = Yii::$app->params['ServiceName']['IanSoftFactory'];

                $data = [
                    'shiftNo' => $result->No,
                    // 'sendMail' => 1,
                    // 'approvalUrl' => Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['overtime/view', 'No' => $No])),
                ];
        
        
                $CodeunitResult = Yii::$app->navhelper->PortalWorkFlows($service,$data,'IanConvertToOvertime');
        
                if(!is_string($CodeunitResult)){
                    Yii::$app->session->setFlash('success', 'Overtime Created Successfully.', true);
                    return $this->redirect('approved');
                }else{
        
                    Yii::$app->session->setFlash('error', 'Error Creating Overtime  : '. $CodeunitResult);
                    return $this->redirect(Yii::$app->request->referrer);
        
                }

            }else{
                Yii::$app->session->setFlash('error','Error Updating Document'.$result );
                return $this->redirect(Yii::$app->request->referrer);

            }

        }


        //load nav result to model
        $model = Yii::$app->navhelper->loadmodel($refresh[0],$model) ;

        //Yii::$app->recruitment->printrr($model);

        return $this->render('add-employee',[
            'model' => $model,
            'Employees' => @ArrayHelper::map($this->getEmployees($model->Job_Title),'No','Full_Name'),
            
        ]);
    }

    public function actionViewApprovers($DocNum){
        $service = Yii::$app->params['ServiceName']['TrackApprovals'];

        $filter = [
            'Document_No' => $DocNum
        ];

        $refresh = Yii::$app->navhelper->getData($service, $filter);
        return $this->render('view-approvers',[
            'model' => $refresh,           
        ]);
    }

    private function PendingAt($DocumentNo){
        $service = Yii::$app->params['ServiceName'][ 'TrackApprovals'];
        $filter = [
            'Document_No' => $DocumentNo,
        ];
        $results = \Yii::$app->navhelper->getData($service,$filter);
            // echo '<pre>'; print_r($results); exit;

        if(!is_object($results)){
            foreach($results as $item){
                return isset($item->Approver_ID)?$item->Approver_ID: 'Not Set';
            }
        }

        return 'Not Applicable';
    }
   // Get list

    public function actionList(){
        $service = Yii::$app->params['ServiceName']['ShiftsList'];
        $filter = [
            // 'Status' => 'Open',
        ];

        $CurrentApprover = 'Not Applicable';
        $results = \Yii::$app->navhelper->getData($service,$filter);
        //Yii::$app->recruitment->printrr($results);
        $result = [];
        foreach($results as $item){

            if(!empty($item->No ))
            {
                $link = $updateLink = $deleteLink =  '';

                $Viewlink = Html::a('<i class="fas fa-eye"></i>',['view','No'=> $item->No ],['class'=>'btn btn-outline-primary btn-xs','title' => 'View Request.' ]);
                if($item->Status == 'Open'){
                    $link = Html::a('<i class="fas fa-paper-plane"></i>',['send-for-approval','No'=> $item->No ],['title'=>'Send Approval Request','class'=>'btn btn-primary btn-xs']);
                    $updateLink = Html::a('<i class="far fa-edit"></i>',['update','No'=> $item->No ],['class'=>'btn btn-info btn-xs','title' => 'Update Request']);
                }else if($item->Status == 'Pending_Approval'){
                    $link = Html::a('<i class="fas fa-times"></i>',['cancel-request','No'=> $item->No ],['title'=>'Cancel Approval Request','class'=>'btn btn-warning btn-xs']);
                    $CurrentApprover = Html::a('View Approvers',['view-approvers','DocNum'=> $item->No ],['title'=>'View Approvers','class'=>'btn btn-warning btn-md']);
                }

                $result['data'][] = [
                    'Key' => $item->Key,
                    'No' => $item->No,
                    'Department' => !empty($item->Department)?$item->Department:'',
                    'CurrentApprover'=>$CurrentApprover,
                    'StartTime' => !empty($item->Expected_Sart_Time)?$item->Expected_Sart_Time:'',
                    'Expected_End_Time' => !empty($item->Expected_End_Time)?$item->Expected_End_Time:'',
                    'Expected_Hours' => !empty($item->Expected_Hours)?$item->Expected_Hours:'',
                    'Action' => $link.' '. $updateLink.' '.$Viewlink ,

                ];
            }
        }
        return $result;
    }

    public function actionApprovedList(){
        $service = Yii::$app->params['ServiceName']['ShiftsList'];
        $filter = [
            'Status' => 'Approved',
            'Employee_No'=> Yii::$app->user->identity->{'Employee No_'},
        ];


        $results = \Yii::$app->navhelper->getData($service,$filter);
        //Yii::$app->recruitment->printrr($results);
        $result = [];
        foreach($results as $item){

            if(!empty($item->No ))
            {
                $link = $updateLink = $deleteLink =  '';

                $Viewlink = Html::a('Add Employee',['add-employee','No'=> $item->No ],['class'=>'btn btn-outline-primary btn-xs','title' => 'View Request.' ]);
                if($item->Status == 'Open'){
                    $link = Html::a('<i class="fas fa-paper-plane"></i>',['send-for-approval','No'=> $item->No ],['title'=>'Send Approval Request','class'=>'btn btn-primary btn-xs']);
                    $updateLink = Html::a('<i class="far fa-edit"></i>',['update','No'=> $item->No ],['class'=>'btn btn-info btn-xs','title' => 'Update Request']);
                }else if($item->Status == 'Pending_Approval'){
                    $link = Html::a('<i class="fas fa-times"></i>',['cancel-request','No'=> $item->No ],['title'=>'Cancel Approval Request','class'=>'btn btn-warning btn-xs']);
                }

                $result['data'][] = [
                    'Key' => $item->Key,
                    'No' => $item->No,
                    'Department' => !empty($item->Department)?$item->Department:'',
                    'StartTime' => !empty($item->Expected_Sart_Time)?$item->Expected_Sart_Time:'',
                    'Expected_End_Time' => !empty($item->Expected_End_Time)?$item->Expected_End_Time:'',
                    'Expected_Hours' => !empty($item->Expected_Hours)?$item->Expected_Hours:'',
                    'Action' => $Viewlink ,

                ];
            }
        }
        return $result;
    }


    /*Get Programs */

    public function getPrograms(){
        $service = Yii::$app->params['ServiceName']['DimensionValueList'];

        $filter = [
            'Global_Dimension_No' => 1
        ];

        $result = \Yii::$app->navhelper->getData($service, $filter);
        return ArrayHelper::map($result,'Code','Name');
    }

    /* Get Department*/

    public function getDepartments(){
        $service = Yii::$app->params['ServiceName']['DimensionValueList'];

        $filter = [
            'Global_Dimension_No' => 2
        ];
        $result = \Yii::$app->navhelper->getData($service, $filter);
        return ArrayHelper::map($result,'Code','Name');
    }

    public function getApprovedHRJobs()
    {
        $service = Yii::$app->params['ServiceName']['ApprovedHRJobs'];
        $result = Yii::$app->navhelper->getData($service, []);

         return Yii::$app->navhelper->refactorArray($result,'Job_ID','Job_Description');
    }

    

    public function actionPointerDd($scale)
    {
        $service = Yii::$app->params['ServiceName']['PayrollScalePointers'];
        $filter = ['Scale' => $scale];
        $result = Yii::$app->navhelper->getData($service, $filter);

        $data = Yii::$app->navhelper->refactorArray($result, 'Pointer','Pointer');

        if(count($data) )
        {
            foreach($data  as $k => $v )
            {
                echo "<option value='$k'>".$v."</option>";
            }
        }else{
            echo "<option value=''>No data Available</option>";
        }
    }



    /* Get Dimension 3*/

    public function getD3(){
        $service = Yii::$app->params['ServiceName']['DimensionValueList'];

        $filter = [
            'Global_Dimension_No' => 3
        ];
        $result = \Yii::$app->navhelper->getData($service, $filter);
        return ArrayHelper::map($result,'Code','Name');
    }



    public function getEmployees($Job_Title){
        $service = Yii::$app->params['ServiceName']['Employees'];
        $filter = [
            'Job_Title'=>$Job_Title
        ];
        $employees = \Yii::$app->navhelper->getData($service, $filter);
        //  Yii::$app->recruitment->printrr(Yii::$app->user->identity);
        return $employees;
    }

    
    public function getOvertimePeriods(){
        $service = Yii::$app->params['ServiceName']['OvertimePeriods'];
        $filter = [
            'Global_Dimension_1_Code'=>Yii::$app->user->identity->{'Global Dimension 1 Code'}
        ];
        $periods = \Yii::$app->navhelper->getData($service);
        return @ArrayHelper::map($periods,'Start_Date', 'Period_Name');
    }









    /* Call Approval Workflow Methods */

    public function actionSendForApproval($No)
    {
        $service = Yii::$app->params['ServiceName']['PortalFactory'];

        $data = [
            'applicationNo' => $No,
            'sendMail' => 1,
            'approvalUrl' => Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['overtime/view', 'No' => $No])),
        ];


        $result = Yii::$app->navhelper->PortalWorkFlows($service,$data,'IanSendOverTimeForApproval');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Approval Request Sent to Supervisor Successfully.', true);
            return $this->redirect(['index']);
        }else{

            Yii::$app->session->setFlash('error', 'Error Sending Approval Request for Approval  : '. $result);
            return $this->redirect(['view','No' => $No]);

        }
    }

    /*Cancel Approval Request */

    public function actionCancelRequest($No)
    {
        $service = Yii::$app->params['ServiceName']['PortalFactory'];

        $data = [
            'applicationNo' => $No,
        ];


        $result = Yii::$app->navhelper->PortalWorkFlows($service,$data,'IanCancelOverTimeApprovalRequest');

        if(!is_string($result)){
            Yii::$app->session->setFlash('success', 'Approval Request Cancelled Successfully.', true);
            return $this->redirect(['view','No' => $No]);
        }else{

            Yii::$app->session->setFlash('error', 'Error Cancelling Approval Approval Request.  : '. $result);
            return $this->redirect(['view','No' => $No]);

        }
    }

    public function actionSetStartDate(){
        $model = new ShiftCard();
        $service = Yii::$app->params['ServiceName']['ShiftCard'];

        $filter = [
            'No' => Yii::$app->request->post('No')
        ];
        $request = Yii::$app->navhelper->getData($service, $filter);

        if(is_array($request)){
            Yii::$app->navhelper->loadmodel($request[0],$model);
            $model->Key = $request[0]->Key;
            $model->Expected_Date = Yii::$app->request->post('ShiftStartDate');

        }else{
            Yii::$app->response->format = \yii\web\response::FORMAT_JSON;
            return $request;
        }


        $result = Yii::$app->navhelper->updateData($service,$model);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;

    }


    public function actionSetStartTime(){
        $model = new ShiftCard();
        $service = Yii::$app->params['ServiceName']['ShiftCard'];

        $filter = [
            'No' => Yii::$app->request->post('No')
        ];
        $request = Yii::$app->navhelper->getData($service, $filter);

        if(is_array($request)){
            Yii::$app->navhelper->loadmodel($request[0],$model);
            $model->Key = $request[0]->Key;
            $model->Expected_Sart_Time = Yii::$app->request->post('ShiftStartTime');

        }else{
            Yii::$app->response->format = \yii\web\response::FORMAT_JSON;
            return $request;
        }


        $result = Yii::$app->navhelper->updateData($service,$model);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;

    }

    public function actionSetEndTime(){
        $model = new ShiftCard();
        $service = Yii::$app->params['ServiceName']['ShiftCard'];

        $filter = [
            'No' => Yii::$app->request->post('No')
        ];
        $request = Yii::$app->navhelper->getData($service, $filter);

        if(is_array($request)){
            Yii::$app->navhelper->loadmodel($request[0],$model);
            $model->Key = $request[0]->Key;
            $model->Expected_End_Time = Yii::$app->request->post('ShiftEndTime');

        }else{
            Yii::$app->response->format = \yii\web\response::FORMAT_JSON;
            return $request;
        }


        $result = Yii::$app->navhelper->updateData($service,$model);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;

    }

    public function actionCommit(){
        $commitModel = trim(Yii::$app->request->post('model'));
        $commitService = Yii::$app->request->post('service');
        $key = Yii::$app->request->post('key');
        $name = Yii::$app->request->post('name');
        $value = Yii::$app->request->post('value');
        $filterKey = Yii::$app->request->post('filterKey');



        $service = Yii::$app->params['ServiceName'][$commitService];

        if(!empty($filterKey))
        {
            $filter = [
                $filterKey => Yii::$app->request->post('no')
            ];
        }
        else{
            $filter = [
                'Line_No' => Yii::$app->request->post('no')
            ];
        }

        $request = Yii::$app->navhelper->getData($service, $filter);


        $data = [];
        if(is_array($request)){
            $data = [
                'Key' => $request[0]->Key,
                $name => $value
            ];
        }else{
            Yii::$app->response->format = \yii\web\response::FORMAT_JSON;
            return ['error' => $request];
        }



        $result = Yii::$app->navhelper->updateData($service,$data);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;

    }



}