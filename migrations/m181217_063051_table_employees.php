<?php

use yii\db\Migration;

/**
 * Сотрудники
 */
class m181217_063051_table_employees extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        
        $table_options = null;
        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        // Подразделение
        $this->createTable('{{%departments}}', [
            'department_id' => $this->primaryKey(),
            'department_name' => $this->string(100)->notNull(),
        ]);
        $this->createIndex('idx-departments-department_id', '{{%departments}}', 'department_id');
        
        // Подразделения по умолчанию
        $this->batchInsert('{{%departments}}', 
                ['department_id', 'department_name'], [
                    ['1', 'Аварийно-диспетчерская служба'],
                    ['2', 'Отдел эксплуатации и содержания'],
                    ['3', 'Электромонтажная служба'],
                    ['4', 'Сантехническая служба'],
                    ['5', 'Клининговая служба'],
                    ['6', 'Лифтовая служба'],
                    ['7', 'Общестроительный отдел'],
                    ['8', 'Бухгалтерия'],
                    ['9', 'Паспортно-регистрационный отдел'],
                    ['10', 'ИТ отдел'],
                    ['11', 'Отдел по работе с жалобами и претензиями'],
                    ['12', 'Отдел утилизации бытовых отходов'],
                    ['13', 'Служба безопасности'],
                ]);
        
        // Должность
        $this->createTable('{{%posts}}', [
            'post_id' => $this->primaryKey(),
            'post_name' => $this->string(100)->notNull(),
            'posts_department_id' => $this->integer()->notNull(),
        ]);
        $this->createIndex('idx-posts-post_id', '{{%posts}}', 'post_id');
        
        // Должности по умолчанию
        $this->batchInsert('{{%posts}}', 
                ['post_name', 'posts_department_id'], [
                    ['Управляющий', '7'],
                    ['Инженер', '2'],
                    ['Электрик', '3'],
                    ['Сантехник', '4'],
                    ['Паспортист', '7'],
                    ['Бухгалтер', '8'],
                    ['Слесарь', '4'],
                    ['Электромеханник', '3'],
                    ['Мастер участка', '11'],
                    ['ИТ-специалист', '10'],
                    ['Отделочник', '2'],
                    ['Эколог', '12'],
                    ['Менеджер по клинингу', '5'],
                    ['Охранник', '13'],
                ]);
        
        // Сотрудники
        $this->createTable('{{%employees}}', [
            'employee_id' => $this->primaryKey(),
            'employee_name' => $this->string(70)->notNull(),
            'employee_surname' => $this->string(70)->notNull(),
            'employee_second_name' => $this->string(70)->notNull(),
            'employee_birthday' => $this->string(70)->notNull(),
            'employee_department_id' => $this->integer()->notNull(),
            'employee_posts_id' => $this->integer()->notNull(),
        ]);
        
        $this->createIndex('ind-employees-employee_id', '{{%employees}}', 'employee_id');

        $this->addForeignKey(
                'fk-posts-posts_department_id', 
                '{{%posts}}', 
                'posts_department_id', 
                '{{%departments}}', 
                'department_id', 
                'RESTRICT',
                'CASCADE'
        );
        
        $this->addForeignKey(
                'fk-employees-employee_department_id', 
                '{{%employees}}', 
                'employee_department_id', 
                '{{%departments}}', 
                'department_id', 
                'RESTRICT',
                'CASCADE'
        );
        
        $this->addForeignKey(
                'fk-employees-employee_posts_id', 
                '{{%employees}}', 
                'employee_posts_id', 
                '{{%posts}}', 
                'post_id', 
                'RESTRICT',
                'CASCADE'
        );

        $this->addForeignKey(
                'fk-user-user_employee_id', 
                '{{%user}}', 
                'user_employee_id', 
                '{{%employees}}', 
                'employee_id', 
                'CASCADE',
                'CASCADE'
        );        

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        
        $this->dropIndex('idx-departments-department_id', '{{%departments}}');
        $this->dropIndex('idx-posts-post_id', '{{%posts}}');
        $this->dropIndex('ind-employees-employee_id', '{{%employees}}');
        
        $this->addForeignKey('fk-posts-posts_department_id', '{{%posts}}');
        $this->dropForeignKey('fk-employees-employee_department_id', '{{%employees}}');
        $this->dropForeignKey('fk-employees-employee_posts_id', '{{%employees}}');
        
        $this->addForeignKey('fk-user-user_employee_id', '{{%user}}');
        
        $this->dropTable('{{%departments}}');
        $this->dropTable('{{%posts}}');
        $this->dropTable('{{%employees}}');

    }

}
