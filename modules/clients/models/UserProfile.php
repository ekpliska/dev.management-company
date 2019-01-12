<?php

    namespace app\modules\clients\models;
    use app\models\User;

/**
 * Формируем информацию о профиле пользователя,
 * учавствующего в голосовании
 */
class UserProfile extends User {
    
    public static function userInfo($user_id) {
        
        $result = (new \yii\db\Query)
                ->select('u.user_id, u.user_photo, u.created_at, u.last_login,'
                        . 'c.clients_name, c.clients_second_name, c.clients_surname')
                ->from('user as u')
                ->join('LEFT JOIN', 'clients as c', 'u.user_client_id = c.clients_id')
                ->where(['u.user_id' => $user_id])
                ->one();
        
        return $result;
        
    }
    
}
