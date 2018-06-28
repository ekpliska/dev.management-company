<?php
    namespace yii\console\controllers;
    use yii\console\Controller;
    use Yii;

/*
 * Инициация RBAC через консоль
 * Добавление ролей для пользователей:
 * lodger - Жилец
 * tenant - Аредатор
 * dispatcher - Сотрудник (Диспетчер)
 * specialist - Специалист
 * administrator - Администратор
*/
    
class RbacController extends Controller {
    
    public function actionInit() {
        $auth = Yii::$app->authManager;
        
        $auth->removeAll();

        /*
         *  Добавление ролей для пользователей:
         * lodger - Жилец
         * tenant - Аредатор
         * dispatcher - Сотрудник (Диспетчер)
         * specialist - Специалист
         * administrator - Администратор
         */
        
        $lodger = $auth->createRole('lodger');
        $tenant = $auth->createRole('tenant');
        $dispatcher = $auth->createRole('dispatcher');
        $specialist = $auth->cteateRole('specialist');
        $administrator = $auth->createRole('administrator');
        
        $auth->add($lodger);
        $auth->add($tenant);
        $auth->add($dispatcher);
        $auth->add($specialist);
        $auth->add($administrator);
        
        
    }
}
?>

