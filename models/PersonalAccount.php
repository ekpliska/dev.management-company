<?php
    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\Lodgers;

/**
 * Лицевой счет
 */
class PersonalAccount extends ActiveRecord
{
    /**
     * Таблица из БД
     */
    public static function tableName()
    {
        return 'personal_account';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [[
                'account_number', 'account_organization',
                'account_adress_street', 'account_adress_house', 'account_adress_flat',
                'account_adress_porch', 'account_adress_floor', 'account_square'], 'required'],
            [['account_adress_house', 'account_adress_floor'], 'integer'],
            [['account_number'], 'string', 'min' => 1, 'max' => 100],
            [['account_organization', 'account_adress_street'], 'string', 'min' => 5, 'max' => 255],
            [['account_adress_flat', 'account_adress_porch', 'account_square'], 'string', 'min' => 1, 'max' => 10],
            [['account_number'], 'unique'],
        ];
    }
    
    public function getLodger() {
        return $this->hasOne(Lodgers::className(), ['account_id' => 'lodger_account_id']);
    }

    /**
     * Метки для полей
     */
    public function attributeLabels()
    {
        return [
            'account_id' => 'Account ID',
            'account_number' => 'Account Number',
            'account_organization' => 'Account Organiztion',
            'account_adress_street' => 'Account Adress Street',
            'account_adress_house' => 'Account Adress House',
            'account_adress_flat' => 'Account Adress Flat',
            'account_adress_porch' => 'Account Adress Porch',
            'account_adress_floor' => 'Account Adress Floor',
            'account_square' => 'Account Square',
        ];
    }
}
