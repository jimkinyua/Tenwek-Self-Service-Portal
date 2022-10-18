<?php

namespace frontend\models;
use common\models\User;
use Yii;
use yii\base\Model;


class ShiftCard extends Model
{

public $Key;
public $No;
public $Department;
public $Job_Title;
public $Expected_Date;
public $Expected_Sart_Time;
public $Expected_End_Time;
public $Expected_Hours;
public $Shift_Employee_No;
public $Employee_Name;
public $Type;
public $Shift_Applied;
public $isNewRecord;
public $Status;
public $Employee_No;
public $Overtime_Period;
public $Rejection_Comments;
public $Worked_to_Do;

    /*public function __construct(array $config = [])
    {
        return $this->getLines($this->No);
    }*/

    public function rules()
    {
        return [
            [['Expected_Date', 'Expected_Sart_Time', 'Expected_End_Time', 'Expected_Hours', 'Overtime_Period', 'Worked_to_Do'], 'required'],

            [['Shift_Employee_No', 'Type'], 'required', 'when' => function($model) {
                return $model->Status == 'Approved';
            }, 'whenClient' => "function (attribute, value) {
                return $('#shiftcard-status').val() == 'Approved';
            }"
        ],
        ];
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