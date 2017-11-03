<?php

use yii\db\Migration;

/**
 * Handles the creation of table `execution`.
 * Has foreign keys to the tables:
 *
 * - `task`
 * - `user`
 */
class m171017_100010_create_execution_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('execution', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'text' => $this->text()->notNull(),
            'count_word' => $this->integer(),
            'time' => $this->integer(),
        ]);

        // creates index for column `task_id`
        $this->createIndex(
            'idx-execution-task_id',
            'execution',
            'task_id'
        );

        // add foreign key for table `task`
        $this->addForeignKey(
            'fk-execution-task_id',
            'execution',
            'task_id',
            'task',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            'idx-execution-user_id',
            'execution',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-execution-user_id',
            'execution',
            'user_id',
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
        // drops foreign key for table `task`
        $this->dropForeignKey(
            'fk-execution-task_id',
            'execution'
        );

        // drops index for column `task_id`
        $this->dropIndex(
            'idx-execution-task_id',
            'execution'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-execution-user_id',
            'execution'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-execution-user_id',
            'execution'
        );

        $this->dropTable('execution');
    }
}
