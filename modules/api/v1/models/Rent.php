<?php

    namespace app\modules\api\v1\models;
    use app\models\Rents;

/**
 * Ифнормация об арендаторе
 */
class Rent extends Rents {
    
    public static function rentInfo($rent_id) {
        
        return self::find()
                ->select([
                    'rents_id', 
                    'rents_name', 'rents_second_name', 'rents_surname',
                    'u.user_email', 'u.user_mobile', 'rents_mobile_more'])
                ->joinWith('user u')
                ->where(['rents_id' => $rent_id])
                ->asArray()
                ->one();

    }
    
}
