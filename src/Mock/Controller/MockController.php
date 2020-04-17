<?php

namespace Srplx\Mock\Controller;

use Srplx\Mock\Model\Mock;
use Srplx\Mock\Model\ClientRequest;
use Srplx\Mock\Service\ExpectationService;
use Srplx\Mock\Service\ReservedUrlService;
use Srplx\Mock\Service\ResponseService;
use Srplx\Mock\Service\SessionService;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class MockController extends Controller
{
    /** @var bool */
    public $enableCsrfValidation = false;
    /** @var SessionService */
    public $sessionService;
    /** @var ResponseService */
    public $responseService;

    /**
     * {@inheritdoc}
     */
    public function beforeAction($action): bool
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        try {
            $this->sessionService = \Yii::$container->get(SessionService::class);
            $this->responseService = \Yii::$container->get(ResponseService::class);
        } catch (\Throwable $exception) {
            return false;
        }
        return true;
    }

    /**
     * Creates and saves a mock in the database.
     * @throws BadRequestHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate(): void
    {
        $response = \Yii::$app->getResponse();
        $response->setStatusCode(201);
        $response->content = $this->sessionService->getSession();
        $mock = new Mock();
        $mock->session_id = $this->sessionService->getSession();
        $mock->setExpiration();
        if (!$mock->load(\Yii::$app->getRequest()->getBodyParams()) || !$mock->validate()) {
            throw new BadRequestHttpException();
        }
        $mock->save();
        $response->send();
    }

    /**
     * Sends a mock response to client
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionResponse(): void
    {
        $sessionId = $this->sessionService->getSession();
        $mock = Mock::retrieveMockBySessionId($sessionId);
        if (is_null($mock)) {
            $reservedUrlService = \Yii::createObject(ReservedUrlService::class);
           // call_user_func($reservedUrlService->getUrls()[0]->getCallback(),\Yii::$app->getRequest(), \Yii::$app->getResponse());
            if ($reservedUrlService->isReservedRequest(\Yii::$app->getRequest())) {
                $reservedUrlService->handle(\Yii::$app->getRequest(), \Yii::$app->getResponse());
                return;
            }
            $mock = new Mock();
            $mock->loadDefaultValues();
        } elseif ($mock->request_key != null) {
            $request = \Yii::$app->getRequest();
            $clientRequest = new ClientRequest();
            $clientRequest->session_id = $sessionId;
            $clientRequest->setExpiration();
            $clientRequest->request_key = $mock->request_key;
            $clientRequest->method = $request->method;
            $clientRequest->url = $request->url;
            $clientRequest->headers = json_encode($request->getHeaders()->toArray());
            $clientRequest->body = $request->getRawBody();
            $clientRequest->save();
        }
        \Yii::$app->getResponse()->format = Response::FORMAT_RAW;
        $this->responseService->createResponse(\Yii::$app->getResponse(), $mock)->send();
    }

    /**
     * Sends a response with a count of not retrieved mocks to client
     * @return int
     * @throws \Exception
     */
    public function actionCount(): int
    {
        $this->cleanup();
        return Mock::getCountBySessionId($this->sessionService->getSession());
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionClearSession(): void
    {
        $sessionId = $this->sessionService->getSession();
        Mock::deleteAllMocksBySessionId($sessionId);
        ClientRequest::deleteAllRequestsBySessionId($sessionId);
        $this->cleanup();

        $response = \Yii::$app->getResponse();
        $response->setStatusCode(204);
        $response->send();
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionGetClientRequest(): void
    {
        $sessionId = $this->sessionService->getSession();
        $clientRequest = ClientRequest::retrieveRequestBySessionId($sessionId, \Yii::$app->getRequest()->getQueryParam('request_key'));

        $response = \Yii::$app->getResponse();
        if ($clientRequest !== null) {
            $response->content = json_encode([
                'method'  => $clientRequest->method,
                'url'     => $clientRequest->url,
                'headers' => json_decode($clientRequest->headers, true),
                'body'    => $clientRequest->body,
            ]);
        } else {
            $response->setStatusCode(404);
        }
        $response->send();
    }

    /**
     * Deletes all expired mocks and requests (no matter which session)
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    private function cleanup()
    {
       Mock::deleteExpiredMocks();
       ClientRequest::deleteExpiredRequests();
    }
}
