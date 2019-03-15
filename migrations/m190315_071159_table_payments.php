<?php

    use yii\db\Migration;
    use yii\db\Expression;
    use app\models\Payments;

/**
 * Платежи
 */
class m190315_071159_table_payments extends Migration {
    
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        
        $table_option = null;
        if ($this->db->driverName === 'mysql') {
            $table_option = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%payments}}', [
            'id_payment' => $this->primaryKey(),
            'unique_number' => $this->string(255)->notNull(),
            'receipt_period' => $this->string(25)->notNull(),
            'receipt_number' => $this->string(70)->notNull(),
            'payment_sum' => $this->decimal(10,2)->notNull(),
            'payment_status' => $this->string(25)->defaultValue(Payments::NOT_PAID),
            'account_uid' => $this->integer()->notNull(),
            'user_uid' => $this->integer()->notNull(),
            'create_at' => $this->timestamp()->defaultValue(new Expression("NOW()"))
        ]);
        
        $this->createIndex('idx-payments-id_payment', '{{%payments}}', 'id_payment');
        
        $this->addForeignKey(
                'fk-payments-account_uid', 
                '{{%payments}}', 
                'account_uid', 
                '{{%personal_account}}', 
                'account_id',
                'CASCADE',
                'CASCADE'
        );
        
        $this->addForeignKey(
                'fk-payments-user_uid', 
                '{{%payments}}', 
                'user_uid', 
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
        
        $this->dropIndex('idx-payments-id_payment', '{{%payments}}');
        $this->dropForeignKey('fk-payments-account_uid', '{{%payments}}');
        $this->dropForeignKey('fk-payments-user_uid', '{{%payments}}');
        
        $this->dropTable('{{%payments}}');
        
    }

}
