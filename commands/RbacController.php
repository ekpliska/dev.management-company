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
 * clients - Собственник
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

        /*
         * Роли
         */
        $clients = $auth->createRole('clients');
        $clients->description = 'Собственник';
        $auth->add($clients);
        
        $clients_rent = $auth->createRole('clients_rent');
        $clients_rent->description = 'Арендатор';
        $auth->add($clients_rent);        
        
        $dispatcher = $auth->createRole('dispatcher');
        $dispatcher->description = 'Диспетчер';
        $auth->add($dispatcher);        
        
        $specialist = $auth->createRole('specialist');
        $specialist->description = 'Специалист';
        $auth->add($specialist);
                
        $administrator = $auth->createRole('administrator');
        $administrator->description = 'Администратор';
        $auth->add($administrator);

        /*
         *  Разрешение для Собственников и Арендаторов
         */
        $vote = $auth->createPermission('ParticipantToVote');
        $vote->description('Участие в голосовании');
        $auth->add($vote);
        
        $create_account = $auth->createPermission('CreateAccount');
        $create_account->description('Создание лицевого счета');
        $auth->add($create_account);
        
        
        /*
         * Разрешения для Диспетчера
         */
        $create_news_dispatcher = $auth->createPermission('CreateNewsDispatcher');
        $create_news_dispatcher->description('Добавление новостей');
        $auth->add($create_news_dispatcher);
        
        /*
         * Разрешения для Администратора
         */
        // Собсвенники
        $clients_view = $auth->createPermission('ClientsView');
        $clients_view->description('Просмотр раздела Собственники');
        $auth->add($clients_view);
        
        $clients_edit = $auth->createPermission('ClientsEdit');
        $clients_edit->description('Редактирование учетных записей Собственников');
        $auth->add($clients_edit);
        
        // Сотрудники
        $employees_view= $auth->createPermission('EmployeesView');
        $employees_view->description('Просмотр раздела Сотрудники (Диспетчеры, Специалисты)');
        $auth->add($employees_view);
        
        $employees_edit= $auth->createPermission('EmployeesEdit');
        $employees_edit->description('Редактирование учетных записей Сотрудников (Диспетчеры, Специалисты)');
        $auth->add($employees_edit);
        
        // Услуги
        $services_view= $auth->createPermission('ServicesView');
        $services_view->description('Просмотр раздела Услуги');
        $auth->add($services_view);
        
        $services_edit= $auth->createPermission('ServicesEdit');
        $services_edit->description('Редактирование раздела Услуги');
        $auth->add($services_edit);
        
        // Заявки
        $requests_view= $auth->createPermission('RequestsView');
        $requests_view->description('Просмотр раздела Заявки');
        $auth->add($requests_view);
        
        $requests_edit= $auth->createPermission('RequestsEdit');
        $requests_edit->description('Редактирование раздела Заявки');
        $auth->add($requests_edit);
        
        // Платыне заявки
        $paid_reques_view= $auth->createPermission('PaidReuestsView');
        $paid_reques_view->description('Просмотр раздела Заявки на платные услуги');
        $auth->add($paid_reques_view);
        
        $paid_reques_edit= $auth->createPermission('PaidReuestsEdit');
        $paid_reques_edit->description('Редактирование раздела Заявки на платные услуги');
        $auth->add($paid_reques_edit);
        
        // Новости
        $news_view= $auth->createPermission('NewsView');
        $news_view->description('Просмотр раздела Новости');
        $auth->add($news_view);
        
        $news_edit= $auth->createPermission('NewsEdit');
        $news_edit->description('Редактирование раздела Новости');
        $auth->add($news_edit);        
        
        // Голосование
        $votings_view= $auth->createPermission('VotingsView');
        $votings_view->description('Просмотр раздела Голосования');
        $auth->add($votings_view);
        
        $votings_edit= $auth->createPermission('VotingsEdit');
        $votings_edit->description('Редактирование раздела Голосования');
        $auth->add($votings_edit);        
        
        // Жилищный фонд
        $estates_view= $auth->createPermission('EstatesView');
        $estates_view->description('Просмотр раздела Жилищный фонд');
        $auth->add($estates_view);
        
        $estates_edit= $auth->createPermission('EstatesEdit');
        $estates_edit->description('Редактирование раздела Жилищный фонд');
        $auth->add($estates_edit);        
        
        // Конструктор заявок
        $designer_view= $auth->createPermission('DesignerView');
        $designer_view->description('Просмотр раздела Конструктор заявок');
        $auth->add($designer_view);
        
        $designer_edit= $auth->createPermission('DesignerEdit');
        $designer_edit->description('Редактирование раздела Конструктор заявок');
        $auth->add($designer_edit);        
        
        // Настройки
        $settings_view= $auth->createPermission('SettingsView');
        $settings_view->description('Просмотр раздела Настройки');
        $auth->add($settings_view);
        
        $settings_edit= $auth->createPermission('SettingsEdit');
        $settings_edit->description('Редактирование раздела Настройки');
        $auth->add($settings_edit);        
        
        
        $this->stdout('here', PHP_EOL);
        
    }
}
?>

