<?php

    use yii\db\Migration;

/**
 * Жилой массив
 */
class m180901_103657_table_houses extends Migration
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
        
        $this->createTable('{{%houses}}', [
            'houses_id' => $this->primaryKey(),
            'houses_name' => $this->string(70)->notNull(),
            'houses_town' => $this->string(70)->notNull(),
            'houses_street' => $this->string(100)->notNull(),
            'houses_number_house' => $this->string(10)->notNull(),
            'houses_porch' => $this->integer()->notNull(),
            'houses_floor' => $this->integer()->notNull(),
            'houses_flat' => $this->integer()->notNull(),
            'houses_rooms' => $this->integer()->notNull(),
            'houses_square' => $this->integer()->notNull(),
            'houses_account_id' => $this->integer(),
        ], $table_options);
        
        $this->createIndex('idx-houses-houses_id', '{{%houses}}', 'houses_id');
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-houses-houses_id', '{{%houses}}');
        $this->dropForeignKey('fk-houses-houses_account_id', '{{%houses}}');
        $this->dropTable('{{%houses}}');
    }

}
