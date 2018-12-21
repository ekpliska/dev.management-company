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
            'd_description' => $this->string(255),
        ]);
        $this->createIndex('idx-departments-department_id', '{{%departments}}', 'department_id');
        
        // Должность
        $this->createTable('{{%posts}}', [
            'post_id' => $this->primaryKey(),
            'post_name' => $this->string(100)->notNull(),
            'posts_department_id' => $this->integer()->notNull(),
            'p_description' => $this->string(255),            
        ]);
        $this->createIndex('idx-posts-post_id', '{{%posts}}', 'post_id');
        
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
        
        $this->dropTable('{{%departments}}');
        $this->dropTable('{{%posts}}');
        $this->dropTable('{{%employees}}');

    }

}
