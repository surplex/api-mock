<?php

namespace Srplx\Mock\Interfaces;

interface MockResponseInterface
{
    /**
     * Get the HTTP Status Code
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * Get the HTTP Response Body
     * @return array
     */
    public function getBody(): array;

    /**
     * Get the HTTP Response Headers
     * @return array
     */
    public function getHeaders(): array;
}
