<?php

    namespace app\helpers;
    use app\models\User;
    use app\models\Rents;
    use app\models\Clients;
    use app\models\Employers;

/**
 * Форматирование вывода полного имени пользователя
 */
class FormatFullNameUser {
    
    public static function fullNameByPhone($phone) {

        $full_name = '';
        
        $client = Clients::find()
                ->where(['clients_mobile' => $phone])
                ->orWhere(['clients_phone' => $phone])
                ->asArray()
                ->one();

        $rent = Rents::find()
                ->where(['rents_mobile' => $phone])
                ->orWhere(['rents_mobile_more' => $phone])
                ->asArray()
                ->one();
        
        if ($client == null && $rent == null) {
            $full_name = 'Не задано';
        } elseif ($client != null && $rent == null) {
            $full_name = $client['clients_surname'] . ' ' . $client['clients_name'] . ' ' . $client['clients_second_name'];
        } elseif ($client == null && $rent != null) {
            $full_name = $rent['rents_surname'] . ' ' . $rent['rents_name'] . ' ' . $rent['rents_second_name'];
        }
        
        return $full_name;
        
    }
    
}
