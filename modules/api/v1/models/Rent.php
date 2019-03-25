<?php

    namespace app\modules\api\v1\models;
    use app\models\Rents;
    use app\models\User;

/**
 * Ифнормация об арендаторе
 */
class Rent extends Rents {
    
    public $email;
    
    public function rules() {
        
        return [
            [['rents_name', 'rents_second_name', 'rents_surname', 'rents_mobile', 'email'], 'required'],
            ['email', 'email'],
            
            ['rents_mobile', 'unique',
                'targetClass' => self::className(),
                'targetAttribute' => 'rents_mobile',
            ],
            
        ];
        
    }
    
    /*
     * Поиск арендатора, по номеру лицевого счета
     */
    public static function rentInfo($account) {
        
        return self::find()
                ->select([
                    'rents_id', 
                    'rents_name', 'rents_second_name', 'rents_surname',
                    'u.user_email', 'u.user_mobile'])
                ->joinWith(['user u', 'account'])
                ->where(['account_number' => $account])
                ->asArray()
                ->one();
        
    }
    
    /*
     * Обновление данных арендатора и его учетной записи
     */
    public function updateInfo() {
        
        if (!$this->validate()) {
            return false;
        }
        
        $user = User::findOne(['user_rent_id' => $this->rents_id]);
        $user->user_email = $this->email;
        $user->user_mobile = $this->rents_mobile;
        
        if (!$user->save()) {
            return false;
        }
        
        return $this->save() ? true: false;
        
    }
    
}
