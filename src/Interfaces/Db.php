<?php

namespace TeamA\Lock\Interfaces;

interface Db extends PingConnectionInterface
{
    public const NO_TIMEOUT =  0;
    public const INFINITY_TIMEOUT = -1;
}
