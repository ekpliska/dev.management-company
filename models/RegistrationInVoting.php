<?php

    namespace app\models;
    use Yii;
    use yii\db\ActiveRecord;
    use app\models\SmsSettings;

/**
 * Регистрация в голосованиии
 */
class RegistrationInVoting extends ActiveRecord
{
    /*
     * Статус участния в голосования
     * on register Пользователь поставлен на регистрацию, Отправлено СМС
     * participant Пользователь зарегистрирован, как участник
     */
    const STATUS_DISABLED = 'in register'; 
    const STATUS_ENABLED = 'participant';

    /*
     * Статус завершения голосования
     * 1 Пользователь завершил голосование
     * 0 Пользователь к голосованию не приступил
     */
    const STATUS_FINISH_YES = 1;
    const STATUS_FINISH_NO = 0;

    /**
     * Таблица в бд
     */
    public static function tableName() {
        return 'registration_in_voting';
    }

    /**
     * Правила валидации
     */
    public function rules() {
        return [
            [['voting_id', 'user_id', 'date_registration', 'random_number'], 'required'],
            [['voting_id', 'user_id', 'random_number', 'date_registration', 'status'], 'integer'],
            [['voting_id'], 'exist', 'skipOnError' => true, 'targetClass' => Voting::className(), 'targetAttribute' => ['voting_id' => 'voting_id']],
        ];
    }

    /**
     * Связь с таблицей Голосование
     */
    public function getVoting() {
        return $this->hasOne(Voting::className(), ['voting_id' => 'voting_id']);
    }
    
    /*
     * Связь с таблицей Пользователи
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['user_id' => 'user_id']);
    }
    
    /*
     * Создаем запись регистрации участия в голосовании пользователя
     */
    public function registerIn($voting_id) {
        
        $user_id = Yii::$app->user->identity->id;
        // Проверяем наличие регистрации у пользователя на текущее голосовнаие
        $register = RegistrationInVoting::find()
                ->andWhere(['voting_id' => $voting_id, 'user_id' => $user_id])
                ->asArray()
                ->one();
        
        $number = mt_rand(10000, 99999);
        $this->voting_id = $voting_id;
        $this->user_id = Yii::$app->user->identity->id;
        $this->random_number = $number;
        $this->date_registration = time();
        
        $phone = preg_replace('/[^0-9]/', '', Yii::$app->userProfile->mobile);
        
        if (!$result = Yii::$app->sms->generalMethodSendSms(SmsSettings::TYPE_NOTICE_PARTICIPANT_VOTING, $phone, $number)) {
            // return ['success' => false, 'message' => $result];
            return ['success' => false];
        }
        
        return $this->save() ? true : false;
        
    }
    
    /*
     * Поиск записи о регистрации на голосование
     * 
     * @param integer $voting_id ID голосование
     * @param integer $user_id ID текущего пользователя
     */
    public static function findById($voting_id) {
        
        $user_id = Yii::$app->user->identity->id;
        $register = RegistrationInVoting::find()
                ->andWhere(['voting_id' => $voting_id, 'user_id' => $user_id])
                ->one();
        return $register;
    }
    
    /*
     * Удаление записи о регистрации на голосование
     * 
     * @param integer $voting_id ID голосование
     * @param integer $user_id ID текущего пользователя
     */
    public static function deleteRegisterById($voting_id) {
        
        $user_id = Yii::$app->user->identity->id;
        $register = RegistrationInVoting::find()
                ->andWhere([
                    'voting_id' => $voting_id, 
                    'user_id' => $user_id,
                    'status' => self::STATUS_DISABLED])
                ->one();
        
        if ($register != null) {
            $register->delete();
        }
        
        return true;
        
    }
    
    /*
     * Генерация нового СМС кода
     */
    public function generateNewSMSCode() {
        
        $phone = preg_replace('/[^0-9]/', '', Yii::$app->userProfile->mobile);
        
        $new_value = $number = mt_rand(10000, 99999);
        $this->random_number = $new_value;
        
        if (!$result = Yii::$app->sms->generalMethodSendSms(SmsSettings::TYPE_NOTICE_REPEAT_SMS, $phone, $new_value)) {
//            return ['success' => false, 'message' => $result];
            return ['success' => false];
        }
        
        return $this->save(false);
        
    }
    
    /*
     * Получить список всех участников
     */
    public static function getParticipants($voting_id) {
        
        $result = (new \yii\db\Query)
                ->select('u.user_id, u.user_photo, u.created_at, u.last_login,'
                        . 'c.clients_name')
                ->from('registration_in_voting as r')
                ->join('LEFT JOIN', 'user as u', 'u.user_id = r.user_id')
                ->join('LEFT JOIN', 'clients as c', 'u.user_client_id = c.clients_id')
                ->where(['r.voting_id' => $voting_id])
                ->andWhere(['r.status' => self::STATUS_ENABLED])
                ->andWhere(['r.finished' => self::STATUS_FINISH_YES])
                ->all();
        
        return $result;
        
    }
    
    /*
     * Регистрация пользователя, как участника голосования
     */
    public static function registrationUser($voting_id) {
        
        $user_id = Yii::$app->user->identity->id;
        
        $record = self::find()
                ->where(['voting_id' => $voting_id, 'user_id' => $user_id])
                ->one();
        
        $record->random_number = 0;
        $record->status = self::STATUS_ENABLED;
        
        return $record->save(false) ? true : false;
        
    }
    
    /*
     * Завершение голосования
     */
    public static function finishVoting($voting_id) {
        
        $user_id = Yii::$app->user->identity->id;
        
        $record = self::find()
                ->where(['voting_id' => $voting_id, 'user_id' => $user_id])
                ->one();
        
        $record->finished = self::STATUS_FINISH_YES;
        
        return $record->save(false) ? true : false;
    }
    
    /*
     * Отправка СМС-кода на телефон Собственнику
     */
    private function sendSms($code) {
        
        $phone = preg_replace('/[^0-9]/', '', Yii::$app->userProfile->mobile);

        $sms = Yii::$app->sms;
        $result = $sms->send_sms($phone, 'Для участия в голосовании укажите СМС-код ' . $code);
        if (!$sms->isSuccess($result)) {
//            echo $sms->getError($result);
            return false;
        }
        
        return true;
    }
    
    /**
     * Аттрибуты полей
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'voting_id' => 'Voting ID',
            'user_id' => 'User ID',
            'random_number' => 'Random Number',
            'date_registration' => 'Date Registration',
            'status' => 'Status',
        ];
    }

}
