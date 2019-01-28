<?php

    namespace app\models;
    use Yii;
    use yii\behaviors\TimestampBehavior;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;
    use yii\data\ActiveDataProvider;
    use app\models\StatusRequest;
    use app\models\User;

/**
 * Платные услуги
 */
class PaidServices extends ActiveRecord
{
    
    const SCENARIO_ADD_SERVICE = 'add_record';
    const SCENARIO_EDIT_REQUEST = 'edit_record';
    
    /**
     * Таблица из БД
     */
    public static function tableName()
    {
        return 'paid_services';
    }
    
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }
    

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            
            [['services_servise_category_id', 'services_name_services_id', 'services_comment'], 'required', 'on' => self::SCENARIO_ADD_SERVICE],
            
            [
                'services_phone', 
                'match', 
                'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i',
                'on' => [self::SCENARIO_ADD_SERVICE, self::SCENARIO_EDIT_REQUEST],
            ],

            [['services_comment'], 'string', 'on' => [self::SCENARIO_ADD_SERVICE, self::SCENARIO_EDIT_REQUEST]],
            [['services_comment'], 'string', 'min' => 10, 'max' => 255, 'on' => [self::SCENARIO_ADD_SERVICE, self::SCENARIO_EDIT_REQUEST]],
            
            [['services_servise_category_id', 'services_name_services_id', 'created_at', 'updated_at', 'status', 'services_dispatcher_id', 'services_specialist_id', 'services_account_id'], 'integer'],
            [['services_number'], 'string', 'max' => 50],
            [['services_phone'], 'string', 'max' => 50],
            
            [[
                'services_servise_category_id', 
                'services_name_services_id', 
                'services_phone', 
                'services_account_id', 
                'services_comment'], 'required', 'on' => self::SCENARIO_EDIT_REQUEST],
            
            // Проверка на существования номера телефона, используемого в завке
            [
                'services_phone', 'exist', 
                'targetClass' => User::className(),
                'targetAttribute' => 'user_mobile',
                'message' => 'Указанный номер в системе не найден',
                'on' => self::SCENARIO_EDIT_REQUEST,
            ],
            
        ];
    }
    
    public function getService() {
        return $this->hasOne(Services::className(), ['service_id' => 'services_name_services_id']);
    }

    /*
     * Получить название категории по ID услуги
     */
    public function getNameCategory() {
        $serv = Services::find()->andWhere(['service_id' => $this->services_name_services_id])->one();
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
                        . 's.service_name, '
                        . 'p.created_at, p.services_comment, '
                        . 'e.employee_surname, e.employee_name, e.employee_second_name, '
                        . 'p.status, '
                        . 'p.updated_at')
                ->from('paid_services as p')
                ->join('LEFT JOIN', 'services as s', 's.service_id = p.services_name_services_id')
                ->join('LEFT JOIN', 'category_services as c', 'c.category_id = s.service_category_id')
                ->join('LEFT JOIN', 'employees as e', 'e.employee_id = p.services_specialist_id')
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
    
    /* Формирование идентификатора для заявки:
     *      последние 6 символов даты в unix - 
     *      ID платной заявки
     */
    private static function createNumberRequest($service_id) {
        
        $date = new \DateTime();
        $int = $date->getTimestamp();
        $order_numder = substr($int, 5) . '-' . str_pad($service_id, 2, 0, STR_PAD_LEFT);
        
        return $order_numder;
        
    }
    
    /*
     * Сохранение новой платной заявки
     */
    public function addOrder($accoint_id) {
        
        if ($this->validate()) {
            $order_numder = $this->createNumberRequest($this->services_name_services_id);

            $this->services_number = $order_numder;
            $this->services_phone = Yii::$app->userProfile->mobile;
            $this->status = StatusRequest::STATUS_NEW;
            $this->services_account_id = $accoint_id;
            $this->save();
            return $this->save() ? $order_numder : false;
        }
        return false;
        
    }
    
    public static function findRequestByIdent($request_number) {

        $request = (new \yii\db\Query)
                ->select('ps.services_id as id, ps.services_number as number,'
                        . 'ps.created_at as date_cr, ps.updated_at as date_up, ps.date_closed as date_cl, '
                        . 'ps.services_phone as phone, ps.services_comment as text, '
                        . 'ps.status as status, ps.services_comment as text, '
                        . 'ed.employee_id as employee_id_d, ed.employee_surname as surname_d, ed.employee_name as name_d, ed.employee_second_name as second_name_d, '
                        . 'es.employee_id as employee_id_s, es.employee_surname as surname_s, es.employee_name as name_s, es.employee_second_name as second_name_s, '
                        . 'cs.category_name as category, s.service_name as services_name, '
                        . 'h.houses_gis_adress as gis_adress, h.houses_number as houses_number, '
                        . 'f.flats_porch as porch, f.flats_floor as floor, f.flats_number as flat')
                ->from('paid_services as ps')
                ->join('LEFT JOIN', 'services as s', 's.service_id = ps.services_name_services_id')
                ->join('LEFT JOIN', 'category_services as cs', 'cs.category_id = s.service_category_id')
                ->join('LEFT JOIN', 'employees as ed', 'ed.employee_id = ps.services_dispatcher_id')
                ->join('LEFT JOIN', 'employees as es', 'es.employee_id = ps.services_specialist_id')
                ->join('LEFT JOIN', 'personal_account as pa', 'pa.account_id = ps.services_account_id')
                ->join('LEFT JOIN', 'flats as f', 'f.flats_id = pa.personal_flat_id')
                ->join('LEFT JOIN', 'houses as h', 'h.houses_id = f.flats_house_id')
                ->join('LEFT JOIN', 'clients as c', 'c.clients_id = pa.personal_clients_id')
                ->where(['services_number' => $request_number])
                ->one();
        
        return $request;        
        
    }
    
    /*
     * Переключение статуса для заявки на платную услугу
     */
    public function switchStatus($status) {
        
        $this->status = $status;
        
        if ($status == StatusRequest::STATUS_CLOSE) {
            $this->date_closed = time();
        } else {
            $this->date_closed = null;
        }
        
        return $this->save() ? true : false;
        
    }
    
    public static function findByID($request_id) {
        return self::find()
                ->where(['services_id' => $request_id])
                ->one();
    }
    
    /*
     * Формирование автоматичекой заявки на платную услугу
     * 
     * Услуга - Поверка прибров учета
     */
    public static function automaticRequest($account_id, $type_request, $counter_type, $counter_id) {
        
        if ($type_request == null) {
            return false;
        }
        
        $service_id = Services::find()
                ->where(['like', 'service_name', $type_request])
                ->asArray()
                ->one();
        
        if ($service_id['service_id'] == null) {
            return false;
        }
        
        $new = new PaidServices();
        
        $order_numder = static::createNumberRequest($service_id['service_id']);
        
        $request_body = "Заявка, наименование услуги: {$service_id['service_name']}. Тип прибора учета: {$counter_type}. Уникальный инедтификатор прибора учета: {$counter_id}. [Заявка сформирована автоматически]";
        
        $new->services_number = $order_numder;
        $new->services_servise_category_id = $service_id['service_category_id'];
        $new->services_name_services_id = $service_id['service_id'];
        $new->services_comment = $request_body;
        $new->services_phone = Yii::$app->userProfile->mobile;
        $new->services_account_id = $account_id;
        $new->value = $counter_id;
        
       return $new->save() ? $new->services_number : false;
       
    }
    
    /*
     * Получаем список автоматически сформированных заявок на поверку приборов учета
     * по лицевому счету
     */
    public static function notVerified($account_id) {
        
        $array = self::find()
                ->where(['services_account_id' => $account_id])
                ->andWhere(['!=', 'value', 'not null'])
                ->asArray()
                ->all();
        
        return ArrayHelper::map($array, 'value', 'services_number');
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
