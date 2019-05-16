<?php

    use yii\db\Migration;
    use yii\rbac\DbManager;
    use \yii\base\InvalidConfigException;

/**
 * Создание RBAC таблиц
 */
class m180903_073459_table_rbac_init extends Migration
{
    /*
     * Проверка настройки компонента «authManager» 
     * для использования базы данных перед выполнением этой миграции.
     */
    public function getAuthManger()
    {    
        $auth_manager = Yii::$app->getAuthManager();
        if (!$auth_manager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }
        return $auth_manager;
        
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        
        $auth_manager = $this->getAuthManger();
        $this->db = $auth_manager->db;
        
        $table_options = null;
        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable($auth_manager->ruleTable, [
            'name' => $this->string(64)->notNull(),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY (name)',
        ], $table_options);
        
        $this->createTable($auth_manager->itemTable, [
            'name' => $this->string(64)->notNull(),
            'type' => $this->integer()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(64),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY (name)',
            'FOREIGN KEY (rule_name) REFERENCES ' . $auth_manager->ruleTable . ' (name) ON DELETE SET NULL ON UPDATE CASCADE',
        ], $table_options);
        $this->createIndex('idx-auth_item-type', $auth_manager->itemTable, 'type');
        
        $this->createTable($auth_manager->itemChildTable, [
            'parent' => $this->string(64)->notNull(),
            'child' => $this->string(64)->notNull(),
            'PRIMARY KEY (parent, child)',
            'FOREIGN KEY (parent) REFERENCES ' . $auth_manager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY (child) REFERENCES ' . $auth_manager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $table_options);
        
        $this->createTable($auth_manager->assignmentTable, [
            'item_name' => $this->string(64)->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer(),
            'PRIMARY KEY (item_name, user_id)',
            'FOREIGN KEY (item_name) REFERENCES ' . $auth_manager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $table_options);
        
        // Роли
        $this->batchInsert($auth_manager->itemTable, [
            'name', 'type', 'description', 'rule_name', 'data', 'created_at', 'updated_at'], [
                ['clients', '1', 'Собственник', NULL, NULL, time(), time()],
                ['clients_rent', '1', 'Арендатор', NULL, NULL, time(), time()],
                ['dispatcher', '1', 'Сотрудник (Диспетчер)', NULL, NULL, time(), time()],
                ['specialist', '1', 'Специалист', NULL, NULL, time(), time()],
                ['administrator', '1', 'Администратор', NULL, NULL, time(), time()],
                ['ParticipantToVote', '2', 'Участие в голосовании', NULL, NULL, time(), time()],
                ['CreateAccount', '2', 'Создание лицевого счета', NULL, NULL, time(), time()],
                ['CreateNewsDispatcher', '2', 'Добавление новостей', NULL, NULL, time(), time()],
                ['ClientsView', '2', 'Просмотр раздела Собственники', NULL, NULL, time(), time()],
                ['ClientsEdit', '2', 'Редактирование учетных записей Собственников', NULL, NULL, time(), time()],
                ['EmployeesView', '2', 'Просмотр раздела Сотрудники (Диспетчеры, Специалисты)', NULL, NULL, time(), time()],
                ['EmployeesEdit', '2', 'Редактирование учетных записей Сотрудников (Диспетчеры, Специалисты)', NULL, NULL, time(), time()],
                ['RequestsView', '2', 'Просмотр раздела Заявки', NULL, NULL, time(), time()],
                ['RequestsEdit', '2', 'Редактирование раздела Заявки', NULL, NULL, time(), time()],
                ['PaidRequestsView', '2', 'Просмотр раздела Заявки на платные услуги', NULL, NULL, time(), time()],
                ['PaidRequestsEdit', '2', 'Редактирование раздела Заявки на платные услуги', NULL, NULL, time(), time()],
                ['NewsView', '2', 'Просмотр раздела Новости', NULL, NULL, time(), time()],
                ['NewsEdit', '2', 'Редактирование раздела Новости', NULL, NULL, time(), time()],
                ['VotingsView', '2', 'Просмотр раздела Голосования', NULL, NULL, time(), time()],
                ['VotingsEdit', '2', 'Редактирование раздела Голосования', NULL, NULL, time(), time()],
                ['EstatesView', '2', 'Просмотр раздела Жилищный фонд', NULL, NULL, time(), time()],
                ['EstatesEdit', '2', 'Редактирование раздела Жилищный фонд', NULL, NULL, time(), time()],
                ['DesignerView', '2', 'Просмотр раздела Конструктор заявок', NULL, NULL, time(), time()],
                ['DesignerEdit', '2', 'Редактирование раздела Конструктор заявок', NULL, NULL, time(), time()],
                ['SettingsView', '2', 'Просмотр раздела Настройки', NULL, NULL, time(), time()],
                ['SettingsEdit', '2', 'Редактирование раздела Настройки', NULL, NULL, time(), time()],
            ]
        );
        
        // Добавляем роль для Администратора
        $this->batchInsert($auth_manager->assignmentTable, 
                ['item_name', 'user_id', 'created_at'], [
                    ['administrator', 1, time()],
                    ['ClientsView', 1, time()],
                    ['ClientsEdit', 1, time()],
                    ['EmployeesView', 1, time()],
                    ['EmployeesEdit', 1, time()],
                    ['RequestsView', 1, time()],
                    ['RequestsEdit', 1, time()],
                    ['PaidRequestsView', 1, time()],
                    ['PaidRequestsEdit', 1, time()],
                    ['NewsView', 1, time()],
                    ['NewsEdit', 1, time()],
                    ['VotingsView', 1, time()],
                    ['VotingsEdit', 1, time()],
                    ['EstatesView', 1, time()],
                    ['EstatesEdit', 1, time()],
                    ['DesignerView', 1, time()],
                    ['DesignerEdit', 1, time()],
                    ['SettingsView', 1, time()],
                    ['SettingsEdit', 1, time()]
        ]);
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth_manager = $this->getAuthManger();
        $this->db = $auth_manager->db;
        
        $this->dropTable($auth_manager->assignmentTable);
        $this->dropTable($auth_manager->itemChildTable);
        $this->dropTable($auth_manager->itemTable);
        $this->dropTable($auth_manager->ruleTable);
    }

}
