<?php

declare(strict_types=1);

namespace TeamA\Lock\Interfaces;

interface PingConnectionInterface
{
    public function ping(): bool;
}