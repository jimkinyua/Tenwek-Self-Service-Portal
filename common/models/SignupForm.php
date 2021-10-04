<?php
namespace common\models;

use Yii;
use yii\base\Model;
use common\models\Hruser;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $email;
    public $password;
    public $confirmpassword;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\Hruser', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['confirmpassword','compare','compareAttribute'=>'password','message'=>'Passwords do not match, try again'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new Hruser();
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        return $user->save() && $this->sendEmail($user);

    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'recruitmentemailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->recruitment . ' HR'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->recruitment)
            ->send();
    }

    // public function goHome()
    // {
    //     return Yii::$app->getResponse()->redirect(Yii::$app->urlManager->createAbsoluteUrl(['recruitment/login']));
    // }
}
