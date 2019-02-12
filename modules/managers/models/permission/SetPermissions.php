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
    
    /*
     * Метод для перезаписи прав пользователей, при редактировании учетной записи
     * 
     * Сначала по таблице auth_assignment находим все записи по ID пользователя, и удаляем записи
     * В пришедшем массиве, перенезначаем роль и разрешения для выбранного пользователя
     * 
     * @param array $array_permissions Разрешения, пришедщие из пост запроса
     * 
     */
    public static function changePermissions($user_id, $role, $array_permissions) {

        $remove_record = Yii::$app->db->createCommand()
                ->delete('auth_assignment', ['user_id' => $user_id])
                ->execute();
        
        // Назначаем роль
        $role_name = Yii::$app->authManager->getRole($role);
        Yii::$app->authManager->assign($role_name, $user_id);
        
        // Назначаем разрешений
        if (is_array($array_permissions)) {
            foreach ($array_permissions as $key => $value) {
                $permission_name = Yii::$app->authManager->getPermission($key);
                Yii::$app->authManager->assign($permission_name, $user_id);
            }
        }
        
        return;
        
        
    }
    
}
