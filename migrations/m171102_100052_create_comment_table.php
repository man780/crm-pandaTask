<?php

use yii\db\Migration;

/**
 * Handles the creation of table `comment`.
 * Has foreign keys to the tables:
 *
 * - `user`
 * - `task`
 * - `comment`
 */
class m171102_100052_create_comment_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('comment', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'task_id' => $this->integer()->defaultValue(1),
            'parent_id' => $this->integer(),
            'title' => $this->string(),
            'body' => $this->text(),
            'status' => $this->integer(),
            'dcreated' => $this->integer(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-comment-user_id',
            'comment',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-comment-user_id',
            'comment',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        // creates index for column `task_id`
        $this->createIndex(
            'idx-comment-task_id',
            'comment',
            'task_id'
        );

        // add foreign key for table `task`
        $this->addForeignKey(
            'fk-comment-task_id',
            'comment',
            'task_id',
            'task',
            'id',
            'CASCADE'
        );

        // creates index for column `parent_id`
        $this->createIndex(
            'idx-comment-parent_id',
            'comment',
            'parent_id'
        );

        // add foreign key for table `comment`
        $this->addForeignKey(
            'fk-comment-parent_id',
            'comment',
            'parent_id',
            'comment',
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
            'fk-comment-user_id',
            'comment'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-comment-user_id',
            'comment'
        );

        // drops foreign key for table `task`
        $this->dropForeignKey(
            'fk-comment-task_id',
            'comment'
        );

        // drops index for column `task_id`
        $this->dropIndex(
            'idx-comment-task_id',
            'comment'
        );

        // drops foreign key for table `comment`
        $this->dropForeignKey(
            'fk-comment-parent_id',
            'comment'
        );

        // drops index for column `parent_id`
        $this->dropIndex(
            'idx-comment-parent_id',
            'comment'
        );

        $this->dropTable('comment');
    }
}
