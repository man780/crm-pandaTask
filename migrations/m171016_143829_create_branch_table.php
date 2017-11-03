<?php

use yii\db\Migration;

/**
 * Handles the creation of table `branch`.
 */
class m171016_143829_create_branch_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('branch', [
            'id' => $this->primaryKey(),
			'title' => $this->string()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('branch');
    }
}
