<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\data\ActiveDataProvider;
    use app\models\StatusRequest;

/**
 * Платные услуги
 */
class PaidServices extends ActiveRecord
{
    
    const SCENARIO_ADD_SERVICE = 'add_record';
    
    /**
     * Таблица из БД
     */
    public static function tableName()
    {
        return 'paid_services';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            
            [['services_name_services_id', 'services_phone', 'services_comment'], 'required', 'on' => self::SCENARIO_ADD_SERVICE],
            
            [
                'services_phone', 
                'match', 
                'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i',
                'on' => self::SCENARIO_ADD_SERVICE,
            ],

            [['services_comment'], 'string', 'on' => self::SCENARIO_ADD_SERVICE],
            [['services_comment'], 'string', 'min' => 10, 'max' => 255, 'on' => self::SCENARIO_ADD_SERVICE],
            
            [['services_name_services_id', 'created_at', 'updated_at', 'status', 'services_dispatcher_id', 'services_specialist_id', 'services_account_id'], 'integer'],
            [['services_number'], 'string', 'max' => 16],
            [['services_phone'], 'string', 'max' => 50],
        ];
    }
    
    public function getService() {
        return $this->hasOne(Services::className(), ['services_id' => 'services_name_services_id']);
    }

    /*
     * Получить название категории по ID услуги
     */
    public function getNameCategory() {
        $serv = Services::find()->andWhere(['services_id' => $this->services_name_services_id])->one();
        return ArrayHelper::getValue(CategoryServices::getCategoryNameArray(), $serv->services_category_id);
    }
    
    /*
     * Получить название услуги по ID
     */
    public function getNameServices() {
        return ArrayHelper::getValue(Services::getServicesNameArray(), $this->services_name_services_id);
    }    

    /*
     * Получить все заявки, текущего пользователя
     * @param ActiveQuery $all_orders
     */
    public static function getOrderByUder($account_id) {
        
        $query = (new \yii\db\Query())
                ->select('p.services_number, '
                        . 'c.category_name, '
                        . 's.services_name, '
                        . 'p.created_at, p.services_comment, '
                        . 'p.services_specialist_id,'
                        . 'p.status,'
                        . 'p.updated_at')
                ->from('paid_services as p')
                ->join('LEFT JOIN', 'services as s', 's.services_id = p.services_name_services_id')
                ->join('LEFT JOIN', 'category_services as c', 'c.category_id = s.services_category_id')
                ->andWhere(['services_account_id' => $account_id])
                ->orderBy(['created_at' => SORT_DESC]);
        
        $all_orders = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'forcePageParam' => false,
                    'pageSizeParam' => false,
                    'pageSize' => (Yii::$app->params['countRec']['client']) ? Yii::$app->params['countRec']['client'] : 15,
                ],
            ]);
        
        return $all_orders;
        
    }
    
    /*
     * Сохранение новой платной заявки
     */
    public function addOrder($accoint_id) {
        
        if ($this->validate()) {
        
            /* Формирование идентификатора для заявки:
             *      последние 7 символов лицевого счета - 
             *      последние 6 символов даты в unix - 
             *      тип платной заявки
             */

            $account = PersonalAccount::findByAccountID($accoint_id);

            $date = new \DateTime();
            $int = $date->getTimestamp();

            $order_numder = substr($account->account_number, 4) . '-' . substr($int, 5) . '-' . str_pad($this->services_name_services_id, 2, 0, STR_PAD_LEFT);

            $this->services_number = $order_numder;
            $this->status = StatusRequest::STATUS_NEW;
            $this->services_account_id = $accoint_id;
            $this->save();
            
            Yii::$app->session->setFlash('paid-services', [
                'success' => true,
                'message' => 'Ваша заявка на платную услугу была успешно сформирована на лицевой счет №' . $account->account_number . '<br />' .
                    'Номер вашей заявки №' . $order_numder . '<br />' .
                    'Ознакомиться со списков ваших платных заявок можно пройдя по ' . Html::a('ссылке', ['paid-services/index'])
            ]);
            
            return true;
            
        }

        Yii::$app->session->setFlash('paid-services', [
                'success' => false,
                'error' => 'При формировании заявки на платную услугу возникла ошибка. Обновите страницу и повторите действия еще раз',
        ]);
        return false;
        
    }
    
    
    /**
     * Настройка полей для форм
     */
    public function attributeLabels()
    {
        return [
            'services_id' => 'Services ID',
            'services_number' => 'Номер',
            'services_name_services_id' => 'Наименование услуги',
            'services_comment' => 'Текст заявки',
            'services_phone' => 'Ваш телефон',
            'created_at' => 'Дата заявки',
            'updated_at' => 'Дата закрытия',
            'status' => 'Статус',
            'services_dispatcher_id' => 'Диспетчер',
            'services_specialist_id' => 'Специалист',
            'services_account_id' => 'Лицевой счет',
        ];
    }
}
