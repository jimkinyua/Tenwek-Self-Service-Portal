<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
    Hello <?= $user->email ?>,
    You registered an account on Tenwek Recruitment Portal,
    before being able to use your account you need to verify 
    that this is your email address by clicking here: <?= $verifyLink ?>

    Kind Regards, [Tenwek Hospital]

