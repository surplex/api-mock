<?php

use Srplx\Mock\Model\ClientRequest;
use Srplx\Mock\Model\Mock;
use yii\db\Migration;

/**
 * Class m200406_123035_add_request_key
 */
class m200406_123035_store_client_requests extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Mock::tableName(),
            'request_key', $this->string(255)->null()->defaultValue(null)
        );

        $this->createTable(ClientRequest::tableName(), [
            'id'          => $this->primaryKey(),
            'session_id'  => $this->string()->notNull(),
            'request_key' => $this->string(255)->notNull(),
            'method'      => $this->string(20)->notNull(),
            'url'         => $this->string(255)->notNull(),
            'headers'     => $this->text()->null(),
            'body'        => $this->text()->null(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(ClientRequest::tableName());
        $this->dropColumn(Mock::tableName(), 'request_key');
    }
}
