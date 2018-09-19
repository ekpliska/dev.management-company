<?php

    namespace app\modules\managers\models;
    use Yii;
    use yii\helpers\ArrayHelper;
    use app\models\User as BaseUser;

/**
 * Пользователи
 *
 * Наследуется от основной модели Пользователи
 */
class User extends BaseUser {
    
    
    /*
     * Получить список всех доступнвых ролей
     */
    public static function getRole() {
        
        $list = Yii::$app->authManager->getRoles();
        return ArrayHelper::map($list, 'name', 'description');
        
    }    
    
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
    
}
