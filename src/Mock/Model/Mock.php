<?php

namespace Srplx\Mock\Model;

use yii\db\ActiveRecord;

/**
 * Class Mock
 * @package Srplx\Mock\Model
 * @property $status_code
 * @property $headers
 * @property $data
 * @property $order
 * @property $session_id
 * @property $request_key
 * @property $expires
 */
class Mock extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'mock';
    }

    /**
     * {@inheritdoc}
     */
    public function safeAttributes(): array
    {
        return [
            'data',
            'session_id',
            'status_code',
            'headers',
            'order',
            'request_key',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['data', 'session_id', 'status_code', 'headers'], 'required'],
            [['data', 'session_id', 'status_code', 'headers', 'order', 'request_key'], 'safe'],
            ['order', 'default', 'value' => 0]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function fields(): array
    {
        return [
            'data',
            'session_id',
            'status_code',
            'headers',
            'order',
            'request_key',
        ];
    }

    /**
     * Populates the model with given data.
     * @param array $data
     * @param null $formName
     * @return bool
     */
    public function load($data, $formName = null): bool
    {
        foreach ($data as $key => $value) {
            if (!in_array($key, $this->fields())) {
                continue;
            }
            if (is_array($value)) {
                $value = json_encode($value);
            }
            $this->$key = $value;
        }
        return true;
    }

    /**
     * @param int $seconds
     */
    public function setExpiration(int $seconds = 600)
    {
        $this->expires = date('Y-m-d H:i:s', time() + $seconds);
    }

    /**
     * Return and delete mock record
     * @param string $sessionId
     * @return array|Mock|ActiveRecord|null
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function retrieveMockBySessionId(string $sessionId)
    {
        $mock = Mock::find()->where(['session_id' => $sessionId])->orderBy(['order' => SORT_ASC, 'id' => SORT_ASC])->one();
        if(!is_null($mock)) {
            $mock->delete();
        }
        return $mock;
    }

    /**
     * @param string $sessionId
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function deleteAllMocksBySessionId(string $sessionId)
    {
        /** @var Mock[] $mocks */
        $mocks = Mock::find()->where(['session_id' => $sessionId])->all();
        foreach ($mocks as $mock) {
            $mock->delete();
        }
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function deleteExpiredMocks()
    {
        /** @var Mock[] $mocks */
        $mocks = Mock::find()->where(['<', 'expires', date('Y-m-d H:i:s')])->all();
        foreach ($mocks as $mock) {
            $mock->delete();
        }
    }

    /**
     * Count of not retrieved mocks
     * @param string $sessionId
     * @return int
     */
    public static function getCountBySessionId(string $sessionId): int
    {
        return count(Mock::find()->where(['session_id' => $sessionId])->asArray(true)->all());
    }

    /**
     * Loads default values that allows to return success response
     * @param bool $skipItSelf
     * @return $this|ActiveRecord
     */
    public function loadDefaultValues($skipItSelf = true): Mock
    {
        $this->headers = [
            'Content-Type' => 'application/json'
        ];
        $this->data = [
            'success' => true,
            'error' => null
        ];
        $this->status_code = 200;
        return $this;
    }
    
}
