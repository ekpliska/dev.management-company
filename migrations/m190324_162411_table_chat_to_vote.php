<?php

    use yii\db\Migration;
    use yii\db\Expression; 

/**
 * Чат голосования
 */
class m190324_162411_table_chat_to_vote extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        
        $table_option = null;
        if ($this->db->driverName === 'mysql') {
            $table_option = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%chat_to_vote}}', [
            'id' => $this->primaryKey(),
            'vote_vid' => $this->integer()->notNull(),
            'uid_user' => $this->integer()->notNull(),
            'chat_message' => $this->text(1000)->notNull(),
            'created_at' => $this->timestamp()->defaultValue(new Expression("NOW()")),
        ]);
        
        $this->createIndex('idx-chat_to_vote-vote_vid', '{{%chat_to_vote}}', 'vote_vid');
        
        $this->addForeignKey(
                'fk-chat_to_vote-vote_vid', 
                '{{%chat_to_vote}}', 
                'vote_vid', 
                '{{%voting}}', 
                'voting_id',
                'CASCADE',
                'CASCADE'
        );
        
        $this->addForeignKey(
                'fk-chat_to_vote-uid_user', 
                '{{%chat_to_vote}}', 
                'uid_user', 
                '{{%user}}', 
                'user_id',
                'CASCADE',
                'CASCADE'
        );
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m190324_162411_table_chat_to_vote cannot be reverted.\n";

        return false;
    }
    
}
