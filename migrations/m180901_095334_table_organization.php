<?php

    use yii\db\Migration;

/**
 * Организация
 */
class m180901_095334_table_organization extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        
        $table_options = null;
        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%organizations}}', [
            'organizations_id' => $this->primaryKey(),
            'organizations_name' => $this->string(100)->notNull(),
            'email' => $this->string(150)->notNull(),
            'phone' => $this->string(150)->notNull(),
            'dispatcher_phone' => $this->string(150)->notNull(),
            'postcode' => $this->integer()->notNull(),
            'town' => $this->string(100)->notNull(),
            'street' => $this->string(100)->notNull(),
            'house' => $this->string(40)->notNull(),
            'time_to_work' => $this->string(255)->notNull(),
            'inn' => $this->string(50)->notNull(),
            'kpp' => $this->string(50)->notNull(),
            'checking_account' => $this->string(50)->notNull(),
            'ks' => $this->string(50)->notNull(),
            'bic' => $this->string(50)->notNull(),
        ], $table_options);
        
        $this->createIndex('idx-organizations-organizations_id', '{{%organizations}}', 'organizations_id');
        
        // Реквизиты компании
        $this->batchInsert('{{%organizations}}', 
                [
                    'organizations_id', 'organizations_name', 'email', 'phone', 'dispatcher_phone',
                    'postcode', 'town', 'street', 'house', 
                    'time_to_work', 
                    'inn', 'kpp',
                    'checking_account', 'ks', 'bic'], 
                [
                    [
                        '1', 'Управляющая компания', 'email@email.com', '+7 (000) 000-00-00', '8 000 000 00 00',
                        '123456', 'Town', 'Street', 'House', 
                        'Понедельник-Четверг: 00:00 - 00:00; Пятница: 00:00 - 00:00; Обед - 00:00 - 00:00', 
                        '1234567890', '1234567890', 
                        '1234567890', '1234567890', '1234567890'
                    ]
                ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%organizations}}');
    }

}
