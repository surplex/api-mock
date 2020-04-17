<?php

namespace Srplx\Mock\Response;

use Srplx\Mock\Interfaces\MockResponseInterface;

class NoContentResponse implements MockResponseInterface
{
    /**
     * {@inheritdoc}
     */
    public function getStatusCode(): int
    {
        return 204;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getBody(): array
    {
        return [];
    }

}
