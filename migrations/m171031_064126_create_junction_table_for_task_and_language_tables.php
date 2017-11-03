<?php

use yii\db\Migration;

/**
 * Handles the creation of table `task_language`.
 * Has foreign keys to the tables:
 *
 * - `task`
 * - `language`
 */
class m171031_064126_create_junction_table_for_task_and_language_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('task_language', [
            'task_id' => $this->integer(),
            'language_id' => $this->integer(),
            'PRIMARY KEY(task_id, language_id)',
        ]);

        // creates index for column `task_id`
        $this->createIndex(
            'idx-task_language-task_id',
            'task_language',
            'task_id'
        );

        // add foreign key for table `task`
        $this->addForeignKey(
            'fk-task_language-task_id',
            'task_language',
            'task_id',
            'task',
            'id',
            'CASCADE'
        );

        // creates index for column `language_id`
        $this->createIndex(
            'idx-task_language-language_id',
            'task_language',
            'language_id'
        );

        // add foreign key for table `language`
        $this->addForeignKey(
            'fk-task_language-language_id',
            'task_language',
            'language_id',
            'language',
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
            'fk-task_language-task_id',
            'task_language'
        );

        // drops index for column `task_id`
        $this->dropIndex(
            'idx-task_language-task_id',
            'task_language'
        );

        // drops foreign key for table `language`
        $this->dropForeignKey(
            'fk-task_language-language_id',
            'task_language'
        );

        // drops index for column `language_id`
        $this->dropIndex(
            'idx-task_language-language_id',
            'task_language'
        );

        $this->dropTable('task_language');
    }
}
