<?php

use yii\db\Migration;

/**
 * Handles adding status to table `execution`.
 */
class m171106_053355_add_status_column_to_execution_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('execution', 'status', $this->boolean());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('execution', 'status');
    }
}
