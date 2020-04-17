<?php

use Srplx\Mock\Model\ClientRequest;
use Srplx\Mock\Model\Mock;
use yii\db\Migration;

/**
 * Class m200416_065439_expire_mocks_and_requests
 */
class m200416_065439_expire_mocks_and_requests extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // No datetime data type in SQLite, so use ISO format "yyyy-mm-dd hh:ii:ss"
        $this->addColumn(Mock::tableName(), 'expires', $this->string(19)->null());
        $this->addColumn(ClientRequest::tableName(), 'expires', $this->string(19)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Mock::tableName(), 'expires');
        $this->dropColumn(ClientRequest::tableName(), 'expires');
    }
}
