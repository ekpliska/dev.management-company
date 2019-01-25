<?php

use yii\db\Migration;

/**
 * Вопросы для типа завок (Оценка завершенных заявок)
 */
class m190125_100008_table_request_questions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp() {

        $table_options = null;
        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%request_questions}}', [
            'question_id' => $this->primaryKey(),
            'type_request_id' => $this->integer()->notNull(),
            'question_text' => $this->string(255)->notNull(),
        ], $table_options);
        
        $this->createIndex('idx-request_questions-question_id', '{{%request_questions}}', 'question_id');
        
        $this->addForeignKey(
                'fk-request_questions-type_request_id', 
                '{{%request_questions}}', 
                'type_request_id', 
                '{{%type_requests}}', 
                'type_requests_id', 
                'CASCADE',
                'CASCADE'
        );
        
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        
        $this->dropIndex('idx-request_questions-question_id', '{{%request_questions}}');
        $this->dropForeignKey('fk-request_questions-type_request_id', '{{%request_questions}}');
        
        $this->dropTable('{{%request_questions}}');
        
    }

}
