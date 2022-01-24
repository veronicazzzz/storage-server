<?php

namespace App\Service;

class EnvService
{
    /**
     * @var string
     */
    private $appEnv;

    /**
     * EnvService constructor.
     *
     * @param string $appEnv
     */
    public function __construct(string $appEnv)
    {
        $this->appEnv = $appEnv;
    }

    /**
     * @return string
     */
    public function getAppEnv(): string
    {
        return $this->appEnv;
    }
}
