<?php

use yii\db\Migration;

/**
 * Class m180901_131671_table_flat_fk
 */
class m180901_131671_table_flat_fk extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey(
                'fk-flats-flats_account_id', 
                '{{%flats}}', 
                'flats_account_id', 
                '{{%personal_account}}', 
                'account_id', 
                'CASCADE',
                'CASCADE'
        );        

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180901_131671_table_house_fk cannot be reverted.\n";

        return false;
    }

}
