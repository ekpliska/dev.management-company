<?php

    use yii\db\Migration;

/**
 * Уведомления
 */
class m190307_090202_table_notifications extends Migration {
    public function safeUp() {
        
        $table_options = null;
        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%table_notifications}}', [
            'id' => $this->primaryKey(),
            'user_uid' => $this->integer()->notNull(),
            'type_notification' => $this->string(70)->notNull(),
            'message' => $this->string(255)->notNull(),
            'value_1' => $this->string(255)->notNull(),
            'value_2' => $this->string(255)->notNull(),
            'value_3' => $this->string(255)->notNull(),
        ], $table_options);
        
        $this->addForeignKey(
                'fk-table_notifications-user_uid', 
                '{{%table_notifications}}', 
                'user_uid', 
                '{{%user}}', 
                'user_id', 
                'CASCADE',
                'CASCADE'
        );
                
    }

    public function safeDown() {
        
        $this->dropForeignKey('fk-table_notifications-user_uid', '{{%table_notifications}}');
        $this->dropTable('{{%table_notifications}}');
        
    }
}
