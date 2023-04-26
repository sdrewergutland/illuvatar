<?php

namespace App\Shared\Application\Command\Heartbeat;

use App\Shared\Application\Command\AsyncCommand;

class HeartbeatCommand implements AsyncCommand
{
    public static bool $isHandled = false;

    public function __construct(private readonly string $content)
    {
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
