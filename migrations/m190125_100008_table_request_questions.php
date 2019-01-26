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
        
        // Вопросы к заявке
        $this->createTable('{{%request_questions}}', [
            'question_id' => $this->primaryKey(),
            'type_request_id' => $this->integer()->notNull(),
            'question_text' => $this->string(255)->notNull(),
        ], $table_options);
        $this->createIndex('idx-request_questions-question_id', '{{%request_questions}}', 'question_id');

        // Ответы на вопросы
        $this->createTable('{{%request_answers}}', [
            'answer_id' => $this->primaryKey(),
            'anwswer_question_id' => $this->integer()->notNull(),
            'anwswer_request_id' => $this->integer()->notNull(),
            'answer_value' => $this->integer()->notNull(),
        ], $table_options);
        $this->createIndex('idx-request_answers-answer_id', '{{%request_answers}}', 'answer_id');
        
        $this->addForeignKey(
                'fk-request_questions-type_request_id', 
                '{{%request_questions}}', 
                'type_request_id', 
                '{{%type_requests}}', 
                'type_requests_id', 
                'CASCADE',
                'CASCADE'
        );
        
        $this->addForeignKey(
                'fk-request_answers-anwswer_question_id', 
                '{{%request_answers}}', 
                'anwswer_question_id', 
                '{{%request_questions}}', 
                'question_id', 
                'CASCADE',
                'CASCADE'
        );
        
        $this->addForeignKey(
                'fk-request_answers-anwswer_request_id', 
                '{{%request_answers}}', 
                'anwswer_request_id', 
                '{{%paid_services}}', 
                'services_id', 
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
        
        $this->dropIndex('idx-request_answers-answer_id', '{{%request_answers}}');
        $this->dropForeignKey('fk-request_answers-anwswer_question_id', '{{%request_answers}}');
        $this->dropForeignKey('fk-request_answers-anwswer_request_id', '{{%request_answers}}');
        
        $this->dropTable('{{%request_questions}}');
        $this->dropTable('{{%request_answers}}');
        
    }

}
