<?php

use yii\db\Migration;

/**
 * Class m190315_071159_table_payments
 */
class m190315_071159_table_payments extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190315_071159_table_payments cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190315_071159_table_payments cannot be reverted.\n";

        return false;
    }
    */
}
