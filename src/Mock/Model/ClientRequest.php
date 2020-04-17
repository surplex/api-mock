<?php
namespace Srplx\Mock\Model;

use yii\db\ActiveRecord;

/**
 * Class StoredRequest
 * @property $session_id
 * @property $request_key
 * @property $method
 * @property $url
 * @property $headers
 * @property $body
 * @property $expires
 */
class ClientRequest extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'client_request';
    }


    /**
     * {@inheritdoc}
     */
    public function safeAttributes(): array
    {
        return [
            'session_id',
            'request_key',
            'method',
            'url',
            'headers',
            'body',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['session_id', 'request_key', 'url', 'method'], 'required'],
            [['session_id', 'request_key', 'url', 'method', 'headers', 'body'], 'safe'],
        ];
    }

    /**
     * @param int $seconds
     */
    public function setExpiration(int $seconds = 600)
    {
        $this->expires = date('Y-m-d H:i:s', time() + $seconds);
    }

    /**
     * @param string $sessionId
     * @param string $requestKey
     * @return ClientRequest|null
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function retrieveRequestBySessionId(string $sessionId, string $requestKey): ?ClientRequest
    {
        /** @var ClientRequest|null $request */
        $request = ClientRequest::find()->where(['session_id' => $sessionId, 'request_key' => $requestKey])->orderBy(['id' => 'ASC'])->one();
        if ($request !== null) {
            $request->delete();
        }
        return $request;
    }

    /**
     * @param string $sessionId
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function deleteAllRequestsBySessionId(string $sessionId)
    {
        /** @var ClientRequest[] $requests */
        $requests = ClientRequest::find()->where(['session_id' => $sessionId])->all();
        foreach ($requests as $request) {
            $request->delete();
        }
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function deleteExpiredRequests()
    {
        /** @var ClientRequest[] $requests */
        $requests = ClientRequest::find()->where(['<', 'expires', date('Y-m-d H:i:s')])->all();
        foreach ($requests as $request) {
            $request->delete();
        }
    }
}