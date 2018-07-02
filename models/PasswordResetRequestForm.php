<?php

    namespace app\models;
    use yii\base\Model;
    use app\models\User;
    use Yii;

class PasswordResetRequestForm extends Model {
    
    public $email;
    
    public function rules() {
        return [
            ['email', 'required'],
            ['email', 'email'],
            [
                'email', 'exist',
                'targetClass' => 'app\models\User',
                'filter' => [
                    'status' => User::STATUS_ENABLED,
                ],
                'targetAttribute' => 'user_email',
                'message' => 'Указанный электронный адрес не найден',
            ]
        ];
    }
    
    public function resetPassword() {
        $user = User::findByEmail($this->email);
        
        if (!$user) {
            return false;
        }
        
        $new_password = Yii::$app->security->generateRandomString(6);
        $user->user_password = Yii::$app->security->generatePasswordHash($new_password);
            
        if ($user->save()) {
            $this->sendEmail('ResetPassword', 'Восстановление пароля', ['new_password' => $new_password]);
        }
        return true;
    }
    
    public function sendEmail($view, $subject, $params = []) {
        $message = Yii::$app->mailer->compose(['html' => 'views/' . $view], $params)
                ->setFrom('email-confirm@site.com')
                ->setTo($this->email)
                ->setSubject($subject)
                ->send();
        return $message;
    }
    
}
