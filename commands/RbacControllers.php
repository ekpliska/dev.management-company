<?php
    namespace app\commands;
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
        
        if (!$this->confirm("Данная команда удалит текущие роли и разрешения и пересоздаст исходные. Продолжть?")) {
            return self::EXIT_CODE_NORMAL;
        }
        
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
        $lodger->description = 'Жилец';
        $auth->add($lodger);
        
        $tenant = $auth->createRole('tenant');
        $tenant->description = 'Арендатор';
        $auth->add($tenant);        
        
        $dispatcher = $auth->createRole('dispatcher');
        $dispatcher->description = 'Сотрудник (Диспетчер)';
        $auth->add($dispatcher);        
        
        $specialist = $auth->cteateRole('specialist');
        $specialist->description = 'Специалист';
        $auth->add($specialist);
                
        $administrator = $auth->createRole('administrator');
        $administrator->description = 'Администратор';
        $auth->add($administrator);

        // Разрешение adminPanel - проверяет доступ к панеле управления 
        $adminPanel = $auth->createPermission('AdminPanel');
        $adminPanel->description = 'Панель администратора';
        $auth->add($adminPanel);
        
        // Администратор обладает правами всех других пользователей
        $auth->addChild($administrator, $lodger);
        $auth->addChild($administrator, $tenant);
        $auth->addChild($administrator, $dispatcher);
        $auth->addChild($administrator, $specialist);
        $auth->addChild($administrator, $adminPanel);
        
        $this->stdout('Формирование ролей и разрешений выполнено!', PHP_EOL);
        
    }
}
?>

