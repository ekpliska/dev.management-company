<?php

    namespace app\helpers;
    use yii\helpers\Html;
    use app\models\User;

/**
 * Форматирование вывода полного имени пользователя 
 * Пользователь ищется по номеру телефона
 */
class FormatFullNameUser {
    
    public static function fullNameByPhone($phone) {

        $full_name = '';
        
        $user = User::findByPhone($phone);
        
        $client = $user['client'] ?  $user['client'] : null;

        $rent = $user['rent'] ? $user['rent'] : null;
        
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
     * Формирование ссылки на профиль диспетчера/специалиста по ID сотрудника
     * @param integer $employer_id
     * @param boolean $disp Переключатель формирования ссылки на диспетчера (true),
     * @param boolean $disp Переключатель формирования ссылки на специалиста (false),
     * @param boolean $surname Переключатель вывода ФИО сотрудника 
     *      true - Фамилия Имя Отчество
     *      false - Фамилия И. О.
     * @param array $array_name - Массив содержит Фамилия, имя, отчество
     */
    public static function fullNameEmployee($employee_id, $disp = false, $full = false, $array_name = []) {
                
        $surname = $full ? $array_name[0] . ' ' : $array_name[0] . ' ';
        $name = $full ? $array_name[1] . ' ' : mb_substr($array_name[1], 0, 1, 'UTF-8') . '. ';
        $second_name = $full ? $array_name[2] . ' ' : mb_substr($array_name[2], 0, 1, 'UTF-8') . '.';
        
        $full_name = $surname . $name . $second_name;
        
        if ($disp == true) {
            $link = ['employee-form/employee-profile', 'type' => 'dispatcher', 'employee_id' => $employee_id];
        } else {
            $link = ['employee-form/employee-profile', 'type' => 'specialist', 'employee_id' => $employee_id];
        }
        
        return $employee_id ?
            Html::a($full_name, $link, ['target' => '_blank', 'class' => 'employee-profile']) : '<span>(Не назначен)</span>';
    }
    
    /*
     * Формирование ссылки на профиль сотрудника по ID пользователя
     * @param integer $user_id
     * Фамилия И. О.
     */
    public static function nameEmployeeByUserID($user_id) {
        
        $user = (new \yii\db\Query)
                ->select('au.item_name as role, '
                        . 'e.employee_id as id, '
                        . 'e.employee_surname as surname, e.employee_name as name, e.employee_second_name as second_name, ')
                ->from('user as u')
                ->join('LEFT JOIN', 'employees as e', 'u.user_employee_id = e.employee_id')
                ->join('LEFT JOIN', 'auth_assignment as au', 'au.user_id = u.user_id')
                ->where(['u.user_id' => $user_id])
                ->one();

        $surname = $user['surname'] . ' ';
        $name = mb_substr($user['name'], 0, 1, 'UTF-8') . '. ';
        $second_name = mb_substr($user['second_name'], 0, 1, 'UTF-8') . '.';
        $full_name = $surname . $name . $second_name;
        
        $link = ['employee-form/employee-profile', 'type' => $user['role'], 'employee_id' => $user['id']];
        
        return $user ?
            Html::a($full_name, $link, ['target' => '_blank', 'class' => 'btn btn-link btn-xs']) : 'Не назначен';
        
    }    
    
    /*
     * Формирование Фалмилии имени отчества пользователя
     * @param boolean $full | true Фамилия Имя Отчество | false Фамилия И.О.
     */
    public static function nameEmployee($surname, $name, $second_name, $full = false) {
        
        if ($surname == null || $name == null ||  $second_nam || null) {
            return '<span>(Не назначен)</span>';
        }
        
        $name = $full ? $name. ' ' : mb_substr($name, 0, 1, 'UTF-8') . '. ';
        $second_name = $full ? $second_name . ' ' : mb_substr($second_name, 0, 1, 'UTF-8') . '.';
        
        return $surname . ' ' . $name . $second_name;
        
    }
    
}
