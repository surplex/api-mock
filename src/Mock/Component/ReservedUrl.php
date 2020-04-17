<?php

namespace Srplx\Mock\Component;

class ReservedUrl
{
    /** @var string */
    protected $regex;
    /** @var callable */
    protected $callback;

    public function __construct($regex, $callback)
    {
        $this->regex = $regex;
        $this->callback = $callback;
    }

    /**
     * Returns true when the given url matches the pattern.
     * @param string $url
     *
     * @return bool
     */
    public function is(string $url): bool
    {
        return (bool) preg_match($this->regex, $url);
    }

    /**
     * Returns the registered callback
     * @return callable
     */
    public function getCallback(): callable
    {
        return $this->callback;
    }
    
}