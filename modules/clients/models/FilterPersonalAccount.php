<?php
    
    namespace app\modules\clients\models;
    use yii\base\Model;
    use app\models\PersonalAccount;

/**
 * Форма фильтрации для страницы Лицевой счет / Общая информация
 */
class FilterPersonalAccount extends Model {
    
    public $account_number;
    
    public function attributeLabels() {
        return [
            'account_number' => 'Лицевой счет',
        ];
    }
}
