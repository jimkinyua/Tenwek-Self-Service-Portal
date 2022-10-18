<?php

namespace frontend\models;
use common\models\User;
use Yii;
use yii\base\Model;


class TrackApprovals extends Model
{

public $Key;
public $Document_No;
public $Sender_ID;
public $Approver_ID;
public $Status;
public $Sequence_No;
public $Approval_Date;

    /*public function __construct(array $config = [])
    {
        return $this->getLines($this->No);
    }*/

    public function rules()
    {
        return [];
    }

    public function attributeLabels()
    {
        return [
            'Expected_Sart_Time' => 'Expected Start Time',
            'Global_Dimension_2_Code' => 'Project Code',
            'Worked_to_Do'=>'Work To Do'
        ];
    }

    public function getLines(){
        $service = Yii::$app->params['ServiceName']['OvertimeLine'];
        $filter = [
            'Application_No' => $this->No,
            'Employee_No' => $this->Employee_No,

        ];

        $lines = Yii::$app->navhelper->getData($service, $filter);
       return $lines;


    }



}