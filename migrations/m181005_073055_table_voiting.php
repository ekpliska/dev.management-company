<?php

    use yii\db\Migration;
    use yii\db\Expression;

/**
 * Голосование
 * Вопросы
 * Ответы
 */
class m181005_073055_table_voiting extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        
        $table_option = null;
        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%voiting}}', [
            'voiting_id' => $this->primaryKey(),
            'voiting_type' => $this->tinyInteger()->notNull(),
            'voiting_title' => $this->string(255)->notNull(),
            'voiting_text' => $this->text(10000)->notNull(),
            'voiting_date_start' => $this->timestamp()->defaultValue(new Expression("NOW()")),
            'voiting_date_end' => $this->timestamp()->defaultValue(new Expression("NOW()")),
            'voiting_object' => $this->integer()->notNull(),
            'status' => $this->tinyInteger()->defaultValue(true),
            'created_at' => $this->timestamp()->defaultValue(new Expression("NOW()")),
            'updated_at' => $this->timestamp()->defaultValue(new Expression("NOW()")),
            'voiting_user_id' => $this->integer()->notNull(),
        ]);
        $this->createIndex('idx-voiting-voiting_id', '{{%voiting}}', 'voiting_id');
        
        $this->createTable('{{%questions}}', [
            'questions_id' => $this->primaryKey(),
            'questions_voiting_id' => $this->integer()->notNull(),
            'questions_text' => $this->text(1000)->notNull(),
            'questions_user_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultValue(new Expression("NOW()")),
            'updated_at' => $this->timestamp()->defaultValue(new Expression("NOW()")),
        ]);
        $this->createIndex('idx-questions-questions_id', '{{%questions}}', 'questions_id');
        
        $this->createTable('{{%answers}}', [
            'answers_id' => $this->primaryKey(),
            'answers_questions_id' => $this->integer()->notNull(),
            'answers_vote' => $this->tinyInteger()->notNull(),
            'answers_user_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultValue(new Expression("NOW()")),
        ]);
        $this->createIndex('idx-answers-answers_id', '{{%answers}}', 'answers_id');
        
        $this->addForeignKey(
                'fk-questions-questions_voiting_id', 
                '{{%questions}}', 
                'questions_voiting_id', 
                '{{%voiting}}', 
                'voiting_id', 
                'CASCADE',
                'CASCADE'
        );
        
        $this->addForeignKey(
                'fk-answers-answers_questions_id', 
                '{{%answers}}', 
                'answers_questions_id', 
                '{{%questions}}', 
                'questions_id', 
                'CASCADE',
                'CASCADE'
        );
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-voiting-voiting_id', '{{%voiting}}');
        $this->dropIndex('idx-questions-questions_id', '{{%questions}}');
        $this->dropIndex('idx-answers-answers_id', '{{%answers}}');
        
        $this->dropForeignKey('fk-questions-questions_voiting_id', '{{%questions}}');
        $this->dropForeignKey('fk-answers-answers_questions_id', '{{%answers}}');

        $this->dropTable('{{%answers}}');
        $this->dropTable('{{%questions}}');
        $this->dropTable('{{%voiting}}');
    }

 }
