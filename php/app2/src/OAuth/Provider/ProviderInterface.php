<?php

namespace OAuth\Provider;


interface ProviderInterface
{
    /**
     * Authenticate and return bool result of authentication
     *
     * @return bool
     */
    public function authenticate();
}