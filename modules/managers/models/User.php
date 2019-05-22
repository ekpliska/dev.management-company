<?php

    namespace app\modules\managers\models;
    use Yii;
    use app\models\User as BaseUser;

/**
 * Пользователи
 *
 * Наследуется от основной модели Пользователи
 */
class User extends BaseUser {
    
    public $permission_list;
    
    public function rules() {
        
        return [
            ['user_check_email', 'boolean'],
            ['permission_list', 'safe'],
            [['user_login', 'user_email', 'user_mobile'], 'required'],
            
            ['user_login', 'unique', 
                'targetClass' => self::className(),
                'targetAttribute' => 'user_login',
                'message' => 'Данное имя пользователя уже используется в системе',
            ],
            
            ['user_email', 'unique', 
                'targetClass' => self::className(),
                'targetAttribute' => 'user_email',
                'message' => 'Данный электронный адрес уже используется в системе',
            ],
            
            ['user_mobile', 'unique', 
                'targetClass' => self::className(),
                'targetAttribute' => 'user_mobile',
                'message' => 'Данный номер мобильного телефона уже используется в системе',
            ],
            
        ];
        
    }

    /*
     * Блокировать пользователя, по ID собсвенника
     */
    public function block($client_id, $status) {
        
        $user_info = self::find()
                ->where(['user_client_id' => $client_id])
                ->one();
        
        if ($status == User::STATUS_BLOCK) {
            $user_info->status = User::STATUS_BLOCK;
        } elseif ($status == User::STATUS_ENABLED) {
            $user_info->status = User::STATUS_ENABLED;
        }
        
        return $user_info->save() ? true : false;
        
    }
    
    /*
     * Блокировать пользователя, по ID пользователя
     */
    public function blockInView($user_id, $status) {
        
        $user_info = self::find()
                ->where(['user_id' => $user_id])
                ->one();
        
        if ($status == User::STATUS_BLOCK) {
            $user_info->status = User::STATUS_BLOCK;
        } elseif ($status == User::STATUS_ENABLED) {
            $user_info->status = User::STATUS_ENABLED;
        }
        
        return $user_info->save() ? true : false;
        
    }
    
    /*
     * Получить список новых пользователей
     * за последнуюю неделю
     */
    public static function getNewUser() {
        
        $current_date = time();
        $end_date = strtotime('-1 week');
        
        $user_lists = Clients::find()
                ->joinWith('user as u')
                ->where(['between', 'u.created_at', $end_date, $current_date])
                ->orderBy(['u.created_at' => SORT_DESC])
                ->limit(10)
                ->all();
        
        return $user_lists;
        
    }
}
