<?php

    use yii\db\Migration;
    use app\models\News;

/**
 * Новости
 * Рубрика / Тип публикации
 */
class m181001_072514_table_news extends Migration
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

        $this->createTable('{{%news}}', [
            'news_id' => $this->primaryKey(),
            'news_type_rubric_id' => $this->integer()->notNull(),
            'news_title' => $this->string(255)->notNull(),
            'news_text' => $this->text(5000)->notNull(),
            'news_preview' => $this->string(255)->notNull(),
            'news_house_id' => $this->integer(),
            'news_user_id' => $this->integer()->notNull(),
            'news_status' => $this->integer()->notNull()->defaultValue(News::FOR_ALL),
            'isPrivateOffice' => $this->integer()->notNull(),
            'isSMS' => $this->tinyInteger(),
            'isEmail' => $this->tinyInteger(),
            'isPush' => $this->tinyInteger(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer(),
            'slug' => $this->string(255),
            'isAdvert' => $this->tinyInteger()->defaultValue(0),
        ]);
        $this->createIndex('idx-news-news_id', '{{%news}}', 'news_id');
        
        $this->createTable('{{%rubrics}}', [
            'rubrics_id' => $this->primaryKey(),
            'rubrics_name' => $this->string(170)->notNull(),
        ]);
        $this->createIndex('idx-rubrics-rubrics_id', '{{%rubrics}}', 'rubrics_id');
        
        $this->addForeignKey(
                'fk-news-news_type_rubric_id', 
                '{{%news}}', 
                'news_type_rubric_id', 
                '{{%rubrics}}', 
                'rubrics_id', 
                'RESTRICT',
                'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-news-news_id', '{{%news}}');
        $this->dropIndex('idx-rubrics-rubrics_id', '{{%rubrics}}');
        $this->dropForeignKey('fk-rubrics-rubrics_id', '{{%rubrics}}');
        $this->dropTable('{{%rubrics}}');
        $this->dropTable('{{%news}}');
    }

}
