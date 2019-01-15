<?php

    namespace app\helpers;
    use yii\helpers\Html;
    use app\models\User;
    use app\models\Rents;
    use app\models\Clients;
    use app\models\Employees;

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
     * Формирование ссылки на профиль диспетчера/специалиста по ID сотрудника
     * @param integer $employer_id
     * @param boolean $disp Переключатель формирования ссылки на диспетчера (true),
     * @param boolean $disp Переключатель формирования ссылки на специалиста (false),
     * @param boolean $full Переключатель вывода ФИО сотрудника 
     *      true - Фамилия Имя Отчество
     *      false - Фамилия И. О.
     */
    public static function fullNameEmployer($employee_id, $disp = false, $full = false) {
        
        $employee = Employees::find()
                ->where(['employee_id' => $employee_id])
                ->asArray()
                ->one();
        
        $surname = $full ? $employee['employee_surname'] . ' ' : $employee['employee_surname'] . ' ';
        $name = $full ? $employee['employee_name'] . ' ' : mb_substr($employee['employee_name'], 0, 1, 'UTF-8') . '. ';
        $second_name = $full ? $employee['employee_second_name'] . ' ' : mb_substr($employee['employee_second_name'], 0, 1, 'UTF-8') . '.';
        
        $full_name = $surname . $name . $second_name;
        
        if ($disp == true) {
            $link = ['employee-form/employee-profile', 'type' => 'dispatcher', 'employee_id' => $employee['employee_id']];
        } else {
            $link = ['employee-form/employee-profile', 'type' => 'specialist', 'employee_id' => $employee['employee_id']];
        }
        
        return $employee ?
            Html::a($full_name, $link, ['target' => '_blank']) : 'Не назначен';
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
    
}
