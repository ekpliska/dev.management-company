<?php

    namespace app\helpers;
    use yii\helpers\Html;
    use app\models\User;
    use app\models\Rents;
    use app\models\Clients;
    use app\models\Employers;

/**
 * Форматирование вывода полного имени пользователя 
 * Пользователь ищется по номеру телефона
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
    
    /*
     * Формирование ссылки на профиль диспетчера/специалиста
     * @param integer $employer_id
     * @param boolean $disp Переключатель формирования ссылки на диспетчера (true),
     * @param boolean $disp Переключатель формирования ссылки на специалиста (false),
     */
    public static function fullNameEmployer($employer_id, $disp = false) {
        
        $employer = Employers::find()
                ->where(['employers_id' => $employer_id])
                ->asArray()
                ->one();
        
        $surname = $employer['employers_surname'];
        $name = mb_substr($employer['employers_name'], 0, 1, 'UTF-8');
        $second_name = mb_substr($employer['employers_second_name'], 0, 1, 'UTF-8');
        
        $full_name = $surname . ' ' . $name . '. ' . $second_name . '.';

        
        if ($disp == true) {
            $link = ['employers/edit-dispatcher', 'dispatcher_id' => $employer['employers_id']];
        } else {
            $link = ['employers/edit-specialist', 'specialist_id' => $employer['employers_id']];
        }
        
        return $employer ?
            Html::a($full_name, $link, ['target' => '_blank']) : 'Не назначен';
    }

    
}
