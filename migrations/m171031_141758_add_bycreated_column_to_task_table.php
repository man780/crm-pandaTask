<?php

use yii\db\Migration;

/**
 * Handles adding bycreated to table `task`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m171031_141758_add_bycreated_column_to_task_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('task', 'bycreated', $this->integer());

        // creates index for column `bycreated`
        $this->createIndex(
            'idx-task-bycreated',
            'task',
            'bycreated'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-task-bycreated',
            'task',
            'bycreated',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-task-bycreated',
            'task'
        );

        // drops index for column `bycreated`
        $this->dropIndex(
            'idx-task-bycreated',
            'task'
        );

        $this->dropColumn('task', 'bycreated');
    }
}
