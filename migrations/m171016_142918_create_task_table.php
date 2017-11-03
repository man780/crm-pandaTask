<?php

use yii\db\Migration;

/**
 * Handles the creation of table `task`.
 */
class m171016_142918_create_task_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('task', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'body' => $this->text(),
            'priority' => $this->integer(),
            'deadline' => $this->integer(),
            'to' => $this->integer(),
            'to_copywriter_type' => $this->integer(),
            'to_copywriter_scope' => $this->integer(),
            'to_copywriter_theme' => $this->integer(),
            'to_copywriter_text' => $this->text(),
            'to_copywriter_special' => $this->string(),
            'to_translator_languages' => $this->string(),
            'to_developer_type' => $this->integer(),
            'to_developer_status' => $this->string(),
            'shown_by_executor' => $this->integer(),
			'time' => $this->integer(),
			'status' => $this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('task');
    }
}
