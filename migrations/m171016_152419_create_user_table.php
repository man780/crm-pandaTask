<?php

use yii\db\Migration;
use yii\db\Schema;
/**
 * Handles the creation of table `user`.
 */
class m171016_152419_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user', [
            'id' => Schema::TYPE_PK,
            'username' => Schema::TYPE_STRING.' NOT NULL',
            'email' => Schema::TYPE_STRING.' NOT NULL',
			'avatar' => Schema::TYPE_STRING,
			'name' => Schema::TYPE_STRING.'(32)',
            'birthday' => Schema::TYPE_INTEGER,
			'branch_id' => Schema::TYPE_INTEGER,
			'skype' => Schema::TYPE_STRING.'(32)',
			'phone' => Schema::TYPE_STRING.'(32)',
			'telegramm' => Schema::TYPE_STRING.'(32)',
			'password_hash' => Schema::TYPE_STRING.' NOT NULL',
            'status' => Schema::TYPE_SMALLINT.' NOT NULL',
			'role' => Schema::TYPE_INTEGER,
            'auth_key' => Schema::TYPE_STRING.'(32) NOT NULL',
			'secret_key' => Schema::TYPE_STRING,
            'created_at' => Schema::TYPE_INTEGER.' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER.' NOT NULL',
        ]);
		//$this->addForeignKey('branch', 'branch_id', 'branch', 'id', 'cascade', 'cascade');
		// creates index for column `task_id`
        $this->createIndex(
            'idx-branch-branch_id',
            'user',
            'branch_id'
        );

        // add foreign key for table `task`
        $this->addForeignKey(
            'fk-user-branch_id',
            'user',
            'branch_id',
            'branch',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
		//$this->dropForeignKey('branch');
		// drops foreign key for table `task`
        $this->dropForeignKey(
            'fk-user-branch_id',
            'user'
        );
        $this->dropTable('user');
    }
}
