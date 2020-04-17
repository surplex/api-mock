<?php

use yii\db\Migration;

/**
 * Handles the creation of table mock.
 */
class m190311_141843_create_mock_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(\Srplx\Mock\Model\Mock::tableName(), [
            'id' => $this->primaryKey(),
            'session_id' => $this->string()->notNull(),
            'data' => $this->text()->notNull(),
            'status_code' => $this->integer()->defaultValue(200),
            'headers' => $this->text()->notNull(),
            'order' => $this->integer()->defaultValue(0)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(\Srplx\Mock\Model\Mock::tableName());
    }
}
