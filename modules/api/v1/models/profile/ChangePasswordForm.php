<?php

    namespace app\modules\api\v1\models\profile;
    use Yii;
    use yii\base\Model;
    use app\models\User;
    use app\models\Token;

/*
 * Смена пароля
 */
class ChangePasswordForm extends Model {
    
    public $old_password;
    public $new_password;
    
    private $_user;


    public function __construct(User $user, $config = []) {
        
        $this->_user = $user;
        parent::__construct($config);
        
    }
    
    public function rules() {
        
        return [
            [['old_password', 'new_password'], 'required'],
            [['new_password'],
                'match', 
                'pattern' => '/^[A-Za-z0-9\\_\\-]+$/iu', 
                'message' => 'Вы используете запрещенные символы',
            ],
            ['old_password', 'checkOldPassword'],
        ];
        
    }
    
    public function checkOldPassword($attribute, $param) {
        
        if (!$this->hasErrors()) {
            if (!$this->_user->validatePassword($this->$attribute)) {
                $this->addError($attribute, 'Старый пароль указан не верно');
            }
        }
        
    }
    
    /*
     * Установка нового пароля
     */
    public function changePassword() {
        
        if ($this->validate()) {
            
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $new_token = $this->setNewToken();
                if (!$new_token) {
                    return false;
                }
                $user = $this->_user;
                $user->setUserPassword($this->new_password);
                if (!$user->save()) {
                    return false;
                }
                
                return ['success' => true, 'token' => $new_token];
                
                $transaction->commit();
                
            } catch (Exception $ex) {
                $transaction->rollBack();
            }
        }
        
        return false;
        
    }
    
    /*
     * Сброс токена
     * Удаляем все токены текущего пользователя, формируем новуй токен
     */
    private function setNewToken() {
        
        if (Token::deleteAll(['user_uid' => $this->_user->user_id])) {
            $token = new Token();
            $token->user_uid = $this->_user->user_id;
            $token->generateToken(time() + 3600 * 24 * 365);
            return $token->save() ? $token->token : false;
        }
        return false;
        
    }
    
    
    
}
