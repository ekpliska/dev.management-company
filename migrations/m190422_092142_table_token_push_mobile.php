<?php

    use yii\db\Migration;

/**
 * Токены для расслыки PUSH-уведомления
 */
class m190422_092142_table_token_push_mobile extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table_options = null;
        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%token_push_mobile}}', [
            'id' => $this->primaryKey(),
            'user_uid' => $this->integer()->notNull(),
            'token' => $this->string()->notNull()->unique(),
            'status' => $this->tinyInteger()->defaultValue(1),
        ], $table_options);
        
        $this->createIndex('idx-token_push_mobile-user_uid', '{{%token_push_mobile}}', 'user_uid');
        $this->addForeignKey('fk-token_push_mobile-user_uid', 
                '{{%token_push_mobile}}', 
                'user_uid', 
                '{{%user}}', 
                'user_id', 
                'CASCADE', 
                'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-token_push_mobile-user_uid', '{{%token_push_mobile}}');
        $this->dropForeignKey('fk-token_push_mobile-user_uid', '{{%token_push_mobile}}');
        
        $this->dropTable('{{%token_push_mobile}}');
    }
    
}
