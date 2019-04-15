<?php

    use yii\db\Migration;
    use app\models\Counters;

/**
 * Приборы учета
 */
class m180901_111400_table_counters extends Migration
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
        
        // Тип приборов учета
        $this->createTable('{{%type_counters}}', [
            'type_counters_id' => $this->primaryKey(),
            'type_counters_name' => $this->string(100)->notNull(),
            'type_counters_image' => $this->string(255),
        ], $table_options);        
        $this->createIndex('idx-type_counters-type_counters_id', '{{%type_counters}}', 'type_counters_id');
        $this->createIndex('idx-type_counters-type_counters_name', '{{%type_counters}}', 'type_counters_name');
        
        $this->batchInsert('{{%type_counters}}', 
                ['type_counters_id', 'type_counters_name', 'type_counters_image'], 
                [
                    ['1', 'Счётчик ХВС', '/images/counters/hold-water.svg'],
                    ['2', 'Счётчик ГВС', '/images/counters/hot-water.svg'],
                    ['3', 'Счётчик ЭЭ(д)', '/images/counters/electric-meter.svg'],
                    ['4', 'Счётчик ЭЭ(н)', '/images/counters/electric-meter.svg'],
                    ['5', 'Счётчик отопления', '/images/counters/heating-meter.svg'],
                    ['5', 'Распределитель тепла', '/images/counters/heat-distributor.svg'],
                ]
            );
        
        // Приборы учета собственника
        $this->createTable('{{%counters}}', [
            'counters_id' => $this->primaryKey(),
            'counters_type_id' => $this->integer()->notNull(),
            'counters_number' => $this->integer()->notNull(),
            'counters_description' => $this->string(255),
            'counters_account_id' => $this->integer()->notNull(),
            'date_check' => $this->integer()->notNull(),
            'isRequest' => $this->string(15)->defaultValue(Counters::REQUEST_NO),
            'ID_counter_clients' => $this->integer()->notNull(),
        ], $table_options);
        $this->createIndex('idx-counters-counters_id', '{{%counters}}', 'counters_id');
        $this->createIndex('idx-counters-counters_number', '{{%counters}}', 'counters_number');
                
        $this->addForeignKey(
                'fk-counters-counters_type_id', 
                '{{%counters}}', 
                'counters_type_id', 
                '{{%type_counters}}', 
                'type_counters_id', 
                'RESTRICT',
                'CASCADE'
        );
        
        $this->addForeignKey(
                'fk-counters-counters_account_id', 
                '{{%counters}}', 
                'counters_account_id', 
                '{{%personal_account}}', 
                'account_id', 
                'CASCADE',
                'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropIndex('idx-counters-counters_id', '{{%counters}}');
        $this->dropIndex('idx-counters-counters_number', '{{%counters}}');
        $this->dropForeignKey('fk-counters-counters_type_id', '{{%counters}}');
        $this->dropForeignKey('fk-counters-counters_account_id', '{{%counters}}');
        
        $this->dropIndex('idx-type_counters-type_counters_id', '{{%type_counters}}');
        $this->dropIndex('idx-type_counters-type_counters_name', '{{%type_counters}}');
        
        $this->dropTable('{{%type_counters}}');
        $this->dropTable('{{%counters}}');
    }

}
