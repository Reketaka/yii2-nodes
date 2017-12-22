<?php

use yii\db\Migration;

/**
 * Class m171219_073308_base_tables
 */
class m171219_073308_base_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $options = null;
        if($this->db->getDriverName() == 'mysql'){
            $options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('nodes', [
            'id'=>$this->primaryKey(),
            'parent_id'=>$this->integer()->defaultValue(0)->comment('Родительская страница'),
            'alias'=>$this->string(),
            'title'=>$this->string()->defaultValue(null)->comment('Название'),
            'primary_key'=>$this->integer(),
            'controller_method'=>$this->string()->defaultValue(null)->comment('Ссылка на контроллер и его метод'),
            'default'=>$this->integer(1)->defaultValue(0)->comment('Страница по умолчанию')
        ], $options);

        $this->createIndex('idx-nodes-parent_id', 'nodes', 'parent_id');
        $this->createIndex('idx-nodes-primary_key', 'nodes', 'primary_key');




    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('nodes');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171219_073308_base_tables cannot be reverted.\n";

        return false;
    }
    */
}
