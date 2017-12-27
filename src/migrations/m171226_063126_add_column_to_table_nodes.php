<?php

use yii\db\Migration;

/**
 * Class m171226_063126_add_column_to_table_nodes
 */
class m171226_063126_add_column_to_table_nodes extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('nodes', 'model_class', 'string');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('nodes', 'model_class');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
    echo "m171226_063126_add_column_to_table_nodes cannot be reverted.\n";

    return false;
    }
    */
}
