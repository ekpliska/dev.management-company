<?php

use yii\db\Migration;

/**
 * Class m190301_120648_table_user_fk
 */
class m190301_120648_table_user_fk extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey(
                'fk-user-user_client_id', 
                '{{%user}}', 
                'user_client_id', 
                '{{%clients}}', 
                'clients_id', 
                'CASCADE',
                'CASCADE'
        );
        
        $this->addForeignKey(
                'fk-user-user_rent_id', 
                '{{%user}}', 
                'user_rent_id', 
                '{{%rents}}', 
                'rents_id', 
                'CASCADE',
                'CASCADE'
        );
        
        // Создаем Сотрудника Администратор
        $this->insert('{{%employees}}', [
            'employee_id' => 1, 
            'employee_name' => 'Администратор', 
            'employee_surname' => 'Администратор', 
            'employee_second_name' => 'Администратор',
            'employee_birthday' => '01-01-1970',
            'employee_department_id' => '10',
            'employee_posts_id' => '10']);
        
        // Создаем пользователя Администратор
        $this->insert('{{%user}}', [
            'user_id' => 1, 
            'user_login' => 'administrator', 
            'user_password' => Yii::$app->security->generatePasswordHash('NhQr00'), 
            'user_email' => 'administrator@administrator.com', 
            'user_mobile' => '+7 (000) 000-00-00',
            'user_employee_id' => '1',
            'created_at' => time(), 
            'status' => 1]);
        
        // Добавляем роль и все разрешения для Администратора
        $this->batchInsert('{{%auth_assignment}}', 
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
        echo "m180901_131670_table_user_fk cannot be reverted.\n";

        return false;
    }

}
