<?php
    namespace app\commands;
    use yii\console\Controller;
    use Yii;


/*
 * Испольщование таблиц БД
 * 
 */    

/*
 * Инициация RBAC через консоль
 * Добавление ролей для пользователей:
 * clients - Жилец
 * clients_rent - Аредатор
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

        $clients = $auth->createRole('clients');
        $clients->description = 'Жилец';
        $auth->add($clients);
        
        $clients_rent = $auth->createRole('clients_rent');
        $clients_rent->description = 'Арендатор';
        $auth->add($clients_rent);        
        
        $dispatcher = $auth->createRole('dispatcher');
        $dispatcher->description = 'Сотрудник (Диспетчер)';
        $auth->add($dispatcher);        
        
        $specialist = $auth->createRole('specialist');
        $specialist->description = 'Специалист';
        $auth->add($specialist);
                
        $administrator = $auth->createRole('administrator');
        $administrator->description = 'Администратор';
        $auth->add($administrator);

        // Разрешение adminPanel - проверяет доступ к панеле управления 
//        $adminPanel = $auth->createPermission('AdminPanel');
//        $adminPanel->description = 'Панель администратора';
//        $auth->add($adminPanel);
        
        // Администратор обладает правами всех других пользователей
        /*
        $auth->addChild($administrator, $clients);
        $auth->addChild($administrator, $clients_rent);
        $auth->addChild($administrator, $dispatcher);
        $auth->addChild($administrator, $specialist);
        $auth->addChild($administrator, $adminPanel);
         */
        
        $this->stdout('Формирование ролей и разрешений выполнено!', PHP_EOL);
        
    }
}
?>

