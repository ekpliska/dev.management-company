<?php

use yii\db\Migration;

/**
 * Усуги
 */
class m180901_115120_table_services extends Migration
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

        // Категория услуги
        $this->createTable('{{%category_services}}', [
            'category_id' => $this->primaryKey(),
            'category_name' => $this->string(255)->notNull(),
        ], $table_options);
        $this->createIndex('idx-category_services-category_id', '{{%category_services}}', 'category_id');
        $this->batchInsert('{{%category_services}}', 
                ['category_id', 'category_name'], 
                [
                    ['1', 'Сантехнические работы'],
                    ['2', 'Замена полотенцесушителя'],
                    ['3', 'Замена радиаторов отопления'],
                    ['4', 'Услуги электрика'],
                    ['5', 'Подключение бытовой техники'],
                    ['6', 'Приборы учета'],
                    ['7', 'Клининг'],
                    ['8', 'Сдача квартиры в аренду'],
                    ['9', 'Страхование'],
                    ['10', 'Домофон'],
                    ['11', 'Системы видеонаблюдения'],
                    ['12', 'Копицентр'],
                    ['13', 'Доставка еды'],
                    ['14', 'Аптека'],
                    ['15', 'Доставка продуктов'],
                    ['16', 'Такси'],
                    ['17', 'Доставка воды'],
                    ['18', 'Дизайн и ремонт квартир'],
                ]
        );
        
        
        // Единицы измерения
        $this->createTable('{{%units}}', [
            'units_id' => $this->primaryKey(),
            'units_name' => $this->string(100)->notNull(),
        ], $table_options);
        $this->batchInsert('{{%units}}', 
                ['units_id', 'units_name'], [
                    ['1', 'Гкал'],
                    ['2', 'ГДж'],
                    ['3', 'МВт*ч'],
                    ['4', 'КВт*ч'],
                    ['5', 'Кв. метр'],
                    ['6', 'Куб. метр'],
                    ['7', 'Шт']
                ]
        );
        
        // Услуга
        $this->createTable('{{%services}}', [
            'service_id' => $this->primaryKey(),
            'service_category_id' => $this->integer()->notNull(),
            'service_name' => $this->string(100)->notNull(),
            'service_unit_id' => $this->integer()->notNull(),
            'service_price' => $this->decimal(10,2),
            'service_description' => $this->text(1000),
            'service_image' => $this->string(255),
        ], $table_options);
        $this->createIndex('idx-services-service_id', '{{%services}}', 'service_id');
        $this->batchInsert('{{%services}}', 
                ['service_category_id', 'service_name', 'service_unit_id', 'service_price', 'service_description', 'service_image'], [
                    ['1', 'Установка/Замена беде', '7', null, null, null],
                    ['1', 'Установка/Замена ванны', '7', null, null, null],
                    ['1', 'Установка/Замена сифона', '7', null, null, null],
                    ['1', 'Установка/Замена смесителя для раковины', '7', null, null, null],
                    ['1', 'Установка/Замена унитаза', '7', null, null, null],
                    ['1', 'Установка/Замена шарового крана', '7', null, null, null],
                    ['1', 'Установка/Замена фильтр тонкой очистки', '7', null, null, null],
                    ['1', 'Установка/Замена фильтр грубой очистки', '7', null, null, null],
                    ['1', 'Установка/Замена душевой кабины', '7', null, null, null],
                    ['1', 'Установка инсталляции унитаза', '7', null, null, null],
                    ['1', 'Прочистка фильтра', '7', null, null, null],
                    ['1', 'Штробление стен', '7', null, null, null],
                    ['1', 'Трубы', '7', null, null, null],
                    ['2', 'Установка/Замена полотенцесушителя в подготовленное место', '7', null, null, null],
                    ['2', 'Замена сгонов полотенцесушителя', '7', null, null, null],
                    ['3', 'Установка/Замена радиаторов отопления', '7', null, null, null],
                    ['3', 'Установка/Замена двух радиаторов отопления', '7', null, null, null],
                    ['4', 'Установка/Замена люстры', '7', null, null, null],
                    ['4', 'Установка/Замена розетки', '7', null, null, null],
                    ['4', 'Установка/Замена выключателя', '7', null, null, null],
                    ['4', 'Установка/Замена автоматического выключателя или автомата/УЗО', '7', null, null, null],
                    ['4', 'Поиск и устранение короткого замыкания', '7', null, null, null],
                    ['4', 'Укладка кабеля', '7', null, null, null],
                    ['5', 'Подключение электроплиты', '7', null, null, null],
                    ['5', 'Установка духового шкафа', '7', null, null, null],
                    ['5', 'Подключение стиральной/посудомоечной машины', '7', null, null, null],
                    ['6', 'Поверка прибора учета', '7', null, null, null],
                    ['6', 'Замена ИПУ под ключ', '7', null, null, null],
                    ['6', 'Замена комплекта ИПУ', '7', null, null, null],
                    ['7', 'Генеральная уборка', '7', null, null, null],
                    ['7', 'Уборка после ремонта', '7', null, null, null],
                    ['7', 'Поддерживающая уборка', '7', null, null, null],
                    ['8', 'Подготовка и управление квартирой в сдаче', '7', null, null, null],
                    ['9', 'Страхование нежилых помещений', '7', null, null, null],
                    ['9', 'Страхование квартиры', '7', null, null, null],
                    ['9', 'Страхование жизни', '7', null, null, null],
                    ['9', 'Страхование выезжающих за рубеж', '7', null, null, null],
                    ['10', 'Подключение домофона с прокладкой кабельных линий', '7', null, null, null],
                    ['10', 'Подключение домофона без  прокладкой кабельных линий', '7', null, null, null],
                    ['10', 'Изготовление  ключей домофона', '7', null, null, null],
                    ['10', 'Домофоны', '7', null, null, null],
                    ['11', 'Установка  видеокамер внутри квартиры', '7', null, null, null],
                    ['11', 'Установка  видеокамер  на этаже', '7', null, null, null],
                    ['12', 'Печать фото', '7', null, null, null],
                    ['12', 'Печать документов чёрно-белая', '7', null, null, null],
                    ['12', 'Печать документов цветная', '7', null, null, null],
                    ['12', 'Изготовление печатей', '7', null, null, null],
                    ['12', 'Брошюровка', '7', null, null, null],
                    ['12', 'Изготовление календарей', '7', null, null, null],
                    ['12', 'Изготовление визиток', '7', null, null, null],
                    ['13', 'Доставка еды', '7', null, null, null],
                    ['14', 'Аптека', '7', null, null, null],
                    ['15', 'Доставка продуктов', '7', null, null, null],
                    ['16', 'Такси', '7', null, null, null],
                    ['17', 'Доставка воды', '7', null, null, null],
                    ['18', 'Дизайн и ремонт квартир', '7', null, null, null],
        ]);
        
        
        $this->addForeignKey(
                'fk-services-service_category_id', 
                '{{%services}}', 
                'service_category_id', 
                '{{%category_services}}', 
                'category_id', 
                'CASCADE',
                'CASCADE'
        );
        
        $this->addForeignKey(
                'fk-services-service_unit_id', 
                '{{%services}}', 
                'service_unit_id', 
                '{{%units}}', 
                'units_id', 
                'CASCADE',
                'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-category_services-category_id', '{{%category_services}}');
        
        $this->dropTable('{{%category_services}}');
        $this->dropTable('{{%units}}');

        $this->dropIndex('idx-services-service_id', '{{%services}}');
        
        $this->dropForeignKey('fk-services-service_category_id', '{{%services}}');
        $this->dropForeignKey('fk-services-service_unit_id', '{{%services}}');
        
        $this->dropTable('{{%services}}');

    }

}
