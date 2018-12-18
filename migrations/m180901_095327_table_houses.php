<?php

    use yii\db\Migration;
    use app\models\Flats;

/**
 * Квартиры
 * Дома
 * Жилой комплекс
 */
class m180901_095327_table_houses extends Migration
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
        
        // Дома
        $this->createTable('{{%houses}}', [
            'houses_id' => $this->primaryKey(),
            'houses_postcode' => $this->integer()->notNull(),
            'houses_region' => $this->string(100)->notNull(),
            'houses_area' => $this->string()->notNull(),
            'houses_town' => $this->string(100)->notNull(),
            'houses_village' => $this->string(100),
            'houses_street' => $this->string(100)->notNull(),
            'houses_number_house' => $this->string(10)->notNull(),
            'houses_description' => $this->string(255)->notNull(),
            'houses_name' => $this->string(100),
        ], $table_options);
        $this->createIndex('idx-houses-houses_id', '{{%houses}}', 'houses_id');
        
        // Квартиры
        $this->createTable('{{%flats}}', [
            'flats_id' => $this->primaryKey(),
            'flats_house_id' => $this->integer()->notNull(),
            'flats_porch' => $this->integer()->notNull(),
            'flats_floor' => $this->integer()->notNull(),
            'flats_number' => $this->integer()->notNull(),
            'flats_rooms' => $this->integer()->notNull(),
            'flats_square' => $this->decimal(10,2)->notNull(),
            'status' => $this->tinyInteger()->defaultValue(Flats::STATUS_DEBTOR_NO),
        ], $table_options);
        $this->createIndex('idx-flats-flats_id', '{{%flats}}', 'flats_id');
        
        $this->addForeignKey(
                'fk-flats-flats_house_id', 
                '{{%flats}}', 
                'flats_house_id', 
                '{{%houses}}', 
                'houses_id', 
                'CASCADE',
                'CASCADE'
        );
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        
        $this->dropIndex('idx-houses-houses_id', '{{%houses}}');
        $this->dropIndex('idx-flats-flatsid', '{{%flats}}');
        
        $this->addForeignKey('fk-flats-flats_house_id', '{{%flats}}');
                
        $this->dropTable('{{%flats}}');
        $this->dropTable('{{%houses}}');
    }

}
