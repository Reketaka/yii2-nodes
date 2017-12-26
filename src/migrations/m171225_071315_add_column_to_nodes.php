<?php

use yii\db\Migration;

/**
 * Class m171225_071315_add_column_to_nodes
 */
class m171225_071315_add_column_to_nodes extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('nodes','date_create', 'datetime');
        $this->addColumn('nodes', 'date_update', 'datetime');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('nodes', 'date_create');
        $this->dropColumn('nodes', 'date_update');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
    echo "m171225_071315_add_column_to_nodes cannot be reverted.\n";

    return false;
    }
    */
}
