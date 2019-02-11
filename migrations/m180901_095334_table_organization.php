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

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%organizations}}');
    }

}
