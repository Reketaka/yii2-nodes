<?php

use yii\db\Migration;

/**
 * Class m171225_040249_add_column_to_table_controllers
 */
class m171225_040249_add_column_to_table_controllers extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('nodes_controller_catalog', 'default', 'integer');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('nodes_controller_catalog', 'default');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
    echo "m171225_040249_add_column_to_table_controllers cannot be reverted.\n";

    return false;
    }
    */
}
