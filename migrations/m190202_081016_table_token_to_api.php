<?php

    use yii\db\Migration;
    
/*
 * Авторизация пользователей по токену (для мобильных устройств)
 */    
class m190202_081016_table_token_to_api extends Migration {
    
    public function safeUp() {
        
        $table_options = null;
        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%token}}', [
            'id' => $this->primaryKey(),
            'user_uid' => $this->integer()->notNull(),
            'token' => $this->string()->notNull()->unique(),
            'expired_at' => $this->integer()->notNull(),
        ], $table_options);
        
        $this->createIndex('idx-token-user_uid', '{{%token}}', 'user_uid');
        $this->addForeignKey('fk-token-user_uid', 
                '{{%token}}', 
                'user_uid', 
                '{{%user}}', 
                'user_id', 
                'CASCADE', 
                'RESTRICT');
    }
    
    public function safeDown() {
        
        $this->dropIndex('idx-token-user_uid', '{{%token}}');
        $this->dropForeignKey('fk-token-user_uid', '{{%token}}');
        
        $this->dropTable('{{%token}}');
    }
}
