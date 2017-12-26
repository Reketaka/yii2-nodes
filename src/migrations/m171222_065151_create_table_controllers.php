<?php

use yii\db\Migration;

/**
 * Class m171222_065151_create_table_controllers
 */
class m171222_065151_create_table_controllers extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        if ($this->db->getDriverName() == 'mysql') {
            $options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('nodes_controller_catalog', [
            'id'=>$this->primaryKey(),
            'path'=>$this->string()->defaultValue(null)->comment('Путь до контроллера и его метода')
        ], $options);

        $this->addColumn('nodes', 'controller_id', 'integer');
        $this->dropColumn('nodes', 'controller_method');

        $this->createIndex('idx-nodes-controller_id', 'nodes', 'controller_id');
        $this->addForeignKey('nodes-controller_id-nodes_controller_catalog-id', 'nodes', 'controller_id', 'nodes_controller_catalog', 'id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('nodes-controller_id-nodes_controller_catalog-id', 'nodes');
        $this->dropIndex('idx-nodes-controller_id', 'nodes');

        $this->addColumn('nodes', 'controller_method', 'string');
        $this->dropColumn('nodes', 'controller_id');

        $this->dropTable('nodes_controller_catalog');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
    echo "m171222_065151_create_table_controllers cannot be reverted.\n";

    return false;
    }
    */
}
