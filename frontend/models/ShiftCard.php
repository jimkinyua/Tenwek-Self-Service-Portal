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

    /*public function __construct(array $config = [])
    {
        return $this->getLines($this->No);
    }*/

    public function rules()
    {
        return [
            [['Expected_Date', 'Expected_Sart_Time', 'Expected_End_Time', 'Expected_Hours'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'Expected_Sart_Time' => 'Expected Start Time',
            'Global_Dimension_2_Code' => 'Project Code',
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