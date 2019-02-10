<?php

    namespace app\modules\managers\models\permission;
    use Yii;
    use app\models\User;

/**
 * Установка прав доступа 
 */
class SetPermissions extends User {
    
    /*
     * Метод установки прав для пользователя
     */
    public static function addPermissions($user_id, $role = null, $array_permissions) {
        
        if (is_array($array_permissions)) {
            foreach ($array_permissions as $key => $value) {
                $permission_name = Yii::$app->authManager->getPermission($key);
                Yii::$app->authManager->assign($permission_name, $user_id);
            }
        }
        
        return true;
        
    }
    
}
